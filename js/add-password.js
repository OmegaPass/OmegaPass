$(document).ready(function () {

  $("body").on('click', '.toggle-password', function () {
    $(this).toggleClass("bi-eye bi-eye-slash");
    const input = $("#form-password");
    if (input.attr("type") === "password") {
      input.attr("type", "text");
    } else {
      input.attr("type", "password");
    }
  });

  function validateOnKeyPress(input_id) {
    $(`${input_id}`).keypress(function () {
      if ($(`${input_id}`).val.length > 0) {
        $(`${input_id}`).prop('required', false);
      } else {
        $(`${input_id}`).prop('required', true);
      }
    });
  }

  validateOnKeyPress('form-website');
  validateOnKeyPress('form-username');
  validateOnKeyPress('form-password');

  $(document).keypress(function (e) {
    const form = $('#loginform');

    if (e.which == 13) {

      if (form.reportValidity()) {
        form.submit();
      }

    }
  });

  $('#generate').on('click', function () {
    let generateOptions = {
      'generate': true,
      'length': $('#gen-length').val(),
      'digits': $('#gen-digits').is(":checked"),
      'special': $('#gen-special').is(":checked")
    };
    $.ajax({
      type: 'POST',
      url: '/ajax/ajax.php',
      data: generateOptions,
      success: function (response) {
        $('#form-password').val(response).trigger('input');
      },
      error: function (error) {
        console.log('Error posting data: ' + error);
      }

    });

  });

  const strengthWords = ['calc not avaliable', 'very strong', 'strong', 'medium', 'weak', 'very weak'];
  const strengthColors = ['black', 'lightgreen', 'green', '#fcee59', 'orange', 'red', 'darkred'];
  let timeout = null;
  $('#form-password').on('input', function () {

    let passwordInput = $('#form-password').val();

    let validate = {
      'passwordStrength': true,
      'password': passwordInput
    };

    if (timeout !== null) {
      clearTimeout(timeout);
    }

    if (passwordInput === '') {
      $('#progressBar').css('display', 'none');
      return;
    }

    timeout = setTimeout(getStrength, 20);
    
    function getStrength() {
      $.ajax({
        type: 'POST',
        url: '/ajax/ajax.php',
        data: validate,
        success: response => {
          const strengthMapping = strengthWords.indexOf(response);
          const strengthColor = strengthColors[strengthMapping];
    
          if (strengthMapping !== 0) {
            const strengthPercentage = 100 / strengthMapping;
            $('#progressBar').css({width: `${strengthPercentage}%`, backgroundColor: strengthColor});
          } else {
            $('#progressBar').css({width: '100%', backgroundColor: 'darkred'});
          }
    
          $('#progressBar').css('display', 'block');
        },
        error: error => {
          console.log(`Error posting data: ${error}`);
        }
      });
    }

  });

});