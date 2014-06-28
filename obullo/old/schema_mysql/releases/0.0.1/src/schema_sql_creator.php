<?php
namespace Schema_Mysql\Src;
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

Class Schema_Sql_Creator {

	public $sqlOutput;   // Sql output
	public $schemaArray  = array(); // Schema Data
	public $_escape_char = '`%s`';	// Escape character
	public $_tableSuffix = ' ENGINE=InnoDB DEFAULT CHARSET=utf8;'; // Sql table suffix
	public $dataTypes = array(
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
			'_set');

	/**
	 * Build database object
	 * 
	 * @param object $dbObject database connector
	 */
	public function __construct()
	{
		$this->sqlOutput = null;

		$config = getConfig('schema');  // Get schema configuration file
		$this->_tableSuffix = $config['mysql_table_suffix'];
	}

	// --------------------------------------------------------------------

	/**
	 * Create Sql Output
	 * 
	 * @param  array $schemaArray
	 * @param  string $tablename
	 * @return string sql
	 */
	public function createSQL($tablename)
	{
		$this->schemaArray = getSchema($tablename); // Get schema configuration.;
		unset($this->schemaArray['*']); // Get only fields no settings

		// print_r($this->schemaArray);

        // [Create Table] => CREATE TABLE `check` (
		//   `id` int(11) NOT NULL AUTO_INCREMENT,
		//   `email` varchar(200) NOT NULL,
		//   `lastname` varchar(60) NOT NULL,
		//   `firstname` varchar(60) NOT NULL,
		//   `test` varchar(200) DEFAULT NULL,
		//   `control` varchar(200) NOT NULL,
		//   `t1` varchar(231) NOT NULL,
		//   `t2` int(11) NOT NULL,
		//   PRIMARY KEY (`id`),
		//   UNIQUE KEY `email` (`email`),
		//   UNIQUE KEY `control` (`control`),
		//   UNIQUE KEY `t1` (`t1`,`t2`),
		//   KEY `name` (`lastname`,`firstname`),
		//   KEY `test` (`test`), key(test)
		//   CONSTRAINT `check_ibfk_1` FOREIGN KEY (`id`) REFERENCES `users` (`id`),
		//   CONSTRAINT `check_ibfk_2` FOREIGN KEY (`test`) REFERENCES `packages` (`name`)
		// ) ENGINE=InnoDB DEFAULT CHARSET=latin1
	
		if(is_array($this->schemaArray))
		{
			$startQuery = 'CREATE TABLE IF NOT EXISTS '.$tablename.' ( ';
			$fieldLine  = array();

			foreach($this->schemaArray as $key => $field)
			{
				if(isset($field['types']) AND ! empty($field['types']))
				{
					$trimmedRules = trim($field['types'], '|');
		
					if(strpos($trimmedRules, '|') > 0)
					{
						$exp = explode('|', $trimmedRules);
					}
					else 
					{
						$exp = array($trimmedRules);
					}

					$i = 0;
					foreach($exp as $rule)
					{
						$sqlData = $this->_buildSqlLine($key, $rule);

						if(is_array($sqlData)) // Type
						{
							$fieldLine[$key]['type'] = $sqlData['type'];
						}
						elseif(is_string($sqlData)) // Store Attributes with A-Z sort compatible.
						{
							$sqlItem = trim($sqlData);

							if($sqlItem == 'unsigned')
							{
								$fieldLine[$key]['attributes']['a'] = $sqlData;
							}

							if(strpos($sqlItem, 'unsigned zerofill') !== false)
							{
								$fieldLine[$key]['attributes']['a'] = $sqlData;
							}

							if($sqlItem == 'NOT NULL')
							{
								$fieldLine[$key]['attributes']['b'] = $sqlData;
							}

							if($sqlItem == 'NULL')
							{
								$fieldLine[$key]['attributes']['b'] = $sqlData;
							}

							if(strpos($sqlItem, 'DEFAULT') === 0)
							{
								$fieldLine[$key]['attributes']['c'] = $sqlData;
							}

							if($sqlItem == 'AUTO_INCREMENT')
							{
								$fieldLine[$key]['attributes']['x'] = $sqlData;
							}

							if(strpos($sqlItem, '#') === 0) // store KEY,FOREIGN KEY,PRIMARY KEY data end of the array
							{
								++$i;
								$lk = 'z_'.$i;
								$fieldLine[$key]['attributes'][$lk] = $sqlData;
							}
						}
					}
				}
			}

			// ------------ KEY SORT FOR ATTRIBUTES ------------//

			foreach ($fieldLine as $fieldKEY => $fieldVALUES)
			{
				if(isset($fieldVALUES['attributes']))
				{
					ksort($fieldVALUES['attributes']);  // keysort alphabetical

					$fieldLine[$fieldKEY] = $fieldVALUES;
				}
			}

			########### DEBUG ###########

			// print_r($fieldLine);
			
			#############################

			// ------------ BUILD SQL ----------//

			$sql = '';
			$sqlLine = '';
			$PRIMARY_KEY = '';
			$FOREIGN_KEYS = '';
			foreach($fieldLine as $fieldKey => $fieldVal)
			{
				$fieldType 		= isset($fieldVal['type']) ? $fieldVal['type'] : '';
				$fieldAttribute = '';

				if(isset($fieldVal['attributes']))
				{
					foreach($fieldVal['attributes'] as $val)
					{
						if( ! empty($val) AND ! is_array($val))
						{
							$fieldAttribute.= ' '.$val;
						}
					}
				}

				$sqlLine = trim($this->quoteValue($fieldKey).' '.$fieldType.$fieldAttribute).",\n";

				if(preg_match('/(#_foreign_key)\((.*?)\)\((.*?)\)(#)/', $sqlLine, $_fk_matches)) // Detect keys forign keys
				{
					$_fk_reference = explode(')(',$_fk_matches[3]);
					$_fk = $this->quoteValue($fieldKey);
					$_fk_reference_table = $this->quoteValue($_fk_reference[0]);
					$_fk_reference_field = $this->quoteValue($_fk_reference[1]);
					$_fk_name = $this->quoteValue($_fk_matches[2]);
					$FOREIGN_KEYS.= "\nCONSTRAINT $_fk_name FOREIGN KEY ($_fk) REFERENCES $_fk_reference_table ($_fk_reference_field),"; 
				}

				if(strpos($fieldAttribute, '#_primary_key#') > 0)  // Detect primary key 
				{
					$PRIMARY_KEY = $fieldKey;
				}

				$sql.= $sqlLine;
			}

			$sql = rtrim(trim($sql), ',');
			$sql = $this->_buildKeys($sql, '_key', 'KEY');	// create `KEY` s
			$sql = $this->_buildKeys($sql, '_unique_key', 'UNIQUE KEY');  // create `UNIQUE KEY` s
			$sql = $this->_buildKeys($sql, '_primary_key', 'PRIMARY KEY',$PRIMARY_KEY); // create `PRIMARY KEY` s
			$sql.= trim($FOREIGN_KEYS, ',');

			$sql = preg_replace('/(#)(.*?)(#)/', '', $sql);  // REMOVE TEMPS
			$sql = trim($sql, ',');

			$endQuery = ')'.$this->_tableSuffix;
	
			return $startQuery.$sql.$endQuery;
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Build sql line foreach columns
	 * 
	 * @param  string $field
	 * @param  string $rule
	 * @return string
	 */
	public function _buildSqlLine($field, $rule)
	{
		$ruleType = $rule;

		if(strpos($rule, '_') === 0 AND strpos($rule, '(') > 0)
		{
			$ruleType = preg_replace('/\((.*?)\)/', '', $rule);
		}

		if(in_array($ruleType, $this->dataTypes))
		{
 			return array('type' => $this->_parseType($ruleType, $rule, $field));
		}
		else 
		{
			$attrribute = '';

			if($this->_hasRule('_unsigned', $rule) AND $this->_hasRule('_zerofill', $rule) == false)
			{
				$attrribute.= 'unsigned';
			}

			if($this->_hasRule('_unsigned_zerofill', $rule))
			{
				$attrribute.= 'unsigned zerofill';
			}

			if($this->_hasRule('_null', $rule) AND $this->_hasRule('_default', $rule) == false AND $this->_hasRule('_not_null', $rule) == false)
			{
				$attrribute.= 'NULL';
			}

			if($this->_hasRule('_default', $rule))
			{
				$value = $this->removeQuotes($this->_getRuleValue($rule));

				if($value == 'CURRENT_TIMESTAMP' OR $value == 'NULL')  // Do not use quotes.
				{
					$attrribute.= "DEFAULT ".$this->removeQuotes($value);
				} 
				else 
				{
					$attrribute.= "DEFAULT '".$this->removeQuotes($value)."'";
				}
			}

			if($this->_hasRule('_not_null', $rule))
			{
				$attrribute.= 'NOT NULL';
			}

			if($this->_hasRule('_auto_increment', $rule))
			{
				$attrribute.= 'AUTO_INCREMENT';
			}

			if($this->_hasRule('_primary_key', $rule))
			{
				$attrribute.= " #$rule#";  // we use "#"" temp to catching attributes
			}
			
			// Catch all *_key* words
	
			if($this->_hasRule('_key', $rule) AND $this->_hasRule('_primary_key', $rule) == false)
			{
				$attrribute.= " #$rule#";
			}

			return $attrribute;
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Create sql `KEY' and `UNIQUE KEY` clauses
	 *  
	 * @param  string $sql    sql line
	 * @param  string $_key   schema key type  "_key" or "_unique_key"
	 * @param  string $sqlKey `KEY' or `UNIQUE KEY`
	 * @return string line of sql keys
	 */
	function _buildKeys($sql, $_key = '_key', $sqlKey = 'KEY',$primaryKey = null)
	{
		if(strpos($sql, '#'.$_key) > 0)  // Detect keys and multiple keys
		{
			if (preg_match_all('/(#'.$_key.')\((.*?)\)(#)/', $sql, $_keyMatches, PREG_SET_ORDER)) 
			{
				$keyIndexArray = array();
				$KEYS = '';
				$keyImplode = '';
				foreach ($_keyMatches as $kmValue)
				{
					$keyImplode = $kmValue[2];
					if(strpos($keyImplode, ')(') > 0)
					{
						$kVal = '';
						$exp = explode(')(', $keyImplode);
						$keyIndex  = $exp[0];
						unset($exp[0]);
						if (!in_array($keyIndex,$keyIndexArray))  // if index name exist in index array for DUPLICATE ERROR
						{
							$keyIndexArray[]=$keyIndex;
							$implodeKeys = array();
							foreach($exp as $item)
							{
								if(strpos($item, ',') !== false)
								{
									foreach (explode(',', $item) as $k => $v)
									{
										$implodeKeys[] = $this->quoteValue($v);
									}
								} else 
								{
									$implodeKeys = array($this->quoteValue($item));
								}
							}
							if(isset($keyIndex))
							{
								$KEYS.= ",\n$sqlKey ".$this->quoteValue($keyIndex).' ('.implode($implodeKeys, ',').')';
							} 
							else
							{
								$KEYS.= ",\n$sqlKey ".$this->quoteValue($kmValue[2]).' ('.$this->quoteValue($kmValue[2]).')';
							}
						}
					}
					else
					{
						if (!in_array($_keyMatches[0][2],$keyIndexArray))  // if index name exist in index array for DUPLICATE ERROR
						{

							$keyIndexArray[] = $_keyMatches[0][2];
							$KEYS.= ",\n$sqlKey  ".'('.$_keyMatches[0][2].')';
						}
					}
				}

				$sql.= $KEYS;
				$sql = preg_replace('/(#'.$_key.')(.*?)(#)/', '', $sql);  // REMOVE TEMPS

				return $sql;
			}
			else
			{
				$PRIMARY_KEY = ",\nPRIMARY KEY (".$this->quoteValue($primaryKey)."),";
				$sql .= $PRIMARY_KEY;
				$sql = preg_replace('/(#'.$_key.')(#)/', '', $sql);  // REMOVE TEMPS
			}
		}

		return $sql;
	}

	// --------------------------------------------------------------------

	/**
	 * Use escape character
	 * 
	 * @param  string $val escaped value
	 * @return string
	 */
	private function quoteValue($val)
	{
		return sprintf($this->_escape_char,$val);
	}

	// --------------------------------------------------------------------

	/**
	 * Parse types
	 * 
	 * @param  string $type  column type
	 * @param  string $rule  column rule
	 * @param  string $field column name
	 * @return string        data type
	 */
	private function _parseType($type = '', $rule, $field)
	{
		$not_null = '';
		$function = $type;
		if($this->_hasRule($type, $rule))
		{			
			$value = $this->_getRuleValue($rule);
			if(method_exists($this, $function))
			{
				if($type == '_enum' OR $type == '_set')
				{
					$dataType = $this->{$function}($value, $field);
				} 
				else 
				{
					$dataType = $this->{$function}($value);
				}
			} 
			else 
			{
				$dataType = '';
			}
		}

		return "$dataType";
	}

	// Data type methods
	// --------------------------------------------------------------------

	private function _bit($value) { if(empty($value)){ return 'bit'; } return "bit($value)"; }
	private function _tinyint($value){ if(empty($value)){ return 'tinyint';} return "tinyint($value)";  }
	private function _smallint($value){ if(empty($value)){ return 'smallint';} return "smallint($value)"; }
	private function _mediumint($value){ if(empty($value)){ return 'mediumint';} return "mediumint($value)";  }
	private function _integer($value = ''){ return $this->_int($value); }
	private function _bigint($value = ''){ if(empty($value)){ return 'bigint';} return "bigint($value)"; }
	private function _real($value = ''){ if(empty($value)){ return 'real'; } return "real($value)"; }
	private function _double($value = ''){ if(empty($value)){ return 'double'; } return "double($value)"; }
	private function _float($value = ''){ if(empty($value)){ return 'float'; } return "float($value)"; }
	private function _decimal($value = ''){ if(empty($value)){ return 'decimal'; } return "decimal($value)"; }
	private function _numeric($value = ''){ if(empty($value)){ return 'numeric'; } return "numeric($value)"; }
	private function _date($value = ''){ return 'date'; }
	private function _time($value = ''){ return 'time'; }
	private function _timestamp($value = ''){ return 'timestamp'; }
	private function _datetime($value = ''){ return 'datetime'; }
	private function _year($value = ''){ return 'year'; }
	private function _char($value = ''){ if(empty($value)){ return 'char';} return "char($value)"; }
	private function _varchar($value = ''){ if(empty($value)){ return 'varchar';} return "varchar($value)"; }
	private function _binary($value = ''){ if(empty($value)){ return 'binary';} return "binary($value)"; }
	private function _varbinary($value = ''){ if(empty($value)){ return 'varbinary';} return "varbinary($value)"; }
	private function _tinyblob($value = ''){ if(empty($value)){ return 'tinyblob';} return "tinyblob($value)"; }
	private function _blob($value = ''){ if(empty($value)){ return 'blob';} return "blob($value)"; }
	private function _mediumblob($value = ''){ if(empty($value)){ return 'mediumblob';} return "mediumblob($value)"; }
	private function _longblob($value = ''){ if(empty($value)){ return 'longblob';} return "longblob($value)"; }
	private function _tinytext($value = ''){ if(empty($value)){ return 'tinytext';} return "tinytext($value)"; }
	private function _text($value = ''){ if(empty($value)) { return 'text'; } return "text($value)"; }
	private function _mediumtext($value = ''){ if(empty($value)) { return 'mediumtext'; } return "mediumtext($value)"; }
	private function _longtext($value = ''){ if(empty($value)) { return 'mediumtext'; } return "mediumtext($value)"; }
	private function _enum($value = '', $field = '', $func = '_enum')
	{
		if(isset($this->schemaArray[$field][$func]) AND is_array($this->schemaArray[$field][$func])) 		// parse from schema
		{
			$closure = function($val){   /// run this function for every enum and set value
				return "'".$this->removeQuotes(addslashes($val))."'"; 
			};
			$values = array();
			foreach($this->schemaArray[$field][$func] as $k => $v)
			{
				$values[] = $closure($v);
			}
			return trim($func,'_').'('.implode(',',$values).')';
		}
		// return trim($func,'_').'('.str_replace('"', "'", $value).')'; 
	}
	private function _set($value = '', $field = '', $func = '_set'){ return $this->_enum($value, $field, $func); }
	private function _int($value)
	{
		if(empty($value))
		{
			return "int";
		}

		if($value <= 4) // tinyint
		{
			return "tinyint($value)";
		}

		if($value <= 5) // smallint
		{
			return "smallint($value)";
		}

		if($value <= 9)	// mediumint
		{
			return "mediumint($value)";
		}

		if($value <= 11) // int
		{
			return "int($value)";
		}

		if($value <= 20) // bigint
		{
			return "bigint($value)";
		}

		if($value > 20) // bigint
		{
			return "bigint($value)";
		}
		
		return "int";
	}

	// --------------------------------------------------------------------

	/**
	 * Check rule string has contains
	 * requested rule ?
	 * 
	 * @param  string $rule complete rulename or part of the rule
	 * @param  string  $ruleString complete rule string
	 * @return boolean
	 */
	private function _hasRule($rule, $ruleString)
	{
		if(strpos($ruleString, $rule) === false)
		{
			return false;
		}

		return true;
	}

	// --------------------------------------------------------------------

	/**
	 * Check rule has value like int(9)
	 * it it has value return it.
	 * 
	 * @param  string $rule complete rule _int(11) 
	 * @return string value
	 */
	private function _getRuleValue($rule)
	{
    	if(preg_match('/\((.*?)\)/', $rule, $matches))
    	{
    		return $matches[1];
    	}

		return;
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

}

// END Schema_Table_Creator class

/* End of file schema_table_creator.php */
/* Location: ./packages/schema_mysql/releases/0.0.1/schema_table_creator.php */