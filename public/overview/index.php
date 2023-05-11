<?php
include '../../db.php';

// When not logged in you the client gets redirected to the homepage
if (!isset($_SESSION['masterpass']) && !isset($_SESSION['username'])) {
    header('Location: /');
}

// Create a new instance of the DataBase class
$database = new DataBase();

// Check if the user clicked the "Logout" button
if (isset($_POST['logout'])) {
    // Destroy the user's session and redirect to the home page
    session_start();
    session_destroy();
    header("Location: /");
    exit();
}

// Check if the user has submitted a form to change an entry's information
if (isset($_POST['website']) && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['id'])) {
    // Call the changeEntry() method to update the entry in the database
    try {
        $database->changeEntry($database->getUserId(), $_POST['website'], $_POST['username'], $_POST['password'], $_POST['id']);
    } catch (Exception $e) {
        // TODO
    }
    header('Refresh: 0');
}

// Check if the user has clicked the "Move to trash" or "Move out of trash" button
if (isset($_POST['id']) && $_POST['trash'] === 'trash') {
    switch ($_GET['mode']) {
        case 'trash':
            // Call the moveEntryOutOfTrash() method to move the entry out of the trash
            $database->moveEntryOutOfTrash($_POST['id']);
            break;

        default:
            // Call the moveEntryToTrash() method to move the entry to the trash
            $database->moveEntryToTrash($_POST['id']);
            break;
    }
}

// Check if the user has clicked the "Favorite" or "Unfavorite" button
if (isset($_POST['id']) && $_POST['favorite'] === 'favorite') {
    switch ($_GET['mode']) {
        case 'favorite':
            // Call the moveEntryOutOfFavorite() method to remove the entry from
            // the user's favorites
            $database->moveEntryOutOfFavorite($_POST['id']);
            break;

        default:
            // Call the moveEntryToFavorite() method to add the entry to
            // the user's favorites
            $database->moveEntryToFavorite($_POST['id']);
            break;
    }
}

// Call the deleteAfterThirtyDays() method to delete any entries that have been
// in the trash for 30 days
$database->deleteAfterThirtyDays();

// Get the list of entries to display based on the mode specified in the URL
switch ($_GET['mode']) {
    case 'trash':
        try {
            $entries = $database->get_all_entries($database->getUserId(), 'trash');
        } catch (Exception $e) {
            // TODO
        }
        break;

    case 'favorite':
        try {
            $entries = $database->get_all_entries($database->getUserId(), 'favorite');
        } catch (Exception $e) {
            // TODO
        }
        break;

    default:
        try {
            $entries = $database->get_all_entries($database->getUserId());
        } catch (Exception $e) {
            // TODO
        }
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
        <link rel="icon" href="../omegapass.jpg">
    </head>
    <body>
    <div class="welcome-gif-wrapper">
        <img src="../Omegapass.gif" alt="Welcome gif to OmegaPass" class="welcome-gif">
    </div>
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
                    // If the user is currently in "trash" mode, display a message
                    // about when entries will be deleted permanently
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
                        // Loop through the list of entries and display them in a table
                        foreach ($entries as $key => $entry) {
                            echo "<tr class='entries' data-id='{$entry['id']}'>";
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
                <p id="details-error"></p>
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
                        // If the user is currently in "trash" mode, display a button to move
                        // the entry out of the trash; otherwise, display a button to move
                        // the entry to the trash
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
                        // If the user has favorited the entry, display a button to remove it
                        // from their favorites; otherwise, display a button to add it to their favorites
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

