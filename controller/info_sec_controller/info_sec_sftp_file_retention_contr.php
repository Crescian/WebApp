<?php
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    include_once '../../model/info_sec_model/info_sec_sftp_file_retention_model.php';
    $infoSecSftp = new InfoSecSftpFileRetention();
    $action = trim($_POST['action']);
    date_default_timezone_set('Asia/Manila');
    //* Email Configuration 
    $server = '{192.107.41.4:110/pop3}INBOX';
    $username = 'isd.sftp@bannercard.com';
    $password = '###20231$D!!!!';
    //* Email Configuration End

    switch ($action) {
        case 'load_sftp_file_table_data':
            $searchValue = $_POST['search']['value'];
            $inCategory = trim($_POST['inCategory']);
            echo $infoSecSftp->loadSftpTableData($php_fetch_info_sec_api, $searchValue, $inCategory);
            break;

        case 'load_received_deleted_count':
            $inCategory = trim($_POST['inCategory']);
            echo $infoSecSftp->loadReceivedDeletedCount($php_fetch_info_sec_api, $inCategory);
            break;

        case 'fetch_email':
            $filter_date = trim($_POST['filter_date']);
            //* Connect to the POP3 server
            $inbox = imap_open($server, $username, $password);
            if (!$inbox) {
                echo ('Failed to connect to the POP3 server: ' . imap_last_error());
            }
            //* Fetch email by Filter
            $search = imap_search($inbox, 'ON "' . $filter_date . '"');
            //* Loop through email by Filter
            foreach ($search as $msgno) {
                $header = imap_headerinfo($inbox, $msgno); //* Fetch the email header
                $sender = $header->from[0]->mailbox . "@" . $header->from[0]->host; //* Print the sender of the email
                $subject = $header->subject; //* Print the subject of the email
                $body = imap_body($inbox, $msgno); //* Fetch the email body (plain text part only)
                //* Fetch Email Body
                if ($sender == 'noreply_sftp@bannercard.com') {
                    //* SFTP RECEIVED
                    if (substr($subject, 0, strpos($subject, ':')) == 'SFTP') {
                        $company = trim(substr(substr($body, strpos($body, 'D:\\') + 3), 0, strpos(substr($body, strpos($body, 'D:\\') + 3), '\\')));
                        $received_date = trim(substr($body, strpos($body, 'Received Date :') + 16, 10));
                        $received_time = trim(substr($body, strpos($body, 'Received Time :') + 16, 10));
                        $file_received = trim(substr(substr($body, strpos($body, 'File Received :') + 19 + strlen($company) + 1), 0, strpos(substr($body, strpos($body, 'File Received :') + 19 + strlen($company) + 1), 'File Size :')));
                        $file_size = trim(substr(substr($body, 0, strpos($body, 'Bytes')), strpos($body, 'File Size :') + 12) . 'Bytes');
                        //* Save record
                        echo $infoSecSftp->saveFileReceived($php_fetch_info_sec_api, $php_insert_info_sec_api, $company, $received_date, $received_time, $file_received, $file_size);
                    } else if (substr($subject, 0, strpos($subject, ':')) == 'SFTP DELETE') {
                        //* SFTP DELETED
                        $del_company = trim(substr(substr($body, strpos($body, 'D:\\') + 3), 0, strpos(substr($body, strpos($body, 'D:\\') + 3), '\\')));
                        $deleted_date = trim(substr($body, strpos($body, 'Deleted Date :') + 15, 10));
                        $deleted_time = trim(substr($body, strpos($body, 'Deleted Time :') + 15, 10));
                        $del_file_received = trim(substr(substr($body, strpos($body, 'File Received :') + 19 + strlen($del_company) + 1), 0, strpos(substr($body, strpos($body, 'File Received :') + 19 + strlen($del_company) + 1), 'File Size :')));
                        $del_file_size = trim(substr(substr($body, 0, strpos($body, 'Bytes')), strpos($body, 'File Size :') + 12) . 'Bytes');
                        //* update record if sftp data is deleted
                        $infoSecSftp->updateFileReceivedDeleted($php_fetch_info_sec_api, $php_update_info_sec_api, $del_company, $deleted_date, $deleted_time, $del_file_received, $del_file_size);
                    } else {
                        //* SFTP MOVED
                        $del_company = trim(substr(substr($body, strpos($body, 'D:\\') + 3), 0, strpos(substr($body, strpos($body, 'D:\\') + 3), '\\')));
                        $deleted_date = trim(substr($body, strpos($body, 'Received Date :') + 16, 10));
                        $deleted_time = trim(substr($body, strpos($body, 'Received Time :') + 16, 10));
                        $del_file_received = trim(substr(substr($body, strpos($body, 'File Received :') + 19 + strlen($del_company) + 1), 0, strpos(substr($body, strpos($body, 'File Received :') + 19 + strlen($del_company) + 1), 'File Size :')));
                        $del_file_size = trim(substr(substr($body, 0, strpos($body, 'Bytes')), strpos($body, 'File Size :') + 12) . 'Bytes');
                        //* update record if sftp data is deleted
                        $infoSecSftp->updateFileReceivedDeleted($php_fetch_info_sec_api, $php_update_info_sec_api, $del_company, $deleted_date, $deleted_time, $del_file_received, $del_file_size);
                    }
                }
            }
            //* Close the connection to the POP3 server
            imap_close($inbox);
            break;

        case 'insert_perso_file_deletion':
            $filter_date = trim($_POST['filter_date']);
            echo $infoSecSftp->savePersoFileDeletion($php_fetch_perso_api, $php_fetch_info_sec_api, $php_insert_perso_api, $filter_date);
            break;

        case 'update_file_retention':
            $filter_date = trim($_POST['filter_date']);
            $infoSecSftp->updateFileRetention($php_fetch_info_sec_api, $php_update_info_sec_api, $filter_date);
            break;
    }
}
