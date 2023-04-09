<?php
include '../db.php';

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

if (isset($_POST['id']) && $_POST['trash'] === 'trash') {
    switch ($_GET['mode']) {
        case 'trash':
            moveEntryOutOfTrash($_POST['id']);
            break;

        default:
            moveEntryToTrash($_POST['id']);
            break;
    }
}

switch ($_GET['mode']) {
    case 'trash':
        $entries = get_all_entries(getUserId(), 'trash');
        break;

    default:
        $entries = get_all_entries(getUserId());
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
                <a href="/overview/" target="_self">
                    <h3>Übersicht</h3>
                </a>
                <button onclick="window.location.href='/overview/?mode=trash'">Trash</button>
                <form action="" method="post">
                    <button type="submit" name="logout">Ausloggen</button>
                </form>
            </div>

            <div class="overview-passwords">
                <div class="overview-passwords-header">
                    <h3>Passwörter</h3>
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
                <form id="trash-form" method="post" style="display: none">
                    <input type="hidden" name="id" class="entryId">
                    <input type="hidden" name="trash" value="trash">
                    <button type="submit">
                        <?php
                        if ($_GET['mode'] === 'trash') {
                            echo 'Move out of trash';
                        } else {
                            echo 'Move to trash';
                        }
                        ?>
                    </button>
                </form>
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
                    <input type="hidden" name="id" class="entryId">
                    <button type="submit">Ändern</button>
                </form>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>

        <script src="../js/overview.js"></script>
    </body>
</html>

