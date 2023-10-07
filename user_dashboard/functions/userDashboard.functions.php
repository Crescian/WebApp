<?php
include_once '../../configuration/connection.php';
$BannerWebLive = $conn->db_conn_bannerweb(); //* Banner Web Database connection
session_start();
date_default_timezone_set('Asia/Manila');

if (isset($_POST['action'])) {
  $action = trim($_POST['action']);
  $date = date('Y-m-d');

  switch ($action) {
    case 'loadUserInformation';
      $employee_number = $_POST['employee_number'];
      $query = "SELECT prl_employee.empno, username, CONCAT(emp_fn, ' ', emp_mi, '. ', emp_sn) AS fullname, dtbirth, bpi_department.department, access_lvl, hiredate 
                  FROM bpi_user_accounts
                INNER JOIN prl_employee
                  ON bpi_user_accounts.empno = prl_employee.empno
                INNER JOIN bpi_department
                  ON bpi_user_accounts.department = bpi_department.dept_code
                WHERE bpi_user_accounts.empno = '{$employee_number}'";
      $stmt = $BannerWebLive->prepare($query);
      $stmt->execute();
      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      $data = array(
        "employeeNumber" => $result['empno'],
        "userName" => $result['username'],
        "fullName" => $result['fullname'],
        "birthDay" => $result['dtbirth'],
        "department" => $result['department'],
        "jobPosition" => $result['access_lvl'],
        "employmentDate" => $result['hiredate']
      );

      echo json_encode($data);
      break;
  }
}
