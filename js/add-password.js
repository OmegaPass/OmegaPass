// When the document is ready, execute the following code
$(document).ready(function () {

  // When the toggle password button is clicked, toggle the password visibility
  $("body").on('click', '.toggle-password', function () {
    $(this).toggleClass("bi-eye bi-eye-slash");
    const input = $("#form-password");
    if (input.attr("type") === "password") {
      input.attr("type", "text");
    } else {
      input.attr("type", "password");
    }
  });

  // Validate input fields on keypress
  function validateOnKeyPress(input_id) {
    $(`${input_id}`).keypress(function () {
      if ($(`${input_id}`).val.length > 0) {
        $(`${input_id}`).prop('required', false);
      } else {
        $(`${input_id}`).prop('required', true);
      }
    });
  }

  // Validate website, username, and password inputs on keypress
  validateOnKeyPress('form-website');
  validateOnKeyPress('form-username');
  validateOnKeyPress('form-password');

  // Submit the form when the user presses the Enter key
  $(document).keypress(function (e) {
    const form = $('#loginform');

    if (e.which == 13) {

      if (form.reportValidity()) {
        form.submit();
      }

    }
  });

  // When the password generator button is clicked, generate a new password
  $('#generate').on('click', function () {
    let generateOptions = {
      'generate': true,
      'length': $('#gen-length').val(),
      'digits': $('#gen-digits').is(":checked"),
      'special': $('#gen-special').is(":checked")
    };

    // Send an AJAX request to the server to generate a new password
    $.ajax({
      type: 'POST',
      url: '/ajax/ajax.php',
      data: generateOptions,
      success: function (response) {
        // Set the generated password as the value of the password input field
        $('#form-password').val(response).trigger('input');
      },
      error: function (error) {
        console.log('Error posting data: ' + error);
      }

    });

  });

  // Define arrays for password strength words and colors
  const strengthWords = ['calc not avaliable', 'very strong', 'strong', 'medium', 'weak', 'very weak'];
  const strengthColors = ['black', 'lightgreen', 'green', '#fcee59', 'orange', 'red', 'darkred'];
  let timeout = null;

  // When the password input field is changed, update the password strength meter
  $('#form-password').on('input', function () {

    // Get the value of the password input field
    let passwordInput = $('#form-password').val();

    // Define an object to send in an AJAX request to the server
    let validate = {
      'passwordStrength': true,
      'password': passwordInput
    };

    // Clear the previous timeout and set a new one
    if (timeout !== null) {
      clearTimeout(timeout);
    }

    // If the password is empty, hide the password strength meter and exit
    if (passwordInput === '') {
      $('#progressBar').css('display', 'none');
      return;
    }

    // Define a timeout function to get the password strength from the server
    timeout = setTimeout(getStrength, 20);

    function getStrength() {
      // Send an AJAX request to the server to get the password strength
      $.ajax({
        type: 'POST',
        url: '/ajax/ajax.php',
        data: validate,
        success: response => {
          // Map the password strength to the corresponding word and color
          const strengthMapping = strengthWords.indexOf(response);
          const strengthColor = strengthColors[strengthMapping];

          // Update the password strength meter
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