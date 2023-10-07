<?php
class ClientPortal
{
    private function sqlQuery($sqlstring, $connection)
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

    public function loadClientOrderTable($php_fetch_perso_status_api, $searchValue)
    {
        $itemData_List = array();
        //* ======== Create Array for column same with column names on database for ordering ========
        $col = array(
            0 => 'date_time',
            1 => 'customername',
            2 => 'joborder',
            3 => 'ponumber',
            4 => 'descriptions',
            5 => 'quantity_po',
            6 => 'quantity_order',
            7 => 'vault_good_card',
            8 => 'delivered_card'
        );
        //* ======== Fetch Data ========
        $sqlstring = "SELECT * FROM perso_status_summary WHERE 1 = 1";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_perso_status_api);
        $result_total_record = array_sum(array_map("count", $data_result));
        //* ======== Fetch Total Filtered Record ========
        if (!empty($searchValue)) {
            $sqlstring .= " AND (TO_CHAR(date_time, 'YYYY-MM-DD') ILIKE '%{$searchValue}%' OR customername ILIKE '%{$searchValue}%' OR ponumber ILIKE '%{$searchValue}%'
            OR descriptions ILIKE '%{$searchValue}%' OR joborder ILIKE '%{$searchValue}%' OR CAST(quantity_po AS TEXT) ILIKE '%{$searchValue}%' OR CAST(quantity_order AS TEXT) ILIKE '%{$searchValue}%' 
            OR CAST(vault_good_card AS TEXT) ILIKE '%{$searchValue}%' OR CAST(delivered_card AS TEXT) ILIKE '%{$searchValue}%')";
            $data_result = self::sqlQuery($sqlstring, $php_fetch_perso_status_api);
        }
        $result_total_record_filtered = array_sum(array_map("count", $data_result));
        //* ======== Ordering ========
        $sqlstring .= " ORDER BY {$col[$_POST['order'][0]['column']]} {$_POST['order'][0]['dir']} LIMIT {$_POST['length']} OFFSET {$_POST['start']};";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_perso_status_api);
        //* ======== Prepare Array ========
        foreach ($data_result['data'] as $row) {
            $itemData_List[] = array(
                date_format(date_create($row['date_time']), 'Y-m-d H:i:s A'),
                $row['customername'],
                $row['joborder'],
                $row['ponumber'],
                $row['descriptions'],
                number_format($row['quantity_po']),
                number_format($row['quantity_order']),
                number_format($row['vault_good_card']),
                number_format($row['delivered_card'])
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
    }

    public function loadUserAccountTable($php_fetch_perso_status_api, $searchValue)
    {
        $itemData_List = array();
        //* ======== Create Array for column same with column names on database for ordering ========
        $col = array(
            0 => 'realname',
            1 => 'username',
            2 => 'customername'
        );
        //* ======== Fetch Data ========
        $sqlstring = "SELECT * FROM useraccess WHERE 1 = 1";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_perso_status_api);
        $result_total_record = array_sum(array_map("count", $data_result));
        //* ======== Fetch Total Filtered Record ========
        if (!empty($searchValue)) {
            $sqlstring .= " AND (realname ILIKE '%{$searchValue}%' OR username ILIKE '%{$searchValue}%' OR customername ILIKE '%{$searchValue}%')";
            $data_result = self::sqlQuery($sqlstring, $php_fetch_perso_status_api);
        }
        $result_total_record_filtered = array_sum(array_map("count", $data_result));
        //* ======== Ordering ========
        $sqlstring .= " ORDER BY {$col[$_POST['order'][0]['column']]} {$_POST['order'][0]['dir']} LIMIT {$_POST['length']} OFFSET {$_POST['start']};";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_perso_status_api);
        //* ======== Prepare Array ========
        foreach ($data_result['data'] as $row) {
            $itemData_List[] = array(
                $row['realname'],
                $row['username'],
                $row['customername'],
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
    }

    public function saveClientUser($php_fetch_perso_status_api, $php_insert_perso_status_api, $client_fullname, $client_username, $client_password, $client_customer_name)
    {
        $chkExist = "SELECT username FROM useraccess WHERE username = '{$client_username}';";
        $data_result_exist = self::sqlQuery($chkExist, $php_fetch_perso_status_api);
        $row_count = array_sum(array_map("count", $data_result_exist));
        if ($row_count == 0) {
            $sqlstring = "INSERT INTO useraccess(username,pssword,realname,customername) VALUES('{$client_username}','" . strtoupper(md5($client_password)) . "','$client_fullname','{$client_customer_name}');";
            self::sqlQuery($sqlstring, $php_insert_perso_status_api);
            return json_encode('save');
        } else {
            return json_encode('existing');
        }
    }

    public function deleteClientPortalData($php_update_perso_status_api)
    {
        $sqlDel = "DELETE FROM perso_status_summary";
        self::sqlQuery($sqlDel, $php_update_perso_status_api);
    }

    public function updateClientPortalData($bannerdata_conn, $php_insert_perso_status_api)
    {
        $sqlfetch = "SELECT activity_id, date_time, REPLACE(customername,'''','`') AS customername, ponumber, po.quantity, per_perso_current_status_hsa_summary.orderid, joborder, REPLACE(descriptions,'''','`') AS descriptions, ordersinformation.quantity AS quantity_order, vault_good_card, total_cards_delivered FROM per_perso_current_status_hsa_summary
            INNER JOIN ordersinformation ON ordersinformation.orderid = per_perso_current_status_hsa_summary.orderid
            INNER JOIN po ON po.poid = ordersinformation.poid
            INNER JOIN tblper_assignment ON tblper_assignment.orderid = ordersinformation.orderid
            WHERE RIGHT(joborder,2) <> '-S' AND RIGHT(joborder,2) <> '-R' AND finished = false
            GROUP BY tblper_assignment.orderid,jonumber,per_perso_current_status_hsa_summary.activity_id,ponumber,po.quantity,descriptions,ordersinformation.quantity
            UNION ALL
            SELECT activity_id, date_time, REPLACE(customername,'''','`') AS customername, ponumber, po.quantity, per_perso_current_status_nonhsa_summary.orderid, joborder, REPLACE(descriptions,'''','`') AS descriptions, ordersinformation.quantity AS quantity_order, vault_good_card, total_cards_delivered FROM per_perso_current_status_nonhsa_summary
            INNER JOIN ordersinformation ON ordersinformation.orderid = per_perso_current_status_nonhsa_summary.orderid
            INNER JOIN po ON po.poid = ordersinformation.poid
            INNER JOIN tblper_assignment ON tblper_assignment.orderid = ordersinformation.orderid
            WHERE RIGHT(joborder,2) <> '-S' AND RIGHT(joborder,2) <> '-R' AND finished = false
            GROUP BY tblper_assignment.orderid,jonumber,per_perso_current_status_nonhsa_summary.activity_id,ponumber,po.quantity,descriptions,ordersinformation.quantity";
        $result_stmt_fetch = $bannerdata_conn->prepare($sqlfetch);
        $result_stmt_fetch->execute();
        while ($row_fetch = $result_stmt_fetch->fetch(PDO::FETCH_ASSOC)) {
            $sqlInsert = "INSERT INTO perso_status_summary(date_time,customername,ponumber,quantity_po,joborder,descriptions,quantity_order,vault_good_card,delivered_card) 
                VALUES('{$row_fetch['date_time']}','{$row_fetch['customername']}','{$row_fetch['ponumber']}','{$row_fetch['quantity']}','{$row_fetch['joborder']}','{$row_fetch['descriptions']}','{$row_fetch['quantity_order']}','{$row_fetch['vault_good_card']}','{$row_fetch['total_cards_delivered']}');";
            self::sqlQuery($sqlInsert, $php_insert_perso_status_api);
        }
        $bannerdata_conn = null;
    }

    public function loadCustomerName($bannerdata_conn)
    {
        $itemData_List = array();
        $sqlstring = "SELECT DISTINCT customername FROM per_perso_current_status_hsa_summary
            INNER JOIN ordersinformation ON ordersinformation.orderid = per_perso_current_status_hsa_summary.orderid
            INNER JOIN po ON po.poid = ordersinformation.poid
            WHERE RIGHT(joborder,2) <> '-S' AND RIGHT(joborder,2) <> '-R'
            UNION ALL
            SELECT DISTINCT customername FROM per_perso_current_status_nonhsa_summary
            INNER JOIN ordersinformation ON ordersinformation.orderid = per_perso_current_status_nonhsa_summary.orderid
            INNER JOIN po ON po.poid = ordersinformation.poid
            WHERE RIGHT(joborder,2) <> '-S' AND RIGHT(joborder,2) <> '-R';";
        $result_stmt = $bannerdata_conn->prepare($sqlstring);
        $result_stmt->execute();
        //* ======== Prepare Array ========
        foreach ($result_stmt->fetchAll() as $row) {
            $itemData_List[$row['customername']] = $row['customername'];
        }
        return json_encode($itemData_List);
        $bannerdata_conn = null;
    }
}
