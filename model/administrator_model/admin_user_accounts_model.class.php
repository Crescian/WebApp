<?php
class BannerUserAccounts
{
    public function loadColoredJobPosition($dept_code, $div_code, $department)
    {
        switch ($dept_code) {
                //* Operations Group
            case 'ITD':
                $loadColoredJobPosition = '<span class="badge bg-red col-sm-12">' . $department . '</span>';
                break;
            case 'ISD':
                $loadColoredJobPosition = '<span class="badge date-color14 col-sm-12">' . $department . '</span>';
                break;
            case 'PRD':
                switch ($div_code) {
                    case 'ADS':
                    case 'ADM':
                        $dept_color = 'date-color4';
                        break;
                    case 'MAN':
                        $dept_color = 'bg-blue';
                        break;
                    case 'PER':
                        $dept_color = 'bg-cyan';
                        break;
                    case 'MAC':
                        $dept_color = 'date-color1';
                        break;
                }
                $loadColoredJobPosition = '<span class="badge ' . $dept_color . ' col-sm-12">' . $department . '</span>';
                break;
            case 'FMD':
                $loadColoredJobPosition = '<span class="badge bg-success col-sm-12">' . $department . '</span>';
                break;
                //* Innovation & Management Systems Group
            case 'RDD':
                $loadColoredJobPosition = '<span class="badge bg-yellow col-sm-12">' . $department . '</span>';
                break;
            case 'MSD':
                $loadColoredJobPosition = '<span class="badge bg-yellow col-sm-12">' . $department . '</span>';
                break;
            case 'PHD':
                $loadColoredJobPosition = '<span class="badge bg-yellow col-sm-12">' . $department . '</span>';
                break;
                //* Strategic Support Service Group
            case 'FID':
                $loadColoredJobPosition = '<span class="badge bg-success col-sm-12">' . $department . '</span>';
                break;
            case 'HRD':
                $loadColoredJobPosition = '<span class="badge bg-success col-sm-12">' . $department . '</span>';
                break;
            case 'BDD':
                $loadColoredJobPosition = '<span class="badge bg-success col-sm-12">' . $department . '</span>';
                break;
            case 'SMD':
                $loadColoredJobPosition = '<span class="badge bg-success col-sm-12">' . $department . '</span>';
                break;
            case 'PSD':
                $loadColoredJobPosition = '<span class="badge bg-success col-sm-12">' . $department . '</span>';
                break;
        }
        return $loadColoredJobPosition;
    }
    public function loadUserList($BannerWeb)
    {
        $itemData_List = array();
        $sqlstring = "SELECT user_id,prl_employee.empno,(emp_fn || ' ' || emp_sn) AS fullname,encode(user_image,'escape') AS user_image,pos_name,bpi_department.dept_code,div_code,bpi_department.department,is_logged_in,is_active,act_lockedout FROM bpi_user_accounts
            INNER JOIN prl_employee ON prl_employee.empno = bpi_user_accounts.empno
            INNER JOIN prl_position ON prl_position.pos_code = prl_employee.pos_code
            INNER JOIN bpi_department ON bpi_department.dept_code = bpi_user_accounts.department
            ORDER BY fullname ASC";
        $result_stmt = $BannerWeb->prepare($sqlstring);
        $result_stmt->execute();
        //* ======== Prepare Array ========
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            $nestedData = array();
            $nestedData['user_id'] = $row['user_id'];
            $nestedData['empno'] = $row['empno'];
            $nestedData['fullname'] = $row['fullname'];
            $nestedData['pos_name'] = $row['pos_name'];
            $nestedData['department'] = self::loadColoredJobPosition($row['dept_code'], $row['div_code'], $row['department']);
            $nestedData['user_image'] = 'src="data:image/jpeg;base64,' . $row['user_image'] . '"';
            $nestedData['is_logged_in'] = $row['is_logged_in'];
            $nestedData['is_active'] = $row['is_active'];
            $nestedData['act_lockedout'] = $row['act_lockedout'];
            $itemData_List[] = $nestedData;
        }
        return json_encode($itemData_List);
        $BannerWeb = null; //* ======== Close Connection ========
    }

    public function loadUserInfo($BannerWeb, $user_id)
    {
        $itemData_List = array();
        $sqlstring = "SELECT user_id,prl_employee.empno,(emp_fn || ' ' || emp_sn) AS fullname,encode(user_image,'escape') AS user_image,pos_name,bpi_department.dept_code,div_code,bpi_department.department,is_logged_in,is_active,act_lockedout FROM bpi_user_accounts
            INNER JOIN prl_employee ON prl_employee.empno = bpi_user_accounts.empno
            INNER JOIN prl_position ON prl_position.pos_code = prl_employee.pos_code
            INNER JOIN bpi_department ON bpi_department.dept_code = bpi_user_accounts.department
            WHERE user_id = ?";
        $result_stmt = $BannerWeb->prepare($sqlstring);
        $result_stmt->execute([$user_id]);
        //* ======== Prepare Array ========
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            $itemData_List['fullname'] = $row['fullname'];
            $itemData_List['pos_name'] = $row['pos_name'];
            $itemData_List['department'] = self::loadColoredJobPosition($row['dept_code'], $row['div_code'], $row['department']);
            $itemData_List['user_image'] = 'src="data:image/jpeg;base64,' . $row['user_image'] . '"';
        }
        return json_encode($itemData_List);
        $BannerWeb = null; //* ======== Close Connection ========

    }
}
