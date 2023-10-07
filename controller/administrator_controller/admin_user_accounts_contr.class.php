<?php
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    include_once '../../model/administrator_model/admin_user_accounts_model.class.php';
    $BannerWeb = $conn->db_conn_bannerweb(); //* BannerWeb Database connection
    $bannerUserAccounts = new BannerUserAccounts();
    $action = trim($_POST['action']);

    switch ($action) {
        case 'load_user_list':
            echo $bannerUserAccounts->loadUserList($BannerWeb);
            break;

        case 'load_user_info':
            $user_id = trim($_POST['user_id']);
            echo $bannerUserAccounts->loadUserInfo($BannerWeb, $user_id);
            break;
    }
}
