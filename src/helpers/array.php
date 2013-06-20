<?php 
defined('BASE') or exit('Access Denied!'); 

/**
 * Obullo Framework (c) 2009.
 *
 * PHP5 HMVC Based Scalable Software.
 *
 * @package         obullo       
 * @author          obullo.com
 * @license         public
 * @since           Version 1.0
 * @filesource
 * @license
 */

// ------------------------------------------------------------------------

/**
 * Obullo Array Helpers
 *
 * @package     Obullo
 * @subpackage  Helpers
 * @category    Helpers
 * @author      Obullo Team
 * @link        
 */

// ------------------------------------------------------------------------

/**
* Convert Array to Object
* 
* @param array $array
* @return stdClass 
*/
if( ! function_exists('array_to_object') ) 
{
    function array_to_object($array)
    {
        if( ! is_array($array))
        {
            return $array;
        }

        $object = new stdClass();
        
        if (is_array($array) AND count($array) > 0)
        {
            foreach ($array as $key => $value)
            {
                $key = strtolower(trim($key));

                if ( ! empty($key))
                {
                    $object->{$key} = array_to_object($value);
                }
            }
        }
        else
        {
            return FALSE;
        }

        return $object; 
    }
}

// ------------------------------------------------------------------------
    
/**
* Convert object variables
* to array.
* 
* @param object $object
* @return array 
*/
if( ! function_exists('object_to_array') ) 
{
    function object_to_array($object)
    {
        if ( ! is_object($object))
        {
            return $object;
        }
        
        if(is_object($object))
        {
            foreach ($object as $key => $value)
            {
                $key = strtolower(trim($key));

                if ( ! empty($key))
                {
                    $array[$key] = object_to_array($value);
                }
            }
        }
        else
        {
            return FALSE;
        }

        return $array;   
    }
}

// -------------------------------------------------------------------- 

/**
* Check Array is_associative
* or not.
* 
* @param type $a
* @return type 
*/
if( ! function_exists('is_assoc_array')) 
{
    function is_assoc_array( $a )
    {
        return is_array( $a ) && ( count( $a ) !== array_reduce( array_keys( $a ), create_function( '$a, $b', 'return ($b === $a ? $a + 1 : 0);' ), 0 ) );
    }
}

// -------------------------------------------------------------------- 

/**
 * Element
 *
 * Lets you determine whether an array index is set and whether it has a value.
 * If the element is empty it returns FALSE (or whatever you specify as the default value.)
 *
 * @access	public
 * @param	string
 * @param	array
 * @param	mixed
 * @return	mixed	depends on what the array contains
 */	
if( ! function_exists('element') ) 
{
    function element($item, $array, $default = FALSE)
    {
        if ( ! isset($array[$item]) OR $array[$item] == "")
        {
            return $default;
        }

        return $array[$item];
    }
}	

// ------------------------------------------------------------------------

/**
 * Random Element - Takes an array as input and returns a random element
 *
 * @access	public
 * @param	array
 * @return	mixed	depends on what the array contains
 */	
if( ! function_exists('random_element') ) 
{
    function random_element($array)
    {
        if ( ! is_array($array))
        {
            return $array;
        }
        
        return $array[array_rand($array)];
    }
}	



/* End of file array.php */
/* Location: ./obullo/helpers/array.php */