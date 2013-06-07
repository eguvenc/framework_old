
/**
 * Obullo JQuery Form Validation Plugin  (c) 2011.
 *
 * PHP5 HMVC Based Scalable Software.
 *
 * @package         obullo       
 * @author          obullo.com
 * @license         public
 * @since           Version 1.1
 * @filesource
 * @license
 */

(function() {
  (function($, window) {
    $.fn.form = function(options) {
      var $base, base, settings;

      if (options == null) {
        options = {};
      }                                    
      
      base = this;
      $base = $(base.selector);
      
      settings = _.extend({
        error_msg: form_plugin_settings.error_msg,
        success: function() {},
        error: function() {},
        before: function() {},
        on_load: function() {}
      }, options);
      
      return $base.livequery(function() {        
        var $root, method, root;

        root = this;

        $root = root.$root = $(root);
        method = root.method || 'post';

        if (!_.isEmpty(options)) {
          $root.removeData('form_plugin_active');
          $root.unbind('submit');
        }
        
        if (!$root.data('form_plugin_active')) {
          settings.on_load.call(root, $root);
          
          $root.data('form_plugin_active', true);

          return $root.bind('submit', function() { 
            var data, i;
            
            settings.before.call(root, $root);
            data = $root.find(':visible, [type="hidden"], .send_even_if_hidden').serialize();
            
            if (data.length) {
              data += "&SOCKET_ID=" + window.SOCKET_ID;
            } else {
              data = "SOCKET_ID=" + window.SOCKET_ID;
            }

            $root.find('input[type=submit]', this).attr('disabled', 'disabled');
            $root.find('input[type=submit]', this).addClass('disabled');
            
            $loading = $root.find('.loading_div');
            $loading.after(form_plugin_settings.loading_div);

            $.ajax({
              type: method,
              url: $root.attr('action'),
              dataType: form_plugin_settings.ajax_data_type,
              cache: form_plugin_settings.ajax_cache,
              timeout: form_plugin_settings.ajax_timeout,
              data: data,
              complete: function(){ },
              success: function(r) { 
                
                $root.parent().find('.notification').remove();
                $root.find('.input-error').remove();
              
                if (!r.success) {

                  if ( typeof r.redirect !== 'undefined' && r.redirect)  // if we have server redirect request
                  {
                      window.location.replace(r.redirect);
                      return;
                  }
                 
                  if ( typeof r.alert !== 'undefined' && r.alert != '')  // if we have alert request
                  {
                      alert(r.alert);
                  
                      $('.loading').hide();
                      $root.find('input[type=submit]', this).removeAttr('disabled');
                      $root.find('input[type=submit]', this).removeClass('disabled');
                      
                      return;
                  }
                  
                  $('.loading').hide();
                  $root.find('input[type=submit]', this).removeAttr('disabled');
                  $root.find('input[type=submit]', this).removeClass('disabled');

                  if (r.errors.system_msg) {
                    $root.notification('error', r.errors.system_msg);
                    $('.notification.notification-error').attr("tabindex", '0').focus();
                    return;
                  }

                  settings.error.call(root, r, $root);
                  
                  if ($root.data('form.error')) {
                    $root.data('form.error').call(root, r, $root);
                  }

                  if(_.strpos($root.attr('class'), 'no-top-msg') === false)
                  {
                      $root.notification('error', settings.error_msg);
                  }
                  
                  return _.each(r.errors, function(value, key) {
                    var $input, ar_key, name;
                    ar_key = _.explode('__', key);
                    name = ar_key[0];
                    ar_key = _.rest(ar_key, 1);
                    if (ar_key.length) {
                      name = "" + name + "[" + (_.implode('][', ar_key)) + "]";
                    }

                   // added eq(0) for unique errors (radio, checkbox etc..)
                   $input = $root.find("[name='" + name + "']:visible:eq(0)"); 

                   var i = 0;
                   i = i + 1;
                   
                   if ( ! $input.prev('[class=input-error]').length) {
                        $input.before("<div class='input-error' tabindex='"+ i +"'>" + value + "</div>");
                        $root.find("[class=input-error]:visible:eq(0)").attr("tabindex", i).focus();
                   }
                    
                  });
                  
                } else {

                  if ( typeof r.forward_url !== "undefined" && r.forward_url)  // if we have a forward url request
                  {
                      $root.attr('action', r.forward_url);
                      document.forms[$root.attr('name')].submit();
                      return;
                  }

                  if ( typeof r.redirect !== "undefined" && r.redirect)  // if we have a server redirect request
                  {
                      window.location.replace(r.redirect);
                      return;
                  }

                  $('.loading').hide();
                  $root.find('input[type=submit]', this).removeAttr('disabled');
                  $root.find('input[type=submit]', this).removeClass('disabled');

                  settings.success.call(root, r, $root);
                  
                  if ($root.data('form.success')) {
                    $root.data('form.success').call(root, r, $root);
                  }
                  
                  if ( typeof r.alert !== 'undefined' && r.alert != '')  // if we have alert request
                  {
                      alert(r.alert);
                      return;
                  }
                  
                  if (r.msg)
                  {
                    $root.notification('success', r.msg);
                    
                    $('.notification.notification-success').attr("tabindex", '0').focus();
                  } 
                  else if(settings.success_msg)
                  {
                     $root.notification('success', settings.success_msg);
                     
                     $('.notification.notification-success').attr("tabindex", '0').focus();
                  }

                  if(_.strpos($root.attr('class'), 'hide-form') !== false)
                  {
                      $root.hide();
                  }

                }

              }  // end success,
              
              ,error: function(jqXHR, textStatus, errorThrown) {
                  
                  alert(form_plugin_settings.connection_error);
                  
                  return false; 
            }
           
           });
           
            return false;
          });

        }
      });
    };
    return $(function() {
      return $('form:not(.no-ajax)').form();
    });
  })(jQuery, this || exports);
}).call(this);



/* End of file form.js. */
/* Location: .public/js/form/form.js */