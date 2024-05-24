<?php

require_once("../../admin/inc/config.php");

if (isset($_POST['e_id']) and isset($_POST['c_id']) and isset($_POST['v_id'])) {

    $e_id = $_POST['e_id'];
    $c_id = $_POST['c_id'];
    $v_id = $_POST['v_id'];
    $vote_date = date("Y-m-d");
    $vote_time = date("h:i:s a");

    mysqli_query($conn, "INSERT INTO votings(election_id, voters_id, candidate_id, vote_date, vote_time) VALUES('" . $e_id . "', '" . $v_id . "', '" . $c_id . "', '" . $vote_date . "', '" . $vote_time . "') ") or die(mysqli_error($conn));

    echo "Success";
}
