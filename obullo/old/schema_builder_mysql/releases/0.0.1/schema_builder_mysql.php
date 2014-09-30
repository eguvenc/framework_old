<?php

/**
 * Schema Builder Mysql Class
 *
 * Builds sql query output for mysql and do "add-to-file" schema
 * actions.
 *
 * @package       packages
 * @subpackage    schema_builder_mysql
 * @category      schema
 * @link
 */

Class Schema_Builder_Mysql extends Schema_Builder {

    public $dbCommand   = '';
    private $schemaName = '';
    private $columnName = '';
    public  $currentDataType = '';

    public $dataTypes  = array(        // defined mysql data types
        '_bit',
        '_tinyint',
        '_smallint',
        '_mediumint',
        '_int',
        '_integer',
        '_bigint',
        '_real',
        '_double',
        '_float',
        '_decimal',
        '_numeric',
        '_date',
        '_time',
        '_timestamp',
        '_datetime',
        '_year',
        '_char',
        '_varchar',
        '_binary',
        '_varbinary',
        '_tinyblob',
        '_blob',
        '_mediumblob',
        '_longblob',
        '_tinytext',
        '_text',
        '_mediumtext',
        '_longtext',
        '_enum',
        '_set'
    );

    public $attributeTypes = array(  // defined mysql data type attributes
        '_null',
        '_not_null',
        '_default',
        '_unsigned',
        '_unsigned_zerofill',
        '_key',
        '_foreign_key',
        '_unique_key',
        '_primary_key',
        '_auto_increment',
    );

    private $dbCommands = array();


    //-------------------------------------------------------------------------------------------------------------------------

    /**
     * Add to db button function
     * Creates MYSQL sql output using data types & attributes
     * 
     * @param array $unbracketsSchemaKeys [native Schema Keys]
     * @param array $unbracketsColType    [native Column Type]
     * @param array $schemaKeys           [Schema Keys with values]
     * @param [type] $colType              [Column Type with value]
     * @return array
     */
    public function addToDb($unbracketsSchemaKeys,$schemaKeys,$isNew = false,$colType,$unbracketsColType = null)
    {
        $this->dbCommands[5] = '';
        $this->dbCommand     = ( ! $isNew )  ? 'ALTER TABLE '.$this->quoteValue($this->schemaName).' MODIFY COLUMN '.$this->quoteValue($this->columnName) : 'ALTER TABLE '.$this->quoteValue($this->schemaName);

        if ($unbracketsColType != null) 
        {
            $loop = sizeof($unbracketsSchemaKeys);

            for ($k = 0; $k < $loop ; $k++) 
            {
                if ($unbracketsSchemaKeys[$k] == '_unique_key' || $unbracketsSchemaKeys[$k] == '_key' || $unbracketsSchemaKeys[$k] == '_foreign_key') 
                {
                    unset($unbracketsSchemaKeys[$k]);
                    unset($schemaKeys[$k]);
                }
            }

            $schemaKeys[]           = $colType;
            $unbracketsSchemaKeys[] = $unbracketsColType;
            $unbracketsSchemaKeys   = array_values($unbracketsSchemaKeys);
            $schemaKeys             = array_values($schemaKeys);
        }

        for ($i=0; $i < sizeof($unbracketsSchemaKeys); $i++) // for don't lose previous changes in db  
        { 
            if (in_array('_primary_key',$unbracketsSchemaKeys) AND $unbracketsColType != '_primary_key' AND ! $isNew) //if primary key defined in fileschema and request is not primary key
            {
                $locationPK           = array_search('_primary_key',$unbracketsSchemaKeys); //find location primary key in schemaArray
                unset($unbracketsSchemaKeys[$locationPK]); 
                $unbracketsSchemaKeys = array_values($unbracketsSchemaKeys);// reOrder Schema Array

                unset($schemaKeys[$locationPK]);
                $schemaKeys = array_values($schemaKeys);// reOrder Schema Array
            }

            switch ($unbracketsSchemaKeys[$i]) 
            {
                case '_null':
                    $this->addNull();
                    break;
                case '_auto_increment':
                    $this->addAutoIncrement();
                    break;
                case '_unsigned':
                    $this->addUnsigned();
                    break;
                case '_unique_key' : 
                case '_key':
                     $this->addKey($unbracketsSchemaKeys[$i],$schemaKeys[$i]);
                    break;
                case '_foreign_key':  
                    $this->addForeignKey($unbracketsSchemaKeys[$i],$schemaKeys[$i]);
                        break;
                case '_primary_key':
                    $this->addPrimaryKey($schemaKeys[$i]);
                    break;
                case '_not_null':
                    $this->addNotNull();
                    break;

                case '_default':
                    $this->addDefault($unbracketsSchemaKeys[$i],$schemaKeys[$i],$colType);
                
                    break;
                default :
                    $this->addDataType($schemaKeys[$i],$isNew);
                    break;
            }
        }

        return $this->sqlOutput();
    } 

   //-------------------------------------------------------------------------------------------------------------------------

   /**
    * Add Null
    */
    public function addNull()
    {
        if ( ! isset($this->dbCommands[2])) //Auto increment UNSET
        {
            $this->dbCommands[1] = 'NULL ';
            unset($this->dbCommands[3]); 
        }

    }  
     //-------------------------------------------------------------------------------------------------------------------------

    /**
     * addDataType
     * 
     * @param string  $schemaKey
     * @param boolean $isNew
     */
    public function addDataType($schemaKey, $isNew = false)
    {
        if (strpos($schemaKey,'(')) 
        {
            $schemaKey = preg_replace_callback('#([a_-z]+(\())#', function($match) { 
                return strtoupper(trim($match[0], '_')); 
            }, $schemaKey);

            $this->currentDataType = $schemaKey;
        }
        else
        {
            $schemaKey = strtoupper($this->removeUnderscore($schemaKey));
        }

        if ( ! $isNew) 
        {
            $this->dbCommand .= $schemaKey.' ';
        }
        else
        {
            $this->dbCommand .= ' ADD COLUMN '.$this->quoteValue($this->columnName).$schemaKey.' ';
        }
    }  

   //-------------------------------------------------------------------------------------------------------------------------

   /**
    * addNotNull Create a Sql Query
    */
    public function addNotNull()
    {
        $this->dbCommands[1] = 'NOT NULL ';
    } 

    //-------------------------------------------------------------------------------------------------------------------------

   /**
    * addUnsigned Create a Sql Query
    */
    public function addUnsigned()
    {
         $this->dbCommands[0] = 'UNSIGNED ';
    }

    //-------------------------------------------------------------------------------------------------------------------------

    /**
     * addDefault
     * 
     * @param string $unbracketSchemaKey
     * @param string $schemaKey          
     * @param string $colType            
     */
    public function addDefault($unbracketSchemaKey,$schemaKey)
    {
        if ( ! isset($this->dbCommands[2])) 
        {
            $defValues = $schemaKey; // if schema key and column type
            preg_match('#\((([^\]])*)\)#',$defValues, $defaultValue);

            $this->dbCommands[1] = 'NOT NULL ';

            if(is_numeric($defaultValue[1]))
            {
                $this->dbCommands[3] = "DEFAULT $defaultValue[1]";
            }
            else // string
            {
                $currentType = $this->getCurrentDataType();

                if(strpos($currentType, 'BIT') === 0) // add b for bit values
                {
                     // DEFAULT b'0'
                    $this->dbCommands[3] = "DEFAULT $defaultValue[1]";
                    return;
                }

                // sanitize from " double quotes
                // sanitize from ' quote
                $defaultStringValue  = trim($defaultValue[1], '"');
                $defaultStringValue  = trim($defaultStringValue, "'");

                $this->dbCommands[3] = "DEFAULT '".addslashes($defaultStringValue)."'";
            }
        }   
    }

    //-------------------------------------------------------------------------------------------------------------------------

    /**
     * addKey add Unique Key and Key
     * 
     * @param string $unbracketsSchemaKey 
     * @param string $colType             
     */
    public function addKey($unbracketsSchemaKey,$colType)
    {
        preg_match('#_key\((.*?)\)#',$colType,$matches); // Get Key Index Name

        $indexName = $matches[1];

        $newColType = trim($colType, '_');

        if (strpos($newColType, ')(') > 0) 
        {
            $newColType = trim($newColType,')');
            $exp        = explode(')(', $newColType);

            $keyIndex  = $exp[0];
            unset($exp[0]);

            $implodeKeys = array();

            foreach($exp as $item)
            {
                if(strpos($item, ',') !== false)
                {
                    foreach (explode(',', $item) as $k => $v)
                    {
                        $implodeKeys[] = $this->quoteValue($v);
                    }
                } 
                else 
                {
                    $implodeKeys = array($this->quoteValue($item));
                }
            }

            $this->dbCommands[5] .= ',ADD'.strtoupper($this->removeUnderscore($unbracketsSchemaKey)).' '.$this->quoteValue($indexName).' ('.implode($implodeKeys, ",").')';
        }
        
    }

    //-------------------------------------------------------------------------------------------------------------------------

    /**
     * addForeignKey
     * 
     * @param string $unbracketsSchemaKey [Native Key _foreign_key]
     * @param string $schemaKey           [Key with value _foreign_key(KeyName)(referencesTable)(ReferenceField)]
     */
    public function addForeignKey($unbracketsSchemaKey,$schemaKey)
    {
        preg_match_all('#\((.*?)\)#',$schemaKey,$matches);// Get Key Index Name

        $indexName  = $matches[1][1];
        $newColType = trim($schemaKey, '_');

        if (strpos($schemaKey, ')(') > 0) 
        {
            $newColType = trim($newColType,')');
            $exp        = explode(')(', $newColType);
            unset($exp[0]);
            
            $refField = array_values($exp);
            if (isset($refField[1])) 
            {
                $this->dbCommands[5] .= ',ADD CONSTRAINT `'.$matches[1][0].'`'.strtoupper($this->removeUnderscore($unbracketsSchemaKey)).' ('.$this->quoteValue($this->columnName).') REFERENCES '.$this->quoteValue($indexName).' ('.$this->quoteValue($refField[1]).') ';
            }
        }
    }

    //-------------------------------------------------------------------------------------------------------------------------

    /**
     * addAutoIncrement
     *
     * @return  void
     */
    public function addAutoIncrement()
    {
        if (isset($this->dbCommands[3])) 
        {
            unset($this->dbCommands[3]);
        }
        
        $this->dbCommands[1] = 'NOT NULL ';
        $this->dbCommands[2] = 'AUTO_INCREMENT ';
    }

    //-------------------------------------------------------------------------------------------------------------------------

    /**
     * addPrimaryKey
     * 
     * @param string $schemaKey 
     */
    public function addPrimaryKey($schemaKey)
    {
        preg_match('#(\(.*?\))#',$schemaKey, $pKColumns);

        $pkey = (isset($pKColumns[0])) ? $pKColumns[0] : '('.$this->quoteValue($this->columnName).')'; 

        $this->dbCommands[4] = ',ADD PRIMARY KEY '.$pkey.' ';
    }

    //-------------------------------------------------------------------------------------------------------------------------

    /**
     * changeDataTye to new Type
     * 
     * @param  string $newDataType new Data Type
     * @return string Sql Query for change data types
     */
    public function changeDataType($newDataType)
    {
        return 'ALTER TABLE '.$this->quoteValue($this->schemaName).' MODIFY COLUMN '.$this->quoteValue($this->columnName).$newDataType;
    }

    //-------------------------------------------------------------------------------------------------------------------------

    /**
    * Drop Column
    * 
    * @return string
    */
    public function dropColumn()
    {
        return 'ALTER TABLE '.$this->quoteValue($this->schemaName).' DROP '.$this->quoteValue($this->columnName);
    }  

    //-------------------------------------------------------------------------------------------------------------------------

    /**
    * Changing old column type with new one
    * 
    * @param string $newColType 
    * @return string
    */
    public function modifyColumn ($newColType)
    {
        return 'ALTER TABLE '.$this->quoteValue($this->schemaName).' MODIFY COLUMN '.$this->quoteValue($this->columnName).$newColType;
    }

    //-------------------------------------------------------------------------------------------------------------------------

    /**
     * dropAttribute creates sql query for drop attribute
     * 
     * @param  string $attributeType [native Column Type]
     * @param  string $colType       [Column Type]
     * @param  string $dataType      [Data Type]
     * @return string                [Sql Query for drop attribute]
     */
    public function dropAttribute($attributeType,$colType,$dataType)
    {
        switch ($attributeType)    // types
        {
            case '_null':
            case '_auto_increment':
                $dbCommand = 'ALTER TABLE '.$this->quoteValue($this->schemaName).' MODIFY COLUMN '.$this->quoteValue($this->columnName).$dataType.' NOT NULL';
                break;
            case '_unsigned':
                $dbCommand = 'ALTER TABLE '.$this->quoteValue($this->schemaName).' MODIFY COLUMN '.$this->quoteValue($this->columnName).$dataType;
                break;

            case '_key':    
            case '_unique_key' : 
                preg_match('#_key\((.*?)\)#',$colType,$matches);

                $indexName = $matches[1];
                $dbCommand = 'ALTER TABLE '.$this->quoteValue($this->schemaName).' DROP INDEX '.$this->quoteValue($indexName);
                break;
            case '_foreign_key':
                    preg_match_all('#\((.*?)\)#',$colType,$matches);// Get Key Index Name

                    $indexName = $matches[1][0];
                    $dbCommand ='ALTER TABLE '.$this->quoteValue($this->schemaName).' DROP FOREIGN KEY `'.$indexName.'`';
                break;
            case '_primary_key':
                $dbCommand = 'ALTER TABLE '.$this->quoteValue($this->schemaName).' MODIFY COLUMN '.$this->quoteValue($this->columnName).$dataType.',DROP PRIMARY KEY ';
                break;

            case '_not_null':
                $dbCommand = 'ALTER TABLE '.$this->quoteValue($this->schemaName).' MODIFY COLUMN '.$this->quoteValue($this->columnName).$dataType.' NULL';
                break;

            case '_default':
                $dbCommand = 'ALTER TABLE '.$this->quoteValue($this->schemaName).' ALTER COLUMN '.$this->quoteValue($this->columnName).' DROP DEFAULT';
                break;
        }
        return $dbCommand;
    }

    //-------------------------------------------------------------------------------------------------------------------------
    
    /**
     * renameColumn Rename Column
     * 
     * @param  array $unbracketsColTypes Native Column Types without brackets
     * @param  array $schemaKeys         Schema Keys
     * @param  string $dataType          Data Type
     * @return string                     
     */
    public function renameColumn($unbracketsColTypes,$schemaKeys,$dataType)
    {
        $this->dbCommands[5] = ''; // for multiple indexes we should define here

        for ($i=0; $i < sizeof($unbracketsColTypes); $i++) 
        { 
            switch ($unbracketsColTypes[$i]) 
            {
                case '_null':
                    $this->addNull();
                    break;
                case '_auto_increment':
                    $this->addAutoIncrement();
                    break;
                case '_unsigned':
                    $this->addUnsigned();
                    break;
                case '_not_null':
                    $this->addNotNull();
                    break;

                case '_default':
                    $this->addDefault($unbracketsColTypes[$i],$schemaKeys[$i]);
                    break;
            }
        }

        $this->dbCommand = 'ALTER TABLE '.$this->quoteValue($this->schemaName).' CHANGE '.$this->quoteValue($this->columnName).' '.$this->quoteValue('$NEW NAME');
        $this->addDataType($dataType);
        
        return $this->sqlOutput();
   } 

    //-------------------------------------------------------------------------------------------------------------------------
    
    /**
    * Rename Column
    * 
    * @param type $newColType 
    * @return type
    */
    public function addToFile($colType,$fileSchema)
    {
        $schemaKeys = explode('|', $fileSchema[$this->columnName]);

        $unbracketsFileColType  = preg_replace('#(\(.*?\))#','', $fileSchema[$this->columnName]); // Get pure column type withouth brackets
        $unbracketsFileColTypes = explode('|',$unbracketsFileColType); // Array of unbracketsFileColType

        if ($key = preg_grep('#'.$unbracketsFileColType.'#',$this->dataTypes)) // Search into datatypes with matches
        {
            $colFileKeyValue = array_values($key)[0];
            $colkey          = array_search($colFileKeyValue,$unbracketsFileColTypes); // Find datatype location in matches 
        }
        
        $unbracketsColType  = preg_replace('#(\(.*?\))#','', $colType);// Get pure column type withouth brackets

        switch ($unbracketsColType) // types
        {
            case '_null':

                    if (is_numeric(($key = array_search('_not_null',$unbracketsFileColTypes)))) // if not null exists change it
                    {
                        $schemaKeys[$key] = '_null' ;
                    }
                    elseif (is_numeric(($key = array_search('_null',$unbracketsFileColTypes)))) // if null exists change it
                    {
                        $schemaKeys[$key] = '_null' ;
                    }
                    else
                    {
                        $schemaKeys[] = $colType;   
                    }

                break;

            case '_not_null':

                    if (is_numeric(($key = array_search('_null',$unbracketsFileColTypes)))) // if null exists change it
                    {
                        $schemaKeys[$key] = '_not_null' ;
                    }
                    elseif (is_numeric(($key = array_search('_not_null',$unbracketsFileColTypes)))) // if _not_null exists change it
                    {
                        $schemaKeys[$key] = '_not_null' ;
                    }
                    else
                    {
                        $schemaKeys[] = $colType;   
                    }

                break;
            case '_unsigned':
            case '_primary_key':
            case '_default':
            case '_auto_increment':
                is_numeric(($key = array_search($unbracketsColType,$unbracketsFileColTypes))) ? $schemaKeys[$key] = $colType : $schemaKeys[] = $colType; 
                break;
            case '_key':
            case '_foreign_key':
            case '_unique_key' : 
                is_numeric(($key = array_search($unbracketsColType,$unbracketsFileColTypes))) ? $schemaKeys[] = $colType
                 : $schemaKeys[] = $colType;
                break;   
            default:
                isset($colkey) ?  $schemaKeys[$colkey] = $colType : $schemaKeys[] = $colType ;
                break;
        }

        return $schemaKeys;
    }

    //-------------------------------------------------------------------------------------------------------------------------

    /**
    * Set schemaName
    * 
    * @param type $schemaName 
    */
    public function setSchemaName($schemaName)
    {
        $this->schemaName = $schemaName;
    } 

    //-------------------------------------------------------------------------------------------------------------------------

    /**
   * sqlOutput Build Sql Output
   * 
   * @return string Sql Output
   */
    public function sqlOutput()
    {
        ksort($this->dbCommands);
        $this->dbCommands = array_values($this->dbCommands);

        for ($i = 0; $i < sizeof($this->dbCommands); $i++) 
        { 
            $this->dbCommand.= $this->dbCommands[$i];
        }

        return $this->dbCommand;
    }

    //-------------------------------------------------------------------------------------------------------------------------

    /**
    * Set columnName
    * 
    * @param type $columnName 
    */
    public function setColName($columnName)
    {
        $this->columnName = $columnName;
    }

    //-------------------------------------------------------------------------------------------------------------------------

    /**
     * Get Schema Name
     * 
     * @return string Schema Name
     */
    public function getSchemaName()
    {
        return $this->schemaName;
    }

    //-------------------------------------------------------------------------------------------------------------------------

    /**
     * Get Column Name
     * 
     * @return string Column Name
     */
    public function getColName()
    {
        return $this->columnName;
    }

    //-------------------------------------------------------------------------------------------------------------------------

    /**
     * Get current data type
     */
    public function getCurrentDataType()
    {
        return $this->currentDataType;
    }

}  

// END Class Schema_Builder_Mysql

/* End of file Schema_Builder_Mysql */
/* Location: ./packages/schema_builder_mysql/releases/0.0.1/schema_builder_mysql.php */
