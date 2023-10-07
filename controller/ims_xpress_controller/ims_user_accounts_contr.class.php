<?php
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    include_once '../../model/ims_express_model/ims_user_accounts_model.class.php';
    $imsUserAccounts = new ImsUserAccounts();
    $action = trim($_POST['action']);

    switch ($action) {
        case 'load_user_list':
            echo $imsUserAccounts->loadUserList($php_fetch_bannerweb_api);
            break;
            //* ======================= Save User Access Section =======================
        case 'save_sys_mod_user_access':
            $access_id = trim($_POST['access_id']);
            $empno = trim($_POST['empno']);
            $imsUserAccounts->saveUserAccess($php_insert_ims_express_api, 'ims_sys_mod_menu_access', 'sysmodmenu_id', 'sys_mod_access_users', $access_id, $empno);
            break;
        case 'save_iso_mod_user_access':
            $access_id = trim($_POST['access_id']);
            $empno = trim($_POST['empno']);
            $imsUserAccounts->saveUserAccess($php_insert_ims_express_api, 'ims_iso_mod_menu_access', 'isomodmenu_id', 'iso_mod_access_users', $access_id, $empno);
            break;
        case 'save_doc_view_mod_user_access':
            $access_id = trim($_POST['access_id']);
            $empno = trim($_POST['empno']);
            $imsUserAccounts->saveUserAccess($php_insert_ims_express_api, 'ims_doc_viewing_access', 'doc_menu_parent_id', 'doc_viewing_user', $access_id, $empno);
            break;
        case 'save_doc_control_mod_user_access':
            $access_id = trim($_POST['access_id']);
            $empno = trim($_POST['empno']);
            $imsUserAccounts->saveUserAccess($php_insert_ims_express_api, 'ims_doc_control_access', 'doc_menu_parent_id', 'doc_control_user', $access_id, $empno);
            break;
            //* ======================= Save User Access Section End =======================
            //* ======================= Delete User Access Section =======================
        case 'delete_sys_mod_user_access':
            $empno = trim($_POST['empno']);
            $imsUserAccounts->deleteUserAccess($php_update_ims_express_api, 'ims_sys_mod_menu_access', 'sys_mod_access_users', $empno);
            break;
        case 'delete_iso_mod_user_access':
            $empno = trim($_POST['empno']);
            $imsUserAccounts->deleteUserAccess($php_update_ims_express_api, 'ims_iso_mod_menu_access', 'iso_mod_access_users', $empno);
            break;
        case 'delete_doc_view_mod_user_access':
            $empno = trim($_POST['empno']);
            $imsUserAccounts->deleteUserAccess($php_update_ims_express_api, 'ims_doc_viewing_access', 'doc_viewing_user', $empno);
            break;
        case 'delete_doc_control_mod_user_access':
            $empno = trim($_POST['empno']);
            $imsUserAccounts->deleteUserAccess($php_update_ims_express_api, 'ims_doc_control_access', 'doc_control_user', $empno);
            break;
            //* ======================= Delete User Access Section End =======================
            //* ======================= Load User Access Section =======================
        case 'load_system_menu_access':
            $empno = trim($_POST['empno']);
            echo $imsUserAccounts->loadUserAccess($php_fetch_ims_express_api, 'ims_sys_mod_menu_access', 'sysmodmenu_id', 'sys_mod_access_users', $empno);
            break;
        case 'load_iso_menu_access':
            $empno = trim($_POST['empno']);
            echo $imsUserAccounts->loadUserAccess($php_fetch_ims_express_api, 'ims_iso_mod_menu_access', 'isomodmenu_id', 'iso_mod_access_users', $empno);
            break;
        case 'load_document_viewing_access':
            $empno = trim($_POST['empno']);
            echo $imsUserAccounts->loadUserAccess($php_fetch_ims_express_api, 'ims_doc_viewing_access', 'doc_menu_parent_id', 'doc_viewing_user', $empno);
            break;
        case 'load_document_control_access':
            $empno = trim($_POST['empno']);
            echo $imsUserAccounts->loadUserAccess($php_fetch_ims_express_api, 'ims_doc_control_access', 'doc_menu_parent_id', 'doc_control_user', $empno);
            break;
            //* ======================= Load User Access Section End =======================






        case 'load_select_department':
            echo $imsUserAccounts->loadSelectValueWithId($php_fetch_bannerweb_api, 'dept_code', 'department', 'bpi_department', 'ASC');
            break;

        case 'load_select_employee':
            $dept_code = trim($_POST['dept_code']);
            echo $imsUserAccounts->loadSelectEmployee($php_fetch_bannerweb_api, 'empno', 'emp_name', 'prl_employee',  $dept_code, 'ASC');
            break;

        case 'load_employee_job_title':
            $empno = trim($_POST['empno']);
            echo $imsUserAccounts->loadEmployeeJobTitle($php_fetch_bannerweb_api, $empno);
            break;

        case 'load_employee_information':
            $userid = trim($_POST['userid']);
            echo $imsUserAccounts->loadEmployeeInfo($php_fetch_bannerweb_api, $userid);
            break;

        case 'load_system_menu_tree':
            echo $imsUserAccounts->loadMenuTree($php_fetch_ims_express_api, 'ims_sys_mod_menu', 'sysmodmenuid', 'sys_mod_menu_title', 'sys_mod_parent_id');
            break;

        case 'load_iso_menu_tree':
            echo $imsUserAccounts->loadMenuTree($php_fetch_ims_express_api, 'ims_iso_mod_menu', 'isomodmenuid', 'iso_mod_menu_title', 'iso_mod_parent_id');
            break;

        case 'load_document_control_menu_tree':
            echo $imsUserAccounts->loadMenuTree($php_fetch_ims_express_api, 'ims_document_menu', 'docmenuid', 'doc_menu_title', 'doc_menu_parent_id');
            break;

        case 'load_document_viewing_menu_tree':
            echo $imsUserAccounts->loadMenuTree($php_fetch_ims_express_api, 'ims_document_menu', 'docmenuid', 'doc_menu_title', 'doc_menu_parent_id');
            break;

        case 'load_image_base64':
            $image_data = $_POST['image'];
            $image_array_1 = explode(";", $image_data);
            $image_array_2 = explode(",", $image_array_1[1]);
            $image_data = base64_decode($image_array_2[1]);
            $photo_base64 = base64_encode($image_data);
            echo $photo_base64;
            break;
    }
}
