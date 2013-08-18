<?php

/**
 * ACL Class
 *
 * A lightweight and simple access control list (ACL) 
 * implementation for privileges management.
 *
 * @package       Obullo
 * @subpackage    Libraries
 * @category      Libraries
 * @author        Obullo Team
 * @link
 */

Class Acl {
   
    public $groups      = array();
    public $members     = array();
    public $access_list = array();
    
    /**
    * Constructor
    *
    * Sets the variables and runs the compilation routine
    * 
    * @param mixed $no_instance
    * @version   0.1
    * @access    public
    * @return    void
    */
    public function __construct($no_instance = true)
    {
        if($no_instance)
        {
            getInstance()->acl = $this; // Available it in the contoller $this->auth->method();
        }
        
        log\me('debug', "Acl Class Initialized");
    }
   
    // ------------------------------------------------------------------------
    
    /**
     * Initialize to Class.
     * 
     * @return object
     */
    public function init()
    {
        return ($this);
    }
    
    // ------------------------------------------------------------------------
    
    /**
    * Create a Group
    * 
    * @param string $group_name
    * @throws Exception 
    */
    public function addGroup($group_name)
    {
        $group_name = mb_strtolower($group_name);
        
        if(strpos($group_name, '@') !== 0)
        {
            throw new Exception('The group name must have @ prefix e.g. @admin.');
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
    public function addMember($member, $group_name)
    {
        if(strpos($group_name, '@') !== 0)
        {
            throw new Exception('The group name must have @ prefix e.g. @admin.');
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
    public function delMember($member, $group_name)
    {
        if(strpos($group_name, '@') !== 0)
        {
            throw new Exception('The group name must have @ prefix e.g. @admin.');
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
    public function allow($name, $operation, $deny = false)
    {
        if(strpos($name, '@') === 0) // group process .. 
        {
            if(isset($this->groups[$name])) // check really is it group ?
            {
                if(is_array($operation) AND count($operation) > 0)
                {
                    foreach($operation as $o_name)
                    {
                        $this->access_list[$name][$o_name] = ($deny) ? false : true;
                    }
                } 
                else 
                {
                    $this->access_list[$name][$operation] = ($deny) ? false : true;
                }
            }
            else
            {
                throw new Exception('Undefined group name '.$name.', please add a group using $acl->addGroup() method.');
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
        $this->allow($name, $operation, $deny = true);
    }
    
    // ------------------------------------------------------------------------
   
    /**
    * Check User or Group permissions
    * 
    * @param string $name group or member name
    * @param string $operation user task
    * @return boolean if has access return true else false
    * @throws Exception 
    */
    public function isAllowed($name, $operation)
    {        
        if(strpos($name, '@') === 0) // group process .. 
        {
            if(isset($this->groups[$name])) // check really is it group ?
            {
                if( ! isset($this->access_list[$name][$operation])) // check operation is defined ?
                {
                    throw new Exception('Undefined operation '.$operation.' for '.$name.', please add a operation using $acl->allow('.$name.', \'operation\') method.');
                }
                
                if($this->access_list[$name][$operation])
                {
                    return true;
                }
            }
            else
            {
                throw new Exception('Undefined group name '.$name.', please add a group using $acl->addGroup() method.');
            }
            
            return false;
        }
        
        if( ! isset($this->members[$name]))   // check really is it member ?
        {
            throw new Exception('Undefined member name '.$name.', please add a member using $acl->addMember(\'membername\', \'groupname\') method.');
        }

        $group = $this->members[$name];   
       
        if(isset($this->groups[$group]))          // check member is a member of the group ? 
        {
            return $this->isAllowed($group, $operation);
        } 
  
        return false;
    }
    
}

// END Acl Class

/* End of file Acl.php */
/* Location: ./ob/acl/releases/0.0.1/acl.php */