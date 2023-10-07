<?php
date_default_timezone_set('Asia/Manila');
class ITRsmMonitoring
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
    public function load_table_rsm($WHPO, $statusVal, $searchValue)
    {
        $itemData_List = array();
        //* ======== Create Array for column same with column names on database for ordering ========
        $col = array(
            0 => 'rsmnumber',
            1 => 'code',
            2 => 'description',
            3 => 'rsmquantity',
            4 => 'purchasemeasure',
            5 => 'remarks'
        );
        //* =========== Fetch Total Record Data ===========
        switch ($statusVal) {
            case 'All':
                $sqlstring = "SELECT rsmheader.rsmnumber,rsmdetail.code,rsmdetail.description,rsmquantity,rsmdetail.purchasemeasure,rsmdetail.remarks FROM rsmdetail LEFT JOIN prdetail ON prdetail.rsmno = rsmdetail.rsmnumber AND rsmdetail.code = prdetail.code
                        LEFT JOIN podetail ON podetail.prnumber = prdetail.prnumber AND podetail.code = prdetail.code 
                        LEFT JOIN rsmheader ON rsmheader.rsmnumber = rsmdetail.rsmnumber 
                        WHERE department = 'Information Technology' AND dateprepared > '2023-01-01'";
                break;
            case 'Ongoing':
                $sqlstring = "SELECT rsmheader.rsmnumber,rsmdetail.code,rsmdetail.description,rsmquantity,rsmdetail.purchasemeasure,rsmdetail.remarks FROM rsmdetail LEFT JOIN prdetail ON prdetail.rsmno = rsmdetail.rsmnumber AND rsmdetail.code = prdetail.code
                        LEFT JOIN podetail ON podetail.prnumber = prdetail.prnumber AND podetail.code = prdetail.code 
                        LEFT JOIN rsmheader ON rsmheader.rsmnumber = rsmdetail.rsmnumber 
                        WHERE department = 'Information Technology' AND prdetail.prnumber is null 
                        AND dateprepared > '2023-01-01' AND rsmdetail.closed = 'true'";
                break;
            case 'Finished':
                $sqlstring = "SELECT rsmheader.rsmnumber,rsmdetail.code,rsmdetail.description,rsmquantity,rsmdetail.purchasemeasure,rsmdetail.remarks FROM rsmdetail LEFT JOIN prdetail ON prdetail.rsmno = rsmdetail.rsmnumber AND rsmdetail.code = prdetail.code
                        LEFT JOIN podetail ON podetail.prnumber = prdetail.prnumber AND podetail.code = prdetail.code 
                        LEFT JOIN rsmheader ON rsmheader.rsmnumber = rsmdetail.rsmnumber 
                        WHERE department = 'Information Technology' AND prdetail.prnumber is NOT null 
                        AND dateprepared > '2023-01-01' AND rsmdetail.closed = 'false'";
                break;
        }
        $result_stmt = $WHPO->prepare($sqlstring);
        $result_stmt->execute();
        $result_total_record = $result_stmt->rowCount();
        // //* =========== Fetch Total Filtered Record Data ===========
        if (!empty($searchValue)) {
            $sqlstring .= " AND (rsmheader.rsmnumber ILIKE '%{$searchValue}%' OR rsmdetail.code ILIKE '%{$searchValue}%' OR rsmdetail.description ILIKE '%{$searchValue}%' OR CAST(rsmquantity AS TEXT) ILIKE '%{$searchValue}%' 
                OR rsmdetail.purchasemeasure ILIKE '%{$searchValue}%' OR rsmdetail.remarks ILIKE '%{$searchValue}%')";
            $result_stmt = $WHPO->prepare($sqlstring);
            $result_stmt->execute();
        }
        $result_total_record_filtered = $result_stmt->rowCount();
        // //* ======== Ordering ========
        $sqlstring .= " ORDER BY dateprepared DESC, {$col[$_POST['order'][0]['column']]} {$_POST['order'][0]['dir']} LIMIT {$_POST['length']} OFFSET {$_POST['start']};";
        $result_stmt = $WHPO->prepare($sqlstring);
        $result_stmt->execute();
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            $itemData_List[] = array(
                $row['rsmnumber'],
                $row['code'] == '' ? '-' : $row['code'],
                $row['description'],
                $row['rsmquantity'],
                $row['purchasemeasure'],
                $row['remarks']
            );
        }
        // //* ======== Output Data ========
        $output = array(
            'draw'                  =>  intval($_POST['draw']),
            'iTotalRecords'         =>  $result_total_record,
            'iTotalDisplayRecords'  =>  $result_total_record_filtered,
            'data'                  =>  $itemData_List
        );
        //* ======== Send Data as JSON Format ========
        return json_encode($output);
    }
    public function load_rsm_count($WHPO)
    {
        $sqlstringAll = "SELECT COUNT(*) as count FROM rsmdetail LEFT JOIN prdetail ON prdetail.rsmno = rsmdetail.rsmnumber AND rsmdetail.code = prdetail.code
                        LEFT JOIN podetail ON podetail.prnumber = prdetail.prnumber AND podetail.code = prdetail.code 
                        LEFT JOIN rsmheader ON rsmheader.rsmnumber = rsmdetail.rsmnumber 
                        WHERE department = 'Information Technology' AND dateprepared > '2023-01-01';";
        $result_stmt = $WHPO->prepare($sqlstringAll);
        $result_stmt->execute();
        $result_total_record = $result_stmt->rowCount();
        $result_stmt_res = $result_stmt->fetchAll();
        if ($result_total_record > 0) {
            foreach ($result_stmt_res as $row) {
                $itemData_List['all'] =  $row['count'];
            }
        }
        $sqlstringOngoing = "SELECT COUNT(*) as count FROM rsmdetail LEFT JOIN prdetail ON prdetail.rsmno = rsmdetail.rsmnumber AND rsmdetail.code = prdetail.code
                        LEFT JOIN podetail ON podetail.prnumber = prdetail.prnumber AND podetail.code = prdetail.code 
                        LEFT JOIN rsmheader ON rsmheader.rsmnumber = rsmdetail.rsmnumber 
                        WHERE department = 'Information Technology' AND prdetail.prnumber is null 
                        AND dateprepared > '2023-01-01' AND rsmdetail.closed = 'true';";
        $result_stmt = $WHPO->prepare($sqlstringOngoing);
        $result_stmt->execute();
        $result_total_record = $result_stmt->rowCount();
        $result_stmt_res = $result_stmt->fetchAll();
        if ($result_total_record > 0) {
            foreach ($result_stmt_res as $row) {
                $itemData_List['ongoing'] =  $row['count'];
            }
        }
        $sqlstringFinished = "SELECT COUNT(*) as count FROM rsmdetail LEFT JOIN prdetail ON prdetail.rsmno = rsmdetail.rsmnumber AND rsmdetail.code = prdetail.code
                        LEFT JOIN podetail ON podetail.prnumber = prdetail.prnumber AND podetail.code = prdetail.code 
                        LEFT JOIN rsmheader ON rsmheader.rsmnumber = rsmdetail.rsmnumber 
                        WHERE department = 'Information Technology' AND prdetail.prnumber is NOT null 
                        AND dateprepared > '2023-01-01' AND rsmdetail.closed = 'false';";
        $result_stmt = $WHPO->prepare($sqlstringFinished);
        $result_stmt->execute();
        $result_total_record = $result_stmt->rowCount();
        $result_stmt_res = $result_stmt->fetchAll();
        if ($result_total_record > 0) {
            foreach ($result_stmt_res as $row) {
                $itemData_List['finished'] =  $row['count'];
            }
        }
        return json_encode($itemData_List);
    }
}
