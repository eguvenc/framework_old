<?php
namespace bench {

    // --------------------------------------------------------------------

    /**
    * Benchmark Helper
    *
    * @package     Obullo
    * @subpackage  benchmark
    * @category    benchmarks
    * @author      Obullo Team
    * @link        
    */
    
    /**
    * Bench Class.
    *
    * This class enables you to mark points and calculate the time difference
    * between them.  Memory consumption can also be displayed.
    */
    Class start {

        var $marker = array();

        public static $instance;

        public function __construct()
        {
            \log\me('debug', "Bench Class Initialized");
        }

        // --------------------------------------------------------------------

        public static function getInstance()
        {
            if( ! self::$instance instanceof self)
            {
                self::$instance = new self();
            } 

            return self::$instance;
        }

        // --------------------------------------------------------------------

        /**
        * Set a benchmark marker
        *
        * Multiple calls to this function can be made so that several
        * execution points can be timed
        *
        * @access	public
        * @param	string	$name	name of the marker
        * @return	void
        */
        public function mark($name)
        {
            $this->marker[$name] = microtime();
        }

        // --------------------------------------------------------------------

        /**
        * Calculates the time difference between two marked points.
        *
        * If the first parameter is empty this function instead returns the
        * {elapsed_time} pseudo-variable. This permits the full system
        * execution time to be shown in a template. The output class will
        * swap the real value for this variable.
        *
        * @access	public
        * @param	string	a particular marked point
        * @param	string	a particular marked point
        * @param	integer	the number of decimal places
        * @return	mixed
        */
        public function elapsedTime($point1 = '', $point2 = '', $decimals = 4)
        {
            if ($point1 == '')
            {
                return '{elapsed_time}';
            }

            if ( ! isset($this->marker[$point1]))
            {
                return '';
            }

            if ( ! isset($this->marker[$point2]))
            {
                $this->marker[$point2] = microtime();
            }

            list($sm, $ss) = explode(' ', $this->marker[$point1]);
            list($em, $es) = explode(' ', $this->marker[$point2]);

            return number_format(($em + $es) - ($sm + $ss), $decimals);
        }

    }
    
    /**
     * Get instance of Bench Class.
     * @return object
     */
    function getInstance()
    {
        return start::getInstance();
    }

    // Benchmark Functions.
    // -------------------------------------------------------------------- 

    /**
    * Set a benchmark marker
    *
    * Multiple calls to this function can be made so that several
    * execution points can be timed
    *
    * @access    public
    * @param     string    $name    name of the marker
    * @return    void
    */
    function mark($name)
    {
        getInstance()->mark($name);
    }
    
    // -------------------------------------------------------------------- 

    /**
    * Calculates the time difference between two marked points.
    *
    * @access   public
    * @param    string    a particular marked point
    * @param    string    a particular marked point
    * @param    integer   the number of decimal places
    * @return   mixed
    */
    function elapsedTime($point1 = '', $point2 = '', $decimals = 4)
    {        
        return getInstance()->elapsedTime($point1, $point2, $decimals);
    }
    
    // -------------------------------------------------------------------- 
    
    /**
    * Memory Usage
    *
    * @access    public
    * @return    string
    */
    function memoryUsage()
    {
        return '{memory_usage}';
    }
        
}


/* End of file Benchmark.php */
/* Location: ./ob/bench/releases/0.0.1/bench.php */