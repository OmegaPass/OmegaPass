$(document).ready(function() {
  
  // Add a click event listener to all elements with the 'toggle-password' class.
  $(".toggle-password").on('click', function () {
    
    // Toggle the 'bi-eye' and 'bi-eye-slash' classes to switch between the 'show password' and 'hide password' icons.
    $(this).toggleClass("bi-eye bi-eye-slash");
    
    // Get the input element immediately preceding the clicked element.
    const input = $(this).prev('input');
    
    // If the input's type attribute is 'password' change the input's type attribute to 
    // 'text' to show the password.
    if (input.attr("type") === "password") {
      input.attr("type", "text");
    } 
    // Otherwise, change the input's type attribute back to 'password' to hide the password.
    else {
      input.attr("type", "password");
    }
  });
});