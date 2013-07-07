<?php

/**
 * Json Form Validation Helper
 *
 * @package     Obullo
 * @subpackage  Helpers
 * @category    Helpers
 * @author      Obullo Team
 * @link        
 */

/**
* Send Json Errors
*
* @access public
* @param object | string $model or $system_message
* @return string
*/
if ( ! function_exists('form_json_error'))
{
    function form_json_error($model = '', $no_cache = true)
    {
        if(uri_extension() == 'json' AND ! headers_sent() ) // Check uri extension 
        {
            /*
            * The first two headers prevent the browser from caching the 
            * response (a problem with IE and GET requests) and the third sets 
            * the correct MIME type for JSON.
            */
            if($no_cache)
            {
                header('Cache-Control: no-cache, must-revalidate');
                header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            }

            header('Content-type: application/json;charset=UTF-8');
        }
        
        if(is_object($model))
        {
            if($model->get_func())
            {
                return json_encode(array('success' => false, $model->get_func('name') => $model->get_func('val'), 'errors' => array()));
            }
           
            $table = $model->schema->config['table'];
            
            if(isset($model->errors[$table]['transaction_error']))
            {
                log_me('debug', 'Transaction Error: '. $model->errors[$table]['transaction_error']);
                
                return json_encode(array('success' => false, 'errors' => array('system_msg' => lang('odm_system_msg').$model->errors[$table]['transaction_error'])));
            }

            if(isset($model->errors[$table]['redirect']))
            {
                return json_encode(array('success' => false, 'redirect' => $model->errors[$table]['redirect']));
            }

            return json_encode(array('success' => false, 'errors' => $model->errors[$table]));
        }
        else
        {
            return json_encode(array('success' => false, 'errors' => array('system_msg' => $model)));
        }
    }
}
    
// ------------------------------------------------------------------------

/**
* Json send success, send succes if form data 
* save operation successfull.
*
* @access public
* @param string $message success msg
* @param boolean $js_alert return to javascript alert
* @return string
*/
if ( ! function_exists('form_json_success'))
{
    function form_json_success($model = '', $no_cache = true)
    {
        if(uri_extension() == 'json' AND ! headers_sent() ) // Check uri extension 
        {
            /*
            * The first two headers prevent the browser from caching the 
            * response (a problem with IE and GET requests) and the third sets 
            * the correct MIME type for JSON.
            */
            if($no_cache)
            {
                header('Cache-Control: no-cache, must-revalidate');
                header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            }

            header('Content-type: application/json;charset=UTF-8');
        }
        
        if(is_object($model))
        {
            if($model->get_func('name') != '') 
            {
                $array = array('success' => true, $model->get_func('name') => $model->get_func('val'), 'msg' => $model->errors('msg'), 'errors' => $model->errors());
            } 
            else
            {
                $array = array('success' => true, 'msg' => $model->errors('msg'), 'errors' => $model->errors());
            }
        }
        else
        {
            $array = array('success' => true, 'msg' => $model);
        }
        
        return json_encode($array);
    }
}

// ------------------------------------------------------------------------

/**
* Redirect user to second page using Jquery
* Obullo form plugin.
* 
* @param string $redirect_url /page/to/redirect
* @return string
*/
if ( ! function_exists('form_json_redirect'))
{
    function form_json_redirect($redirect_url, $top_redirect = FALSE)
    {
        if(uri_extension() == 'json' AND ! headers_sent() ) // Check uri extension 
        {
            header('Content-type: application/json;charset=UTF-8');
        }
        
        $type = 'redirect'; // window.location.replace(); command
        
        if($top_redirect)   // this functionality execute the javascript window.top.location.replace(); command
        {
            $type = 'top_redirect';
        }
        
        return json_encode(array('success' => true, 'msg' => '', $type => $redirect_url));
    }
}

// ------------------------------------------------------------------------

/**
* Change form action dynamically and
* post data to another url if form validation success. 
* 
* @param string $post_url url you want to post 2nd page
* @return string
*/
if ( ! function_exists('form_json_forward'))
{
    function form_json_forward($forward_url)
    {
        if(uri_extension() == 'json' AND ! headers_sent() ) // Check uri extension 
        {
            header('Content-type: application/json;charset=UTF-8');
        }
        
        return json_encode(array('success' => true, 'forward_url' => $forward_url));
    }
}


/* End of file form_json.php */
/* Location: ./ob_modules/form_json/releases/0.0.1/form_json.php */