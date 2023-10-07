<?php
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    $bannerData = $conn->db_conn_bannerdata(); //* Bannerdata Database connection
    $prod = $conn->db_conn_manufacturing(); //* Manufacturing Database connection
    $action = trim($_POST['action']);
    date_default_timezone_set('Asia/Manila');
    $currentDate = date('Y-m-d');

    function fill_process_select_box($prod, $processid)
    {
        $output = '';
        $result_sql = "SELECT * FROM prod_process_name ORDER BY processid ASC";
        $result_stmt = $prod->prepare($result_sql);
        $result_stmt->execute();
        $output .= '<option value="">Choose...</option>';
        foreach ($result_stmt->fetchAll() as $row) {
            $selected = $row["processid"] == $processid ? "selected" : "";
            $output .= '<option value="' . $row["processid"] . '" ' . $selected . '>' . $row["process_name"] . '</option>';
        }
        return $output;
        $prod = null; //* ======== Close Connection ========
    }

    function setSelected($selectValue, $rowValue)
    {
        if ($selectValue == $rowValue) {
            $setSelected = 'selected';
        }
        return $setSelected;
    }

    switch ($action) {
        case 'load_template_list_table':
            //* ======== Read Data ========
            $searchValue = $_POST['search']['value'];
            $resultData_List = array();
            //* ======== Create Array for column same with column names on database for ordering ========
            $col = array(
                0 => 'template_name'
            );
            //* ======== Fetch Record ========
            $sqlstring = "SELECT * FROM prod_template_name";
            $result_stmt = $prod->prepare($sqlstring);
            $result_stmt->execute();
            $result_total_record = $result_stmt->rowCount();
            //* ======== Fetch Total Filtered Record ========
            $sqlstring = "SELECT * FROM prod_template_name WHERE 1 = 1 ";
            //* ======== Search ========
            if (!empty($searchValue)) {
                $sqlstring .= "AND (template_name ILIKE '%" . $searchValue . "%') ";
            }
            $result_stmt = $prod->prepare($sqlstring);
            $result_stmt->execute();
            $result_total_record_filtered = $result_stmt->rowCount();
            //* ======== Ordering ========
            $sqlstring .=
                " ORDER BY " . $col[$_POST['order'][0]['column']] . " " . $_POST['order'][0]['dir'] . " LIMIT " . $_POST['length'] . " OFFSET " . $_POST['start'];
            $result_stmt = $prod->prepare($sqlstring);
            $result_stmt->execute();
            //* ======== Prepare Array ========
            while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
                //* ======== Check if Template is In use ========
                $result_chk_sql = "SELECT * FROM prod_job_entry_header WHERE template_id = ?";
                $result_chk_stmt = $prod->prepare($result_chk_sql);
                $result_chk_stmt->execute([$row['templateid']]);

                $nestedData = array();
                $nestedData[] = $row['template_name'];
                $nestedData[] = $result_chk_stmt->rowCount();
                $nestedData[] = array($result_chk_stmt->rowCount(), $row["templateid"]);
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
            $prod = null; //* ======== Close Connection ========
            break;

        case 'load_customer_list_table':
            //* ======== Read Data ========
            $searchValue = $_POST['search']['value'];
            $resultData_List = array();
            //* ======== Create Array for column same with column names on database for ordering ========
            $col = array(
                0 => 'companyname'
            );
            //* ======== Fetch Record ========
            $sqlstring = "SELECT DISTINCT companyname FROM ordersinformation
                INNER JOIN po ON po.poid = ordersinformation.poid
                INNER JOIN company ON company.companyid = po.customerid";
            $result_stmt = $bannerData->prepare($sqlstring);
            $result_stmt->execute();
            $result_total_record = $result_stmt->rowCount();
            //* ======== Fetch Total Filtered Record ========
            $sqlstring = "SELECT DISTINCT companyname FROM ordersinformation
                INNER JOIN po ON po.poid = ordersinformation.poid
                INNER JOIN company ON company.companyid = po.customerid 
                WHERE 1 = 1 ";
            //* ======== Search ========
            if (!empty($searchValue)) {
                $sqlstring .= "AND companyname ILIKE '%" . $searchValue . "%' ";
            }
            $result_stmt = $bannerData->prepare($sqlstring);
            $result_stmt->execute();
            $result_total_record_filtered = $result_stmt->rowCount();
            //* ======== Ordering ========
            $sqlstring .=
                " ORDER BY " . $col[$_POST['order'][0]['column']] . " " . $_POST['order'][0]['dir'] . " LIMIT " . $_POST['length'] . " OFFSET " . $_POST['start'];
            $result_stmt = $bannerData->prepare($sqlstring);
            $result_stmt->execute();
            //* ======== Prepare Array ========
            while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {

                $nestedData = array();
                $nestedData[] = $row['companyname'];
                $nestedData[] = str_replace("'", "\'", $row['companyname']);
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
            $bannerData = null; //* ======== Close Connection ========
            break;

        case 'load_process_section':
            $processid = trim($_POST['processid']);
            $itemData_List = array();

            $sqlstring = "SELECT processid,process_name,section_name FROM prod_process_name
                INNER JOIN prod_section_name ON prod_section_name.sectionid = prod_process_name.section_id  WHERE processid = :processid";
            $result_stmt = $prod->prepare($sqlstring);
            $result_stmt->bindParam(':processid', $processid);
            $result_stmt->execute();
            $row = $result_stmt->fetch(PDO::FETCH_ASSOC);
            $itemData_List['section_name'] = $row['section_name'];
            echo json_encode($itemData_List);
            $prod = null; //* ======== Close Connection ========
            break;

        case 'save_template_name':
            $template_name = trim($_POST['template_name']);
            $itemData_List = array();

            $chkExistsql = "SELECT * FROM prod_template_name WHERE template_name = :template_name";
            $chkExistsql_stmt = $prod->prepare($chkExistsql);
            $chkExistsql_stmt->bindParam(':template_name', $template_name);
            $chkExistsql_stmt->execute();

            if ($chkExistsql_stmt->rowCount() > 0) {
                $itemData_List['templateid'] = 'existing';
            } else {
                $sqlstring = "INSERT INTO prod_template_name(template_name) VALUES(:template_name) RETURNING templateid";
                $result_stmt = $prod->prepare($sqlstring);
                $result_stmt->bindParam(':template_name', $template_name);
                $result_stmt->execute();
                $itemData_List['templateid'] = $prod->lastInsertId();
            }
            echo json_encode($itemData_List);
            $prod = null; //* ======== Close Connection ========
            break;

        case 'save_template_process':
            $template_id = trim($_POST['template_id']);
            $process_id = trim($_POST['process_id']);
            $process_seq = trim($_POST['process_seq']);
            $card_side = trim($_POST['card_side']);

            $result_sql = "INSERT INTO prod_template_process(template_id,process_id,process_seq,card_side) VALUES(:template_id,:process_id,:process_seq,:card_side)";
            $result_stmt = $prod->prepare($result_sql);
            $result_stmt->bindParam(':template_id', $template_id);
            $result_stmt->bindParam(':process_id', $process_id);
            $result_stmt->bindParam(':process_seq', $process_seq);
            $result_stmt->bindParam(':card_side', $card_side);
            $result_stmt->execute();
            $prod = null; //* ======== Close Connection ========
            break;

        case 'load_template_name':
            $templateid = trim($_POST['templateid']);
            $itemData_List = array();

            $result_sql = "SELECT * FROM prod_template_name WHERE templateid = :templateid";
            $result_stmt = $prod->prepare($result_sql);
            $result_stmt->bindParam(':templateid', $templateid);
            $result_stmt->execute();
            $row = $result_stmt->fetch(PDO::FETCH_ASSOC);
            $itemData_List['template_name'] = $row['template_name'];
            echo json_encode($itemData_List);
            $prod = null; //* ======== Close Connection ========
            break;

        case 'load_template_process':
            $templateid = trim($_POST['templateid']);
            $itemData_List = array();

            $sqlstring = "SELECT template_id,prod_template_process.process_id,process_seq,process_name,section_name,card_side FROM prod_template_process 
                INNER JOIN prod_process_name ON prod_process_name.processid = prod_template_process.process_id
                INNER JOIN prod_section_name ON prod_section_name.sectionid = prod_process_name.section_id 
                WHERE template_id = :templateid ORDER BY process_seq ASC";
            $result_stmt = $prod->prepare($sqlstring);
            $result_stmt->bindParam(':templateid', $templateid);
            $result_stmt->execute();
            foreach ($result_stmt->fetchAll() as $row) {
                $itemData_List[] = $row;
            }
            echo json_encode($itemData_List);
            $prod = null; //* ======== Close Connection ========
            break;

        case 'update_template_name':
            $templateid = trim($_POST['templateid']);
            $template_name = trim($_POST['template_name']);

            $chkExist_sql = "SELECT * FROM prod_template_name WHERE template_name = :template_name AND templateid <> :templateid";
            $chkExist_stmt = $prod->prepare($chkExist_sql);
            $chkExist_stmt->bindParam(':templateid', $templateid);
            $chkExist_stmt->bindParam(':template_name', $template_name);
            $chkExist_stmt->execute();

            if ($chkExist_stmt->rowCount() > 0) {
                echo 'existing';
            } else {
                $sqlstring = "UPDATE prod_template_name SET template_name = :template_name WHERE templateid = :templateid";
                $result_stmt = $prod->prepare($sqlstring);
                $result_stmt->bindParam(':templateid', $templateid);
                $result_stmt->bindParam(':template_name', $template_name);
                $result_stmt->execute();
            }
            $prod = null; //* ======== Close Connection ========
            break;

        case 'delete_process_update':
            $template_id = trim($_POST['template_id']);
            //* ======== Delete Record For Process Update ========
            $result_delete_process_sql = "DELETE FROM prod_template_process WHERE template_id = :template_id";
            $result_delete_process_stmt = $prod->prepare($result_delete_process_sql);
            $result_delete_process_stmt->bindParam(':template_id', $template_id);
            $result_delete_process_stmt->execute();
            $prod = null; //* ======== Close Connection ========
            break;

        case 'delete_template':
            $templateid = trim($_POST['templateid']);

            $result_sql = "DELETE FROM prod_template_name WHERE templateid = :templateid";
            $result_stmt = $prod->prepare($result_sql);
            $result_stmt->bindParam(':templateid', $templateid);
            $result_stmt->execute();
            $prod = null; //* ======== Close Connection ========
            break;

        case 'load_job_order_number':
            $companyname = str_replace("'", "", trim($_POST['companyname']));

            $sqlstring = "SELECT jonumber FROM ordersinformation 
                INNER JOIN po ON po.poid = ordersinformation.poid 
                INNER JOIN company ON company.companyid = po.customerid 
                WHERE REPLACE(companyname,'''','') = :companyname
                GROUP BY jonumber, ordersinformation.orderid 
                ORDER BY ordersinformation.orderid DESC";
            $result_stmt = $bannerData->prepare($sqlstring);
            $result_stmt->bindParam(':companyname', $companyname);
            $result_stmt->execute();
            echo '<option value="">Choose...</option>';
            foreach ($result_stmt->fetchAll() as $row) {
                echo '<option value="' . $row['jonumber'] . '">' . $row['jonumber'] . '</option>';
            }
            $bannerData = null; //* ======== Close Connection ========
            break;

        case 'load_job_description':
            $jonumber = trim($_POST['jonumber']);
            $itemData_List = array();

            $sqlstring = "SELECT descriptions,orderid FROM ordersinformation WHERE jonumber = :jonumber";
            $result_stmt = $bannerData->prepare($sqlstring);
            $result_stmt->bindParam(':jonumber', $jonumber);
            $result_stmt->execute();

            foreach ($result_stmt->fetchAll() as $row) {
                $itemData_List['descriptions'] = $row['descriptions'];
                $itemData_List['orderid'] = $row['orderid'];
            }
            echo json_encode($itemData_List);
            $bannerData = null; //* ======== Close Connection ========
            break;

        case 'load_assigned_template_table':
            //* ======== Read Data ========
            $searchValue = $_POST['search']['value'];
            $companyname = trim($_POST['companyname']);
            $jonumber = trim($_POST['jonumber']);
            $orderid = trim($_POST['orderid']);
            $resultData_List = array();
            //* ======== Create Array for column same with column names on database for ordering ========
            $col = array(
                0 => 'template_name'
            );
            //* ======== Fetch Record ========
            $sqlstring = "SELECT TempAssign.tempassignid,TempName.template_name FROM prod_template_assign TempAssign
                INNER JOIN prod_template_name TempName ON TempName.templateid = TempAssign.template_id
                WHERE TempAssign.customer_name = :customer AND TempAssign.jonumber = :jonumber AND TempAssign.orderid = :orderid";
            $result_stmt = $prod->prepare($sqlstring);
            $result_stmt->bindParam(':customer', $companyname);
            $result_stmt->bindParam(':jonumber', $jonumber);
            $result_stmt->bindParam(':orderid', $orderid);
            $result_stmt->execute();
            $result_total_record = $result_stmt->rowCount();
            //* ======== Fetch Total Filtered Record ========
            $sqlstring = "SELECT TempAssign.tempassignid,TempName.template_name FROM prod_template_assign TempAssign
                INNER JOIN prod_template_name TempName ON TempName.templateid = TempAssign.template_id
                WHERE 1 = 1 AND TempAssign.customer_name = :customer AND TempAssign.jonumber = :jonumber AND TempAssign.orderid = :orderid";
            //* ======== Search ========
            if (!empty($searchValue)) {
                $sqlstring .= " AND (template_name ILIKE '%" . $searchValue . "%') ";
            }
            $result_stmt = $prod->prepare($sqlstring);
            $result_stmt->bindParam(':customer', $companyname);
            $result_stmt->bindParam(':jonumber', $jonumber);
            $result_stmt->bindParam(':orderid', $orderid);
            $result_stmt->execute();
            $result_total_record_filtered = $result_stmt->rowCount();
            //* ======== Ordering ========
            $sqlstring .= " ORDER BY " . $col[$_POST['order'][0]['column']] . " " . $_POST['order'][0]['dir'] . " LIMIT " . $_POST['length'] . " OFFSET " . $_POST['start'] . "";
            $result_stmt = $prod->prepare($sqlstring);
            $result_stmt->bindParam(':customer', $companyname);
            $result_stmt->bindParam(':jonumber', $jonumber);
            $result_stmt->bindParam(':orderid', $orderid);
            $result_stmt->execute();
            //* ======== Prepare Array ========
            while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
                // $btnAction = '<button type="button" class="btn btn-danger col-sm-12" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Remove Template" onclick="removeAssignedTemplate(\'' . $row["tempassignid"] . '\');"><i class="fa-solid fa-trash-can fa-shake"></i></button>';

                $nestedData = array();
                $nestedData[] = $row['template_name'];
                $nestedData[] = $row["tempassignid"];
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
            $prod = null; //* ======== Close Connection ========
            break;

        case 'load_assign_template_process':
            $templateid = trim($_POST['templateid']);
            $itemData_List = array();

            $result_sql = "SELECT TempProc.process_seq,ListProc.process_name,section_name,card_side
                FROM prod_template_name TempName
                INNER JOIN prod_template_process TempProc ON TempProc.template_id = TempName.templateid
                INNER JOIN prod_process_name ListProc ON ListProc.processid = TempProc.process_id
                INNER JOIN prod_section_name ON prod_section_name.sectionid = ListProc.section_id
                WHERE TempName.templateid = :templateid ORDER BY TempProc.process_seq";
            $result_stmt = $prod->prepare($result_sql);
            $result_stmt->bindParam(':templateid', $templateid);
            $result_stmt->execute();
            foreach ($result_stmt->fetchAll() as $row) {
                $itemData_List[] = $row;
            }
            echo json_encode($itemData_List);
            $prod = null; //* ======== Close Connection ========
            break;

        case 'save_assign_template':
            $customer_name = trim($_POST['customer_name']);
            $jonumber = trim($_POST['jonumber']);
            $orderid = trim($_POST['orderid']);
            $templateid = trim($_POST['templateid']);
            //* ======== Check Existing Template Name Within J.O Number ========
            $result_chkExisting_sql = "SELECT * FROM prod_template_assign WHERE template_id = :templateid AND customer_name = :customer_name AND jonumber = :jonumber AND orderid = :orderid";
            $result_chkExisting_stmt = $prod->prepare($result_chkExisting_sql);
            $result_chkExisting_stmt->bindParam(':customer_name', $customer_name);
            $result_chkExisting_stmt->bindParam(':jonumber', $jonumber);
            $result_chkExisting_stmt->bindParam(':orderid', $orderid);
            $result_chkExisting_stmt->bindParam(':templateid', $templateid);
            $result_chkExisting_stmt->execute();

            if ($result_chkExisting_stmt->rowCount() > 0) {
                echo 'existing';
            } else {
                $sqlstring = "INSERT INTO prod_template_assign(customer_name,jonumber,orderid,template_id) VALUES(:customer_name,:jonumber,:orderid,:templateid)";
                $result_stmt = $prod->prepare($sqlstring);
                $result_stmt->bindParam(':customer_name', $customer_name);
                $result_stmt->bindParam(':jonumber', $jonumber);
                $result_stmt->bindParam(':orderid', $orderid);
                $result_stmt->bindParam(':templateid', $templateid);
                $result_stmt->execute();
            }
            $prod = null; //* ======== Close Connection ========
            break;
    }
}
