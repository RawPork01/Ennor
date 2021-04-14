<?php
    $db = new mysqli('localhost', 'gandalf', 'frodo', 'db_mittelerde');

    if ($db->connect_errno) {
        die('Can not connect to DB!');
    }