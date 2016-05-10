<?php
define('XML_HEADING_TITLE', 'XML backup/restore');
define('TABLE_HEADING_RESTORE_STARTED', 'Available restore points');
define('ERROR_CATALOG_XML_DIRECTORY_NOT_READABLE', 'Error: Catalog xml backups directory is not readable: ' . DIR_FS_CATALOG_XML);
define('ERROR_CATALOG_XML_DIRECTORY_DOES_NOT_EXIST', 'Error: Catalog xml backups directory does not exist: ' . DIR_FS_CATALOG_XML);
define('TABLE_HEADING_RESTORE_ALL', 'Below there are all available restoration backups, grouped by type.<br> To restore data click on the needed backup date');
define('TEXT_INVALID_BACKUP', 'Invalid backup');
define('TEXT_DELETE_DUMP', "Delete dump from server");
define('TEXT_WAS_DELETED', " was removed from server");


define('TEXT_WRONG_DATATYPE', 'Wrong data type for backup');
define('TEXT_WRONG_DATA', 'You haven\'t select data for backup');

define('TEXT_DIR_CREATED', ' directory successfully created');
define('TEXT_CATEGORIES_BACKUP', 'starting product categories backup');
define('TEXT_CATEGORIES_COUNT', '<b>%s</b> categories and <b>%s</b> categories descriptions were dumped');
define('TEXT_PRODUCTS_ATTRIBUTES_BACKUP', 'starting products options backup');
define('TEXT_PRODUCTS_ATTRIBUTES_BACKUP_DONE', 'products options backup done');
define('TEXT_PRODUCTS_BACKUP', 'starting products backup');
define('TEXT_PRODUCTS_BACKUP_DONE', 'products backup done');

define('TEXT_STARTING_DUMP', 'Starting <b>%s</b> backup at %s');
define('TEXT_FINISHED_DUMP', 'Finished <b>%s</b> backup at %s');
define('TEXT_TABLE_BACKUP', 'Dumping <b>%s</b> table');
define('TEXT_TABLE_BACKUP_RECORD', '<b>%s</b> records from <b>%s</b> were dumped');
define('TEXT_TOTAL_BACKUP_TIME', 'Total backup time: <b>%s</b> seconds');
define('TEXT_QUERIES_EXECUTED', ' queries were executed');
define('TEXT_RESTORE_FINISHED', ' Restore has been successfully finished!');



?>