<?php
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sId = $_POST['sId'];

    require 'dbPDO.php';
    $statement = $pdo->prepare("DELETE FROM tbl_sammlerobjekt WHERE s_id = ?");
    $statement->execute(array($sId));

    $hostname = $_SERVER['HTTP_HOST'];
    $path = dirname($_SERVER['PHP_SELF']);

    header('Location: http://'.$hostname.($path == '/' ? '' : $path).'/myObjects.php');
}

