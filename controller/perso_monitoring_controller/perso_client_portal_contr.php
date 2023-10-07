<?php
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    include_once '../../model/perso_monitoring_model/perso_client_portal_model.php';
    $bannerdata_conn = $conn->db_conn_bannerdata();
    $clientPortal = new ClientPortal();
    $action = trim($_POST['action']);

    switch ($action) {
        case 'load_client_order_table':
            $searchValue = $_POST['search']['value'];
            echo $clientPortal->loadClientOrderTable($php_fetch_perso_status_api, $searchValue);
            break;

        case 'load_user_accounts_table':
            $searchValue = $_POST['search']['value'];
            echo $clientPortal->loadUserAccountTable($php_fetch_perso_status_api, $searchValue);
            break;

        case 'save_client_user':
            $client_fullname = trim($_POST['client_fullname']);
            $client_username = trim($_POST['client_username']);
            $client_password = trim($_POST['client_password']);
            $client_customer_name = trim($_POST['client_customer_name']);
            echo $clientPortal->saveClientUser($php_fetch_perso_status_api, $php_insert_perso_status_api, $client_fullname, $client_username, $client_password, $client_customer_name);
            break;

        case 'delete_client_portal_data':
            $clientPortal->deleteClientPortalData($php_update_perso_status_api);
            break;

        case 'update_client_portal_data':
            $clientPortal->updateClientPortalData($bannerdata_conn, $php_insert_perso_status_api);
            break;

        case 'load_customer_name':
            echo $clientPortal->loadCustomerName($bannerdata_conn);
            break;
    }
}
