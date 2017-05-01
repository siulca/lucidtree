<?php
/**
 * The db.php file which initiates a connection to the database
 * and gives a global $db variable for access
 * @author Swashata <swashata@intechgrity.com>
 * @uses ezSQL MySQL
 */
/** edit your configuration */
$dbhost = 'localhost';
$dbname = 'documentaries';
$dbuser = 'root';
$dbpassword = 'root';

/** Stop editing from here, else you know what you are doing ;) */

/** defined the root for the db */
if(!defined('ADMIN_DB_DIR'))
    define('ADMIN_DB_DIR', dirname(__FILE__));

	// Include ezSQL core
	//include_once "/ez_sql_core.php";

	// Include ezSQL database specific component
	//include_once "/ez_sql_mysqli.php";

include_once ADMIN_DB_DIR . '/ez_sql_core.php';
include_once ADMIN_DB_DIR . '/ez_sql_mysqli.php';

global $db;
$db = new ezSQL_mysqli($dbuser, $dbpassword, $dbname, $dbhost);
