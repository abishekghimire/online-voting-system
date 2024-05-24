<?php

if (isset($_POST['updateCandidateBtn'])) {
    $election_id = mysqli_real_escape_string($conn, $_POST['election_id']);
    $candidate_name = mysqli_real_escape_string($conn, $_POST['candidate_name']);
    $candidate_details = mysqli_real_escape_string($conn, $_POST['candidate_details']);


    $inserted_by = $_SESSION['username'];
    $inserted_on = date("Y-m-d");

    //For image
    $targeted_folder = "../assets/images/candidate_photos/";
    $candidate_photo = $targeted_folder . rand(1111111, 9999999) . "_" . rand(1111111, 9999999) . $_FILES['candidate_photo']['name'];
    $candidate_photo_tmp_name = $_FILES['candidate_photo']['tmp_name'];
    $candidate_photo_type = strtolower(pathinfo($candidate_photo, PATHINFO_EXTENSION));
    $allowed_types = array("jpg", "png", "jpeg");
    $image_size = $_FILES['candidate_photo']['size'];


    if ($image_size < 2100000) // 2 MB
    {
        if (in_array($candidate_photo_type, $allowed_types)) {
            if (move_uploaded_file($candidate_photo_tmp_name, $candidate_photo)) {
                //Updating into db
                mysqli_query($conn, "UPDATE candidate_details SET
            election_id = '$election_id', 
            candidate_name = '$candidate_name', 
            candidate_details = ' $candidate_details ',
            candidate_photo = '$candidate_photo',
            inserted_by = '$inserted_by',
            inserted_on = '$inserted_on'
            WHERE id = '$candidate_id'") or die(mysqli_error($conn));

                echo "<script> location.assign('index.php?addCandidatesPage=1&updated=1'); </script>";
            } else {
                echo "<script> location.assign('index.php?addCandidatesPage=1&failed=1'); </script>";
            }
        } else {
            echo "<script> location.assign('index.php?addCandidatesPage=1&invalidFile=1'); </script>";
        }
    } else {
        echo "<script> location.assign('index.php?addCandidatesPage=1&largeFile=1'); </script>";
    }
?>
<?php
}
?>