
/**
 * form notification.
 *
 * notification message settings.
 * 
 * * Customize it.
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

      $notification = $('<div>', {  // Close notification window when user click.
        html:  notification_content,
        click: function() {     
            /*
              return $notification.fadeOut('fast', function() {
              return $notification.remove();
              });
            */
        }
      }).hide();
      if (type === 'success') {  // Fade out success messages if you want.
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


/* End of file form.notification.js. */
/* Location: .assets/js/form_json/form.notification.js */