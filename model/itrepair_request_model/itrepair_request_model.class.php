<?php
class ItRepairRequest
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
    public function fetchSignature($emp_name, $php_fetch_bannerweb_api)
    {
        $empSignature = "SELECT encode(employee_signature, 'escape') as employee_signature FROM bpi_employee_signature WHERE emp_name = '{$emp_name}';";
        $data_result = self::sqlQuery($empSignature, $php_fetch_bannerweb_api);
        foreach ($data_result['data'] as $row) {
            $empSignature_row = $row['employee_signature'];
        }
        return $empSignature_row;
    }

    public function fetchOngoingRepairRequest($db, $table, $data, $data2)
    {
        $query = "SELECT $data FROM $table WHERE $data2 = 'Ongoing' ORDER BY $data";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $rowData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $rowCount = $stmt->rowCount();
        if ($rowCount) {
            foreach ($rowData as $row) {
                $ongoing[] = $row[$data];
            }
        } else {
            $ongoing[] = null;
        }
        return $ongoing;
    }

    public function generateQueueNumber($connectiom, $table, $initial)
    {
        $currentYear = date('y');
        $sqlstring = "SELECT control_no FROM $table";
        $result_stmt = $connectiom->prepare($sqlstring);
        $result_stmt->execute();
        // $data_result = sqlQuery($sqlstring, $php_fetch_it_repair_api);
        // $rowCount = array_sum(array_map("count", $data_result));

        if ($result_stmt->rowCount() > 0) {
            foreach ($result_stmt->fetchAll() as $row) {
                $control_no = $row['control_no'];
            }
            $queueNumberParts = explode("-", $control_no);
            $queueNumber = sprintf('%04d', $queueNumberParts[1] + 1);
            $queueYear = end($queueNumberParts);
            $generatedQueueNumber = ($queueYear == $currentYear) ? "$initial-$queueNumber-$currentYear" : "$initial-0001-$currentYear";
        } else {
            $generatedQueueNumber = "$initial-0001-$currentYear";
        }

        return $generatedQueueNumber;
    }

    public function loadLiveSearch($ITR, $InfoSec, $searchVal)
    {
        $query = "SELECT queue_number, date_requested
                        FROM tblit_request
                        WHERE
                            (queue_number ILIKE ? OR prepared_by ILIKE ?) AND
                            status <> 'Done'
                    UNION
                    SELECT queue_number, date_requested
                        FROM tblit_repair
                        WHERE
                            (queue_number ILIKE ? OR prepared_by ILIKE ?) AND
                            status <> 'Done'
                    ORDER BY date_requested DESC";
        $stmt = $ITR->prepare($query);
        $stmt->execute(["%$searchVal%", "%$searchVal%", "%$searchVal%", "%$searchVal%"]);
        $rowData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $rowCount = $stmt->rowCount();

        if ($rowCount) {
            foreach ($rowData as $row) {
                $data["queueNumber"][] = $row["queue_number"];
            }
            $data["status"] = "success";
        } else {
            $data = array(
                "status" => "error",
                "message" => "No Results Found"
            );

            $query = "SELECT control_no, date_requested
                    FROM info_sec_web_app_request
                    WHERE (control_no ILIKE ?) AND
                    status <> 'Done'";
            $stmt = $InfoSec->prepare($query);
            $stmt->execute(["%$searchVal%"]);
            $rowData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $rowCount = $stmt->rowCount();

            if ($rowCount) {
                foreach ($rowData as $row) {
                    $data["queueNumber"][] = $row["control_no"];
                }
                $data["status"] = "success";
            } else {
                $data = array(
                    "status" => "error",
                    "message" => "No Results Found"
                );
            }
        }

        return json_encode($data);
        $InfoSec = null;
        $ITR = null;
    }
    public function loadInputSearch($ITR, $InfoSec, $searchVal, $searchType)
    {
        if ($searchType == "ITR") {
            $query = "SELECT *, TO_CHAR(date_requested,  'Month DD, YYYY - HH12:MI:SS PM') AS new_date FROM tblit_repair
                      WHERE queue_number = ?";
            $stmt = $ITR->prepare($query);
            $stmt->execute([$searchVal]);
            $rowData = $stmt->fetch(PDO::FETCH_ASSOC);
            $rowCount = $stmt->rowCount();

            if ($rowCount > 0) {
                $data = array(
                    "type" => $searchType,
                    "dept_code" => $rowData["dept_code"],
                    "item" => $rowData["item"],
                    "area" => $rowData["area"],
                    "location" => $rowData["location"],
                    "prepared_by" => $rowData["prepared_by"],
                    "remarks" => $rowData["remarks"],
                    "date_requested" => $rowData["date_requested"],
                    "status" => $rowData["status"],
                    "queue_number" => $rowData["queue_number"],
                );
            }
        } else if ($searchType == "SHR") {
            $query = "SELECT * FROM tblit_request
                      WHERE queue_number = ?";
            $stmt = $ITR->prepare($query);
            $stmt->execute([$searchVal]);
            $rowData = $stmt->fetch(PDO::FETCH_ASSOC);
            $rowCount = $stmt->rowCount();

            if ($rowCount > 0) {
                $data = $rowData;
                $data['type'] = $searchType;
            }
        } else 
            if ($searchType == 'WRF') {
            $query = "SELECT * FROM info_sec_web_app_request
                      WHERE control_no = ?";
            $stmt = $InfoSec->prepare($query);
            $stmt->execute([$searchVal]);
            $rowData = $stmt->fetch(PDO::FETCH_ASSOC);
            $rowCount = $stmt->rowCount();

            if ($rowCount > 0) {
                $data['status'] = $rowData['status'];
                $data['control_no'] = $rowData['control_no'];
                $data['request_by'] = $rowData['request_by'];
                $data['web_priority'] = $rowData['web_priority'];
                $data['service_type'] = $rowData['service_type'];
                $data['req_description'] = $rowData['req_description'];
                $data['application_name'] = $rowData['application_name'];
                $data['date_requested'] = $rowData['date_requested'];
                $data['date_needed'] = $rowData['date_needed'];
                $data['type'] = $searchType;
            }
        }

        $data ??= null;
        return json_encode($data);
        $ITR = null;
    }
    public function loadUserDetailsFunction($BannerWeb, $logged_user)
    {
        $sqlstring = "SELECT (emp_fn || ' ' || emp_sn) AS fullname,bpi_department.department,bpi_user_accounts.department as dept_code FROM bpi_user_accounts
                            INNER JOIN prl_employee ON prl_employee.empno = bpi_user_accounts.empno
                            INNER JOIN bpi_department ON bpi_department.dept_code = bpi_user_accounts.department 
                            WHERE (emp_fn || ' ' || emp_sn) = ?";
        $result_stmt = $BannerWeb->prepare($sqlstring);
        $result_stmt->execute([$logged_user]);
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            $resultData_List['dept_code'] = $row['dept_code'];
            $resultData_List['department'] = $row['department'];
            $resultData_List['fullname'] = $row['fullname'];
        }
        return json_encode($resultData_List);
    }
    public function loadNowRepairingOngoingRequest($ITR, $InfoSec)
    {
        $data = array(
            "repair" => self::fetchOngoingRepairRequest($ITR, 'tblit_repair', 'queue_number', 'status'),
            "request" => self::fetchOngoingRepairRequest($ITR, 'tblit_request', 'queue_number', 'status'),
            "requestWeb" => self::fetchOngoingRepairRequest($InfoSec, 'info_sec_web_app_request', 'control_no', 'web_status')
        );
        return json_encode($data);
    }
    public function loadQueueList($ITR, $InfoSec, $logged_user)
    {
        $query = "SELECT queue_number, status, prepared_by FROM tblit_repair WHERE prepared_by = ? AND status NOT IN('Done', 'Cancelled') ORDER BY queue_number";
        $stmt = $ITR->prepare($query);
        $stmt->execute([$logged_user]);
        $rowData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $rowCount = $stmt->rowCount();

        if ($rowCount) foreach ($rowData as $row) $data['repair'][$row['queue_number']][$row['prepared_by']] =  $row['status'];
        $data['repair'] ??= null;

        $query = "SELECT queue_number, status, prepared_by FROM tblit_request WHERE prepared_by = ? AND status NOT IN('Done', 'Cancelled') ORDER BY queue_number";
        $stmt = $ITR->prepare($query);
        $stmt->execute([$logged_user]);
        $rowData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $rowCount = $stmt->rowCount();

        if ($rowCount) foreach ($rowData as $row) $data['request'][$row['queue_number']][$row['prepared_by']] =  $row['status'];
        $data['request'] ??= null;

        $query = "SELECT control_no, web_status, prepared_by FROM info_sec_web_app_request WHERE prepared_by = ? AND web_status NOT IN('Done', 'Cancelled') ORDER BY control_no";
        $stmt = $InfoSec->prepare($query);
        $stmt->execute([$logged_user]);
        $rowData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $rowCount = $stmt->rowCount();

        if ($rowCount) foreach ($rowData as $row) $data['web_request'][$row['control_no']][$row['prepared_by']] =  $row['status'];
        $data['web_request'] ??= null;

        $query = "SELECT server_access_revoke_controlno, server_access_status, prepared_by FROM info_sec_server_access_revoke_request WHERE prepared_by = ? AND server_access_status NOT IN('Done', 'Cancelled') ORDER BY server_access_revoke_controlno";
        $stmt = $InfoSec->prepare($query);
        $stmt->execute([$logged_user]);
        $rowData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $rowCount = $stmt->rowCount();

        if ($rowCount) foreach ($rowData as $row) $data['server_request'][$row['server_access_revoke_controlno']][$row['prepared_by']] =  $row['server_access_status'];
        $data['server_request'] ??= null;

        return json_encode($data);
        $InfoSec = null;
        $ITR = null;
    }

    public function loadNewRepair($ITR, $BannerWeb, $deptCode, $requestedBy, $area, $location, $item, $remarks, $ipAddress, $appId, $requested_by_sign, $noted_by, $noted_by_sign)
    {
        $queueNumber = self::generateQueueNumber($ITR, 'tblit_rep_control_no', 'ITR');

        $query = "INSERT INTO tblit_repair(item, area, location, dept_code, prepared_by, remarks, queue_number, ip_address, prepared_by_sign, noted_by, noted_by_sign)VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) RETURNING repair_id";
        $stmt = $ITR->prepare($query);
        $stmt->execute([$item, $area, $location, $deptCode, $requestedBy, $remarks, $queueNumber, $ipAddress, $requested_by_sign, $noted_by, $noted_by_sign]);

        $sqlstring = "UPDATE tblit_rep_control_no SET control_no = '{$queueNumber}'";
        $result_stmt = $ITR->prepare($sqlstring);
        $result_stmt->execute();

        return $queueNumber;
        $ITR = null;
    }
    public function loadNewWebApp($ITR, $BannerWeb,  $InfoSec, $php_update_info_sec_api, $php_insert_info_sec_api, $date_requested, $date_needed, $service_type, $application_name, $req_description, $web_priority, $prepared_by, $approved_by, $noted_by, $prepared_by_signature, $approved_by_signature, $noted_by_signature, $appId)
    {
        $control_no = self::generateQueueNumber($InfoSec, 'info_sec_web_app_control_no', 'WRF');
        $sqlstring = "INSERT INTO info_sec_web_app_request(control_no,date_requested,date_needed,service_type,application_name,req_description,web_priority,prepared_by,approved_by,noted_by,prepared_by_sign,approved_by_sign,noted_by_sign,status)
                VALUES('{$control_no}','{$date_requested}','{$date_needed}','{$service_type}','{$application_name}','{$req_description}','{$web_priority}','{$prepared_by}','{$approved_by}','{$noted_by}','{$prepared_by_signature}','{$approved_by_signature}','{$noted_by_signature}','Pending') RETURNING webappid";
        $result_stmt = $InfoSec->prepare($sqlstring);
        $result_stmt->execute();
        // $data_result = sqlQuery($sqlstring, $php_insert_info_sec_api);
        $webapp_id = $InfoSec->lastInsertId();

        $sqlstringNotif = "INSERT INTO bpi_notification_module(table_name, table_database, table_field_id, table_field_id_name, prepared_by, prepared_by_date, approved_by, noted_by, app_id, field1, field2, field3 ,field4 , field5, field6, remarks) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        // $sqlstringNotif = "INSERT INTO bpi_notification_module(prepared_by, table_name, table_database, table_field_id, table_field_id_name, approved_by, noted_by, app_id, queue_no_itr, date_needed_itreq, date_request, description_itreq, service_type_web_itr, application_name_web_itr, web_priority_web_itr) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmtNotif = $BannerWeb->prepare($sqlstringNotif);
        $stmtNotif->execute(['info_sec_web_app_request', 'info_security', $webapp_id, 'webappid', $prepared_by, $date_requested, $approved_by, $noted_by, $appId, 'control_no', 'date_requested', 'req_description', 'service_type', 'application_name', 'web_priority', $req_description]);
        // $stmtNotif->execute([$prepared_by, 'info_sec_web_app_request', 'info_security', $webapp_id, 'webappid', $approved_by, $noted_by, $appId, $control_no, $date_needed, $date_requested, $req_description, $service_type, $application_name, $web_priority]);

        $sqlcontrol_no = "UPDATE info_sec_web_app_control_no SET control_no = '{$control_no}'";
        $result_stmt = $InfoSec->prepare($sqlcontrol_no);
        $result_stmt->execute();
        // sqlQuery($sqlcontrol_no, $php_update_info_sec_api);
        return $control_no;
    }
    public function loadNewRequest($ITR, $BannerWeb, $date, $requestType, $softwareType, $dateNeeded, $item, $description, $purpose, $requestedBy, $approvedBy, $notedBy, $appId, $prepared_by_signature, $approved_by_signature, $noted_by_signature)
    {
        $queueNumber = self::generateQueueNumber($ITR, 'tblit_req_control_no', 'SHR');
        $query = "INSERT INTO tblit_request(request_type, software_type, date_needed, item, description, purpose, prepared_by, approved_by, noted_by, queue_number,prepared_by_sign,approved_by_sign,noted_by_sign) 
                        VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) RETURNING request_id";
        $stmt = $ITR->prepare($query);
        $stmt->execute([$requestType, $softwareType, $dateNeeded, $item, $description, $purpose, $requestedBy, $approvedBy, $notedBy, $queueNumber, $prepared_by_signature, $approved_by_signature, $noted_by_signature]);

        $request_id = $ITR->lastInsertId();

        $sqlstringNotif = "INSERT INTO bpi_notification_module(table_name, table_database, table_field_id, table_field_id_name,prepared_by, prepared_by_date, approved_by, noted_by, app_id, field1, field2, field3, field4, field5, field6,field7, remarks) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        // $sqlstringNotif = "INSERT INTO bpi_notification_module(notif_to, table_name, table_database, table_field_id, table_field_id_name, date_request, approved_by, noted_by, request_type_itreq, software_type_itreq, date_needed_itreq, item_itreq, description_itreq, purpose_itreq, queue_no_itr, app_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmtNotif = $BannerWeb->prepare($sqlstringNotif);
        $stmtNotif->execute(['tblit_request', 'it_repair_request', $request_id, 'request_id', $requestedBy, $date, $approvedBy, $notedBy, $appId, 'queue_number', 'date_needed', 'description', 'software_type', 'purpose', 'item', 'request_type', $purpose]);
        // $stmtNotif->execute([$requestedBy, 'tblit_request', 'it_repair_request', $request_id, 'request_id', $date, $approvedBy, $notedBy, $requestType, $softwareType, $dateNeeded, $item, $description, $purpose, $queueNumber, $appId]);

        $sqlstring = "UPDATE tblit_req_control_no SET control_no = '{$queueNumber}'";
        $result_stmt = $ITR->prepare($sqlstring);
        $result_stmt->execute();

        return $queueNumber;

        $ITR = null;
    }
    public function loadMessageSender($ITR, $InfoSec)
    {
        $query = "SELECT queue_number, prepared_by_date 
                        FROM tblit_request WHERE status <> 'Done' 
                      UNION 
                      SELECT queue_number, prepared_by_date 
                        FROM tblit_repair WHERE status <> 'Done'
                      ORDER BY prepared_by_date DESC";
        $stmt = $ITR->prepare($query);
        $stmt->execute();
        $rowData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rowData as $row) {
            $data[] = $row['queue_number'];
        }

        $query = "SELECT control_no, date_requested 
                        FROM info_sec_web_app_request WHERE web_status <> 'Done'
                      ORDER BY date_requested DESC";
        $stmt = $InfoSec->prepare($query);
        $stmt->execute();
        $rowData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rowData as $row) {
            $data[] = $row['control_no'];
        }
        $data ??= null;
        return json_encode($data);
        $ITR = null;
    }

    public function loadMessages($ITR, $ipAddress, $sender)
    {
        if ($sender) {
            $query = "SELECT * FROM tblit_conversation WHERE sender = ?";
            $stmt = $ITR->prepare($query);
            $stmt->execute([$sender]);
            $rowData = $stmt->fetch(PDO::FETCH_ASSOC);
            $hasConversation = $stmt->rowCount();
            if ($hasConversation) {
                $conversationId = $rowData['conversation_id'];
                $query = "SELECT * FROM tblit_messages WHERE conversation_id = ? ORDER BY message_id DESC LIMIT 20";
                $stmt = $ITR->prepare($query);
                $stmt->execute([$conversationId]);
                $rowData = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($rowData as $row) {
                    $row['username'] == $sender ? $data[] = array("sender" => $row) : $data[] = array("receiver" => $row);
                }
            }
            $data ??= "";
            return json_encode($data);
        }
    }
    public function loadMessageSend($ITR, $ipAddress, $message, $sender)
    {
        while (true) {
            $query = "SELECT * FROM tblit_conversation WHERE sender = ?";
            $stmt = $ITR->prepare($query);
            $stmt->execute([$sender]);
            $rowData = $stmt->fetch(PDO::FETCH_ASSOC);
            $hasConversation = $stmt->rowCount();

            if ($hasConversation) { // * Send a message if there has already been a conversation.
                $conversationId = $rowData['conversation_id'];
                $query = "INSERT INTO tblit_messages(conversation_id, username, message) VALUES(?, ?, ?)";
                $stmt = $ITR->prepare($query)->execute([$conversationId, $sender, $message]);
                break; // break out of the loop once the message has been sent
            } else { // * If it doesn't have a conversation, start one.
                $query = "INSERT INTO tblit_conversation(sender, receiver, ip_address) VALUES(?, ?, ?)";
                $stmt = $ITR->prepare($query)->execute([$sender, 'ITD', $ipAddress]);
                // continue the loop to try again
            }
        }
        return "Message sent";
        $ITR = null;
    }
    public function loadUpdateEditRequestWeb($InfoSec, $request_date_needed_edit, $priority, $service_type, $application_name, $request_description_edit, $request_requested_by_edit, $request_approved_by_edit, $request_noted_by_edit, $queue_number)
    {
        $sqlstring = "UPDATE info_sec_web_app_request SET date_needed = ?, web_priority = ?, service_type = ?, 
            application_name = ?, req_description = ?, prepared_by = ?, approved_by = ?, noted_by = ? 
            WHERE control_no = ?";
        $stmt = $InfoSec->prepare($sqlstring);
        $stmt->execute([$request_date_needed_edit, $priority, $service_type, $application_name, $request_description_edit, $request_requested_by_edit, $request_approved_by_edit, $request_noted_by_edit, $queue_number]);
    }
    public function loadUpdateEditRequest($ITR, $request_request_type_edit, $request_software_type_edit, $request_date_needed_edit, $request_description_edit, $request_purpose_edit, $request_requested_by_edit, $request_approved_by_edit, $request_noted_by_edit, $queue_number)
    {
        $sqlstring = "UPDATE tblit_request SET request_type = '{$request_request_type_edit}', software_type = '{$request_software_type_edit}',date_needed = '{$request_date_needed_edit}', description = '{$request_description_edit}', purpose = '{$request_purpose_edit}', prepared_by = '{$request_requested_by_edit}', approved_by = '{$request_approved_by_edit}', noted_by = '{$request_noted_by_edit}' WHERE queue_number = '{$queue_number}'";
        $result_stmt = $ITR->prepare($sqlstring);
        $result_stmt->execute();
        // $data_result = sqlQuery($sqlstring, $php_update_it_repair_api);
    }
    public function loadEditRequestWeb($InfoSec, $referenceNumber)
    {
        $itemData_List = array();
        $sqlstring = "SELECT * FROM info_sec_web_app_request WHERE control_no = ?";
        $stmt = $InfoSec->prepare($sqlstring);
        $stmt->execute([$referenceNumber]);
        $rowCount = $stmt->rowCount();
        if ($rowCount > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $itemData_List['date_needed'] = $row['date_needed'];
                $itemData_List['web_priority'] = $row['web_priority'];
                $itemData_List['service_type'] = $row['service_type'];
                $itemData_List['application_name'] = $row['application_name'];
                $itemData_List['req_description'] = $row['req_description'];
                $itemData_List['prepared_by'] = $row['prepared_by'];
                $itemData_List['approved_by'] = $row['approved_by'];
                $itemData_List['noted_by'] = $row['noted_by'];
            }
        }
        $itemData_List ??= null;
        return json_encode($itemData_List);
        $InfoSec = null;
    }
    public function loadEditRequest($ITR, $referenceNumber)
    {
        $itemData_List = array();
        $query = "SELECT * FROM tblit_request WHERE queue_number = ?";
        $stmt = $ITR->prepare($query);
        $stmt->execute([$referenceNumber]);
        $rowCount = $stmt->rowCount();
        if ($rowCount > 0) {
            while ($rowData = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $itemData_List['request_type'] = $rowData['request_type'];
                $itemData_List['software_type'] = $rowData['software_type'];
                $itemData_List['date_needed'] = $rowData['date_needed'];
                $itemData_List['description'] = $rowData['description'];
                $itemData_List['purpose'] = $rowData['purpose'];
                $itemData_List['prepared_by'] = $rowData['prepared_by'];
                $itemData_List['approved_by'] = $rowData['approved_by'];
                $itemData_List['noted_by'] = $rowData['noted_by'];
            }
        }
        return json_encode($itemData_List);
        $ITR = null;
    }
    public function loadGetPosCode($BannerWeb, $user_department)
    {
        $resultData_List = array();
        $sqlstring = "SELECT pos_code FROM prl_employee WHERE dept_code = ? AND job_level in ('Level-08', 'Level-07')";
        $result_stmt = $BannerWeb->prepare($sqlstring);
        $result_stmt->execute([$user_department]);
        foreach ($result_stmt as $row) {
            $resultData_List['pos_code'] = $row['pos_code'];
        }
        return json_encode($resultData_List);
    }
    public function loadUpdateOnHoldRepair($ITR, $queueNumber, $deptCode, $requestedBy, $area, $location, $item, $remarks, $ipAddress)
    {
        $query = "UPDATE tblit_repair SET dept_code = ?, prepared_by = ?, area = ?, location = ?, item = ?, remarks = ?, ip_address = ? WHERE queue_number = ?";
        $stmt = $ITR->prepare($query)->execute([$deptCode, $requestedBy, $area, $location, $item, $remarks, $ipAddress, $queueNumber]);
        return true;
        $ITR = null;
    }
    public function new_server_request($InfoSec, $server_ip, $user_name, $revoke, $ip_address, $mac_address, $location_server, $request_purpose, $requestedBy, $approvedBy, $notedBy, $requested_by_sign, $approved_by_sign, $noted_by_sign)
    {
        $queueNumber = self::generateQueueNumber($InfoSec, 'info_sec_server_access_revoke_controlno', 'SAF');
        $sqlstring = "INSERT INTO info_sec_server_access_revoke_request(server_access_revoke_controlno,server_ip_address,server_user_ip_address,server_user_mac_address,server_user_name,server_user_location,server_user_purpose,server_revoke_access,prepared_by,approved_by,noted_by,prepared_by_sign,approved_by_sign,noted_by_sign)
                VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $result_stmt = $InfoSec->prepare($sqlstring);
        $result_stmt->execute([$queueNumber, $server_ip, $ip_address, $mac_address, $user_name, $location_server, $request_purpose, $revoke, $requestedBy, $approvedBy, $notedBy, $requested_by_sign, $approved_by_sign, $noted_by_sign]);

        $sqlstring = "UPDATE info_sec_server_access_revoke_controlno SET control_no = '{$queueNumber}'";
        $result_stmt = $InfoSec->prepare($sqlstring);
        $result_stmt->execute();

        return $queueNumber;
    }
    public function loadEditRequestServer($InfoSec, $referenceNumber)
    {
        $itemData_List = array();
        $sqlstring = "SELECT * FROM info_sec_server_access_revoke_request WHERE server_access_revoke_controlno = ?";
        $stmt = $InfoSec->prepare($sqlstring);
        $stmt->execute([$referenceNumber]);
        $rowCount = $stmt->rowCount();
        if ($rowCount > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $itemData_List['server_ip_address'] = $row['server_ip_address'];
                $itemData_List['server_user_ip_address'] = $row['server_user_ip_address'];
                $itemData_List['server_user_mac_address'] = $row['server_user_mac_address'];
                $itemData_List['server_user_name'] = $row['server_user_name'];
                $itemData_List['server_user_location'] = $row['server_user_location'];
                $itemData_List['server_user_purpose'] = $row['server_user_purpose'];
                $itemData_List['server_revoke_access'] = $row['server_revoke_access'];
                $itemData_List['prepared_by'] = $row['prepared_by'];
                $itemData_List['approved_by'] = $row['approved_by'];
                $itemData_List['noted_by'] = $row['noted_by'];
            }
        }
        $itemData_List ??= null;
        return json_encode($itemData_List);
        $InfoSec = null;
    }
    public function loadUpdateEditRequestServer($InfoSec, $server_edit, $user_edit, $access_revoke_edit, $server_ip_edit, $mac_address_edit, $location_server_edit, $request_purpose_edit, $request_requested_by_edit, $request_approved_by_edit, $request_noted_by_edit, $queue_number)
    {
        $sqlstring = "UPDATE info_sec_server_access_revoke_request SET server_ip_address = ?, server_user_ip_address = ?, server_user_mac_address = ?, 
            server_user_name = ?, server_user_location = ?, server_user_purpose = ?, server_revoke_access = ?, prepared_by = ?, approved_by = ?, noted_by = ? 
            WHERE server_access_revoke_controlno = ?";
        $stmt = $InfoSec->prepare($sqlstring);
        $stmt->execute([$server_edit, $server_ip_edit, $mac_address_edit, $user_edit, $location_server_edit, $request_purpose_edit, $access_revoke_edit, $request_requested_by_edit, $request_approved_by_edit, $request_noted_by_edit, $queue_number]);
    }
    public function saveUserAccess($php_fetch_itasset_api, $php_fetch_bannerweb_api, $php_insert_bannerweb_api, $php_update_itasset_api, $php_insert_itasset_api, $itassetdbnew, $control_no, $date, $date_needed, $access, $priority, $domainAccount, $mail_account, $file_storage_access, $in_house_access, $purpose, $preparedBy, $approvedBy, $notedBy)
    {
        $prepared_by_signature = self::fetchSignature($preparedBy, $php_fetch_bannerweb_api);
        $approved_by_signature = self::fetchSignature($approvedBy, $php_fetch_bannerweb_api);
        $noted_by_signature = self::fetchSignature($notedBy, $php_fetch_bannerweb_api);
        $sqlstringValidate = "SELECT control_no FROM tblit_user_access_request;";
        $data_result = self::sqlQuery($sqlstringValidate, $php_fetch_itasset_api);
        foreach ($data_result['data'] as $row) {
            $control_no_validate = $row['control_no'];
        }
        if ($control_no_validate == $control_no) {
            echo 'Exist';
        } else {
            $updateRefno = "UPDATE tblit_control_no SET user_access_control_no = '{$control_no}';";
            self::sqlQuery($updateRefno, $php_update_itasset_api);
            $sqlstring = "INSERT INTO tblit_user_access_request(control_no,date_need,access,priority,domain_account,mail_account,file_storage_access,in_house_access,purpose,prepared_by,prepared_by_sign,approved_by,approved_by_sign,noted_by,noted_by_sign)
            VALUES('{$control_no}','{$date_needed}','{$access}','{$priority}','{$domainAccount}','{$mail_account}','{$file_storage_access}','{$in_house_access}','{$purpose}','{$preparedBy}','{$prepared_by_signature}','{$approvedBy}','{$approved_by_signature}','{$notedBy}','{$noted_by_signature}') RETURNING useraccessid;";
            $stmt = $itassetdbnew->prepare($sqlstring);
            $stmt->execute();

            $useraccessid = $itassetdbnew->lastInsertId();
            
            $sqlstringNotif = "INSERT INTO bpi_notification_module(table_name, table_database, table_field_id, table_field_id_name,prepared_by, prepared_by_date, approved_by, noted_by, app_id, field1, field2, field3, field4, field5, field6,field7, remarks) VALUES ('tblit_user_access_request', 'itassetdb_new', '{$useraccessid}', 'useraccessid', '{$preparedBy}', '{$date}', '{$approvedBy}', '{$notedBy}', '8', 'control_no', 'date_request', 'date_need', 'priority', 'purpose', 'mail_account', 'in_house_access', '{$purpose}')";
            self::sqlQuery($sqlstringNotif, $php_insert_bannerweb_api);
        }
    }
}
