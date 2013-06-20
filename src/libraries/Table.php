<?php
defined('BASE') or exit('Access Denied!');

/**
 * Obullo Framework (c) 2009.
 *
 * PHP5 HMVC Based Scalable Software.
 * 
 * @package         obullo       
 * @author          obullo.com
 * @copyright       Ersin Guvenc (c) 2009.
 * @filesource
 * @license
 */

/**
 * Table Genarator Class
 *
 * @package     Obullo
 * @subpackage  Libraries
 * @category    Libraries
 * @author      Ersin Guvenc
 * @link        
 */
Class OB_Table {

    public $rows               = array();
    public $heading            = array();
    public $auto_heading       = TRUE;    
    public $caption            = NULL;    
    public $template           = NULL;
    public $newline            = "\n";
    public $empty_cells        = "";
    
    public function __construct()
    {
        log_me('debug', "Table Class Initialized");
    }

    // --------------------------------------------------------------------

    /**
     * Set the template
     *
     * @access    public
     * @param    array
     * @return    void
     */
    public function set_template($template)
    {
        if ( ! is_array($template))
        {
            return FALSE;
        }
    
        $this->template = $template;
    }

    // --------------------------------------------------------------------

    /**
     * Set the table heading
     *
     * Can be passed as an array or discreet params
     *
     * @access    public
     * @param     mixed
     * @return    void
     */
    public function set_heading()
    {
        $args = func_get_args();
        $this->heading = (is_array($args[0])) ? $args[0] : $args;
    }

    // --------------------------------------------------------------------

    /**
     * Set columns.  Takes a one-dimensional array as input and creates
     * a multi-dimensional array with a depth equal to the number of
     * columns.  This allows a single array with many elements to  be
     * displayed in a table that has a fixed column count.
     *
     * @access   public
     * @param    array
     * @param    int
     * @return   void
     */
    public function make_columns($array = array(), $col_limit = 0)
    {
        if ( ! is_array($array) OR count($array) == 0)
        {
            return FALSE;
        }
        
        // Turn off the auto-heading feature since it's doubtful we 
        // will want headings from a one-dimensional array
        $this->auto_heading = FALSE;
        
        if ($col_limit == 0)
        {
            return $array;
        }
    
        $new = array();
        while(count($array) > 0)
        {    
            $temp = array_splice($array, 0, $col_limit);
            
            if (count($temp) < $col_limit)
            {
                for ($i = count($temp); $i < $col_limit; $i++)
                {
                    $temp[] = '&nbsp;';
                }
            }
            
            $new[] = $temp;
        }
        
        return $new;
    }

    // --------------------------------------------------------------------

    /**
     * Set "empty" cells
     *
     * Can be passed as an array or discreet params
     *
     * @access   public
     * @param    mixed
     * @return   void
     */
    public function set_empty($value)
    {
        $this->empty_cells = $value;
    }
    
    // --------------------------------------------------------------------

    /**
     * Add a table row
     *
     * Can be passed as an array or discreet params
     *
     * @access   public
     * @param    mixed
     * @return   void
     */
    public function add_row()
    {
        $args = func_get_args();
        $this->rows[] = (is_array($args[0])) ? $args[0] : $args;
    }

    // --------------------------------------------------------------------

    /**
     * Add a table caption
     *
     * @access   public
     * @param    string
     * @return   void
     */
    public function set_caption($caption)
    {
        $this->caption = $caption;
    }    

    // --------------------------------------------------------------------

    /**
     * Generate the table
     *
     * @access   public
     * @param    mixed
     * @return   string
     */
    public function generate($table_data = NULL)
    {
        // The table data can optionally be passed to this function
        // either as a database result object or an array
        if ( ! is_null($table_data))
        {
            if (is_object($table_data))
            {
                $this->_set_from_object($table_data);
            }
            elseif (is_array($table_data))
            {
                $set_heading = (count($this->heading) == 0 AND $this->auto_heading == FALSE) ? FALSE : TRUE;
                $this->_set_from_array($table_data, $set_heading);
            }
        }
    
        // Is there anything to display?  No?  Smite them!
        if (count($this->heading) == 0 AND count($this->rows) == 0)
        {
            return 'Undefined table data';
        }
    
        // Compile and validate the template date
        $this->_compile_template();
    
    
        // Build the table!
        
        $out = $this->template['table_open'];
        $out .= $this->newline;        

        // Add any caption here
        if ($this->caption)
        {
            $out .= $this->newline;
            $out .= '<caption>' . $this->caption . '</caption>';
            $out .= $this->newline;
        }

        // Is there a table heading to display?
        if (count($this->heading) > 0)
        {
            $out .= $this->template['heading_row_start'];
            $out .= $this->newline;        

            foreach($this->heading as $heading)
            {
                $out .= $this->template['heading_cell_start'];
                $out .= $heading;
                $out .= $this->template['heading_cell_end'];
            }

            $out .= $this->template['heading_row_end'];
            $out .= $this->newline;                
        }

        // Build the table rows
        if (count($this->rows) > 0)
        {
            $i = 1;
            foreach($this->rows as $row)
            {
                if ( ! is_array($row))
                {
                    break;
                }
            
                // We use modulus to alternate the row colors
                $name = (fmod($i++, 2)) ? '' : 'alt_';
            
                $out .= $this->template['row_'.$name.'start'];
                $out .= $this->newline;        
    
                foreach($row as $cell)
                {
                    $out .= $this->template['cell_'.$name.'start'];
                    
                    if ($cell === "")
                    {
                        $out .= $this->empty_cells;
                    }
                    else
                    {
                        $out .= $cell;
                    }
                    
                    $out .= $this->template['cell_'.$name.'end'];
                }
    
                $out .= $this->template['row_'.$name.'end'];
                $out .= $this->newline;    
            }
        }

        $out .= $this->template['table_close'];
    
        return $out;
    }
    
    // --------------------------------------------------------------------

    /**
     * Clears the table arrays.  Useful if multiple tables are being generated
     *
     * @access    public
     * @return    void
     */
    public function clear()
    {
        $this->rows               = array();
        $this->heading            = array();
        $this->auto_heading       = TRUE;    
    }
    
    // --------------------------------------------------------------------

    /**
     * Set table data from a database result object
     *
     * @access   public
     * @param    object
     * @return   void
     */
    public function _set_from_object($query)
    {
        if ( ! is_object($query))
        {
            return FALSE;
        }
        
        // First generate the headings from the table column names
        if (count($this->heading) == 0)
        {   
            $table_list    = $query->fetch_all(result::assoc()); // Obullo Changes ..
            $this->heading = array_keys($table_list[0]);         // Obullo Changes ..
        }
                
        // Next blast through the result array and build out the rows
        if (count($table_list) > 0)
        { 
            foreach ($table_list as $row) // Obullo Changes ..
            {
                $this->rows[] = $row;
            }
        }
        
        unset($table_list);
    }

    // --------------------------------------------------------------------

    /**
     * Set table data from an array
     *
     * @access    public
     * @param    array
     * @return    void
     */
    public function _set_from_array($data, $set_heading = TRUE)
    {
        if ( ! is_array($data) OR count($data) == 0)
        {
            return FALSE;
        }
        
        $i = 0;
        foreach ($data as $row)
        {
            if ( ! is_array($row))
            {
                $this->rows[] = $data;
                break;
            }
                        
            // If a heading hasn't already been set we'll use the first row of the array as the heading
            if ($i == 0 AND count($data) > 1 AND count($this->heading) == 0 AND $set_heading == TRUE)
            {
                $this->heading = $row;
            }
            else
            {
                $this->rows[] = $row;
            }
            
            $i++;
        }
    }

    // --------------------------------------------------------------------

    /**
     * Compile Template
     *
     * @access    private
     * @return    void
     */
     private function _compile_template()
     {     
         if ($this->template == NULL)
         {
             $this->template = $this->_default_template();
             return;
         }
        
        $this->temp = $this->_default_template();
        foreach (array('table_open','heading_row_start', 'heading_row_end', 'heading_cell_start', 'heading_cell_end', 'row_start', 'row_end', 'cell_start', 'cell_end', 'row_alt_start', 'row_alt_end', 'cell_alt_start', 'cell_alt_end', 'table_close') as $val)
        {
            if ( ! isset($this->template[$val]))
            {
                $this->template[$val] = $this->temp[$val];
            }
        }     
     }
    
    // --------------------------------------------------------------------

    /**
     * Default Template
     *
     * @access    private
     * @return    void
     */
    private function _default_template()
    {
        return  array (
                        'table_open'             => '<table border="0" cellpadding="4" cellspacing="0">',

                        'heading_row_start'     => '<tr>',
                        'heading_row_end'         => '</tr>',
                        'heading_cell_start'    => '<th>',
                        'heading_cell_end'        => '</th>',

                        'row_start'             => '<tr>',
                        'row_end'                 => '</tr>',
                        'cell_start'            => '<td>',
                        'cell_end'                => '</td>',

                        'row_alt_start'         => '<tr>',
                        'row_alt_end'             => '</tr>',
                        'cell_alt_start'        => '<td>',
                        'cell_alt_end'            => '</td>',

                        'table_close'             => '</table>'
                    );    
    }
    

}


/* End of file Table.php */
/* Location: ./obullo/libraries/Table.php */