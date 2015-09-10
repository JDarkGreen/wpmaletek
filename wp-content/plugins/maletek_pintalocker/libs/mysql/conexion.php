<?php
include_once '../../../../../wp-config.php';
include_once 'Database.php';

define('WP_PREFIX',$table_prefix);

global $DB;
$DB=new Database(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
$DB->query("SET NAMES utf8"); 

