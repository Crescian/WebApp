<?php
date_default_timezone_set('Asia/Manila');
class LtoSerial
{
    public function serialStatus($serialStats)
    {
        switch ($serialStats) {
            case '0':
                $serialStatus = '<span class="badge bg-danger col-sm-12">Not Scanned</span>';
                break;
            case '1':
                $serialStatus = '<span class="badge bg-success col-sm-12">Already Scanned</span>';
                break;
        }
        return $serialStatus;
    }

    public function loadLtoSerialData($lto_conn, $searchValue, $inFilter, $startSerial, $endSerial, $inStatus)
    {
        $itemData_List = array();
        //* ======== Create Array for column same with column names on database for ordering ========
        $col = array(
            0 => 'tmprocess',
            1 => 'serialno',
            2 => 'username',
            3 => 'status'
        );
        //* =========== Fetch Total Record Data ===========
        if ($inFilter == 'Yes') {
            $sqlstring = "SELECT * FROM osrserialno WHERE serialno BETWEEN '{$startSerial}' AND '{$endSerial}' AND status = '{$inStatus}'";
        } else {
            $sqlstring = "SELECT * FROM osrserialno WHERE 1 = 1";
        }
        $result_stmt = $lto_conn->prepare($sqlstring);
        $result_stmt->execute();
        $result_total_record = $result_stmt->rowCount();
        //* =========== Fetch Total Filtered Record Data ===========
        if (!empty($searchValue)) {
            $sqlstring .= " AND (TO_CHAR(tmprocess, 'YYYY-MM-DD') ILIKE '%{$searchValue}%' OR serialno ILIKE '%{$searchValue}%' OR username ILIKE '%{$searchValue}%' OR to_char(status, '9') ILIKE '%{$searchValue}%')";
            $result_stmt = $lto_conn->prepare($sqlstring);
            $result_stmt->execute();
        }
        $result_total_record_filtered = $result_stmt->rowCount();
        //* ======== Ordering ========
        $sqlstring .= " ORDER BY {$col[$_POST['order'][0]['column']]} {$_POST['order'][0]['dir']} LIMIT {$_POST['length']} OFFSET {$_POST['start']}";
        $result_stmt = $lto_conn->prepare($sqlstring);
        $result_stmt->execute();
        //* ======== Prepare Array ========
        foreach ($result_stmt->fetchAll() as $row) {
            $itemData_List[] = array(
                $row['tmprocess'] == '' ? '----/--/-- --:--:--' : $row['tmprocess'],
                $row['serialno'],
                $row['username'] == '' ? '-' : $row['username'],
                self::serialStatus($row['status']),
                [$row['serialid'], $row['status'], $row['manual_update']]
            );
        }
        //* ======== Output Data ========
        $output = array(
            'draw'                  =>  intval($_POST['draw']),
            'iTotalRecords'         =>  $result_total_record,
            'iTotalDisplayRecords'  =>  $result_total_record_filtered,
            'data'                  =>  $itemData_List
        );
        //* ======== Send Data as JSON Format ========
        return json_encode($output);
        $lto_conn = null; //* ======== Close Connection ========
    }

    public function loadUserData($lto_conn, $searchValue)
    {
        $itemData_List = array();
        //* ======== Create Array for column same with column names on database for ordering ========
        $col = array(
            0 => 'username',
        );
        //* =========== Fetch Total Record Data ===========
        $sqlstring = "SELECT * FROM osruseraccess WHERE 1 = 1";
        $result_stmt = $lto_conn->prepare($sqlstring);
        $result_stmt->execute();
        $result_total_record = $result_stmt->rowCount();
        //* =========== Fetch Total Filtered Record Data ===========
        if (!empty($searchValue)) {
            $sqlstring .= " AND (username ILIKE '%{$searchValue}%')";
            $result_stmt = $lto_conn->prepare($sqlstring);
            $result_stmt->execute();
        }
        $result_total_record_filtered = $result_stmt->rowCount();
        //* ======== Ordering ========
        $sqlstring .= " ORDER BY {$col[$_POST['order'][0]['column']]} {$_POST['order'][0]['dir']} LIMIT {$_POST['length']} OFFSET {$_POST['start']}";
        $result_stmt = $lto_conn->prepare($sqlstring);
        $result_stmt->execute();
        //* ======== Prepare Array ========
        foreach ($result_stmt->fetchAll() as $row) {
            $itemData_List[] = array(
                $row['username'],
                [
                    $row['userid'],
                    $row['username']
                ]
            );
        }
        //* ======== Output Data ========
        $output = array(
            'draw'                  =>  intval($_POST['draw']),
            'iTotalRecords'         =>  $result_total_record,
            'iTotalDisplayRecords'  =>  $result_total_record_filtered,
            'data'                  =>  $itemData_List
        );
        //* ======== Send Data as JSON Format ========
        return json_encode($output);
        $lto_conn = null; //* ======== Close Connection ========
    }

    public function loadSerialInfo($lto_conn, $serialid)
    {
        $itemData_List = array();
        $sqlstring = "SELECT * FROM osrserialno WHERE serialid = '{$serialid}'";
        $result_stmt = $lto_conn->prepare($sqlstring);
        $result_stmt->execute();
        //* ======== Prepare Array ========
        foreach ($result_stmt->fetchAll() as $row) {
            $itemData_List['manual_by'] = $row['manual_by'];
            $itemData_List['manual_datetime'] = $row['manual_datetime'];
            $itemData_List['manual_remarks'] = $row['manual_remarks'];
            $itemData_List['serialno'] = $row['serialno'];
        }
        return json_encode($itemData_List);
        $lto_conn = null; //* ======== Close Connection ========
    }

    public function saveUserData($lto_conn, $user_name, $user_pass)
    {
        $chkExist = "SELECT * FROM osruseraccess WHERE username = '{$user_name}'";
        $chkExist_stmt = $lto_conn->prepare($chkExist);
        $chkExist_stmt->execute();
        if ($chkExist_stmt->rowCount() > 0) {
            return json_encode('exist');
        } else {
            $sqlstring = "INSERT INTO osruseraccess(username,userpass,realname) VALUES('{$user_name}','{$user_pass}','{$user_name}')";
            $result_stmt = $lto_conn->prepare($sqlstring);
            $result_stmt->execute();
            return json_encode('success');
        }
        $lto_conn = null; //* ======== Close Connection ========
    }

    public function removeUserData($lto_conn, $userid)
    {
        $sqlstring = "DELETE FROM osruseraccess WHERE userid = '{$userid}'";
        $result_stmt = $lto_conn->prepare($sqlstring);
        $result_stmt->execute();
        $lto_conn = null; //* ======== Close Connection ========
    }

    public function updateUserPass($lto_conn, $userid, $new_pass)
    {
        $sqlstring = "UPDATE osruseraccess SET userpass = '{$new_pass}' WHERE userid = '{$userid}'";
        $result_stmt = $lto_conn->prepare($sqlstring);
        $result_stmt->execute();
        $lto_conn = null; //* ======== Close Connection ========
    }

    public function updateSerialStatus($lto_conn, $serialid, $logged_user, $manual_remarks)
    {
        $manual_date = date('Y-m-d H:i:s');

        $sqlstring = "UPDATE osrserialno SET status = '1', manual_update = true, username = '{$logged_user}',tmprocess = '{$manual_date}', manual_by = '{$logged_user}', manual_remarks = '{$manual_remarks}', manual_datetime = '{$manual_date}' WHERE serialid = '{$serialid}'";
        $result_stmt = $lto_conn->prepare($sqlstring);
        $result_stmt->execute();
        $lto_conn = null; //* ======== Close Connection ========
    }

    public function insertSerialData($lto_conn, $serialNo)
    {
        if ($serialNo != '') { //* ======== Check if Empty String ========
            $sqlstring = "INSERT INTO osrserialno(serialno) VALUES('{$serialNo}')";
            $result_stmt = $lto_conn->prepare($sqlstring);
            $result_stmt->execute();
        }
        $lto_conn = null; //* ======== Close Connection ========
    }
}
