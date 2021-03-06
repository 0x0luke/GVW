<?php require "../../config/database.php";
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: http://127.0.0.1:8080/");
    exit;
}
 
// Define variables and initialize with empty values
$username = $_POST['username'];
$password = $_POST['password'];
 
// Processing form data when form is submitted
    
// Validate credentials
// Prepare a select statement
$sql = "SELECT id, username, password FROM sellers WHERE username = ?";

if($stmt = mysqli_prepare($con, $sql)){
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "s", $param_username);
    
    // Set parameters
    $param_username = $username;
    
    // Attempt to execute the prepared statement
    if(mysqli_stmt_execute($stmt)){
        // Store result
        mysqli_stmt_store_result($stmt);
        
        // Check if username exists, if yes then verify password
        if(mysqli_stmt_num_rows($stmt) == 1){                    
            // Bind result variables
            mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
            if(mysqli_stmt_fetch($stmt)){
                if($password == $hashed_password){
                    // Password is correct, so start a new session
                    
                    
                    // Store data in session variables
                    $_SESSION["loggedin"] = true;
                    $_SESSION["id"] = $id;
                    $_SESSION["username"] = $username;
                    $_SESSION["seller"] = true;    
                                             
                    
                    // Redirect user to welcome page
                    $error = "Successful Sign in user: ".$username;
                    $_SESSION["error"] = $error;
                    header("location: http://127.0.0.1:8080/index.php");
                } else{
                    // Display an error message if password is not valid
                    $error =  "The password you entered was not valid.";
                    setcookie('error',$error);
                    header("location: seller_login.php");
                }
            }
        } else{
            // Display an error message if username doesn't exist
            $error =  "No account found with that username.";
            setcookie('error',$error);
            header("location: seller_login.php");
        }
    } else{
        echo "Oops! Something went wrong. Please try again later.";
    }

    // Close statement
    mysqli_stmt_close($stmt);
}


// Close connection
mysqli_close($con);

?>

