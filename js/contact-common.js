$(function () {
  // init the validator
  // I am using using the CDN. You can download also from http://1000hz.github.io/bootstrap-validator
  $('#contact-form').validator();

  $('#contact-form').on('submit', function (e) {
    // Since we are using form validator. So the validator will prevent form submission in case of any validation issue
    // if the validator does not prevent form submit, it means we are good to go ahead. Hence call php file to process further
    if(!e.isDefaultPrevented()) {
      document.getElementById("send-msg").disabled = true;
      $('.myspinner').css('display','');
      var url = "/contact-common.php";
      $.ajax({
        type: "POST",
        url: url,
        data: $(this).serialize(),
        complete: function(jqXHR, textStatus) {
          document.getElementById("send-msg").disabled = false;
          $('.myspinner').css('display','none');
        },
        error: function(jqXHR, textStatus, errorThrown) {
          alert("Error: " + errorThrown);
        },
        success: function (data) {
          var messageAlert = 'alert-' + data.type;
          var messageText = data.message;
          var alertBox = '<div class="alert ' + messageAlert + ' alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + messageText + '</div>';
          if (messageAlert && messageText) {
            $('#contact-form').find('.messages').html(alertBox);
            $('#contact-form')[0].reset();   // empty the form
            alert(messageText);
            window.history.back();
          }
        }
      });
      return false;
    }
  })
});