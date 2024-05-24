<?php

$fetchingData = mysqli_query($conn, "SELECT * FROM users WHERE contact_no= '" . $su_p_number . "'") or die(mysqli_error($conn));



if (mysqli_num_rows($fetchingData) > 0) {
    $data = mysqli_fetch_assoc($fetchingData);

    if (mysqli_num_rows($fetchingData) > 0) {
        $data = mysqli_fetch_assoc($fetchingData);

        if ($su_p_number != $data['contact_no']) {
?>
            <script>
                location.assign("index.php?sign-up=1&registered=1")
            </script>
        <?php
        } else {
        ?>
            <script>
                location.assign("index.php?sign-up=1&invalid=1")
            </script>
<?php
        }
    }
}
