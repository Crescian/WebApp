<?php
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    include_once '../../model/ims_express_model/ims_system_modules_model.class.php';
    $imsSystemModules = new ImsSystemModules();
    $action = trim($_POST['action']);

    switch ($action) {
            //* ================================ SYSTEM MODULE SECTION ================================
        case 'load_sys_mod_menu_table':
            $searchValue = $_POST['search']['value'];
            echo $imsSystemModules->loadSysModMenuTable($php_fetch_ims_express_api, 'ims_sys_mod_menu', 'sysmodmenuid', 'sys_mod_menu_title', 'sys_mod_parent_id', $searchValue);
            break;
        case 'load_iso_mod_menu_table':
            $searchValue = $_POST['search']['value'];
            echo $imsSystemModules->loadSysModMenuTable($php_fetch_ims_express_api, 'ims_iso_mod_menu', 'isomodmenuid', 'iso_mod_menu_title', 'iso_mod_parent_id', $searchValue);
            break;
        case 'load_document_menu_table':
            $searchValue = $_POST['search']['value'];
            echo $imsSystemModules->loadDocumentMenuTable($php_fetch_ims_express_api, $searchValue);
            break;
            //* ================================ SYSTEM SECTION ================================
        case 'save_sys_menu_title':
            $parent_menu_id = trim($_POST['parent_menu_id']);
            $menu_title = trim($_POST['menu_title']);
            echo $imsSystemModules->saveMenuModule($php_fetch_ims_express_api, $php_insert_ims_express_api, 'ims_sys_mod_menu', 'sys_mod_menu_title', 'sys_mod_parent_id', $parent_menu_id, $menu_title);
            break;
        case 'load_sys_menu':
            $dataId = trim($_POST['dataId']);
            echo $imsSystemModules->loadMenuModule($php_fetch_ims_express_api, 'ims_sys_mod_menu', 'sysmodmenuid', 'sys_mod_parent_id', 'sys_mod_menu_title', $dataId);
            break;
        case 'update_sys_menu_title':
            $parent_menu_id = trim($_POST['parent_menu_id']);
            $menu_title = trim($_POST['menu_title']);
            $dataId = trim($_POST['dataId']);
            echo $imsSystemModules->updateMenuModule($php_fetch_ims_express_api, $php_update_ims_express_api, 'ims_sys_mod_menu', 'sysmodmenuid', 'sys_mod_menu_title', 'sys_mod_parent_id',  $menu_title, $parent_menu_id, $dataId);
            break;
        case 'delete_sys_menu_title':
            $dataId = trim($_POST['dataId']);
            echo $imsSystemModules->deleteMenuModule($php_fetch_ims_express_api, $php_update_ims_express_api, 'ims_sys_mod_menu', 'sysmodmenuid', 'sys_mod_parent_id', $dataId);
            break;
            //* ================================ ISO SECTION ================================
        case 'save_iso_menu_title':
            $parent_menu_id = trim($_POST['parent_menu_id']);
            $menu_title = trim($_POST['menu_title']);
            echo $imsSystemModules->saveMenuModule($php_fetch_ims_express_api, $php_insert_ims_express_api, 'ims_iso_mod_menu', 'iso_mod_menu_title', 'iso_mod_parent_id', $parent_menu_id, $menu_title);
            break;
        case 'load_iso_menu':
            $dataId = trim($_POST['dataId']);
            echo $imsSystemModules->loadMenuModule($php_fetch_ims_express_api, 'ims_iso_mod_menu', 'isomodmenuid', 'iso_mod_parent_id', 'iso_mod_menu_title', $dataId);
            break;
        case 'update_iso_menu_title':
            $parent_menu_id = trim($_POST['parent_menu_id']);
            $menu_title = trim($_POST['menu_title']);
            $dataId = trim($_POST['dataId']);
            echo $imsSystemModules->updateMenuModule($php_fetch_ims_express_api, $php_update_ims_express_api, 'ims_iso_mod_menu', 'isomodmenuid', 'iso_mod_menu_title', 'iso_mod_parent_id',  $menu_title, $parent_menu_id, $dataId);
            break;
        case 'delete_iso_menu_title':
            $dataId = trim($_POST['dataId']);
            echo $imsSystemModules->deleteMenuModule($php_fetch_ims_express_api, $php_update_ims_express_api, 'ims_iso_mod_menu', 'isomodmenuid', 'iso_mod_parent_id', $dataId);
            break;
            //* ================================ DOCUMENT MODULE SECTION ================================
        case 'save_document_title':
            $parent_menu_id = trim($_POST['parent_menu_id']);
            $menu_title = trim($_POST['menu_title']);
            echo $imsSystemModules->saveMenuModule($php_fetch_ims_express_api, $php_insert_ims_express_api, 'ims_document_menu', 'doc_menu_title', 'doc_menu_parent_id', $parent_menu_id, $menu_title);
            break;
        case 'load_doc_menu':
            $dataId = trim($_POST['dataId']);
            echo $imsSystemModules->loadMenuModule($php_fetch_ims_express_api, 'ims_document_menu', 'docmenuid', 'doc_menu_parent_id', 'doc_menu_title', $dataId);
            break;
        case 'update_doc_menu_title':
            $parent_menu_id = trim($_POST['parent_menu_id']);
            $menu_title = trim($_POST['menu_title']);
            $dataId = trim($_POST['dataId']);
            echo $imsSystemModules->updateMenuModule($php_fetch_ims_express_api, $php_update_ims_express_api, 'ims_document_menu', 'docmenuid', 'doc_menu_title', 'doc_menu_parent_id',  $menu_title, $parent_menu_id, $dataId);
            break;
        case 'delete_doc_menu_title':
            $dataId = trim($_POST['dataId']);
            echo $imsSystemModules->deleteMenuModule($php_fetch_ims_express_api, $php_update_ims_express_api, 'ims_document_menu', 'docmenuid', 'doc_menu_parent_id', $dataId);
            break;
            //* ================================ SYSTEM MODULE COMMON SECTION ================================
        case 'load_select_values':
            switch (trim($_POST['category'])) {
                case 'sys_parent_menu':
                    echo $imsSystemModules->loadSysModSelectValues($php_fetch_ims_express_api, 'ims_sys_mod_menu', 'sysmodmenuid', 'sys_mod_menu_title', 'ASC');
                    break;
                case 'iso_parent_menu':
                    echo $imsSystemModules->loadSysModSelectValues($php_fetch_ims_express_api, 'ims_iso_mod_menu', 'isomodmenuid', 'iso_mod_menu_title', 'ASC');
                    break;
                default:
                    echo $imsSystemModules->loadSysModSelectValues($php_fetch_ims_express_api, 'ims_document_menu', 'docmenuid', 'doc_menu_title', 'ASC');
                    break;
            }
            break;
    }
}
