<?php
namespace Ob\Email;

/**
 * Obullo Email Class
 *
 *
 * @package       Obullo
 * @subpackage    email
 * @category      Email
 * @author        Obullo Team
 * @link          
 */
Class Email {

    public    $useragent       = "Obullo";
    public    $mailpath        = "/usr/sbin/sendmail";    // Sendmail path
    public    $protocol        = "mail";    // mail/sendmail/smtp
    public    $smtp_host       = "";        // SMTP Server.  Example: mail.earthlink.net
    public    $smtp_user       = "";        // SMTP Username
    public    $smtp_pass       = "";        // SMTP Password
    public    $smtp_port       = "25";        // SMTP Port
    public    $smtp_timeout    = 5;        // SMTP Timeout in seconds
    public    $wordwrap        = true;        // true/false  Turns word-wrap on/off
    public    $wrapchars       = "76";        // Number of characters to wrap at.
    public    $mailtype        = "text";    // text/html  Defines email formatting
    public    $charset         = "utf-8";    // Default char set: iso-8859-1 or us-ascii
    public    $multipart       = "mixed";    // "mixed" (in the body) or "related" (separate)
    public    $alt_message     = '';        // Alternative message for HTML emails
    public    $validate        = false;    // true/false.  Enables email validation
    public    $priority        = "3";        // Default priority (1 - 5)
    public    $newline         = "\n";        // Default newline. "\r\n" or "\n" (Use "\r\n" to comply with RFC 822)
    public    $crlf            = "\n";        // The RFC 2045 compliant CRLF for quoted-printable is "\r\n".  Apparently some servers,
                                    // even on the receiving end think they need to muck with CRLFs, so using "\n", while
                                    // distasteful, is the only thing that seems to work for all environments.
    public    $send_multipart    = true;        // true/false - Yahoo does not like multipart alternative, so this is an override.  Set to false for Yahoo.
    public    $bcc_batch_mode    = false;    // true/false  Turns on/off Bcc batch feature
    public    $bcc_batch_size    = 200;        // If bcc_batch_mode = true, sets max number of Bccs in each batch
    public    $_safe_mode        = false;
    public    $_subject          = "";
    public    $_body             = "";
    public    $_finalbody        = "";
    public    $_alt_boundary     = "";
    public    $_atc_boundary     = "";
    public    $_header_str       = "";
    public    $_smtp_connect     = "";
    public    $_encoding         = "8bit";
    public    $_smtp_auth        = false;
    public    $_replyto_flag     = false;
    public    $_debug_msg        = array();
    public    $_recipients       = array();
    public    $_cc_array         = array();
    public    $_bcc_array        = array();
    public    $_headers          = array();
    public    $_attach_name      = array();
    public    $_attach_type      = array();
    public    $_attach_disp      = array();
    public    $_protocols        = array('mail', 'sendmail', 'smtp');
    public    $_base_charsets    = array('us-ascii', 'iso-2022-');    // 7-bit charsets (excluding language suffix)
    public    $_bit_depths       = array('7bit', '8bit');
    public    $_priorities       = array('1 (Highest)', '2 (High)', '3 (Normal)', '4 (Low)', '5 (Lowest)');
    
    /**
    * Constructor - Sets Email Preferences
    *
    * The constructor can be passed an array of config values
    */
    function __construct($no_instance = true, $config = array())
    {
        if($no_instance)
        {
            \Ob\getInstance()->email = $this; // Make available it in the controller $this->email->method();
        }
        
        if (count($config) > 0)
        {
            $this->init($config);
        }
        else
        {
            $this->_smtp_auth = ($this->smtp_user == '' AND $this->smtp_pass == '') ? false : true;
            $this->_safe_mode = ((boolean)@ini_get("safe_mode") === false) ? false : true;
        }

        \Ob\log\me('debug', "Email Class Initialized");
    }
    
    
    /**
    * Constructor - Sets Email Preferences
    *
    * The constructor can be passed an array of config values
    */
    public function init($config = array())
    {
        $this->clear();
        foreach ($config as $key => $val)
        {
            if (isset($this->$key))
            {
                $method = 'set_'.$key;

                if (method_exists($this, $method))
                {
                    $this->$method($val);
                }
                else
                {
                    $this->$key = $val;
                }
            }
        }

        $this->_smtp_auth = ($this->smtp_user == '' AND $this->smtp_pass == '') ? false : true;
        $this->_safe_mode = ((boolean)@ini_get("safe_mode") === false) ? false : true;
        
        return $this;
    }
      
    // --------------------------------------------------------------------

    /**
     * Initialize the Email Data
     *
     * @access    public
     * @return    void
     */
    public function clear($clear_attachments = false)
    {
        $this->_subject        = "";
        $this->_body           = "";
        $this->_finalbody      = "";
        $this->_header_str     = "";
        $this->_replyto_flag   = false;
        $this->_recipients     = array();
        $this->_headers        = array();
        $this->_debug_msg      = array();

        $this->_setHeader('User-Agent', $this->useragent);
        $this->_setHeader('Date', $this->_setDate());

        if ($clear_attachments !== false)
        {
            $this->_attach_name = array();
            $this->_attach_type = array();
            $this->_attach_disp = array();
        }
    }
  
    // --------------------------------------------------------------------

    /**
     * Set FROM
     *
     * @access    public
     * @param    string
     * @param    string
     * @return    void
     */
    public function from($from, $name = '')
    {
        if (preg_match( '/\<(.*)\>/', $from, $match))
        {
            $from = $match['1'];
        }

        if ($this->validate)
        {
            $this->validateEmail($this->_str2array($from));
        }

        // prepare the display name
        if ($name != '')
        {
            // only use Q encoding if there are characters that would require it
            if ( ! preg_match('/[\200-\377]/', $name))
            {
                // add slashes for non-printing characters, slashes, and double quotes, and surround it in double quotes
                $name = '"'.addcslashes($name, "\0..\37\177'\"\\").'"';
            }
            else
            {
                $name = $this->_prepQencoding($name, true);
            }
        }

        $this->_setHeader('From', $name.' <'.$from.'>');
        $this->_setHeader('Return-Path', '<'.$from.'>');
    }
  
    // --------------------------------------------------------------------

    /**
     * Set Reply-to
     *
     * @access    public
     * @param    string
     * @param    string
     * @return    void
     */
    public function replyTo($replyto, $name = '')
    {
        if (preg_match( '/\<(.*)\>/', $replyto, $match))
        {
            $replyto = $match['1'];
        }

        if ($this->validate)
        {
            $this->validateEmail($this->_str2array($replyto));
        }

        if ($name == '')
        {
            $name = $replyto;
        }

        if (strncmp($name, '"', 1) != 0)
        {
            $name = '"'.$name.'"';
        }

        $this->_setHeader('Reply-To', $name.' <'.$replyto.'>');
        $this->_replyto_flag = true;
    }
  
    // --------------------------------------------------------------------

    /**
     * Set Recipients
     *
     * @access    public
     * @param    string
     * @return    void
     */
    public function to($to)
    {
        $to = $this->_str2array($to);
        $to = $this->cleanEmail($to);

        if ($this->validate)
        {
            $this->validateEmail($to);
        }

        if ($this->_getProtocol() != 'mail')
        {
            $this->_setHeader('To', implode(", ", $to));
        }

        switch ($this->_getProtocol())
        {
            case 'smtp'        : $this->_recipients = $to;
            break;
            case 'sendmail'    : $this->_recipients = implode(", ", $to);
            break;
            case 'mail'        : $this->_recipients = implode(", ", $to);
            break;
        }
    }
  
    // --------------------------------------------------------------------

    /**
     * Set CC
     *
     * @access    public
     * @param    string
     * @return    void
     */
    public function cc($cc)
    {
        $cc = $this->_str2array($cc);
        $cc = $this->cleanEmail($cc);

        if ($this->validate)
        {
            $this->validateEmail($cc);
        }

        $this->_setHeader('Cc', implode(", ", $cc));

        if ($this->_getProtocol() == "smtp")
        {
            $this->_cc_array = $cc;
        }
    }
  
    // --------------------------------------------------------------------

    /**
     * Set BCC
     *
     * @access    public
     * @param    string
     * @param    string
     * @return    void
     */
    public function bcc($bcc, $limit = '')
    {
        if ($limit != '' && is_numeric($limit))
        {
            $this->bcc_batch_mode = true;
            $this->bcc_batch_size = $limit;
        }

        $bcc = $this->_str2array($bcc);
        $bcc = $this->cleanEmail($bcc);

        if ($this->validate)
        {
            $this->validateEmail($bcc);
        }

        if (($this->_getProtocol() == "smtp") OR ($this->bcc_batch_mode && count($bcc) > $this->bcc_batch_size))
        {
            $this->_bcc_array = $bcc;
        }
        else
        {
            $this->_setHeader('Bcc', implode(", ", $bcc));
        }
    }
  
    // --------------------------------------------------------------------

    /**
     * Set Email Subject
     *
     * @access    public
     * @param    string
     * @return    void
     */
    public function subject($subject)
    {
        $subject = $this->_prepQencoding($subject);
        $this->_setHeader('Subject', $subject);
    }
  
    // --------------------------------------------------------------------

    /**
     * Set Body
     *
     * @access    public
     * @param    string
     * @return    void
     */
    public function message($body)
    {
        $this->_body = stripslashes(rtrim(str_replace("\r", "", $body)));
    }
 
    // --------------------------------------------------------------------

    /**
     * Assign file attachments
     *
     * @access    public
     * @param    string
     * @return    void
     */
    public function attach($filename, $disposition = 'attachment')
    {
        $this->_attach_name[] = $filename;
        
        $file  = basename($filename);
        $mimes = explode('.', $file);
        $mimes = next($mimes);
        
        $this->_attach_type[] = $this->_mimeTypes($mimes);
        $this->_attach_disp[] = $disposition; // Can also be 'inline'  Not sure if it matters
    }

    // --------------------------------------------------------------------

    /**
     * Add a Header Item
     *
     * @access    private
     * @param    string
     * @param    string
     * @return    void
     */
    private function _setHeader($header, $value)
    {
        $this->_headers[$header] = $value;
    }
  
    // --------------------------------------------------------------------

    /**
     * Convert a String to an Array
     *
     * @access    private
     * @param    string
     * @return    array
     */
    public function _str2array($email)
    {
        if ( ! is_array($email))
        {
            if (strpos($email, ',') !== false)
            {
                $email = preg_split('/[\s,]/', $email, -1, PREG_SPLIT_NO_EMPTY);
            }
            else
            {
                $email = trim($email);
                settype($email, "array");
            }
        }
        return $email;
    }
  
    // --------------------------------------------------------------------

    /**
     * Set Multipart Value
     *
     * @access    public
     * @param    string
     * @return    void
     */
    public function setAltMessage($str = '')
    {
        $this->alt_message = ($str == '') ? '' : $str;
    }
  
    // --------------------------------------------------------------------

    /**
     * Set Mailtype
     *
     * @access    public
     * @param    string
     * @return    void
     */
    public function setMailtype($type = 'text')
    {
        $this->mailtype = ($type == 'html') ? 'html' : 'text';
    }
  
    // --------------------------------------------------------------------

    /**
     * Set Wordwrap
     *
     * @access    public
     * @param    string
     * @return    void
     */
    public function setWordwrap($wordwrap = true)
    {
        $this->wordwrap = ($wordwrap === false) ? false : true;
    }
  
    // --------------------------------------------------------------------

    /**
     * Set Protocol
     *
     * @access    public
     * @param    string
     * @return    void
     */
    public function setProtocol($protocol = 'mail')
    {
        $this->protocol = ( ! in_array($protocol, $this->_protocols, true)) ? 'mail' : strtolower($protocol);
    }
  
    // --------------------------------------------------------------------

    /**
     * Set Priority
     *
     * @access    public
     * @param    integer
     * @return    void
     */
    public function setPriority($n = 3)
    {
        if ( ! is_numeric($n))
        {
            $this->priority = 3;
            return;
        }

        if ($n < 1 OR $n > 5)
        {
            $this->priority = 3;
            return;
        }

        $this->priority = $n;
    }
  
    // --------------------------------------------------------------------

    /**
     * Set Newline Character
     *
     * @access    public
     * @param    string
     * @return    void
     */
    public function setNewline($newline = "\n")
    {
        if ($newline != "\n" AND $newline != "\r\n" AND $newline != "\r")
        {
            $this->newline = "\n";
            return;
        }

        $this->newline = $newline;
    }
  
    // --------------------------------------------------------------------

    /**
     * Set CRLF
     *
     * @access    public
     * @param    string
     * @return    void
     */
    public function setCrlf($crlf = "\n")
    {
        if ($crlf != "\n" AND $crlf != "\r\n" AND $crlf != "\r")
        {
            $this->crlf = "\n";
            return;
        }

        $this->crlf = $crlf;
    }
  
    // --------------------------------------------------------------------

    /**
     * Set Message Boundary
     *
     * @access    private
     * @return    void
     */
    private function _setBoundaries()
    {
        $this->_alt_boundary = "B_ALT_".uniqid(''); // multipart/alternative
        $this->_atc_boundary = "B_ATC_".uniqid(''); // attachment boundary
    }
  
    // --------------------------------------------------------------------

    /**
     * Get the Message ID
     *
     * @access    private
     * @return    string
     */
    private function _getMessageId()
    {
        $from = $this->_headers['Return-Path'];
        $from = str_replace(">", "", $from);
        $from = str_replace("<", "", $from);

        return  "<".uniqid('').strstr($from, '@').">";
    }
  
    // --------------------------------------------------------------------

    /**
     * Get Mail Protocol
     *
     * @access    private
     * @param    bool
     * @return    string
     */
    private function _getProtocol($return = true)
    {
        $this->protocol = strtolower($this->protocol);
        $this->protocol = ( ! in_array($this->protocol, $this->_protocols, true)) ? 'mail' : $this->protocol;

        if ($return == true)
        {
            return $this->protocol;
        }
    }
  
    // --------------------------------------------------------------------

    /**
     * Get Mail Encoding
     *
     * @access    private
     * @param    bool
     * @return    string
     */
    private function _getEncoding($return = true)
    {
        $this->_encoding = ( ! in_array($this->_encoding, $this->_bit_depths)) ? '8bit' : $this->_encoding;

        foreach ($this->_base_charsets as $charset)
        {
            if (strncmp($charset, $this->charset, strlen($charset)) == 0)
            {
                $this->_encoding = '7bit';
            }
        }

        if ($return == true)
        {
            return $this->_encoding;
        }
    }

    // --------------------------------------------------------------------

    /**
     * Get content type (text/html/attachment)
     *
     * @access    private
     * @return    string
     */
    private function _getContentType()
    {
        if ($this->mailtype == 'html' &&  count($this->_attach_name) == 0)
        {
            return 'html';
        }
        elseif ($this->mailtype == 'html' &&  count($this->_attach_name)  > 0)
        {
            return 'html-attach';
        }
        elseif ($this->mailtype == 'text' &&  count($this->_attach_name)  > 0)
        {
            return 'plain-attach';
        }
        else
        {
            return 'plain';
        }
    }
  
    // --------------------------------------------------------------------

    /**
     * Set RFC 822 Date
     *
     * @access    private
     * @return    string
     */
    private function _setDate()
    {
        $timezone = date("Z");
        $operator = (strncmp($timezone, '-', 1) == 0) ? '-' : '+';
        $abs = abs($abs);
        $floor_timezone = floor($abs/3600) * 100 + ($abs % 3600 ) / 60;

        return sprintf("%s %s%04d", date("D, j M Y H:i:s"), $operator, $floor_timezone);
    }
  
    // --------------------------------------------------------------------

    /**
     * Mime message
     *
     * @access    private
     * @return    string
     */
    private function _getMimeMessage()
    {
        return "This is a multi-part message in MIME format.".$this->newline."Your email application may not support this format.";
    }
  
    // --------------------------------------------------------------------

    /**
     * Validate Email Address
     *
     * @access    public
     * @param    string
     * @return    bool
     */
    public function validateEmail($email)
    {
        if ( ! is_array($email))
        {
            $this->_setErrorMessage('email_must_be_array');
            return false;
        }

        foreach ($email as $val)
        {
            if ( ! $this->validEmail($val))
            {
                $this->_setErrorMessage('email_invalid_address', $val);
                return false;
            }
        }

        return true;
    }
  
    // --------------------------------------------------------------------

    /**
     * Email Validation
     *
     * @access    public
     * @param    string
     * @return    bool
     */
    public function validEmail($address)
    {
        return ( ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $address)) ? false : true;
    }
  
    // --------------------------------------------------------------------

    /**
     * Clean Extended Email Address: Joe Smith <joe@smith.com>
     *
     * @access    public
     * @param    string
     * @return    string
     */
    public function cleanEmail($email)
    {
        if ( ! is_array($email))
        {
            if (preg_match('/\<(.*)\>/', $email, $match))
            {
                   return $match['1'];
            }
               else
            {
                   return $email;
            }
        }

        $clean_email = array();

        foreach ($email as $addy)
        {
            if (preg_match( '/\<(.*)\>/', $addy, $match))
            {
                   $clean_email[] = $match['1'];
            }
               else
            {
                   $clean_email[] = $addy;
            }
        }

        return $clean_email;
    }
  
    // --------------------------------------------------------------------

    /**
     * Build alternative plain text message
     *
     * This function provides the raw message for use
     * in plain-text headers of HTML-formatted emails.
     * If the user hasn't specified his own alternative message
     * it creates one by stripping the HTML
     *
     * @access    private
     * @return    string
     */
    private function _getAltMessage()
    {
        if ($this->alt_message != "")
        {
            return $this->wordWrap($this->alt_message, '76');
        }

        if (preg_match('/\<body.*?\>(.*)\<\/body\>/si', $this->_body, $match))
        {
            $body = $match['1'];
        }
        else
        {
            $body = $this->_body;
        }

        $body = trim(strip_tags($body));
        $body = preg_replace( '#<!--(.*)--\>#', "", $body);
        $body = str_replace("\t", "", $body);

        for ($i = 20; $i >= 3; $i--)
        {
            $n = "";

            for ($x = 1; $x <= $i; $x ++)
            {
                 $n .= "\n";
            }

            $body = str_replace($n, "\n\n", $body);
        }

        return $this->wordWrap($body, '76');
    }
  
    // --------------------------------------------------------------------

    /**
     * Word Wrap
     *
     * @access    public
     * @param    string
     * @param    integer
     * @return    string
     */
    public function wordWrap($str, $charlim = '')
    {
        // Se the character limit
        if ($charlim == '')
        {
            $charlim = ($this->wrapchars == "") ? "76" : $this->wrapchars;
        }

        // Reduce multiple spaces
        $str = preg_replace("| +|", " ", $str);

        // Standardize newlines
        if (strpos($str, "\r") !== false)
        {
            $str = str_replace(array("\r\n", "\r"), "\n", $str);
        }

        // If the current word is surrounded by {unwrap} tags we'll
        // strip the entire chunk and replace it with a marker.
        $unwrap = array();
        if (preg_match_all("|(\{unwrap\}.+?\{/unwrap\})|s", $str, $matches))
        {
            for ($i = 0; $i < count($matches['0']); $i++)
            {
                $unwrap[] = $matches['1'][$i];
                $str = str_replace($matches['1'][$i], "{{unwrapped".$i."}}", $str);
            }
        }

        // Use PHP's native function to do the initial wordwrap.
        // We set the cut flag to false so that any individual words that are
        // too long get left alone.  In the next step we'll deal with them.
        $str = wordwrap($str, $charlim, "\n", false);

        // Split the string into individual lines of text and cycle through them
        $output = "";
        foreach (explode("\n", $str) as $line)
        {
            // Is the line within the allowed character count?
            // If so we'll join it to the output and continue
            if (strlen($line) <= $charlim)
            {
                $output .= $line.$this->newline;
                continue;
            }

            $temp = '';
            while((strlen($line)) > $charlim)
            {
                // If the over-length word is a URL we won't wrap it
                if (preg_match("!\[url.+\]|://|wwww.!", $line))
                {
                    break;
                }

                // Trim the word down
                $temp .= substr($line, 0, $charlim-1);
                $line = substr($line, $charlim-1);
            }

            // If $temp contains data it means we had to split up an over-length
            // word into smaller chunks so we'll add it back to our current line
            if ($temp != '')
            {
                $output .= $temp.$this->newline.$line;
            }
            else
            {
                $output .= $line;
            }

            $output .= $this->newline;
        }

        // Put our markers back
        if (count($unwrap) > 0)
        {
            foreach ($unwrap as $key => $val)
            {
                $output = str_replace("{{unwrapped".$key."}}", $val, $output);
            }
        }

        return $output;
    }
  
    // --------------------------------------------------------------------

    /**
     * Build final headers
     *
     * @access    private
     * @param    string
     * @return    string
     */
    private function _buildHeaders()
    {
        $this->_setHeader('X-Sender', $this->cleanEmail($this->_headers['From']));
        $this->_setHeader('X-Mailer', $this->useragent);
        $this->_setHeader('X-Priority', $this->_priorities[$this->priority - 1]);
        $this->_setHeader('Message-ID', $this->_getMessageId());
        $this->_setHeader('Mime-Version', '1.0');
    }
  
    // --------------------------------------------------------------------

    /**
     * Write Headers as a string
     *
     * @access    private
     * @return    void
     */
    private function _writeHeaders()
    {
        if ($this->protocol == 'mail')
        {
            $this->_subject = $this->_headers['Subject'];
            unset($this->_headers['Subject']);
        }

        reset($this->_headers);
        $this->_header_str = "";

        foreach($this->_headers as $key => $val)
        {
            $val = trim($val);

            if ($val != "")
            {
                $this->_header_str .= $key.": ".$val.$this->newline;
            }
        }

        if ($this->_getProtocol() == 'mail')
        {
            $this->_header_str = rtrim($this->_header_str);
        }
    }
  
    // --------------------------------------------------------------------

    /**
     * Build Final Body and attachments
     *
     * @access    private
     * @return    void
     */
    private function _buildMessage()
    {
        if ($this->wordwrap === true  AND  $this->mailtype != 'html')
        {
            $this->_body = $this->wordWrap($this->_body);
        }

        $this->_setBoundaries();
        $this->_writeHeaders();

        $hdr = ($this->_getProtocol() == 'mail') ? $this->newline : '';

        switch ($this->_getContentType())
        {
            case 'plain' :

                $hdr .= "Content-Type: text/plain; charset=" . $this->charset . $this->newline;
                $hdr .= "Content-Transfer-Encoding: " . $this->_getEncoding();

                if ($this->_getProtocol() == 'mail')
                {
                    $this->_header_str .= $hdr;
                    $this->_finalbody = $this->_body;

                    return;
                }

                $hdr .= $this->newline . $this->newline . $this->_body;

                $this->_finalbody = $hdr;
                return;

            break;
            case 'html' :

                if ($this->send_multipart === false)
                {
                    $hdr .= "Content-Type: text/html; charset=" . $this->charset . $this->newline;
                    $hdr .= "Content-Transfer-Encoding: quoted-printable";
                }
                else
                {
                    $hdr .= "Content-Type: multipart/alternative; boundary=\"" . $this->_alt_boundary . "\"" . $this->newline . $this->newline;
                    $hdr .= $this->_getMimeMessage() . $this->newline . $this->newline;
                    $hdr .= "--" . $this->_alt_boundary . $this->newline;

                    $hdr .= "Content-Type: text/plain; charset=" . $this->charset . $this->newline;
                    $hdr .= "Content-Transfer-Encoding: " . $this->_getEncoding() . $this->newline . $this->newline;
                    $hdr .= $this->_getAltMessage() . $this->newline . $this->newline . "--" . $this->_alt_boundary . $this->newline;

                    $hdr .= "Content-Type: text/html; charset=" . $this->charset . $this->newline;
                    $hdr .= "Content-Transfer-Encoding: quoted-printable";
                }

                $this->_body = $this->_prepQuotedPrintable($this->_body);

                if ($this->_getProtocol() == 'mail')
                {
                    $this->_header_str .= $hdr;
                    $this->_finalbody = $this->_body . $this->newline . $this->newline;

                    if ($this->send_multipart !== false)
                    {
                        $this->_finalbody .= "--" . $this->_alt_boundary . "--";
                    }

                    return;
                }

                $hdr .= $this->newline . $this->newline;
                $hdr .= $this->_body . $this->newline . $this->newline;

                if ($this->send_multipart !== false)
                {
                    $hdr .= "--" . $this->_alt_boundary . "--";
                }

                $this->_finalbody = $hdr;
                return;

            break;
            case 'plain-attach' :

                $hdr .= "Content-Type: multipart/".$this->multipart."; boundary=\"" . $this->_atc_boundary."\"" . $this->newline . $this->newline;
                $hdr .= $this->_getMimeMessage() . $this->newline . $this->newline;
                $hdr .= "--" . $this->_atc_boundary . $this->newline;

                $hdr .= "Content-Type: text/plain; charset=" . $this->charset . $this->newline;
                $hdr .= "Content-Transfer-Encoding: " . $this->_getEncoding();

                if ($this->_getProtocol() == 'mail')
                {
                    $this->_header_str .= $hdr;

                    $body  = $this->_body . $this->newline . $this->newline;
                }

                $hdr .= $this->newline . $this->newline;
                $hdr .= $this->_body . $this->newline . $this->newline;

            break;
            case 'html-attach' :

                $hdr .= "Content-Type: multipart/".$this->multipart."; boundary=\"" . $this->_atc_boundary."\"" . $this->newline . $this->newline;
                $hdr .= $this->_getMimeMessage() . $this->newline . $this->newline;
                $hdr .= "--" . $this->_atc_boundary . $this->newline;

                $hdr .= "Content-Type: multipart/alternative; boundary=\"" . $this->_alt_boundary . "\"" . $this->newline .$this->newline;
                $hdr .= "--" . $this->_alt_boundary . $this->newline;

                $hdr .= "Content-Type: text/plain; charset=" . $this->charset . $this->newline;
                $hdr .= "Content-Transfer-Encoding: " . $this->_getEncoding() . $this->newline . $this->newline;
                $hdr .= $this->_getAltMessage() . $this->newline . $this->newline . "--" . $this->_alt_boundary . $this->newline;

                $hdr .= "Content-Type: text/html; charset=" . $this->charset . $this->newline;
                $hdr .= "Content-Transfer-Encoding: quoted-printable";

                $this->_body = $this->_prepQuotedPrintable($this->_body);

                if ($this->_getProtocol() == 'mail')
                {
                    $this->_header_str .= $hdr;

                    $body  = $this->_body . $this->newline . $this->newline;
                    $body .= "--" . $this->_alt_boundary . "--" . $this->newline . $this->newline;
                }

                $hdr .= $this->newline . $this->newline;
                $hdr .= $this->_body . $this->newline . $this->newline;
                $hdr .= "--" . $this->_alt_boundary . "--" . $this->newline . $this->newline;

            break;
        }

        $attachment = array();

        $z = 0;

        for ($i=0; $i < count($this->_attach_name); $i++)
        {
            $filename = $this->_attach_name[$i];
            $basename = basename($filename);
            $ctype = $this->_attach_type[$i];

            if ( ! file_exists($filename))
            {
                $this->_setErrorMessage('email_attachment_missing', $filename);
                return false;
            }

            $h  = "--".$this->_atc_boundary.$this->newline;
            $h .= "Content-type: ".$ctype."; ";
            $h .= "name=\"".$basename."\"".$this->newline;
            $h .= "Content-Disposition: ".$this->_attach_disp[$i].";".$this->newline;
            $h .= "Content-Transfer-Encoding: base64".$this->newline;

            $attachment[$z++] = $h;
            $file = filesize($filename) +1;

            if ( ! $fp = fopen($filename, FOPEN_READ))
            {
                $this->_setErrorMessage('email_attachment_unreadable', $filename);
                return false;
            }

            $attachment[$z++] = chunk_split(base64_encode(fread($fp, $file)));
            fclose($fp);
        }

        if ($this->_getProtocol() == 'mail')
        {
            $this->_finalbody = $body . implode($this->newline, $attachment).$this->newline."--".$this->_atc_boundary."--";

            return;
        }

        $this->_finalbody = $hdr.implode($this->newline, $attachment).$this->newline."--".$this->_atc_boundary."--";

        return;
    }
  
    // --------------------------------------------------------------------

    /**
     * Prep Quoted Printable
     *
     * Prepares string for Quoted-Printable Content-Transfer-Encoding
     * Refer to RFC 2045 http://www.ietf.org/rfc/rfc2045.txt
     *
     * @access    private
     * @param    string
     * @param    integer
     * @return    string
     */
    private function _prepQuotedPrintable($str, $charlim = '')
    {
        // Set the character limit
        // Don't allow over 76, as that will make servers and MUAs barf
        // all over quoted-printable data
        if ($charlim == '' OR $charlim > '76')
        {
            $charlim = '76';
        }

        // Reduce multiple spaces
        $str = preg_replace("| +|", " ", $str);

        // kill nulls
        $str = preg_replace('/\x00+/', '', $str);

        // Standardize newlines
        if (strpos($str, "\r") !== false)
        {
            $str = str_replace(array("\r\n", "\r"), "\n", $str);
        }

        // We are intentionally wrapping so mail servers will encode characters
        // properly and MUAs will behave, so {unwrap} must go!
        $str = str_replace(array('{unwrap}', '{/unwrap}'), '', $str);

        // Break into an array of lines
        $lines = explode("\n", $str);

        $escape = '=';
        $output = '';

        foreach ($lines as $line)
        {
            $length = strlen($line);
            $temp = '';

            // Loop through each character in the line to add soft-wrap
            // characters at the end of a line " =\r\n" and add the newly
            // processed line(s) to the output (see comment on $crlf class property)
            for ($i = 0; $i < $length; $i++)
            {
                // Grab the next character
                $char = substr($line, $i, 1);
                $ascii = ord($char);

                // Convert spaces and tabs but only if it's the end of the line
                if ($i == ($length - 1))
                {
                    $char = ($ascii == '32' OR $ascii == '9') ? $escape.sprintf('%02s', dechex($ascii)) : $char;
                }

                // encode = signs
                if ($ascii == '61')
                {
                    $char = $escape.strtoupper(sprintf('%02s', dechex($ascii)));  // =3D
                }

                // If we're at the character limit, add the line to the output,
                // reset our temp variable, and keep on chuggin'
                if ((strlen($temp) + strlen($char)) >= $charlim)
                {
                    $output .= $temp.$escape.$this->crlf;
                    $temp = '';
                }

                // Add the character to our temporary line
                $temp .= $char;
            }

            // Add our completed line to the output
            $output .= $temp.$this->crlf;
        }

        // get rid of extra CRLF tacked onto the end
        $output = substr($output, 0, strlen($this->crlf) * -1);

        return $output;
    }

    // --------------------------------------------------------------------
    
    /**
     * Prep Q Encoding
     *
     * Performs "Q Encoding" on a string for use in email headers.  It's related
     * but not identical to quoted-printable, so it has its own method
     *
     * @access    public
     * @param    str
     * @param    bool    // set to true for processing From: headers
     * @return    str
     */
    public function _prepQencoding($str, $from = false)
    {
        $str = str_replace(array("\r", "\n"), array('', ''), $str);

        // Line length must not exceed 76 characters, so we adjust for
        // a space, 7 extra characters =??Q??=, and the charset that we will add to each line
        $limit = 75 - 7 - strlen($this->charset);

        // these special characters must be converted too
        $convert = array('_', '=', '?');

        if ($from === true)
        {
            $convert[] = ',';
            $convert[] = ';';
        }

        $output = '';
        $temp = '';

        for ($i = 0, $length = strlen($str); $i < $length; $i++)
        {
            // Grab the next character
            $char = substr($str, $i, 1);
            $ascii = ord($char);

            // convert ALL non-printable ASCII characters and our specials
            if ($ascii < 32 OR $ascii > 126 OR in_array($char, $convert))
            {
                $char = '='.dechex($ascii);
            }

            // handle regular spaces a bit more compactly than =20
            if ($ascii == 32)
            {
                $char = '_';
            }

            // If we're at the character limit, add the line to the output,
            // reset our temp variable, and keep on chuggin'
            if ((strlen($temp) + strlen($char)) >= $limit)
            {
                $output .= $temp.$this->crlf;
                $temp = '';
            }

            // Add the character to our temporary line
            $temp .= $char;
        }

        $str = $output.$temp;

        // wrap each line with the shebang, charset, and transfer encoding
        // the preceding space on successive lines is required for header "folding"
        $str = trim(preg_replace('/^(.*)$/m', ' =?'.$this->charset.'?Q?$1?=', $str));

        return $str;
    }

    // --------------------------------------------------------------------
    
    /**
     * Send Email
     *
     * @access    public
     * @return    bool
     */
    public function send()
    {
        if ($this->_replyto_flag == false)
        {
            $this->replyTo($this->_headers['From']);
        }

        if (( ! isset($this->_recipients) AND ! isset($this->_headers['To']))  AND
            ( ! isset($this->_bcc_array) AND ! isset($this->_headers['Bcc'])) AND
            ( ! isset($this->_headers['Cc'])))
        {
            $this->_setErrorMessage('email_no_recipients');
            return false;
        }

        $this->_buildHeaders();

        if ($this->bcc_batch_mode  AND  count($this->_bcc_array) > 0)
        {
            if (count($this->_bcc_array) > $this->bcc_batch_size)
                return $this->batchBccSend();
        }

        $this->_buildMessage();

        if ( ! $this->_spoolEmail())
        {
            return false;
        }
        else
        {
            return true;
        }
    }
  
    // --------------------------------------------------------------------

    /**
     * Batch Bcc Send.  Sends groups of BCCs in batches
     *
     * @access    public
     * @return    bool
     */
    public function batchBccSend()
    {
        $float = $this->bcc_batch_size -1;

        $set = "";

        $chunk = array();

        for ($i = 0; $i < count($this->_bcc_array); $i++)
        {
            if (isset($this->_bcc_array[$i]))
            {
                $set .= ", ".$this->_bcc_array[$i];
            }

            if ($i == $float)
            {
                $chunk[] = substr($set, 1);
                $float = $float + $this->bcc_batch_size;
                $set = "";
            }

            if ($i == count($this->_bcc_array)-1)
            {
                $chunk[] = substr($set, 1);
            }
        }

        for ($i = 0; $i < count($chunk); $i++)
        {
            unset($this->_headers['Bcc']);
            unset($bcc);

            $bcc = $this->_str2array($chunk[$i]);
            $bcc = $this->cleanEmail($bcc);

            if ($this->protocol != 'smtp')
            {
                $this->_setHeader('Bcc', implode(", ", $bcc));
            }
            else
            {
                $this->_bcc_array = $bcc;
            }

            $this->_buildMessage();
            $this->_spoolEmail();
        }
    }
  
    // --------------------------------------------------------------------

    /**
     * Unwrap special elements
     *
     * @access    private
     * @return    void
     */
    private function _unwrapSpecials()
    {
        $this->_finalbody = preg_replace_callback("/\{unwrap\}(.*?)\{\/unwrap\}/si", array($this, '_removeNlCallback'), $this->_finalbody);
    }
  
    // --------------------------------------------------------------------

    /**
     * Strip line-breaks via callback
     *
     * @access    private
     * @return    string
     */
    private function _removeNlCallback($matches)
    {
        if (strpos($matches[1], "\r") !== false OR strpos($matches[1], "\n") !== false)
        {
            $matches[1] = str_replace(array("\r\n", "\r", "\n"), '', $matches[1]);
        }

        return $matches[1];
    }
  
    // --------------------------------------------------------------------

    /**
     * Spool mail to the mail server
     *
     * @access    private
     * @return    bool
     */
    private function _spoolEmail()
    {
        $this->_unwrapSpecials();

        switch ($this->_getProtocol())
        {
            case 'mail'    :

                    if ( ! $this->_sendWithMail())
                    {
                        $this->_setErrorMessage('email_send_failure_phpmail');
                        return false;
                    }
            break;
            case 'sendmail'    :

                    if ( ! $this->_sendWithSendmail())
                    {
                        $this->_setErrorMessage('email_send_failure_sendmail');
                        return false;
                    }
            break;
            case 'smtp'    :

                    if ( ! $this->_sendWithSmtp())
                    {
                        $this->_setErrorMessage('email_send_failure_smtp');
                        return false;
                    }
            break;

        }

        $this->_setErrorMessage('email_sent', $this->_getProtocol());
        return true;
    }
  
    // --------------------------------------------------------------------

    /**
     * Send using mail()
     *
     * @access    private
     * @return    bool
     */
    private function _sendWithMail()
    {
        if ($this->_safe_mode == true)
        {
            if ( ! mail($this->_recipients, $this->_subject, $this->_finalbody, $this->_header_str))
            {
                return false;
            }
            else
            {
                return true;
            }
        }
        else
        {
            // most documentation of sendmail using the "-f" flag lacks a space after it, however
            // we've encountered servers that seem to require it to be in place.
            if ( ! mail($this->_recipients, $this->_subject, $this->_finalbody, $this->_header_str, "-f ".$this->cleanEmail($this->_headers['From'])))
            {
                return false;
            }
            else
            {
                return true;
            }
        }
    }
  
    // --------------------------------------------------------------------

    /**
     * Send using Sendmail
     *
     * @access    private
     * @return    bool
     */
    private function _sendWithSendmail()
    {
        $fp = @popen($this->mailpath . " -oi -f ".$this->cleanEmail($this->_headers['From'])." -t", 'w');

        if ($fp === false OR $fp === null)
        {
            // server probably has popen disabled, so nothing we can do to get a verbose error.
            return false;
        }
        
        fputs($fp, $this->_header_str);
        fputs($fp, $this->_finalbody);

        $status = pclose($fp);
        
        if (version_compare(PHP_VERSION, '4.2.3') == -1)
        {
            $status = $status >> 8 & 0xFF;
        }
    
        if ($status != 0)
        {
            $this->_setErrorMessage('email_exit_status', $status);
            $this->_setErrorMessage('email_no_socket');
            return false;
        }

        return true;
    }
  
    // --------------------------------------------------------------------

    /**
     * Send using SMTP
     *
     * @access    private
     * @return    bool
     */
    private function _sendWithSmtp()
    {
        if ($this->smtp_host == '')
        {
            $this->_setErrorMessage('email_no_hostname');
            return false;
        }

        $this->_smtpConnect();
        $this->_smtpAuthenticate();

        $this->_sendCommand('from', $this->cleanEmail($this->_headers['From']));

        foreach($this->_recipients as $val)
        {
            $this->_sendCommand('to', $val);
        }

        if (count($this->_cc_array) > 0)
        {
            foreach($this->_cc_array as $val)
            {
                if ($val != "")
                {
                    $this->_sendCommand('to', $val);
                }
            }
        }

        if (count($this->_bcc_array) > 0)
        {
            foreach($this->_bcc_array as $val)
            {
                if ($val != "")
                {
                    $this->_sendCommand('to', $val);
                }
            }
        }

        $this->_sendCommand('data');

        // perform dot transformation on any lines that begin with a dot
        $this->_sendData($this->_header_str . preg_replace('/^\./m', '..$1', $this->_finalbody));

        $this->_sendData('.');

        $reply = $this->_getSmtpData();

        $this->_setErrorMessage($reply);

        if (strncmp($reply, '250', 3) != 0)
        {
            $this->_setErrorMessage('email_smtp_error', $reply);
            return false;
        }

        $this->_sendCommand('quit');
        return true;
    }
  
    // --------------------------------------------------------------------

    /**
     * SMTP Connect
     *
     * @access    private
     * @param    string
     * @return    string
     */
    private function _smtpConnect()
    {
        $this->_smtp_connect = fsockopen($this->smtp_host,
                                        $this->smtp_port,
                                        $errno,
                                        $errstr,
                                        $this->smtp_timeout);

        if( ! is_resource($this->_smtp_connect))
        {
            $this->_setErrorMessage('email_smtp_error', $errno." ".$errstr);
            return false;
        }

        $this->_setErrorMessage($this->_getSmtpData());
        return $this->_sendCommand('hello');
    }
  
    // --------------------------------------------------------------------

    /**
     * Send SMTP command
     *
     * @access    private
     * @param    string
     * @param    string
     * @return    string
     */
    private function _sendCommand($cmd, $data = '')
    {
        switch ($cmd)
        {
            case 'hello' :

                    if ($this->_smtp_auth OR $this->_getEncoding() == '8bit')
                        $this->_sendData('EHLO '.$this->_getHostname());
                    else
                        $this->_sendData('HELO '.$this->_getHostname());

                        $resp = 250;
            break;
            case 'from' :

                        $this->_sendData('MAIL FROM:<'.$data.'>');

                        $resp = 250;
            break;
            case 'to'    :

                        $this->_sendData('RCPT TO:<'.$data.'>');

                        $resp = 250;
            break;
            case 'data'    :

                        $this->_sendData('DATA');

                        $resp = 354;
            break;
            case 'quit'    :

                        $this->_sendData('QUIT');

                        $resp = 221;
            break;
        }

        $reply = $this->_getSmtpData();

        $this->_debug_msg[] = "<pre>".$cmd.": ".$reply."</pre>";

        if (substr($reply, 0, 3) != $resp)
        {
            $this->_setErrorMessage('email_smtp_error', $reply);
            return false;
        }

        if ($cmd == 'quit')
        {
            fclose($this->_smtp_connect);
        }

        return true;
    }
  
    // --------------------------------------------------------------------

    /**
     *  SMTP Authenticate
     *
     * @access    private
     * @return    bool
     */
    private function _smtpAuthenticate()
    {
        if ( ! $this->_smtp_auth)
        {
            return true;
        }

        if ($this->smtp_user == ""  AND  $this->smtp_pass == "")
        {
            $this->_setErrorMessage('email_no_smtp_unpw');
            return false;
        }

        $this->_sendData('AUTH LOGIN');

        $reply = $this->_getSmtpData();

        if (strncmp($reply, '334', 3) != 0)
        {
            $this->_setErrorMessage('email_failed_smtp_login', $reply);
            return false;
        }

        $this->_sendData(base64_encode($this->smtp_user));

        $reply = $this->_getSmtpData();

        if (strncmp($reply, '334', 3) != 0)
        {
            $this->_setErrorMessage('email_smtp_auth_un', $reply);
            return false;
        }

        $this->_sendData(base64_encode($this->smtp_pass));

        $reply = $this->_getSmtpData();

        if (strncmp($reply, '235', 3) != 0)
        {
            $this->_setErrorMessage('email_smtp_auth_pw', $reply);
            return false;
        }

        return true;
    }
  
    // --------------------------------------------------------------------

    /**
     * Send SMTP data
     *
     * @access    private
     * @return    bool
     */
    private function _sendData($data)
    {
        if ( ! fwrite($this->_smtp_connect, $data . $this->newline))
        {
            $this->_setErrorMessage('email_smtp_data_failure', $data);
            return false;
        }
        else
        {
            return true;
        }
    }
  
    // --------------------------------------------------------------------

    /**
     * Get SMTP data
     *
     * @access    private
     * @return    string
     */
    private function _getSmtpData()
    {
        $data = "";

        while ($str = fgets($this->_smtp_connect, 512))
        {
            $data .= $str;

            if (substr($str, 3, 1) == " ")
            {
                break;
            }
        }

        return $data;
    }
  
    // --------------------------------------------------------------------

    /**
     * Get Hostname
     *
     * @access    private
     * @return    string
     */
    private function _getHostname()
    {
        return (isset($_SERVER['SERVER_NAME'])) ? $_SERVER['SERVER_NAME'] : 'localhost.localdomain';
    }
  
    // --------------------------------------------------------------------

    /**
     * Get Debug Message
     *
     * @access    public
     * @return    string
     */
    public function printDebugger()
    {
        $msg = '';

        if (count($this->_debug_msg) > 0)
        {
            foreach ($this->_debug_msg as $val)
            {
                $msg .= $val;
            }
        }

        $msg .= "<pre>".$this->_header_str."\n".htmlspecialchars($this->_subject)."\n".htmlspecialchars($this->_finalbody).'</pre>';
        
        return $msg;
    }
  
    // --------------------------------------------------------------------

    /**
     * Set Message
     *
     * @access    private
     * @param    string
     * @return    string
     */
    private function _setErrorMessage($msg, $val = '')
    {
        \Ob\getInstance()->locale->load('obullo');

        if (false === ($line = \Ob\lang($msg)))
        {
            $this->_debug_msg[] = str_replace('%s', $val, $msg)."<br />";
        }
        else
        {
            $this->_debug_msg[] = str_replace('%s', $val, $line)."<br />";
        }
    }
  
    // --------------------------------------------------------------------

    /**
     * Mime Types
     *
     * @access    private
     * @param    string
     * @return    string
     */
    private function _mimeTypes($ext = "")
    {
        $mimes = array(    'hqx'    =>    'application/mac-binhex40',
                        'cpt'    =>    'application/mac-compactpro',
                        'doc'    =>    'application/msword',
                        'bin'    =>    'application/macbinary',
                        'dms'    =>    'application/octet-stream',
                        'lha'    =>    'application/octet-stream',
                        'lzh'    =>    'application/octet-stream',
                        'exe'    =>    'application/octet-stream',
                        'class'    =>    'application/octet-stream',
                        'psd'    =>    'application/octet-stream',
                        'so'    =>    'application/octet-stream',
                        'sea'    =>    'application/octet-stream',
                        'dll'    =>    'application/octet-stream',
                        'oda'    =>    'application/oda',
                        'pdf'    =>    'application/pdf',
                        'ai'    =>    'application/postscript',
                        'eps'    =>    'application/postscript',
                        'ps'    =>    'application/postscript',
                        'smi'    =>    'application/smil',
                        'smil'    =>    'application/smil',
                        'mif'    =>    'application/vnd.mif',
                        'xls'    =>    'application/vnd.ms-excel',
                        'ppt'    =>    'application/vnd.ms-powerpoint',
                        'wbxml'    =>    'application/vnd.wap.wbxml',
                        'wmlc'    =>    'application/vnd.wap.wmlc',
                        'dcr'    =>    'application/x-director',
                        'dir'    =>    'application/x-director',
                        'dxr'    =>    'application/x-director',
                        'dvi'    =>    'application/x-dvi',
                        'gtar'    =>    'application/x-gtar',
                        'php'    =>    'application/x-httpd-php',
                        'php4'    =>    'application/x-httpd-php',
                        'php3'    =>    'application/x-httpd-php',
                        'phtml'    =>    'application/x-httpd-php',
                        'phps'    =>    'application/x-httpd-php-source',
                        'js'    =>    'application/x-javascript',
                        'swf'    =>    'application/x-shockwave-flash',
                        'sit'    =>    'application/x-stuffit',
                        'tar'    =>    'application/x-tar',
                        'tgz'    =>    'application/x-tar',
                        'xhtml'    =>    'application/xhtml+xml',
                        'xht'    =>    'application/xhtml+xml',
                        'zip'    =>    'application/zip',
                        'mid'    =>    'audio/midi',
                        'midi'    =>    'audio/midi',
                        'mpga'    =>    'audio/mpeg',
                        'mp2'    =>    'audio/mpeg',
                        'mp3'    =>    'audio/mpeg',
                        'aif'    =>    'audio/x-aiff',
                        'aiff'    =>    'audio/x-aiff',
                        'aifc'    =>    'audio/x-aiff',
                        'ram'    =>    'audio/x-pn-realaudio',
                        'rm'    =>    'audio/x-pn-realaudio',
                        'rpm'    =>    'audio/x-pn-realaudio-plugin',
                        'ra'    =>    'audio/x-realaudio',
                        'rv'    =>    'video/vnd.rn-realvideo',
                        'wav'    =>    'audio/x-wav',
                        'bmp'    =>    'image/bmp',
                        'gif'    =>    'image/gif',
                        'jpeg'    =>    'image/jpeg',
                        'jpg'    =>    'image/jpeg',
                        'jpe'    =>    'image/jpeg',
                        'png'    =>    'image/png',
                        'tiff'    =>    'image/tiff',
                        'tif'    =>    'image/tiff',
                        'css'    =>    'text/css',
                        'html'    =>    'text/html',
                        'htm'    =>    'text/html',
                        'shtml'    =>    'text/html',
                        'txt'    =>    'text/plain',
                        'text'    =>    'text/plain',
                        'log'    =>    'text/plain',
                        'rtx'    =>    'text/richtext',
                        'rtf'    =>    'text/rtf',
                        'xml'    =>    'text/xml',
                        'xsl'    =>    'text/xml',
                        'mpeg'    =>   'video/mpeg',
                        'mpg'    =>    'video/mpeg',
                        'mpe'    =>    'video/mpeg',
                        'qt'    =>    'video/quicktime',
                        'mov'    =>    'video/quicktime',
                        'avi'    =>    'video/x-msvideo',
                        'movie'    =>    'video/x-sgi-movie',
                        'doc'    =>    'application/msword',
                        'word'    =>    'application/msword',
                        'xl'    =>    'application/excel',
                        'eml'    =>    'message/rfc822'
                    );

        return ( ! isset($mimes[strtolower($ext)])) ? "application/x-unknown-content-type" : $mimes[strtolower($ext)];
    }

}
// END email class

/* End of file Email.php */
/* Location: ./ob/email/releases/0.0.1/email.php */