<?php

if (isset($_GET['added'])) {
?>
    <div class="alert alert-success my-3" role="alert">
        Election Added Successfully!
    </div>
<?php
} else if (isset($_GET['delete_id'])) {
    $d_id = $_GET['delete_id'];
    mysqli_query($conn, "DELETE  FROM elections   WHERE id = '" . $d_id . "'") or die(mysqli_error($conn));

    mysqli_query($conn, "DELETE  FROM candidate_details   WHERE election_id = '" . $d_id . "'") or die(mysqli_error($conn));
?>
    <div class="alert alert-danger my-3" role="alert">
        Election Deleted Successfully!
    </div>
<?php
} else if (isset($_GET['updated'])) {
?>
    <div class="alert alert-success my-3" role="alert">
        Election Updated Successfully!
    </div>
<?php
}
?>


<div class="row my-3">
    <div class="col-4 mx-3">
        <h3>Add New Election</h3>
        <form method="post">
            <div class="form-group my-3">
                <input type="text" name="election_topic" placeholder="Election Topic" class="form-control" required />
            </div>
            <div class="form-group my-3">
                <input type="number" name="no_of_candidates" placeholder="No. of Candidates" class="form-control" required />
            </div>
            <div class="form-group my-3">
                <input type="text" onfocus="this.type='Date'" name="starting_date" placeholder="Starting Date" class="form-control" required />
            </div>
            <div class="form-group my-3">
                <input type="text" onfocus="this.type='Date'" name="ending_date" placeholder="Ending Date" class="form-control" required />
            </div>
            <input type="submit" value="Add Election" name="addElectionBtn" class="btn btn-primary active">
        </form>
    </div>

    <div class="col-7">
        <h3>Upcoming Elections</h3>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">S.no</th>
                    <th scope="col">Election Name</th>
                    <th scope="col">No. of Candidates</th>
                    <th scope="col">Starting On</th>
                    <th scope="col">Ending On</th>
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $fetchingData = mysqli_query($conn, "SELECT * FROM elections") or die(mysqli_error($conn));

                $isAnyElection = mysqli_num_rows($fetchingData);

                if ($isAnyElection > 0) {
                    $sno = 1;
                    while ($row = mysqli_fetch_assoc($fetchingData)) {
                        $election_id = $row['id'];
                ?>
                        <tr>
                            <td><?php echo $sno++; ?></td>
                            <td><?php echo $row['election_topic']; ?></td>
                            <td><?php echo $row['no_of_candidates']; ?></td>
                            <td><?php echo $row['starting_date']; ?></td>
                            <td><?php echo $row['ending_date']; ?></td>
                            <td><?php echo $row['status']; ?></td>
                            <td>
                                <a href="index.php?updateElectionPage=1&edit_id=<?php echo $election_id ?>" class="btn btn-sm btn-warning ">Edit</a>
                                <a href="#" class="btn btn-sm btn-danger" onclick="DeleteData(<?php echo $election_id; ?>)">Delete</a>
                            </td>
                        </tr>
                    <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="7">No any election yet!</td>
                    </tr>
                <?php
                }
                ?>

            </tbody>
        </table>
    </div>
</div>

<script>
    const DeleteData = (e_id) => {
        let c = confirm("Are you sure you want to delete Election?");

        if (c == true) {
            location.assign("index.php?addElectionPage=1&delete_id=" + e_id);
        }
    }
</script>


<?php

if (isset($_POST['addElectionBtn'])) {


    $election_topic = mysqli_real_escape_string($conn, $_POST['election_topic']);
    $no_of_candidates = mysqli_real_escape_string($conn, $_POST['no_of_candidates']);
    $starting_date = mysqli_real_escape_string($conn, $_POST['starting_date']);
    $ending_date = mysqli_real_escape_string($conn, $_POST['ending_date']);

    $inserted_by = $_SESSION['username'];
    $inserted_on = date("Y-m-d");

    $date1 = date_create($inserted_on);
    $date2 = date_create($starting_date);
    $diff = date_diff($date1, $date2);

    if ((int)$diff->format("%R%a") > 0) {
        $status = "InActive";
    } else {
        $status = "Active";
    }

    //Inserting into db
    mysqli_query($conn, "INSERT INTO elections(
        election_topic, 
        no_of_candidates, 
        starting_date,
        ending_date,
        status,
        inserted_by,
        inserted_on) VALUES(
        '" . $election_topic . "',
        '" . $no_of_candidates . "',
        '" . $starting_date . "',
        '" . $ending_date . "', 
        '" . $status . "', 
        '" . $inserted_by . "',
        '" . $inserted_on . "')") or die(mysqli_error($conn));


?>
    <script>
        location.assign("index.php?addElectionPage=1&added=1");
    </script>
<?php
}

?>