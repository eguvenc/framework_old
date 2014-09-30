<?php

/**
 * Schema Sync Mysql Class
 *
 * @package       packages
 * @subpackage    schema_sync_mysql
 * @category      schema
 * @link
 */

Class Schema_Sync_Mysql extends Schema_Sync {

    public $builderObject;
    
    function __construct($schemaDBContent, $schemaObject)
    {
        parent::__construct($schemaObject);

        $fileSchema = getSchema($this->schemaName); // Get current schema
        
        unset($fileSchema['*']);  // Get only fields no settings

        $newFileSchema = array();
        foreach($fileSchema as $k => $v)
        {
            $newFileSchema[$k] = $v;
        }

        eval('$databaseSchema = array('.$schemaDBContent.');');  // Active Schema coming from database
        unset($databaseSchema['*']);

        $this->builderObject = new Schema_Builder_Mysql();

        $this->dbSchema     = $this->_reformatSchemaTypes($databaseSchema); // Render schema, fetch just types.
        $this->fileSchema   = $this->_reformatSchemaTypes($newFileSchema, true);  // Get just types 

        $this->db           = $schemaObject->dbObject;     // Database Object
        $this->schemaObject = $schemaObject; // Schema Object
        $this->debug        = false;
    }

     // --------------------------------------------------------------------

    /**
    * Convert output && remove unnecessary pipes ..
    * 
    *   Array
    *  (
    *    [id] => _not_null|_primary_key|_unsigned|_int(11)|_auto_increment
    *    [cities] => _not_null|_default("banana")|_enum("apple","orange","banana")
    *    [datetime] => _not_null|_datetime
    *    [email] => _null|_char(160)
    *   );
    * @param  array  $schemaArray
    * @return array              
    */
    public function _reformatSchemaTypes($schemaArray = array(), $quote = false)
    {
        $schema = array();
        foreach($schemaArray as $key => $val)
        {
            $val['types'] = (isset($val['types'])) ? $val['types'] : '';
            $val['types'] = preg_replace('#(\|+|\|\s+)(?:\|)#', '|', $val['types']);  // remove unnecessary pipes ..
            $val['types'] = preg_replace('#["]+#', '', $val['types']); // remove double " " quotes ...
            $val['types'] = preg_replace('#(_enum)(\(.*?\))#', "_enum", $val['types']); // if user insert enum data in types we remove it

            if(isset($schemaArray[$key]['_enum'])) // Mysql Enum Type
            { 
                $enum = '';
                foreach($schemaArray[$key]['_enum'] as $enumVal)
                {
                    if($quote)
                    {
                        $enumVal = addslashes($enumVal);  // add slashes for ' quote
                    }

                    $enum.= "'".$enumVal."',";
                }
                $enum = trim($enum, ',');
                $schema[$key] = preg_replace('#_enum#', "_enum($enum)", $val['types']);
          
            }
            elseif(isset($schemaArray[$key]['_set']))
            {
                $set = '';
                foreach($schemaArray[$key]['_set'] as $enumVal)
                {
                    if($quote)
                    {
                        $enumVal = addslashes($enumVal); // add slashes for ' quote
                    }

                    $set.= "'".$enumVal."',";
                }

                $set = trim($set, ',');
                $schema[$key] = preg_replace('#_set#', "_set($set)", $val['types']);
            }
            else 
            {
                $schema[$key] = $val['types'];
            }
        }

        return $schema;
    }

    // --------------------------------------------------------------------

    /**
     * _dbDiff Calculate Db differencies
     * 
     * @return void
     */
    protected function _dbDiff()
    {
        foreach($this->dbSchema as $k => $v)
        {
            if( ! isset($this->fileSchema[$k])) // Sync column names
            {
                $dbTypes      = explode('|', trim($this->dbSchema[$k], '|'));
                foreach ($dbTypes as $type) 
                {
                    $this->schemaDiff[$k]['options'] = array(
                        'types' => $v,
                        'drop',
                        'add-to-file',
                        'rename'
                        );
                    $this->schemaDiff[$k]['new_types'][] = array(
                    'type' => $type,
                    'types' => $v,
                    'options' => array(
                        'drop',
                        'add-to-file',
                        'rename'
                        ),
                    );   
                }
                
            }

            if(array_key_exists($k, $this->fileSchema)) // Sync column types
            {
                $dbTypes     = explode('|', trim($this->dbSchema[$k], '|'));
                $schemaTypes = explode('|', trim($this->fileSchema[$k], '|'));
                $diffMatches = array_diff($dbTypes, $schemaTypes);
                
                if(sizeof($diffMatches) > 0)
                {
                    foreach ($diffMatches as $diffVal)
                    {
                        $this->schemaDiff[$k][] = array(
                        'update_types' => $diffVal,
                        'options' => array(
                            'drop',
                            'add-to-file'
                            ),
                        );
                    }
                }

                /* Search data types and remove unecessary buttons. ( REMOVE DROP  BUTTON )*/

                $result     = preg_replace('#(\(.*?\))#','', $this->fileSchema[$k]);  // Remove data type brackets from file schema
                $grep_array = preg_grep('#'.$result.'#',$this->builderObject->dataTypes); // Search data type exists

                // If at least one data type not exists in the schema file
                // don't show "drop" button to users
                // users must be add a data type in the fileschema field.

                if(sizeof($grep_array) == 0) // If data type not exists 
                {
                     unset($this->schemaDiff[$k][0]['options'][0]); // REMOVE DROP  BUTTON 
                }
            }

        }
    }

    // --------------------------------------------------------------------

    /**
     * _schemaDiff Calculate schema differencies
     * 
     * @return void
     */
    protected function _schemaDiff()
    {
        $allDbData      = implode('|',$this->dbSchema); // Get all db data
        $allDbData      = preg_replace('#(\(.*?\))#','', $allDbData);
        $allDbDataArray = explode('|',$allDbData);

        foreach($this->fileSchema as $k => $v)
        {
            if( ! isset($this->dbSchema[$k]))    // Sync column names
            {
                $result = preg_replace('#(\(.*?\))#','', $v); // Remove data type brackets from file schema

                $dataTypeArray = $this->builderObject->getDataTypeArray($result,$this->builderObject->dataTypes);
                $dataTypeCount = sizeof($dataTypeArray);
                $dataTypeArray = array_values($dataTypeArray);
                $newAttributes = explode('|', trim($result, '|'));        

                $v = explode('|', trim($v, '|'));

                if ($dataTypeCount == 0)  // Datatype must be unique and valid
                {
                    $i = 0;
                    foreach($newAttributes as $types)
                    {
                        $this->schemaDiff[$k][] = array(
                        'new_types' => $v[$i],
                        'options' => array(
                            'remove-from-file'
                            ),
                        'errors' => "    <span style='color:red;font-size:12px'>You have to define a valid datatype in your file schema.</span>"
                        );
                        $i++;
                    }
                }
                elseif ($dataTypeCount > 1) 
                {
                    $i = 0;
                    foreach($newAttributes as $types)
                    {
                        $this->schemaDiff[$k][] = array(
                            'new_types' => $v[$i],
                            'options' => array(
                                'remove-from-file',
                                ),
                            'errors' => "    <span style='color:red;font-size:12px'>You can't define more than one datatype in your file schema.</span>"
                            );
                        $i++;
                    }
                }
                else
                {
                    $i = 0;
                    foreach($newAttributes as $type)
                    {
                        if (in_array($type,$this->builderObject->attributeTypes) || in_array($type, $this->builderObject->dataTypes))
                        {
                            $this->schemaDiff[$k]['options'] = array(
                                'types' => implode('|', $v),
                                'remove-from-file',
                                'add-to-db',
                                
                                );
                            $this->schemaDiff[$k]['new_types'][] = array(
                            'type' => $v[$i],
                            'types' => implode('|', $v),
                            'options' => array(
                                'remove-from-file',
                                'add-to-db'
                                ),
                            );
                        }
                        else
                        {
                            $this->schemaDiff[$k]['options'] = array(
                                'types' => implode('|', $v),
                                'remove-from-file',
                                'add-to-db',
                                'class' => 'syncerror'
                                );
                            $this->schemaDiff[$k]['new_types'][] = array(
                            'type' => $v[$i],
                            'types' => implode('|', $v),
                            'options' => array(
                                'remove-from-file',
                                ),
                            );
                        }
                        $i++;
                    }
                }
            }
            else 
            {
                $this->schemaDiff[$k]['types'] = $v;  // * Add current columns
            }

            // --------------------------------------------------------------------

            if(array_key_exists($k, $this->dbSchema)) // Sync column types
            {
                $dbTypes     = explode('|', trim($this->dbSchema[$k], '|'));
                $schemaTypes = explode('|', trim($this->fileSchema[$k], '|'));

                $diffMatches = array_diff($schemaTypes, $dbTypes);
                
                if(sizeof($diffMatches) > 0)
                {   
                    $result = preg_replace('#(\(.*?\))#','',implode('|', $diffMatches));
                    $unbreacketsDiffMatches = explode('|', $result); 

                    $i = 0;
                    $diffMatches = array_values($diffMatches);

                    foreach ($diffMatches as $diffVal)
                    {
                        if (in_array($unbreacketsDiffMatches[$i],$this->builderObject->dataTypes) OR in_array($unbreacketsDiffMatches[$i], $this->builderObject->attributeTypes)) // check is it datatype.
                        {
                            switch ($unbreacketsDiffMatches[$i])  
                            {
                                case '_varchar': // these types don't have brackets or contains non-numeric chars
                                case '_varbinary':

                                       preg_match('#('.$unbreacketsDiffMatches[$i].')(\(.*?\))#s',$diffMatches[$i], $match);
                                       preg_match('#(\(([0-9]*)\))#s',$diffMatches[$i], $bracketsMatch);

                                       $isBracketNull = (isset($bracketsMatch[2])) ? trim($bracketsMatch[2]) : '';

                                       isset($match[2]) AND ! empty($isBracketNull) ? $this->createSchemaDiff($k,$i,$diffMatches,true) : $this->createSchemaDiff($k,$i,$diffMatches); 
                                        
                                    break;
                                case '_enum': // these types don't have brackets or contains non-numeric chars
                                case '_set':

                                       preg_match('#('.$unbreacketsDiffMatches[$i].')(\(.*?\))#s',$diffMatches[$i], $match);
                                       preg_match('#(\((.*?)\))#s',$diffMatches[$i], $bracketsMatch);

                                        $isBracketNull = (isset($bracketsMatch[2])) ? trim($bracketsMatch[2]) : '';
                                        
                                        isset($match[2]) AND ! empty($isBracketNull) ? $this->createSchemaDiff($k,$i,$diffMatches,true) : $this->createSchemaDiff($k,$i,$diffMatches); 

                                    break;

                                default:
                                    
                                    $unbracketsDBColType      = preg_replace('#(\(.*?\))#s','', $this->dbSchema[$k]);
                                    $unbracketsDBColTypeArray = explode('|',$unbracketsDBColType);  // if any field has a primary key 

                                    $isBracketNull = false;
                                    if ($unbreacketsDiffMatches[$i] ==  '_foreign_key') // if foreign keys field is empty 
                                    {
                                        preg_match_all('#\((.*?)\)#s',$diffMatches[$i], $match);

                                        if (count($match[1]) == 3) 
                                        {
                                            for ($j=0; $j < count($match[1]); $j++) 
                                            { 
                                                $trimed = $match[1][$j];
                                               if (empty($trimed)) 
                                               {
                                                    $isBracketNull = true;
                                                    break;
                                               }
                                            }
                                        }
                                        else
                                        {
                                            $isBracketNull = true;
                                        }
                                    }
                                    if ( ! in_array('_primary_key' ,$allDbDataArray) || $unbreacketsDiffMatches[$i] != '_primary_key' && ! $isBracketNull) // if foreign keys fields is not  empty and any other primary key doesn't exist in db 
                                    {
                                        $this->createSchemaDiff($k,$i,$diffMatches,true); 
                                    }
                                    else
                                    {
                                        $this->createSchemaDiff($k,$i,$diffMatches);
                                    }
                                    break;
                            }
                        } 
                        else 
                        {
                            $this->createSchemaDiff($k,$i,$diffMatches);
                        }
                        $i++;
                    }
                }
            }
        }  // end foreach

    } // end function

    // --------------------------------------------------------------------

    /**
     * createSchemaDiff description
     * 
     * @param  string  $key         
     * @param  integer $i           
     * @param  array   $diffMatches 
     * @param  boolean $option      
     * @return void               
     */
    private function createSchemaDiff($key,$i,$diffMatches,$option = false)
    {
        if ($option) 
        {
            $this->schemaDiff[$key][] = array(
            'update_types' => $diffMatches[$i],
            'options' => array(
                'remove-from-file',
                'add-to-db'
                ),
            );
        }
        else
        {
            $this->schemaDiff[$key]['options'] = array(
                'class' => 'syncerror'
                );
            $this->schemaDiff[$key][] = array(
                    'update_types' => $diffMatches[$i], 
                    'options' => array(
                        'remove-from-file'
                        ),
                    );
        }
       
    }

}

// END Schema_Sync_Mysql class

/* End of file schema_sync_mysql.php */
/* Location: ./packages/schema_sync_mysql/releases/0.0.1/src/schema_sync_mysql.php */