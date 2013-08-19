<?php
namespace form_Json {
    
    /**
     * Form Json Helper
     *
     * @package     Obullo
     * @subpackage  Helpers
     * @category    Helpers
     * @author      Obullo Team
     * @link        
     */
    Class start
    {
       function __construct()
       {
           \log\me('debug', 'Form Json Helper Initialized.');
       }
    }
    
    // ------------------------------------------------------------------------

    function error($model = '', $no_cache = true)
    {
        if(getInstance()->uri->extension() == 'json' AND ! headers_sent() ) // Check uri extension 
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
            if($model->customKey())
            {
                return json_encode(array('success' => false, $model->customKey('name') => $model->customKey('val'), 'errors' => array()));
            }

            if($model->errors('transaction') != '')
            {
                \log\me('debug', 'System Error: '. $model->errors('transaction'));

                return json_encode(array('success' => false, 'errors' => array('sys_error' => lang('We couldn\'t save data at this time please try again. Error: ').$model->errors('transaction'))));
            }

            if($model->errors('redirect') != '')
            {
                return json_encode(array('success' => false, 'redirect' => $model->errors('redirect')));
            }

            return json_encode(array('success' => false, 'errors' => $model->errors()));
        }
        else
        {
            return json_encode(array('success' => false, 'errors' => array('sys_error' => (string)$model)));
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
    function success($model = '', $no_cache = true)
    {
        if(getInstance()->uri->extension() == 'json' AND ! headers_sent() ) // Check uri extension 
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
            if($model->customKey('name') != '') 
            {
                $array = array('success' => true, $model->customKey('name') => $model->customKey('val'), 'msg' => $model->errors('msg'), 'errors' => $model->errors());
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

    // ------------------------------------------------------------------------

    /**
    * Redirect user to second page using Jquery
    * Obullo form plugin.
    * 
    * @param string $redirect_url /page/to/redirect
    * @return string
    */
    function redirect($redirect_url, $top_redirect = false)
    {
        if(getInstance()->uri->extension() == 'json' AND ! headers_sent() ) // Check uri extension 
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

    // ------------------------------------------------------------------------

    /**
    * Change form action dynamically and
    * post data to another url if form validation success. 
    * 
    * @param string $post_url url you want to post 2nd page
    * @return string
    */
    function forward($forward_url)
    {
        if(getInstance()->uri->extension() == 'json' AND ! headers_sent() ) // Check uri extension 
        {
            header('Content-type: application/json;charset=UTF-8');
        }
        
        return json_encode(array('success' => true, 'forward_url' => $forward_url));
    }

}

/* End of file form_json.php */
/* Location: ./ob/form_json/releases/0.0.1/form_json.php */