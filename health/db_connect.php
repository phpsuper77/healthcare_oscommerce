<?php
// define our database connection
error_reporting(E_ERROR);
ini_set('display_errors','1');

//require('includes/application_top.php');

//define('DB_DATABASE', 'amp3_kayako');
//define('DB_SERVER_PASSWORD', 'luQVr6diWR');
//define('DB_SERVER_USERNAME', 'amp3kayako');
//define('DB_SERVER', '192.168.1.69');
if (!empty($_GET['action'])) {
    switch ($_GET['action']) {
        case 'connect':
                $link = mysql_connect($_POST['db_host'], $_POST['db_user'], $_POST['db_pass']);
                if (!$link) {
                    die('Could not connect to ['.$_POST['db_host'].']: ' . mysql_error());
                }

                echo 'Connected to ['.$_POST['db_host'].'] successfully<br>';


                $db_selected = mysql_select_db($_POST['db_database'], $link);
                if (!$db_selected) {
                    die ('Can\'t use ['.$_POST['db_database'].']: ' . mysql_error());
                }

                echo 'DB ['.$_POST['db_database'].'] selected successfully<br>';

                mysql_close($link);
            break;
        case 'info':
            phpinfo();
            break;
        default:
            break;
    }   
}
?>

<hr />

<form name="db_test" action="db_connect.php?action=connect" method="post">
<label>DB HOST:<input name="db_host" value="localhost" /></label><br />
<label>DB USER:<input name="db_user" /></label><br />
<label>DB PASS:<input name="db_pass" /></label><br />
<label>DB DATABASE:<input name="db_database" /></label><br />
<button type="submit" name="test connection">test connection</button>
</form>
