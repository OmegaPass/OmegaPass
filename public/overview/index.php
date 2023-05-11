<?php
// Import required dependencies
include_once '../../config.php';
include '../../db.php';

// Regenerate session ID after logout
if (isset($_POST['logout']) || (!isset($_SESSION['masterpass']) && !isset($_SESSION['username']))) {
    session_regenerate_id(true);
}

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
if (isset($_POST['edit_website']) && isset($_POST['edit_username']) && isset($_POST['edit_password']) && isset($_POST['edit_id'])) {
    // Call the changeEntry() method to update the entry in the database
    try {
        $database->changeEntry($database->getUserId(), $_POST['edit_website'], $_POST['edit_username'], $_POST['edit_password'], $_POST['edit_id']);
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
                <button id="settings">Account settings</button>
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

        <dialog id="edit-modal">
            <button class="modal-close">X</button>
            <form class="modal-content" method="post" action="">
                <label>Website</label>
                <input type="text" name="edit_website" required>
                <label>Username</label>
                <input type="text" name="edit_username" required>
                <label>Password</label>
                <input type="password" name="edit_password" required>
                <input type="hidden" name="edit_id" class="entryId">
                <button type="submit">Change</button>
            </form>
        </dialog>


        <dialog id="add-modal">
            <button class="modal-close">X</button>
            <form class="modal-content add-password-card" id="add-password-form" action="" method="post">
                <label>Website</label>
                <input type="text" placeholder="Website" required id="form-website" name="add_website">
                <label>Username</label>
                <input type="text" placeholder="Username" required id="form-username" name="add_username">
                <label>Password</label>
                <div class="form-password-field">
                    <div>
                        <input type="password" placeholder="Password" required id="form-password" name="add_password">
                        <span toggle="#password-field" class="toggle-password bi-eye"></span>
                    </div>
                    <div id="progress">
                        <div id="progressBar"></div>
                    </div>
                    <p>Generate a password</p>
                    <div class="gen-field">
                        <div>
                            <input type="number" id="gen-length">
                            <label>Number of characters</label>
                            <input type="checkbox" id="gen-digits">
                            <label>Numbers</label>
                            <input type="checkbox" id="gen-special">
                            <label>Special characters</label>
                        </div>
                        <button id="generate" type="button">Generate and fill</button>
                    </div>
                </div>
                <button type="submit" class="modal-submit">Save</button>
            </form>
        </dialog>


        <dialog id="settings-modal">
            <button class="modal-close">X</button>
            <form class="modal-content" method="post" action="">

            </form>
            <section class="change-username">
                <h2>Change your account username</h2>
                <form class="change-username-form" method="post" action="">
                    <label>New username</label>
                    <input type="text" name="newUsername" required>
                    <button type="submit">Change</button>
                </form>
            </section>
            <section class="change-masterpass">
                <h2>Change your account password</h2>
                <?php echo "<p class='errorMsg'>$errorMsg</p>"?>
                <form class="change-masterpass-form" method="post" action="">
                    <label>Old password</label>
                    <div class="change-masterpass-form-input">
                        <input type="password" name="oldPassword" required class="password-input">
                        <span toggle="#password-field" class="toggle-password bi-eye"></span>
                    </div>
                    <label>New password</label>
                    <div class="change-masterpass-form-input">
                        <input type="password" name="newPassword" required class="password-input">
                        <span toggle="#password-field" class="toggle-password bi-eye"></span>
                    </div>
                    <button type="submit">Change</button>
                </form>
            </section>
        </dialog>

        <footer>
            <a href="/imprint/">Imprint</a>
            <a href="/privacy-policy/">Privacy policy</a>
        </footer>
        <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>

        <script src="../js/overview.js"></script>
    </body>
</html>

