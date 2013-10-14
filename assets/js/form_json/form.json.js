
/**
 * Form Json Plugin.
 *
 * @package         obullo
 * @category        json
 * @link
 */
(function() {
  (function($, window) {
    $.fn.form = function(options) {
      var $base, base, config;
      if (options == null) {
        options = {};
      }                                 
      base = this;
      $base = $(base.selector);
      
      config = _.extend({
        error_msg: form_json_config.error_msg,
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
        
        if ( ! $root.data('form_plugin_active')) {
          config.on_load.call(root, $root);
          $root.data('form_plugin_active', true);

          return $root.bind('submit', function() {
            var data;
            config.before.call(root, $root);
            data = $root.find(':visible, [type="hidden"], .send_even_if_hidden').serialize();
            
            if (data.length) {
              data += "&SOCKET_ID=" + window.SOCKET_ID;
            } else {
              data = "SOCKET_ID=" + window.SOCKET_ID;
            }

            $root.find('input[type=submit]', this).attr('disabled', 'disabled');
            $root.find('input[type=submit]', this).addClass('disabled');
            
            $loading = $root.find('.loading_element');
            $loading.after(form_json_config.loading_element);

            $.ajax({
              type: method,
              url: $root.attr('action'),
              dataType: form_json_config.ajax_data_type,
              cache: form_json_config.ajax_cache,
              timeout: form_json_config.ajax_timeout,
              data: data,
              complete: function(){ 
                  $root.find('input[type=submit]', this).removeClass('disabled');
              },
              success: function(r)
              { 
                $root.parent().find('.notification').remove();
                $root.find('.input-error').remove();

                if ( ! r.messages.success) {

                  $('.loading').hide();

                  $root.find('input[type=submit]', this).removeAttr('disabled');
                  $root.find('input[type=submit]', this).removeClass('disabled');

                  config.error.call(root, r, $root);

                  if ($root.data('form.error')) {
                    $root.data('form.error').call(root, r, $root);
                  }
                  if(_.strpos($root.attr('class'), 'no-top-msg') === false){
                      $root.notification('error', config.error_msg);
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
                   
                   if ( ! $input.prev('[class=input-error]').length){
                        $input.before("<div class='input-error' tabindex='"+ i +"'>" + value + "</div>");
                        $root.find("[class=input-error]:visible:eq(0)").attr("tabindex", i).focus();
                   }

                  if (r.messages.errorString) {
                    $('.notification.notification-error').attr("tabindex", '0').focus();
                  } 

                  });

                } else {

                  $('.loading').hide();

                  $root.find('input[type=submit]', this).removeAttr('disabled');
                  $root.find('input[type=submit]', this).removeClass('disabled');

                  config.success.call(root, r, $root);
                  
                  if ($root.data('form.success')) {
                    $root.data('form.success').call(root, r, $root);
                  }
                  if (r.messages.errorString){
                    $root.notification('success', r.messages.errorString);
                    $('.notification.notification-success').attr("tabindex", '0').focus();
                  } 
                  else if(config.success_msg) {
                     $root.notification('success', config.success_msg);
                     $('.notification.notification-success').attr("tabindex", '0').focus();
                  }
                  if(_.strpos($root.attr('class'), 'hide-form') !== false){
                      $root.hide();
                  }
                }
              }  // end success,
              ,error: function(jqXHR, textStatus, errorThrown) {
                  alert(form_json_config.connection_error);
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


/* End of file form.json.js. */
/* Location: .assets/js/form_json/form.json.js */