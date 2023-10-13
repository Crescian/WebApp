<?php
class notificationModule
{
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

    public function load_nav_link($BannerWeb, $fullname, $archive)
    {
        $categoryArray = array();
        if ($archive == 'not_archive') {
            $sqlstring = "SELECT DISTINCT app_name,app_id FROM bpi_notification_module 
            INNER JOIN bpi_app_menu ON bpi_app_menu.appid = bpi_notification_module.app_id WHERE 
            prepared_by = ? AND prepared_by_acknowledge = false 
            OR checked_by = ? AND checked_by_acknowledge = false 
            OR approved_by = ? AND approved_by_acknowledge = false 
            OR noted_by = ? AND (approved_by_acknowledge = true OR checked_by_acknowledge = true) AND noted_by_acknowledge = false 
            AND prepared_by_acknowledge = false
            ORDER BY app_name DESC";
        } else {
            $sqlstring = "SELECT DISTINCT app_name,app_id FROM bpi_notification_module 
            INNER JOIN bpi_app_menu ON bpi_app_menu.appid = bpi_notification_module.app_id WHERE 
            prepared_by = ?
            OR checked_by = ?
            OR approved_by = ?
            OR noted_by = ?
            AND prepared_by_acknowledge = true
            ORDER BY app_name DESC";
        }
        $result_stmt = $BannerWeb->prepare($sqlstring);
        $result_stmt->execute([$fullname, $fullname, $fullname, $fullname]);
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            $categoryArray[] =
                [
                    'app_name' => $row['app_name'],
                    'app_id' => $row['app_id']
                ];
        }
        return json_encode($categoryArray);
        $BannerWeb = null; //* ======== Close Connection ========
    }
    public function load_assignatory($BannerWeb, $id, $fullname, $archive)
    {
        $categoryArray = array();
        if ($archive == 'not_archive') {
            $sqlstring = "SELECT DISTINCT app_id,table_name,(Case when prepared_by = '{$fullname}' AND prepared_by_acknowledge = false THEN 1 ELSE 0 END) as prepared_by,
            (Case when checked_by = '{$fullname}' AND checked_by_acknowledge = false THEN 1 ELSE 0 END) as checked_by,
            (Case when received_by = '{$fullname}' AND received_by_acknowledge = false THEN 1 ELSE 0 END) as received_by,
            (Case when approved_by = '{$fullname}' AND approved_by_acknowledge = false THEN 1 ELSE 0 END) as approved_by,
            (Case when noted_by = '{$fullname}' AND (approved_by_acknowledge = true OR checked_by_acknowledge = true) AND noted_by_acknowledge = false THEN 1 ELSE 0 END) as noted_by
            FROM bpi_notification_module WHERE app_id = '{$id}'";
        } else {
            $sqlstring = "SELECT DISTINCT app_id,table_name,(Case when prepared_by = '{$fullname}' THEN 1 ELSE 0 END) as prepared_by,
            (Case when checked_by = '{$fullname}' THEN 1 ELSE 0 END) as checked_by,
            (Case when received_by = '{$fullname}' THEN 1 ELSE 0 END) as received_by,
            (Case when approved_by = '{$fullname}' THEN 1 ELSE 0 END) as approved_by,
            (Case when noted_by = '{$fullname}' THEN 1 ELSE 0 END) as noted_by
            FROM bpi_notification_module WHERE app_id = '{$id}'";
        }
        $result_stmt = $BannerWeb->prepare($sqlstring);
        $result_stmt->execute();
        $result_res = $result_stmt->fetchAll();
        foreach ($result_res as $row) {
            $categoryArray[] =
                [
                    'app_id' => $row['app_id'],
                    'table_name' => $row['table_name'],
                    'prepared_by' => $row['prepared_by'],
                    'checked_by' => $row['checked_by'],
                    'received_by' => $row['received_by'],
                    'approved_by' => $row['approved_by'],
                    'noted_by' => $row['noted_by'],
                ];
        }
        return json_encode($categoryArray);
        $BannerWeb = null; //* ======== Close Connection ========
    }

    public function loadCount($BannerWeb, $app_id, $fullname)
    {
        $queryCancel = "SELECT COUNT(*) AS prep_cancel_count
        FROM bpi_notification_module
        WHERE cancel_status = true AND app_id = '{$app_id}' AND prepared_by = '{$fullname}';";
        $stmt_cancel = $BannerWeb->prepare($queryCancel);
        $stmt_cancel->execute();
        $rowDataCancel = $stmt_cancel->fetchAll(PDO::FETCH_ASSOC);
        $rowCountCancel = $stmt_cancel->rowCount();
        if ($rowCountCancel) foreach ($rowDataCancel as $row) $data['cancelP_count'] = $row['prep_cancel_count'];
        $data['cancelP_count'] ??= null;
        $query = "SELECT COUNT(*) AS prepar_count
        FROM bpi_notification_module
        WHERE ((table_database = 'it_repair_request' OR table_database = 'itassetdb_new') AND repair_by_acknowledge = true OR table_database = 'physical_security' AND noted_by_acknowledge = true) AND prepared_by_acknowledge = false AND app_id = '{$app_id}' AND prepared_by = '{$fullname}';";
        $stmt = $BannerWeb->prepare($query);
        $stmt->execute();
        $rowData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $rowCount = $stmt->rowCount();
        if ($rowCount) foreach ($rowData as $row) $data['prepared_count'] = $row['prepar_count'];
        // $data['prepared_count'] ??= null;
        $data['prepared_count'] = ($data['prepared_count'] == 0 && $data['cancelP_count'] > 0) ? 0 : ($data['prepared_count'] - $data['cancelP_count']);

        $queryCancel = "SELECT COUNT(*) AS chec_cancel_count
        FROM bpi_notification_module
        WHERE cancel_status = true AND app_id = '{$app_id}' AND checked_by = '{$fullname}';";
        $stmt_cancel = $BannerWeb->prepare($queryCancel);
        $stmt_cancel->execute();
        $rowDataCancel = $stmt_cancel->fetchAll(PDO::FETCH_ASSOC);
        $rowCountCancel = $stmt_cancel->rowCount();
        if ($rowCountCancel) foreach ($rowDataCancel as $row) $data['cancelC_count'] = $row['chec_cancel_count'];
        $data['cancelC_count'] ??= null;
        $query = "SELECT COUNT(*) AS chec_count
        FROM bpi_notification_module
        WHERE checked_by_acknowledge = false AND app_id = '{$app_id}' AND checked_by = '{$fullname}';";
        $stmt = $BannerWeb->prepare($query);
        $stmt->execute();
        $rowData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $rowCount = $stmt->rowCount();
        if ($rowCount) foreach ($rowData as $row) $data['checked_count'] =  $row['chec_count'];
        // $data['checked_count'] ??= null;
        $data['checked_count'] = ($data['checked_count'] == 0 && $data['cancelC_count'] > 0) ? 0 : ($data['checked_count'] - $data['cancelC_count']);

        $queryCancel = "SELECT COUNT(*) AS received_cancel_count
        FROM bpi_notification_module
        WHERE cancel_status = true AND app_id = '{$app_id}' AND received_by = '{$fullname}';";
        $stmt_cancel = $BannerWeb->prepare($queryCancel);
        $stmt_cancel->execute();
        $rowDataCancel = $stmt_cancel->fetchAll(PDO::FETCH_ASSOC);
        $rowCountCancel = $stmt_cancel->rowCount();
        if ($rowCountCancel) foreach ($rowDataCancel as $row) $data['cancelR_count'] = $row['received_cancel_count'];
        $data['cancelR_count'] ??= null;
        $query = "SELECT COUNT(*) AS receiv_count
        FROM bpi_notification_module
        WHERE received_by_acknowledge = false AND app_id = '{$app_id}' AND received_by = '{$fullname}';";
        $stmt = $BannerWeb->prepare($query);
        $stmt->execute();
        $rowData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $rowCount = $stmt->rowCount();
        if ($rowCount) foreach ($rowData as $row) $data['received_count'] =  $row['receiv_count'];
        // $data['received_count'] ??= null;
        $data['received_count'] = ($data['received_count'] == 0 && $data['cancelR_count'] > 0) ? 0 : ($data['received_count'] - $data['cancelR_count']);

        $queryCancel = "SELECT COUNT(*) AS approv_cancel_count
        FROM bpi_notification_module
        WHERE cancel_status = true AND app_id = '{$app_id}' AND approved_by = '{$fullname}';";
        $stmt_cancel = $BannerWeb->prepare($queryCancel);
        $stmt_cancel->execute();
        $rowDataCancel = $stmt_cancel->fetchAll(PDO::FETCH_ASSOC);
        $rowCountCancel = $stmt_cancel->rowCount();
        if ($rowCountCancel) foreach ($rowDataCancel as $row) $data['cancelA_count'] = $row['approv_cancel_count'];
        $data['cancelA_count'] ??= null;
        $query = "SELECT COUNT(*) AS approv_count
        FROM bpi_notification_module
        WHERE approved_by_acknowledge = false AND app_id = '{$app_id}' AND approved_by = '{$fullname}';";
        $stmt = $BannerWeb->prepare($query);
        $stmt->execute();
        $rowData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $rowCount = $stmt->rowCount();
        if ($rowCount) foreach ($rowData as $row) $data['approved_count'] =  $row['approv_count'];
        // $data['approved_count'] ??= null;
        // $data['approved_count'] = ($data['approved_count'] == 0 && $data['cancelA_count'] > 0) ? 0 : ($data['approved_count'] - $data['cancelA_count']);
        $data['approved_count'] = ($data['approved_count'] == 0 && $data['cancelA_count'] > 0) ? 0 : ($data['approved_count'] - $data['cancelA_count']);

        $queryCancel = "SELECT COUNT(*) AS note_cancel_count
        FROM bpi_notification_module
        WHERE cancel_status = true AND app_id = '{$app_id}' AND noted_by = '{$fullname}';";
        $stmt_cancel = $BannerWeb->prepare($queryCancel);
        $stmt_cancel->execute();
        $rowDataCancel = $stmt_cancel->fetchAll(PDO::FETCH_ASSOC);
        $rowCountCancel = $stmt_cancel->rowCount();
        if ($rowCountCancel) foreach ($rowDataCancel as $row) $data['cancelN_count'] = $row['note_cancel_count'];
        $data['cancelN_count'] ??= null;
        $query = "SELECT COUNT(*) AS not_count
            FROM bpi_notification_module
            WHERE (approved_by_acknowledge = true OR checked_by_acknowledge = true) AND noted_by_acknowledge = false 
            AND app_id = '{$app_id}' AND noted_by = '{$fullname}';";
        $stmt = $BannerWeb->prepare($query);
        $stmt->execute();
        $rowData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $rowCount = $stmt->rowCount();
        if ($rowCount) foreach ($rowData as $row) $data['noted_count'] =  $row['not_count'];
        // $data['noted_count'] ??= null;
        $data['noted_count'] < 0 ? 0 : ($data['noted_count'] - $data['cancelN_count']);

        // $data['total'] = $data['prepared_count'] + $data['approved_count'] + $data['noted_count'];
        $data['total'] = $data['prepared_count'] + $data['checked_count'] + $data['received_count'] + $data['approved_count'] + $data['noted_count'];

        return json_encode($data);
        $BannerWeb = null;
    }
    public function loadTable($BannerWeb, $app_id, $fullname, $label, $archive)
    {
        // //* ======== Prepare Array ========
        //* ======== Read Data ========
        $searchValue = $_POST['search']['value'];
        $resultData_List = array();
        //* ======== Create Array for column same with column names on database for ordering ========
        $col = array(
            0 => 'table_name',
            1 => 'remarks',
        );
        if ($archive == 'not_archive') {
            if ($app_id == '8') {
                if ($label == 'approved_by') {
                    $filterMessage = 'AND approved_by_acknowledge = false AND prepared_by_acknowledge = false';
                } else if ($label == 'noted_by') {
                    $filterMessage = 'AND approved_by_acknowledge = true AND noted_by_acknowledge = false AND prepared_by_acknowledge = false';
                } else {
                    $filterMessage = 'AND prepared_by_acknowledge = false';
                }
            } else if ($app_id == '6') {
                if ($label == 'checked_by') {
                    $filterMessage = 'AND checked_by_acknowledge = false AND prepared_by_acknowledge = false';
                } else if ($label == 'noted_by') {
                    $filterMessage = 'AND noted_by_acknowledge = false AND prepared_by_acknowledge = false';
                } else {
                    $filterMessage = 'AND prepared_by_acknowledge = false';
                }
            }
        } else {
            $filterMessage = 'AND prepared_by_acknowledge = true';
        }
        //* ======== Fetch Record ========
        $sqlstring = "SELECT * FROM bpi_notification_module WHERE app_id = '{$app_id}' AND $label = '{$fullname}' {$filterMessage}";
        $result_stmt = $BannerWeb->prepare($sqlstring);
        $result_stmt->execute();
        $result_total_record = $result_stmt->rowCount();
        //* ======== Fetch Total Filtered Record ========
        $sqlstring = "SELECT * FROM bpi_notification_module WHERE 1 = 1 AND app_id = '{$app_id}' AND $label = '{$fullname}' {$filterMessage}";
        //* ======== Search ========
        if (!empty($searchValue)) {
            $sqlstring .= " AND (table_name ILIKE '%" . $searchValue . "%' OR remarks ILIKE '%" . $searchValue . "%')";
        }
        $result_stmt = $BannerWeb->prepare($sqlstring);
        $result_stmt->execute();
        $result_total_record_filtered = $result_stmt->rowCount();
        //* ======== Ordering ========
        $sqlstring .= " ORDER BY prepared_by_date DESC, " . $col[$_POST['order'][0]['column']] . " " . $_POST['order'][0]['dir'] . " LIMIT " . $_POST['length'] . " OFFSET " . $_POST['start'];
        $result_stmt = $BannerWeb->prepare($sqlstring);
        $result_stmt->execute();
        //* ======== Prepare Array ========
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            $nestedData = array();
            $nestedData[] = ucfirst(trim(str_replace('_', ' ', str_replace('tblit', '', $row['table_name']))));
            $nestedData[] = ucfirst($row['remarks']);
            $fieldNames = [
                "notificationid", "table_database", "table_name", "table_field_id", "table_field_id_name", "prepared_by_acknowledge", "checked_by_acknowledge", "approved_by_acknowledge", "noted_by_acknowledge",
                "repair_by_acknowledge", "received_by_acknowledge", "cancel_status", "prepared_by", "checked_by", "approved_by", "noted_by", "repair_by", "received_by", "prepared_by_date"
            ];
            $nestedData[] = array_intersect_key($row, array_flip($fieldNames));
            $resultData_List[] = $nestedData;
        }
        //* ======== Output Data ========
        $output = array(
            'draw'                  =>  intval($_POST['draw']),
            'iTotalRecords'         =>  $result_total_record,
            'iTotalDisplayRecords'  =>  $result_total_record_filtered,
            'data'                  =>  $resultData_List
        );
        //* ======== Send Data as JSON Format ========
        echo json_encode($output);
        $BannerWeb = null; //* ======== Close Connection ========
    }
    public function generateFields($BannerWeb, $connection, $notificationid, $table_name, $table_field_id, $table_field_id_name)
    {
        $data = array();
        $sqlstring = "SELECT * FROM $table_name WHERE $table_field_id_name = ?";
        $result_stmt = $connection->prepare($sqlstring);
        $result_stmt->execute([$table_field_id]);
        $result_res = $result_stmt->fetchAll();
        foreach ($result_res as $row) {
            $sqlstringScanAssig = "SELECT * FROM bpi_notification_module WHERE notificationid = ?";
            $result_stmt_scan = $BannerWeb->prepare($sqlstringScanAssig);
            $result_stmt_scan->execute([$notificationid]);
            $result_res_scan = $result_stmt_scan->fetchAll();
            foreach ($result_res_scan as $row) {
                $acknowledgeType = ['prepared_by', 'checked_by', 'received_by', 'approved_by', 'noted_by', 'repair_by'];
                $fields = ['field1', 'field2', 'field3', 'field4', 'field5', 'field6', 'field7'];
                $data['notificationid'] = $row['notificationid'];
                $data['app_id'] = $row['app_id'];
                $data['cancel_status'] = $row['cancel_status'];
                foreach ($acknowledgeType as $acknowledge) {
                    if ($row[$acknowledge] != null) {
                        $data[$acknowledge . '_acknowledge'] = $row[$acknowledge . '_acknowledge'];
                    }
                }
                foreach ($acknowledgeType as $acknowledgeAssign) {
                    if ($row[$acknowledgeAssign] != null) {
                        $data[$acknowledgeAssign] = $row[$acknowledgeAssign];
                    }
                }
                foreach ($fields as $field) {
                    if ($row[$field] != null) {
                        $data[$field] = $row[$field];
                    }
                }
            }
        }
        return json_encode($data);
        $connection = null; //* ======== Close Connection ========
    }
    public function fillData($connection, $table_name, $table_field_id, $table_field_id_name, $data)
    {
        $itemData = array();
        $sqlstring = "SELECT * FROM $table_name WHERE $table_field_id_name = ?";
        $result_stmt = $connection->prepare($sqlstring);
        $result_stmt->execute([$table_field_id]);
        $result_res = $result_stmt->fetchAll();
        foreach ($result_res as $row) {
            $itemData[$data] = $row[$data];
        }
        return json_encode($itemData);
        $connection = null; //* ======== Close Connection ========
    }
    public function cancelAcknowledge($connection, $BannerWeb, $id, $table_name, $table_id, $table_id_name)
    {
        $sqlstring = "UPDATE $table_name SET status = 'Cancelled' WHERE $table_id_name = ?";
        $result_stmt = $connection->prepare($sqlstring);
        $result_stmt->execute([$table_id]);

        $sqlstring = "UPDATE bpi_notification_module SET cancel_status = true WHERE notificationid = ?";
        $result_stmt = $BannerWeb->prepare($sqlstring);
        $result_stmt->execute([$id]);
    }
    public function acknowledge($connection, $itassetdbnew, $ITR, $BannerWeb, $id, $table_id, $table_id_name, $table_name, $date)
    {
        switch ($table_name) {
            case 'tblit_repair':
                $sqlstringRepair = "UPDATE bpi_notification_module SET prepared_by_acknowledge = true WHERE notificationid = ?";
                $result_stmt_repair = $BannerWeb->prepare($sqlstringRepair);
                $result_stmt_repair->execute([$id]);

                $sqlstringItr = "UPDATE tblit_repair SET status = 'Done', prepared_by_acknowledge = true, prepared_by_date = ? 
                WHERE $table_id_name = ?";
                $result_stmt_itr = $connection->prepare($sqlstringItr);
                $result_stmt_itr->execute([$date, $table_id]);
                break;
            case 'tblit_request':
                $sqlstringScan = "SELECT * FROM bpi_notification_module WHERE notificationid = ?";
                $result_stmt = $BannerWeb->prepare($sqlstringScan);
                $result_stmt->execute([$id]);
                $result_res = $result_stmt->fetchAll();
                foreach ($result_res as $row) {
                    if ($row['approved_by_acknowledge'] == false) {
                        $acknowledgeType = "approved_by_acknowledge";
                        $sqlstring = "UPDATE tblit_request SET approved_by_acknowledge = true, approved_by_date = ? WHERE request_id = ?";
                        $result_stmt = $ITR->prepare($sqlstring);
                        $result_stmt->execute([$date, $table_id]);
                    } else {
                        if ($row['noted_by_acknowledge'] == false) {
                            $acknowledgeType = "noted_by_acknowledge";
                            $sqlstring = "UPDATE $table_name SET noted_by_acknowledge = true, noted_by_date = ? WHERE $table_id_name = ?";
                            $result_stmt = $ITR->prepare($sqlstring);
                            $result_stmt->execute([$date, $table_id]);
                        } else {
                            $acknowledgeType = "prepared_by_acknowledge";
                            $sqlstring = "UPDATE $table_name SET status = 'Done', prepared_by_acknowledge = 'true', prepared_by_acknowledge_date = ? WHERE $table_id_name = ?";
                            $result_stmt = $ITR->prepare($sqlstring);
                            $result_stmt->execute([$date, $table_id]);
                        }
                    }
                    $sqlstring = "UPDATE bpi_notification_module SET $acknowledgeType = true WHERE notificationid = ?";
                    $result_stmt = $BannerWeb->prepare($sqlstring);
                    $result_stmt->execute([$id]);
                }
                break;
            case 'info_sec_web_app_request':
                $sqlstringScan = "SELECT * FROM bpi_notification_module WHERE notificationid = ?";
                $result_stmt = $BannerWeb->prepare($sqlstringScan);
                $result_stmt->execute([$id]);
                $result_res = $result_stmt->fetchAll();
                foreach ($result_res as $row) {
                    if ($row['approved_by_acknowledge'] == false) {
                        $acknowledgeType = "approved_by_acknowledge";
                        $sqlstring = "UPDATE $table_name SET approved_by_acknowledge = true WHERE $table_id_name = ?";
                        $result_stmt = $connection->prepare($sqlstring);
                        $result_stmt->execute([$table_id]);
                    } else {
                        if ($row['noted_by_acknowledge'] == false) {
                            $acknowledgeType = "noted_by_acknowledge";
                            $sqlstring = "UPDATE $table_name SET noted_by_acknowledge = true WHERE $table_id_name = ?";
                        } else {
                            $acknowledgeType = "prepared_by_acknowledge";
                            $sqlstring = "UPDATE $table_name SET web_status = 'Done', prepared_by_acknowledge = true WHERE $table_id_name = ?";
                        }
                        $result_stmt = $connection->prepare($sqlstring);
                        $result_stmt->execute([$table_id]);
                    }
                    $sqlstring = "UPDATE bpi_notification_module SET $acknowledgeType = true WHERE notificationid = ?";
                    $result_stmt = $BannerWeb->prepare($sqlstring);
                    $result_stmt->execute([$id]);
                }
                break;
            case 'phd_time_sync_log_header':
                $sqlstringScan = "SELECT * FROM bpi_notification_module WHERE notificationid = ?";
                $result_stmt = $BannerWeb->prepare($sqlstringScan);
                $result_stmt->execute([$id]);
                $result_res = $result_stmt->fetchAll();
                foreach ($result_res as $row) {
                    if ($row['checked_by_acknowledge'] == false) {
                        $acknowledgeType = "checked_by_acknowledge";
                        $sqlstring = "UPDATE $table_name SET checked_by_acknowledge = true WHERE $table_id_name = ?";
                        $result_stmt = $connection->prepare($sqlstring);
                        $result_stmt->execute([$table_id]);
                    } else {
                        if ($row['noted_by_acknowledge'] == false) {
                            $acknowledgeType = "noted_by_acknowledge";
                            $sqlstring = "UPDATE $table_name SET noted_by_acknowledge = true WHERE $table_id_name = ?";
                        } else {
                            $acknowledgeType = "prepared_by_acknowledge";
                            $sqlstring = "UPDATE $table_name SET prepared_by_acknowledge = true WHERE $table_id_name = ?";
                        }
                        $result_stmt = $connection->prepare($sqlstring);
                        $result_stmt->execute([$table_id]);
                    }
                    $sqlstring = "UPDATE bpi_notification_module SET $acknowledgeType = true WHERE notificationid = ?";
                    $result_stmt = $BannerWeb->prepare($sqlstring);
                    $result_stmt->execute([$id]);
                }
                break;
            case 'phd_event_monitoring_header':
                $sqlstringScan = "SELECT * FROM bpi_notification_module WHERE notificationid = ?";
                $result_stmt = $BannerWeb->prepare($sqlstringScan);
                $result_stmt->execute([$id]);
                $result_res = $result_stmt->fetchAll();
                foreach ($result_res as $row) {
                    if ($row['noted_by_acknowledge'] == false && $row['prepared_by_acknowledge'] == false) {
                        $acknowledgeType = "noted_by_acknowledge";
                        $sqlstring = "UPDATE $table_name SET noted_by_acknowledge = true WHERE $table_id_name = ?";
                    } else {
                        $acknowledgeType = "prepared_by_acknowledge";
                        $sqlstring = "UPDATE $table_name SET prepared_by_acknowledge = true WHERE $table_id_name = ?";
                    }
                    $result_stmt = $connection->prepare($sqlstring);
                    $result_stmt->execute([$table_id]);
                    $sqlstring = "UPDATE bpi_notification_module SET $acknowledgeType = true WHERE notificationid = ?";
                    $result_stmt = $BannerWeb->prepare($sqlstring);
                    $result_stmt->execute([$id]);
                }
                break;
            case 'phd_quarterly_vs_header':
                $sqlstringScan = "SELECT * FROM bpi_notification_module WHERE notificationid = ?";
                $result_stmt = $BannerWeb->prepare($sqlstringScan);
                $result_stmt->execute([$id]);
                $result_res = $result_stmt->fetchAll();
                foreach ($result_res as $row) {
                    if ($row['checked_by_acknowledge'] == false) {
                        $acknowledgeType = "checked_by_acknowledge";
                        $sqlstring = "UPDATE $table_name SET checked_by_acknowledge = true WHERE $table_id_name = ?";
                        $result_stmt = $connection->prepare($sqlstring);
                        $result_stmt->execute([$table_id]);
                    } else {
                        if ($row['noted_by_acknowledge'] == false) {
                            $acknowledgeType = "noted_by_acknowledge";
                            $sqlstring = "UPDATE $table_name SET noted_by_acknowledge = true WHERE $table_id_name = ?";
                        } else {
                            $acknowledgeType = "prepared_by_acknowledge";
                            $sqlstring = "UPDATE $table_name SET prepared_by_acknowledge = true WHERE $table_id_name = ?";
                        }
                        $result_stmt = $connection->prepare($sqlstring);
                        $result_stmt->execute([$table_id]);
                    }
                    $sqlstring = "UPDATE bpi_notification_module SET $acknowledgeType = true WHERE notificationid = ?";
                    $result_stmt = $BannerWeb->prepare($sqlstring);
                    $result_stmt->execute([$id]);
                }
                break;
            case 'tblit_user_access_request':
                $sqlstringScan = "SELECT * FROM bpi_notification_module WHERE notificationid = ?";
                $result_stmt = $BannerWeb->prepare($sqlstringScan);
                $result_stmt->execute([$id]);
                $result_res = $result_stmt->fetchAll();
                foreach ($result_res as $row) {
                    if ($row['approved_by_acknowledge'] == false) {
                        $acknowledgeType = "approved_by_acknowledge";
                        $sqlstring = "UPDATE $table_name SET approved_by_acknowledge = true, approved_by_date = ? WHERE useraccessid = ?";
                        $result_stmt = $itassetdbnew->prepare($sqlstring);
                        $result_stmt->execute([$date, $table_id]);
                    } else {
                        if ($row['noted_by_acknowledge'] == false) {
                            $acknowledgeType = "noted_by_acknowledge";
                            $sqlstring = "UPDATE $table_name SET noted_by_acknowledge = true, noted_by_date = ? WHERE $table_id_name = ?";
                            $result_stmt = $itassetdbnew->prepare($sqlstring);
                            $result_stmt->execute([$date, $table_id]);
                        } else {
                            
                            $acknowledgeType = "prepared_by_acknowledge";
                            $sqlstring = "UPDATE $table_name SET prepared_by_acknowledge = 'true', prepared_by_date = ?, status = 'Done' WHERE $table_id_name = ?";
                            $result_stmt = $itassetdbnew->prepare($sqlstring);
                            $result_stmt->execute([$date, $table_id]);
                        }
                    }
                    $sqlstring = "UPDATE bpi_notification_module SET $acknowledgeType = true WHERE notificationid = ?";
                    $result_stmt = $BannerWeb->prepare($sqlstring);
                    $result_stmt->execute([$id]);
                }
                break;
        }
        $BannerWeb = null; //* ======== Close Connection ========
        $connection = null; //* ======== Close Connection ========
    }
}
