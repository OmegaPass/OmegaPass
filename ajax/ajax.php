<?php
include "../db.php";

if (isset($_GET['getPass'])) {
    echo json_encode(get_all_entries(getUserId())[$_GET['getPass']]);
}