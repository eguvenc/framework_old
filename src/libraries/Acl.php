<?php
defined('BASE') or exit('Access Denied!');

/**
 * Obullo Framework (c) 2009 - 2012.
 *
 * PHP5 HMVC Based Scalable Software.
 *
 * @package         obullo
 * @author          obullo.com
 * @copyright       Obullo Team
 * @filesource
 * @license
 */

// ------------------------------------------------------------------------

/**
 * ACL Class
 *
 * A lightweight and simple access control list (ACL) 
 * implementation for privileges management.
 *
 * @package       Obullo
 * @subpackage    Libraries
 * @category      Libraries
 * @author        Obullo
 * @link
 */

Class OB_Acl {
   
    public $groups      = array();
    public $members     = array();
    public $access_list = array();
    
    /**
    * Constructor
    *
    * Sets the variables and runs the compilation routine
    *
    * @version   0.1
    * @access    public
    * @return    void
    */
    public function __construct()
    {
        log_me('debug', "Acl Class Initialized");
    }
    
    // ------------------------------------------------------------------------

    /**
    * Create a Group
    * 
    * @param string $group_name
    * @throws Exception 
    */
    public function add_group($group_name)
    {
        $group_name = mb_strtolower($group_name);
        
        if(strpos($group_name, '@') !== 0)
        {
            throw new Exception('The group name must be have @ prefix e.g. @admin.');
        }
        
        $this->groups[$group_name] = array();
    }
    
    // ------------------------------------------------------------------------
    
    /**
    * Add member to existing group
    * 
    * @param string $member
    * @param string $group_name
    * @throws Exception 
    */
    public function add_member($member, $group_name)
    {
        if(strpos($group_name, '@') !== 0)
        {
            throw new Exception('The group name must be have @ prefix e.g. @admin.');
        }
        
        $this->groups[$group_name][$member] = $member;
        $this->members[$member]             = $group_name;
    }
    
    // ------------------------------------------------------------------------
    
    /**
    * Delete member from existing group
    * 
    * @param string $member
    * @param string $group_name
    * @throws Exception 
    */
    public function del_member($member, $group_name)
    {
        if(strpos($group_name, '@') !== 0)
        {
            throw new Exception('The group name must be have @ prefix e.g. @admin.');
        }
        
        unset($this->groups[$group_name][$member]);
        unset($this->members[$member]);
    }
    
    // ------------------------------------------------------------------------
    
    /**
    * Add access to access list.
    * 
    * @param string $name
    * @param string $operation
    * @param boolean $deny ( private )
    * @return void
    * @throws Exception 
    */
    public function allow($name, $operation, $deny = FALSE)
    {
        if(strpos($name, '@') === 0) // group process .. 
        {
            if(isset($this->groups[$name])) // check really is it group ?
            {
                if(is_array($operation) AND count($operation) > 0)
                {
                    foreach($operation as $o_name)
                    {
                        $this->access_list[$name][$o_name] = ($deny) ? FALSE : TRUE;
                    }
                } 
                else 
                {
                    $this->access_list[$name][$operation] = ($deny) ? FALSE : TRUE;
                }
            }
            else
            {
                throw new Exception('Undefined group name <b>'.$name.'</b>, please add a group using <b>$acl->add_group()</b> method.');
            }
            
            return;
        }
    }
    
    // ------------------------------------------------------------------------
    
    /**
    * Delete an access from access list.
    * 
    * @param string $name
    * @param string $operation 
    */
    public function deny($name, $operation)
    {
        $this->allow($name, $operation, $deny = TRUE);
    }
    
    // ------------------------------------------------------------------------
   
    /**
    * Check User or Group permissions
    * 
    * @param string $name group or member name
    * @param string $operation user task
    * @return boolean if has access return TRUE else FALSE
    * @throws Exception 
    */
    public function is_allowed($name, $operation)
    {        
        if(strpos($name, '@') === 0) // group process .. 
        {
            if(isset($this->groups[$name])) // check really is it group ?
            {
                if( ! isset($this->access_list[$name][$operation])) // check operation is defined ?
                {
                    throw new Exception('Undefined operation <b>'.$operation.'</b> for <b>'.$name.'</b>, please add a operation using <b>$acl->allow('.$name.', \'operation\')</b> method.');
                }
                
                if($this->access_list[$name][$operation])
                {
                    return TRUE;
                }
            }
            else
            {
                throw new Exception('Undefined group name <b>'.$name.'</b>, please add a group using <b>$acl->add_group()</b> method.');
            }
            
            return FALSE;
        }
        
        if( ! isset($this->members[$name]))   // check really is it member ?
        {
            throw new Exception('Undefined member name <b>'.$name.'</b>, please add a member using <b>$acl->add_member(\'membername\', \'groupname\')</b> method.');
        }

        $group = $this->members[$name];   
       
        if(isset($this->groups[$group]))          // check member is a member of the group ? 
        {
            return $this->is_allowed($group, $operation);
        } 
  
        return FALSE;
    }
    
}

// END Acl Class

/* End of file Acl.php */
/* Location: ./obullo/libraries/Acl.php */