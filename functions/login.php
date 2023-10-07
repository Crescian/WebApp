<?php
include_once '../configuration/connection.php';
$BannerWebLive = $conn->db_conn_bannerweb(); //* Banner Web Database connection

session_start();
date_default_timezone_set('Asia/Manila');

$date = date('Y-m-d');
$timestamp = new DateTime('now');
$timestamp = $timestamp->format('Y-m-d H:i:s');

if (isset($_POST['action'])) {
    $action = trim($_POST['action']);

    switch ($action) {
        case 'login':
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);
            // Check if all fields are not empty.
            if (!empty($username) and !empty($password)) {

                $query = "SELECT *,encode(user_image, 'escape') AS encoded_image,(emp_fn || ' ' || emp_sn) AS fullname 
                FROM bpi_user_accounts
                INNER JOIN prl_employee ON prl_employee.empno = bpi_user_accounts.empno 
                WHERE username = :username";
                $stmt = $BannerWebLive->prepare($query);
                $stmt->bindParam(':username', $username);
                $stmt->execute();
                $result_row = $stmt->fetch(PDO::FETCH_ASSOC);
                $result_count = $stmt->rowCount();
                // $data_base64 = base64_encode($query);
                // $curl = curl_init();
                // curl_setopt($curl, CURLOPT_URL, $php_fetch_BPI);
                // curl_setopt($curl, CURLOPT_HEADER, false);
                // curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                // curl_setopt($curl, CURLOPT_POST, true);
                // curl_setopt($curl, CURLOPT_POSTFIELDS, array("data" => $data_base64));
                // $json_response = curl_exec($curl);
                // ## ====== Close Connection ======
                // curl_close($curl);
                // ## ====== Create Array ======
                // $result_row = json_decode($json_response, true);
                // $result_count = array_sum(array_map("count", $result_row));

                // Check if there is a matching record. 
                if ($result_count > 0) {
                    $pass_created_date = date('Y-m-d',  strtotime("+3 months", strtotime($result_row['pass_created_date'])));
                    // Check if the account is active.
                    if ($result_row['is_active'] == true) {

                        // Check if password reset upon creation of user.
                        if ($result_row['reset_pass'] == true && $result_row['userpass'] == md5($password)) {
                            echo "reset-01-" . $result_row['user_id'];
                        } else {
                            // Check if the account is locked.
                            if ($result_row['act_lockedout'] == false) {

                                // Check if password has expired/ 90days.
                                if ($pass_created_date != $date) {

                                    // Check if the password is correct.
                                    if ($result_row['userpass'] == md5($password)) {

                                        // Verify whether the account is logged onto another device.
                                        if ($result_row['is_logged_in'] == false) {

                                            $query = "UPDATE bpi_user_accounts SET login_attempt = 0, is_logged_in = true WHERE username = :username";
                                            $stmt = $BannerWebLive->prepare($query);
                                            $stmt->bindParam(':username', $username);
                                            $stmt->execute();

                                            // $data_base64 = base64_encode($query);
                                            // $curl = curl_init();
                                            // curl_setopt($curl, CURLOPT_URL, $php_update_BPI);
                                            // curl_setopt($curl,  CURLOPT_HEADER, false);
                                            // curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                            // curl_setopt($curl, CURLOPT_POST, true);
                                            // curl_setopt($curl, CURLOPT_POSTFIELDS, array("data" => $data_base64));
                                            // $json_response = curl_exec($curl);
                                            // ## ====== Close Connection ======
                                            // curl_close($curl);

                                            $_SESSION['username'] = $result_row['username'];
                                            $_SESSION['empno'] = $result_row['empno'];
                                            $_SESSION['user_image'] = $result_row['encoded_image'];
                                            $_SESSION['access_lvl'] = $result_row['access_lvl'];
                                            $_SESSION['fullname'] = $result_row['fullname'];
                                            $_SESSION['dept_code'] = $result_row['department'];
                                            echo "Login";
                                        } else {
                                            echo "Account Already Logged In.";
                                        }
                                    } else {
                                        $login_attempt = $result_row['login_attempt'];
                                        $login_attempt += 1;
                                        $query = "UPDATE bpi_user_accounts SET login_attempt = '{$login_attempt}' WHERE username = '{$username}'";
                                        $stmt = $BannerWebLive->prepare($query);
                                        $stmt->execute();
                                        $attempt_left = 6 - $login_attempt;

                                        // Check the number of login attempts.
                                        if ($login_attempt < 6) {
                                            echo "Username and Password Do Not Match! {$attempt_left} Attempt/s Left.";
                                        } else {
                                            $query = "UPDATE bpi_user_accounts SET login_attempt = 0, act_lockedout = true WHERE username = '{$username}'";
                                            $stmt = $BannerWebLive->prepare($query);
                                            $stmt->execute();
                                            echo "Too Many Attempts, Account Locked!";
                                        }
                                    }
                                } else {
                                    // Reset password after 90 days.
                                    echo "reset-90-" . $result_row['user_id'];
                                }
                            } else {
                                echo "Account Locked! Please Contact Administrator. Call Tel# loc. 120";
                            }
                        }
                    } else {
                        echo "Account is Disabled.";
                    }
                } else {
                    echo "Incorrect Username or Password!";
                }
            } else {
                echo "Please Fill Out All Fields!";
            }

            $BannerWebLive = null;
            break;

        case 'resetPassword':
            $user_id = trim($_POST['user_id']);
            $new_password = trim($_POST['new_password']);
            $confirm_password = trim($_POST['confirm_password']);

            if (empty($new_password) || empty($confirm_password)) {
                echo "Please fill out all fields.";
            } else if (($new_password != $confirm_password)) {
                echo "New Password and Confirm Password Do Not Match.";
            } else if (preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)+(?=.*[-+_!@#$%^&*., ?]).+$/', $new_password) == 0 || preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)+(?=.*[-+_!@#$%^&*., ?]).+$/', $confirm_password) == 0) {
                echo 'Password must contain one uppercase, one lowercase, numbers and special characters.';
            } else if ((strlen($new_password) < 8 || strlen($new_password) > 24) || (strlen($confirm_password) < 8 || strlen($confirm_password) > 24)) {
                echo "Password must be 8-24 characters long.";
            } else {
                $query = "SELECT * FROM bpi_user_accounts WHERE user_id = '" . $user_id . "'";
                $stmt = $BannerWebLive->prepare($query);
                $stmt->execute();
                $result_row = $stmt->fetch(PDO::FETCH_ASSOC);

                // $data_base64 = base64_encode($query);
                // $curl = curl_init();
                // curl_setopt($curl, CURLOPT_URL, $php_fetch_BPI);
                // curl_setopt($curl, CURLOPT_HEADER, false);
                // curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                // curl_setopt($curl, CURLOPT_POST, true);
                // curl_setopt($curl, CURLOPT_POSTFIELDS, array("data" => $data_base64));
                // $json_response = curl_exec($curl);
                // ## ====== Close Connection ======
                // curl_close($curl);
                // ## ====== Create Array ======
                // $result_row = json_decode($json_response, true);

                // Check password if it is an Old Password
                if ($result_row['userpass'] != md5($confirm_password) && $result_row['prev_pass1'] != md5($confirm_password) && $result_row['prev_pass2'] != md5($confirm_password) && $result_row['prev_pass3'] != md5($confirm_password) && $result_row['prev_pass4'] != md5($confirm_password)) {
                    // Count the number of password changes.
                    $change_pass_count = $result_row['change_pass_count'] + 1;
                    $change_pass_count_remainder = fmod($change_pass_count, 4);
                    $prev_pass = "";
                    switch ($change_pass_count_remainder) {
                        case '1':
                            $prev_pass = "prev_pass1";
                            break;

                        case '2':
                            $prev_pass = "prev_pass2";
                            break;

                        case '3':
                            $prev_pass = "prev_pass3";
                            break;

                        case '0':
                            $prev_pass = "prev_pass4";
                            break;
                    }
                    $md5_password = md5($new_password);
                    $query_update = "UPDATE bpi_user_accounts SET userpass = '{$md5_password}', login_attempt = 0, reset_pass = false, $prev_pass = '{$md5_password}', pass_created_date = '{$timestamp}', change_pass_count = '{$change_pass_count}' WHERE user_id = '{$user_id}'";
                    $stmt_update = $BannerWebLive->prepare($query_update);
                    $stmt_update->execute();

                    // $data_base64 = base64_encode($query_update);
                    // $curl = curl_init();
                    // curl_setopt($curl, CURLOPT_URL, $php_update_BPI);
                    // curl_setopt($curl, CURLOPT_HEADER, false);
                    // curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    // curl_setopt($curl, CURLOPT_POST, true);
                    // curl_setopt($curl, CURLOPT_POSTFIELDS, array("data" => $data_base64));
                    // $json_response = curl_exec($curl);
                    // ## ====== Close Connection ======
                    // curl_close($curl);

                    echo "Password Changed!";
                } else {
                    echo 'Old Password';
                }
            }

            $BannerWebLive = null;
            break;

        case 'forgotPassword':
            $username_forgot_password = $_POST['username_forgot_password'];
            $query = "SELECT * FROM bpi_user_accounts WHERE username = '{$username_forgot_password}'";
            $stmt = $BannerWebLive->prepare($query);
            $stmt->execute();
            $result_row = $stmt->rowCount();
            if ($result_row > 0) {
                $otp = rand(100000, 999999);

                $query = "UPDATE bpi_user_accounts SET forgot_password = true WHERE username = '{$username_forgot_password}'";
                $stmt = $BannerWebLive->prepare($query);
                $stmt->execute();
                echo $otp;
            } else {
                // No record found
                echo false;
            }

            $BannerWebLive = null;
            break;

        case 'isLoggedIn':
            $query = "SELECT * FROM bpi_user_accounts WHERE username = '{$_SESSION['username']}'";
            $stmt = $BannerWebLive->prepare($query);
            $stmt->execute();
            $result_row = $stmt->fetch(PDO::FETCH_ASSOC);

            // $data_base64 = base64_encode($query);
            // $curl = curl_init();
            // curl_setopt($curl, CURLOPT_URL, $php_fetch_BPI);
            // curl_setopt($curl, CURLOPT_HEADER, false);
            // curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            // curl_setopt($curl, CURLOPT_POST, true);
            // curl_setopt($curl, CURLOPT_POSTFIELDS, array("data" => $data_base64));
            // $json_response = curl_exec($curl);
            // ## ====== Close Connection ======
            // curl_close($curl);
            // ## ====== Create Array ======
            // $result_row = json_decode($json_response, true);

            echo $result_row['is_logged_in'];
            $BannerWebLive = null;
            break;
        case 'forceLogOut':
            $username = trim($_POST['username']);
            $query = "UPDATE bpi_user_accounts SET is_logged_in = false WHERE username = '{$username}'";
            $stmt = $BannerWebLive->prepare($query);
            $stmt->execute();

            // $data_base64 = base64_encode($query);
            // $curl = curl_init();
            // curl_setopt($curl, CURLOPT_URL, $php_update_BPI);
            // curl_setopt($curl, CURLOPT_HEADER, false);
            // curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            // curl_setopt($curl, CURLOPT_POST, true);
            // curl_setopt($curl, CURLOPT_POSTFIELDS, array("data" => $data_base64));
            // $json_response = curl_exec($curl);
            // ## ====== Close Connection ======
            // curl_close($curl);
            break;
        case 'dateTime':
            $query = "SELECT * FROM bpi_user_accounts WHERE username = 'jmacuisa'";
            $stmt = $BannerWebLive->prepare($query);
            $stmt->execute();
            $result_row = $stmt->fetch(PDO::FETCH_ASSOC);
            // $datetime2 = new DateTime($result_row['lockout_timestart']);
            // $interval = $timestamp->diff($datetime2);
            // $elapsed = $interval->format('%h hours %i minutes %s seconds');
            // echo $elapsed;
            echo $result_row['lockout_timestart'];
            break;
    }
}
