<?php
session_start();
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    //* Banner Web Database connection
    $BannerWebLive = $conn->db_conn_bannerweb();
    $action = trim($_POST['action']);
    date_default_timezone_set('Asia/Manila');

    switch ($action) {
        case 'loadAnnounceTable':
            ## Read Data
            $searchValue = $_POST['search']['value']; // Search value

            $col = array(
                0 => 'announce_id',
                1 => 'announce_date',
                2 => 'announce_by',
                3 => 'announce_header'
            );
            ### TOTAL RECORD ###
            $sqlstring = "SELECT announce_id,announce_date,CONCAT(emp_fn,' ',emp_sn) AS announce_by,announce_header FROM bpi_announcement
            INNER JOIN prl_employee ON prl_employee.empno = bpi_announcement.announce_by";
            $result_stmt = $BannerWebLive->prepare($sqlstring);
            $result_stmt->execute();
            $result_total_record = $result_stmt->rowCount();
            ## Fetch filtered Record
            $sqlstring = "SELECT announce_id,announce_date,CONCAT(emp_fn,' ',emp_sn) AS announce_by,announce_header FROM bpi_announcement
            INNER JOIN prl_employee ON prl_employee.empno = bpi_announcement.announce_by
            WHERE 1 = 1 ";
            ## Search 
            if (!empty($searchValue)) {
                $sqlstring .= "AND (announce_by ILIKE '%" . $searchValue . "%' OR announce_header ILIKE '%" . $searchValue . "%')";
            }
            $result_stmt = $BannerWebLive->prepare($sqlstring);
            $result_stmt->execute();
            $result_total_record_filtered = $result_stmt->rowCount();

            ## ======== Ordering ========
            $sqlstring .= " ORDER BY " . $col[$_POST['order'][0]['column']] . " " . $_POST['order'][0]['dir'] . " LIMIT " . $_POST['length'] . " OFFSET " . $_POST['start'];
            $result_stmt = $BannerWebLive->prepare($sqlstring);
            $result_stmt->execute();

            $resultData_List = array();

            while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
                $announce_id = $row['announce_id'];
                $btnAction = '<button type="button" class="btn btn-info" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Preview" onclick="btnPreview(\'' . $announce_id . '\');"><i class="fa-regular fa-pen-to-square"></i></button>
                <button type="button" class="btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete" onclick="btnDelete(\'' . $announce_id . '\');"><i class="fa-solid fa-trash-can"></i></button>';
                $nestedData = array();
                $nestedData[] = date_format(date_create($row['announce_date']), 'd-m-Y H:i:s A');
                $nestedData[] = $row['announce_by'];
                $nestedData[] = $row['announce_header'];
                $nestedData[] = $btnAction;
                $resultData_List[] = $nestedData;
            }
            ## Output Data
            $output = array(
                "draw"                   =>  intval($_POST["draw"]),
                'iTotalRecords'          =>  $result_total_record,
                'iTotalDisplayRecords'   =>  $result_total_record_filtered,
                'data'                   =>  $resultData_List
            );
            ## Send Data as JSON Format
            echo json_encode($output);
            ## CLOSE CONNECTION
            $BannerWebLive = null;
            break;

        case 'load-image-base64':
            $image_data = $_POST['image'];
            $image_array_1 = explode(";", $image_data);
            $image_array_2 = explode(",", $image_array_1[1]);
            $image_data = base64_decode($image_array_2[1]);
            $photo_base64 = base64_encode($image_data);
            echo $photo_base64;
            ## CLOSE CONNECTION
            $BannerWebLive = null;
            break;
        case 'btnAnnounceSaveFunction':
            $by = trim($_POST['by']);
            $header_announce = trim($_POST['header_announce']);
            $body_announce = trim($_POST['body_announce']);
            $user_image = trim($_POST['user_image']);
            $date = date("Y-m-d H:i:s");
            // $query = "SELECT * FROM prl_employee WHERE empno = '".$by."'";
            // $result_stmt = $BannerWebLive->prepare($query);
            // $result_stmt->execute();
            // $result_res = $result_stmt->fetch(PDO::FETCH_ASSOC);
            // $fullname = $result_res['emp_fn'] . ' ' . $result_res['emp_sn'];
            // echo $user_image;
            $sqlstring = "INSERT INTO bpi_announcement(announce_date,announce_by,announce_header,announce_body,announce_image)
            VALUES('" . $date . "','" . $by . "','" . $header_announce . "','" . $body_announce . "','" . $user_image . "')";
            $result_stmt = $BannerWebLive->prepare($sqlstring);
            $result_stmt->execute();
            ## CLOSE CONNECTION
            $BannerWebLive = null;
            break;
        case 'btnPreviewFunctions':
            $id = trim($_POST['id']);
            $sqlstring = "SELECT announce_by,announce_header,announce_body,encode(announce_image, 'escape') as user_pic FROM bpi_announcement WHERE announce_id = '" . $id . "'";
            $result_stmt = $BannerWebLive->prepare($sqlstring);
            $result_stmt->execute();
            $preview = array();
            while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
                $preview["announce_header"] = $row['announce_header'];
                $preview["announce_body"] = $row['announce_body'];
                $preview["image"] = $row['user_pic'] == '' ? '<img src="../vendor/images/announcement.jpg" alt="" id="add_announcement_image">' : '<img src="data:image/jpeg;base64,' . $row['user_pic'] . '" value="' . $row['user_pic'] . '" id="add_announcement_image">';
            }
            echo json_encode($preview);
            ## CLOSE CONNECTION
            $BannerWebLive = null;
            break;
        case 'btnDeleteFunction':
            $id = trim($_POST['id']);
            $sqlstring = "DELETE FROM bpi_announcement WHERE announce_id = '" . $id . "'";
            $result_stmt = $BannerWebLive->prepare($sqlstring);
            $result_stmt->execute();
            ## CLOSE CONNECTION
            $BannerWebLive = null;
            break;
        case 'loadProfileFunction':
            $username = trim($_POST['username']);
            $sqlstring = "SELECT encode(user_image, 'escape') as user_pic FROM bpi_user_accounts WHERE empno = '" . $username . "'";
            $result_stmt = $BannerWebLive->prepare($sqlstring);
            $result_stmt->execute();
            $preview = array();
            while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
                $preview["image"] = $row['user_pic'] == '' ? '<img src="../vendor/images/blank-profile-picture.png" alt="" id="profile_image">' : '<img src="data:image/jpeg;base64,' . $row['user_pic'] . '" value="' . $row['user_pic'] . '" id="profile_image">';
            }
            echo json_encode($preview);
            ## CLOSE CONNECTION
            $BannerWebLive = null;
            break;
        case 'btnAnnounceUpdateFunction':
            $prev_header = trim($_POST['prev_header']);
            $prev_body = trim($_POST['prev_body']);
            $by = trim($_POST['by']);
            $previewVal = trim($_POST['previewVal']);
            $user_image = trim($_POST['user_image']);
            if (strlen($user_image) == 0) {
                $sqlstring = "SELECT encode(announce_image, 'escape') as user_pic FROM bpi_announcement WHERE announce_id = '" . $previewVal . "'";
                $result_stmt = $BannerWebLive->prepare($sqlstring);
                $result_stmt->execute();
                $result_res = $result_stmt->fetch(PDO::FETCH_ASSOC);
                $image = $result_res['user_pic'];
                $sqlstring = "UPDATE bpi_announcement SET announce_header = '" . $prev_header . "',announce_body = '" . $prev_body . "',announce_image = '" . $image . "' WHERE announce_id = '" . $previewVal . "'";
                $result_stmt = $BannerWebLive->prepare($sqlstring);
                $result_stmt->execute();
            } else {
                $sqlstring = "UPDATE bpi_announcement SET announce_header = '" . $prev_header . "',announce_body = '" . $prev_body . "',announce_image = '" . $user_image . "' WHERE announce_id = '" . $previewVal . "'";
                $result_stmt = $BannerWebLive->prepare($sqlstring);
                $result_stmt->execute();
            }
            ## CLOSE CONNECTION
            $BannerWebLive = null;
            break;
    }
}
