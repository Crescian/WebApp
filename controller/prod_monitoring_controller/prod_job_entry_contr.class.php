<?php
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    include_once '../../model/prod_monitoring_model/prod_job_entry_model.class.php';
    $prod = $conn->db_conn_manufacturing(); //* Manufacturing Database connection
    $jobEntry = new ProdJobEntry();
    $action = trim($_POST['action']);

    switch ($action) {
        case 'load_job_entry_table':
            echo $jobEntry->fetchData($prod);
            break;

        case 'load_job_order_number':
            $companyname = trim($_POST['companyname']);
            echo $jobEntry->loadJobOrderNumber($prod, $companyname);
            break;

        case 'load_template_name':
            $company = trim($_POST['company']);
            $jonumber = trim($_POST['jonumber']);
            $orderid = trim($_POST['orderid']);
            echo $jobEntry->loadTemplateName($prod, $company, $jonumber, $orderid);
            break;

        case 'load_assign_template_process':
            $templateid = trim($_POST['templateid']);
            echo $jobEntry->loadAssignTemplate($prod, $templateid);
            break;

        case 'save_job_entry':
            $customer_name = trim($_POST['customer_name']);
            $jonumber = trim($_POST['jonumber']);
            $job_description = trim($_POST['job_description']);
            $orderid = trim($_POST['orderid']);
            $template_id = trim($_POST['template_id']);
            $job_quantity = trim(str_replace(',', '', trim($_POST['job_quantity'])));
            $outs_no = trim($_POST['outs_no']);
            $card_type = trim($_POST['card_type']);
            $equiv_sheets = trim(str_replace(',', '', trim($_POST['equiv_sheets'])));
            $date_receive = date(trim($_POST['date_receive']) . ' H:i:s');
            $delivery_date = trim($_POST['delivery_date']);
            $start_transfer_date = date(trim($_POST['start_transfer_date']) . ' H:i:s');
            $end_transfer_date = date(trim($_POST['end_transfer_date']) . ' H:i:s');
            $job_filename = trim($_POST['job_filename']);
            $job_priority = trim($_POST['job_priority']);
            if (trim($_POST['job_chk_hold']) == 'true') {
                $job_status = 'Hold';
                $job_hold = 'true';
            } else {
                $job_status = 'Pending';
                $job_hold = 'false';
            }
            echo $jobEntry->saveJobEntry(
                $prod,
                $customer_name,
                $jonumber,
                $job_description,
                $orderid,
                $template_id,
                $job_quantity,
                $outs_no,
                $card_type,
                $equiv_sheets,
                $date_receive,
                $delivery_date,
                $start_transfer_date,
                $end_transfer_date,
                $job_filename,
                $job_priority,
                $job_status,
                $job_hold
            );
            break;

        case 'save_job_process':
            $jobentry_id = trim($_POST['jobentry_id']);
            $template_id = trim($_POST['template_id']);
            $job_priority = trim($_POST['job_priority']);
            if (trim($_POST['job_chk_hold']) == 'true') {
                $process_status = 'Hold';
            } else {
                $process_status = 'Pending';
            }
            $jobEntry->saveJobProcess($prod, $jobentry_id, $template_id, $job_priority, $process_status);
            break;

        case 'load_job_information':
            $jobentryid = trim($_POST['jobentryid']);
            echo $jobEntry->loadJobEntryInfo($prod, $jobentryid);
            break;
    }
}
