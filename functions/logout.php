<?php
session_start();
include_once '../configuration/connection.php';
$BannerWebLive = $conn->db_conn_bannerweb(); //* Banner Web Database connection

$query = "UPDATE bpi_user_accounts SET is_logged_in = false WHERE empno = '{$_SESSION['empno']}'";
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

session_destroy();
header("Location: ./../index.php");
