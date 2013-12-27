<?php
defined('STDIN') or die('Access Denied');

// @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
// @
// @ SYNC TASK RUN THE MODEL AUTO SYNC FEATURE
// @
// @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@

/**
 * $c sync
 * @var Controller
 */
$c = new Controller(function(){
    // __construct
});

/**
 * Start model auto sync
 * task
 * 
 * @param string $tablename   tablename of the database table
 * @param string $modelName   modelname
 * @param string $dbVar       database variable name
 * @param string $requestUri  urlencoded string ( current page url )
 * @param string $postData base64 encoded serialized string $_POST data
 */
$c->func('index', function($tablename, $modelName, $dbVar, $requestUri, $postData = '')
{
    $schema     = new Schema($tablename, $modelName, getInstance()->{$dbVar}, urldecode($requestUri));
    $schemaPath = $schema->getPath();

    if( ! empty($postData))
    {        
        $_POST = unserialize(base64_decode($postData));  // Convert encoded raw post data to array format
    }

    if( ! file_exists($schemaPath)) // If schema file exists ?
    {
        $schema->writeToFile($schema->read(), null);  // Write content to schema file
    } 
    else 
    {
      // Check any changes in the column prefix
      // If any changes exists in the colprefix remove the valid schema and create new one
      //---------------------------------------------

        $currentSchema = getSchema($tablename);

        if(isset($currentSchema['*']['colprefix']) AND $schema->getPrefix() != $currentSchema['*']['colprefix'])
        {
            $colprefix = $currentSchema['*']['colprefix'];
            unset($currentSchema['*']);

            $ruleString = '';
            foreach($currentSchema as $key => $val)
            {
                $ruleString.= $schema->driver->buildSchemaField($key, $val['types']); 
            }

            $schema->writeToFile($ruleString, $colprefix); // recreate it with new prefix
        } 
        else  // Check database table
        {
            if( ! $schema->tableExists())  // Check table exits.
            {
              $schema->createTable(); // Create sql query & run
            }
            else 
            {
              $schema->syncTable();  // Display sync table
            }
        }
    }        

});


/* End of file sync.php */
/* Location: .app/tasks/controller/sync.php */
