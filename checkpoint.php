<?php
require_once("config.php");
require_once("functions.php");

if(!isset($_SESSION["userLoggedIn"]) || !isset($_SESSION["confirmationCode"])) {
    exit();
}
else {
    if(isset($_POST["codeSubmit"])) {
        if(isset($_POST["code"])) {
            if($_POST["code"] == $_SESSION["confirmationCode"]) {
                try {
                    $accessToken = base64_encode(bin2hex(openssl_random_pseudo_bytes(24)));
                    $query = $con->prepare("INSERT INTO sessions (user_id, access_token) VALUES (:id, :token)");
                    $query->bindParam(":id", $_SESSION["userLoggedIn"]);
                    $query->bindParam(":token", $accessToken);
                    $query->execute();

                    if($query->rowCount() == 1) {
                        session_unset(); 
                        setcookie("access_token", $accessToken, time() + (86400 * 30), "/");
                        header('Location: http://localhost/index.php');
                        die();
                    }
                }
                catch(PDOException $err) {
                    $errors[] = "<span class='error'>Something Went Wrong Please Try Again</span>";
                }
            }
            else {
                $errors[] = "<span class='error'>The code you entered was wrong</span>";
            }
        }
    }

}
?>
<!DOCTYPE html>
<html>
<head>
  	<link rel="stylesheet" type="text/css" href="style.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700&display=swap" rel="stylesheet">
</head>

<body>
    <div class="form-body">
        <div class="form-header">
                <h1>Two Factor Authentication Required</h1>
        </div>
        <?php 
            if(isset($_POST["codeSubmit"])) {
                if(!empty($errors)) {
                    echo "<div class='errors'>";
                    foreach($errors as $error) {
                        echo $error;
                    }
                    echo "</div>";
                }
            } 
        ?>
        <form action="checkpoint.php" method="POST">
            <input type="text" id="code" class="input" name="code" placeholder="Please enter the 6-digit code sent to your phone" autocomplete="off" required>
            <input type="submit" id="codeSubmit" class="btn" name="codeSubmit" value="Submit">
        </form>
    </div>
</body>
</html>