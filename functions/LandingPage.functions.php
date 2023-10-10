<?php
include_once '../configuration/connection.php';
$BannerWebLive = $conn->db_conn_bannerweb(); //* Banner Web Database connection

session_start();
if (isset($_POST['action'])) {
    $action = trim($_POST['action']);
    date_default_timezone_set('Asia/Manila');
    $date = date('Y-m-d');

    //* Add Thumbnails for application if new
    function applicationCardIcon($handler)
    {
        switch ($handler) {
            case 'IT Asset':
                $result = '<img src="vendor/images/itasset.gif" class="nav-icon" width="120" height="120" style="border-radius: 10px;">';
                break;
            case 'Administrator':
                $result = '<img src="vendor/images/administrator.gif" class="nav-icon" width="120" height="120" style="border-radius: 10px;">';
                break;
            case 'Physical Security':
                $result = '<img src="vendor/images/physicalSecurity.gif" class="nav-icon" width="120" height="120" style="border-radius: 10px;">';
                break;
            case 'Perso Monitoring':
                $result = '<img src="vendor/images/personalization.gif" class="nav-icon" width="120" height="120"style="border-radius: 10px;">';
                break;
            case 'Info Security':
                $result = '<img src="vendor/images/cybersecurity.gif" class="nav-icon" width="120" height="120" style="border-radius: 10px;">';
                break;
            case 'Prod Monitoring':
                $result = '<img src="vendor/images/manufacturing.gif" class="nav-icon" width="120" height="120" style="border-radius: 10px;">';
                break;
            case 'IMS Express':
                $result = '<img src="vendor/images/imsxpress.gif" class="nav-icon" width="120" height="120" style="border-radius: 10px;">';
                break;
            case 'IT Repair and Request':
                $result = '<img src="vendor/images/support.gif" class="nav-icon" width="120" height="120" style="border-radius: 10px;">';
                break;
            case 'Human Resources':
                $result = '<img src="vendor/images/human_resources.gif" class="nav-icon" width="120" height="120" style="border-radius: 10px;">';
                break;
            default:
                $result = '<i class="fa-brands fa-microsoft nav-icon"></i>';
                break;
        }
        return $result;
    }

    function loadNewsFeedFunction($countAdd, $load, $BannerWebLive)
    {
        $sqlstring = "SELECT announce_id, announce_date, announce_by, announce_header, announce_body, encode(announce_image, 'escape') as upload_pic 
        FROM bpi_announcement ORDER BY announce_date DESC ";
        if ($load == 'seeAndLess') {
            $sqlstring .= "LIMIT $countAdd";
        } else {
            $sqlstring .= "LIMIT 10";
        }
        $stmt = $BannerWebLive->prepare($sqlstring);
        $stmt->execute();
        $res_stmt = $stmt->fetchAll();
        $dataCount = $stmt->rowCount();

        // $data_base64 = base64_encode($sqlstring);
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
        // $res_stmt = json_decode($json_response, true);
        // $dataCount = array_sum(array_map("count", $res_stmt));

        if ($dataCount == 0) {
            echo '<span class="fw-bold fs-20 text-danger" style="text-align: center; margin: auto;">Currently No Announcement</span>';
        } else {
            foreach ($res_stmt as $row) {
                $current_date = strtotime(date("Y-m-d H:i:s"));
                $past_date = strtotime($row['announce_date']);
                $minutes = round(abs($past_date - $current_date) / 60);
                $hours = (intval($minutes) / 60);
                $days = (intval($hours) / 24);
                if (floor($minutes) == 0) {
                    $post_date = " Just Now ";
                } else if (floor($minutes) >= 1 && floor($minutes) <= 59) {
                    $post_date = floor($minutes) < 2 ? 'A minute Ago' : floor($minutes) . " Minutes Ago ";
                } else if (floor($minutes) >= 60 && floor($minutes) <= 1439) {
                    $post_date = floor($hours) < 2  ? 'An hour Ago' : floor($hours) . " hrs Ago ";
                } else if (floor($minutes) >= 1440 && floor($minutes) <= 10079) {
                    $post_date = floor($days) < 2 ? 'A day Ago' : floor($days) . " Days Ago ";
                } else {
                    $post_date = date_format(date_create($row['announce_date']), 'd F Y');
                }
                $html = '
                        <div class="card mb-4 shadow border-0 w-100" onclick="btnThumb(\'' . $row['announce_id'] . '\');">
                            <div class="row g-0">
                                <div class="col-md-4">';
                if ($row['upload_pic'] == '') {
                    $html .= '<img src="vendor/images/announcement.jpg" class="img-fluid h-100 rounded-start" alt="...">';
                } else {
                    $html .= '<img src="data:image/jpeg;base64,' . $row['upload_pic'] . '" value="' . $row['upload_pic'] . '" class="img-fluid h-100 rounded-start" alt="...">';
                }
                $html .= '</div>
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <h2 class ="card-title text-warning fw-bold" style="display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden">' . $row['announce_header'] . '</h2>
                                        <p class ="card-text"><small class="text-muted">' . $post_date . '</small></p>
                                        <p class="mt-1 fs-15 two-line-ellipsis" style="display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden">' . $row['announce_body'] . '</p><br>
                                        <span class="fs-10 fw-bold text-secondary">Banner Placticard Inc. </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        ';

                echo $html;
            }
            echo '<a href="#" class="to-top">
                        <i class="fas fa-chevron-up"></i>
                    </a>';
            if ($dataCount < 10) {
                echo '';
            } else if ($dataCount < $countAdd) {
                echo '
                <div class="row allignCenterSeeMore">
                    <span class="seeMoreSeeless d-flex justify-content-center fw-bold fs-20" onclick="seeLessFunction();">See Less</span>
                </div>';
            } else {
                echo '
                <div class="row allignCenterSeeMore">
                    <span class="seeMoreSeeless  d-flex justify-content-center fw-bold fs-20" onclick="seeMoreFunction();">See More</span>
                </div>
                   ';
            }
        }
    }
    switch ($action) {
        case 'loadAccessApp':
            $department = trim($_POST['department']);
            $queryAccessLevel = "SELECT DISTINCT app_name, app_link, appid FROM bpi_access_module 
                INNER JOIN bpi_app_menu ON bpi_app_menu.appid = bpi_access_module.app_id
                INNER JOIN bpi_user_accounts ON bpi_user_accounts.empno = bpi_access_module.access_user 
                WHERE empno = :empno ORDER BY app_name";
            $stmtAccessLevel = $BannerWebLive->prepare($queryAccessLevel);
            $stmtAccessLevel->bindParam(':empno', $_SESSION['empno']);
            $stmtAccessLevel->execute();
            $result_row = $stmtAccessLevel->fetchAll();
            $result_count =  $stmtAccessLevel->rowCount();

            // $data_base64 = base64_encode($queryAccessLevel);
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

            $nav_color = 0;
            $card_count = 0;
            $result = "";

            $queryFullName = "SELECT DISTINCT CONCAT(emp_fn,' ',emp_sn) AS fullname, pos_name FROM prl_employee 
                INNER JOIN bpi_user_accounts ON bpi_user_accounts.empno = prl_employee.empno
                INNER JOIN prl_position ON prl_position.pos_code = prl_employee.pos_code
                WHERE bpi_user_accounts.empno = :empno";
            $stmtFullName = $BannerWebLive->prepare($queryFullName);
            $stmtFullName->bindParam(':empno', $_SESSION['empno']);
            $stmtFullName->execute();
            $resultFullName = $stmtFullName->fetch(PDO::FETCH_ASSOC);
            $fullName = $resultFullName['fullname'];
            $jobTitle = $resultFullName['pos_name'];
            // $data_base64 = base64_encode($queryFullName);
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
            // $resultFullName = json_decode($json_response, true);

            // ========= STRETCH COLUMN IF SINGLE APP =========
            if ($result_count == 1) {
                $col_number_apps = 'col-12 col-sm-12 col-md-12';
                $card_count++;
            } else {
                $col_number_apps = 'col';
            }

            // ========= DISPLAY APPS =========
            foreach ($result_row as $row) {
                $nav_color = fmod(++$nav_color, 6) != 0 ? fmod($nav_color, 6) : 6;
                $result .= '<div class="' . $col_number_apps . '">
                                <a href="' . $row['app_link'] . '?app_id=' . $row['appid'] . '">
                                    <div class="nav-box nav-' . $nav_color . ' px-5">
                                        <h2 class="nav-title">' . $row['app_name'] . '</h2>
                                        ' .  applicationCardIcon($row['app_name']) . '
                                    </div>
                                </a>
                            </div>';
                $card_count++;
            }

            // ========= STRETCH PROFILE COLUMN IF APPS ARE ODD =========
            $col_number_profile = fmod($card_count, 2) == 0 ? 'col-12 col-sm-12 col-md-12' : 'col';
            // ? Total Count Notification
            $sqlstringcategory = "SELECT *, 'request' AS category FROM bpi_notification_module WHERE table_database IN ('it_repair_request','info_security','itassetdb_new') AND prepared_by = ?
                     UNION ALL
                     SELECT *, 'approved' AS category FROM bpi_notification_module WHERE table_database IN ('it_repair_request','info_security','itassetdb_new') AND table_name IN ('tblit_request','info_sec_web_app_request','tblit_user_access_request') AND approved_by = ?
                     UNION ALL
                     SELECT *, 'noted' AS category FROM bpi_notification_module WHERE table_database IN ('it_repair_request','info_security','itassetdb_new') AND table_name IN ('tblit_request','info_sec_web_app_request','tblit_user_access_request') AND noted_by = ?
                     ORDER BY prepared_by_date DESC";
            $result_stmt_categ = $BannerWebLive->prepare($sqlstringcategory);
            $result_stmt_categ->execute([$_SESSION['fullname'], $_SESSION['fullname'], $_SESSION['fullname']]);
            $countReceivedNotif = 0;
            $countRequestNotif = 0;
            $countApprovedNotif = 0;
            $countNotedNotif = 0;
            while ($row = $result_stmt_categ->fetch(PDO::FETCH_ASSOC)) {
                $category = $row['category'];
                $table_name = $row['table_name'];
                $prepared_acknowledge = $row['prepared_by_acknowledge'];
                $approved_acknowledge = $row['approved_by_acknowledge'];
                $noted_acknowledge = $row['noted_by_acknowledge'];
                $repair_by_acknowledge = $row['repair_by_acknowledge'];
                switch ($category) {
                    case 'request':
                        if ($table_name == 'tblit_repair') {
                            if ($prepared_acknowledge == 0) {
                                $countRequestNotif++;
                            }
                        } else if ($table_name == 'tblit_request') {
                            if ($approved_acknowledge == true && $noted_acknowledge == true && $repair_by_acknowledge == true && $prepared_acknowledge == false) {
                                $countRequestNotif++;
                            }
                        } else if ($table_name == 'info_sec_web_app_request') {
                            if ($approved_acknowledge == true && $noted_acknowledge == true && $repair_by_acknowledge == true && $prepared_acknowledge == false) {
                                $countRequestNotif++;
                            }
                        } else if ($table_name == 'tblit_user_access_request') {
                            if ($approved_acknowledge == true && $noted_acknowledge == true && $prepared_acknowledge == false) {
                                $countRequestNotif++;
                            }
                        }
                        break;
                    case 'approved':
                        if ($approved_acknowledge == 0) {
                            $countApprovedNotif++;
                        }
                        break;
                    case 'noted':
                        if ($noted_acknowledge == 0 && $approved_acknowledge == 1) {
                            $countNotedNotif++;
                        }
                }
            }
            $sqlstringCategoryPhd = "SELECT *, 'request' AS category FROM bpi_notification_module WHERE table_database = 'physical_security' AND prepared_by = ?
                    UNION ALL
                    SELECT *, 'checked' AS category FROM bpi_notification_module WHERE table_database = 'physical_security' AND table_name = 'phd_time_sync_log_header' AND checked_by = ?
                    UNION ALL
                    SELECT *, 'noted' AS category FROM bpi_notification_module WHERE table_database = 'physical_security' AND table_name IN ('phd_time_sync_log_header','phd_event_monitoring_header') AND noted_by = ?
                    ORDER BY prepared_by_date DESC";
            $result_stmt_categ_phd = $BannerWebLive->prepare($sqlstringCategoryPhd);
            $result_stmt_categ_phd->execute([$_SESSION['fullname'], $_SESSION['fullname'], $_SESSION['fullname']]);
            $countRequestNotifPhd = 0;
            $countCheckedNotifPhd = 0;
            $countNotedNotifPhd = 0;
            while ($row = $result_stmt_categ_phd->fetch(PDO::FETCH_ASSOC)) {
                $category = $row['category'];
                $table_name = $row['table_name'];
                $prepared_acknowledge_phd = $row['prepared_by_acknowledge'];
                $checked_acknowledge_phd = $row['checked_by_acknowledge'];
                $approved_acknowledge_phd = $row['approved_by_acknowledge'];
                $noted_acknowledge_phd = $row['noted_by_acknowledge'];
                switch ($category) {
                    case 'request':
                        if ($table_name == 'phd_time_sync_log_header') {
                            if ($checked_acknowledge_phd == true && $noted_acknowledge_phd == true && $prepared_acknowledge_phd == false) {
                                $countRequestNotifPhd++;
                            }
                        }
                        if ($table_name == 'phd_event_monitoring_header') {
                            if ($approved_acknowledge_phd == true && $noted_acknowledge_phd == true && $prepared_acknowledge_phd == false) {
                                $countRequestNotifPhd++;
                            }
                        }
                        break;
                    case 'checked':
                        if ($checked_acknowledge_phd == 0) {
                            $countCheckedNotifPhd++;
                        }
                        break;
                    case 'noted':
                        if ($table_name == 'phd_event_monitoring_header') {
                            if ($noted_acknowledge_phd == 0 && $approved_acknowledge_phd == 1) {
                                $countNotedNotifPhd++;
                            }
                        } else {
                            if ($noted_acknowledge_phd == 0 && $checked_acknowledge_phd == 1) {
                                $countNotedNotifPhd++;
                            }
                        }
                }
            }
            $sqlstringCancel = "SELECT COUNT(*) AS total_cancel_requests
                                FROM bpi_notification_module
                                WHERE cancel_status = true
                                AND (prepared_by = ?
                                OR approved_by = ?
                                OR noted_by = ?);";
            $result_stmt_cancel = $BannerWebLive->prepare($sqlstringCancel);
            $result_stmt_cancel->execute([$_SESSION['fullname'], $_SESSION['fullname'], $_SESSION['fullname']]);
            $result_res_cancel = $result_stmt_cancel->fetchAll();
            foreach ($result_res_cancel as $row) {
                $total_cancel_request = $row['total_cancel_requests'];
            }
            // ? It Repair and Request
            $totalCountItr = $countRequestNotif + $countApprovedNotif + $countNotedNotif + $countReceivedNotif;
            // ? Physical Security
            $totalCountPhd = $countRequestNotifPhd + $countCheckedNotifPhd + $countNotedNotifPhd;
            $totalCount = $totalCountItr + $totalCountPhd - $total_cancel_request;
            $zeroCount = $totalCount == 0 ? '' : $totalCount . '+';
            $result .= '
                <div class="' . $col_number_profile . '">
                    <div class="nav-box nav-user px-5">
                     <h3 class="nav-title">' . $fullName . '
                        <p class="fs-6">' . $jobTitle . '</p>
                     </h3>
                     
                        <a href="user_dashboard/user_dashboard.php">
                            <i>
                                <img class=" nav-user-image" height="125" width="125" src="data:image/jpeg;base64,' . $_SESSION['user_image'] . '">
                            </i>
                        </a>
                        <div class="user-setting-panel">
                            <a href="#" onclick="maintenance();">
                                <i class="fa-solid fa-message" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Messages"></i>
                            </a>
                            <a href="user_dashboard/notification_module.php"> 
                            <i class="fa-solid fa-bell position-relative" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Notifications">
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger fa-fade total-count-landing-page">' . $zeroCount . '</span>
                            </i>
                            </a>
                            <a href="user_dashboard/user_dashboard.php">
                                <i class="fa-solid fa-user" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Profile"></i>
                            </a>
                            <a href="functions/logout.php">
                                <i class="fa-solid fa-arrow-right-from-bracket" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Logout"></i>
                            </a>                        
			            </div>
                    </div>
                </div>';
            echo $result;
            $BannerWebLive = null; //* ======== Close Connection ========
            break;

        case 'loadMenu':
            $app_name = trim($_POST['app_name']);
            $query = "SELECT app_menu_title 
                        FROM bpi_app_menu_module 
                        INNER JOIN bpi_app_menu
                            ON bpi_app_menu.appid = bpi_app_menu_module.app_id
                        WHERE app_name = '{$app_name}'";
            $stmt = $BannerWebLive->prepare($query);
            $stmt->execute();
            $result_row = $stmt->fetchAll();

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

            foreach ($result_row as $row) {
                echo $row['menu_title'] . "\n";
            }
            break;

        case 'loadNewsFeedFunctions':
            $sqlstring = "SELECT announce_id, announce_date, announce_by, announce_header, announce_body, encode(announce_image, 'escape') AS upload_pic 
                FROM bpi_announcement ORDER BY announce_date DESC";
            $stmt = $BannerWebLive->prepare($sqlstring);
            $stmt->execute();
            $dataCount = $stmt->rowCount();

            // $data_base64 = base64_encode($sqlstring);
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
            // $dataCount = array_sum(array_map("count", $result_row));

            if ($dataCount == 0) {
                echo '<span class="fw-bold fs-20 text-secondary" style="text-align: center; margin: auto;">Currently No Announcement</span>';
            } else {
                echo loadNewsFeedFunction(0, 'onLoad', $BannerWebLive);
            }
            break;

        case 'seeMoreAndLessFunction':
            $seeChoice = trim($_POST['seeChoice']);
            $sqlstring = "SELECT announce_id, announce_date, announce_by, announce_header, announce_body, encode(announce_image, 'escape') as upload_pic FROM bpi_announcement ORDER BY announce_date DESC";
            $stmt = $BannerWebLive->prepare($sqlstring);
            $stmt->execute();
            $dataCount = $stmt->rowCount();

            // $data_base64 = base64_encode($sqlstring);
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
            // $dataCount = array_sum(array_map("count", $result_row));

            if ($dataCount > 0) {
                switch ($seeChoice) {
                    case 'more':
                        $countAdd = trim($_POST['count']);
                        break;
                    case 'less':
                        $countAdd = 10;
                        break;
                }
                $load = 'seeAndLess';
                echo loadNewsFeedFunction($countAdd, $load, $BannerWebLive);
            }
            break;

        case 'btnThumbFunc':
            $id = trim($_POST['id']);
            $sqlstring = "SELECT announce_date,CONCAT(emp_fn,' ',emp_sn) AS announce_by,announce_header,announce_body,encode(announce_image, 'escape') AS upload_pic FROM bpi_announcement
                    INNER JOIN prl_employee ON prl_employee.empno = bpi_announcement.announce_by 
                    WHERE announce_id = '" . $id . "'";
            $stmt = $BannerWebLive->prepare($sqlstring);
            $stmt->execute();
            $result_Res = $stmt->fetchAll();

            // $data_base64 = base64_encode($sqlstring);
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
            // $result_Res = json_decode($json_response, true);

            foreach ($result_Res as $row) {
                $current_date = strtotime(date("Y-m-d H:i:s"));
                $past_date = strtotime($row['announce_date']);
                $minutes = round(abs($past_date - $current_date) / 60);
                $hours = (intval($minutes) / 60);
                $days = (intval($hours) / 24);
                if (floor($minutes) == 0) {
                    $post_date = " Just Now ";
                } else if (floor($minutes) >= 1 && floor($minutes) <= 59) {
                    $post_date = floor($minutes) < 2 ? 'A minute Ago' : floor($minutes) . " Minutes Ago ";
                } else if (floor($minutes) >= 60 && floor($minutes) <= 1439) {
                    $post_date = floor($hours) < 2  ? 'An hour Ago' : floor($hours) . " hrs Ago ";
                } else if (floor($minutes) >= 1440 && floor($minutes) <= 10079) {
                    $post_date = floor($days) < 2 ? 'A day Ago' : floor($days) . " Days Ago ";
                } else {
                    $post_date = date_format(date_create($row['announce_date']), 'd F Y');
                }
                $html = '
                        <div class="announce-body mb-3">
                            <div class="row mt-3 mb-3 announcement-container-modal">
                                <span class="fs-35 fw-bold">' . $row['announce_header'] . '</span>
                                <span class="fs-20 mt-3 fw-bold">' . $row['announce_by'] . '<span class="text-secondary announce-body-modal fs-15">' . $post_date . '</span></span>
                            </div>
                            <div class="row bg-dark announcement-container-modal">
                                <div class="d-flex justify-content-center image-announcement-container">';
                if ($row['upload_pic'] == '') {
                    $html .= '<img src="vendor/images/announcement.jpg" alt="" id="post_image">';
                } else {
                    $html .= '<img src="data:image/jpeg;base64,' . $row['upload_pic'] . '" value="' . $row['upload_pic'] . '" alt="" id="post_image">';
                }
                $html .=   '</div>
                            </div>
                            <div class="row mt-3 announce-body-modal">
                                <span class="mt-3 mb-5 fs-25 text-secondary">' . $row['announce_body'] . '</span>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center">
                            <span class="fs-10 fw-bold text-secondary">� 2022 Banner Plasticard, Inc. All rights reserved.</span>
                        </div>';
                echo $html;
            }
            break;
    }
}
