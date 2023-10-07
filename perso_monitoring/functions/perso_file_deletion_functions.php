<?php
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    $email_logs = $conn->db_conn_email_logs(); //* email_logs Database connection
    $action = trim($_POST['action']);
    date_default_timezone_set('Asia/Manila');
    $currentDate = date('Y-m-d');

    switch ($action) {
        case 'load_file_list_table':
            //* ======== Read Data ========
            $searchValue = $_POST['search']['value'];
            $resultData_List = array();
            //* ======== Create Array for column same with column names on database for ordering ========
            $col = array(
                0 => 'received_date',
                1 => 'filename',
                2 => 'filesize',
                3 => 'company',
                4 => 'delivered_date',
                5 => 'deletion_date'
            );
            //* ======== Fetch Data ========
            $sqlstring = "SELECT notifid, received_date, filename, filesize, company, delivered_date, (CASE WHEN TO_CHAR(deletion_date,'YYYY-MM-DD') ISNULL THEN 'N/A' ELSE TO_CHAR(deletion_date,'YYYY-MM-DD') END) AS deletion_date, for_deletion 
                FROM bpi_perso_notification";
            $result_stmt = $email_logs->prepare($sqlstring);
            $result_stmt->execute();
            $result_total_record = $result_stmt->rowCount();
            //* ======== Search ========
            $sqlstring = "SELECT notifid, received_date, filename, filesize, company, delivered_date, (CASE WHEN TO_CHAR(deletion_date,'YYYY-MM-DD') ISNULL THEN 'N/A' ELSE TO_CHAR(deletion_date,'YYYY-MM-DD') END) AS deletion_date, for_deletion 
                FROM bpi_perso_notification WHERE 1 = 1 ";
            if (!empty($searchValue)) {
                $sqlstring .= "AND (TO_CHAR(received_date, 'YYYY-MM-DD') ILIKE '%" . $searchValue . "%' OR filename ILIKE '%" . $searchValue . "%' OR filesize ILIKE '%" . $searchValue . "%' 
                        OR company ILIKE '%" . $searchValue . "%' OR TO_CHAR(delivered_date, 'YYYY-MM-DD') ILIKE '%" . $searchValue . "%' OR TO_CHAR(deletion_date, 'YYYY-MM-DD') ILIKE '%" . $searchValue . "%') ";
            }
            $result_stmt = $email_logs->prepare($sqlstring);
            $result_stmt->execute();
            $result_total_record_filtered = $result_stmt->rowCount();

            //* ======== Ordering ========
            $sqlstring .= " ORDER BY " . $col[$_POST['order'][0]['column']] . " " . $_POST['order'][0]['dir'] . " LIMIT " . $_POST['length'] . " OFFSET " . $_POST['start'] . "";
            $result_stmt = $email_logs->prepare($sqlstring);
            $result_stmt->execute();
            //* ======== Prepare Array ========
            while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
                $nestedData = array();
                $nestedData[] = $row['received_date'];
                $nestedData[] = $row['filename'];
                $nestedData[] = $row['filesize'];
                $nestedData[] = $row['company'];
                $nestedData[] = $row['delivered_date'];
                $nestedData[] = $row['deletion_date'];
                $nestedData[] = array($row['for_deletion'], $row["notifid"]);
                $resultData_List[] = $nestedData;
            }
            //* ======== Output Data ========
            $output = array(
                'draw'                  =>  intval($_POST['draw']),
                'iTotalRecords'         =>  $result_total_record,
                'iTotalDisplayRecords'  =>  $result_total_record_filtered,
                'data'                  =>  $resultData_List
            );
            echo json_encode($output); //* ======== Send Data as JSON Format ========
            $email_logs = null; //* ======== Close Connection ========
            break;

        case 'load_company_selection':
            $itemData_List = array();

            $sqlstring = "SELECT DISTINCT company FROM bpi_perso_notification";
            $result_stmt = $email_logs->prepare($sqlstring);
            $result_stmt->execute();
            if ($result_stmt->rowCount() > 0) {
                foreach ($result_stmt->fetchAll() as $row) {
                    $itemData_List[$row['company']] = $row['company'];
                }
            }
            $itemData_List ??= null;
            echo json_encode($itemData_List);
            $email_logs = null; //* ======== Close Connection ========
            break;

        case 'load_referrence_no':
            $dateFrom = trim($_POST['date_from']);
            $dateTo = trim($_POST['date_to']);
            $company = trim($_POST['company']);
            $chkReceivedDate = trim($_POST['chkReceivedDate']);
            $chkDeletionDate = trim($_POST['chkDeletionDate']);

            $sqlstring = "SELECT DISTINCT referrence_no FROM bpi_perso_notification WHERE company ilike '%" . $company . "%'";
            if ($chkReceivedDate == 'true') {
                $sqlstring .= " AND received_date BETWEEN '" . $dateFrom . "' AND '" . $dateTo . "' ";
            } else if ($chkDeletionDate == 'true') {
                $sqlstring .= " AND deletion_date BETWEEN '" . $dateFrom . "' AND '" . $dateTo . "' ";
            } else {
                $sqlstring .= " AND delivered_date BETWEEN '" . $dateFrom . "' AND '" . $dateTo . "' ";
            }
            $sqlstring .= "AND for_deletion = true ORDER BY referrence_no DESC";
            $result_stmt = $email_logs->prepare($sqlstring);
            $result_stmt->execute();
            $result_res = $result_stmt->fetchAll();
            echo '<option value="">Choose...</option>';
            foreach ($result_res as $row) {
                echo '<option value="' . $row['referrence_no'] . '">' . $row['referrence_no'] . '</option>';
            }
            $email_logs = null; //* ======== Close Connection ========
            break;

        case 'record_check':
            $category = trim($_POST['category']);
            $company = trim($_POST['company']);

            switch ($category) {
                case 'monthly':
                    $month_date = trim($_POST['month_date']);
                    $sqlstring = "SELECT * FROM bpi_perso_notification WHERE TO_CHAR(received_date, 'MM YYYY') BETWEEN '" . $month_date . "' AND '" . $month_date . "' AND company ILIKE '" . $company . "' ORDER BY received_date ASC";
                    break;
                case 'quarterly':
                    $dateFrom = trim($_POST['date_from']);
                    $dateTo = trim($_POST['date_to']);
                    $sqlstring = "SELECT * FROM bpi_perso_notification WHERE TO_CHAR(received_date, 'MM YYYY') BETWEEN '" . $dateFrom . "' AND '" . $dateTo . "' AND company ilike '%" . $company . "%' ORDER BY received_date ASC";
                    break;
            }
            $result_chkExisting_stmt = $email_logs->prepare($sqlstring);
            $result_chkExisting_stmt->execute();

            if ($result_chkExisting_stmt->rowCount() > 0) {
                echo 'existing';
            } else {
                echo 'no record';
            }
            $email_logs = null; //* ======== Close Connection ========
            break;

        case 'load_for_deletion_count':
            $sqlstring = "SELECT * FROM bpi_perso_notification WHERE for_deletion = false";
            $result_stmt = $email_logs->prepare($sqlstring);
            $result_stmt->execute();
            echo $result_stmt->rowCount(); //* ====== Display Count ======
            $email_logs = null; //* ======== Close Connection ========
            break;

        case 'gen_referrence_no':
            $sqlstring = "SELECT ref_no FROM bpi_perso_notif_ref_no";
            $result_stmt = $email_logs->prepare($sqlstring);
            $result_stmt->execute();
            $result_row = $result_stmt->fetch(PDO::FETCH_ASSOC);
            $referrence_no = $result_row['ref_no'];

            $currYear = date('y');
            $getYear =  substr($referrence_no, 5, 2);

            if ($currYear != $getYear) {
                $ref_no = '0001-' . $currYear;
            } else {
                $currCount = substr($referrence_no, 0, 4);
                $counter = intval($currCount) + 1;
                $ref_no = str_pad($counter, 4, '0', STR_PAD_LEFT) . '-' . $currYear;
            }
            echo $ref_no;
            $email_logs = null; //* ======== Close Connection ========
            break;

        case 'load_for_deletion_data':
            //* ======== Read Data ========
            $dateFrom = trim($_POST['date_from']);
            $dateTo = trim($_POST['date_to']);
            $company = trim($_POST['company']);
            $dateFilter = trim($_POST['dateFilter']);
            $resultData_List = array();
            //* ======== Create Array for column same with column names on database for ordering ========
            $col = array(
                0 => 'notifid',
                1 => 'filename',
                2 => 'filesize',
                3 => 'received_date',
                4 => 'delivered_date'
            );
            //* ======== Fetch Data ========
            if ($dateFilter == 'true') {
                $date_category = 'received_date';
            } else {
                $date_category = 'delivered_date';
            }
            $sqlstring = "SELECT * FROM bpi_perso_notification WHERE " . $date_category . " BETWEEN :date_from AND :date_to 
                AND company = :company AND for_deletion = false";
            $result_stmt = $email_logs->prepare($sqlstring);
            $result_stmt->bindParam(':date_from', $dateFrom);
            $result_stmt->bindParam(':date_to', $dateTo);
            $result_stmt->bindParam(':company', $company);
            $result_stmt->execute();
            $result_total_record = $result_stmt->rowCount();

            //* ======== Search ========
            $sqlstring = "SELECT * FROM bpi_perso_notification WHERE 1 = 1 AND " . $date_category . " BETWEEN :date_from AND :date_to 
                AND company = :company AND for_deletion = false";
            $result_stmt = $email_logs->prepare($sqlstring);
            $result_stmt->bindParam(':date_from', $dateFrom);
            $result_stmt->bindParam(':date_to', $dateTo);
            $result_stmt->bindParam(':company', $company);
            $result_stmt->execute();
            $result_total_record_filtered = $result_stmt->rowCount();
            //* ======== Ordering ========
            $sqlstring .= " ORDER BY " . $col[$_POST['order'][0]['column']] . " " . $_POST['order'][0]['dir'] . " LIMIT " . $_POST['length'] . " OFFSET " . $_POST['start'] . "";
            $result_stmt = $email_logs->prepare($sqlstring);
            $result_stmt->bindParam(':date_from', $dateFrom);
            $result_stmt->bindParam(':date_to', $dateTo);
            $result_stmt->bindParam(':company', $company);
            $result_stmt->execute();
            //* ======== Prepare Array ========
            while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
                $nestedData = array();
                $nestedData[] = '<input type="checkbox" class="rowChkBox">';
                $nestedData[] = $row['filename'];
                $nestedData[] = $row['filesize'];
                $nestedData[] = $row['received_date'];
                $nestedData[] = $row['delivered_date'];
                $resultData_List[] = $nestedData;
            }
            //* ======== Output Data ========
            $output = array(
                'draw'                  =>  intval($_POST['draw']),
                'iTotalRecords'         =>  $result_total_record,
                'iTotalDisplayRecords'  =>  $result_total_record_filtered,
                'data'                  =>  $resultData_List
            );
            echo json_encode($output); //* ======== Send Data as JSON Format ========
            $email_logs = null; //* ======== Close Connection ========
            break;

        case 'save_file_deletion':
            $company = trim($_POST['company']);
            $dateFrom = trim($_POST['date_from']);
            $dateTo = trim($_POST['date_to']);
            $deletionDate = trim($_POST['deletion_date']);
            $deleted_by = trim($_POST['deleted_by']);
            $witnessed_by = trim($_POST['witnessed_by']);
            $certified_by = trim($_POST['certified_by']);
            $dateFilter = trim($_POST['dateFilter']);
            $ref_no = trim($_POST['referrence_no']);
            $filename = "%" . trim($_POST['filename']) . "%";

            if ($dateFilter == 'true') {
                $date_category = 'received_date';
            } else {
                $date_category = 'delivered_date';
            }
            $sqlstring = "UPDATE bpi_perso_notification SET for_deletion = true, deletion_date = :deletion_date, deleted_by = :deleted_by, witnessed_by = :witnessed_by,
                certified_by = :certified_by, referrence_no = :referrence_no WHERE " . $date_category . " BETWEEN :date_from AND :date_to
                AND company = :company AND filename ILIKE '" . $filename . "'";
            $result_stmt = $email_logs->prepare($sqlstring);
            $result_stmt->bindParam(':deletion_date', $deletionDate);
            $result_stmt->bindParam(':deleted_by', $deleted_by);
            $result_stmt->bindParam(':witnessed_by', $witnessed_by);
            $result_stmt->bindParam(':certified_by', $certified_by);
            $result_stmt->bindParam(':referrence_no', $ref_no);
            $result_stmt->bindParam(':date_from', $dateFrom);
            $result_stmt->bindParam(':date_to', $dateTo);
            $result_stmt->bindParam(':company', $company);
            $result_stmt->execute();

            //* ====== Update Reference No. ======
            $sql_refno = "UPDATE bpi_perso_notif_ref_no SET ref_no = :ref_no";
            $result_refno_stmt = $email_logs->prepare($sql_refno);
            $result_refno_stmt->bindParam(':ref_no', $ref_no);
            $result_refno_stmt->execute();

            $email_logs = null; //* ======== Close Connection ========
            break;

        case 'save_holiday':
            $holidayDay = date_format(date_create(trim($_POST['holidayDate'])), 'd');
            $holidayMonth = date_format(date_create(trim($_POST['holidayDate'])), 'm');

            $result_chk_sql = "SELECT * FROM bpi_perso_holiday_entry WHERE holiday_day = :holiday_day AND holiday_month = :holiday_month";
            $result_chk_stmt = $perso->prepare($result_chk_sql);
            $result_chk_stmt->bindParam(':holiday_day', $holidayDay);
            $result_chk_stmt->bindParam(':holiday_month', $holidayMonth);
            $result_chk_stmt->execute();

            if ($result_chk_stmt->rowCount() > 0) {
                echo 'existing';
            } else {
                $sqlstring = "INSERT INTO bpi_perso_holiday_entry(holiday_day,holiday_month) VALUES(:holiday_day,:holiday_month)";
                $result_stmt = $perso->prepare($sqlstring);
                $result_stmt->bindParam(':holiday_day', $holidayDay);
                $result_stmt->bindParam(':holiday_month', $holidayMonth);
                $result_stmt->execute();
            }
            $perso = null; //* ====== Close Connection ======
            break;

        case 'load_file_deletion_info':
            $notifid = trim($_POST['notifid']);
            $resultData_List = array();

            $sqlstring = "SELECT * FROM bpi_perso_notification WHERE notifid = :notifid";
            $result_stmt = $email_logs->prepare($sqlstring);
            $result_stmt->bindParam(':notifid', $notifid);
            $result_stmt->execute();

            while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
                $resultData_List['company'] = $row['company'];
                $resultData_List['filename'] = $row['filename'];
                $resultData_List['filesize'] = $row['filesize'];
                $resultData_List['received_date'] = $row['received_date'];
                $resultData_List['delivery_date'] = $row['delivered_date'];
                $resultData_List['deletion_date'] = $row['deletion_date'];
                $resultData_List['deleted_by'] = $row['deleted_by'];
                $resultData_List['witnessed_by'] = $row['witnessed_by'];
                $resultData_List['certified_by'] = $row['certified_by'];
            }
            echo json_encode($resultData_List);
            $email_logs = null; //* ====== Close Connection ======
            break;

        case 'update_file_info':
            $notifid = trim($_POST['notifid']);
            $info_filename = trim($_POST['info_filename']);
            $info_filesize = trim($_POST['info_filesize']);
            $info_deletion_date = trim($_POST['info_deletion_date']);
            $info_deleted_by = trim($_POST['info_deleted_by']);
            $info_witnessed_by = trim($_POST['info_witnessed_by']);
            $info_delivery_date = trim($_POST['info_delivery_date']);

            $sqlstring = "UPDATE bpi_perso_notification SET filename = :filename, filesize = :filesize, deletion_date = :deletion_date, 
                deleted_by = :deleted_by, witnessed_by = :witnessed_by , delivered_date = :delivered_date
                WHERE notifid = :notifid";
            $result_stmt = $email_logs->prepare($sqlstring);
            $result_stmt->bindParam(':filename', $info_filename);
            $result_stmt->bindParam(':filesize', $info_filesize);
            $result_stmt->bindParam(':deletion_date', $info_deletion_date);
            $result_stmt->bindParam(':deleted_by', $info_deleted_by);
            $result_stmt->bindParam(':witnessed_by', $info_witnessed_by);
            $result_stmt->bindParam(':delivered_date', $info_delivery_date);
            $result_stmt->bindParam(':notifid', $notifid);
            $result_stmt->execute();
            $email_logs = null; //* ====== Close Connection ======
            break;
    }
}
