<?php
defined('BASE') or exit('Access Denied!');

/*
|--------------------------------------------------------------------------
| Mongo Db Config 
|--------------------------------------------------------------------------
| Mongodb database api configuration file.
| 
| Prototype: 
|
|   $mongodb['key'] = value;
| 
*/

$mongodb['host']         = 'localhost';
$mongodb['port']         = '27017';
$mongodb['database']     = '';
$mongodb['username']     = '';
$mongodb['password']     = '';

/*
|--------------------------------------------------------------------------
| Persistent connections
|--------------------------------------------------------------------------
|
*/
$mongodb['persist']      = FALSE;
$mongodb['persist_key']  = 'ob_mongo_persist';

/*
|--------------------------------------------------------------------------
| Safe Queries
|--------------------------------------------------------------------------
| Writing speed and safety options.
| 
| Options:
|
|   none  = Default, high speed.
|   safe  = The database has receieved and executed the query
|   fysnc = as above + the change has been committed to harddisk. 
|   ( NOTE: Will introduce a performance penalty ).
|
*/
$mongodb['query_safety'] = 'safe';

/*
|--------------------------------------------------------------------------
| Connection Flag
|--------------------------------------------------------------------------
| If you are having connection problems try change set to TRUE.
|
*/
$mongodb['timeout']      = 100;
$mongodb['host_db_flag'] = FALSE;

/* End of file mongodb.php */
/* Location: .app/config/mongodb.php */