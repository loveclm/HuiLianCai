<?php
/**
 * =======================================================================
 *  File:       Sending_sms.php
 *  Author:      MessageBird B.V.
 *
 *  More information? Go to www.messagebird.com/sms-api
 *
 *  This class requires that you have PHP 5.1.x or higher installed.
 * ========================================================================
 */

require_once 'class.Messagebird.php';

// Set the MessageBird username and password. Will be used later in the script
$username = 'cabtu';
$password = '!@#456QWErty';

$sms_code = mt_rand(1000,9999);
// Prevent default username/password example settings to call the API
if ($username == 'username' || $password == 'password') {
    //echo '<br />You need to enter the correct username and password in example_form.php before you can use this example!';
    $data['type'] = '00';
    $data['err'] = "Incorrect Username";

    echo json_encode($data);
    exit;
}

// Only send message when this script is accessed using an HTTP POST
if (! empty($_POST)) {
    // Check if destination is posted
    if (! empty($_POST['destination'])) {
        $destination = $_POST['destination'];
    } else {
        $destination = null;
    }

    // Check if message is posted
    if (! empty($_POST['message'])) {
        $message = 'Welcome to visit tourism site. Please use verification code<'.$sms_code.'>. Tourism Company';
    } else {
        $message = null;
    }

    // Check if sender is posted
    if (! empty($_POST['sender'])) {
        $sender = $_POST['sender'];
    } else {
        $sender = null;
    }

    // Check if reference is posted
    if (! empty($_POST['reference'])) {
        $reference = $_POST['reference'];
    } else {
        $reference = null;
    }

    // If we have the required parameters, we can send a message.
    if ($destination !== null && $message !== null && $sender !== null) {
        $sms = new MessageBird($username, $password);

        // Add the destination mobile number.
        // This method can be called several times to add have more then one recipient for the same message
        $sms->addDestination($destination);

        if ($sender !== null) {
            // Set the sender, could be an number (16 numbers) or letters (11 characters)
            $sms->setSender($sender);
        }

        if ($reference !== null) {
            // Set an reference
            $sms->setReference($reference);
        }

        // Send the message to the destination(s)
        $sms->setTest(false);
        $sms->sendSms($message);

        // Output the response to the browser
        //Response Code: $sms->getResponseCode();
        //Response Message: $sms->getResponseMessage();
        $data['type'] = $sms->getResponseCode();
        $data['err'] = $sms->getResponseMessage();
        $data['code'] = $sms_code;
        echo json_encode($data);
        // There is no destination or message posted, we realy need those two to work.
    } else {
        $data['type'] = '00';
        $data['err'] = "Don't set phone number";
        echo json_encode($data);
    }
    // It seems there is no POST, and this example script only works with an HTTP POST.
} else {
    $data['type'] = '00';
    $data['err'] = "Incorrect call method";
    echo json_encode($data);
}
