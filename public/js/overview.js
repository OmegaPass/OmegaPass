// Wait until the document is ready before running the code
$(document).ready(function() {

    showLoadingScreen();

    // Object to store the current password and whether it is being displayed or hidden
    let showPass = {'password': '', 'show': true};

    // When a password entry is clicked, fetch the password details and display them
    $(".entries").click(function() {
        // Get the ID of the clicked entry
        const id = $(this).attr('data-id');

        // Show the password details and buttons
        $('#clear-details').show();
        $('#copy-to-clipboard').show();
        $('#show-password').show();
        $('#details-edit').show();
        $('#trash-form').show();
        $('#favorite-form').show();

        // Build the AJAX URL based on the entry ID and the current URL parameters
        let currentUrl = new URL(window.location);
        let currentUrlParams = new URLSearchParams(currentUrl.search);
        let mode = null;
        let ajaxUrl = `/ajax/ajax.php?getPass=${id}`;

        if (currentUrlParams.has('mode')) {
            mode = '&mode=' + currentUrlParams.get('mode');
            ajaxUrl =  ajaxUrl + mode;
        }

        // Fetch the password details from the server using AJAX
        fetch(ajaxUrl)
            .then((response) => {
                if (response.ok) {
                    return response.json();
                }
                return Promise.reject()
            })
            .then((res) => {
                // Display the retrieved password details in the appropriate elements
                $('#details-website-link').text(res.website);
                if (res.website.includes('http://') || res.website.includes('https://')) {
                    $("#details-website-link").prop("href", res.website);
                } else {
                    $("#details-website-link").prop("href", 'http://' + res.website);
                }
                $('#details-username').text(res.username);
                $('#details-password').text('*********');
                $('input.entryId').val(res.id);

                // Store the retrieved password in a variable to enable showing/hiding the password
                showPass.password = res.password;
                showPass.show = false;

                // Logic for 'Copy to Clipboard' button
                // .off('click') method removes any previous click handlers to prevent multiple handlers from being attached each time an entry is clicked.
                $('#copy-to-clipboard').off('click').on('click', function() {
                    navigator.clipboard.writeText(showPass.password)
                        .then(() => {
                            console.log('Password copied to clipboard');
                        })
                        .catch((error) => {
                            console.error('Could not copy password: ', error);
                        });
                });

            })
            .catch(() => {
                $('#details-error').text('Error during details gathering');
            });
    });

    // Function that handles click events on the "Clear" button
    $('#clear-details').click(function() {
        // Hide the password details and buttons
        $('#details-website-link').text('');
        $('#details-username').text('');
        $('#details-password').text('');
        $('#clear-details').hide();
        $('#show-password').hide();
        $('#details-edit').hide();
        $('#trash-form').hide();
        $('#favorite-form').hide();
        $('#details-error').text('');
    });

    // Function that handles click events on the "Show Password" button
    $('#show-password').click(function() {
        // Toggle between showing and hiding the password
        if (!showPass.show) {
            $('#details-password').text(showPass.password);
            showPass.show = true;
        } else {
            $('#details-password').text('*********');
            showPass.show = false;
        }

    });

    // * Edit entry modal
        // Define a click event handler for the edit button
        $('#details-edit').click(function() {
            // Show the edit modal
            document.getElementById('edit-modal').showModal();
            $('#edit_website').val($('#details-website-link').text());
            $('#edit_username').val($('#details-username').text());
        });

        // Define a click event handler for the edit change button
        $('#edit-change').click(function() {
            // Perform AJAX request
            $.ajax({
            type: 'POST',
            url: '/ajax/edit-password.php',
            data: {
                edit_id: $('#edit_id').val(),
                edit_website: $('#edit_website').val(),
                edit_username: $('#edit_username').val(),
                edit_password: $('#edit_password').val()
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                // Redirect to the specified URL
                window.location.href = response.redirect;
                } else {
                    if (response.redirect) {
                        window.location.href = response.redirect;
                    } else {
                        // Display the error message in the edit modal
                        $('#edit-errorMsg').text(response.error);
                    }
                }
            },
            error: function(error) {
                // Log the error
                console.log('Ajax request error:', error);
            }
            });
        });

        // Define a click event handler for the edit cancel button
        $('#edit-cancel').click(function() {
            // Hide the edit modal
            document.getElementById('edit-modal').close();
        });


    // * Account settings modal
        // Define a click event handler for the settings button
        $('#settings').click(function() {
            // Show the settings modal
            document.getElementById('settings-modal').showModal();
        });

        $('#settings-change').click(function() {
            if ($('#newUsername').val() !== "")
            {
                $.ajax({
                type: 'POST',
                url: '/ajax/account-settings.php',
                data: {
                    newUsername: $('#newUsername').val()
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                    window.location.href = response.redirect;
                    } else {
                    if (response.redirect) {
                        window.location.href = response.redirect;
                    } else {
                        $('#settings-errorMsg').text(response.error);
                    }
                    }
                },
                error: function(error) {
                    console.error(error);
                }
                });
            }
            else if ($('#oldPassword, #newPassword').val() !== "")
            {
                $.ajax({
                    type: 'POST',
                    url: '/ajax/account-settings.php',
                    data: {
                        oldPassword: $('#oldPassword').val(),
                        newPassword: $('#newPassword').val()
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                        window.location.href = response.redirect;
                        } else {
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        } else {
                            $('#settings-errorMsg').text(response.error);
                        }
                        }
                    },
                    error: function(error) {
                        console.error(error);
                    }
                    });
            }
        });

        $('#settings-cancel').click(function() {
            document.getElementById('settings-modal').close();
        });

        $('.settings-tabs span').click(function() {
            const $changeUsername = $('.change-username');
            const $changeMasterPass = $('.change-masterpass');

            $('span.active').removeClass('active');
            $(this).addClass('active');

            if ($(this)[0].classList.contains('settings-username')) {
                $changeMasterPass.hide();
                $('#oldPassword').val("");
                $('#newPassword').val("");
                $changeUsername.fadeIn(100);
                return;
            }

            $changeUsername.hide();
            $('#newUsername').val("");
            $changeMasterPass.fadeIn(100);
        });


    // * Add entry modal
        // Define a click event handler for the add button
        $('#add-password').click(function() {
            // Show the add modal
            document.getElementById('add-modal').showModal();
        });

        $('#add-change').click(function() {
            $.ajax({
                type: 'POST',
                url: '/ajax/add-password.php',
                data: {
                    website: $('#add_website').val(),
                    username: $('#add_username').val(),
                    password: $('#add_password').val()
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        window.location.href = response.redirect;
                    } else {
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        } else {
                            $('#add-errorMsg').text(response.error);
                        }
                    }
                },
                error: function(error) {
                    console.error(error);
                }
            });
        });

        $('#add-cancel').click(function() {
            document.getElementById('add-modal').close();
        });

        // When the toggle password button is clicked, toggle the password visibility
        $("dialog").on('click', '.toggle-password', function () {
            $(this).toggleClass("bi-eye bi-eye-slash");
            var input;
            if ($("#edit_password").val() !== "") {
              input = $("#edit_password");
            } else if ($("#add_password").val() !== "") {
              input = $("#add_password");
            }

            if (input.attr("type") === "password") {
              input.attr("type", "text");
            } else {
              input.attr("type", "password");
            }
          });

        $('#gen-password').click(() => {
            const $genField = $('.gen-field');

            if ($genField.css('display') === 'none') {
                $genField.fadeIn(100);
                return;
            }

            $genField.fadeOut(100);
        });

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
              url: '/ajax/add-password.php',
              data: generateOptions,
              dataType: 'json',
              success: function (response) {
                // Set the generated password as the value of the password input field
                if (response.success)
                { 
                    $('#add_password').val(response.password).trigger('input');
                }
              },
              error: function (error) {
                console.log('Error posting data: ' + error);
              }
            });
          });

        const strengthWords = ['calc not avaliable', 'very strong', 'strong', 'medium', 'weak', 'very weak'];
        const strengthColors = ['black', 'lightgreen', 'green', '#fcee59', 'orange', 'red', 'darkred'];
        let timeout = null;
        // When the password input field is changed, update the password strength meter
        $('#add_password').on('input', function () {

            // Get the value of the password input field
            let passwordInput = $('#add_password').val();
        
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
            // Call the check_password_strength function and get the password strength
            let response = check_password_strength(passwordInput);
        
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
            }
        });
  

    // add eventListener to dialog elements, when clicked outside of dialog element and its open then close it
    document.querySelectorAll('dialog').forEach((element) => {
        element.addEventListener("click", e => {
            const dialogDimensions = element.getBoundingClientRect()
            if (
                e.clientX < dialogDimensions.left ||
                e.clientX > dialogDimensions.right ||
                e.clientY < dialogDimensions.top ||
                e.clientY > dialogDimensions.bottom
                && element.open
            ) {
                element.close()
            }
        })
    })


    $('#sidebar-toggle').on('click', function(){
        const $sidebar = $('.overview-sidebar');
        const $sidebarText = $('.overview-sidebar-text');

        if ($sidebar.width() > 50) {

            $('.overview-sidebar').width(50);
            $(this).find(':first-child')
                .removeClass('bi-arrow-bar-left')
                .addClass('bi-arrow-bar-right');
            $sidebarText.hide();
            return;
        }

        $sidebar
            .width(200)
            .css('background', '');
        $(this).find(':first-child')
            .removeClass('bi-arrow-bar-right')
            .addClass('bi-arrow-bar-left');
        $sidebarText.show();
    });

    function showLoadingScreen() {
        if (sessionStorage.getItem('visited') === null) {
            let fadeOutTime = 200;
            const $welcome = $('.welcome-gif-wrapper');

            $welcome.css('visibility', 'visible');
            $welcome.fadeIn(fadeOutTime);
            setTimeout(() => {
                $welcome.fadeOut(fadeOutTime);

                setTimeout(() => $welcome.hide(), fadeOutTime);
            }, 2210)

            sessionStorage.setItem('visited', 'true');
        }
    }
});


function check_password_strength(password) {
    // Ensure that zxcvbn is loaded
    if (typeof zxcvbn === 'undefined') {
        console.error('zxcvbn library not loaded');
        return;
    }
    
    var strength = zxcvbn(password).score;
    
    // Define a message for each password strength level
    var strengthMessage = [
        'very weak',
        'weak',
        'medium',
        'strong',
        'very strong'
    ];
    
    // Return the appropriate strength message based on the score
    return strengthMessage[strength] || 'calc not available';
}