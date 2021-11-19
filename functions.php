<?php

// function sendSMS($mobile=null, $subject=null)
// {
// $SMSapiKey = 'OvoKqPy4NsF9';
// $url = SMS_URL.'?target=https://mifos.inukasystem.com:8443/fineract-provider/api/v1/'.SMS_SENDERID.'&login=INUKAAFRICA'.SMS_USERNAME.'&pass=inukaafrica'.SMS_PASSWORD.'&msisdn=OvoKqPy4NsF9'.$telephone.'&text='.urlencode($sms);

// $ch = curl_init();
// curl_setopt($ch, CURLOPT_URL, $url);
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// $returndata = curl_exec($ch);
// curl_close($ch);
// return "A SMS SENT SUCCESSFULLY TO $mobile";
// }

function clear($text) {
    $text = strip_tags($text);
    $text = trim($text);
    $text = htmlspecialchars($text);
    return $text;
}
function clearText($text) {
    $text = clear($text);
    $text = str_replace(" ", "", $text);
    $text = strtolower($text);
    $text = ucfirst($text);
    return $text;
}
function validateFirstName($firstName) {
    $errors = array();
    if(strlen($firstName) > 25 || strlen($firstName) < 2) {
        array_push($errors, "<span class='error' data-errorType='firstName'>Your first name must be between 2 and 25 character</span>");
    }
    return $errors;
}
function validateLastName($lastName) {
    $errors = array();
    if(strlen($lastName) > 25 || strlen($lastName) < 2) {
        array_push($errors, "<span class='error' data-errorType='lastName'>Your last name must be between 2 and 25 character</span>");
    }
    return $errors;
}
function validateEmail($con, $email, $confirmation) {
    $errors = array();
    if($email != $confirmation) {
        array_push($errors, "<span class='error' data-errorType='email'>Your emails do not match</span>");
        return $errors;
    }
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        array_push($errors, "<span class='error' data-errorType='email'>Please enter a valid email adress</span>");
        return $errors;
    }

    $query = $con->prepare("SELECT email FROM users WHERE email=:em");
    $query->bindParam(":em", $email);
    $query->execute();

    if($query->rowCount() != 0) {
        array_push($errors, "<span class='error' data-errorType='email'>This Email is already exists</span>");
        return $errors;
    } 
    return $errors;
}
function validatePasswords($password, $confirmation) {
    $errors = array();
    if($password != $confirmation) {
        array_push($errors, "<span class='error'>Your Passwords do not match</span>");
        return $errors;
    }
    if(preg_match("/[^A-za-z0-9]/", $password)) {
        array_push($errors, "<span class='error'>Your password can only contain numbers and letters</span>");
        return $errors;
    }
    if(strlen($password) > 30 || strlen($password) < 5) {
        array_push($errors, "<span class='error'>Your password must be between 5 and 30 characters</span>");
        return $errors;
    }
    return $errors;
}