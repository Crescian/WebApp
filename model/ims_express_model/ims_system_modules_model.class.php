<?php
class ImsSystemModules
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

    public function loadSysModMenuTable($php_fetch_ims_express_api, $inTable, $inFieldId, $inField1, $inField2, $searchValue)
    {
        $itemData_List = array();
        //* ======== Create Array for column same with column names on database for ordering ========
        $col = array(
            0 => $inField1,
            1 => $inField2,
        );
        //* ======== Fetch Data ========
        $sqlstring = "SELECT {$inTable}.{$inFieldId},{$inTable}.{$inField1},
            CASE WHEN {$inTable}.{$inField2} = 0 THEN '-' ELSE doc_parent.{$inField1} END AS menu_parent 
            FROM {$inTable}
            LEFT JOIN (SELECT * FROM {$inTable}) AS doc_parent ON doc_parent.{$inFieldId} = {$inTable}.{$inField2} WHERE 1 = 1";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_ims_express_api);
        $result_total_record = array_sum(array_map("count", $data_result));
        //* ======== Fetch Total Filtered Record ========
        if (!empty($searchValue)) {
            $sqlstring .= " AND {$inTable}.{$inField1} ILIKE '%{$searchValue}%'";
            $data_result = self::sqlQuery($sqlstring, $php_fetch_ims_express_api);
        }
        $result_total_record_filtered = array_sum(array_map("count", $data_result));
        //* ======== Ordering ========
        $sqlstring .= " ORDER BY {$col[$_POST['order'][0]['column']]} {$_POST['order'][0]['dir']} LIMIT {$_POST['length']} OFFSET {$_POST['start']}";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_ims_express_api);
        //* ======== Prepare Array ========
        foreach ($data_result['data'] as $row) {
            $itemData_List[] = array(
                $row[$inField1],
                $row['menu_parent'],
                $row[$inFieldId]
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

    public function loadDocumentMenuTable($php_fetch_ims_express_api, $searchValue)
    {
        $itemData_List = array();
        //* ======== Create Array for column same with column names on database for ordering ========
        $col = array(
            0 => 'doc_menu_title',
            1 => 'doc_menu_link',
            2 => 'doc_menu_parent'
        );
        //* ======== Fetch Data ========
        $sqlstring = "SELECT ims_document_menu.docmenuid,ims_document_menu.doc_menu_title,ims_document_menu.doc_menu_link,
            CASE WHEN ims_document_menu.doc_menu_parent_id = 0 THEN '-' ELSE doc_parent.doc_menu_title END AS doc_menu_parent 
            FROM ims_document_menu
            LEFT JOIN (SELECT * FROM ims_document_menu) AS doc_parent ON doc_parent.docmenuid = ims_document_menu.doc_menu_parent_id WHERE 1 = 1";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_ims_express_api);
        $result_total_record = array_sum(array_map("count", $data_result));
        //* ======== Fetch Total Filtered Record ========
        if (!empty($searchValue)) {
            $sqlstring .= " AND (ims_document_menu.doc_menu_title ILIKE '%{$searchValue}%' OR ims_document_menu.doc_menu_link ILIKE '%{$searchValue}%')";
            $data_result = self::sqlQuery($sqlstring, $php_fetch_ims_express_api);
        }
        $result_total_record_filtered = array_sum(array_map("count", $data_result));
        //* ======== Ordering ========
        $sqlstring .= " ORDER BY {$col[$_POST['order'][0]['column']]} {$_POST['order'][0]['dir']} LIMIT {$_POST['length']} OFFSET {$_POST['start']}";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_ims_express_api);
        //* ======== Prepare Array ========
        foreach ($data_result['data'] as $row) {
            $itemData_List[] = array(
                $row['doc_menu_title'],
                $row['doc_menu_link'],
                $row['doc_menu_parent'],
                $row['docmenuid']
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

    public function saveMenuModule($php_fetch_ims_express_api, $php_insert_ims_express_api, $inTable, $inField1, $inField2, $parent_menu_id, $menu_title)
    {
        $itemData_List = '';
        $doc_parent_id = $parent_menu_id == '' ? '0' : $parent_menu_id;
        if ($doc_parent_id == '0') {
            $chkExist = "SELECT * FROM {$inTable} WHERE {$inField1} = '{$menu_title}'";
        } else {
            $chkExist = "SELECT * FROM {$inTable} WHERE {$inField1} = '{$menu_title}' AND {$inField2} = '{$doc_parent_id}'";
        }
        $data_result = self::sqlQuery($chkExist, $php_fetch_ims_express_api);
        $rowCount = array_sum(array_map("count", $data_result));
        if ($rowCount > 0) {
            if ($doc_parent_id == '0') {
                $itemData_List = 'doc_exist';
            } else {
                $itemData_List = 'doc_sub_exist';
            }
        } else {
            $sqlstring = "INSERT INTO {$inTable}({$inField1},{$inField2},doc_menu_type) VALUES('{$menu_title}','{$doc_parent_id}','folder')";
            $data_result = self::sqlQuery($sqlstring, $php_insert_ims_express_api);
        }
        return json_encode($itemData_List);
    }

    public function loadMenuModule($php_fetch_ims_express_api, $inTable, $inFieldId, $inField1, $inField2, $dataId)
    {
        $itemData_List = array();
        $sqlstring = "SELECT * FROM {$inTable} WHERE {$inFieldId} = '{$dataId}'";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_ims_express_api);
        $row_count = array_sum(array_map("count", $data_result));
        //* ======== Prepare Array ========
        if ($row_count > 0) {
            foreach ($data_result['data'] as $row) {
                $itemData_List['parentMenu'] = $row[$inField1] == '0' ? '' : $row[$inField1];
                $itemData_List['menuTitle'] = $row[$inField2];
            }
        }
        $itemData_List ??= null;
        return json_encode($itemData_List);
    }

    public function updateMenuModule($php_fetch_ims_express_api, $php_update_ims_express_api, $inTable, $inFieldId, $inField1, $inField2, $menu_title, $parent_menu_id,  $dataId)
    {
        $itemData_List = '';
        $doc_parent_id = $parent_menu_id == '' ? '0' : $parent_menu_id;

        if ($doc_parent_id == '0') {
            $chkExist = "SELECT * FROM {$inTable} WHERE {$inField1} = '{$menu_title}' AND {$inFieldId} <> '{$dataId}'";
        } else {
            $chkExist = "SELECT * FROM {$inTable} WHERE {$inField1} = '{$menu_title}' AND {$inField2} = '{$doc_parent_id}' AND {$inFieldId} <> '{$dataId}'";
        }
        $data_result = self::sqlQuery($chkExist, $php_fetch_ims_express_api);
        $rowCount = array_sum(array_map("count", $data_result));
        if ($rowCount > 0) {
            if ($doc_parent_id == '0') {
                $itemData_List = 'doc_exist';
            } else {
                $itemData_List = 'doc_sub_exist';
            }
        } else {
            $sqlstring = "UPDATE {$inTable} SET {$inField1} = '{$menu_title}',{$inField2} = '{$doc_parent_id}' WHERE {$inFieldId} = '{$dataId}'";
            $data_result = self::sqlQuery($sqlstring, $php_update_ims_express_api);
        }
        return json_encode($itemData_List);
    }

    public function deleteMenuModule($php_fetch_ims_express_api, $php_update_ims_express_api, $inTable, $inFieldId, $inField, $dataId)
    {
        $chkExist = "SELECT * FROM {$inTable} WHERE {$inField} = {$dataId}";
        $data_result = self::sqlQuery($chkExist, $php_fetch_ims_express_api);
        $rowCount = array_sum(array_map("count", $data_result));
        if ($rowCount > 0) {
            return 'in_use';
        } else {
            $sqlDel = "DELETE FROM {$inTable} WHERE {$inFieldId} = '{$dataId}'";
            $data_result = self::sqlQuery($sqlDel, $php_update_ims_express_api);
            return $data_result['data'];
        }
    }

    public function loadSysModSelectValues($php_fetch_ims_express_api, $inTable, $inFieldId, $inField, $inOrder)
    {
        $itemData_List = array();
        $sqlstring = "SELECT DISTINCT {$inFieldId},{$inField} FROM {$inTable} ORDER BY {$inField} {$inOrder}";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_ims_express_api);
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
}
