<?php
defined('STDIN') or die('Access Denied');

// @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
// @
// @ SYNC TASK RUN AUTO SYNC FEATURE FOR SCHEMA
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
 * @param string $dbVar       database variable name
 * @param string $requestUri  urlencoded string ( current page url )
 * @param string $postData base64 encoded serialized string $_POST data
 */
$c->func('index', function($tablename, $dbVar, $requestUri, $postData = 'false')
{
    if( ! isset(getInstance()->{$dbVar}))
    {
        $dbObject = new Db($dbVar);
    } 
    else 
    {
        $dbObject = getInstance()->{$dbVar};
    }

    $schema     = new Schema($tablename, $dbObject, base64_decode($requestUri));
    $schemaPath = $schema->getPath();

    if($postData != 'false')
    {
        $base64_data = base64_decode($postData);

        if($this->_is_serialized($base64_data)) // check data is Serialized ?
        {
            $_POST = unserialize($base64_data);  // Convert encoded raw post data to array format
        }
    }

    if( ! file_exists($schemaPath)) // If schema file exists ?
    {
        $schema->writeToFile($schema->read());  // Write content to schema file
    } 
    else 
    {
        // Check any changes in the column prefix
        // If any changes exists in the colprefix remove the valid schema and create new one
        //---------------------------------------------

        if( ! $schema->tableExists())  // Check table exits.
        {
            $schema->createTable(); // Create sql query & run
        }
        else 
        {
            $schema->syncTable();  // Display sync table
        }
    }
    
});

$c->func('_is_serialized', function($data){

        if ( ! is_string( $data ) )  // if it isn't a string, it isn't serialized
        {
            return false;
        }

        $data = trim( $data );
        if ( 'N;' == $data )
        {
            return true;
        }
        
        if ( ! preg_match( '/^([adObis]):/', $data, $badions ) )
        {
            return false;
        }

        switch ( $badions[1] )
        {
            case 'a' :
            case 'O' :
            case 's' :
                if ( preg_match( "/^{$badions[1]}:[0-9]+:.*[;}]\$/s", $data ) )
                    return true;
                break;
            case 'b' :
            case 'i' :
            case 'd' :
                if ( preg_match( "/^{$badions[1]}:[0-9.E-]+;\$/", $data ) )
                    return true;
                break;
        }

        return false;
});


/* End of file sync.php */
/* Location: .app/tasks/controller/sync.php */