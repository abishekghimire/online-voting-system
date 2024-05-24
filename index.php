<?php

require_once("admin/inc/config.php");

$fetchingElections = mysqli_query($conn, "SELECT * FROM elections") or die(mysqli_error($conn));

while ($data = mysqli_fetch_assoc($fetchingElections)) {
    $starting_date = $data['starting_date'];
    $ending_date = $data['ending_date'];
    $current_date = date("Y-m-d");
    $election_id = $data['id'];
    $status = $data['status'];

    if ($status == "Active") {


        $date1 = date_create($current_date);
        $date2 = date_create($ending_date);
        $diff = date_diff($date1, $date2);

        if ((int)$diff->format("%R%a") < 0) {

            //Update
            mysqli_query($conn, "UPDATE elections SET status = 'Expired' WHERE id = '" . $election_id . "'") or die(mysqli_error($conn));
        }
    } else if ($status == "InActive") {
        $date1 = date_create($current_date);
        $date2 = date_create($starting_date);
        $diff = date_diff($date1, $date2);

        if ((int)$diff->format("%R%a") < 0) {


            //Update
            mysqli_query($conn, "UPDATE elections SET status = 'Active' WHERE id = '" . $election_id . "'") or die(mysqli_error($conn));
        }
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Online Voting System | Login</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>
    <div class="container h-100">
        <div class="d-flex justify-content-center h-100">
            <div class="user_card">
                <div class="d-flex justify-content-center">
                    <div class="brand_logo_container">
                        <img src="assets/images/logo.png" class="brand_logo" alt="Logo">
                    </div>
                </div>

                <?php

                if (isset($_GET['sign-up'])) {
                ?>
                    <div class="d-flex justify-content-center form_container">
                        <form method="post">
                            <div class="input-group mb-3">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                </div>
                                <input type="text" name="su_username" class="form-control input_user" placeholder="Username" required />
                            </div>
                            <div class="input-group mb-2">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fa-solid fa-phone" style="color: #000000;"></i>
                                    </span>
                                </div>
                                <input type="text" name="su_p_number" class="form-control input_pass" placeholder="Phone Number">
                            </div>
                            <div class="input-group mb-2">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                                </div>
                                <input type="password" name="su_password" class="form-control input_pass" placeholder="Password">
                            </div>
                            <div class="input-group mb-2">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                                </div>
                                <input type="password" name="confirm_password" class="form-control input_pass" placeholder="Confirm Password">
                            </div>

                            <div class="d-flex justify-content-center mt-3 login_container">
                                <button type="submit" name="sign_up_btn" class="btn login_btn">Sign Up</button>
                            </div>
                        </form>
                    </div>

                    <div class="mt-4">
                        <div class="d-flex justify-content-center links">
                            Already Have An Account ? <a href="index.php" class="ml-2">Sign In</a>
                        </div>

                    </div>
                <?php
                } else {
                ?>
                    <div class="d-flex justify-content-center form_container">
                        <form method="post">
                            <div class="input-group mb-3">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                </div>
                                <input type="text" name="username" class="form-control input_user" placeholder="Username" required />
                            </div>
                            <div class="input-group mb-2">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                                </div>
                                <input type="password" name="password" class="form-control input_pass" placeholder="Password" required>
                            </div>

                            <div class="d-flex justify-content-center mt-3 login_container">
                                <button type="submit" name="loginbtn" class="btn login_btn">Sign In</button>
                            </div>
                        </form>
                    </div>

                    <div class="mt-4">
                        <div class="d-flex justify-content-center links">
                            Don't have an account? <a href="?sign-up=1" class="ml-2">Sign Up</a>
                        </div>
                        <div class="d-flex justify-content-center links">
                            <a href="#">Forgot your password?</a>
                        </div>
                    </div>
                <?php
                }

                ?>

                <?php
                if (isset($_GET['registered'])) {
                ?>
                    <span class="text-success text-center ">Your account has been created successfully!</span>
                <?php
                } else if (isset($_GET['invalid'])) {
                ?>
                    <span class="text-danger text-center ">Something went wrong, please try again</span>
                <?php
                } else if (isset($_GET['not_registered'])) {
                ?>
                    <span class="text-warning text-center ">Sorry, you are not registered yet.</span>
                <?php
                } else if (isset($_GET['invalid_access'])) {
                ?>
                    <span class="text-danger text-center ">Invalid username or password.</span>
                <?php
                } else if (isset($_GET['userExist'])) {
                ?>
                    <span class="text-danger text-center ">Username already exist.</span>
                <?php
                } else if (isset($_GET['numberExist'])) {
                ?>
                    <span class="text-danger text-center ">Phone number already exists.</span>
                <?php
                }


                ?>


            </div>
        </div>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
</body>

</html>


<?php

require_once("admin/inc/config.php");

if (isset($_POST['sign_up_btn'])) {

    $su_username = mysqli_real_escape_string($conn, $_POST['su_username']);
    $su_p_number = mysqli_real_escape_string($conn, $_POST['su_p_number']);
    $su_password = mysqli_real_escape_string($conn, sha1($_POST['su_password']));
    $confirm_password = mysqli_real_escape_string($conn, sha1($_POST['confirm_password']));
    $user_role = "Voter";

    //fetching data from user table
    $fetchingUserData = mysqli_query($conn, "SELECT * FROM users ") or die(mysqli_error($conn));
    $isAnyUser = mysqli_fetch_assoc($fetchingUserData);
    if ($isAnyUser > 0) {
        $data = mysqli_fetch_assoc($fetchingUserData);
        $username = $data['username'];
        $contact_no = $data['contact_no'];

        if ($su_username == $username) {
            echo "<script> location.assign('index.php?sign-up=1&userExist=1');</script>";
        } else if ($su_p_number == $contact_no) {
            echo "<script> location.assign('index.php?sign-up=1&numberExist=1');</script>";
        }
    }


    if ($su_password == $confirm_password) {
        //Insert Query
        mysqli_query($conn, "INSERT INTO users(username,
         contact_no, password, user_role) VALUES('" . $su_username . "', '" . $su_p_number . "', '" . $su_password . "', '" . $user_role . "')") or die(mysqli_error($conn));

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
} else if (isset($_POST['loginbtn'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, sha1($_POST['password']));

    //FETCH or SELECT Query
    $fetchingData = mysqli_query($conn, "SELECT * FROM users WHERE username= '" . $username . "'") or die(mysqli_error($conn));



    if (mysqli_num_rows($fetchingData) > 0) {
        $data = mysqli_fetch_assoc($fetchingData);

        if ($username == $data['username'] and $password == $data['password']) {
            session_start();
            $_SESSION['user_role'] = $data['user_role'];
            $_SESSION['username'] = $data['username'];
            $_SESSION['user_id'] = $data['id'];

            if ($data['user_role'] == "Admin") {
                $_SESSION['key'] = "AdminKey";
        ?>
                <script>
                    location.assign("admin/index.php?homepage=1");
                </script>
            <?php
            } else {
                $_SESSION['key'] = "VotersKey";
            ?>
                <script>
                    location.assign("voters/index.php");
                </script>
            <?php
            }
        } else {
            ?>
            <script>
                location.assign("index.php?invalid_access=1")
            </script>
        <?php
        }
    } else {
        ?>
        <script>
            location.assign("index.php?sign-up=1&not_registered=1")
        </script>
<?php
    }
}

?>