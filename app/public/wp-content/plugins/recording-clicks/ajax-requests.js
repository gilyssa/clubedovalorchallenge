jQuery(document).on('click', '#click-button', function() {
  var $this = jQuery(this); 

  jQuery.ajax({
      type: 'POST',
      url: Ajax.ajaxurl,
      data: {
          action: 'addingClick',
      },
      success: function() {
          var acknowledgement = '<p id="acknowledgement">Obrigada por contribuir!</p>';
          $this.after(acknowledgement); 
          
          setTimeout(function() {
            $this.siblings('#acknowledgement').remove();
          }, 30000); 
      }
  });
});
