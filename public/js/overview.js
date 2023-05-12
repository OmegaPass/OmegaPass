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

        // Define a click event handler for the edit button
        $('#details-edit').click(function() {
            // Show the edit modal
            $('#edit-modal').addClass('open');
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
            $('#edit-modal').removeClass('open');
        });

        // Define a click event handler for the settings button
        $('#settings').click(function() {
            // Show the settings modal
            $('#settings-modal').addClass('open');
        });

        $('#settings-change').click(function() {
            $.ajax({
            type: 'POST',
            url: '/ajax/account-settings.php',
            data: {
                newUsername: $('#newUsername').val(),
                oldPassword: $('#oldPassword').val(),
                newPassword: $('#newPassword').val()
            },
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
        });

        $('#settings-cancel').click(function() {
            $('#settings-modal').removeClass('open');
        });


        // Define a click event handler for the add button
        $('#add-password').click(function() {
            // Show the add modal
            $('#add-modal').addClass('open');
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
            $('#add-modal').removeClass('open');
        });


    $('#sidebar-toggle').on('click', () => {
        const $sidebar = $('.overview-sidebar');
        const $sidebarText = $('.overview-sidebar-text');

        if ($sidebar.width() > 50) {

            $('.overview-sidebar').width(50);
            $sidebarText.hide();
            return;
        }

        $sidebar.width(200);
        $sidebar.css('background', '')
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
