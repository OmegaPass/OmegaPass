// Wait until the document is ready before running the code
$(document).ready(function() {

    // Object to store the current password and whether it is being displayed or hidden
    let showPass = {'password': '', 'show': true};

    // When a password entry is clicked, fetch the password details and display them
    $(".entries").click(function(event) {
        // Get the ID of the clicked entry
        const id = event.currentTarget.id.replace('entry-', '');

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
        .then((response) => response.json())
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

    // Redirect to the "add password" page when the add password button is clicked
    $('#add-password').click(function() {
        window.location.href = '/add-password/';
    });

    // Show the edit modal when the edit button is clicked
    $('#details-edit').click(function() {
        $('#edit-modal').show();
    });

    // Hide the edit modal when the close button is clicked
    $('#modal-close').click(function() {
        $('#edit-modal').hide();
    });

});
