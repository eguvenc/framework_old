<?php
/**
 * Mongo Db Class Crud Dabase Results.
 * 
 * A library that interfaces with Mongo_Db Package
 * through Crud functions.
 * 
 * Derived from https://github.com/Garciat/codeigniter-mongodb
 */

Class Mongo_Db_Results {

	protected $rows;	// row array data
	protected $current_row; // current data
	protected $count_rows;  // number of rows
	
	/**
	 * Constructor
	 * 
	 * @param array $rows result array
	 */
	public function __construct($rows = null)
	{
		$this->rows        = $rows;
		$this->current_row = 0;
		$this->count_rows  = count($rows);
	}

	// ------------------------------------------------------------------------
	
	/**
	 * Fetch all results as object.
	 * 
	 * @return array
	 */
	public function getResult()
	{
		$rows = array();

		foreach($this->rows as $row)
		{
			$rows[] = (object)$row;
		}

		return $rows;
	}
	
    // ------------------------------------------------------------------------

	/**
	 * Fetch all results as array
	 * 
	 * @return array
	 */
	public function getResultArray()
	{
		return $this->rows;
	}

    // ------------------------------------------------------------------------

	/**
	 * Fetch row as object
	 * 
	 * @param  integer $n [description]
	 * @return [type]     [description]
	 */
	public function getRow()
	{
		return (isset($this->rows[0])) ? (object)$this->rows[0] : (object)$this->rows;
	}

    // ------------------------------------------------------------------------
	
	/**
	 * Fetch results as array
	 * 
	 * @param  integer $n
	 * @return array
	 */
	public function getRowArray()
	{
		return (isset($this->rows[0])) ? $this->rows[0] : (array)$this->rows;
	}

    // ------------------------------------------------------------------------

	/**
	 * Fetch first row as object
	 * 
	 * @return object
	 */
	public function getFirstRow()
	{
        $result = $this->_getRow();

        if (count($result) == 0)
        {
            return $result;
        }
        
        return $result[0];
	}

    // ------------------------------------------------------------------------
	
	/**
	 * Fetch previous row as object
	 * 
	 * @return object
	 */
	public function getPreviousRow()
	{
        $result = $this->_getRow();

        if (count($result) == 0)
        {
            return $result;
        }

        if (isset($result[$this->current_row - 1]))
        {
            --$this->current_row;
        }
        
        return $result[$this->current_row];

	}
	
    // ------------------------------------------------------------------------

	/**
	 * Fetch next row as object
	 * 
	 * @return object
	 */
	public function getNextRow()
	{
		$result = $this->_getRow();

        if(count($result) == 0)
        {
            return $result;
        }
        
        if(isset($result[$this->current_row + 1]))
        {
            ++$this->current_row;
        }

        return $result[$this->current_row];
	}

    // ------------------------------------------------------------------------

	/**
	 * Fetch last row as object
	 * 
	 * @return object
	 */
	public function getLastRow()
	{
		$result = $this->_getRow();

        if (count($result) == 0)
        {
            return $result;
        }

        return $result[count($result) - 1];
	}
	
    // ------------------------------------------------------------------------
    
	/**
	 * Get number of rows
	 * 
	 * @return integer
	 */
	public function getCount()
	{
		return (int)$this->count_rows;
	}

    // ------------------------------------------------------------------------

	/**
	 * Private function
	 * 
	 * @return mixed
	 */
	private function _getRow()
	{
		return $this->rows;
	}

}

// END Mongo_Db Class

/* End of file mongo_db.php */
/* Location: ./packages/mongo_db/releases/0.0.1/mongo_db.php */