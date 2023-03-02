$(document).ready(function() {

    let showPass = {'password': '', 'show': true};

    $(".entries").click(function(event) {
        const id = event.currentTarget.id.replace('entry-', '');

        $('#clear-details').show();
        $('#show-password').show();

        fetch(`/ajax/ajax.php?getPass=${id}`)
        .then((response) => response.json())
        .then((res) => {
            $('#details-website-link').text(res.website);
            $("#details-website-link").prop("href", res.website);
            $('#details-username').text(res.username);
            $('#details-password').text('*********');
            showPass.password = res.password;
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
        if (showPass.show) {
            $('#details-password').text(showPass.password);
            showPass.show = false;
        } else {
            $('#details-password').text('*********');
            showPass.show = true;
        }
        
    });
});