<?php
include_once '../../vendor/PhpMailer/mail_setting.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/PhpMailer/PHPMailer/src/Exception.php';
require '../../vendor/PhpMailer/PHPMailer/src/PHPMailer.php';
require '../../vendor/PhpMailer/PHPMailer/src/SMTP.php';
$mail = new PHPMailer(true);
$mail->SMTPDebug = SMTP::DEBUG_SERVER;
$mail->isSMTP();
$mail->Host       = $mail_host;
$mail->Port       = $mail_port;
$mail->SMTPAuth   = $mail_smtpauth;
$mail->SMTPSecure = $mail_smtpsecure;
$mail->Username   = $mail_username;
$mail->Password   = $mail_password;
$mail->From       = $mail_from;
$mail->FromName   = $mail_fromname;
$mail->isHTML(true);


if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    include_once '../../model/itrepair_request_model/itrepair_request_model.class.php';

    $ITR = $conn->db_conn_it_repair_request(); //* IT REPAIR AND REQUEST Database connection
    $InfoSec = $conn->db_conn_info_security(); //* INFO SEC Database connection
    $BannerWeb = $conn->db_conn_bannerweb(); //* BANNER WEB Database connection
    $ItRepairRequest = new ItRepairRequest();
    $action = trim($_POST['action']);
    $date = date('Y-m-d');

    function fetchSignature($emp_name, $BannerWeb)
    {
        $empSignature = "SELECT encode(employee_signature, 'escape') as employee_signature FROM bpi_employee_signature WHERE emp_name = :emp_name";
        $empSignature_stmt = $BannerWeb->prepare($empSignature);
        $empSignature_stmt->bindParam(':emp_name', $emp_name);
        $empSignature_stmt->execute();
        $empSignature_row = $empSignature_stmt->fetch(PDO::FETCH_ASSOC);
        return $empSignature_row['employee_signature'];
        $BannerWeb = null; //* ======== Close Connection ========
    }
    function sendEmail($BannerWeb, $mail, $requestor, $emp_name, $assignatory, $requestType, $item, $control_no, $date, $dateNeeded)
    {
        $sqlstring = "SELECT empno FROM prl_employee WHERE (emp_fn || ' ' || emp_sn) = '{$emp_name}';";
        $stmt = $BannerWeb->prepare($sqlstring);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $empno = $row['empno'];
            $sqlstringEmail = "SELECT user_email FROM bpi_user_accounts WHERE empno = '{$empno}';";
            $result_stmt = $BannerWeb->prepare($sqlstringEmail);
            $result_stmt->execute();
            while ($rows = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
                $mail->addAddress($rows['user_email']);
                if ($dateNeeded) {
                    $mail->Subject = 'FOR ' . $assignatory . ': IT REQUEST(' . $requestType . ')';
                } else {
                    $mail->Subject = 'FOR ' . $assignatory . ': IT REPAIR';
                }
                $mail->Body    = '
                            <form action="https://192.107.17.48/BannerWebApp/index.php" method="get">
                                <div style="box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);transition: 0.3s;width: 40%;border-radius: 5px;">
                                    <div style="padding: 25px 16px;">
                                        <p style="font-family:verdana;font-size:30px;">Good day! ' . $emp_name . '</p>
                                        <p style="font-family:verdana;font-size:15px;"><strong>' . $requestor . '</strong> has requested a <strong>' . $item . '</strong> with the control number <strong>' . $control_no . '</strong>. This request was made on <strong>' . $date . '</strong>';
                if ($dateNeeded) {
                    $mail->Body    .= ', and needed on <strong>' . $dateNeeded . '</strong></p>';
                }
                $mail->Body    .= '<p style="font-family:verdana;font-size:15px;">On the button below or use this link <a href="https://192.107.17.48/BannerWebApp/index.php">https://192.107.17.48/BannerWebApp/index.php</a></p>
                                        <div style="display: flex;justify-content: center;align-items: center;">
                                            <button type="submit" style="border:none;color:white;padding:15px 32px;text-align:center;text-decoration: none;display: inline-block;font-size: 16px;cursor: pointer;background-color: #4CAF50;width: 75%;">Login</button>
                                        </div>
                                    </div>
                                </div>
                            </form>';
                $mail->send();
                $mail->clearAddresses();
            }
        }
    }
    switch ($action) {
        case 'liveSearch':
            $searchVal = trim($_POST['searchVal']);
            echo $ItRepairRequest->loadLiveSearch($ITR, $InfoSec, $searchVal);
            break;
        case 'inputSearch':
            $searchVal = trim($_POST['searchVal']);
            $searchType = substr($searchVal, 0, 3);
            echo $ItRepairRequest->loadInputSearch($ITR, $InfoSec, $searchVal, $searchType);
            break;
        case 'userDetailsFunction':
            $logged_user = trim($_POST['logged_user']);
            echo $ItRepairRequest->loadUserDetailsFunction($BannerWeb, $logged_user);
            break;
        case 'loadNowRepairingOngoingRequest':
            echo $ItRepairRequest->loadNowRepairingOngoingRequest($ITR, $InfoSec);
            break;
        case 'loadQueueList':
            $logged_user = trim($_POST['logged_user']);
            echo $ItRepairRequest->loadQueueList($ITR, $InfoSec, $logged_user);
            break;
        case 'newRepair':
            $deptCode = trim($_POST['dept_code']);
            $requestedBy = trim($_POST['requested_by']);
            $area = trim($_POST['area']);
            $location = trim($_POST['location']);
            $item = trim($_POST['item']);
            $remarks = trim($_POST['remarks']);
            $ipAddress = $_SERVER['REMOTE_ADDR'];
            $appId = trim($_POST['appId']);
            $requested_by_sign = fetchSignature($requestedBy, $BannerWeb);
            $noted_by = 'Oliver Razalan';
            $noted_by_sign = fetchSignature($noted_by, $BannerWeb);
            $control_no = $ItRepairRequest->loadNewRepair($ITR, $BannerWeb, $deptCode, $requestedBy, $area, $location, $item, $remarks, $ipAddress, $appId, $requested_by_sign, $noted_by, $noted_by_sign);
            echo $control_no;
            sendEmail($BannerWeb, $mail, $requestedBy, $noted_by, 'NOTED', 'Software', $item, $control_no, $date, '');
            break;
        case 'new_web_app':
            $date_requested = $date;
            $date_needed = trim($_POST['date_needed']);
            $service_type = trim($_POST['service_type']);
            $application_name = trim($_POST['application_name']);
            $req_description = trim($_POST['req_description']);
            $web_priority = trim($_POST['web_priority']);
            $prepared_by = trim($_POST['prepared_by']);
            $approved_by = trim($_POST['approved_by']);
            $noted_by = trim($_POST['noted_by']);
            $appId = 4;
            $prepared_by_signature = fetchSignature($prepared_by, $BannerWeb);
            $approved_by_signature = fetchSignature($approved_by, $BannerWeb);
            $noted_by_signature = fetchSignature($noted_by, $BannerWeb);
            $control_no = $ItRepairRequest->loadNewWebApp($ITR, $BannerWeb, $InfoSec, $php_update_info_sec_api, $php_insert_info_sec_api, $date_requested, $date_needed, $service_type, $application_name, $req_description, $web_priority, $prepared_by, $approved_by, $noted_by, $prepared_by_signature, $approved_by_signature, $noted_by_signature, $appId);
            echo $control_no;
            sendEmail($BannerWeb, $mail, $prepared_by, $approved_by, 'APPROVAL', 'Software', $application_name, $control_no, $date_requested, $date_needed);
            sendEmail($BannerWeb, $mail, $prepared_by, $noted_by, 'NOTED', 'Software', $application_name, $control_no, $date_requested, $date_needed);
            break;
        case 'newRequest':
            $requestType = $_POST['requestType'];
            $softwareType = $_POST['softwareType'];
            $dateNeeded = $_POST['dateNeeded'];
            $item = $_POST['item'];
            $description = $_POST['description'];
            $purpose = $_POST['purpose'];
            $requestedBy = $_POST['requestedBy'];
            $approvedBy = $_POST['approvedBy'];
            $notedBy = $_POST['notedBy'];
            $prepared_by_signature = fetchSignature($requestedBy, $BannerWeb);
            $approved_by_signature = fetchSignature($approvedBy, $BannerWeb);
            $noted_by_signature = fetchSignature($notedBy, $BannerWeb);
            $appId = trim($_POST['appId']);
            $control_no = $ItRepairRequest->loadNewRequest($ITR, $BannerWeb, $date, $requestType, $softwareType, $dateNeeded, $item, $description, $purpose, $requestedBy, $approvedBy, $notedBy, $appId, $prepared_by_signature, $approved_by_signature, $noted_by_signature);
            echo $control_no;
            sendEmail($BannerWeb, $mail, $requestedBy, $approvedBy, 'APPROVAL', $requestType, $item, $control_no, $date, $dateNeeded);
            sendEmail($BannerWeb, $mail, $requestedBy, $notedBy, 'NOTED', $requestType, $item, $control_no, $date, $dateNeeded);
            break;
        case 'loadMessageSender':
            echo $ItRepairRequest->loadMessageSender($ITR, $InfoSec);
            break;
        case 'loadMessages':
            $ipAddress = $_SERVER['REMOTE_ADDR'];
            $sender = $_POST['sender'];
            echo $ItRepairRequest->loadMessages($ITR, $ipAddress, $sender);
            break;
        case 'messageSend':
            $ipAddress = $_SERVER['REMOTE_ADDR'];
            $message = $_POST['message'];
            $sender = $_POST['sender'];
            echo $ItRepairRequest->loadMessageSend($ITR, $ipAddress, $message, $sender);
            break;
        case 'updateEditRequestWeb':
            $request_date_needed_edit = trim($_POST['request_date_needed_edit']);
            $priority = trim($_POST['priority']);
            $service_type = trim($_POST['service_type']);
            $application_name = trim($_POST['application_name']);
            $request_description_edit = trim($_POST['request_description_edit']);
            $request_requested_by_edit = trim($_POST['request_requested_by_edit']);
            $request_approved_by_edit = trim($_POST['request_approved_by_edit']);
            $request_noted_by_edit = trim($_POST['request_noted_by_edit']);
            $queue_number = trim($_POST['queue_number']);
            echo $ItRepairRequest->loadUpdateEditRequestWeb($InfoSec, $request_date_needed_edit, $priority, $service_type, $application_name, $request_description_edit, $request_requested_by_edit, $request_approved_by_edit, $request_noted_by_edit, $queue_number);
            break;
        case 'updateEditRequest':
            $request_request_type_edit = trim($_POST['request_request_type_edit']);
            $request_software_type_edit = trim($_POST['request_software_type_edit']);
            $request_date_needed_edit = trim($_POST['request_date_needed_edit']);
            $request_description_edit = trim($_POST['request_description_edit']);
            $request_purpose_edit = trim($_POST['request_purpose_edit']);
            $request_requested_by_edit = trim($_POST['request_requested_by_edit']);
            $request_approved_by_edit = trim($_POST['request_approved_by_edit']);
            $request_noted_by_edit = trim($_POST['request_noted_by_edit']);
            $queue_number = trim($_POST['queue_number']);
            echo $ItRepairRequest->loadUpdateEditRequest($ITR, $request_request_type_edit, $request_software_type_edit, $request_date_needed_edit, $request_description_edit, $request_purpose_edit, $request_requested_by_edit, $request_approved_by_edit, $request_noted_by_edit, $queue_number);
            break;
        case 'load_edit_request_web':
            $referenceNumber = trim($_POST['referenceNumber']);
            echo $ItRepairRequest->loadEditRequestWeb($InfoSec, $referenceNumber);
            break;
        case 'load_edit_request':
            $referenceNumber = trim($_POST['referenceNumber']);
            echo $ItRepairRequest->loadEditRequest($ITR, $referenceNumber);
            break;
        case 'getPosCode':
            $user_department = trim($_POST['user_department']);
            echo $ItRepairRequest->loadGetPosCode($BannerWeb, $user_department);
            break;
        case 'updateOnHoldRepair':
            $queueNumber = trim($_POST['queue_number']);
            $deptCode = trim($_POST['dept_code']);
            $requestedBy = trim($_POST['requested_by']);
            $area = trim($_POST['area']);
            $location = trim($_POST['location']);
            $item = trim($_POST['item']);
            $remarks = trim($_POST['remarks']);
            $ipAddress = $_SERVER['REMOTE_ADDR'];
            echo $ItRepairRequest->loadUpdateOnHoldRepair($ITR, $queueNumber, $deptCode, $requestedBy, $area, $location, $item, $remarks, $ipAddress);
            break;
        case 'new_server_request':
            $server_ip = trim($_POST['server_ip']);
            $user_name = trim($_POST['user_name']);
            $revoke = trim($_POST['revoke']);
            $ip_address = trim($_POST['ip_address']);
            $mac_address = trim($_POST['mac_address']);
            $location_server = trim($_POST['location_server']);
            $request_purpose = trim($_POST['request_purpose']);
            $requestedBy = trim($_POST['requestedBy']);
            $approvedBy = trim($_POST['approvedBy']);
            $notedBy = trim($_POST['notedBy']);
            $requested_by_sign = fetchSignature($requestedBy, $BannerWeb);
            $approved_by_sign = fetchSignature($approvedBy, $BannerWeb);
            $noted_by_sign = fetchSignature($notedBy, $BannerWeb);
            echo $ItRepairRequest->new_server_request($InfoSec, $server_ip, $user_name, $revoke, $ip_address, $mac_address, $location_server, $request_purpose, $requestedBy, $approvedBy, $notedBy, $requested_by_sign, $approved_by_sign, $noted_by_sign);
            break;
        case 'load_edit_request_server':
            $referenceNumber = trim($_POST['referenceNumber']);
            echo $ItRepairRequest->loadEditRequestServer($InfoSec, $referenceNumber);
            break;
        case 'updateEditRequestServer':
            $server_edit = trim($_POST['server_edit']);
            $user_edit = trim($_POST['user_edit']);
            $access_revoke_edit = trim($_POST['access_revoke_edit']);
            $server_ip_edit = trim($_POST['server_ip_edit']);
            $mac_address_edit = trim($_POST['mac_address_edit']);
            $location_server_edit = trim($_POST['location_server_edit']);
            $request_purpose_edit = trim($_POST['request_purpose_edit']);
            $request_requested_by_edit = trim($_POST['request_requested_by_edit']);
            $request_approved_by_edit = trim($_POST['request_approved_by_edit']);
            $request_noted_by_edit = trim($_POST['request_noted_by_edit']);
            $queue_number = trim($_POST['queue_number']);
            echo $ItRepairRequest->loadUpdateEditRequestServer($InfoSec, $server_edit, $user_edit, $access_revoke_edit, $server_ip_edit, $mac_address_edit, $location_server_edit, $request_purpose_edit, $request_requested_by_edit, $request_approved_by_edit, $request_noted_by_edit, $queue_number);
            break;
    }
}
