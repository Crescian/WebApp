<?php
date_default_timezone_set('Asia/Manila');
class ITDefectiveItems
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
    public function generate_defective_refno($php_fetch_itasset_api, $inField, $inTable)
    {
        $currYear = date('y');
        $fetchRefNo = "SELECT " . $inField . " FROM " . $inTable . ";";
        $data_result = self::sqlQuery($fetchRefNo, $php_fetch_itasset_api);
        foreach ($data_result['data'] as $fetchRefNo_row) {
            $getYear =  substr($fetchRefNo_row[$inField], 5, 2);
        }
        if ($currYear != $getYear) {
            $defective_ref_no = '0001-' . $currYear;
        } else {
            $currCount = substr($fetchRefNo_row[$inField], 0, 4);
            $counter = intval($currCount) + 1;
            $defective_ref_no = str_pad($counter, 4, '0', STR_PAD_LEFT) . '-' . $currYear;
        }
        return $defective_ref_no;
    }
    public function loadTableDefective($php_fetch_itasset_api, $filter, $search)
    {
        $columns = array(
            0 => 'item',
            1 => 'description',
            2 => 'issued_by',
            3 => 'date_issued',
            4 => 'status',
        );
        //* =========== Fetch Total Record Data ===========
        $query = "SELECT status, description, date_issued, issued_by, item 
        FROM tblit_hardware_issuance_employee
        WHERE status = '{$filter}'
        UNION
        SELECT status, description, date_issued, issued_by, item 
        FROM tblit_hardware_issuance_machine
        WHERE status = '{$filter}' ";
        $data_result = self::sqlQuery($query, $php_fetch_itasset_api);
        $totalRecord = array_sum(array_map("count", $data_result));
        if (!empty($search)) { //* =========== Fetch Total Filtered Record Data ===========
            $query .= " AND (
                item ILIKE '%{$search}%'
                OR description ILIKE '%{$search}%'
                OR issued_by ILIKE '%{$search}%'
                OR status ILIKE '%{$search}%'
                OR TO_CHAR(date_issued, 'YYYY-MM-DD') ILIKE '%{$search}%'
              ) ";
        }
        $data_result = self::sqlQuery($query, $php_fetch_itasset_api);
        $totalFilteredRecord = array_sum(array_map("count", $data_result));
        //* ======== Ordering ========
        $query .= "ORDER BY {$columns[$_POST['order'][0]['column']]} {$_POST['order'][0]['dir']} LIMIT {$_POST['length']} OFFSET {$_POST['start']};";
        $data = array();
        $data_result = self::sqlQuery($query, $php_fetch_itasset_api);
        // //* ======== Prepare Array ========
        foreach ($data_result['data'] as $row) {
            $data[] = array(
                $row['item'],
                $row['description'],
                $row['issued_by'],
                $row['date_issued'],
                ($row['status'] == 'Defective' || 'Recycled') ? '<span class="badge rounded-pill shadow text-bg-danger w-100">' . $row['status'] . '</span>' : ''
            );
        }
        $json = array( //* ====== Output Data Array ======
            "draw"                    =>  intval($_POST["draw"]),
            "iTotalRecords"           =>  $totalRecord,
            "iTotalDisplayRecords"    =>  $totalFilteredRecord,
            "data"                    =>  $data
        );
        //* ====== Return Data as JSON Format ======
        return json_encode($json);
    }
    public function recycleDefective($php_update_itasset_api, $control_no, $filter)
    {
        $updateRefno = "UPDATE tblit_control_no SET defective_control_no = '{$control_no}';";
        self::sqlQuery($updateRefno, $php_update_itasset_api);
        $queryEmployee = "UPDATE tblit_hardware_issuance_employee SET status = 'Recycled' WHERE status = '{$filter}';";
        self::sqlQuery($queryEmployee, $php_update_itasset_api);
        $queryMachine = "UPDATE tblit_hardware_issuance_machine SET status = 'Recycled' WHERE status = '{$filter}';";
        self::sqlQuery($queryMachine, $php_update_itasset_api);
    }
}
