<?php
date_default_timezone_set('Asia/Manila');
class PersoAnnouncement
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

    public function loadAnnouncementListTable($php_fetch_perso_api, $searchValue, $announce_by_empno)
    {
        $itemData_List = array();
        //* ======== Create Array for column same with column names on database for ordering ========
        $col = array(
            0 => 'announce_date_entry',
            1 => 'announce_by',
            2 => 'announce_header',
            3 => 'announce_body',
            4 => 'announce_recipients',
        );
        //* ======== Fetch Data ========
        $sqlstring = "SELECT announce_date_entry,announce_by,announce_header,announce_body,announce_recipients,announcementid FROM bpi_perso_announcement_header WHERE announce_by_empno = '{$announce_by_empno}'";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_perso_api);
        $result_total_record = array_sum(array_map("count", $data_result));
        //* ======== Fetch Total Filtered Record ========
        if (!empty($searchValue)) {
            $sqlstring .= " AND (TO_CHAR(announce_date_entry, 'YYYY-MM-DD') ILIKE '%{$searchValue}%' OR announce_by ILIKE '%{$searchValue}%' OR announce_header ILIKE '%{$searchValue}%' OR announce_body ILIKE '%{$searchValue}%'
            OR announce_recipients ILIKE '%{$searchValue}%')";
            $data_result = self::sqlQuery($sqlstring, $php_fetch_perso_api);
        }
        $result_total_record_filtered = array_sum(array_map("count", $data_result));
        //* ======== Ordering ========
        $sqlstring .= " ORDER BY {$col[$_POST['order'][0]['column']]} {$_POST['order'][0]['dir']} LIMIT {$_POST['length']} OFFSET {$_POST['start']};";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_perso_api);
        //* ======== Prepare Array ========
        foreach ($data_result['data'] as $row) {
            $itemData_List[] = array(
                date_format(date_create($row['announce_date_entry']), 'Y-m-d H:i:s A'),
                $row['announce_by'],
                $row['announce_header'],
                $row['announce_body'],
                $row['announce_recipients'],
                $row['announcementid']
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

    public function loadAnnouncementHistoryListTable($php_fetch_perso_api, $php_fetch_bannerweb_api, $searchValue)
    {
        $itemData_List = array();
        //* ======== Create Array for column same with column names on database for ordering ========
        $col = array(
            0 => 'announce_date_entry',
            1 => 'announce_by',
            2 => 'announce_header',
            3 => 'announce_body',
            4 => 'recipients_acknowledged_date',
        );
        //* ======== Fetch Data ========
        $sqlstring = "SELECT announce_date_entry,announce_by,announce_header,announce_body,bpi_perso_announcement_reciever.announce_recipients,recipients_acknowledged_date 
            FROM bpi_perso_announcement_header
            INNER JOIN bpi_perso_announcement_reciever ON bpi_perso_announcement_reciever.announcement_id = bpi_perso_announcement_header.announcementid WHERE 1 = 1";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_perso_api);
        $result_total_record = array_sum(array_map("count", $data_result));
        //* ======== Fetch Total Filtered Record ========
        if (!empty($searchValue)) {
            $sqlstring .= " AND (TO_CHAR(announce_date_entry, 'YYYY-MM-DD') ILIKE '%{$searchValue}%' OR announce_by ILIKE '%{$searchValue}%' OR announce_header ILIKE '%{$searchValue}%' OR announce_body ILIKE '%{$searchValue}%'
            OR TO_CHAR(recipients_acknowledged_date, 'YYYY-MM-DD') ILIKE '%{$searchValue}%')";
            $data_result = self::sqlQuery($sqlstring, $php_fetch_perso_api);
        }
        $result_total_record_filtered = array_sum(array_map("count", $data_result));
        //* ======== Ordering ========
        $sqlstring .= " ORDER BY {$col[$_POST['order'][0]['column']]} {$_POST['order'][0]['dir']} LIMIT {$_POST['length']} OFFSET {$_POST['start']};";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_perso_api);
        //* ======== Prepare Array ========
        foreach ($data_result['data'] as $row) {
            //* ======== Fetch Employee Fullname ========
            $sqlFetch = "SELECT (emp_fn||' '||emp_sn) AS fullname FROM prl_employee WHERE empno = '{$row['announce_recipients']}';";
            $data_result_fetch = self::sqlQuery($sqlFetch, $php_fetch_bannerweb_api);
            foreach ($data_result_fetch['data'] as $row_fetch) {
                $emp_fulname = $row_fetch['fullname'];
            } //* ======== Fetch Employee Fullname End ========
            $itemData_List[] = array(
                date_format(date_create($row['announce_date_entry']), 'Y-m-d H:i:s A'),
                $row['announce_by'],
                $row['announce_header'],
                $row['announce_body'],
                $emp_fulname,
                date_format(date_create($row['recipients_acknowledged_date']), 'Y-m-d H:i:s A')
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

    public function loadSectionList($php_fetch_perso_api, $php_fetch_bannerweb_api)
    {
        $itemData_List = array();
        $sqlstring = "SELECT * FROM bpi_perso_section;";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_perso_api);
        foreach ($data_result['data'] as $row) {
            $itemData_List[] = $row['perso_section_name'];
        }
        $fetchEmployee = "SELECT (emp_fn || ' ' || emp_sn) AS fullname FROM bpi_user_accounts
        INNER JOIN prl_employee ON prl_employee.empno = bpi_user_accounts.empno
        WHERE department = 'PRD';";
        $data_result_fetch = self::sqlQuery($fetchEmployee, $php_fetch_bannerweb_api);
        foreach ($data_result_fetch['data'] as $row_fetch) {
            $itemData_List[] = $row_fetch['fullname'];
        }
        return json_encode($itemData_List);
    }

    public function saveAnnouncementHeader($php_fetch_bannerweb_api, $php_insert_perso_api, $php_fetch_perso_api,  $announce_recipients, $announce_header, $announce_body, $announce_by, $announce_by_empno, $announce_by_job_title)
    {
        $current_datetime = date('Y-m-d H:i:s');
        //* ====== Fetch Job Title ======
        $sqlFetchJobTitle = "SELECT pos_name FROM prl_position WHERE pos_code = '{$announce_by_job_title}';";
        $data_result_fetch_job = self::sqlQuery($sqlFetchJobTitle, $php_fetch_bannerweb_api);
        foreach ($data_result_fetch_job['data'] as $row_fetch_job) {
            $job_title = $row_fetch_job['pos_name'];
        }
        //* ====== Save Header ======
        $sqlstring = "INSERT INTO bpi_perso_announcement_header(announce_date_entry,announce_header,announce_body,announce_by_empno,announce_by,announce_by_job_title,announce_recipients) VALUES('{$current_datetime}','{$announce_header}','{$announce_body}','{$announce_by_empno}','{$announce_by}','{$job_title}','{$announce_recipients}');";
        self::sqlQuery($sqlstring, $php_insert_perso_api);
        //* ====== Fetch Last Inserted ID ======
        $sqlFetchId = "SELECT announcementid FROM bpi_perso_announcement_header WHERE announce_by_empno = '{$announce_by_empno}' AND announce_date_entry = '{$current_datetime}';";
        $data_result = self::sqlQuery($sqlFetchId, $php_fetch_perso_api);
        foreach ($data_result['data'] as $row) {
            return json_encode($row['announcementid']);
        }
    }

    public function saveAnnouncementDetail($php_fetch_perso_api, $php_fetch_bannerweb_api, $php_insert_perso_api, $announcementid, $strRecipients)
    {
        //* ====== Fetch Recipients per Section ======
        $sqlRecipients = "SELECT perso_assigned_name FROM bpi_perso_section_assigned
            INNER JOIN bpi_perso_section ON bpi_perso_section.perso_sectionid = bpi_perso_section_assigned.perso_section_id
            WHERE perso_section_name = '{$strRecipients}';";
        $data_result_recipients = self::sqlQuery($sqlRecipients, $php_fetch_perso_api);
        $row_count = array_sum(array_map("count", $data_result_recipients));
        if ($row_count == 0) {
            $sqlFetchEmpno = "SELECT empno FROM prl_employee WHERE (emp_fn ||' '|| emp_sn) = '{$strRecipients}';";
            $data_result_empno = self::sqlQuery($sqlFetchEmpno, $php_fetch_bannerweb_api);
            foreach ($data_result_empno['data'] as $row_emp) {
                $empno = $row_emp['empno'];
                //* ====== Assign Announcement ======
                $sqlstring = "INSERT INTO bpi_perso_announcement_reciever(announcement_id,announce_recipients) VALUES('{$announcementid}','{$empno}');";
                self::sqlQuery($sqlstring, $php_insert_perso_api);
            }
        } else {
            foreach ($data_result_recipients['data'] as $row_recipients) {
                $access_lvl = $row_recipients['perso_assigned_name'];
                //* ====== Fetch Recipients with Perso Monitoring Account ======
                $sqlFetch = "SELECT empno FROM bpi_user_accounts WHERE access_lvl = '{$access_lvl}';";
                $data_result_fetch = self::sqlQuery($sqlFetch, $php_fetch_bannerweb_api);
                foreach ($data_result_fetch['data'] as $row_account) {
                    $empno = $row_account['empno'];
                    //* ====== Assign Announcement ======
                    $sqlstring = "INSERT INTO bpi_perso_announcement_reciever(announcement_id,announce_recipients) VALUES('{$announcementid}','{$empno}');";
                    self::sqlQuery($sqlstring, $php_insert_perso_api);
                }
            }
        }
    }

    public function loadAnnouncementList($php_fetch_perso_api, $php_fetch_bannerweb_api, $announce_to)
    {
        $current_date = date('Y-m-d');
        $itemData_List = array();
        $sqlstring = "SELECT announce_recieverid,announce_date_entry,announce_header,announce_body,announce_by,announce_by_empno,recipients_acknowledged FROM bpi_perso_announcement_reciever
            INNER JOIN bpi_perso_announcement_header ON bpi_perso_announcement_header.announcementid = bpi_perso_announcement_reciever.announcement_id
            WHERE bpi_perso_announcement_reciever.announce_recipients = '{$announce_to}' AND TO_CHAR(announce_date_entry,'YYYY-MM-DD') = '{$current_date}';";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_perso_api);
        $row_count = array_sum(array_map("count", $data_result));
        if ($row_count == 0) {
            return json_encode('no record');
        } else {
            foreach ($data_result['data'] as $row) {
                $sqlFetchPic = "SELECT encode(user_image,'escape') AS announce_by_pic FROM bpi_user_accounts WHERE empno = '{$row['announce_by_empno']}';";
                $data_result_pic = self::sqlQuery($sqlFetchPic, $php_fetch_bannerweb_api);
                foreach ($data_result_pic['data'] as $row_pic) {
                    $announce_by_pic = $row_pic['announce_by_pic'];
                }
                $itemData_List[] = array(
                    'announce_recieverid' => $row['announce_recieverid'],
                    'announce_by_pic' => $announce_by_pic,
                    'announce_date_entry' => date_format(date_create($row['announce_date_entry']), 'Y-m-d H:i:s A'),
                    'announce_header' => $row['announce_header'],
                    'announce_body' => $row['announce_body'],
                    'announce_by' => $row['announce_by'],
                    'announce_check' => $row['recipients_acknowledged'] == 'true' ? 'icon_check' : 'd-none',
                    'announce_btn' => $row['recipients_acknowledged'] == 'true' ? 'd-none' : ''
                );
            }
            return json_encode($itemData_List);
        }
    }

    public function updateAnnouncement($php_update_perso_api, $announce_recieverid)
    {
        $acknowledge_date_time = date('Y-m-d H:i:s');
        $sqlstring = "UPDATE bpi_perso_announcement_reciever SET recipients_acknowledged = true, recipients_acknowledged_date = '{$acknowledge_date_time}' WHERE announce_recieverid = '{$announce_recieverid}';";
        self::sqlQuery($sqlstring, $php_update_perso_api);
    }

    public function loadAnnouncementInfo($php_fetch_perso_api, $announcementid)
    {
        $itemData_List = array();
        $sqlstring = "SELECT announce_header,announce_body FROM bpi_perso_announcement_header WHERE announcementid = '{$announcementid}';";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_perso_api);
        foreach ($data_result['data'] as $row) {
            $itemData_List['announce_header'] = $row['announce_header'];
            $itemData_List['announce_body'] = $row['announce_body'];
        }
        return json_encode($itemData_List);
    }

    public function updateAnnouncementInfo($php_update_perso_api, $announce_header, $announce_details, $announcementid)
    {
        $sqlstring = "UPDATE bpi_perso_announcement_header SET announce_header = '{$announce_header}', announce_body = '{$announce_details}' WHERE announcementid = '{$announcementid}';";
        self::sqlQuery($sqlstring, $php_update_perso_api);
    }

    public function deleteAnnouncement($php_update_perso_api, $announcementid)
    {
        $sqlstring = "DELETE FROM bpi_perso_announcement_header WHERE announcementid = '{$announcementid}';";
        self::sqlQuery($sqlstring, $php_update_perso_api);
    }
}
