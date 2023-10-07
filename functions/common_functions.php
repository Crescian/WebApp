<?php
if (isset($_POST['action'])) {
    include_once '../configuration/connection.php';
    $PHD = $conn->db_conn_physical_security(); //* Physical Security Database connection
    $bannerData = $conn->db_conn_bannerdata(); //* Bannerdata Database connection
    $BannerWebLive = $conn->db_conn_bannerweb(); //* Banner Web Database connection
    //* For DB connection if using same function with different db connection Note: add ka lang ng connection mo at ng trigger
    $connDb = ['physical_security' => $PHD, 'bannerData' => $bannerData, 'banner_web_live' => $BannerWebLive];

    $action = trim($_POST['action']);
    date_default_timezone_set('Asia/Manila');
    $currentDate = date('Y-m-d');

    switch ($action) {
        case 'load_image_base64':
            $image_data = $_POST['image'];
            $image_array_1 = explode(";", $image_data);
            $image_array_2 = explode(",", $image_array_1[1]);
            $image_data = base64_decode($image_array_2[1]);
            $photo_base64 = base64_encode($image_data);
            echo $photo_base64;
            break;

        case 'load_employee_per_department':
            $dept_code = trim($_POST['dept_code']);

            $sqlstring = "SELECT empno,(emp_fn || ' ' || emp_sn) AS fullname FROM prl_employee WHERE dept_code = :dept_code";
            $result_stmt = $BannerWebLive->prepare($sqlstring);
            $result_stmt->bindParam(':dept_code', $dept_code);
            $result_stmt->execute();
            echo '<option value="">Choose...</option>';
            foreach ($result_stmt->fetchAll() as $row) {
                echo '<option value="' . $row['fullname'] . '">' . $row['fullname'] . '</option>';
            }
            $BannerWebLive = null; //* ======== Close Connection ========
            break;

        case 'load_select_values_with_id':
            $inTable = trim($_POST['inTable']);
            $inField = trim($_POST['inField']);
            $inFieldId = trim($_POST['inFieldId']);
            $connection = trim($_POST['connection']);
            $itemData_List = array();

            $sqlstring = "SELECT " . $inFieldId . ", " . $inField . " FROM " . $inTable . " ORDER BY " . $inField . " ASC";
            $result_stmt = $connDb[$_POST['connection']]->prepare($sqlstring);
            $result_stmt->execute();
            foreach ($result_stmt->fetchAll() as $row) {
                $itemData_List[$row[$inFieldId]] = $row[$inField];
            }
            echo json_encode($itemData_List);
            $connDb[$_POST['connection']] = null; //* ======== Close Connection ========
            break;

        case 'load_select_values':
            $inTable = trim($_POST['inTable']);
            $inField = trim($_POST['inField']);
            $connection = trim($_POST['connection']);
            $itemData_List = array();

            $sqlstring = "SELECT " . $inField . " FROM " . $inTable . " ORDER BY " . $inField . " ASC";
            $result_stmt = $connDb[$_POST['connection']]->prepare($sqlstring);
            $result_stmt->execute();
            foreach ($result_stmt->fetchAll() as $row) {
                $itemData_List[] = $row[$inField];
            }
            echo json_encode($itemData_List);
            $connDb[$_POST['connection']] = null; //* ======== Close Connection ========
            break;

        case 'load_job_pos_name_employee':
            $employee = trim($_POST['employee']);
            $itemData_List = array();

            $sqlstring = "SELECT pos_name FROM prl_employee 
                INNER JOIN prl_position ON prl_position.pos_code = prl_employee.pos_code 
                WHERE (emp_fn || ' ' || emp_sn) = :employee";
            $result_stmt = $BannerWebLive->prepare($sqlstring);
            $result_stmt->bindParam(':employee', $employee);
            $result_stmt->execute();
            while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
                $itemData_List['pos_name'] = $row['pos_name'];
            }
            echo json_encode($itemData_List);
            $BannerWebLive = null; //* ======== Close Connection ========
            break;

        case 'load_jonumber_description':
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
    }
}
