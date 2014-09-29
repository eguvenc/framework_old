<?php

/**
 * Schema Mysql Driver for Model Class
 *
 * @package       packages 
 * @subpackage    schema_mysql
 * @category      models
 * @link            
 */

Class Schema_Mysql {

	public $db;		   	     // Database object
	public $tablename; 		 // Valid tablename
	public $schemaObject;	 // Schema Object

	/**
	 * Construct Schema Driver Paramaters
	 * 
	 * @param object $schemaObject
	 * @param object $dbObject
	 */
	public function __construct($schemaObject)
	{
		$this->schemaObject = $schemaObject; // Store schema object

		$this->tablename = $schemaObject->getTableName();
		$this->db 		 = $schemaObject->getDbObject();
	}

	// --------------------------------------------------------------------

	/**
	 * Build the Schema File content
	 * using your database table.
	 * 
	 * @return string schema file string
	 */
	public function read()
	{
		if($this->tableExists() == 1) // If Already exists read from sql ?
		{		
			$tableReader   = new Schema_Mysql\Src\Schema_Sql_Reader($this->db); // Read column information from table
	        $schemaContent = $tableReader->readSQL($this->tablename);
            
	        if($schemaContent == false)
	        {
	        	return false;
	        }

	        return $schemaContent;
		}
		
		return false;
	}

	// --------------------------------------------------------------------

	/**
	 * Read schemaArray and build Mysql
	 * Query Output
	 * 
	 * @return string sql output
	 */
	public function create()
	{
		$tableCreator = new Schema_Mysql\Src\Schema_Sql_Creator();

        return $tableCreator->createSQL($this->tablename);
	}

	// --------------------------------------------------------------------

	/**
	 * Synchronize schema model
	 * and database table
	 * 
	 * @return string sync results
	 */
	public function sync()
	{                           
		$schemaContent = $this->read();

		if($schemaContent != false)
		{           
			$sync_mysql = new Schema_Sync_Mysql($schemaContent, $this->schemaObject); // Schema_Sync_Mysql
            $sync_mysql->run(); 
            
			if($sync_mysql->collisionExists())
			{	
				echo $sync_mysql->output(); // Display sync table to developer
				exit;  				  		// die current process
			}

		}
	}
 
	// --------------------------------------------------------------------
	
    /**
     * Build schema string
     * 
     * @param  fieldname $key
     * @param  database types $types
     * @param  string $newType
     * @return string
     */
    public function buildSchemaField($key, $types, $newType = '')
    {
        $fileSchema        = getSchema($this->tablename);
        $currentFileSchema = $fileSchema;
        unset($fileSchema['*']);  // Get only fields no settings

        $label = (isset($currentFileSchema[$key]['label'])) ? $currentFileSchema[$key]['label'] : $this->schemaObject->_createLabel($key);
        // $rules = (isset($currentFileSchema[$key]['rules'])) ? $currentFileSchema[$key]['rules'] : '';

        $ruleString = "\n\t'$key' => array(";
        // $ruleString.= "\n\t\t'label' => '$label',";  // fetch label from current schema

        // --------- RENDER FUNC ----------//
        
        if(isset($currentFileSchema[$key]['func']))  // Don't remove functions
        {
            $schemaFile = file_get_contents($this->schemaObject->getPath());
            $schemaFile = str_replace(array('<?php', '<?'),'',$schemaFile);
            preg_match("#'$key'.*?'func'\s*=>\s*((?:(array.*?}\s*\)\s*,))|(?![array])(.*?}\s*,))#s",$schemaFile, $matches);

            if( isset($matches[1]))
            {
                $ruleString.= "\n\t\t'func' => $matches[1]";  // fetch _func from current schema
            }
        }

        // --------- RENDER FUNC ----------//

        if(empty($newType))
        {
            //------------ PARSE _ENUM -----------//

            if(isset($currentFileSchema[$key]['_enum']))  // if _enum exists convert it to string for array rendering.
            {
                $types = $this->_parseType('_enum', $types, $currentFileSchema[$key]['_enum']);
            }

            //------------ PARSE _SET -----------//
            
            if(isset($currentFileSchema[$key]['_set']))  // if _set exists convert it to string for array rendering.
            {
                $types = $this->_parseType('_set', $types, $currentFileSchema[$key]['_set']);
            }

            //------------ RENDER ENUM -----------//

            if (preg_match('#(_enum)(\(.*?\))#s',$types, $match) ) // if type is enum create enum field as an array
            {
                $enumStr  = $match[0];  // _enum("","")
                $enum     = $match[1];  // _enum
                $enumData = $match[2];  // ("","")

                $types = preg_replace('#'.preg_quote($enumStr).'#', '_enum', $types);
                
                $ruleString .= "\n\t\t'_enum' => array(";   // render enum types

                $enumData = preg_replace('#(?<=[\w\s+])(?:[,]+)#', '__TEMP_COMMA__', $enumData); // sanitize comma.
                foreach(explode(',', trim(trim($enumData,')'),'(')) as $v)
                {
                    $v = str_replace('__TEMP_COMMA__',',',$v);
                    $ruleString .= "\n\t\t\t".$v.","; // add new line after that for each comma
                }
                $ruleString .= "\n\t\t),";
                $types = str_replace($enumData, '', $types);
            }

            //------------ RENDER SET -----------//

            if (preg_match('#(_set)(\(.*?\))#s',$types, $match) ) // if type is enum create enum field as an array
            {
                $setStr  = $match[0];  // _set("","")
                $set     = $match[1];  // _set
                $setData = $match[2];  // ("","")

                $types = preg_replace('#'.preg_quote($setStr).'#', '_set', $types);
                
                $ruleString .= "\n\t\t'_set' => array(";   // render enum types

                $setData = preg_replace('#(?<=[\w\s+])(?:[,]+)#', '__TEMP_COMMA__', $setData); // sanitize comma.
                foreach(explode(',', trim(trim($setData,')'),'(')) as $v)
                {
                    $v = str_replace('__TEMP_COMMA__',',',$v);
                    $ruleString .= "\n\t\t\t".$v.","; // add new line after that for each comma
                }
                $ruleString .= "\n\t\t),";
                $types = str_replace($setData, '', $types);
            }

            $types = preg_replace('#(\|+|\|\s+)(?:\|)#', '|', $types); // remove unecessary pipes "||" ... prevent pipe repeats
            $ruleString.= "\n\t\t'types' => '".addslashes($types)."',";  // add adslashes for "'" quotes e.g.  _varchar(255)|_default('\true\')|
        }
        else 
        {
            $typeStr    = (is_array($types)) ? $newType : $types.'|'.$newType; // new field comes as array data we need to prevent it.
            $ruleString.= "\n\t\t'types' => '".$typeStr."',";
        }

        // $ruleString.= "\n\t\t'rules' => '$rules',"; // fetch the validation rules from current schema
        $ruleString.= "\n\t\t),";

        return $ruleString;
    }

	// --------------------------------------------------------------------

    /**
     * Parse _enum & _set
     * 
     * @param  string $type
     * @param  string $types
     * @return string 
     */
    public function _parseType($type = '_enum', $types, $data)
    {
        $setData = '(';
        foreach($data as $v)
        {
            $setData.= '"'.$v.'",';
        }
        $setData = rtrim($setData,',');
        $setData.= ')';

        preg_match('#('.$type.')(\(.*?\))#s',$types, $match);

        if (isset($match[2])) 
        {
            $types = preg_replace('#'.preg_quote($match[0]).'#', $type.$match[2], $types);
        }
        else
        {
            $types = str_replace($type, $type.$setData, $types);  // $types = str_replace('_enum', '_enum'.$setData, $types);    
        }

        return $types;
    }

    // --------------------------------------------------------------------

	/**
	 * Check table exists
	 * 
	 * @return int
	 */
	public function tableExists()
	{
        $this->db->query("SHOW TABLES LIKE '".$this->tablename."'");

        if($this->db->getCount() > 0)
        {
        	return true;
        }

       	return false;
	}

    // /**
    //  * Get the schema driver name
    //  * 
    //  * @return string
    //  */
    // public function getDriverName()
    // {
    //     $dbConfig = getConfig('database');
    //     $exp = explode('_', get_class($dbConfig[Db::$var]));
    //     return 'Schema_Sync_'.ucfirst($exp[1]);
    // }
    
}

// END Schema_Mysql class

/* End of file schema_mysql.php */
/* Location: ./packages/schema_mysql/releases/0.0.1/schema_mysql.php */