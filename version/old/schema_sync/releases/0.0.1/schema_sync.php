<?php

/**
 * Schema Sync Class
 *
 * @package       packages
 * @subpackage    schema_sync
 * @category      schema
 * @link
 */

Class Schema_Sync {

    public $queryWarning;               // Javascript query alert for dangerous mysql operations.
    public $db;                         // database object
    public $tablename;                  // tablename
    public $schemaObject;               // schema class object
    public $schemaName = null;          // lowercase schema name
    public $dbSchema   = array();       // database schema array
    public $fileSchema = array();       // stored file schema array
    public $schemaDiff = array();       // last schema output after that the sync


    // --------------------------------------------------------------------

    /**
     * Constructor
     * 
     * @param string $schemaDBContent database schema content
     * @param string $schemaObject schema Object
     */
    public function __construct($schemaObject)
    {
        $this->tablename    = $schemaObject->getTableName(); // Set tablename
        $this->schemaName   = strtolower($this->tablename);  // set schema name
    }

    // --------------------------------------------------------------------

    /**
     * Check fileSchema & dbSchema 
     * collisions.
     * 
     * @return boolean
     */
    public function collisionExists()
    {
        $schemaDiff = $this->getSchemaDiffArray();
        $colCount = sizeof($schemaDiff);

        $sumChanges = 0;
        foreach ($schemaDiff as $k => $v)
        {
            $sumChanges += sizeof($v);
        }

        if($sumChanges == $colCount)  // no collisions
        {
            return false;
        }

        return true; // Yes, we have schema collisions
    }

    // --------------------------------------------------------------------

    /**
     * Get the schema differencies in array format
     * 
     * @return string
     */
    public function getSchemaDiffArray()
    {
        return $this->schemaDiff;
    }

    // --------------------------------------------------------------------

    /**
     * Run the class
     * 
     * @return boolean
     */
    public function run()
    {
        $this->_dbDiff();
        $this->_schemaDiff();
        $this->_isPostCommand(); // check the post action the run the command

        if($this->schemaObject->debug)
        {
            echo $this->schemaObject->getDebugOutput();
        }

        $this->schemaObject->runQuery();  // it check $_POST['query'] input & run sql query.
    }

    // --------------------------------------------------------------------

    /**
     * Calculate database differencies
     * 
     * @return void
     */
    
    /**
     * Display all html output of 
     * the Sync Feature.
     * 
     * @return string
     */
    public function output()
    {
        $output = $this->schemaObject->getOutput(); // Sync diff ooutput

        if( ! empty($output)) // write output to schema file
        { 
            $this->schemaObject->writeToFile($output);
        }
        
        $sync_html = new Schema_Sync\Src\Schema_Sync_Html($this, $this->schemaObject);
        return $sync_html->writeOutput();
    }

    // --------------------------------------------------------------------

    /**
     * Post command action
     * 
     * @return boolean
     */
    public function _isPostCommand()
    {
        if(isset($_POST['lastSyncCommand'])) // If we have post action with post command
        {
            $lastCommand = explode('|', $_POST['lastSyncCommand']);
            $lastFunc    = $_POST['lastSyncFunc'];

            if($lastFunc == 'addKey' OR $lastFunc == 'removeKey') // add / remove attribute
            {
                $count    = count($lastCommand);
                $colName  = $lastCommand[0]; // column name
                $command  = $lastCommand[$count - 1];

                unset($lastCommand[0]);
                unset($lastCommand[$count - 1]);

                $colType = implode('|', $lastCommand) ; // _null|_text|..........
                $isNew   = true;
            }
            else
            {
                $colName = $lastCommand[0]; // column name
                $colType = $lastCommand[1]; // _not_null
                $command = $lastCommand[2]; // drop
                $isNew   = (isset($lastCommand[3]) AND $lastCommand[3] == 'new') ? true : false; // is new field ?
            }
        
            $startArray = '$'.$this->schemaName.' = array(';
            $endArray   = ');';
            $disabled   = false;
            
            //--------Send schema name and column name to the builder class------//
            
            $this->builderObject->setSchemaName($this->schemaName);
            $this->builderObject->setColName($colName);

            //------------------------------------------------------------------//
            
            switch ($command)
            {
                case 'drop':   // Drop Type from Database
                    
                    $colTypeArray = explode('|', $colType);
                
                    if ($isNew == true AND count($colTypeArray) > 1)  // If user want to drop this field run this process & coltype has more one
                    {
                        //------- Drop column -------//
                         
                        $dbCommand = $this->builderObject->dropColumn();
                        
                        //---------------------------//
                    }
                    else // If this field exists in FileSchema
                    {
                        $unbracketsColType     = preg_replace('#(\(.*?\))#','', $colType); // Post data column types
                        $explodedColType       = explode('|',$unbracketsColType);
                        $dataTypeNotExist      = false;

                        if (isset($this->fileSchema[$colName])) 
                        {
                            $unbracketsFileColType = preg_replace('#(\(.*?\))#','', $this->fileSchema[$colName]); // File schema column types
                            $explodedFileColTypes  = explode('|',$unbracketsFileColType);
                                
                            if ($dbcolKeys = preg_grep('#'.$unbracketsFileColType.'#',$this->builderObject->dataTypes)) // Check data type exists in FileSchema
                            {
                                $colkey            = array_search(array_values($dbcolKeys)[0],$explodedFileColTypes);// ColType array position in file schema   
                                $tempColKey        = $colkey;  // temp column key for get datatype in fileschema if try to drop a previous data type
                                
                                $fileSchemaArray   = explode('|',$this->fileSchema[$colName]);
                                $dataType          = $fileSchemaArray;
                                $dataType[$colkey] = $this->builderObject->removeUnderscore($dataType[$colkey]);// Remove Underscores from datatypes

                            }
                            else
                            {
                                $dataTypeNotExist = true;
                            }

                            if (isset($this->dbSchema[$colName])) // if this column exists in DB
                            {
                                $unbracketsDbColType = preg_replace('#(\(.*?\))#','', $this->dbSchema[$colName]); // Db schema column types
                                $explodedDbColTypes  = explode('|',$unbracketsDbColType); // Get column types from DBSchema to array 
                                
                                $dbcolKeys           = preg_grep('#'.$unbracketsDbColType.'#',$this->builderObject->dataTypes); // Check in builder data types 
                                $dbColKeyValue       = array_values($dbcolKeys)[0];
                                $colkey              = array_search($dbColKeyValue,$explodedDbColTypes);  // Array Position 
                                
                                $dbArray             = explode('|',$this->dbSchema[$colName]);

                                $dbArray[$colkey]    = $this->builderObject->removeUnderscore($dbArray[$colkey]); // Remove Underscores from datatypes

                                if ($this->builderObject->removeUnderscore($colType) == $dbArray[$colkey] && isset($dataType)) 
                                {
                                    $dbArray[$colkey] = $dataType[$tempColKey];
                                }
                                
                                $dbArray[$colkey] = preg_replace_callback('#([a_-z]+(\())#', function($match) {  // Dont uppercase data inside brackets
                                    return strtoupper($match[0]); 
                                }, $dbArray[$colkey]);

                                $dataType = $dbArray;
                            }
                        }
                        else
                        {
                            $unbracketsDbColType = preg_replace('#(\(.*?\))#','', $this->dbSchema[$colName]); // Db schema column types
                            $explodedDbColTypes  = explode('|',$unbracketsDbColType); // Get column types from DBSchema to array 
                            
                            $dbcolKeys           = preg_grep('#'.$unbracketsDbColType.'#',$this->builderObject->dataTypes); // Check in builder data types 
                            $dbColKeyValue       = array_values($dbcolKeys)[0];
                            $colkey              = array_search($dbColKeyValue,$explodedDbColTypes);  // Array Position 
                            
                            $dbArray             = explode('|',$this->dbSchema[$colName]);
                            $dataType            = $dbArray;

                            $dataType[$colkey] = $this->builderObject->removeUnderscore($dataType[$colkey]); // Remove Underscores from datatypes

                        }
                        
                        if (count($dbcolKeys) > 0) // Datatype exist in schema
                        {
                            if ($colKeys = preg_grep('#'.$explodedColType[0].'#',$this->builderObject->dataTypes)) // Check in builder data types 
                            {
                                $dataType[$colkey] = preg_replace_callback('#([a_-z]+(\())#', function($match) {   // Dont uppercase data inside brackets
                                    return strtoupper($match[0]); 
                                }, $dataType[$colkey]);

                                if ($isNew OR $dataTypeNotExist) // if you want to drop a new field 
                                {
                                    unset($dataType[$colkey]);
                                }
                                
                                //------ if any data type set in fileschema change with old one else drop column ------//
                                
                                $dbCommand =  (isset($dataType[$colkey])) ? $this->builderObject->modifyColumn($dataType[$colkey]) : $this->builderObject->dropColumn();
                                //-------------------------------------------------------------------------------------//
                            }
                            else
                            {
                                //--------------------Drop attribute from column -----------------------//
                                
                                $dbCommand =  $this->builderObject->dropAttribute($explodedColType[0],$colType,$dataType[$colkey]); // DROP COLUMN ATTRIBUTE
                                
                                //---------------------------------------------------------------------//
                            }
                        }
                        else
                        {
                            $dbCommand = '<span style="color:red">You can not drop a data type without define a new in '.ucfirst($this->schemaName).' Schema.</span>';
                            $disabled = true;
                        }   
                    }

                    echo $this->schemaObject->displaySqlQueryForm($dbCommand, $this->queryWarning,$disabled); // Show sql query to developer to confirmation.

                    break;
                    
                case 'add-to-db':   //  Add Type to Database
                    
                    $schemaKeys = explode('|',$colType);
                    
                    if (isset($this->dbSchema[$colName])) // Already exists 
                    {
                        $unbracketsColType    = preg_replace('#(\(.*?\))#','', $colType);// Get pure column type withouth brackets coming from $_POST
                        $unbracketsColTypes   = explode('|',$unbracketsColType);
                        
                        $schemaKeys           = explode('|',$this->dbSchema[$colName]);
                        
                        $unbracketsDBColType  = preg_replace('#(\(.*?\))#s','', $this->dbSchema[$colName]);// Get pure DB column type withouth brackets
                        $unbracketsDBColTypes = explode('|',$unbracketsDBColType);
                        
                        $dbcolKeys            = preg_grep('#'.$unbracketsDBColType.'#',$this->builderObject->dataTypes); // Data Type Confirmation
                        
                        $dbColKeyValue        = array_values($dbcolKeys)[0];
                        $colkey               = array_search($dbColKeyValue,$unbracketsDBColTypes); // Find the location of columnType  in array of dbSchema

                        if ($columnType = preg_grep('#'.$unbracketsColType.'#',$this->builderObject->dataTypes))  // If colname exists and has a datatype in DBSchema Change Datatypes 
                        {
                            if (strpos($colType,'(')) 
                            {
                                $schemaKeys[$colkey] = preg_replace_callback('#([a_-z]+(\())#', function($match) { 
                                    return strtoupper($this->builderObject->removeUnderscore($match[0])); 
                                }, $colType);
                            }
                            else
                            {
                                $schemaKeys[$colkey] = strtoupper($this->builderObject->removeUnderscore($colType));
                            }
                               
                            //-------------------- Change Data Type from column ---------------------//
                            
                            $dbCommand = $this->builderObject->changeDataType($schemaKeys[$colkey]); 

                            //----------------------------------------------------------------------//
                        }
                        else
                        {   
                            $unbracketsSchemaKeys = preg_replace('#(\(.*?\))#','', $schemaKeys);// we'r using unbracketSchemaKeys to get unbracket field names  for don't lose previous changes in db 

                            //---------- Add Attribute don't lose previous changes in db ---------//
                            
                            $dbCommand = $this->builderObject->addToDb($unbracketsSchemaKeys,$schemaKeys,false,$colType,$unbracketsColType);
                            
                            //-------------------------------------------------------------------//
                        }
                    }
                    else    // New 
                    {
                        $unbracketsColType    = preg_replace('#(\(.*?\))#','', $colType);// Get pure column type withouth brackets
                        $unbracketsSchemaKeys = preg_replace('#(\(.*?\))#','', $schemaKeys);

                        if ( ! $columnType = preg_grep('#'.$unbracketsColType.'#', $this->builderObject->dataTypes))
                        {
                            $dbCommand = 'You have to define a datatype in your file schema.';
                            $disabled = true;
                        }
                        else
                        {
                            $colKeyValue = array_values($columnType)[0];
                            $colkey      = array_search($colKeyValue, $unbracketsSchemaKeys);//Get position in Array
                            
                            $columnType  = $this->builderObject->removeUnderscore($schemaKeys[$colkey]);
                            
                            //---------- Add Attribute  ---------//
                            
                            $dbCommand = $this->builderObject->addToDb($unbracketsSchemaKeys,$schemaKeys,true,$colType);
                            
                            //----------------------------------//

                        }   
                    }

                    echo $this->schemaObject->displaySqlQueryForm($dbCommand, $this->queryWarning,$disabled); // Show sql query to developer to confirmation.

                    break;

                case 'add-to-file':  // Add item to valid schema // Rebuild Schema Array & Write To File

                    $ruleString = '';
                    if( $isNew AND ! array_key_exists($colName, $this->fileSchema) AND isset($colType)) // new "field"
                    {
                        $colTypeArray = explode('|', $colType);

                        for ($i = 0; $i < count($colTypeArray); $i++) 
                        { 
                            $schemaKeys[] = $colTypeArray[$i];
                        }
                    }
                    else
                    {
                        //---------------- Add to File ----------------//
                        
                        $schemaKeys = $this->builderObject->addToFile($colType, $this->fileSchema);

                        //---------------------------------------------//
                    }
                    
                    $this->fileSchema[$colName] = trim(implode('|',$schemaKeys),'|');

                    foreach($this->fileSchema as $k => $types)
                    {
                        $ruleString.= $this->schemaObject->driver->buildSchemaField($k, $types);
                    }
                    
                    $this->schemaObject->setDebugOutput($ruleString);
                    $this->schemaObject->setOutput($ruleString);

                    break;

                case 'rename':

                    $schemaKeys         = explode('|',$colType);
                    $unbracketsColType  = preg_replace('#(\(.*?\))#','', $colType);// Get pure column type withouth brackets
                    $unbracketsColTypes = explode('|',$unbracketsColType); // Create pure column types without brackets and variables 

                    if ( ! $columnType = preg_grep('#'.$unbracketsColType.'#', $this->builderObject->dataTypes))
                    {
                        $dbCommand = 'You have to define a datatype in your file schema.';
                        $disabled  = true;
                    }
                    else
                    {
                        $colKeyValue = array_values($columnType)[0];
                        $colkey      = array_search($colKeyValue, $unbracketsColTypes);//Get Data Type position in Array
                        $dataType    = $schemaKeys[$colkey];

                        //---------------- Create Rename Column Sql Query ----------------//
                        
                        $dbCommand = $this->builderObject->renameColumn($unbracketsColTypes,$schemaKeys,$dataType);

                        //---------------- ----------------------------- ----------------//
                    }

                    echo $this->schemaObject->displaySqlQueryForm($dbCommand, $this->queryWarning,$disabled); // Show sql query to developer to confirmation.

                       break;

                case 'remove-from-file':

                    if(array_key_exists($colName, $this->fileSchema)) // new Field
                    {
                        $ruleString = '';
                        $colTypeArray = explode('|',$colType);

                        if (count($colTypeArray) > 1 ) 
                        {
                            unset($this->fileSchema[$colName]);
                        }
                        else
                        {
                            $schemaKeys = explode('|',$this->fileSchema[$colName]);
                            unset($schemaKeys[array_search($colType, $schemaKeys)]);
                            
                            if (count($schemaKeys) > 0)
                            {
                                $this->fileSchema[$colName] = implode('|',$schemaKeys);
                            }
                            else
                            {
                                unset($this->fileSchema[$colName]);
                            }
                        }

                        foreach($this->fileSchema as $k => $types)
                        {
                            $ruleString.= $this->schemaObject->driver->buildSchemaField($k, $types);
                        }

                        $this->schemaObject->setDebugOutput($ruleString);
                        $this->schemaObject->setOutput($ruleString);
                    }

                    break;
            }
        }
    }

}

// END Schema_Sync class

/* End of file schema_sync.php */
/* Location: ./packages/schema_mysql/releases/0.0.1/src/schema_sync.php */