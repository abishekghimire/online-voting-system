<?php

require_once("inc/header.php");
require_once("inc/navigation.php");


if (isset($_GET['homepage'])) {

    require_once("inc/homepage.php");
} else if (isset($_GET['addElectionPage'])) {

    require_once("inc/add_election.php");
} else if (isset($_GET['addCandidatesPage'])) {

    require_once("inc/add_candidates.php");
} else if (isset($_GET['resultPage'])) {
    require_once("inc/results.php");
} else if (isset($_GET['updateElectionPage'])) {
    require_once("inc/update_election.php");
} else if (isset($_GET['updateCandidatePage'])) {
    require_once("inc/update_candidate.php");
}

?>

<?php

require_once("inc/footer.php");

?>