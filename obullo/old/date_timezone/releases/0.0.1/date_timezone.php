<?php

/**
 * Date Timezone Class
 *
 * @package     packages
 * @subpackage  date
 * @category    timezone
 * @link        
 */
Class Date_Timezone
{
    public function __construct()
    {
        global $logger;
        getInstance()->translator->load('date_format');
        
        if ( ! isset(getInstance()->date_timezone)) {
            getInstance()->date_timezone = $this; // Make available it in the controller $this->date_timezone->method();
        }

        $logger->debug('Date_Timezone Class Initialized');
    }

    // -----------------------------------------------------------------------

    /**
     * Timezone Menu
     *
     * Generates a drop-down menu of timezones.
     *
     * @access	public
     * @param	string	timezone
     * @param	string	classname
     * @param	string	menu name
     * @return	string
     */
    function getMenu($default = 'UTC', $class = '', $name = 'timezones')
    {
        if ($default == 'GMT') {
            $default = 'UTC';
        }

        $menu = '<select name="' . $name . '"';
        if ($class != '') {
            $menu .= ' class="' . $class . '"';
        }
        $menu .= ">\n";

        foreach ($this->getZones() as $key => $val) {
            $selected = ($default == $key) ? " selected='selected'" : '';
            $menu .= "<option value='{$key}'{$selected}>" . translate($key) . "</option>\n";
        }
        $menu .= "</select>";

        return $menu;
    }

    // ------------------------------------------------------------------------

    /**
     * Timezones
     *
     * Returns an array of timezones.  This is a helper function
     * for various other ones in this library
     *
     * @access	public
     * @param	string	timezone
     * @return	string
     */
    function getZones($tz = '')
    {
        // Note: Don't change the order of these even though
        // some items appear to be in the wrong order

        $zones = array(
            'UM12' => -12,
            'UM11' => -11,
            'UM10' => -10,
            'UM95' => -9.5,
            'UM9' => -9,
            'UM8' => -8,
            'UM7' => -7,
            'UM6' => -6,
            'UM5' => -5,
            'UM45' => -4.5,
            'UM4' => -4,
            'UM35' => -3.5,
            'UM3' => -3,
            'UM2' => -2,
            'UM1' => -1,
            'UTC' => 0,
            'UP1' => +1,
            'UP2' => +2,
            'UP3' => +3,
            'UP35' => +3.5,
            'UP4' => +4,
            'UP45' => +4.5,
            'UP5' => +5,
            'UP55' => +5.5,
            'UP575' => +5.75,
            'UP6' => +6,
            'UP65' => +6.5,
            'UP7' => +7,
            'UP8' => +8,
            'UP875' => +8.75,
            'UP9' => +9,
            'UP95' => +9.5,
            'UP10' => +10,
            'UP105' => +10.5,
            'UP11' => +11,
            'UP115' => +11.5,
            'UP12' => +12,
            'UP1275' => +12.75,
            'UP13' => +13,
            'UP14' => +14
        );

        if ($tz == '') {
            return $zones;
        }

        if ($tz == 'GMT') {
            $tz = 'UTC';
        }

        return (!isset($zones[$tz])) ? 0 : $zones[$tz];
    }

}

/* End of file date_timezone.php */
/* Location: ./packages/date_timezone/releases/0.0.1/date_timezone.php */