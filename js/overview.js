$(document).ready(function() {

    let showPass = {'password': '', 'show': true};

    $(".entries").click(function(event) {
        const id = event.currentTarget.id.replace('entry-', '');

        $('#clear-details').show();
        $('#show-password').show();
        $('#details-edit').show();

        fetch(`/ajax/ajax.php?getPass=${id}`)
        .then((response) => response.json())
        .then((res) => {
            $('#details-website-link').text(res.website);
            if (res.website.includes('http://') || res.website.includes('https://')) {
                $("#details-website-link").prop("href", res.website);
            } else {
                $("#details-website-link").prop("href", 'http://' + res.website);
            }
            $('#details-username').text(res.username);
            $('#details-password').text('*********');
            $('#entryId').val(res.id);
            showPass.password = res.password;
            showPass.show = false;
        })
    });

    $('#clear-details').click(function() {
        $('#details-website-link').text('');
        $('#details-username').text('');
        $('#details-password').text('');
        $('#clear-details').hide();
        $('#show-password').hide();
    });

    $('#show-password').click(function() {
        if (!showPass.show) {
            $('#details-password').text(showPass.password);
            showPass.show = true;
        } else {
            $('#details-password').text('*********');
            showPass.show = false;
        }
        
    });

    $('#add-password').click(function() {
        window.location.href = '/add-password/';
    });

    $('#details-edit').click(function() {
        $('#edit-modal').show();
    });

    $('#modal-close').click(function() {
        $('#edit-modal').hide();
    });

});