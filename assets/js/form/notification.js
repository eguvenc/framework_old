
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
    return $.fn.notification = function(type, message, view) {
      var $notification, notification_content, self;
      
      self = this;
      self.find('.notification').remove();
      
      notification_content = '<div class="notification ' + type + '">' + message + '</div>';

      if(view){
          notification_content += '<div style="margin-top:10px;padding:10px;">' + view + '</div>';
      }

      $notification = $('<div>', {
        html:  notification_content,
        click: function() {
            /*
              return $notification.fadeOut('fast', function() {
              return $notification.remove();
              });
            */
        }
      }).hide();

      if (type === 'success') {

        /*
            setTimeout(function() {
              return $notification.fadeOut('slow', function() {
                return $notification.remove();
              });
            }, 3000);
        */
      }
      
      self.parent().prepend($notification.fadeIn('fast'));
      
      return self;
      
    };
  })(jQuery, this);
}).call(this);


/* End of file notification.js. */
/* Location: .public/js/form/notification.js */