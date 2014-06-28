<?php
namespace Form\Src {

    // ------------------------------------------------------------------------

    /**
    * Validation Error String
    *
    * Returns all the errors associated with a form submission.  This is a helper
    * function for the form validation class.
    *
    * @access	public
    * @param	string
    * @param	string
    * @return	string
    */
    function getErrorString($prefix = '', $suffix = '')
    {
        if (false === ($OBJ = getInstance()->form->_getValidatorObject()))
        {
            return '';
        }

        return $OBJ->getErrorString($prefix, $suffix);
    }

}