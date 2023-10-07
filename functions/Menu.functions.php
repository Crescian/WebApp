<?php
include_once '../configuration/connection.php';
$BannerWebLive = $conn->db_conn_bannerweb(); //* Banner Web Database connection

session_start();
date_default_timezone_set('Asia/Manila');

if (isset($_POST['action'])) {
    $action = trim($_POST['action']);
    $date = date('Y-m-d');

    // ==================== Functions ====================
    function loadMenu($appId, $menuGrouped, $parentId)
    {
        $result = "";
        if (isset($menuGrouped[$parentId])) {
            foreach ($menuGrouped[$parentId] as $menuId => $menuTitleAndLink) {
                // Parent Menu
                if (isset($menuGrouped[$menuId])) {
                    $result .=
                        '<button class="list-group-item list-group-item-action fs-6" data-bs-toggle="collapse" data-bs-target="#parentMenu' . $menuId . '" aria-expanded="false">
                            <i class="fa-solid fa-folder"></i> ' . $menuTitleAndLink[0] . '
                        </button>
                        <div class="collapse" id="parentMenu' . $menuId . '">
                            ' . loadMenu($appId, $menuGrouped, $menuId) . '
                        </div>';
                }
                // Child Menu
                if (!isset($menuGrouped[$menuId])) {
                    $result .=
                        '<a href="' . $menuTitleAndLink[1] . '?app_id=' . $appId . '">
                            <div class="list-group-item list-group-item-action">
                                ' . $menuTitleAndLink[0] . ' 
                            </div>
                        </a>';
                }
            }
        }
        return '<div class="list-group main-menu">' . $result . '</div>';
    }

    // ==================== Switch ====================
    switch ($action) {
        case 'loadAppMenu':
            $app_id = trim($_POST['app_id']);

            $queryMenu = "SELECT appmenuid, app_menu_title, app_menu_parent_id, app_menu_link FROM bpi_app_menu_module 
                INNER JOIN bpi_access_module ON bpi_access_module.appmenu_id = bpi_app_menu_module.appmenuid
                WHERE bpi_app_menu_module.app_id = :app_id AND access_user = :empno
                ORDER BY appmenuid";
            $stmtMenu = $BannerWebLive->prepare($queryMenu);
            $stmtMenu->bindParam(':app_id', $app_id);
            $stmtMenu->bindParam(':empno', $_SESSION['empno']);
            $stmtMenu->execute();
            $menuList = $stmtMenu->fetchAll(PDO::FETCH_ASSOC);
            $menuRow =  $stmtMenu->rowCount();

            // $data_base64 = base64_encode($queryMenu);
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

            if ($menuRow == 0) {
                echo '<h3 class"text-white">No Data</h3>';
            } else {
                foreach ($menuList as $row) {
                    $menuGrouped[$row['app_menu_parent_id']][$row['appmenuid']] = array($row['app_menu_title'], $row['app_menu_link']);
                }
                // print_r($menuGrouped);
                echo loadMenu($app_id, $menuGrouped, 0);
            }
            $BannerWebLive = null; //* ======== Close Connection ========
            break;
    }
}
