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

if (isset($_POST['id']) && $_POST['favorite'] === 'favorite') {
    switch ($_GET['mode']) {
        case 'favorite':
            moveEntryOutOfFavorite($_POST['id']);
            break;

        default:
            moveEntryToFavorite($_POST['id']);
            break;
    }
}

deleteAfterThirtyDays();

switch ($_GET['mode']) {
    case 'trash':
        $entries = get_all_entries(getUserId(), 'trash');
        break;

    case 'favorite':
        $entries = get_all_entries(getUserId(), 'favorite');
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
                    <h3>Overview</h3>
                </a>
                <button onclick="window.location.href='/overview/?mode=favorite'">Favorites</button>
                <button onclick="window.location.href='/overview/?mode=trash'">Trash</button>
                <button onclick="window.location.href='/account-settings/'">Account settings</button>
                <form action="" method="post">
                    <button type="submit" name="logout">Logout</button>
                </form>
            </div>

            <div class="overview-passwords">
                <div class="overview-passwords-header">
                    <h3>Passwords</h3>
                    <button id="add-password">+</button>
                </div>

                <div class="overview-passwords-subheader">
                    <?php
                    if ($_GET['mode'] === 'trash') {
                        echo '
                            <p class="trash-delete-info">A password will be deleted after 30 days in the trash!</p>
                        ';
                    }
                    ?>
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
                <button id="show-password" style="display: none">Show</button>
                <button id="details-edit" style="display: none">Edit</button>
                <form id="trash-form" method="post" style="display: none">
                    <input type="hidden" name="id" class="entryId">
                    <button type="submit" name="trash" value="trash">
                        <?php
                        if ($_GET['mode'] === 'trash') {
                            echo 'Move out of trash';
                        } else {
                            echo 'Move to trash';
                        }
                        ?>
                    </button>
                </form>
                <form id="favorite-form" method="post" action="" style="display: none">
                    <input type="hidden" name="id" class="entryId">
                    <button type="submit" name="favorite" value="favorite">
                        <?php
                        if ($_GET['mode'] === 'favorite') {
                            echo 'Unfavorite';
                        } else {
                            echo 'Favorite';
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
                    <label>Website</label>
                    <input type="text" name="website" required>
                    <label>Username</label>
                    <input type="text" name="username" required>
                    <label>Password</label>
                    <input type="password" name="password" required>
                    <input type="hidden" name="id" class="entryId">
                    <button type="submit">Change</button>
                </form>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>

        <script src="../js/overview.js"></script>
    </body>
</html>

