<?php

session_start();
require_once("../admin/inc/config.php");

if ($_SESSION['key'] != "VotersKey") {
    echo "<script> location.assign('../admin/logout.php'); </script>";
    die;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Voting System - Voters Panel</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>

    <div class="container-fluid">
        <div class="row bg-blue text-white">
            <div class="col-1">
                <img src="../assets/images/logo.png" alt="" width="80px" />
            </div>
            <div class="col-11 my-auto">
                <h3 class="p-top">ONLINE VOTING SYSTEM - <small>Welcome <?php echo $_SESSION['username']; ?></small></h3>
            </div>
        </div>