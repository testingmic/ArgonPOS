<?php

class Authenticate {

    public $status = false;
    public $redirect_url;
    public $error_response;
    public $success_response;

    public function processLogin($username, $password, $href) {

        global $pos, $session, $config;

        try {

            $stmt = $pos->prepare("
                SELECT * FROM users
                WHERE
                    (login='$username' OR email='$username') AND status='1'
            ");
            $stmt->execute();

            // count the number of rows found
            if($stmt->rowCount() == 1) {

                // using the foreach to fetch the information
                while($results = $stmt->fetch(PDO::FETCH_ASSOC)) {

                    // verify the password
                    if($password) {

                        // set the status variable to true
                        $this->status = true;

                        // set the user sessions for the person to continue
                        $session->set_userdata("puserLoggedIn", random_string('alnum', 50));
                        $session->set_userdata("branchId", $results["branchId"]);
                        $session->set_userdata("clientId", $results["clientId"]);
                        $session->set_userdata("userId", $results["user_id"]);
                        $session->set_userdata("userName", $results["login"]);
                        $session->set_userdata("userEmail", $results["email"]);
                        $session->set_userdata("accessLevel", $results["access_level"]);
                        // unset session locked
                        $session->userSessionLocked = null;

                        $user_agent = load_class('user_agent', 'libraries');

                        #update the table
                        $ip = ip_address();
                        $br = $user_agent->browser."|".$user_agent->platform;

                        $stmt = $pos->prepare("UPDATE users SET last_login=now(), online='1' where id='{$results["id"]}'");
                        $stmt->execute();

                        $stmt = $pos->prepare("INSERT INTO users_login_history SET branchId='{$results["branchId"]}', clientId='{$results["clientId"]}', username='$username', log_ipaddress='$ip', log_browser='$br', user_id='".$session->userId."', log_platform='".$user_agent->agent."'");
                        $stmt->execute();

                        // redirect the user
                        $this->success_response = "<script>setTimeout(function() { window.location.href='".SITE_URL."/dashboard'; }, 1000);</script>";

                    } else {
                        $this->error_response = "<div class='alert alert-danger'>Sorry! Invalid username/password</div>";
                    }
                }
            } else {
                $this->error_response = "<div class='alert alert-danger'>Sorry! Invalid username/password</div>";
            }

        } catch(PDOException $e) {
            $this->error_response = "<div class='alert alert-danger'>Sorry! Invalid username/password</div>";
        }

        return $this;
    }

    public function sendPasswordResetToken($email_address) {

        global $pos, $config;

        $user_agent = load_class('user_agent', 'libraries');

        try {

            $stmt = $pos->prepare("
                SELECT * FROM users WHERE email='$email_address' AND status='1'
            ");
            $stmt->execute();

            // count the number of rows found
            if($stmt->rowCount() == 1) {

                // set the status variable to true
                $this->status = true;
                // using the foreach to fetch the information
                while($results = $stmt->fetch(PDO::FETCH_ASSOC)) {

                    #assign variable
                    $user_id = $results["user_id"];
                    $fullname = $results["name"];
                    $username = $results["login"];

                    #create the reset password token
                    $request_token = random_string('alnum', mt_rand(40, 75));

                    #set the token expiry time to 1 hour from the moment of request
                    $expiry_time = time()+(60*60*1);

                    #update the table
                    $ip = $user_agent->ip_address();
                    $br = $user_agent->browser()." ".$user_agent->platform();

                    #deactivate all reset tokens
                    $stmt = $pos->prepare("UPDATE users_reset_request SET token_status='ANNULED' where username='$username' AND user_id='$user_id' AND token_status='PENDING'");
                    $stmt->execute();
                    
                    #process the form
                    $stmt = $pos->prepare("INSERT INTO users_reset_request SET username='$username', user_id='$user_id', request_token='$request_token', user_agent='$br|$ip', expiry_time='$expiry_time'");
                    $stmt->execute();
                    #FORM THE MESSAGE TO BE SENT TO THE USER
                    $message = 'Hi '.$fullname.'<br>You have requested to reset your password at '.config_item('site_name');
                    $message .= '<br><br>The scoreg are your login details:<br>';
                    $message .= '<strong>Email Address:</strong> '.$email_address;
                    $message .= '<br><strong>Username:</strong> '.$username;
                    $message .= '<br><br>Before you can reset your password please follow this link.<br><br>';
                    $message .= '<a class="alert alert-success" href="'.$config->base_url().'verify?pwd&tk='.$request_token.'">Click Here to Reset Password</a>';
                    $message .= '<br><br>If it does not work please copy this link and place it in your browser url.<br><br>';
                    $message .= $config->base_url().'verify?pwd&tk='.$request_token;

                    #send email to the user
                    @send_email(
                        $email_address, "[".config_item('site_name')."] Change Password",
                        $message, config_item('site_name'), config_item('site_email'), NULL, 'default', $username
                    );

                    #record the password change request
                    return true;

                }

            } else {
                return false;
            }

        } catch(PDOException $e) {
            return false;
        }
    }

    public function resetUserPassword($password, $user_id, $username, $reset_token) {

        global $pos, $config;

        $user_agent = load_class('user_agent', 'libraries');

        try {

            $stmt = $pos->query("
                SELECT users.user_id as user_id, users.clientId, users.branchId, users.status, users.login as username, reset.request_token as request_token, reset.user_id, users.email as email, users.name as name 
                FROM users users, users_reset_request reset 
                WHERE users.user_id='$user_id' AND users.status='1' AND reset.request_token='$reset_token' AND reset.user_id='$user_id'
            ");
            // count the number of rows found
            if($stmt->rowCount() == 1) {

                $this->status = true;
                
                // using the foreach to fetch the information
                while($results = $stmt->fetch(PDO::FETCH_ASSOC)) {

                    #assign variable
                    $user_id = $results["user_id"];
                    $fullname = $results["name"];
                    $email = $results["email"];
                    $username = $results["username"];
                    $clientId = $results["clientId"];
                    $branchId = $results["branchId"];                    

                    #update the table
                    $ip = ip_address();
                    $br = $user_agent->browser." ".$user_agent->platform;

                    #encrypt the password
                    $password = password_hash($password, PASSWORD_DEFAULT);
                    #deactivate all reset tokens
                    $stmt = $pos->prepare("
                        UPDATE users SET password='$password' WHERE user_id='$user_id'
                    ");
                    $stmt->execute();

                    #process the form
                    $stmt = $pos->prepare("
                        UPDATE users_reset_request SET request_token=NULL, reset_date=now(), reset_agent='$br|$ip', token_status='USED', expiry_time='".time()."' WHERE request_token='$reset_token'
                    ");
                    $stmt->execute();

                        #record the activity
                    $stmt = $pos->prepare("
                        INSERT INTO users_activity_logs SET branchId='{$branchId}', clientId='{$clientId}', fulldate=now(), user_id='$user_id', date_recorded=now(), username='$username', description='You have successfully changed your password.'
                    ");
                    $stmt->execute();

                    //FORM THE MESSAGE TO BE SENT TO THE USER
                    $message = 'Hi '.$fullname.'<br>You have successfully changed your password at '.config_item('site_name');
                    $message .= '<br><br>Do ignore this message if your rightfully effected this change.<br>';
                    $message .= '<br>If not, do';
                    $message .= '<a class="alert alert-success" href="'.$config->base_url().'recover">Click Here assword</a> if you did not perform this act.';

                    #send email to the user
                    @send_email(
                        $email, "[".config_item('site_name')."] Password Change Successful",
                        $message, config_item('site_name'), config_item('site_email'), NULL, 'default', $username
                    );

                    #record the password change request
                    return true;

                }

            } else {
                return false;
            }

        } catch(PDOException $e) {
            return $e->getMessage();
        }
    }


    public function compare_password($password) {
        global $pos;
                #run the user set password against a list of known passwords
                #to see if there is any match
                #return true if the password was not found in the database table
        try {
                    #run the search query
            $stmt = $pos->prepare("select * from users_passwords_log where password='$password'");
            $stmt->execute();
                    #count the number of rows found
            if($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch(PDOException $e) {}
    }

}