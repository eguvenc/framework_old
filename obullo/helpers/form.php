<?php
defined('BASE') or exit('Access Denied!');

/**
 * Obullo Framework (c) 2009.
 *
 * PHP5 HMVC Based Scalable Software.
 * 
 * @package         obullo       
 * @author          obullo.com
 * @license         public
 * @since           Version 1.0
 * @filesource
 * @license
 */

// ------------------------------------------------------------------------

/**
 * Obullo Form Helpers
 *
 * @package     Obullo
 * @subpackage  Helpers
 * @category    Helpers
 * @author      Obullo Team
 * @link        
 */

// ------------------------------------------------------------------------

/**
* Form Declaration
*
* Creates the opening portion of the form.
*
* @access	public
* @param	string	the URI segments of the form destination
* @param	array	a key/value pair of attributes
* @param	array	a key/value pair hidden data
* @return	string
*/
if( ! function_exists('form_open') ) 
{
    function form_open($action = '', $attributes = '', $hidden = array())
    {
        $config = lib('ob/Config');

        if ($attributes == '')
        {
            $attributes = 'method="post"';
        }

        $action = ( strpos($action, '://') === FALSE) ? $config->site_url($action) : $action;

        $form = '<form action="'.$action.'"';

        $form .= _attributes_to_string($attributes, TRUE);

        $form .= '>';
        
        if ($config->item('csrf_protection') === TRUE) // CSRF Support
        {
            $security = lib('ob/Security');
            
            $hidden[$security->get_csrf_token_name()] = $security->get_csrf_hash();
        }

        if (is_array($hidden) AND count($hidden) > 0)
        {
            $form .= sprintf("<div style=\"display:none\">%s</div>", form_hidden($hidden));
        }

        return $form;
    }
}
// ------------------------------------------------------------------------

/**
* Form Declaration - Multipart type
*
* Creates the opening portion of the form, but with "multipart/form-data".
*
* @access	public
* @param	string	the URI segments of the form destination
* @param	array	a key/value pair of attributes
* @param	array	a key/value pair hidden data
* @return	string
*/
if( ! function_exists('form_open_multipart') ) 
{    
    function form_open_multipart($action, $attributes = array(), $hidden = array())
    {
        if (is_string($attributes))
        {
            $attributes .= ' enctype="multipart/form-data"';
        }
        else
        {
            $attributes['enctype'] = 'multipart/form-data';
        }

	return form_open($action, $attributes, $hidden);
    }
}
// ------------------------------------------------------------------------

/**
* Hidden Input Field
*
* Generates hidden fields.  You can pass a simple key/value string or an associative
* array with multiple values.
*
* @access	public
* @param	mixed
* @param	string
* @return	string
*/
if( ! function_exists('form_hidden') ) 
{    
    function form_hidden($name, $value = '', $extra = '', $recursing = FALSE)
    {
        static $form;

        if ($recursing === FALSE)
        {
            $form = "\n";
        }

        if (is_array($name))
        {
            foreach ($name as $key => $val)
            {
                form_hidden($key, $val, '', TRUE);
            }
            return $form;
        }

        if ( ! is_array($value))
        {
            $form .= '<input type="hidden" name="'.$name.'" value="'.form_prep($value, $name).'"'. $extra . '/>'."\n";
        }
        else
        {
            foreach ($value as $k => $v)
            {
                $k = (is_int($k)) ? '' : $k; 
                form_hidden($name.'['.$k.']', $v, '', TRUE);
            }
        }

        return $form;
}
}
// ------------------------------------------------------------------------

/**
* Text Input Field
*
* @access	public
* @param	mixed
* @param	string
* @param	string
* @return	string
*/
if( ! function_exists('form_input') ) 
{  
    function form_input($data = '', $value = '', $extra = '')
    {
        $defaults = array('type' => 'text', 'name' => (( ! is_array($data)) ? $data : ''), 'value' => $value);

        return "<input "._parse_form_attributes($data, $defaults).$extra." />";
    }
}
// ------------------------------------------------------------------------

/**
* Password Field
*
* Identical to the input function but adds the "password" type
*
* @access	public
* @param	mixed
* @param	string
* @param	string
* @return	string
*/
if( ! function_exists('form_password') ) 
{ 
    function form_password($data = '', $value = '', $extra = '')
    {
        if ( ! is_array($data))
        {
            $data = array('name' => $data);
        }

        $data['type'] = 'password';
        return form_input($data, $value, $extra);
    }
}
// ------------------------------------------------------------------------

/**
* Upload Field
*
* Identical to the input function but adds the "file" type
*
* @access	public
* @param	mixed
* @param	string
* @param	string
* @return	string
*/
if( ! function_exists('form_upload') ) 
{ 
    function form_upload($data = '', $value = '', $extra = '')
    {
        if ( ! is_array($data))
        {
            $data = array('name' => $data);
        }
        
        $data['type'] = 'file';
        return form_input($data, $value, $extra);
    }
}
// ------------------------------------------------------------------------

/**
* Textarea field
*
* @access	public
* @param	mixed
* @param	string
* @param	string
* @return	string
*/
if( ! function_exists('form_textarea') ) 
{ 
    function form_textarea($data = '', $value = '', $extra = '')
    {
        $defaults = array('name' => (( ! is_array($data)) ? $data : ''), 'cols' => '90', 'rows' => '12');

        if ( ! is_array($data) OR ! isset($data['value']))
        {
            $val = $value;
        }
        else
        {
            $val = $data['value']; 
            unset($data['value']); // textareas don't use the value attribute
        }

        $name = (is_array($data)) ? $data['name'] : $data;
        return "<textarea "._parse_form_attributes($data, $defaults).$extra.">".form_prep($val, $name)."</textarea>";
    }
}
// ------------------------------------------------------------------------

/**
* Multi-select menu
*
* @access	public
* @param	string
* @param	array
* @param	mixed
* @param	string
* @return	type
*/
if( ! function_exists('form_multiselect') ) 
{ 
    function form_multiselect($name = '', $options = array(), $selected = array(), $extra = '')
    {
        if ( ! strpos($extra, 'multiple'))
        {
                $extra .= ' multiple="multiple"';
        }

        return form_dropdown($name, $options, $selected, $extra);
    }
}

// --------------------------------------------------------------------

/**
* Drop-down Menu
*
* @access	public
* @param	string
* @param	array
* @param	string
* @param	string
* @return	string
*/
if( ! function_exists('form_dropdown') ) 
{
    function form_dropdown($name = '', $options = array(), $selected = array(), $extra = '')
    {
        if($selected === FALSE)   // False == "0" bug fix, FALSE is not a Integer.
        {
            $selected_option = array_keys($options);
            $selected == $selected_option[0];
        }
        
        if ( ! is_array($selected))
        {
            $selected = array($selected);
        }

        // If no selected state was submitted we will attempt to set it automatically
        if (count($selected) === 0)
        {
                // If the form name appears in the $_POST array we have a winner!
                if (isset($_POST[$name]))
                {
                        $selected = array($_POST[$name]);
                }
        }

        if ($extra != '') 
        {
            $extra = ' '.$extra;
        }

        $multiple = (count($selected) > 1 && strpos($extra, 'multiple') === FALSE) ? ' multiple="multiple"' : '';

        $form = '<select name="'.$name.'"'.$extra.$multiple.">\n";

        foreach ($options as $key => $val)
        {
                $key = (string) $key;

                if (is_array($val))
                {
                        $form .= '<optgroup label="'.$key.'">'."\n";

                        foreach ($val as $optgroup_key => $optgroup_val)
                        {
                                $sel = (in_array($optgroup_key, $selected, true)) ? ' selected="selected"' : '';

                                $form .= '<option value="'.$optgroup_key.'"'.$sel.'>'.(string) $optgroup_val."</option>\n";
                        }

                        $form .= '</optgroup>'."\n";
                }
                else
                {
                        $sel = (in_array($key, $selected, true)) ? ' selected="selected"' : '';

                        $form .= '<option value="'.$key.'"'.$sel.'>'.(string) $val."</option>\n";
                }
        }

        $form .= '</select>';

        return $form;
    }
}
// ------------------------------------------------------------------------

/**
* Checkbox Field
*
* @access	public
* @param	mixed
* @param	string
* @param	bool
* @param	string
* @return	string
*/
if( ! function_exists('form_checkbox') ) 
{ 
    function form_checkbox($data = '', $value = '', $checked = FALSE, $extra = '')
    {
        $defaults = array('type' => 'checkbox', 'name' => (( ! is_array($data)) ? $data : ''), 'value' => $value);

        if (is_array($data) AND array_key_exists('checked', $data))
        {
            $checked = $data['checked'];

            if ($checked == FALSE)
            {
                unset($data['checked']);
            }
            else
            {
                $data['checked'] = 'checked';
            }
        }

        if ($checked == TRUE)
        {
            $defaults['checked'] = 'checked';
        }
        else
        {
            unset($defaults['checked']);
        }

        return "<input "._parse_form_attributes($data, $defaults).$extra." />";
    }
}

// ------------------------------------------------------------------------

/**
* Radio Button
*
* @access	public
* @param	mixed
* @param	string
* @param	bool
* @param	string
* @return	string
*/
if( ! function_exists('form_radio') ) 
{ 
    function form_radio($data = '', $value = '', $checked = FALSE, $extra = '')
    {
        if ( ! is_array($data))
        {
            $data = array('name' => $data); 
        }

        $data['type'] = 'radio';
        return form_checkbox($data, $value, $checked, $extra);
    }
}

// ------------------------------------------------------------------------

/**
* Submit Button
*
* @access	public
* @param	mixed
* @param	string
* @param	string
* @return	string
*/
if( ! function_exists('form_submit') ) 
{ 
    function form_submit($data = '', $value = '', $extra = '')
    {
        $defaults = array('type' => 'submit', 'name' => (( ! is_array($data)) ? $data : ''), 'value' => $value);

        return "<input "._parse_form_attributes($data, $defaults).$extra." />";
    }
}

// ------------------------------------------------------------------------

/**
* Reset Button
*
* @access    public
* @param    mixed
* @param    string
* @param    string
* @return    string
*/
if( ! function_exists('form_reset') ) 
{ 
    function form_reset($data = '', $value = '', $extra = '')
    {
        $defaults = array('type' => 'reset', 'name' => (( ! is_array($data)) ? $data : ''), 'value' => $value);

        return "<input "._parse_form_attributes($data, $defaults).$extra." />";
    }
}

// ------------------------------------------------------------------------

/**
* Form Button
*
* @access	public
* @param	mixed
* @param	string
* @param	string
* @return	string
*/
if( ! function_exists('form_button') ) 
{ 
    function form_button($data = '', $content = '', $extra = '')
    {
        $defaults = array('name' => (( ! is_array($data)) ? $data : ''), 'type' => 'button');

        if ( is_array($data) AND isset($data['content']))
        {
            $content = $data['content'];
            unset($data['content']); // content is not an attribute
        }

        return "<button "._parse_form_attributes($data, $defaults).$extra.">".$content."</button>";
    }
}

// ------------------------------------------------------------------------

/**
* Form Label Tag
*
* @access	public
* @param	string	The text to appear onscreen
* @param	string	The id the label applies to
* @param	string	Additional attributes
* @return	string
*/
if( ! function_exists('form_label') ) 
{ 
    function form_label($label_text = '', $id = '', $attributes = array())
    {
        $label = '<label';

        if ($id != '')
        {
            $label .= " for=\"$id\"";
        }

        if (is_array($attributes) AND count($attributes) > 0)
        {
            foreach ($attributes as $key => $val)
            {
                    $label .= ' '.$key.'="'.$val.'"';
            }
        }

        $label .= ">$label_text</label>";

        return $label;
    }
}

// ------------------------------------------------------------------------

/**
* Fieldset Tag
*
* Used to produce <fieldset><legend>text</legend>.  To close fieldset
* use form_fieldset_close()
*
* @access	public
* @param	string	The legend text
* @param	string	Additional attributes
* @return	string
*/
if( ! function_exists('form_fieldset') ) 
{ 
    function form_fieldset($legend_text = '', $attributes = array())
    {
        $fieldset = "<fieldset";

        $fieldset .= _attributes_to_string($attributes, FALSE);

        $fieldset .= ">\n";

        if ($legend_text != '')
        {
                $fieldset .= "<legend>$legend_text</legend>\n";
        }

        return $fieldset;
    }
}
// ------------------------------------------------------------------------

/**
* Fieldset Close Tag
*
* @access	public
* @param	string
* @return	string
*/
if( ! function_exists('form_fieldset_close') ) 
{ 
    function form_fieldset_close($extra = '')
    {
        return "</fieldset>".$extra;
    }
}

// ------------------------------------------------------------------------

/**
* Form Close Tag
*
* @access	public
* @param	string
* @return	string
*/
if( ! function_exists('form_close') ) 
{ 
    function form_close($extra = '')
    {
        return "</form>".$extra;
    }
}

// ------------------------------------------------------------------------

/**
* Form Prep
*
* Formats text so that it can be safely placed in a form field in the event it has HTML tags.
*
* @access	public
* @param	string
* @return	string
*/
if( ! function_exists('form_prep') ) 
{ 
    function form_prep($str = '')
    {
        // if the field name is an array we do this recursively
        if (is_array($str))
        {
            foreach ($str as $key => $val)
            {
                $str[$key] = form_prep($val);
            }

            return $str;
        }

        if ($str === '')
        {
            return '';
        }

        $temp = '__TEMP_AMPERSANDS__';

        // Replace entities to temporary markers so that 
        // htmlspecialchars won't mess them up
        $str = preg_replace("/&#(\d+);/", "$temp\\1;", $str);
        $str = preg_replace("/&(\w+);/",  "$temp\\1;", $str);

        $str = htmlspecialchars($str);

        // In case htmlspecialchars misses these.
        $str = str_replace(array("'", '"'), array("&#39;", "&quot;"), $str);

        // Decode the temp markers back to entities
        $str = preg_replace("/$temp(\d+);/","&#\\1;",$str);
        $str = preg_replace("/$temp(\w+);/","&\\1;",$str);

        return $str;
    }
}

// ------------------------------------------------------------------------

/**
* Form Value
*
* Grabs a value from the POST array for the specified field so you can
* re-populate an input field or textarea.  If Form Validation
* is active it retrieves the info from the validation class
*
* @access	public
* @param	string
* @return	mixed
*/
if( ! function_exists('set_value') ) 
{ 
    function set_value($field = '', $default = '')
    {
        if (FALSE === ($OBJ = _get_validation_object()))
        {
            if ( ! isset($_REQUEST[$field]))
            {
                return $default;
            }

            if(isset($_REQUEST[$field]))
            {
                return form_prep($_REQUEST[$field], $field);
            }   
        }

        return form_prep($OBJ->set_value($field, $default), $field);
    }
}

// ------------------------------------------------------------------------

/**
* Set Select
*
* Let's you set the selected value of a <select> menu via data in the POST array.
* If Form Validation is active it retrieves the info from the validation class
*
* @access	public
* @param	string
* @param	string
* @param	bool
* @return	string
*/
if( ! function_exists('set_select') ) 
{ 
    function set_select($field = '', $value = '', $default = FALSE)
    {
        $OBJ = _get_validation_object();

        if ($OBJ === FALSE)
        {
            if ( ! isset($_REQUEST[$field]))
            {
                if (count($_REQUEST) === 0 AND $default == TRUE)
                {
                    return ' selected="selected"';
                }
                
                return '';
            }

            $field = $_REQUEST[$field];

            if (is_array($field))
            {
                if ( ! in_array($value, $field))
                {
                    return '';
                }
            }
            else
            {
                if (($field == '' OR $value == '') OR ($field != $value))
                {
                    return '';
                }
            }

            return ' selected="selected"';
        }

        return $OBJ->set_select($field, $value, $default);
    }
}

// ------------------------------------------------------------------------

/**
* Set Checkbox
*
* Let's you set the selected value of a checkbox via the value in the POST array.
* If Form Validation is active it retrieves the info from the validation class
*
* @access	public
* @param	string
* @param	string
* @param	bool
* @return	string
*/
if( ! function_exists('set_checkbox') ) 
{ 
    function set_checkbox($field = '', $value = '', $default = FALSE)
    {
        $OBJ = _get_validation_object();

        if ($OBJ === FALSE)
        { 
            if ( ! isset($_REQUEST[$field]))
            {
                if (count($_REQUEST) === 0 AND $default == TRUE)
                {
                        return ' checked="checked"';
                }
                return '';
            }

            $field = $_REQUEST[$field];

            if (is_array($field))
            {
                if ( ! in_array($value, $field))
                {
                        return '';
                }
            }
            else
            {
                if (($field == '' OR $value == '') OR ($field != $value))
                {
                        return '';
                }
            }

            return ' checked="checked"';
        }

        return $OBJ->set_checkbox($field, $value, $default);
    }
}

// ------------------------------------------------------------------------

/**
* Set Radio
*
* Let's you set the selected value of a radio field via info in the POST array.
* If Form Validation is active it retrieves the info from the validation class
*
* @access	public
* @param	string
* @param	string
* @param	bool
* @return	string
*/
if( ! function_exists('set_radio') ) 
{ 
    function set_radio($field = '', $value = '', $default = FALSE)
    {
        $OBJ = _get_validation_object();

        if ($OBJ === FALSE)
        {
            if ( ! isset($_REQUEST[$field]))
            {
                if (count($_REQUEST) === 0 AND $default == TRUE)
                {
                    return ' checked="checked"';
                }
                
                return '';
            }

            $field = $_REQUEST[$field];

            if (is_array($field))
            {
                if ( ! in_array($value, $field))
                {
                    return '';
                }
            }
            else
            {
                if (($field == '' OR $value == '') OR ($field != $value))
                {
                    return '';
                }
            }

            return ' checked="checked"';
        }

        return $OBJ->set_radio($field, $value, $default);
    }
}
// ------------------------------------------------------------------------

/**
* Form Error
*
* Returns the error for a specific form field.  This is a helper for the
* form validation class.
*
* @access	public
* @param	string | object
* @param	string
* @param	string
* @return	string
*/
if( ! function_exists('form_error') ) 
{ 
    function form_error($field = '', $prefix = '<p>', $suffix = '</p>')
    {        
        if(isset($_GET['errors'][$field])) // GET Support
        {
            return $prefix.$_GET['errors'][$field].$suffix;
        }
        
        if(is_object($field)) // Validation Model System Error Support
        {
            $model = &$field;
            
            if(isset($model->errors[$model->item('table')]['transaction_error']))
            {
                log_me('debug', 'Transactional Error: '. $model->errors[$model->item('table')]['transaction_error']);
                
                return lang('vm_system_message') . $model->errors[$model->item('table')]['transaction_error'];
            }

            if($model->error('success') == 0)
            {
                return $model->error('msg');
            }
            
            return;
        }
        
        if (FALSE === ($OBJ = _get_validation_object()))
        {
            return '';
        }

        return $OBJ->error($field, $prefix, $suffix);
    }
}


// ------------------------------------------------------------------------

/**
 * Check form field is return to error
 * if $return_string not empty it returns to
 * string otherwise it returns to booelan.
 * 
 * @param string $field
 * @param string $return_string
 * @return booelan | string
 */
function form_is_error($field = '', $return_string = '')
{
    $error = form_error($field);
    
    if($error != '')
    {
        return ($return_string != '') ? $return_string : TRUE;
    }
    
    return ($return_string != '') ? '' : FALSE;
}

// ------------------------------------------------------------------------

/**
 * Form (Validation Model) Head Error
 * 
 * @param object $model
 * @param string $save_msg
 * @return string 
 */

/**
 * Form (Validation Model) Head Error
 * 
 * @param object $model
 * @param string $save_msg
 * @return string 
 */
if( ! function_exists('form_msg'))
{
    function form_msg($model = '', $form_msg = '', $prefix = '<div class="notification error">', $suffix = '</div>')
    {
        if( ! is_object($model))
        {
           return;
        }
        
        if(isset($model->errors[$model->item('table')]['transaction_error']))
        {
            log_me('debug', 'Transactional Error: '. $model->errors[$model->item('table')]['transaction_error']);

            $msg =  $model->errors[$model->item('table')]['msg'] .' '. lang('vm_system_message') . $model->errors[$model->item('table')]['transaction_error'];
            
            return ($msg == '') ? '' : $prefix.$msg.$suffix;
        }
        
        if($model->errors('system_msg') != '')
        {
            $msg = $model->errors('system_msg');
            
            return ($msg == '') ? '' : $prefix.$msg.$suffix;
        }
        
        if($form_msg === FALSE)
        {
            return;
        }
        
        if($model->errors('success') == 0)
        {
            $msg = lang('vm_form_error');

            if( ! empty($form_msg))
            {
                $msg = $form_msg;
            }
            
            if($model->validation())  // If validation ok but we have a transaction error ?
            {
                $msg = form_error($model);
            }

            return ($msg == '') ? '' : $prefix.$msg.$suffix;
        }
    }
}

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
if( ! function_exists('validation_errors') ) 
{ 
    function validation_errors($prefix = '', $suffix = '')  // Obullo changes ..
    {
        if (FALSE === ($OBJ = _get_validation_object()))
        {
            return '';
        }

        return $OBJ->error_string($prefix, $suffix);
    }
}

// ------------------------------------------------------------------------

/**
* Parse the form attributes
*
* Helper function used by some of the form helpers
*
* @access	private
* @param	array
* @param	array
* @return	string
*/
if( ! function_exists('_parse_form_attributes') ) 
{ 
    function _parse_form_attributes($attributes, $default)
    {
        if (is_array($attributes))
        {
            foreach ($default as $key => $val)
            {
                if (isset($attributes[$key]))
                {
                    $default[$key] = $attributes[$key];
                    unset($attributes[$key]);
                }
            }

            if (count($attributes) > 0)
            {
                $default = array_merge($default, $attributes);
            }
        }

        $att = '';

        foreach ($default as $key => $val)
        {
            if ($key == 'value')
            {
                $val = form_prep($val, $default['name']);
            }

            $att .= $key . '="' . $val . '" ';
        }

        return $att;
    }
}

// ------------------------------------------------------------------------

/**
* Attributes To String
*
* Helper function used by some of the form helpers
*
* @access	private
* @param	mixed
* @param	bool
* @return	string
*/
if( ! function_exists('_attributes_to_string') ) 
{ 
    function _attributes_to_string($attributes, $formtag = FALSE)
    {
        if (is_string($attributes) AND strlen($attributes) > 0)
        {
            if ($formtag == TRUE AND strpos($attributes, 'method=') === FALSE)
            {
                $attributes .= ' method="post"';
            }

            return ' '.$attributes;
        }

        if (is_object($attributes) AND count($attributes) > 0)
        {
            $attributes = (array)$attributes;
        }

        if (is_array($attributes) AND count($attributes) > 0)
        {
            $atts = '';

            if ( ! isset($attributes['method']) AND $formtag === TRUE)
            {
                $atts .= ' method="post"';
            }

            foreach ($attributes as $key => $val)
            {
                $atts .= ' '.$key.'="'.$val.'"';
            }

            return $atts;
        }
    }
}

// ------------------------------------------------------------------------

/**
* Validation Object
*
* Determines what the form validation class was instantiated as, fetches
* the object and returns it.
*
* @access	private
* @return	mixed
*/
if( ! function_exists('_get_validation_object') ) 
{ 
    function _get_validation_object()
    {
        if ( ! class_exists('OB_Validator'))  // Obullo Changes ..
        {
            return FALSE;
        }
        
        return lib('ob/Validator');
    }
}


/* End of file form.php */
/* Location: ./obullo/helpers/form.php */