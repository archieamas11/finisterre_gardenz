<?php
require_once 'config.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Google\Client as Google_Client;
use Google\Service\Oauth2 as Google_Service_Oauth2;

function initGoogleClient() {
    $client = new Google_Client();
    $client->setClientId(GOOGLE_CLIENT_ID);
    $client->setClientSecret(GOOGLE_CLIENT_SECRET);
    $client->setRedirectUri(GOOGLE_REDIRECT_URI);
    $client->addScope("email");
    $client->addScope("profile");
    
    return $client;
}

function handleGoogleCallback($mysqli, $client) {
    if (isset($_GET['code'])) {
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        
        if (!isset($token['error'])) {
            $client->setAccessToken($token['access_token']);
            $google_oauth = new Google_Service_Oauth2($client);
            $google_account_info = $google_oauth->userinfo->get();
            
            $oauth_provider = 'google';
            $userID = $google_account_info->id;
            $email = $google_account_info->email;
            $isVerified = $google_account_info->verified_email ? '1' : '0'; 
            $firstName = $google_account_info->givenName ?? '';
            $lastName = $google_account_info->familyName ?? '';
              // Check if user exists
            $stmt = $mysqli->prepare("SELECT users.user_id, users.user_type, users.username, users.is_verified, customer.email, customer.first_name, customer.last_name, customer.customer_id
            FROM tbl_users as users
            INNER JOIN tbl_customers as customer ON users.user_id = customer.user_id
            WHERE users.google_id = ? AND customer.email = ?");
            $stmt->bind_param("ss", $userID, $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                // User exists - log them in
                $user = $result->fetch_assoc();
                $_SESSION['loggedin'] = true;
                $_SESSION['id'] = $user['user_id'];
                $_SESSION['customer_id'] = $user['customer_id'];
                $_SESSION['user_type'] = $user['user_type'];
                $_SESSION['email'] = $email;
                $_SESSION['name'] = $user['first_name'] . ' ' . $user['last_name'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['firstname'] = $firstName;
                $_SESSION['lastname'] = $lastName;

                return $user['user_type'];
            } else {                
                // Create new user
                $stmt = $mysqli->prepare("INSERT INTO tbl_users (google_id, auth_provider, is_verified, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
                $stmt->bind_param("sss", $userID, $oauth_provider, $isVerified);
                $stmt->execute();
                
                $userId = $mysqli->insert_id;
                
                // Insert customer information
                $stmt = $mysqli->prepare("INSERT INTO tbl_customers (user_id, first_name, last_name, email) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("isss", $userId, $firstName, $lastName, $email);
                $stmt->execute();

                // Fetch the customer_id
                $stmt = $mysqli->prepare("SELECT customer_id FROM tbl_customers WHERE user_id = ?");
                $stmt->bind_param("i", $userId);
                $stmt->execute();
                $result = $stmt->get_result();
                $customer = $result->fetch_assoc();

                $_SESSION['loggedin'] = true;
                $_SESSION['id'] = $userId;
                $_SESSION['customer_id'] = $customer['customer_id'];
                $_SESSION['user_type'] = 'user';
                $_SESSION['email'] = $email;
                $_SESSION['name'] = $firstName . ' ' . $lastName;
                $_SESSION['firstname'] = $firstName;
                $_SESSION['lastname'] = $lastName;
                
                return 'user';
            }
        }
    }
    return false;
}