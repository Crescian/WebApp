<?php
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    $perso = $conn->db_conn_personalization(); //* Personalization Database connection
    $bannerData = $conn->db_conn_bannerdata(); //* BannerData Database connection
    $action = trim($_POST['action']);
    date_default_timezone_set('Asia/Manila');
    $currentDate = date('Y-m-d');

    switch ($action) {
        case 'load_assigned_dr_table_data':
            //* ======== Read Data ========
            $searchValue = $_POST['search']['value'];
            $empno = trim($_POST['empno']);
            $resultData_List = array();

            //* ======== Create Array for column same with column names on database for ordering ========
            $col = array(
                0 => 'drnumber',
                1 => 'date_time_prep',
                2 => 'customer_name',
                3 => 'jonumber',
                4 => 'job_description',
                5 => 'received_by',
                6 => 'signed',
                7 => 'date_received'
            );
            //* ======== Fetch Data ========
            $sqlstring = "SELECT * FROM bpi_perso_dr_assigned_list ORDER BY drnumber ASC";
            $result_stmt = $perso->prepare($sqlstring);
            $result_stmt->execute();
            $result_total_record = $result_stmt->rowCount();
            //* ======== Fetch Total Filtered Record ========
            $sqlstring = "SELECT * FROM bpi_perso_dr_assigned_list WHERE 1 = 1 ";
            //* ======== Search ========
            if (!empty($searchValue)) {
                $sqlstring .= "AND (drnumber ILIKE '%" . $searchValue . "%' OR TO_CHAR(date_time_prep, 'YYYY-MM-DD') ILIKE '%" . $searchValue . "%' OR customer_name ILIKE '%" . $searchValue . "%' OR jonumber ILIKE '%" . $searchValue . "%' OR job_description ILIKE '%" . $searchValue . "%' OR TO_CHAR(date_received, 'YYYY-MM-DD') ILIKE '%" . $searchValue . "%') ";
            }
            $result_stmt = $perso->prepare($sqlstring);
            $result_stmt->execute();
            $result_total_record_filtered = $result_stmt->rowCount();
            //* ======== Ordering ========
            $sqlstring .= "ORDER BY " . $col[$_POST['order'][0]['column']] . " " . $_POST['order'][0]['dir'] . " LIMIT " . $_POST['length'] . " OFFSET " . $_POST['start'];
            $result_stmt = $perso->prepare($sqlstring);
            $result_stmt->execute();
            //* ======== Prepare Array ========
            while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
                $date_time_prep = ($row['date_time_prep'] == '') ? '-' : $row['date_time_prep'];
                $drnumber = ($row['drnumber'] == '') ? '-' : $row['drnumber'];
                $customer_name = ($row['customer_name'] == '') ? '-' : $row['customer_name'];
                $jonumber = ($row['jonumber'] == '') ? '-' : $row['jonumber'];
                $job_description = ($row['job_description'] == '') ? '-' : $row['job_description'];
                $receivedBy = ($row['received_by'] == '') ? '-' : $row['received_by'];
                $dateReceived = ($row['date_received'] == '') ? '- - -' : $row['date_received'];

                if ($empno == 'B-96-0030') { //* ======== Maam Rose ========
                    if ($row['date_time_prep'] == '') {
                        $btnSigned = ($row['signed'] == false) ? '<button type="button" class="btn btn-secondary col-sm-12" disabled><i class="fa-solid fa-signature"></i></button>' : '<span class="badge bg-success col-sm-12 fs-14">Signed</span>';
                    } else {
                        $btnSigned = ($row['signed'] == false) ? '<button type="button" class="btn btn-primary col-sm-12" data-bs-toggle="tooltip" data-bs-placement="top" title="Signed" onclick="signedDr(\'' . $row['drassignid'] . '\');"><i class="fa-solid fa-signature"></i></button>' : '<span class="badge bg-success col-sm-12 fs-14">Signed</span>';
                    }
                } else {
                    $btnSigned = ($row['signed'] == false) ? '<span class="badge bg-danger col-sm-12 fs-14">To Sign</span>' : '<span class="badge bg-success col-sm-12 fs-14">Signed</span>';
                }

                $nestedData = array();
                $nestedData[] = $date_time_prep;
                $nestedData[] = $drnumber;
                $nestedData[] = $customer_name;
                $nestedData[] = $jonumber;
                $nestedData[] = $job_description;
                $nestedData[] = $btnSigned;
                $nestedData[] = $receivedBy;
                $nestedData[] = $dateReceived;
                $resultData_List[] = $nestedData;
            }
            //* ======== Output Data ========
            $output = array(
                'draw'                  =>  intval($_POST['draw']),
                'iTotalRecords'         =>  $result_total_record,
                'iTotalDisplayRecords'  =>  $result_total_record_filtered,
                'data'                  =>  $resultData_List
            );
            //* ======== Send Data as JSON Format ========
            echo json_encode($output);
            $perso = null; //* ======== Close Connection ========
            break;

        case 'add_dr_number':
            $drnumberqty = trim(str_replace(',', '', $_POST['drnumberqty']));;

            $result_sql = "SELECT DISTINCT drnumber FROM bpi_perso_dr_assigned_list ORDER BY drnumber DESC LIMIT 1";
            $result_stmt = $perso->prepare($result_sql);
            $result_stmt->execute();
            $result_row = $result_stmt->fetch(PDO::FETCH_ASSOC);

            $currCount = substr($result_row['drnumber'], 2, 10);

            for ($x = 1; $x <= $drnumberqty; $x++) {
                $counter = intval($currCount) + $x;
                $drnumber = 'DR' . str_pad($counter, 10, '0', STR_PAD_LEFT);

                $save_dr_sql = "INSERT INTO bpi_perso_dr_assigned_list(drnumber) VALUES(:drnumber)";
                $save_dr_stmt = $perso->prepare($save_dr_sql);
                $save_dr_stmt->bindParam(':drnumber', $drnumber);
                $save_dr_stmt->execute();
            }
            $perso = null; //* ======== Close Connection ========
            break;

        case 'signed_dr':
            $drassignid = trim($_POST['drassignid']);

            $result_sql = "UPDATE bpi_perso_dr_assigned_list SET signed = true WHERE drassignid = :drassignid";
            $result_stmt = $perso->prepare($result_sql);
            $result_stmt->bindParam(':drassignid', $drassignid);
            $result_stmt->execute();
            $perso = null; //* ======== Close Connection ========
            break;

        case 'fetch_dr_update_from_live':
            $per_deliveredcard_deliverydate = '';
            $per_deliveredcard_orderid = '';
            $tbleb_tocustomer_orderid = '';
            $per_embossing_deliverydate = '';

            $chkDrNumber_sql = "SELECT drnumber FROM bpi_perso_dr_assigned_list WHERE date_time_prep ISNULL ORDER BY drnumber ASC";
            $chkDrNumber_stmt = $perso->prepare($chkDrNumber_sql);
            $chkDrNumber_stmt->execute();
            foreach ($chkDrNumber_stmt->fetchAll(PDO::FETCH_ASSOC) as $chkDrNumber_row) {
                $drnumber = $chkDrNumber_row['drnumber'];

                $sqlstring_fetch = "SELECT orderid,deliverydate,drnumber FROM per_deliveredcard WHERE drnumber = :drnumber";
                $sqlstring_stmt = $bannerData->prepare($sqlstring_fetch);
                $sqlstring_stmt->bindParam(':drnumber', $drnumber);
                $sqlstring_stmt->execute();
                foreach ($sqlstring_stmt->fetchAll(PDO::FETCH_ASSOC) as $row_per_deliveredcard) {
                    $per_deliveredcard_deliverydate = $row_per_deliveredcard['deliverydate'];
                    $per_deliveredcard_orderid = $row_per_deliveredcard['orderid'];
                }
                if ($sqlstring_stmt->rowCount() > 0) {
                    $sqlstring_info = "SELECT companyname, jonumber, descriptions FROM ordersinformation 
                        INNER JOIN po ON po.poid = ordersinformation.poid
                        INNER JOIN company ON company.companyid = po.customerid WHERE orderid = :per_deliveredcard_orderid";
                    $sqlstring_info_stmt = $bannerData->prepare($sqlstring_info);
                    $sqlstring_info_stmt->bindParam(':per_deliveredcard_orderid', $per_deliveredcard_orderid);
                    $sqlstring_info_stmt->execute();

                    foreach ($sqlstring_info_stmt->fetchAll(PDO::FETCH_ASSOC) as $row_ordersinformation) {
                        $result_update_dr_assigned = "UPDATE bpi_perso_dr_assigned_list SET date_time_prep = :date_time_prep, customer_name = :customer_name, jonumber = :jonumber, job_description = :job_description WHERE drnumber = :drnumber";
                        $result_update_dr_assigned_stmt = $perso->prepare($result_update_dr_assigned);
                        $result_update_dr_assigned_stmt->bindParam(':drnumber', $drnumber);
                        $result_update_dr_assigned_stmt->bindParam(':date_time_prep', $per_deliveredcard_deliverydate);
                        $result_update_dr_assigned_stmt->bindParam(':customer_name', $row_ordersinformation['companyname']);
                        $result_update_dr_assigned_stmt->bindParam(':jonumber', $row_ordersinformation['jonumber']);
                        $result_update_dr_assigned_stmt->bindParam(':job_description', $row_ordersinformation['descriptions']);
                        $result_update_dr_assigned_stmt->execute();
                    }
                } else {
                    $sqlstring = "SELECT orderid,deliverydate,drnumber FROM tbleb_tocustomer WHERE drnumber = :drnumber";
                    $result_stmt = $bannerData->prepare($sqlstring);
                    $result_stmt->bindParam(':drnumber', $drnumber);
                    $result_stmt->execute();
                    foreach ($result_stmt->fetchAll(PDO::FETCH_ASSOC) as $row_tbleb_tocustomer) {
                        $tbleb_tocustomer_orderid = $row_tbleb_tocustomer['orderid'];
                        $per_embossing_deliverydate = $row_tbleb_tocustomer['deliverydate'];
                    }

                    if ($result_stmt->rowCount() > 0) {
                        $sqlstring_info = "SELECT companyname, jonumber, descriptions FROM ordersinformation 
                            INNER JOIN po ON po.poid = ordersinformation.poid
                            INNER JOIN company ON company.companyid = po.customerid WHERE orderid = :tbleb_tocustomer_orderid";
                        $sqlstring_info_stmt = $bannerData->prepare($sqlstring_info);
                        $sqlstring_info_stmt->bindParam(':tbleb_tocustomer_orderid', $tbleb_tocustomer_orderid);
                        $sqlstring_info_stmt->execute();

                        foreach ($sqlstring_info_stmt->fetchAll(PDO::FETCH_ASSOC) as $row_ordersinformation) {
                            $result_update_dr_assigned = "UPDATE bpi_perso_dr_assigned_list SET date_time_prep = :date_time_prep, customer_name = :customer_name, jonumber = :jonumber, job_description = :job_description WHERE drnumber = :drnumber";
                            $result_update_dr_assigned_stmt = $perso->prepare($result_update_dr_assigned);
                            $result_update_dr_assigned_stmt->bindParam(':drnumber', $drnumber);
                            $result_update_dr_assigned_stmt->bindParam(':date_time_prep', $per_embossing_deliverydate);
                            $result_update_dr_assigned_stmt->bindParam(':customer_name', $row_ordersinformation['companyname']);
                            $result_update_dr_assigned_stmt->bindParam(':jonumber', $row_ordersinformation['jonumber']);
                            $result_update_dr_assigned_stmt->bindParam(':job_description', $row_ordersinformation['descriptions']);
                            $result_update_dr_assigned_stmt->execute();
                        }
                    } else {
                        $result_update_dr_assigned = "UPDATE bpi_perso_dr_assigned_list SET date_time_prep = NULL, customer_name = NULL, jonumber = NULL, job_description = NULL WHERE drnumber = :drnumber";
                        $result_update_dr_assigned_stmt = $perso->prepare($result_update_dr_assigned);
                        $result_update_dr_assigned_stmt->bindParam(':drnumber', $drnumber);
                        $result_update_dr_assigned_stmt->execute();
                    }
                }
            }
            $perso = null; //* ======== Close Connection ========
            $bannerData = null; //* ======== Close Connection ========
            break;
    }
}
