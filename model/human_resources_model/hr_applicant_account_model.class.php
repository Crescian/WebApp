<?php
class ApplicantAccount
{
    public function emailVerified($emailVerified)
    {
        switch ($emailVerified) {
            case '1':
                $emailVerify = '<span class="badge bg-success col-sm-12">Yes</span>';
                break;
            case '0':
                $emailVerify = '<span class="badge bg-danger col-sm-12">No</span>';
                break;
        }
        return $emailVerify;
    }

    public function applicantGender($appGender)
    {
        switch ($appGender) {
            case 'Male':
                $gender = '<span class="badge bg-primary col-sm-12"><i class="fa-solid fa-mars p-r-8"></i>Male</span>';
                break;
            case 'Female':
                $gender = '<span class="badge date-color14 col-sm-12"><i class="fa-solid fa-venus p-r-8"></i>Female</span>';
                break;
        }
        return $gender;
    }

    public function loadApplicantAccountData($hr_conn, $searchValue)
    {
        $itemData_List = array();
        //* ======== Create Array for column same with column names on database for ordering ========
        $col = array(
            0 => 'applicant_fn',
            1 => 'applicant_sn',
            2 => 'applicant_email',
            3 => 'applicant_bday',
            4 => 'applicant_gender',
            5 => 'account_date_created',
            6 => 'email_verified'
        );

        $sqlstring = "SELECT * FROM hr_applicant_accounts WHERE 1 = 1";
        $result_stmt = $hr_conn->prepare($sqlstring);
        $result_stmt->execute();
        $result_total_record = $result_stmt->rowCount();
        //* ======== Fetch Total Filtered Record ========
        if (!empty($searchValue)) {
            $sqlstring .= " AND (applicant_fn ILIKE '%" . $searchValue . "%' OR applicant_sn ILIKE '%" . $searchValue . "%' OR applicant_email ILIKE '%" . $searchValue . "%' OR TO_CHAR(applicant_bday, 'YYYY-MM-DD') ILIKE '%" . $searchValue . "%' OR
                applicant_gender ILIKE '%" . $searchValue . "%' OR TO_CHAR(account_date_created, 'YYYY-MM-DD') ILIKE '%" . $searchValue . "%')";
            $result_stmt = $hr_conn->prepare($sqlstring);
            $result_stmt->execute();
        }
        $result_total_record_filtered = $result_stmt->rowCount();
        //* ======== Ordering ========
        $sqlstring .= " ORDER BY {$col[$_POST['order'][0]['column']]} {$_POST['order'][0]['dir']} LIMIT {$_POST['length']} OFFSET {$_POST['start']}";
        $result_stmt = $hr_conn->prepare($sqlstring);
        $result_stmt->execute();
        //* ======== Prepare Array ========
        foreach ($result_stmt->fetchAll() as $row) {
            $itemData_List[] = array(
                $row['applicant_fn'],
                $row['applicant_sn'],
                $row['applicant_email'],
                $row['applicant_bday'],
                self::applicantGender($row['applicant_gender']),
                date_format(date_create($row['account_date_created']), 'Y-m-d'),
                self::emailVerified($row['email_verified']),
                [$row['applicantid']]
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
        $hr_conn = null; //* ======== Close Connection ========
    }

    public function deleteApplicantAccount($hr_conn, $applicantid)
    {
        $sqlstring = "DELETE FROM hr_applicant_accounts WHERE applicantid = ?";
        $result_stmt = $hr_conn->prepare($sqlstring);
        $result_stmt->execute([$applicantid]);
        $hr_conn = null; //* ======== Close Connection ========
    }
}
