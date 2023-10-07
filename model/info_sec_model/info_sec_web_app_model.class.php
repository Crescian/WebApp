<?php
date_default_timezone_set('Asia/Manila');
class WebRequest
{
    public function webStatus($webStats)
    {
        switch ($webStats) {
            case 'Pending':
                $webStatus = '<span class="badge bg-warning col-sm-12 fs-18">Pending</span>';
                break;
            case 'On-Going':
                $webStatus = '<span class="badge bg-success col-sm-12 fs-18">On-Going</span>';
                break;
            case 'Done':
                $webStatus = '<span class="badge bg-dark col-sm-12 fs-18">Done</span>';
                break;
        }
        return $webStatus;
    }

    public function fetchSignature($emp_name, $bannerWeb)
    {
        $sqlstring = "SELECT encode(employee_signature, 'escape') as employee_signature FROM bpi_employee_signature WHERE emp_name = ?";
        $result_stmt = $bannerWeb->prepare($sqlstring);
        $result_stmt->execute([$emp_name]);
        while ($result_row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            return $result_row['employee_signature'];
        }
        $bannerWeb = null; //* ======== Close Connection ========
    }

    public function loadTableRequest($infoSec, $statusVal, $searchValue)
    {
        $itemData_List = array();
        //* ======== Create Array for column same with column names on database for ordering ========
        $col = array(
            0 => 'control_no',
            1 => 'prepared_by',
            2 => 'date_requested',
            3 => 'date_needed',
            4 => 'service_type',
            5 => 'application_name',
            6 => 'req_description',
            7 => 'web_priority'
        );
        //* =========== Fetch Total Record Data ===========
        $sqlstring = "SELECT * FROM info_sec_web_app_request WHERE web_status = '{$statusVal}'";
        $result_stmt = $infoSec->prepare($sqlstring);
        $result_stmt->execute();
        $result_total_record = $result_stmt->rowCount();
        //* =========== Fetch Total Filtered Record Data ===========
        if (!empty($searchValue)) {
            $sqlstring .= " AND (control_no ILIKE '%{$searchValue}%' OR prepared_by ILIKE '%{$searchValue}%' OR TO_CHAR(date_requested, 'YYYY-MM-DD') ILIKE '%{$searchValue}%'
                OR TO_CHAR(date_needed, 'YYYY-MM-DD') ILIKE '%{$searchValue}%' OR service_type ILIKE '%{$searchValue}%' OR application_name ILIKE '%{$searchValue}%' OR req_description ILIKE '%{$searchValue}%')";
            $result_stmt = $infoSec->prepare($sqlstring);
            $result_stmt->execute();
        }
        $result_total_record_filtered = $result_stmt->rowCount();
        //* ======== Ordering ========
        $sqlstring .= " ORDER BY {$col[$_POST['order'][0]['column']]} {$_POST['order'][0]['dir']} LIMIT {$_POST['length']} OFFSET {$_POST['start']}";
        $result_stmt = $infoSec->prepare($sqlstring);
        $result_stmt->execute();
        //* ======== Prepare Array ========
        foreach ($result_stmt->fetchAll() as $row) {
            $itemData_List[] = array(
                $row['control_no'],
                $row['prepared_by'],
                $row['date_requested'],
                $row['date_needed'],
                $row['service_type'],
                $row['application_name'],
                $row['req_description'],
                $row['web_priority'],
                [
                    $row['webappid'],
                    $row['web_status'],
                    $row['received_by'] == '' ? '-' : $row['received_by'],
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
        $infoSec = null; //* ======== Close Connection ========
    }
    public function loadRequestCount($infoSec)
    {
        $sqlstring = "SELECT web_status, COUNT(*) AS count FROM info_sec_web_app_request GROUP BY web_status";
        $result_stmt = $infoSec->prepare($sqlstring);
        $result_stmt->execute();
        //* ======== Prepare Array ========
        if ($result_stmt->rowCount() > 0) foreach ($result_stmt->fetchAll() as $row)  $data[$row['web_status']] = sprintf("%03d", $row['count']);
        $data ??= [null];
        return json_encode($data);
        $infoSec = null; //* ======== Close Connection ========
    }

    public function acknowledgeRequest($infoSec, $bannerWeb, $webappid, $logged_user)
    {
        $receivedBySignature = self::fetchSignature($logged_user, $bannerWeb);
        $sqlstring = "UPDATE info_sec_web_app_request SET received_by = ?, received_by_sign = ?, received_by_date = ? WHERE webappid = ?";
        $result_stmt = $infoSec->prepare($sqlstring);
        $result_stmt->execute([$logged_user, $receivedBySignature, date('Y-m-d'), $webappid]);
        $infoSec = null; //* ======== Close Connection ========
    }

    public function processRequest($infoSec, $webappid, $logged_user)
    {
        $chkReceiver = "SELECT * FROM info_sec_web_app_request WHERE webappid = ? AND received_by = ?";
        $receiver_stmt = $infoSec->prepare($chkReceiver);
        $receiver_stmt->execute([$webappid, $logged_user]);
        if ($receiver_stmt->rowCount() > 0) {
            $sqlstring = "UPDATE info_sec_web_app_request SET web_status = 'Ongoing', acknowledged_date = ? WHERE webappid = ?";
            $result_stmt = $infoSec->prepare($sqlstring);
            $result_stmt->execute([date('Y-m-d'), $webappid]);

            $itemData_List = 'same';
        } else {
            $itemData_List = 'not same';
        }
        return json_encode($itemData_List);
        $infoSec = null; //* ======== Close Connection ========
    }

    public function accomplishRequest($infoSec, $webappid)
    {
        $sqlstring = "UPDATE info_sec_web_app_request SET web_status = 'Done', finish_date = ? WHERE webappid = ?";
        $result_stmt = $infoSec->prepare($sqlstring);
        $result_stmt->execute([date('Y-m-d'), $webappid]);
        $infoSec = null; //* ======== Close Connection ========
    }

    public function loadRequestDetails($infoSec, $webappid)
    {
        $itemData_List = array();
        $sqlstring = "SELECT * FROM info_sec_web_app_request WHERE webappid = ?";
        $result_stmt = $infoSec->prepare($sqlstring);
        $result_stmt->execute([$webappid]);
        //* ======== Prepare Array ========
        foreach ($result_stmt->fetchAll() as $row) {
            $itemData_List['date_requested'] = $row['date_requested'];
            $itemData_List['date_needed'] = $row['date_needed'];
            $itemData_List['web_priority'] = $row['web_priority'];
            $itemData_List['service_type'] = $row['service_type'];
            $itemData_List['application_name'] = $row['application_name'];
            $itemData_List['req_description'] = $row['req_description'];
            $itemData_List['prepared_by'] = $row['prepared_by'];
            $itemData_List['approved_by'] = $row['approved_by'];
            $itemData_List['received_by'] = $row['received_by'] == '' ? '-' : $row['received_by'];
            $itemData_List['noted_by'] = $row['noted_by'];
            $itemData_List['web_status'] = self::webStatus($row['web_status']);
        }
        return json_encode($itemData_List);
        $infoSec = null; //* ======== Close Connection ========
    }
}
