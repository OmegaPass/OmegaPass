<?php
include '../db.php';
$entries = get_all_entries(getUserId());
?>
<link rel="stylesheet" type="text/css" href="./style.css">
<div class="overview">
    <div class="overview-sidebar">
        <h3>Übersicht</h3>
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
    </section>
</div>

<script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>

<script src="overview.js"></script>