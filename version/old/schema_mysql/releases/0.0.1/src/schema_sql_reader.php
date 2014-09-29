<?php
namespace Schema_Mysql\Src;

/**
 * Schema Mysql
 * Table Column Reader for Model
 *
 * @package       packages 
 * @subpackage    schema_mysql
 * @category      schemas
 * @link            
 */

Class Schema_Sql_Reader {

	public $db; 			     // Database Object
	public $enumData = array();  // Write _enum data as array
	public $setData  = array();  // Write _set data as array

	/**
	 * Build database object
	 * 
	 * @param object $dbObject database connector
	 */
	public function __construct($dbObject)
	{
		$this->db = $dbObject;

		$this->sqlOutput = null;
		$this->enumData  = array();
	}

	// --------------------------------------------------------------------

	############# EXAMPLE DATABASE OUTPUT #######################
	// Array
	// (
	//     [0] => Array
	//         (
	//             [Table] => check
	//             [Create Table] => CREATE TABLE `check` (
	//   `id` int(11) NOT NULL AUTO_INCREMENT,
	//   `email` varchar(200) NOT NULL,
	//   `lastname` varchar(60) NOT NULL,
	//   `firstname` varchar(60) NOT NULL,
	//   `test` varchar(200) DEFAULT NULL,
	//   `kontrol` varchar(200) NOT NULL,
	//   `t1` varchar(231) NOT NULL,
	//   `t2` int(11) NOT NULL,
	//   PRIMARY KEY (`id`),
	//   UNIQUE KEY `email` (`email`),
	//   UNIQUE KEY `kontrol` (`kontrol`),
	//   UNIQUE KEY `t1` (`t1`,`t2`),
	//   KEY `name` (`lastname`,`firstname`),
	//   KEY `test` (`test`),
	//   CONSTRAINT `check_ibfk_1` FOREIGN KEY (`id`) REFERENCES `users` (`id`),
	//   CONSTRAINT `check_ibfk_2` FOREIGN KEY (`test`) REFERENCES `packages` (`name`)
	// ) ENGINE=InnoDB DEFAULT CHARSET=latin1
	//         )
	// )
	#######################################################################################

	/**
	 * Read Table Column Information
	 * And Create Schema Fields
	 * 
	 * @return string
	 */
	public function readSQL($tablename)
	{
		$keyData = $this->db->query('SHOW CREATE TABLE '.$tablename)->getResultArray();

		$tempFkArray  = $this->_buildForeignKeys($keyData);
		$tempUkArray  = $this->_buildUniqueKeys($keyData);
		$tempKeyArray = $this->_buildKeys($keyData);
		$tempPkArray  = $this->_buildPrimaryKeys($keyData);
		
		$columns = $this->db->query('SHOW COLUMNS FROM '.$tablename)->getResultArray();

        if(count($columns) > 0)
        {	
        	$schemaContent = '';
        	foreach($columns as $col)
        	{
		    		
        		if (isset($tempFkArray[$col['Field']])) 
        		{
        			$col['_foreign_keys'][] = $tempFkArray[$col['Field']];
        			unset($tempFkArray[$col['Field']]);
        		}

        		if (isset($tempPkArray[$col['Field']])) 
        		{
        			$col['_primary_keys'][] = $tempPkArray[$col['Field']];
        			unset($tempPkArray[$col['Field']]);
        		}

        		if (isset($tempUkArray[$col['Field']])) 
        		{
        			$col['_unique_keys'][] = $tempUkArray[$col['Field']];
        			unset($tempUkArray[$col['Field']]);
        		}

        		if (isset($tempKeyArray[$col['Field']])) 
        		{
        			$col['_keys'][] = $tempKeyArray[$col['Field']];
        			unset($tempKeyArray[$col['Field']]);

        		}
        		$schemaContent.= $this->_buildRow($col); // Output 'password' = array('rules' => 'required|_string(20)|minLen(6)');
        	}

        	return $schemaContent;
        }

        return false;
	}

	// --------------------------------------------------------------------

	/**
	 * Build Column Row
	 * (
     *   [0] => Array
     *   (
     *       [Field] => id
     *       [Type] => int(11)
     *       [Null] => NO
     *       [Key] => PRI
     *       [Default] => 
     *       [Extra] => auto_increment
     *       [_indexes] => array( key => array('user_email', 'name' => 'last_name, first_name' ))
     *       [_unique_keys] => array( 'id' ) // multiple de olabilir 
     *       [_foreign_keys] => ''
     *   )
	 * 
	 * @param  string $col mysql column array
	 * @return string column rules
	 */
	private function _buildRow($col)
	{
		$label = $this->_createLabel($col['Field']);

		$startArray = "array(\n\t\t'label' => '$label',\n\t\t'types' => ";
		$rulesArray = "\n\t\t'rules' => '',\n\t\t";
		$endArray   = "),";

		$typeString = $this->_buildRuleString($col);
		if($typeString == false) // no rule
		{
			return "'".trim($col['Field'])."' => '',\n";
		}

		// parse _enum & _set data
		$enumArray = '';
		$setArray  = '';
		if(isset($this->enumData[$col['Field']]))
		{
			$enumString = $this->enumData[$col['Field']];

			// sanitize comma.
			$enumString = preg_replace('#(?<=[\w\s+])(?:[,]+)#', '__TEMP_COMMA__', $enumString); // Thanks to Obullo Team ( ali ihsan çağlayan & burak abir)

		 	$enumArray = "\n\t\t'_enum' => array(";	// render enum types
		 	foreach(explode(',', trim(trim($enumString, ')'),'(')) as $v)
		 	{
				$v = str_replace('__TEMP_COMMA__',',',$v);
		 		$enumArray.= "\n\t\t\t".str_replace('"',"'",$v).","; // add new line after that for each comma
		 	}

		 	$enumArray.= "\n\t\t),";
		}
		elseif(isset($this->setData[$col['Field']]))
		{			
		 	$setArray = "\n\t\t'_set' => array(";	// render enum types
		 	foreach(explode(',', trim(trim($this->setData[$col['Field']], ')'),'(')) as $v)
		 	{
		 		$setArray.= "\n\t\t\t".str_replace('"',"'",$v).","; // add new line after that for each comma
		 	}
		 	$setArray.= "\n\t\t),";
		}

		return "\n\t'".trim($col['Field'])."' => ".$startArray."'".$typeString."',".$enumArray.$setArray.$rulesArray.$endArray;
	}

	// --------------------------------------------------------------------

	/**
	 * Create UNIQUE KEY(s)
	 * 
	 * @param  array $row key data
	 * @return string uniq key data
	 */
	private function _buildUniqueKeys($row)
	{
		$matches 	= array();
		$uniqueKeys = array();

		foreach($row as $key => $sql)
		{
			foreach ($sql as $key) 
			{
				if(preg_match_all('#UNIQUE KEY\s+([^\(^\s]+)\s*\(([^\)]+)\)#mi', $key, $matches, PREG_SET_ORDER))
				{
					break;
				}
			}
		}
		foreach($matches as $match)
		{
			$keys = array_map('trim', explode(',', str_replace(array('`','"'), '', $match[2])));

			foreach($keys as $k => $name)
			{
				$uniqueKeys[$name][] = array(str_replace(array('`','"'), '', $match[1]) => str_replace(array('`','"') ,'', $match[2]));
			}
		}

		return $uniqueKeys;
	}

	// --------------------------------------------------------------------

	/**
	 * Create PRIMARY KEY(s)
	 * 
	 * @param  array $row key data
	 * @return string uniq key data
	 */
	private function _buildPrimaryKeys($row)
	{
		$matches 	 = array();
		$primaryKeys = array();

		foreach($row as $key => $sql)
		{
			foreach ($sql as $key) 
			{
				if(preg_match_all('#PRIMARY KEY\s+\((.*?)\)#mi', $key, $matches, PREG_SET_ORDER))
				{
					break;
				}
			}
		}

		foreach($matches as $match)
		{
			$keys = array_map('trim', explode(',', str_replace(array('`','"'), '', $match[1])));

			if (sizeof($keys) > 1) 
			{
				foreach($keys as $k => $name)
				{
					$primaryKeys[$name][] = array('pk' => str_replace(array('`','"') ,'', $match[1]));
				}
			}
			else
			{
				$primaryKeys[$keys[0]] = array();
			}

		}

		return $primaryKeys;
	}

	// --------------------------------------------------------------------

	/**
	 * Create KEY(s)
	 * 
	 * @param  array $row key data
	 * @return string key data
	 */
	private function _buildKeys($row)
	{
		$matches   = array();
		$indexKeys = array();

		foreach($row as $key => $sql)
		{
			foreach ($sql as $key) 
			{
				if(preg_match_all('/\s+\s+KEY\s+([^\(^\s]+)\s*\(([^\)]+)\)/mi', $key, $matches, PREG_SET_ORDER))
				{
					break;
				}
			}
		}

		foreach($matches as $match)
		{
			$keys = array_map('trim', explode(',', str_replace(array('`','"'), '', $match[2])));

			foreach($keys as $k => $name)
			{
				$indexKeys[$name][] = array(str_replace(array('`','"'), '', $match[1]) => str_replace(array('`','"'), '', $match[2]));
			}
		}

		return $indexKeys;
	}

	// --------------------------------------------------------------------

	/**
	 * Create FOREIGN KEY(s)
	 * 
	 * @param  array $row key data
	 * @return string key data
	 */
	private function _buildForeignKeys($row)
	{
		$matches 	 = array();
		$foreignKeys = array();

		foreach($row as $key => $sql)
		{
			foreach ($sql as $key) 
			{
				if(preg_match_all('/FOREIGN KEY\s+\(([^\)]+)\)\s+REFERENCES\s+([^\(^\s]+)\s*\(([^\)]+)\)/mi', $key, $matches, PREG_SET_ORDER) && preg_match_all('/CONSTRAINT\s+([^\(^\s]+)/mi', $key, $constraitNames, PREG_SET_ORDER))
				{
					break;
				}
			}			
		}
		for ($i=0; $i < sizeof($matches); $i++) 
		{ 
			$keys = array_map('trim', explode(',', str_replace(array('`','"'), '', $matches[$i][1])));
			$fks  = array_map('trim', explode(',', str_replace(array('`','"'), '', $matches[$i][3])));
			
			for ($k=0; $k <sizeof($keys) ; $k++) 
			{ 
				$foreignKeys[$keys[$k]][] = array($constraitNames[$i][$k+1],$keys[$k],str_replace(array('`','"'), '', $matches[$i][2]), $fks[$k]);
			}
			
		}
		return $foreignKeys;
	}

    // --------------------------------------------------------------------

	/**
	 * Build one column rule 
	 * ( e.g. '_string|valid_email|maxLen(160)' )
	 * 
	 * @param  array $col column array
	 * @return string rules => '_string|valid_email|maxLen(160)'
	 */
	private function _buildRuleString($col) // Create rules for selected column.
	{
		$rules = '';

		if(isset($col['Null']))	// Add null data
		{
			if($col['Null'] == 'NO')
			{
				$rules = '_not_null|';
			}

			if($col['Null'] == 'YES')
			{
				$rules = '_null|';
			}
		}

		if(isset($col['Default'])) // Add auto increment
		{
			if($col['Default'] == 'CURRENT_TIMESTAMP' OR $col['Default'] == 'NULL')
			{

				$rules.= '_default('.addslashes($col['Default']).')|';	
			} 
			else 
			{
				$rules.= '_default('.addslashes($col['Default']).')|';
			}
		}

		if(isset($col['Type']))  // Parse types and Build Schema Rules
		{
			$typeName = $col['Type'];

			if($this->_typeHasValue($typeName))  // e.g. varchar(60)
			{
				$val = $this->_getTypeValues($typeName);

				$typeName  = $val['typeName'];
				$typeValue = $val['typeValue'];
				$attribute = $val['typeAttribute'];
			}

			if(isset($attribute))
			{
				if($attribute == 'unsigned')
				{
					$rules.= "_unsigned|"; //  Whether the number is positive. It must be positive.
				}

				if($attribute == 'unsigned zerofill')
				{
					$rules.= "_unsigned_zerofill|";
				}
			}

			######################################
			# 
			# MYSQL STRING DATA TYPES
			#
			######################################

			//------------- VARCHAR -------------//

			if($typeName == 'varchar')
			{
				if(isset($typeValue))
				{
					$rules.= '_varchar('.$typeValue.')|';
				} 
				else 
				{
					$rules.= '_varchar(255)|';
				}
			}
			
			//------------- CHAR -------------//

			if($typeName == 'char')
			{				
				if(isset($typeValue))
				{
					$rules.= '_char('.$typeValue.')';
				} 
				else 
				{
					$rules.= '_char(255)|';
				}
			}

			//------------- TEXT -------------//

			if($typeName == 'text')
			{
				if(isset($typeValue))
				{
					$rules.= '_text('.$typeValue.')|';
				} 
				else 
				{
					$rules.= '_text|';
				}
			}

			//------------- TINYTEXT -------------//
			
			if($typeName == 'tinytext')
			{
				if(isset($typeValue))
				{
					$rules.= '_tinytext('.$typeValue.')|';
				} 
				else 
				{
					$rules.= '_tinytext|';
				}
			}

			//------------- MEDIUMTEXT -------------//
			
			if($typeName == 'mediumtext')
			{
				$rules.= '_mediumtext|';
			}

			//------------- LONGTEXT -------------//
			
			if($typeName == 'longtext')
			{
				$rules.= '_longtext|';
			}

			//------------- ENUM -------------//
			
			if($typeName == 'enum')
			{
				if(isset($typeValue))
				{
					$this->enumData[$col['Field']] = $this->sanitizeEnum($typeValue);
				}

				$rules.= '_enum|';
			}

			//------------- SET -------------//

			if($typeName == 'set')
			{
				if(isset($typeValue))
				{
					$this->setData[$col['Field']] = $this->sanitizeEnum($typeValue);
				}

				$rules.= '_set|';
			}

			######################################
			# 
			# MYSQL BINARY DATA TYPES
			#
			######################################

			// @link http://dev.mysql.com/doc/refman/5.0/en/binary-varbinary.html

			//------------- VARBINARY -------------//

			if($typeName == 'varbinary')
			{
				if(isset($typeValue))
				{
					$rules.= '_varbinary('.$typeValue.')|';
				} 
				else 
				{
					$rules.= '_varbinary|';
				}
			}

			//------------- BINARY -------------//

			if($typeName == 'binary')
			{				
				if(isset($typeValue))
				{
					$rules.= '_binary('.$typeValue.')|';
				} 
				else 
				{
					$rules.= '_binary|';
				}
			}

			//------------- BLOB -------------//
			
			if($typeName == 'blob')
			{
				if(isset($typeValue))
				{
					$rules.= '_blob('.$typeValue.')';
				} 
				else 
				{
					$rules.= '_blob|';
				}
			}

			//------------- TINYBLOB -------------//
			
			if($typeName == 'tinyblob')
			{
				if(isset($typeValue))
				{
					$rules.= '_tinyblob('.$typeValue.')';
				} 
				else 
				{
					$rules.= '_tinyblob|';
				}
			}

			//------------- MEDIUMBLOB -------------//
			
			if($typeName == 'mediumblob')
			{
				$rules.= '_mediumblob|';
			}

			//------------- LONGBLOB -------------//
			
			if($typeName == 'longblob')
			{
				$rules.= '_longblob|';
			}

			######################################
			# 
			# MYSQL NUMERIC DATA TYPES
			#
			######################################

			//------------- BIT -------------//

			if($typeName == 'bit')
			{				
				if(isset($typeValue))
				{
					$rules.= '_bit('.$typeValue.')|';
				} 
				else 
				{
					$rules.= '_bit|';
				}
			}

			//------------- INT -------------//

			if($typeName == 'int')
			{				
				if(isset($typeValue))
				{
					$rules.= '_int('.$typeValue.')|';
				} 
				else 
				{
					$rules.= '_int(11)|';
				}
			}

			//------------- TINYINT -------------//

			if($typeName == 'tinyint')
			{				
				if(isset($typeValue))
				{
					$rules.= '_tinyint('.$typeValue.')|';
				} 
				else 
				{
					$rules.= '_tinyint(4)|';
				}
			}

			//------------- SMALLINT -------------//

			if($typeName == 'smallint')
			{				
				if(isset($typeValue))
				{
					$rules.= '_smallint('.$typeValue.')|';
				} 
				else 
				{
					$rules.= '_smallint(5)|';
				}
			}

			//------------- MEDIUMINT -------------//

			if($typeName == 'mediumint')
			{				
				if(isset($typeValue))
				{
					$rules.= '_mediumint('.$typeValue.')|';
				} 
				else 
				{
					$rules.= '_mediumint(9)|';
				}
			}

			//------------- BIGINT -------------//

			if($typeName == 'bigint')
			{				
				if(isset($typeValue))
				{
					$rules.= '_bigint('.$typeValue.')|';
				} 
				else 
				{
					$rules.= '_bigint(20)|';
				}
			}

			//------------- FLOAT -------------//

			if($typeName == 'float')
			{				
				if(isset($typeValue))
				{
					$rules.= '_float('.$typeValue.')|';
				} 
				else 
				{
					$rules.= '_float|';
				}
			}

			//------------- DOUBLE -------------//

			if($typeName == 'double')
			{				
				if(isset($typeValue))
				{
					$rules.= '_double('.$typeValue.')|';
				} 
				else 
				{
					$rules.= '_double|';
				}
			}

			//------------- DECIMAL -------------//

			if($typeName == 'decimal')
			{
				if(isset($typeValue))
				{
					$rules.= '_decimal('.$typeValue.')|';
				} 
				else 
				{
					$rules.= '_decimal|';
				}
			}

			######################################
			# 
			# MYSQL DATE TYPES
			#
			######################################

			//------------- DATE -------------//

			if($typeName == 'date')
			{				
				$rules.= '_date|';
			}

			//------------- DATETIME -------------//

			if($typeName == 'datetime')
			{				
				$rules.= '_datetime|';
			}

			//------------- TIMESTAMP -------------//

			if($typeName == 'timestamp') // unix time
			{	
				$rules.= '_timestamp|';
			}

			//------------- TIME -------------//

			if($typeName == 'time')
			{	
				$rules.= '_time|';
			}
			
			//------------- YEAR -------------//

			if($typeName == 'year')
			{				
				if(isset($typeValue))
				{
					$rules.= '_year(4)|';
				} 
				else 
				{
					$rules.= '_year(4)|';
				}
			}
		}

    	// -------------------- BUILD KEYS --------------------//

		if(isset($col['Extra']) AND $col['Extra'] == 'auto_increment') // Add auto increment
		{
			$rules.= '_auto_increment|';
		}

		if(isset($col['_foreign_keys']))  // Add Foreign Key 
		{
			for ($i=0; $i < sizeof($col['_foreign_keys'][0]) ; $i++)  // get all unique keys for field
			{ 
				$rules.= '_foreign_key';
				for ($j=0; $j < sizeof($col['_foreign_keys'][0][$i]); $j++)
				{ 
					if ($j != '1') 
					{
						$trimValue = trim($col['_foreign_keys'][0][$i][$j],'`');
						$rules.= '('.$trimValue.')';
					}
					
				}
				$rules.= '|';
				
			}
		}

		if(isset($col['_keys']))  // Add Index 
		{
			for ($i=0; $i < sizeof($col['_keys'][0]) ; $i++) // get all keys for field
			{ 
				$temp  = array_keys($col['_keys'][0][$i]); 
				$rules.= '_key('.$temp[0].')('.$col['_keys'][0][$i][$temp[0]].')|';
			}
		}

		if(isset($col['_unique_keys']))  // Add Unique Key  
		{
			for ($i=0; $i < sizeof($col['_unique_keys'][0]) ; $i++)  // get all unique keys for field
			{ 
				$temp  = array_keys($col['_unique_keys'][0][$i]);
				$rules.= '_unique_key('.$temp[0].')('.$col['_unique_keys'][0][$i][$temp[0]].')|';
			}
		}
		

		if(isset($col['_primary_keys']))  // Add Primary Key  
		{
			if (sizeof($col['_primary_keys'][0])>0) // this is for composite keys
			{
				for ($i=0; $i < sizeof($col['_primary_keys'][0]) ; $i++)  // get all unique keys for field
				{ 

					$temp = array_keys($col['_primary_keys'][0][$i]);

					$rules.= '_primary_key('.$col['_primary_keys'][0][$i][$temp[0]].')|';
				}
			}
			else
			{
				$rules.= '_primary_key|';
			}
			
			
		}

		return trim($rules,'|');
	}

    // --------------------------------------------------------------------

	/**
	 * Create column label automatically
	 * 
	 * @param  string $field field name
	 * @return string column label
	 */
	private function _createLabel($field)
	{
		$exp = explode('_', $field); // explode underscores ..

		if($exp)
		{
			$label = '';
			foreach($exp as $val)
			{
				$label.= ucfirst($val).' ';
			}
		} 
		else 
		{
			$label = ucfirst($field);
		}

		return trim($label);
	}

    // --------------------------------------------------------------------

	/**
	 * Check column type has value e.g. varchar(20)
	 *
	 * @param  string $type
	 * @return boolean 
	 */
	private function _typeHasValue($type)
	{
		if(strpos($type, '(') > 0)
		{
			return true;
		}

		return false;
	}

    // --------------------------------------------------------------------

	/**
	 * Fetch column type and value
	 * 
	 * @param  string $type type
	 * @return array type name and type value
	 */
	private function _getTypeValues($type)
	{
		$exp = explode('(', $type);

		$typeName  = $type;
		$typeValue = '';
		$attribute = '';

		if(strpos($type, 'unsigned zerofill')) // check type attributes
		{
			$attribute = 'unsigned_zerofill';
		}

		if(strpos($type, 'unsigned')) // check type attributes
		{
			$attribute = 'unsigned';
		}

		if ($exp !== false AND preg_match('/\((.*?)\)/', $type, $matches)) // render type and values
		{ 
		    $typeName  = current($exp);
		   	$typeValue = $matches[1];
		} 

		return array('typeName' => $typeName, 'typeValue' => $typeValue, 'typeAttribute' => $attribute);
	}

    // --------------------------------------------------------------------

	/**
	 * Remove Double and Single Quotes
	 * 
	 * @param  string $str input
	 * @return string $str output
	 */
	function removeQuotes($str)
	{
		return trim(trim($str, '"'), "'");
	}

	// --------------------------------------------------------------------

	/**
	 * Sanitize Enum chars (,'")
	 * 
	 * @param  string $enumData
	 * @return array
	 */
	function sanitizeEnum($enumData)
	{
        $enumData = preg_replace('#(?<=[\w\s+])(?:[,]+)#', '__TEMP_COMMA__', $enumData); // sanitize comma.

        $enumArray = array();
        foreach(explode(',', trim(trim($enumData,')'),'(')) as $v)
        {
            $v = str_replace('__TEMP_COMMA__',',',$v);  // come back again comma chars
            $v = trim(trim($v,"'"),'"');
            $v = preg_replace("#[']+#", "'", $v);  // remove mysql escaped quotes \''
            $v = "'".addslashes(addslashes($v))."'";
            $enumArray[] = $v;
        }

        return implode(',', $enumArray); // render as array
	}

}

/*
------------------------------------------------
| Mysql Data Types:
------------------------------------------------
  | BIT[(length)]
  | TINYINT[(length)] [UNSIGNED] [ZEROFILL]
  | SMALLINT[(length)] [UNSIGNED] [ZEROFILL]
  | MEDIUMINT[(length)] [UNSIGNED] [ZEROFILL]
  | INT[(length)] [UNSIGNED] [ZEROFILL]
  | INTEGER[(length)] [UNSIGNED] [ZEROFILL]
  | BIGINT[(length)] [UNSIGNED] [ZEROFILL]
  | REAL[(length,decimals)] [UNSIGNED] [ZEROFILL]
  | DOUBLE[(length,decimals)] [UNSIGNED] [ZEROFILL]
  | FLOAT[(length,decimals)] [UNSIGNED] [ZEROFILL]
  | DECIMAL[(length[,decimals])] [UNSIGNED] [ZEROFILL]
  | NUMERIC[(length[,decimals])] [UNSIGNED] [ZEROFILL]
  | DATE
  | TIME
  | TIMESTAMP
  | DATETIME
  | YEAR
  | CHAR[(length)]
  | VARCHAR(length)
  | BINARY[(length)]
  | VARBINARY(length)
  | TINYBLOB
  | BLOB
  | MEDIUMBLOB
  | LONGBLOB
  | TINYTEXT [BINARY]
  | TEXT [BINARY]
  | MEDIUMTEXT [BINARY]
  | LONGTEXT [BINARY]
  | ENUM(value1,value2,value3,...)
  | SET(value1,value2,value3,...)
*/

// END Schema_Sql_Reader class

/* End of file schema_sql_reader.php */
/* Location: ./packages/schema_mysql/releases/0.0.1/src/schema_sql_reader.php */