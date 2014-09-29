<?php

/**
 * Date Format Class
 *
 * @package     packages
 * @subpackage  date
 * @category    date & time
 * @link        
 */
Class Date_Format
{
    public function __construct()
    {
        global $logger;
        getInstance()->translator->load('date_format');
        
        if (!isset(getInstance()->date_format)) {
            getInstance()->date_format = $this; // Make available it in the controller $this->date_format->method();
        }

        $logger->debug('Date_Format Class Initialized');
    }

    // ------------------------------------------------------------------------

    /**
     * Convert MySQL Style Datecodes
     *
     * This function is identical to PHPs date() function,
     * except that it allows date codes to be formatted using
     * the MySQL style, where each code letter is preceded
     * with a percent sign:  %Y %m %d etc...
     *
     * The benefit of doing dates this way is that you don't
     * have to worry about escaping your text letters that
     * match the date codes.
     *
     * @access   public
     * @param    string
     * @param    integer
     * @return   integer
     */
    public function getDate($datestr = '', $time = '')
    {
        if ($datestr == '') {
            return '';
        }

        if ($time == '') {
            $time = $this->getNow();
        }

        $datestr = str_replace('%\\', '', preg_replace("/([a-z]+?){1}/i", "\\\\\\1", $datestr));
        return date($datestr, $time);
    }

    // ------------------------------------------------------------------------

    /**
     * Number of days in a month
     *
     * Takes a month/year as input and returns the number of days
     * for the given month/year. Takes leap years into consideration.
     *
     * @access   public
     * @param    integer a numeric month
     * @param    integer a numeric year
     * @return   integer
     */
    public function getDaysInMonth($month = 0, $year = '')
    {
        if ($month < 1 OR $month > 12) {
            return 0;
        }

        if (!is_numeric($year) OR strlen($year) != 4) {
            $year = date('Y');
        }

        if ($month == 2) {
            if ($year % 400 == 0 OR ($year % 4 == 0 AND $year % 100 != 0)) {
                return 29;
            }
        }

        $days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
        return $days_in_month[$month - 1];
    }

    // ------------------------------------------------------------------------

    /**
     * Get "now" time
     *
     * Returns time() or its GMT equivalent based on the config file preference
     *
     * @access   public
     * @return   integer
     */
    public function getNow()
    {
        global $config, $logger;
        $time_ref = $config['time_reference'];

        if (strtolower($time_ref) == 'gmt') {
            $now = time();
            $system_time = mktime(
                    gmdate("H", $now), gmdate("i", $now), gmdate("s", $now), gmdate("m", $now), gmdate("d", $now), gmdate("Y", $now));

            if (strlen($system_time) < 10) {
                $system_time = time();
                $logger->error('The Date Format class could not set a proper GMT timestamp so the local time() value was used.');
            }
            return $system_time;
        } else {
            return time();
        }
    }

    // ------------------------------------------------------------------------

    /**
     * Standard Date
     *
     * Returns a date formatted according to the submitted standard.
     *
     * @access   public
     * @param    string  the chosen format
     * @param    integer Unix timestamp
     * @return   string
     */
    public function getStandardDate($fmt = 'DATE_RFC822', $time = '')
    {
        $formats = array(
            'DATE_ATOM' => '%Y-%m-%dT%H:%i:%s%Q',
            'DATE_COOKIE' => '%l, %d-%M-%y %H:%i:%s UTC',
            'DATE_ISO8601' => '%Y-%m-%dT%H:%i:%s%O',
            'DATE_RFC822' => '%D, %d %M %y %H:%i:%s %O',
            'DATE_RFC850' => '%l, %d-%M-%y %H:%m:%i UTC',
            'DATE_RFC1036' => '%D, %d %M %y %H:%i:%s %O',
            'DATE_RFC1123' => '%D, %d %M %Y %H:%i:%s %O',
            'DATE_RSS' => '%D, %d %M %Y %H:%i:%s %O',
            'DATE_W3C' => '%Y-%m-%dT%H:%i:%s%Q'
        );

        if (!isset($formats[$fmt])) {
            return false;
        }
        return mdate($formats[$fmt], $time);
    }

    // ------------------------------------------------------------------------

    /**
     * Converts GMT time to a localized value
     *
     * Takes a Unix timestamp (in GMT) as input, and returns
     * at the local value based on the timezone and DST setting
     * submitted
     *
     * @access   public
     * @param    integer Unix timestamp
     * @param    string  timezone
     * @param    bool    whether DST is active
     * @return   integer
     */
    public function gmtToLocal($time = '', $timezone = 'UTC', $dst = false)
    {
        if ($time == '') {
            return $this->getNow();
        }

        $time += $this->getTimeZones($timezone) * 3600;

        if ($dst == true) {
            $time += 3600;
        }

        return $time;
    }

    // ------------------------------------------------------------------------

    /**
     * Convert "human" date to GMT
     *
     * Reverses the above process
     *
     * @access   public
     * @param    string  format: us or euro
     * @return   integer
     */
    public function humanToUnix($datestr = '')
    {
        if ($datestr == '') {
            return false;
        }

        $datestr = trim($datestr);
        $datestr = preg_replace("/\040+/", ' ', $datestr);

        if ( ! preg_match('/^[0-9]{2,4}\-[0-9]{1,2}\-[0-9]{1,2}\s[0-9]{1,2}:[0-9]{1,2}(?::[0-9]{1,2})?(?:\s[AP]M)?$/i', $datestr)) {
            return false;
        }

        $split = explode(' ', $datestr);
        $ex = explode("-", $split['0']);
        $year = (strlen($ex['0']) == 2) ? '20' . $ex['0'] : $ex['0'];
        $month = (strlen($ex['1']) == 1) ? '0' . $ex['1'] : $ex['1'];
        $day = (strlen($ex['2']) == 1) ? '0' . $ex['2'] : $ex['2'];

        $ex = explode(":", $split['1']);

        $hour = (strlen($ex['0']) == 1) ? '0' . $ex['0'] : $ex['0'];
        $min = (strlen($ex['1']) == 1) ? '0' . $ex['1'] : $ex['1'];

        if (isset($ex['2']) AND preg_match('/[0-9]{1,2}/', $ex['2'])) {
            $sec = (strlen($ex['2']) == 1) ? '0' . $ex['2'] : $ex['2'];
        } else {
            $sec = '00';  // Unless specified, seconds get set to zero.
        }

        if (isset($split['2'])) {
            $ampm = strtolower($split['2']);

            if (substr($ampm, 0, 1) == 'p' AND $hour < 12) {
                $hour = $hour + 12;
            }

            if (substr($ampm, 0, 1) == 'a' AND $hour == 12) {
                $hour = '00';
            }

            if (strlen($hour) == 1) {
                $hour = '0' . $hour;
            }
        }
        return mktime($hour, $min, $sec, $month, $day, $year);
    }

    // ------------------------------------------------------------------------

    /**
     * Converts a local Unix timestamp to GMT
     *
     * @access   public
     * @param    integer Unix timestamp
     * @return   integer
     */
    public function localToGmt($time = '')
    {
        if ($time == '') {
            $time = time();
        }
        return mktime(
                gmdate("H", $time), gmdate("i", $time), gmdate("s", $time), gmdate("m", $time), gmdate("d", $time), gmdate("Y", $time));
    }

    // ------------------------------------------------------------------------

    /**
     * Converts a MySQL Timestamp to Unix
     *
     * @access   public
     * @param    integer Unix timestamp
     * @return   integer
     */
    public function mysqlToUnix($time = '')
    {
        // We'll remove certain characters for backward compatibility
        // since the formatting changed with MySQL 4.1
        // YYYY-MM-DD HH:MM:SS

        $time = str_replace('-', '', $time);
        $time = str_replace(':', '', $time);
        $time = str_replace(' ', '', $time);

        // YYYYMMDDHHMMSS
        return mktime(
                substr($time, 8, 2), substr($time, 10, 2), substr($time, 12, 2), substr($time, 4, 2), substr($time, 6, 2), substr($time, 0, 4)
        );
    }

    // ------------------------------------------------------------------------

    /**
     * Unix to "Human"
     *
     * Formats Unix timestamp to the following prototype: 2006-08-21 11:35 PM
     *
     * @access   public
     * @param    integer Unix timestamp
     * @param    bool    whether to show seconds
     * @param    string  format: us or euro
     * @return   string
     */
    public function unixToHuman($time = '', $seconds = false, $fmt = 'us')
    {
        $r = date('Y', $time) . '-' . date('m', $time) . '-' . date('d', $time) . ' ';

        if ($fmt == 'us') {
            $r .= date('h', $time) . ':' . date('i', $time);
        } else {
            $r .= date('H', $time) . ':' . date('i', $time);
        }

        if ($seconds) {
            $r .= ':' . date('s', $time);
        }

        if ($fmt == 'us') {
            $r .= ' ' . date('A', $time);
        }

        return $r;
    }

}

/* End of file date_format.php */
/* Location: ./packages/date_format/releases/0.0.1/date_format.php */