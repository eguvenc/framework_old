<?php

/*
|--------------------------------------------------------------------------
| Mongo Db Config 
|--------------------------------------------------------------------------
| Mongodb database api configuration file.
| 
| Prototype: 
|
|   $mongo['key'] = value;
| 
*/

$mongo['host']         = 'localhost';
$mongo['port']         = '27017';
$mongo['database']     = 'obullo';
$mongo['username']     = '';
$mongo['password']     = '';
$mongo['dsn']          = ''; // mongodb://connection_string

/*
|--------------------------------------------------------------------------
| Safe Queries
|--------------------------------------------------------------------------
| Writing speed and safety options.
| 
| Options:
|   
|   fysnc = Boolean, defaults to FALSE. Forces the insert to be synced to disk before returning success.
|   wtimewout = How long to wait for WriteConcern acknowledgement.The default value for MongoClient is 10000 milliseconds.
|   timeout = If acknowledged writes are used, this sets how long (in milliseconds) for the client to wait for a database response.
|  
|   @link http://www.php.net/manual/en/mongocollection.save.php
| 
|  Write Concerns:
|   w=0         Unacknowledged	A write will not be followed up with a GLE call, and therefore not checked ("fire and forget")
|   w=1         Acknowledged	The write will be acknowledged by the server (the primary on replica set configuration)
|   w=N         Replica Set Acknowledged	The write will be acknowledged by the primary server, and replicated to N-1 secondaries.
|   w=majority	Majority Acknowledged	The write will be acknowledged by the majority of the replica set (including the primary). This is a special reserved string.
|   w=<tag set>	Replica Set Tag Set Acknowledged	The write will be acknowledged by members of the entire tag set
|   j=true	Journaled	The write will be acknowledged by primary and the journal flushed to disk
|
|   @link http://www.php.net/manual/en/mongo.writeconcerns.php
*/
$mongo['query_safety'] = array('w' => 0, 'j' => 1);

/*
|--------------------------------------------------------------------------
| Connection Flag
|--------------------------------------------------------------------------
| If you are having connection problems try change set to TRUE.
|
| if set true db will available end of the the connection string.
| mongodb://[username:password@]/host/{dbname} 
|
*/
$mongo['dbname_flag'] = FALSE;

/* End of file mongo.php */
/* Location: .app/config/mongo.php */
