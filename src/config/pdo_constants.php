<?php
defined('BASE') or exit('Access Denied!');  

/**
|--------------------------------------------------------------------------
| Database Result Type Constants
|--------------------------------------------------------------------------
| PDO paramater type constants
| @link http://php.net/manual/en/pdo.constants.php
|
| These prefs are used when working with query results.
|
*/
define('param_null', 0);  // null
define('param_int' , 1);  // integer
define('param_str' , 2);  // string
define('param_lob' , 3);  // integer  Large Object Data (lob)
define('param_stmt', 4);  // integer  Represents a recordset type ( Not currently supported by any drivers).
define('param_bool', 5);  // boolean                                
define('param_inout' , -2147483648); // PDO::PARAM_INPUT_OUTPUT integer

/**
|--------------------------------------------------------------------------
| Database Result Constants
|--------------------------------------------------------------------------
| These prefs are used when working with query results.
|
*/

/**
* PDO::FETCH_LAZY
* Fetch each row as an object with variable names 
* that correspond to the column names.
* 
* @return integer
*/
define('lazy', 1);

// --------------------------------------------------------------------

/**
* Fetch All query results
* in associative array data format
* 
* @return integer
*/
define('assoc', 2);

// --------------------------------------------------------------------

/**
* Fetch each row as an array
* indexed by column number
* 
* @return integer
*/
define('num', 3);

// --------------------------------------------------------------------

/**
* Fetch each row as an array 
* indexed by both column name and numbers
* 
* @return integer
*/
define('both', 4);

// --------------------------------------------------------------------

/**
* Fetch All query results
* in std object data format
* 
* @return integer
*/
define('obj', 5);
define('row', 5);

// --------------------------------------------------------------------

/**
* Specifies that the fetch method shall return TRUE 
* and assign the values of the columns in the result 
* set to the PHP variables to which they were bound 
* with the PDOStatement::bindParam() or PDOStatement::bindColumn() methods.
* 
* @return integer
*/
define('bound', 6);

// --------------------------------------------------------------------

/**
* Specifies that the fetch method shall return only a
* single requested column from the next row in the 
* result set
* 
* @return integer
*/
define('column', 7);

// --------------------------------------------------------------------

/**
* Specifies that the fetch method shall return a new instance
* of the requested class, mapping the columns to named p
* roperties in the class.
* 
* @return integer
*/
define('as_class', 8);

// --------------------------------------------------------------------

/**
* Specifies that the fetch method shall update an existing 
* instance of the requested class, mapping the columns to named 
* properties in the class.
* 
* @return integer
*/
define('into', 9);

// --------------------------------------------------------------------

/**
* Fetch func.
* @return integer
*/
define('func', 10);

// --------------------------------------------------------------------

/**
* Fetch each row as an array 
* indexed by column name
* 
* @return integer
*/
define('named', 11);

// --------------------------------------------------------------------

/**
* WARNING ! Available @since PHP 5.2.3
* Fetch into an array where the 1st column is a key and 
* all subsequent columns are value
* 
* @return integer
*/
define('key_pair', 12);

// --------------------------------------------------------------------

/**
* Fecth group. 
* @return integer
*/
define('group', 65536);

// --------------------------------------------------------------------

/**
* Fecth unique. 
* @return integer
*/
define('unique', 196608);

// --------------------------------------------------------------------
    
/**
* Determine the class name from the value of first column.
* 
* @return integer
*/
define('class_type', 262144);

// --------------------------------------------------------------------

/**
* Available @since PHP 5.1.0.
* As PDO::FETCH_INTO but object is provided 
* as a serialized string
* 
* @return integer
*/
define('serialize', 524288);

// --------------------------------------------------------------------

/**
* WARNING ! Available @since PHP 5.2.0
* 
* @return integer
*/
define('props_late', 1048576);


/**
|--------------------------------------------------------------------------
| Database Result Cursor Orientation Constants
|--------------------------------------------------------------------------
| These prefs are used when working with query results.
|
*/

// --------------------------------------------------------------------

/**
* Fetch the next row in the result set.
* Valid only for scrollable cursors. 
* 
* @return integer
*/
define('ori_next', 0);

// --------------------------------------------------------------------

/**
* Fetch the previous row in the result set.
* Valid only for scrollable cursors. 
* 
* @return integer
*/
define('ori_prior', 1);

// --------------------------------------------------------------------

/**
* Fetch the first row in the result set.
* Valid only for scrollable cursors. 
* 
* @return integer
*/
define('ori_first', 2);

// --------------------------------------------------------------------

/**
* Fetch the last row in the result set.
* Valid only for scrollable cursors. 
* 
* @return integer
*/
define('ori_last', 3);

// --------------------------------------------------------------------

/**
* Fetch the requested row by row number from the result set.
* Valid only for scrollable cursors. 
* 
* @return integer
*/
define('ori_abs', 4);

// --------------------------------------------------------------------

/**
* Fetch the requested row by relative position from the current 
* position of the cursor in the result set. 
* Valid only for scrollable cursors. 
* 
* @return integer
*/
define('ori_rel', 5);



/* End of file db_constants.php */
/* Location: ./obullo/config/db_constants.php */