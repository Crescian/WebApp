<?php
class PhdDailyRoom
{
    public function fetchData($PHD)
    {
        $itemData_List = array();
        $sqlstring = "SELECT * FROM phd_dr_inspection_header";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute();
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            $sqlstringCount = "SELECT COUNT(*) AS total_count, (SELECT COUNT(*) FROM phd_dr_inspection_details WHERE dr_prepared IS NOT NULL AND dailyroom_id = ?) AS prepared_count 
                    FROM phd_dr_inspection_details WHERE dailyroom_id = ? ";
            $fetchCount_stmt = $PHD->prepare($sqlstringCount);
            $fetchCount_stmt->execute([$row['dailyroomid'], $row['dailyroomid']]);
            $fetchCount_row = $fetchCount_stmt->fetch(PDO::FETCH_ASSOC);

            $nestedData = array();
            $nestedData[] = date_format(date_create($row['dr_date']), 'Y-m-d');
            $nestedData[] = $row['dr_title'];
            $nestedData[] = $row['dr_prepared_main'] == '' ? '-' : $row['dr_prepared_main'];
            $nestedData[] = $row['dr_prepared_admin_lobby'] == '' ? '-' : $row['dr_prepared_admin_lobby'];
            $nestedData[] = $row['dr_prepared_warehouse_2_3'] == '' ? '-' : $row['dr_prepared_warehouse_2_3'];
            $nestedData[] = $row['dr_noted'];
            $nestedData[] = array($row['dailyroomid'], $fetchCount_row['total_count'], $fetchCount_row['prepared_count']);
            $itemData_List['data'][] = $nestedData;
        }
        return json_encode($itemData_List);
        $PHD = null; //* ======== Close Connection ========
    }
    public function generateData($PHD)
    {
        $categoryArray = array();
        $sqlstringcategory = "SELECT checklist_name,category_name,location_name FROM phd_checklist_assign 
                            INNER JOIN phd_checklist_name ON phd_checklist_name.phdchklistid = phd_checklist_assign.phdchklist_id
                            INNER JOIN phd_location_category ON phd_location_category.phdloccatid = phd_checklist_assign.phdloccat_id
                            INNER JOIN phd_loc_category_assign ON phd_loc_category_assign.phdloccat_id = phd_checklist_assign.phdloccat_id
                            INNER JOIN phd_location ON phd_location.phdlocationid = phd_loc_category_assign.phdlocation_id
                            WHERE checklist_name ILIKE '%Daily Room Inspection Checklist%' ORDER BY phdloccatassignid ASC";
        $result_stmt_categ = $PHD->prepare($sqlstringcategory);
        $result_stmt_categ->execute();
        while ($row = $result_stmt_categ->fetch(PDO::FETCH_ASSOC)) {
            $categoryArray[$row['category_name']][] = $row;
        }
        return json_encode($categoryArray);
        $PHD = null; //* ======== Close Connection ========
    }
    public function saveHeaderData($PHD, $preparedBySignature, $notedBySignature, $prepared_by, $designation, $notedBy, $date_created)
    {
        $drTitle = 'Daily Room Inspection For The Month Of ' . date('F');
        $itemData_List = array();
        // * GENERATE REFERRENCE NUMBER *//
        $sqlstringRefno = "SELECT * FROM phd_dr_inspection_refno";
        $result_stmt_refno = $PHD->prepare($sqlstringRefno);
        $result_stmt_refno->execute();
        $result_res_refno = $result_stmt_refno->fetch(PDO::FETCH_ASSOC);
        $refno = $result_res_refno['dr_refno'];
        $currYear = date('y');
        $getYear =  substr($refno, 5, 2);
        if ($currYear != $getYear) {
            $ref_noResult = '0001-' . $currYear;
        } else {
            $currCount = substr($refno, 0, 4);
            $counter = intval($currCount) + 1;
            $ref_noResult = str_pad($counter, 4, '0', STR_PAD_LEFT) . '-' . $currYear;
        }
        switch ($designation) {
            case 'Admin & Lobby':
                $prepared_field = 'dr_prepared_admin_lobby,dr_prepared_admin_lobby_sign,dr_prepared_admin_lobby_date';
                break;
            case 'Warehouse 2 & 3':
                $prepared_field = 'dr_prepared_warehouse_2_3,dr_prepared_warehouse_2_3_sign,dr_prepared_warehouse_2_3_date';
                break;
            default:
                $prepared_field = 'dr_prepared_main,dr_prepared_main_sign,dr_prepared_main_date';
                break;
        }

        $sqlstring = "INSERT INTO phd_dr_inspection_header(dr_refno,dr_title,dr_date," . $prepared_field . ",dr_noted, dr_noted_sign)
                VALUES(?,?,?,?,?,?,?,?) RETURNING dailyroomid";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$ref_noResult, $drTitle, $date_created, $prepared_by, $preparedBySignature, $date_created, $notedBy, $notedBySignature]);

        $itemData_List['pagingheader_id'] = $PHD->lastInsertId();
        $itemData_List['paging_ref_no'] = $ref_noResult;
        return json_encode($itemData_List);
        $PHD = null; //* ======== Close Connection ========
    }
    public function saveDetailsData($PHD, $generateRefno, $strRoom, $strCategory, $strAircon, $strlight, $strDoor, $strOutlet, $strRemarks, $dailyroomid, $dr_time_activated, $details_date_created, $performedBy)
    {
        $sqlstringDetails = "INSERT INTO phd_dr_inspection_details(dailyroom_id,dr_rooms,dr_time_activated,dr_aircon_off,dr_lights_off,dr_door_locked,dr_conv_outlet_unplugged,dr_remarks,dr_refno,dr_category,dr_prepared,dr_prepared_date)
                                VALUES(?,?,?,?,?,?,?,?,?,?,?,?)";
        $result_stmt_details = $PHD->prepare($sqlstringDetails);
        $result_stmt_details->execute([$dailyroomid, $strRoom, $dr_time_activated, $strAircon, $strlight, $strDoor, $strOutlet, $strRemarks, $generateRefno, $strCategory, $performedBy, $details_date_created]);
        //* ========== Update Ref No ==========
        $sqlstringUpRefno = "UPDATE phd_dr_inspection_refno SET dr_refno = ?";
        $result_stmt_refno = $PHD->prepare($sqlstringUpRefno);
        $result_stmt_refno->execute([$generateRefno]);
        $PHD = null; //* ======== Close Connection ========
    }
    public function deleteData($PHD, $dailyid)
    {
        $sqlstring = "DELETE FROM phd_dr_inspection_header WHERE dailyroomid = ?";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$dailyid]);
        $PHD = null; //* ======== Close Connection ========
    }
    public function previewData($PHD, $dailyroomid)
    {
        $categoryArray = array();
        $sqlstring = "SELECT * FROM phd_dr_inspection_details WHERE dailyroom_id = ? ORDER BY drdetailid ASC";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$dailyroomid]);
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            $categoryArray[$row['dr_category']][] = $row;
        }
        return json_encode($categoryArray);
        $PHD = null; //* ======== Close Connection ========
    }
    public function updateHeaderData($PHD, $logged_user, $designation, $dailyroomid, $result_res_main, $result_res_admin, $result_res_warehouse, $date_created, $result_res_main1, $result_res_main2, $result_res_main3, $result_res_admin_lobby1, $result_res_admin_lobby2, $result_res_admin_lobby3, $result_res_warehouse_2_31, $result_res_warehouse_2_32, $result_res_warehouse_2_33)
    {
        $sqlstring = "UPDATE phd_dr_inspection_header SET ";

        switch ($designation) {
            case 'Main Plant':
                if ($result_res_main1['dr_prepared_main'] != '') {
                    if ($result_res_main2['dr_prepared_main2'] != '') {
                        if ($result_res_main3['dr_prepared_main3'] == '') {
                            $sqlstring .= "dr_prepared_main3 = '" . $logged_user . "',dr_prepared_main3_sign = '" . $result_res_main . "' ,dr_prepared_main3_date = '" . $date_created . "' ";
                        }
                    } else {
                        $sqlstring .= "dr_prepared_main2 = '" . $logged_user . "',dr_prepared_main2_sign = '" . $result_res_main . "' ,dr_prepared_main2_date = '" . $date_created . "' ";
                    }
                } else {
                    $sqlstring .= "dr_prepared_main = '" . $logged_user . "',dr_prepared_main_sign = '" . $result_res_main . "' ,dr_prepared_main_date = '" . $date_created . "' ";
                }
                break;

            case 'Admin & Lobby':
                if ($result_res_admin_lobby1['dr_prepared_admin_lobby'] != '') {
                    if ($result_res_admin_lobby2['dr_prepared_admin_lobby2'] != '') {
                        if ($result_res_admin_lobby3['dr_prepared_admin_lobby3'] == '') {
                            $sqlstring .= "dr_prepared_admin_lobby3 = '" . $logged_user . "',dr_prepared_admin_lobby3_sign = '" . $result_res_admin . "' ,dr_prepared_admin_lobby3_date = '" . $date_created . "' ";
                        }
                    } else {
                        $sqlstring .= "dr_prepared_admin_lobby2 = '" . $logged_user . "',dr_prepared_admin_lobby2_sign = '" . $result_res_admin . "' ,dr_prepared_admin_lobby2_date = '" . $date_created . "' ";
                    }
                } else {
                    $sqlstring .= "dr_prepared_admin_lobby = '" . $logged_user . "',dr_prepared_admin_lobby_sign = '" . $result_res_admin . "' ,dr_prepared_admin_lobby_date = '" . $date_created . "' ";
                }
                break;
            case 'Warehouse 2 & 3':
                if ($result_res_warehouse_2_31['dr_prepared_warehouse_2_3'] != '') {
                    if ($result_res_warehouse_2_32['dr_prepared_warehouse_2_32'] != '') {
                        if ($result_res_warehouse_2_33['dr_prepared_warehouse_2_33'] == '') {
                            $sqlstring .= "dr_prepared_warehouse_2_33 = '" . $logged_user . "',dr_prepared_warehouse_2_3_sign3 = '" . $result_res_warehouse . "' ,dr_prepared_warehouse_2_3_date3 = '" . $date_created . "' ";
                        }
                    } else {
                        $sqlstring .= "dr_prepared_warehouse_2_32 = '" . $logged_user . "',dr_prepared_warehouse_2_3_sign2 = '" . $result_res_warehouse . "' ,dr_prepared_warehouse_2_3_date2 = '" . $date_created . "' ";
                    }
                } else {
                    $sqlstring .= "dr_prepared_warehouse_2_3 = '" . $logged_user . "',dr_prepared_warehouse_2_3_sign = '" . $result_res_warehouse . "' ,dr_prepared_warehouse_2_3_date = '" . $date_created . "' ";
                }
                break;
        }
        $sqlstring .= "WHERE dailyroomid = '" . $dailyroomid . "'";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute();
    }
    public function updateDetailsData($PHD, $strPrepared, $strDetails, $strCategory, $strBtnActivate, $strAircon, $strlight, $strDoor, $strOutlet, $strRemarks, $performedBy, $dailyroomid)
    {
        $sqlstring = "UPDATE phd_dr_inspection_details SET ";
        if ($strPrepared == 'null') {
            if ($strRemarks == '') {
                $performedBy = null;
                $date = null;
            } else {
                $performedBy = trim($_POST['performedBy']);
                $date = date("Y-m-d");
            }
            $sqlstring .= "dr_time_activated = ?, dr_prepared = ?, dr_prepared_date = ?, dr_remarks = ?, dr_aircon_off = ?, dr_lights_off = ?, 
                    dr_door_locked = ?, dr_conv_outlet_unplugged = ? WHERE drdetailid = ?";
            $result_stmt = $PHD->prepare($sqlstring);
            $result_stmt->execute([$strBtnActivate, $performedBy, $date, $strRemarks, $strAircon, $strlight, $strDoor, $strOutlet, $strDetails]);
        } else {
            $sqlstring .= "dr_remarks = ?, dr_aircon_off = ?, dr_lights_off = ?, 
                    dr_door_locked = ?, dr_conv_outlet_unplugged = ? WHERE drdetailid = ?";
            $result_stmt = $PHD->prepare($sqlstring);
            $result_stmt->execute([$strRemarks, $strAircon, $strlight, $strDoor, $strOutlet, $strDetails]);
        }
    }
    public function previewDataNoted($PHD, $dailyroomId)
    {
        $surveillance = array();
        $sqlstring = "SELECT * FROM phd_dr_inspection_header WHERE dailyroomid = '". $dailyroomId."'";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute();
        $result_res = $result_stmt->fetchAll();
        foreach ($result_res as $row) {
            $surveillance['result'] = $row['dr_noted'];
        }
        return json_encode($surveillance);
    }
}
