<?php

require_once '../includes/dbconnect.php';
include_once("../includes/header.php");


$email = $password = $confirm_password = "";
$email_err = $password_err = $confirm_password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["email"]))) {
        $email_err = "Het e-mailadres mag niet leeg zijn.";
    } elseif (!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
        $email_err = "Dit is geen correct e-mailadres.";
    } else {
        $sql = "SELECT id FROM user WHERE UserName = ?"; 
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $param_email);
            // Set de parameter
            $param_email = trim($_POST["email"]);
            if ($stmt->execute()) {
                $stmt->store_result();
                // check if email already exist in the database
                if ($stmt->num_rows == 1) {
                    $email_err = "Er bestaat reeds een gebruiker met dit e-mailadres.";
                } else {
                    $email = trim($_POST["email"]);
                }
            } else {
                echo "Er is iets verkeerd gelopen, probeer opnieuw.";
            }
            // Close statement
            $stmt->close();
        }
    }

    // Password validation
    if (empty(trim($_POST["psw"]))) {
        $password_err = "U moet een wachtwoord ingeven.";
    } elseif (strlen(trim($_POST["psw"])) < 6) {
        $password_err = "Uw wachtwoord moet minimaal 6 karakters bevatten.";
    } else {
        $password = trim($_POST["psw"]);
    }

    // Password valiation
    if (empty(trim($_POST["psw-repeat"]))) {
        $confirm_password_err = "Bevestig uw wachtwoord.";
    } else {
        $confirm_password = trim($_POST["psw-repeat"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Wachtwoorden komen niet overeen.";
        }
    }

    if (empty($email_err) && empty($password_err) && empty($confirm_password_err)) {
        $sql = "INSERT INTO user (Username, Password) VALUES (?, ?)"; 

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ss", $param_email, $param_password);
            // Set de parameters
            $param_email = $email;
            // Encrypt the password
            $param_password = password_hash($password, PASSWORD_DEFAULT);

            if ($stmt->execute()) {
                // Redirect to login page
                // header("location: login.php");
            } else {
                echo "Er is iets verkeerd gelopen, probeer opnieuw.";
            }
            // Close statement
            $stmt->close();
        }
    }
}
?>

<body>
    <div class="container">
        <div class="card card-register mx-auto mt-5">
            <h5 class="card-header text-center">Register</h5>
            <div class="card-body">

                <!-- Error messages -->
                <?php 
                if (!empty($email_err)) {
                    echo '<div class="alert alert-danger">' . $email_err . '</div>';
                }
                if (!empty($password_err)) {
                    echo '<div class="alert alert-danger">' . $password_err . '</div>';
                }
                if (!empty($confirm_password_err)) {
                    echo '<div class="alert alert-danger">' . $confirm_password_err . '</div>';
                }
                ?>

                <form method="post" action="registration.php">

                    <!-- email part -->
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="email">Email</label>
                            <input class="form-control" id="email" type="email" aria-describedby="emailHelp"
                                name="email" value="<?php echo htmlspecialchars($email); ?>">
                        </div>
                    </div>

                    <!-- password part -->
                    <div class="row g-3 mt-1">
                        <div class="col-md-6">
                            <label for="psw">Password</label>
                            <input class="form-control" id="psw" type="password" name="psw">
                        </div>
                        <div class="col-md-6">
                            <label for="psw-repeat">Confirm Password</label>
                            <input class="form-control" id="psw-repeat" type="password" name="psw-repeat">
                        </div>
                    </div>

                    <div class="row g-3 mt-1">
                        <div class="d-grid gap-2" >
                            <button type="submit" class="btn btn-primary" name="reg_user">Register</button>
                        </div>
                    </div>

                    <div class="text-center">
                        <a class="d-block small mt-3" href="login.php">Login Page</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>