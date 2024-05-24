<?php
$election_id = $_GET['resultPage']
?>

<div class="row my-3">
    <div class="col-12">
        <h1> Election Result</h1>

        <?php
        $fetchingElectionId = mysqli_query($conn, "SELECT * FROM elections WHERE id='" . $election_id . "'") or die(mysqli_error($conn));
        $totalActiveElections = mysqli_num_rows($fetchingElectionId);

        if ($totalActiveElections > 0) {
            while ($data = mysqli_fetch_assoc($fetchingElectionId)) {
                $election_id = $data['id'];
                $election_topic = $data['election_topic'];

        ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th colspan="4" style="background-color: #004aad; color:#fff;">
                                <h5><?php echo $election_topic; ?> Election</h5>
                            </th>
                        </tr>
                        <tr>
                            <th>Photo</th>
                            <th>Candidate Details</th>
                            <th>No. of votes</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $fetchingCandidates = mysqli_query($conn, "SELECT * FROM candidate_details WHERE election_id ='" . $election_id . "'") or die(mysqli_error($conn));


                        while ($candidateData = mysqli_fetch_assoc($fetchingCandidates)) {
                            $candidate_id = $candidateData['id'];

                            $candidate_photo = $candidateData['candidate_photo'];

                            // Fetching Candidate Votes
                            $fetchingVotes = mysqli_query($conn, "SELECT * FROM votings WHERE candidate_id ='" . $candidate_id . "'") or die(mysqli_error($conn));
                            $totalVotes = mysqli_num_rows($fetchingVotes);


                        ?>
                            <tr>
                                <td><img src="<?php echo $candidate_photo; ?>" alt="Photo" style=" width: 60px; height: 60px; border: 1px solid #004aad; border-radius: 100%" /></td>
                                <td><?php echo "<b>" . $candidateData['candidate_name'] . "</b><br>" . $candidateData['candidate_details']; ?></td>
                                <td><?php echo $totalVotes; ?></td>

                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
                <a href="index.php?homepage" class="btn btn-m btn-primary active">Back</a>
            <?php
            }
            ?>

        <?php
        } else {
            echo "No active elections yet!";
        }
        ?>


    </div>
</div>