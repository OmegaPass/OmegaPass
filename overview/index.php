<?php
include '../db.php';
$entries = get_all_entries(getUserId());

if (isset($_POST['logout'])) {
    session_start();
    session_destroy();
    header("Location: /");
    exit();
}

if (isset($_POST['website']) && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['id'])) {
    changeEntry(getUserId(), $_POST['website'], $_POST['username'], $_POST['password'], $_POST['id']);
    header('Refresh: 0');
}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>OmegaPass</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../css/overview.css">
    </head>
    <body>
        <div class="overview">
            <div class="overview-sidebar">
                <h3>Übersicht</h3>
                <!-- TODO: change to link or change logout to link -->
                <button onclick="window.location.href='/account-settings/'">Account settings</button>
                <form action="" method="post">
                    <button type="submit" name="logout">Ausloggen</button>
                </form>
            </div>

            <div class="overview-passwords">
                <div class="overview-passwords-header">
                    <h3>Passwörter</h4>
                    <button id="add-password">+</button>
                </div>

                <table boarder='1' class="overview-password-table">
                    <tr>
                        <th>Website</th>
                        <th>Username</th>
                    </tr>

                        <?php
                        foreach ($entries as $key => $entry) {
                            echo "<tr id='entry-{$key}' class='entries'>";
                            echo "<td>" . $entry['website'] . "</td>";
                            echo "<td>" . $entry['username'] . "</td>";
                            echo '</tr>';
                        }
                        ?>
                </table>
            </div>

            <section class="overview-details">
                <section class="deatils-heading">
                    <h3>Details</h3>
                    <button id="clear-details" style="display: none">X</button>
                </section>
                <h4>Website</h4>
                <a href="" id="details-website-link"></a>
                <h4>Username</h4>
                <h5 id="details-username"></h5>
                <h4>Password</h4>
                <h5 id="details-password"></h5>
                <button id="show-password" style="display: none">Zeigen</button>
                <button id="details-edit" style="display: none">Bearbeiten</button>
            </section>
        </div>

        <div id="edit-modal" style="display: none">
            <div class="modal">
                <button id="modal-close">X</button>
                <form class="edit-modal-content" method="post" action="">
                    <label>Webseite</label>
                    <input type="text" name="website" required>
                    <label>Benutzername</label>
                    <input type="text" name="username" required>
                    <label>Password</label>
                    <input type="password" name="password" required>
                    <input type="hidden" name="id" id="entryId">
                    <button type="submit">Ändern</button>
                </form>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>

        <script src="../js/overview.js"></script>
    </body>
</html>

