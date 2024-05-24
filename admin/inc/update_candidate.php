<?php
$candidate_id = $_GET['edit_id'];

//Fetching Data from candidate_details table
$fetchingCandidateData = mysqli_query($conn, "SELECT * FROM candidate_details WHERE id = '" . $candidate_id . "'") or die(mysqli_error($conn));

$data = mysqli_fetch_assoc($fetchingCandidateData);
$id = $data['id'];
$election_id = $data['election_id'];
$candidate_name = $data['candidate_name'];
$candidate_photo = $data['candidate_photo'];
$candidate_details = $data['candidate_details'];

$fetchingElectionName =  mysqli_query($conn, "SELECT * FROM elections WHERE id = '" . $election_id . "'") or die(mysqli_error($conn));
$row = mysqli_fetch_assoc($fetchingElectionName);
$election_name = $row['election_topic'];

?>



<div class="row my-3">
    <div class="col-4 mx-3">
        <h3>Update Candidates</h3>
        <form method="post" enctype="multipart/form-data">
            <div class="form-group my-3">
                <select class="form-control" name="election_id" required>
                    <option value="<?php echo $election_id; ?>"><?php echo $election_name; ?></option>
                    <?php
                    $fetchingElections = mysqli_query($conn, "SELECT * FROM elections") or die(mysqli_error($conn));
                    $isAnyElection = mysqli_num_rows($fetchingElections);
                    if ($isAnyElection > 0) {
                        while ($row = mysqli_fetch_assoc($fetchingElections)) {
                            $election_id = $row['id'];
                            $election_name = $row['election_topic'];
                            $allowed_candidates = $row['no_of_candidates'];

                            //Now checking how many candidates are added
                            $fetchingCandidate = mysqli_query($conn, "SELECT * FROM candidate_details WHERE election_id = '" . $election_id . "'") or die(mysqli_error($conn));
                            $added_candidates = mysqli_num_rows($fetchingCandidate);

                            if ($added_candidates < $allowed_candidates) {
                    ?>
                                <option value="<?php echo $election_id; ?>"><?php echo $election_name; ?></option>


                        <?php
                            }
                        }
                    } else {
                        ?>
                        <option value="">No any election available.</option>
                    <?php
                    }
                    ?>
                </select>
            </div>
            <div class="form-group my-3">
                <input type="text" name="candidate_name" value="<?php echo $candidate_name; ?>" placeholder="Candidate Name" class="form-control" required />
            </div>
            <div class="form-group my-3">
                <input type="file" name="candidate_photo" class="form-control" />

                <input type="hidden" name="old_photo" value="<?php echo $candidate_photo; ?>">


            </div>
            <div class="form-group my-3">
                <input type="text" name="candidate_details" value="<?php echo $candidate_details ?>" placeholder="Candidate Details" class="form-control" required />
            </div>
            <input type="submit" value="Update Candidate" name="updateCandidateBtn" class="btn btn-primary active">
        </form>
    </div>

    <div class="col-7">
        <h3>Candidate Details</h3>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">S.no</th>
                    <th scope="col">Photo</th>
                    <th scope="col">Name</th>
                    <th scope="col">Details</th>
                    <th scope="col">Election</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $fetchingData = mysqli_query($conn, "SELECT * FROM candidate_details") or die(mysqli_error($conn));

                $isAnyCandidate = mysqli_num_rows($fetchingData);

                if ($isAnyCandidate > 0) {
                    $sno = 1;
                    while ($row = mysqli_fetch_assoc($fetchingData)) {

                        $election_id = $row['election_id'];
                        $candidate_id = $row['id'];

                        $fetchingElectionName = mysqli_query($conn, "SELECT * FROM elections WHERE id = '" . $election_id . "'") or die(mysqli_error($conn));
                        $execFetchingElectionNameQuery = mysqli_fetch_assoc($fetchingElectionName);
                        $election_name = $execFetchingElectionNameQuery['election_topic'];

                        $candidate_photo = $row['candidate_photo'];
                ?>
                        <tr>
                            <td><?php echo $sno++; ?></td>
                            <td><img src="<?php echo $candidate_photo; ?>" alt="Photo" style=" width: 60px; height: 60px; border: 1px solid #004aad; border-radius: 100%" /></td>

                            <td><?php echo $row['candidate_name']; ?></td>
                            <td><?php echo $row['candidate_details']; ?></td>
                            <td><?php echo $election_name; ?></td>
                            <td>
                                <a href="index.php?updateCandidatePage=1&edit_id=<?php echo $candidate_id ?>" class="btn btn-sm btn-warning">Edit</a>
                                <a href="#" class="btn btn-sm btn-danger" onclick="DeleteData(<?php echo $candidate_id ?>)">Delete</a>
                            </td>
                        </tr>
                    <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="7">No any candidate yet!</td>
                    </tr>
                <?php
                }
                ?>

            </tbody>
        </table>
    </div>
</div>

<script>
    const DeleteData = (c_id) => {
        let c = confirm("Are you sure you want to delete candidate?");

        if (c == true) {
            location.assign("index.php?addCandidatesPage=1&delete_id=" + c_id);
        }
    }
</script>


<?php

if (isset($_POST['updateCandidateBtn'])) {

    $election_id = mysqli_real_escape_string($conn, $_POST['election_id']);
    $candidate_name = mysqli_real_escape_string($conn, $_POST['candidate_name']);
    $candidate_details = mysqli_real_escape_string($conn, $_POST['candidate_details']);
    $new_photo =  $_FILES['candidate_photo']['name'];
    $old_photo =  $_POST['old_photo'];



    $inserted_by = $_SESSION['username'];
    $inserted_on = date("Y-m-d");

    $targeted_folder = "../assets/images/candidate_photos/";
    $new_photo = $targeted_folder . rand(1111111, 9999999) . "_" . rand(1111111, 9999999) . $_FILES['candidate_photo']['name'];
    $new_photo_tmp_name = $_FILES['candidate_photo']['tmp_name'];
    $new_photo_type = strtolower(pathinfo($new_photo, PATHINFO_EXTENSION));
    $allowed_types = array("jpg", "png", "jpeg");
    $image_size = $_FILES['candidate_photo']['size'];

    echo $new_photo;
    die;

    if ($new_photo != '') {
        $query = "UPDATE candidate_details SET    election_id = '$election_id', 
        candidate_name = '$candidate_name', 
        candidate_details = ' $candidate_details ',
        inserted_by = '$inserted_by',
        inserted_on = '$inserted_on'  
        WHERE id = '$id'";
        $query_run = mysqli_query($conn, $query) or die(mysqli_error($conn));

        echo "<script> location.assign('index.php?addCandidatesPage=1&updated=1'); </script>";
    } else {
        if ($image_size < 2100000) // 2 MB
        {
            if (in_array($new_photo_type, $allowed_types)) {
                if (move_uploaded_file($new_photo_tmp_name, $new_photo)) {
                    //Updating into db

                    $query = "UPDATE candidate_details SET    election_id = '$election_id', 
                        candidate_name = '$candidate_name', 
                        candidate_details = ' $candidate_details ',
                        candidate_photo = '$new_photo',
                        inserted_by = '$inserted_by',
                        inserted_on = '$inserted_on'  
                        WHERE id = '$id'";
                    $query_run = mysqli_query($conn, $query) or die(mysqli_error($conn));

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
    }
}


?>