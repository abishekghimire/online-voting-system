<?php
$election_id = $_GET['edit_id'];



$fetchingelectionData = mysqli_query($conn, "SELECT * FROM elections WHERE id= '" . $election_id . "'") or die(mysqli_error($conn));

$data = mysqli_fetch_assoc($fetchingelectionData);
$id = $data['id'];
$election_topic = $data['election_topic'];
$no_of_candidates = $data['no_of_candidates'];
$starting_date = $data['starting_date'];
$ending_date = $data['ending_date'];

?>

<div class="row my-3">
    <div class="col-4 mx-3">
        <h3>Update Election</h3>
        <form method="post">
            <div class="form-group my-3">
                <input type="text" name="election_topic" value="<?php echo $election_topic; ?>" placeholder="Election Topic" class="form-control" required />
            </div>
            <div class="form-group my-3">
                <input type="number" name="no_of_candidates" value="<?php echo $no_of_candidates; ?>" placeholder="No. of Candidates" class="form-control" required />
            </div>
            <div class="form-group my-3">
                <input type="text" onfocus="this.type='Date'" value="<?php echo $starting_date; ?>" name="starting_date" placeholder="Starting Date" class="form-control" required />
            </div>
            <div class="form-group my-3">
                <input type="text" onfocus="this.type='Date'" value="<?php echo $ending_date; ?>" name="ending_date" placeholder="Ending Date" class="form-control" required />
            </div>
            <input type="submit" value="Update Election" name="updateElectionBtn" class="btn btn-primary active">
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
                                <a href="index.php?updateElectionPage=<?php echo $election_id ?>" class="btn btn-sm btn-warning ">Edit</a>
                                <button class="btn btn-sm btn-danger" onclick="DeleteData(<?php echo $election_id; ?>)">Delete</button>
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

<?php

if (isset($_POST['updateElectionBtn'])) {


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

    //Updating into db
    mysqli_query($conn, "UPDATE elections SET 
        election_topic = '$election_topic' , 
       no_of_candidates = '$no_of_candidates', 
        starting_date = ' $starting_date',
        ending_date = '$ending_date',
       status = '$status',
        inserted_by = '$inserted_by',
        inserted_on = '$inserted_on'
        WHERE id = '$id'") or die(mysqli_error($conn));

?>

    <script>
        location.assign('index.php?addElectionPage=1&updated=1')
    </script>
<?php

}


?>