<?php
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    $perso = $conn->db_conn_personalization(); //* Personalization Database connection
    $cms_data = $conn->db_conn_cms_data(); //* cms_data Database connection
    $action = trim($_POST['action']);
    date_default_timezone_set('Asia/Manila');
    $currentDate = date('Y-m-d');

    switch ($action) {
        case 'load_expected_courier_list':
            //* ======== Read Data ========
            $searchValue = $_POST['search']['value'];
            $resultData_List = array();
            //* ======== Create Array for column same with column names on database for ordering ========
            $col = array(
                0 => 'date_from',
                1 => 'companyname',
                2 => 'courier'
            );
            //* ======== Fetch Data ========
            $sqlstring = "SELECT visitorexpectedid, companyname, courier, date_from  FROM cms_visitorlog_expected WHERE preparedby <> '' AND date_from = :currentDate";
            $result_stmt = $cms_data->prepare($sqlstring);
            $result_stmt->bindParam(':currentDate', $currentDate);
            $result_stmt->execute();
            $result_total_record = $result_stmt->rowCount();
            //* ======== Fetch Total Filtered Record ========
            $sqlstring = "SELECT visitorexpectedid, companyname, courier, date_from  FROM cms_visitorlog_expected WHERE preparedby <> '' AND date_from = :currentDate ";
            //* ======== Search ========
            if (!empty($searchValue)) {
                $sqlstring .= "AND (TO_CHAR(date_from, 'YYYY-MM-DD') ILIKE '%" . $searchValue . "%' OR companyname ILIKE '%" . $searchValue . "%' OR courier ILIKE '%" . $searchValue . "%')";
            }
            $result_stmt = $cms_data->prepare($sqlstring);
            $result_stmt->bindParam(':currentDate', $currentDate);
            $result_stmt->execute();
            $result_total_record_filtered = $result_stmt->rowCount();
            //* ======== Ordering ========
            $sqlstring .= "ORDER BY " . $col[$_POST['order'][0]['column']] . " " . $_POST['order'][0]['dir'] . " LIMIT " . $_POST['length'] . " OFFSET " . $_POST['start'];
            $result_stmt = $cms_data->prepare($sqlstring);
            $result_stmt->bindParam(':currentDate', $currentDate);
            $result_stmt->execute();
            //* ======== Prepare Array ========
            while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
                $sqlChkCourier = "SELECT companyname,courier,name,datetimein 
                    FROM cms_visitorlog WHERE datetimein IS NOT NULL AND datetimeout ISNULL AND REPLACE(REPLACE(LOWER(purpose),'-',' '),' ','') ILIKE '%Pickup%' AND companyname = '" . $row['companyname'] . "' AND courier = '" . $row['courier'] . "'";
                $chkCourier_stmt = $cms_data->prepare($sqlChkCourier);
                $chkCourier_stmt->execute();

                $courierStatus = $chkCourier_stmt->rowCount() > 0 ? '<span class="badge bg-success col-sm-12">Arrived</span>' : '<span class="badge bg-danger col-sm-12">Waiting Arrival</span>';

                $nestedData = array();
                $nestedData[] = $row['date_from'];
                $nestedData[] = $row['companyname'];
                $nestedData[] = $row['courier'];
                $nestedData[] = $courierStatus;
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
            $cms_data = null; //* ======== Close Connection ========
            break;

        case 'load_authorize_courier_list':
            //* ======== Read Data ========
            $searchValue = $_POST['search']['value'];
            $resultData_List = array();
            //* ======== Create Array for column same with column names on database for ordering ========
            $col = array(
                0 => 'company_name',
                1 => 'courier',
                2 => 'authorize_fullname',
                3 => 'authorize_job_position'
            );
            //* ======== Fetch Data ========
            $sqlstring = "SELECT * FROM bpi_perso_authorize_courier";
            $result_stmt = $perso->prepare($sqlstring);
            $result_stmt->execute();
            $result_total_record = $result_stmt->rowCount();
            //* ======== Fetch Total Filtered Record ========
            $sqlstring = "SELECT * FROM bpi_perso_authorize_courier WHERE 1 = 1 ";
            //* ======== Search ========
            if (!empty($searchValue)) {
                $sqlstring .= "AND (company_name ILIKE '%" . $searchValue . "%' OR courier ILIKE '%" . $searchValue . "%' OR authorize_fullname ILIKE '%" . $searchValue . "%' OR authorize_job_position ILIKE '%" . $searchValue . "%') ";
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
                $btnAction = '<button type="button" class="btn btn-info col-sm-6" data-bs-toggle="tooltip" data-bs-placement="top" title="View Information" onclick="authorizeCourierInfo(\'' . $row['authorizeid'] . '\');"><i class="fa-solid fa-circle-info fa-bounce"></i></button>
                    <button type="button" class="btn btn-danger col-sm-6" data-bs-toggle="tooltip" data-bs-placement="top" title="View Information" onclick="authorizeCourierDelete(\'' . $row['authorizeid'] . '\');"><i class="fa-solid fa-trash-can fa-shake"></i></button>';

                $nestedData = array();
                $nestedData[] = $row['company_name'];
                $nestedData[] = $row['courier'];
                $nestedData[] = $row['authorize_fullname'];
                $nestedData[] = $row['authorize_job_position'];
                $nestedData[] = $btnAction;
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

        case 'load_company_name':
            $sqlstring = "SELECT company FROM cms_authorization_courier GROUP BY company ORDER BY company";
            $result_stmt = $cms_data->prepare($sqlstring);
            $result_stmt->execute();
            echo '<option value="">Choose...</option>';
            foreach ($result_stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                echo '<option value="' . $row['company'] . '">' . $row['company'] . '</option>';
            }
            $cms_data = null; //* ======== Close Connection ========
            break;

        case 'load_courier':
            $companyname = trim($_POST['companyname']);

            $sqlstring = "SELECT courier FROM cms_authorization_courier WHERE company = :companyname AND courier <> ''";
            $result_stmt = $cms_data->prepare($sqlstring);
            $result_stmt->bindParam(':companyname', $companyname);
            $result_stmt->execute();
            echo '<option value="">Choose...</option>';
            foreach ($result_stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                echo '<option value="' . $row['courier'] . '">' . $row['courier'] . '</option>';
            }
            $cms_data = null; //* ======== Close Connection ========
            break;

        case 'load_employee':
            $courier = trim($_POST['courier']);
            $companyname = trim($_POST['companyname']);

            $sqlstring = "SELECT authorized_name FROM cms_authorization WHERE authorized_year = '" . date('Y') . "'
                AND company_courier ILIKE :companyname AND company ILIKE :courier ORDER BY authorized_name ASC";
            $result_stmt = $cms_data->prepare($sqlstring);
            $result_stmt->bindParam(':companyname', $companyname);
            $result_stmt->bindParam(':courier', $courier);
            $result_stmt->execute();
            echo '<option value="">Choose...</option>';
            foreach ($result_stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                echo '<option value="' . $row['authorized_name'] . '">' . $row['authorized_name'] . '</option>';
            }
            $cms_data = null; //* ======== Close Connection ========
            break;

        case 'load_image_base64':
            $image_data = $_POST['image'];
            $image_array_1 = explode(";", $image_data);
            $image_array_2 = explode(",", $image_array_1[1]);
            $image_data = base64_decode($image_array_2[1]);
            $photo_base64 = base64_encode($image_data);
            echo $photo_base64;
            break;

        case 'save_authorize_courier':
            $authorize_company_name = trim($_POST['authorize_company_name']);
            $authorize_courier = trim($_POST['authorize_courier']);
            $authorize_employee = trim($_POST['authorize_employee']);
            $authorize_job_position = trim($_POST['authorize_job_position']);
            $authorize_courier_image = trim($_POST['authorize_courier_image']);
            //* ======== Check Courier if Existing ========
            $sqlChkExist = "SELECT * FROM bpi_perso_authorize_courier WHERE company_name = :authorize_company_name AND courier = :authorize_courier  AND authorize_fullname = :authorize_employee";
            $chkExist_stmt = $perso->prepare($sqlChkExist);
            $chkExist_stmt->bindParam(':authorize_company_name', $authorize_company_name);
            $chkExist_stmt->bindParam(':authorize_courier', $authorize_courier);
            $chkExist_stmt->bindParam(':authorize_employee', $authorize_employee);
            $chkExist_stmt->execute();
            if ($chkExist_stmt->rowCount() > 0) {
                echo 'existing';
            } else {
                $sqlstring = "INSERT INTO bpi_perso_authorize_courier(company_name,courier,authorize_fullname,authorize_image,authorize_job_position) 
                VALUES(:authorize_company_name , :authorize_courier ,:authorize_employee ,:authorize_courier_image ,:authorize_job_position)";
                $result_stmt = $perso->prepare($sqlstring);
                $result_stmt->bindParam(':authorize_company_name', $authorize_company_name);
                $result_stmt->bindParam(':authorize_courier', $authorize_courier);
                $result_stmt->bindParam(':authorize_employee', $authorize_employee);
                $result_stmt->bindParam(':authorize_courier_image', $authorize_courier_image);
                $result_stmt->bindParam(':authorize_job_position', $authorize_job_position);
                $result_stmt->execute();
            }
            $perso = null; //* ======== Close Connection ========
            break;

        case 'load_authorize_courier_info':
            //* ======== Read Data ========
            $authorizeid = trim($_POST['authorizeid']);
            $resultData_List = array();
            //* ======== Fetch Data ========
            $sqlstring = "SELECT company_name,courier,authorize_fullname,encode(authorize_image, 'escape') as authorize_image,authorize_job_position FROM bpi_perso_authorize_courier WHERE authorizeid = :authorizeid";
            $result_stmt = $perso->prepare($sqlstring);
            $result_stmt->bindParam(':authorizeid', $authorizeid);
            $result_stmt->execute();
            //* ======== Prepare Array ========
            foreach ($result_stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $resultData_List['company_name'] = $row['company_name'];
                $resultData_List['courier'] = $row['courier'];
                $resultData_List['authorize_fullname'] = $row['authorize_fullname'];
                $resultData_List['authorize_job_position'] = $row['authorize_job_position'];
                $resultData_List['authorize_image'] = '<img src="data:image/jpeg;base64,' . $row['authorize_image'] . '" value="' . $row['authorize_image'] . '" id="authorize_courier_image_container" class="img-thumbnail rounded">';
            }
            //* ======== Send Data as JSON Format ========
            echo json_encode($resultData_List);
            $perso = null; //* ======== Close Connection ========
            break;

        case 'delete_authorize_courier':
            $authorizeid = trim($_POST['authorizeid']);

            $sqlstring = "DELETE FROM bpi_perso_authorize_courier WHERE authorizeid = :authorizeid";
            $result_stmt = $perso->prepare($sqlstring);
            $result_stmt->bindParam(':authorizeid', $authorizeid);
            $result_stmt->execute();
            $perso = null; //* ======== Close Connection ========
            break;
    }
}
