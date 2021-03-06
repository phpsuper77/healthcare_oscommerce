<?php
/*
 * Generated configuration file
 * Generated by: phpMyAdmin 3.3.0-dev setup script by Piotr Przybylski <piotrprz@gmail.com>
 * Date: Thu, 24 Sep 2009 16:19:51 +0300
 */

/* Servers configuration */
$i = 0;

/* Server: dragon [1] */
$i++;
$cfg['Servers'][$i]['verbose'] = 'dragon';
$cfg['Servers'][$i]['host'] = 'localhost';
$cfg['Servers'][$i]['port'] = '';
$cfg['Servers'][$i]['socket'] = '';
$cfg['Servers'][$i]['connect_type'] = 'tcp';
$cfg['Servers'][$i]['extension'] = 'mysqli';
$cfg['Servers'][$i]['auth_type'] = 'config';
$cfg['Servers'][$i]['user'] = 'root';
$cfg['Servers'][$i]['password'] = '';

/* End of servers configuration */
$cfg['MaxDbList'] = 10;
$cfg['MaxTableList'] = 250;
$cfg['MaxCharactersInDisplayedSQL'] = 4000;

$cfg['DefaultLang'] = 'en-utf-8';
$cfg['ServerDefault'] = 1;
$cfg['UploadDir'] = '';
$cfg['SaveDir'] = '';
$cfg['CheckConfigurationPermissions'] = false;
$cfg['LoginCookieValidity'] = 7200;
$cfg['IgnoreMultiSubmitErrors'] = true;
$cfg['SkipLockedTables'] = true;
$cfg['LeftDisplayLogo'] = false;
$cfg['LeftPointerEnable'] = false;
$cfg['DisplayDatabasesList'] = false;
$cfg['ShowTooltip'] = false;
$cfg['ShowStats'] = false;
$cfg['ShowPhpInfo'] = true;
$cfg['ShowChgPassword'] = false;
$cfg['BrowsePointerEnable'] = false;
$cfg['CharTextareaCols'] = 60;
$cfg['CharTextareaRows'] = 15;
$cfg['LightTabs'] = false;
$cfg['SQLQuery'] = array (
  'ShowAsPHP' => false,
);
$cfg['Export'] = array (
  'compression' => 'bzip2',
  'asfile' => false,
  'file_template_table' => '__TABLE__%Y-%m-%d',
  'file_template_database' => '__DB__%Y-%m-%d',
  'file_template_server' => '__SERVER__%Y-%m-%d',
);
$cfg['blowfish_secret'] = '4abb71e2d92cc1.45790066';
?>