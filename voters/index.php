<?php

require_once("inc/header.php");
require_once("inc/navigation.php");
?>
<div class="row my-3">
    <div class="col-12">
        <h1>Voters Panel</h1>

        <?php
        $fetchingActiveElections = mysqli_query($conn, "SELECT * FROM elections WHERE status='Active'") or die(mysqli_error($conn));
        $totalActiveElections = mysqli_num_rows($fetchingActiveElections);

        if ($totalActiveElections > 0) {
            while ($data = mysqli_fetch_assoc($fetchingActiveElections)) {
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
                            <!-- <th>No. of votes</th> -->
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $fetchingCandidates = mysqli_query($conn, "SELECT * FROM candidate_details WHERE election_id ='" . $election_id . "'") or die(mysqli_error($conn));


                        while ($candidateData = mysqli_fetch_assoc($fetchingCandidates)) {
                            $candidate_id = $candidateData['id'];

                            $candidate_photo = $candidateData['candidate_photo'];

                            // Fetching Candidate Votes
                            // $fetchingVotes = mysqli_query($conn, "SELECT * FROM votings WHERE candidate_id ='" . $candidate_id . "'") or die(mysqli_error($conn));
                            // $totalVotes = mysqli_num_rows($fetchingVotes);


                        ?>
                            <tr>
                                <td><img src="<?php echo $candidate_photo; ?>" alt="Photo" style=" width: 60px; height: 60px; border: 1px solid #004aad; border-radius: 100%" /></td>
                                <td><?php echo "<b>" . $candidateData['candidate_name'] . "</b><br>" . $candidateData['candidate_details']; ?></td>
                                <!-- <td><?php echo $totalVotes; ?></td> -->
                                <td>

                                    <?php
                                    $checkIfVoteCasted = mysqli_query($conn, "SELECT * FROM votings WHERE voters_id = '" . $_SESSION['user_id'] . "' AND election_id = '" . $election_id . "'") or die(mysqli_error($conn));

                                    $isVoteCasted = mysqli_num_rows($checkIfVoteCasted);

                                    if ($isVoteCasted > 0) {

                                        $voteCastedData = mysqli_fetch_assoc($checkIfVoteCasted);

                                        $voteCastedToCandidate = $voteCastedData['candidate_id'];

                                        if ($voteCastedToCandidate == $candidate_id) {
                                    ?>
                                            <b style="color: #004aad;">Voted</b>
                                        <?php
                                        }
                                    } else {
                                        ?>
                                        <button class="btn btn-m btn-primary active" onclick="CastVote(<?php echo $election_id; ?> , <?php echo $candidate_id; ?> ,<?php echo $_SESSION['user_id']; ?>)">Vote</button>
                                    <?php
                                    }
                                    ?>


                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
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

<script>
    const CastVote = (election_id, candidate_id, voter_id) => {
        $.ajax({
            type: "POST",
            url: "inc/ajaxCalls.php",
            data: "e_id=" + election_id + "&c_id=" + candidate_id + "&v_id=" + voter_id,
            success: function(response) {
                if (response == "Success") {
                    location.assign("index.php?voteCasted=1");
                } else {
                    location.assign("index.php?voteNotCasted=1");
                }
            }
        });
    }
</script>
<?php
require_once("inc/footer.php");
?>