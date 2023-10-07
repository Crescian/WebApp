<?php
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    include_once '../../model/itrepair_request_model/helper_model.class.php';
    $ITR = $conn->db_conn_it_repair_request(); //* IT REPAIR AND REQUEST Database connection
    $BannerWeb = $conn->db_conn_bannerweb(); //* BANNER WEB Database connection
    $helper = new ItRepairRequestHelper();
    $action = trim($_POST['action']);
    $date = date('Y-m-d');
    $choose = '<option value="">Choose...</option>';

    function generateSelect($connection, $query, $value, $text)
    {
        $stmt = $connection->prepare($query);
        $stmt->execute();
        $rowData = $stmt->fetchAll();

        $option = '';
        foreach ($rowData as $row) {
            $option .= '<option value="' . $row[$value] . '">' . $row[$text] . '</option>';
        }

        return $option;
    }
    switch ($action) {
        case 'onLoadInputs':
            echo $helper->onLoadInputs($ITR, $BannerWeb);
            break;
        case 'loadEditRequest':
            $dept_code = trim($_POST['dept_code']);
            echo $helper->loadEditRequest($BannerWeb, $dept_code);
            break;
        case 'loadLocation':
            $area = $_POST['area'];
            echo $helper->loadLocation($ITR, $area);
            break;
        case 'loadEmployeeDepartment':
            $employee = trim($_POST['employee']);
            echo $helper->loadEmployeeDepartment($BannerWeb, $employee);
            break;
        case 'loadEmployee':
            $deptCode = $_POST['deptCode'];
            echo $helper->loadEmployee($BannerWeb, $deptCode);
            break;
        case 'loadDepartmentHead':
            echo $helper->loadDepartmentHead($BannerWeb);
            break;
    }
}
