<?php
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    include_once '../../model/info_sec_model/info_sec_web_app_model.class.php';
    $bannerWeb = $conn->db_conn_bannerweb(); //* BannerWeb Database connection
    $infoSec = $conn->db_conn_info_security(); //* Info Security Database connection
    $webRequest = new WebRequest();
    $action = trim($_POST['action']);

    switch ($action) {
        case 'load_table_request':
            $statusVal = trim($_POST['statusVal']);
            $searchValue = $_POST['search']['value'];
            echo $webRequest->loadTableRequest($infoSec, $statusVal, $searchValue);
            break;

        case 'load_request_count':
            echo $webRequest->loadRequestCount($infoSec);
            break;

        case 'acknowledge_request':
            $webappid = trim($_POST['webappid']);
            $logged_user = trim($_POST['logged_user']);
            $webRequest->acknowledgeRequest($infoSec, $bannerWeb, $webappid, $logged_user);
            break;

        case 'process_request':
            $webappid = trim($_POST['webappid']);
            $logged_user = trim($_POST['logged_user']);
            echo $webRequest->processRequest($infoSec, $webappid, $logged_user);
            break;

        case 'accomplish_request':
            $webappid = trim($_POST['webappid']);
            $webRequest->accomplishRequest($infoSec, $webappid);
            break;

        case 'load_request_details':
            $webappid = trim($_POST['webappid']);
            echo $webRequest->loadRequestDetails($infoSec, $webappid);
            break;
    }
}
