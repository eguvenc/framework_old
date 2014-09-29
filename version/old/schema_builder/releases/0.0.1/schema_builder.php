<?php

/**
 * Schema Builder Adapter Class
 *
 * @package       packages
 * @subpackage    schema_builder
 * @category      schema
 * @link
 */

Abstract class Schema_Builder
{
    protected $_escape_char    = '`%s`';

    // --------------------------------------------------------------------

    /**
    * Use escape character
    * 
    * @param  string $val escaped value
    * @return string
    */
    public function quoteValue($val)
    {
        return sprintf($this->_escape_char, $val);
    }

    // --------------------------------------------------------------------

    /**
    * Remove underscored from data types
    * 
    * @param  string $str
    * @return string
    */
    public function removeUnderscore($str)
    {
        return str_replace('_',' ',$str);
    }

    // --------------------------------------------------------------------

    /**
    * Get number of data type count.
    * 
    * @param  string  $typeString
    * @return int
    */
    public function getDataTypeArray($typeString,$dataTypes)
    {
        return array_intersect(explode('|', $typeString),$dataTypes);
    }

    // --------------------------------------------------------------------

    /**
    * dropAttribute creates sql query for drop attribute
    * 
    * @param  string $attributeType native Column Type
    * @param  string $colType       Column Type
    * @param  string $dataType      Data Type
    * @return string                Sql Query for drop attribute
    */
    abstract function dropAttribute($attributeType,$colType,$dataType);

    // --------------------------------------------------------------------

    /**
    * Drop Column
    * 
    * @return string
    */
    abstract function dropColumn();

    // --------------------------------------------------------------------

    /**
    * Changing old column type with new one  
    * 
    * @param string $newColType 
    * @return string
    */
    abstract function modifyColumn($newColType);

    // --------------------------------------------------------------------
    
    /**
    * dropAttribute creates sql query for drop attribute
    * 
    * @param  string $attributeType native Column Types
    * @param  string $colType       Column Type
    * @param  string $dataType      Data Type
    * @return string                Sql Query for drop attribute
    */
    abstract function renameColumn($attributeTypes,$colType,$dataType);

    // --------------------------------------------------------------------

    /**
    * Creates schema file column array
    * 
    * @param string $columnType 
    * @param string $fileSchema 
    * @return array
    */
    abstract function addToFile($columnType,$fileSchema);

    // --------------------------------------------------------------------

    /**
     * Set schema name
     * 
     * @param string $schemaName
     */
    abstract function setSchemaName($schemaName);

    // --------------------------------------------------------------------

    /**
    * Set column name
    * 
    * @return type
    */
    abstract function setColName($columnName);
   
}

// END Abstract Class Schema_Builder

/* End of file Schema_Builder */
/* Location: ./packages/schema_builder/releases/0.0.1/schema_builder.php */
