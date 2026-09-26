<?php
session_start();

require_once("../model/database.php");
require_once("../model/trandau.php");
require_once("../model/giaidau.php");
require_once("../model/doibong.php");

$td = new TRANDAU();
$gd = new GIAIDAU();
$db = new DOIBONG();

if (isset($_REQUEST["action"])) {
    $action = $_REQUEST["action"];
} else {
    $action = "null";
}

switch ($action) {
    case "null":
        $giaidau = $gd->laydanhsachgiaidau();
        $dsdoibong = $db->laydanhsachdoibong();
        include("main.php");
        break;

    case "laydoibong":
        if (isset($_GET["giaidauid"]) && $_GET["giaidauid"] > 0) {
            $giaidauid = $_GET["giaidauid"];
            $dsdoibong = $db->laydoibongtheogiaidau($giaidauid);
        } else {
            $dsdoibong = $db->laydanhsachdoibong();
        }

        header("Content-Type: application/json; charset=utf-8");
        echo json_encode($dsdoibong);
        exit();

    case "timkiem":

        break;

    default:
        break;
}
?>