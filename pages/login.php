<link rel="stylesheet" href="../styles/login.css">

<?php
    require_once '../includes/dbconnect.php';
    include_once("../includes/header.php");
    include '../includes/message.php';

     $username = "";
     $password = "";
     $username_err = ""; 
     $password_err = "";
     $login_err = "";
 
     if($_SERVER["REQUEST_METHOD"] == "POST"){
 
        // Check if the user is not empty
         if(empty(trim($_POST["username"]))){
             $username_err = "The user cannot be empty";
         } else{
             $username = trim($_POST["username"]);
         }
 
         // check if the password is not empty
         if(empty(trim($_POST["password"]))){
             $password_err = "You need to provide a password";
         } else{
             $password = trim($_POST["password"]);
         }
 
         // Validate credentials
         if(empty($username_err) && empty($password_err)){
             // callout to the server in order to check if the username exist
             $sql = "SELECT id, username, password, type FROM
             user WHERE username = ? AND Active = 1";
 
             // Prepare het select-statement
             if($stmt = $conn->prepare($sql)){ 

                 $stmt->bind_param("s", $param_username); 
                 $param_username = $username;
 
                 if($stmt->execute()){
 
                     $stmt->store_result();
 
                     // check if the user exist in te database (it return only one result).
                     if($stmt->num_rows == 1){
 
                         $stmt->bind_result($id, $gebruikersnaam,$wachtwoord, $rol);
 
                         if($stmt->fetch()){
 
                             //check the password
                             //this function will only work if he password has been hashed
                             if(password_verify($_POST["password"], $wachtwoord)){
 
                                 // password is correct
                                 // set the user into the session
                                 $_SESSION["login"] = true;
                                 $_SESSION["id"] = $id;
                                 $_SESSION["username"] = $gebruikersnaam;
                                 $_SESSION["type"] = $rol;
 
                                 // redirect to the home page
                                 header("location: home.php");
                             } else{
 
                                 // credentials are not correct set error
                                 $login_err = "wrong user or password";
                             }
                         }
                     } else{
 
                         // user does not exist
                         $login_err = "user does not exist";
                     }
                 } else{
 
                     $login_err = "O_o something went wrong !";
                 }
                 // Close statement
                 $stmt->close();
             }
         }
     }
?>


<div class="login-container">
    <h2 class="text-center">Login</h2>

    <!-- Display error messages -->
    <?php if (!empty($login_err)): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $login_err; ?>
        </div>
    <?php endif; ?>

    <!-- Login form -->
    <form action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?> method="post">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>

    <!-- link to Registration page -->
    <div class="register-link">
        <p>Don't have an account? <a href="registration.php">Register here</a></p>
    </div>
</div>