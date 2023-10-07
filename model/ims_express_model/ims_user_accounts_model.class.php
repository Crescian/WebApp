<?php
class ImsUserAccounts
{
    public function sqlQuery($sqlstring, $connection)
    {
        $data_base64 = base64_encode($sqlstring);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $connection);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, array("data" => $data_base64));
        $json_response = curl_exec($curl);
        //* ====== Close Connection ======
        curl_close($curl);
        return json_decode($json_response, true);
    }

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

    public function loadUserList($php_fetch_bannerweb_api)
    {
        $itemData_List = array();
        $sqlstring = "SELECT DISTINCT user_id,prl_employee.empno,(emp_fn || ' ' || emp_sn) AS fullname,encode(user_image,'escape') AS user_image,pos_name,bpi_department.dept_code,div_code,bpi_department.department,is_logged_in,is_active,act_lockedout FROM bpi_user_accounts
            INNER JOIN prl_employee ON prl_employee.empno = bpi_user_accounts.empno
            INNER JOIN prl_position ON prl_position.pos_code = prl_employee.pos_code
            INNER JOIN bpi_department ON bpi_department.dept_code = bpi_user_accounts.department
            INNER JOIN bpi_access_module ON bpi_access_module.access_user = bpi_user_accounts.empno
            WHERE app_id = '7'
            ORDER BY fullname ASC";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_bannerweb_api);
        // * ======== Prepare Array ========
        foreach ($data_result['data'] as $row) {
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
    }

    public function saveUserAccess($php_insert_ims_express_api, $inTable, $inField1, $inField2, $access_id, $empno)
    {
        $sqlstring = "INSERT INTO {$inTable}({$inField1},{$inField2}) VALUES('{$access_id}','{$empno}')";
        self::sqlQuery($sqlstring, $php_insert_ims_express_api);
    }

    public function deleteUserAccess($php_update_ims_express_api, $inTable, $inField, $empno)
    {
        $delAccess = "DELETE FROM {$inTable} WHERE {$inField} = '{$empno}'";
        self::sqlQuery($delAccess, $php_update_ims_express_api);
    }

    public function loadUserAccess($php_fetch_ims_express_api, $inTable, $inField1, $inField2, $empno)
    {
        $itemData_List = array();
        $sqlstring = "SELECT * FROM {$inTable} WHERE {$inField2} = '{$empno}'";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_ims_express_api);
        // * ======== Prepare Array ========
        foreach ($data_result['data'] as $row) {
            $itemData_List[] = $row[$inField1];
        }
        return json_encode($itemData_List);
    }

    public function loadEmployeeInfo($php_fetch_bannerweb_api, $userid)
    {
        $itemData_List = array();
        $sqlstring = "SELECT user_id,username,prl_employee.empno,(emp_fn || ' ' || emp_sn) AS fullname,encode(user_image,'escape') AS user_image,pos_name,bpi_department.dept_code,is_active,user_email FROM bpi_user_accounts
            INNER JOIN prl_employee ON prl_employee.empno = bpi_user_accounts.empno
            INNER JOIN prl_position ON prl_position.pos_code = prl_employee.pos_code
            INNER JOIN bpi_department ON bpi_department.dept_code = bpi_user_accounts.department
            WHERE user_id = '{$userid}'";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_bannerweb_api);
        // * ======== Prepare Array ========
        foreach ($data_result['data'] as $row) {
            $itemData_List['dept_code'] = $row['dept_code'];
            $itemData_List['empno'] = $row['empno'];
            $itemData_List['pos_name'] = $row['pos_name'];
            $itemData_List['user_email'] = $row['user_email'];
            $itemData_List['username'] = $row['username'];
            $itemData_List['user_image'] = $row['user_image'];
            $itemData_List['is_active'] = $row['is_active'];
        }
        return json_encode($itemData_List);
    }

    public function loadSelectValueWithId($php_fetch_bannerweb_api, $inFieldId, $inField, $inTable, $inOrder)
    {
        $itemData_List = array();
        $sqlstring = "SELECT DISTINCT " . $inFieldId . "," . $inField . " FROM " . $inTable . " ORDER BY " . $inField . " " . $inOrder . "";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_bannerweb_api);
        $row_count = array_sum(array_map("count", $data_result));
        //* ======== Prepare Array ========
        if ($row_count > 0) {
            foreach ($data_result['data'] as $row) {
                $itemData_List[$row[$inFieldId]] = $row[$inField];
            }
        }
        $itemData_List ??= null;
        return json_encode($itemData_List);
    }

    public function loadSelectEmployee($php_fetch_bannerweb_api, $inFieldId, $inField, $inTable, $inConditionValue, $inOrder)
    {
        $itemData_List = array();
        $sqlstring = "SELECT {$inFieldId},(emp_fn || ' ' || emp_sn) AS {$inField} FROM {$inTable} WHERE dept_code = '{$inConditionValue}' ORDER BY {$inField} {$inOrder}";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_bannerweb_api);
        $row_count = array_sum(array_map("count", $data_result));
        //* ======== Prepare Array ========
        if ($row_count > 0) {
            foreach ($data_result['data'] as $row) {
                $itemData_List[$row[$inFieldId]] = $row[$inField];
            }
        }
        $itemData_List ??= null;
        return json_encode($itemData_List);
    }

    public function loadEmployeeJobTitle($php_fetch_bannerweb_api, $empno)
    {
        $itemData_List = array();
        $sqlstring = "SELECT prl_position.pos_name FROM prl_employee
            INNER JOIN prl_position ON prl_position.pos_code = prl_employee.pos_code WHERE empno = '{$empno}'";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_bannerweb_api);
        //* ======== Prepare Array ========
        foreach ($data_result['data'] as $row) {
            $itemData_List['job_title'] = $row['pos_name'];
        }
        return json_encode($itemData_List);
    }

    public function loadMenuTree($php_fetch_ims_express_api, $inTable, $inFieldId, $inField1, $inField2)
    {
        $itemData_List = array();
        $sqlstring = "SELECT CAST({$inFieldId} AS varchar) AS id,{$inField1} AS text, CASE WHEN CAST({$inField2} AS varchar) = '0' THEN '#' ELSE CAST({$inField2} AS varchar) END AS parent 
            FROM {$inTable}";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_ims_express_api);
        $row_count = array_sum(array_map("count", $data_result));
        //* ======== Prepare Array ========
        if ($row_count > 0) {
            foreach ($data_result['data'] as $row) {
                $app_menu[] = $row;
            }
            //* build array of item references
            foreach ($app_menu as $key => $item) {
                $itemData_List[$item['id']] = $item;
            }
            return json_encode($app_menu);
        } else {
            return json_encode('empty');
        }
    }
}
