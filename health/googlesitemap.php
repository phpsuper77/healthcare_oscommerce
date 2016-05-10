<?php
/* Google Site Map by Senia
   by Senia
   ver 20.10.2005 (1.00)
   ver 21.10.2005 (1.01)
   ver 01.11.2005 (1.02)
   ver 09.02.2006 (1.03)
*/

require('includes/application_top.php');


define( 'SITE_CATALOG', preg_replace('/\/$/','',DIR_FS_CATALOG) );

chdir(SITE_CATALOG);

if (!defined('GOOGLE_SITEMAP_COMPRESS')) define('GOOGLE_SITEMAP_COMPRESS', 'false'); //Option to compress the files
if (!defined('GOOGLE_SITEMAP_PROD_CHANGE_FREQ')) define('GOOGLE_SITEMAP_PROD_CHANGE_FREQ', 'weekly'); //Option for change frequency of products
if (!defined('GOOGLE_SITEMAP_CAT_CHANGE_FREQ')) define('GOOGLE_SITEMAP_CAT_CHANGE_FREQ', 'weekly'); //Option for change frequency of categories
if (!defined('FILENAME_PREFIX')) define('FILENAME_PREFIX','sitemap'); // default filename  prefix


function SaveFile($data, $type){
    $filename = SITE_CATALOG .'/'. FILENAME_PREFIX . $type;
    $compress = defined('GOOGLE_SITEMAP_COMPRESS') ? GOOGLE_SITEMAP_COMPRESS : 'false';
    if ($type == 'index') $compress = 'false';
      switch($compress){
        case 'true':
           $filename .= '.xml.gz';
           if ($gz = gzopen($filename,'wb9')){
                  gzwrite($gz, $data);
                  gzclose($gz);
                  return true;
           } else {
                  $file_check = file_exists($filename) ? 'true' : 'false';
                  return false;
           }
      break;
      default:
          $filename .= '.xml';
            if ($fp = fopen($filename, 'w+')){
                    fwrite($fp, $data);
                    fclose($fp);
                    return true;
            } else {
                    $file_check = file_exists($filename) ? 'true' : 'false';
                    return false;
            }
            break;
    }
}

function CompressFile($file){
        $source = SITE_CATALOG . '/' . $file . '.xml';
        $filename = SITE_CATALOG . '/' . $file . '.xml.gz';
        $error_encountered = false;
        if( $gz_out = gzopen($filename, 'wb9') ){
                if($fp_in = fopen($source,'rb')){
                        while(!feof($fp_in)) gzwrite($gz_out, fread($fp_in, 1024*512));
                                fclose($fp_in);

                } else {
                        $error_encountered = true;
                }
                gzclose($gz_out);
        } else {
                $error_encountered = true;
        }
        if($error_encountered){
                return false;
        } else {
                return true;
        }
} # end function

function GenerateSitemap($data, $file){
        $content = '<?xml version="1.0" encoding="UTF-8"?'.'>' . "\n";
        $content .= '<urlset xmlns="http://www.google.com/schemas/sitemap/0.84">' . "\n";
        foreach ($data as $url){
                $content .= "\t" . '<url>' . "\n";
                $content .= "\t\t" . '<loc>'.$url['loc'].'</loc>' . "\n";
                $content .= "\t\t" . '<lastmod>'.$url['lastmod'].'</lastmod>' . "\n";
                $content .= "\t\t" . '<changefreq>'.$url['changefreq'].'</changefreq>' . "\n";
                $content .= "\t\t" . '<priority>'.$url['priority'].'</priority>' . "\n";
                $content .= "\t" . '</url>' . "\n";
        } # end foreach
        $content .= '</urlset>';
        return SaveFile($content, $file);
} # end function

function GenerateSitemapIndex(){
        $content = '<?xml version="1.0" encoding="UTF-8"?'.'>' . "\n";
        $content .= '<sitemapindex xmlns="http://www.google.com/schemas/sitemap/0.84">' . "\n";
        $pattern = defined('GOOGLE_SITEMAP_COMPRESS')? GOOGLE_SITEMAP_COMPRESS == 'true'? "{sitemap*.xml.gz}" : "{sitemap*.xml}" : "{sitemap*.xml}";
        foreach ( glob(SITE_CATALOG .'/'. $pattern, GLOB_BRACE) as $filename ) {
           if ( eregi('index', $filename) || filesize($filename)==0 ) continue;
           $content .= "\t" . '<sitemap>' . "\n";
           $content .= "\t\t" . '<loc>'.tep_href_link(basename($filename),'', 'NONSSL', false).'</loc>' . "\n";
           $content .= "\t\t" . '<lastmod>'.date ("Y-m-d", filemtime($filename)).'</lastmod>' . "\n";
           $content .= "\t" . '</sitemap>' . "\n";
        } # end foreach
        $content .= '</sitemapindex>';
        return SaveFile($content, 'index');
} # end function

function GenerateProductSitemap(){
  global $languages_id;
  //$get_products_q = tep_db_query("select p.* from ".TABLE_PRODUCTS." p left join ".TABLE_PRODUCTS_TO_CATEGORIES." p2c on p.products_id=p2c.products_id left join ".TABLE_CATEGORIES." c on p2c.categories_id=c.categories_id WHERE p.products_status='1' order by c.sort_order, p.products_ordered, c.categories_id, p.products_id");
  $get_products_q = tep_db_query("select p.* from ".TABLE_PRODUCTS." p, ".TABLE_PRODUCTS_TO_CATEGORIES." p2c, ".TABLE_CATEGORIES." c WHERE p.products_id=p2c.products_id and p2c.categories_id=c.categories_id and c.categories_status=1 and p.products_status=1 order by c.sort_order, p.products_ordered, c.categories_id, p.products_id");

        if (tep_db_num_rows($get_products_q)>0){
            $container = array();
            $number = 0;
            $top = 0;
         while($result = tep_db_fetch_array($get_products_q) ){
             $top = max($top, $result['products_ordered']);
             $location = tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $result['products_id'], 'NONSSL', false);
             if ( !empty($result['products_last_modified']) && substr($result['products_last_modified'], 0, 10)!='0000-00-00' ) {
               $lastmod = $result['products_last_modified'];
             }else{
               $lastmod = $result['products_date_added']; 
             }
             $changefreq = GOOGLE_SITEMAP_PROD_CHANGE_FREQ;
             $ratio = ($top>0?($result['products_ordered']/$top):0);
             $priority = $ratio < 0.1 ? 0.1 : number_format($ratio, 1, '.', '');

             $container[] = array('loc' => htmlspecialchars(utf8_encode($location)),
                                  'lastmod' => date ("Y-m-d", strtotime($lastmod)),
                                  'changefreq' => $changefreq,
                                  'priority' => $priority
                                 );

             if (sizeof($container) >= 50000 ){
                  $type = $number == 0 ? 'products' : 'products' . $number;
                  GenerateSitemap($container, $type);
                  $container = array();
                  $number++;
            }
         } # end while

          if ( sizeof($container) > 1 ) {
              $type = $number == 0 ? 'products' : 'products' . $number;
              return GenerateSitemap($container, $type);
          } # end if
    }
} # end function

function GSM_Cat_Path($category_id){ 
  $categories = array();
  tep_get_parent_categories($categories, $category_id);
  $categories = array_reverse($categories);
  $cPath = implode('_', $categories);

  if (tep_not_null($cPath)) $cPath .= '_';
  $cPath .= $category_id;
  return 'cPath='.$cPath; 
}

function GenerateCategorySitemap(){
   //$teg_get_category_q = tep_db_query("select * from ".TABLE_CATEGORIES." where 1  ORDER BY parent_id ASC, sort_order ASC, categories_id ASC");
     $teg_get_category_q = tep_db_query("select * from ".TABLE_CATEGORIES." where categories_status=1 ORDER BY parent_id ASC, sort_order ASC, categories_id ASC");
        if (tep_db_num_rows($teg_get_category_q)>0){
            $container = array();
            $number = 0;
        while($result = tep_db_fetch_array($teg_get_category_q)){

           $location = tep_href_link(FILENAME_DEFAULT, GSM_Cat_Path($result['categories_id']), 'NONSSL',false);
           if ( !empty($result['last_modified']) && substr($result['last_modified'], 0, 10)!='0000-00-00' ) {
             $lastmod = $result['last_modified'];
           }else{
             $lastmod = $result['date_added']; 
           }

           $changefreq = GOOGLE_SITEMAP_CAT_CHANGE_FREQ;
           $priority = 0.5;

           $container[] = array('loc' => htmlspecialchars(utf8_encode($location)),
                                'lastmod' => date ("Y-m-d", strtotime($lastmod)),
                                'changefreq' => $changefreq,
                                'priority' => $priority
                               );
           if ( sizeof($container) >= 50000 ){
                $type = $number == 0 ? 'categories' : 'categories' . $number;
                GenerateSitemap($container, $type);
                $container = array();
                $number++;
           }
       } # end while

       if ( sizeof($container) > 1 ) {
             $type = $number == 0 ? 'categories' : 'categories' . $number;
              return GenerateSitemap($container, $type);
       } # end if
  }
} # end function

function ReadGZ( $file ){
        $file = $this->savepath . $file;
        $lines = gzfile($file);
        return implode('', $lines);
} # end function


function GenerateSubmitURL(){
        $url = urlencode(HTTP_SERVER. DIR_WS_HTTP_CATALOG.FILENAME_PREFIX.'index.xml');
        return '<a target="_blank" href="'.htmlspecialchars(utf8_encode('http://www.google.com/webmasters/sitemaps/ping?sitemap=' . $url)).'">http://www.google.com/webmasters/sitemaps/ping?sitemap='.HTTP_SERVER. DIR_WS_HTTP_CATALOG.FILENAME_PREFIX.'index.xml'.'</a>';
} # end function







// if ($HTTP_GET_VARS['do']=='silent') {
 @GenerateProductSitemap();
 @GenerateCategorySitemap();
 @GenerateSitemapIndex();
/*
} else {

$submit = true;

echo '<pre>';

if (GenerateProductSitemap()){
  echo 'Generated Google Product Sitemap Successfully' . "\n\n";
} else {
  $submit = false;
  echo 'ERROR: Google Product Sitemap Generation FAILED!' . "\n\n";
}


flush();

if (GenerateCategorySitemap()){
   echo 'Generated Google Category Sitemap Successfully' . "\n\n";
} else {
   $submit = false;
   echo 'ERROR: Google Category Sitemap Generation FAILED!' . "\n\n";
}

flush();

if (GenerateSitemapIndex()){
   echo 'Generated Google Sitemap Index Successfully' . "\n\n";
} else {
   $submit = false;
   echo 'ERROR: Google Sitemap Index Generation FAILED!' . "\n\n";
}

flush();

if ($submit){
        echo 'CONGRATULATIONS! All files generated successfully.' . "\n\n";
        echo 'If you have not already submitted the sitemap index to Google click the link below.' . "\n";
        echo 'Before you do I HIGHLY recommend that you view the XML files to make sure the data is correct.' . "\n\n";
        echo  GenerateSubmitURL() . "\n\n";
        echo 'For your convenience here is the CRON command for your site:' . "\n";
        echo 'php ' . dirname($_SERVER['SCRIPT_FILENAME']) . '/googlesitemap.php' . "\n\n";
        echo 'Here is your sitemap index: ' . HTTP_SERVER. DIR_WS_HTTP_CATALOG . FILENAME_PREFIX . 'index.xml' . "\n";
        echo 'Here is your product sitemap: ' . HTTP_SERVER. DIR_WS_HTTP_CATALOG . FILENAME_PREFIX . 'products.xml' .(GOOGLE_SITEMAP_COMPRESS=='false'?'':'.gz'). "\n";
        echo 'Here is your category sitemap: ' . HTTP_SERVER. DIR_WS_HTTP_CATALOG . FILENAME_PREFIX . 'categories.xml' .(GOOGLE_SITEMAP_COMPRESS=='false'?'':'.gz'). "\n";
}
echo '</pre>';

flush();

}
*/
?>
