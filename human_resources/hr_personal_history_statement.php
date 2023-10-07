<?php include './../includes/header.php';
session_start();
// * Check if module is within the application
$currentPage = substr($_SERVER['SCRIPT_NAME'], strrpos($_SERVER['SCRIPT_NAME'], "/") + 1);
$sqlstring = "SELECT app_id FROM bpi_app_menu_module WHERE app_menu_link ILIKE '%" . $currentPage . "'";
$data_base64 = base64_encode($sqlstring);
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $php_fetch_bannerweb_api);
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, array("data" => $data_base64));
$json_response = curl_exec($curl);
//* ====== Close Connection ======
curl_close($curl);
// * ======== Prepare Array ========
$data_result = json_decode($json_response, true);
foreach ($data_result['data'] as $row) {
    $chkAppId = $row['app_id'];
}
if (!isset($_GET['app_id'])) {
    header('location: ../Landing_Page.php');
} else if ($_GET['app_id'] != $chkAppId) {
    header('location: ../Landing_Page.php');
}
?>
<link rel="stylesheet" type="text/css" href="../vendor/css/custom.menu.css" />
<style>
    ::-webkit-scrollbar {
        width: 0.5vw;
    }

    ::-webkit-scrollbar-thumb {
        background-color: #291af5;
        border-radius: 100vw;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col content overflow-auto p-4 d-md-block" style="max-height: 100vh;">
            <div class="row">
                <span class="page-title-humanresources">Personal History Statement</span>
            </div>
            <!-- content section -->
            <div class="row mt-5 mb-4">
                <div class="col-xl-12">
                    <div class="card shadow mb-4">
                        <div class="card-header card-8 py-3">
                            <div class="row">
                                <span class="fw-bold fs-27 text-light">List</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="phs_applicant_table" class="table table-bordered table-striped fw-bold" width="100%">
                                    <thead class="customHeaderHR">
                                        <tr>
                                            <th style="text-align: center;">Date Created</th>
                                            <th>Applicant Name</th>
                                            <th>Referred By</th>
                                            <th style="text-align: center;">Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot class="customHeaderHR">
                                        <tr>
                                            <th style="text-align: center;">Date Created</th>
                                            <th>Applicant Name</th>
                                            <th>Referred By</th>
                                            <th style="text-align: center;">Action</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- =============== Submit Form Modal =============== -->
            <div class="modal fade" id="personalHistoryStatementModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-8">
                            <h4 class="modal-title text-uppercase fw-bold text-light">PERSONAL HISTORY STATEMENT</h4>
                        </div>
                        <div class="modal-body">
                            <div class="hr_applicant_image_container mb-5">
                                <div class="hr_applicant_container"></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-1 d-flex align-items-center">
                                    <span class="fs-18 fw-bold text-black-50">Name</span>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_surname" readonly>
                                        <label for="fr_surname" class="col-form-label fw-bold">Surname:</label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_firstname" readonly>
                                        <label for="fr_firstname" class="col-form-label fw-bold">First name:</label>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_extname" readonly>
                                        <label for="fr_extname" class="col-form-label fw-bold">Name Extension:</label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_middlename" readonly>
                                        <label for="fr_middlename" class="col-form-label fw-bold">Middle name:</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <span class="fs-13 fw-bold text-black-50">(If Married Female, please write Full Maiden Name)</span>
                            </div>
                            <div class="row">
                                <div class="col-sm-2 d-flex align-items-center">
                                    <span class="fs-18 fw-bold text-black-50">Maiden Name</span>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_maiden_surname" readonly>
                                        <label for="fr_maiden_surname" class="col-form-label fw-bold">Surname:</label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_maiden_firstname" readonly>
                                        <label for="fr_maiden_firstname" class="col-form-label fw-bold">First name:</label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_maiden_middlename" readonly>
                                        <label for="fr_maiden_middlename" class="col-form-label fw-bold">Middle name:</label>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_nickname_alias" readonly>
                                        <label for="fr_nickname_alias" class="col-form-label fw-bold">Nickname/Alias:</label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_sex" readonly>
                                        <label for="fr_sex" class="col-form-label fw-bold">Sex:</label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_citizenship" readonly>
                                        <label for="fr_citizenship" class="col-form-label fw-bold">Citizenship:</label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_religion" readonly>
                                        <label for="fr_religion" class="col-form-label fw-bold">Religion:</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-sm-2">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-1 border-black fw-bold" id="fr_blood_type">
                                        <label for="fr_blood_type" class="col-form-label fw-bold">Blood Type:</label>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_height" readonly>
                                        <label for="fr_height" class="col-form-label fw-bold">Height(cm):</label>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_weight" readonly>
                                        <label for="fr_weight" class="col-form-label fw-bold">Weight(Kg):</label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_eye_color" readonly>
                                        <label for="fr_eye_color" class="col-form-label fw-bold">Color of Eye:</label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_hair_color" readonly>
                                        <label for="fr_hair_color" class="col-form-label fw-bold">Color of Hair:</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-sm-3">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_date_of_birth" readonly>
                                        <label for="fr_date_of_birth" class="col-form-label fw-bold">Date of Birth:</label>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_age" readonly>
                                        <label for="fr_age" class="col-form-label fw-bold">Age:</label>
                                    </div>
                                </div>
                                <div class="col-sm-7">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_birth_place" readonly>
                                        <label for="fr_birth_place" class="col-form-label fw-bold">Place of Birth:</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-sm-3">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_contact_number" readonly>
                                        <label for="fr_contact_number" class="col-form-label fw-bold">Contact number:</label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_email_address" readonly>
                                        <label for="fr_email_address" class="col-form-label fw-bold">Email address:</label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_civil_status" readonly>
                                        <label for="fr_civil_status" class="col-form-label fw-bold">Civil Status:</label>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <span class="fs-18 fw-bold text-black-50">Present Address</span>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_present_unit_room_floor" readonly>
                                        <label for="fr_present_unit_room_floor" class="col-form-label fw-bold">Unit/Room No./Floor:</label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_present_lot_block_house_bldg" readonly>
                                        <label for="fr_present_lot_block_house_bldg" class="col-form-label fw-bold">Lot/Block/House/Bldg. No:</label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_present_street" readonly>
                                        <label for="fr_present_street" class="col-form-label fw-bold">Street:</label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_present_subdivision_village" readonly>
                                        <label for="fr_present_subdivision_village" class="col-form-label fw-bold">Subdivision/Village:</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_present_region" readonly>
                                        <label for="fr_present_region" class="col-form-label fw-bold">Region:</label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_present_province" readonly>
                                        <label for="fr_present_province" class="col-form-label fw-bold">Province:</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-5">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_present_city_municipality" readonly>
                                        <label for="fr_present_city_municipality" class="col-form-label fw-bold">City/Municipality:</label>
                                    </div>
                                </div>
                                <div class="col-sm-5">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_present_barangay" readonly>
                                        <label for="fr_present_barangay" class="col-form-label fw-bold">Barangay:</label>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_present_zip_code" readonly>
                                        <label for="fr_present_zip_code" class="col-form-label fw-bold">Zip Code:</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <span class="fs-18 fw-bold text-black-50">Permanent Address</span>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_permanent_unit_room_floor" readonly>
                                        <label for="fr_permanent_unit_room_floor" class="col-form-label fw-bold">Unit/Room No./Floor:</label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_permanent_lot_block_house_bldg" readonly>
                                        <label for="fr_permanent_lot_block_house_bldg" class="col-form-label fw-bold">Lot/Block/House/Bldg. No:</label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_permanent_street" readonly>
                                        <label for="fr_permanent_street" class="col-form-label fw-bold">Street:</label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_permanent_subdivision_village" readonly>
                                        <label for="fr_permanent_subdivision_village" class="col-form-label fw-bold">Subdivision/Village:</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_permanent_region" readonly>
                                        <label for="fr_permanent_region" class="col-form-label fw-bold">Region:</label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_permanent_province" readonly>
                                        <label for="fr_permanent_province" class="col-form-label fw-bold">Province:</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-5">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_permanent_city_municipality" readonly>
                                        <label for="fr_permanent_city_municipality" class="col-form-label fw-bold">City/Municipality:</label>
                                    </div>
                                </div>
                                <div class="col-sm-5">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_permanent_barangay" readonly>
                                        <label for="fr_permanent_barangay" class="col-form-label fw-bold">Barangay:</label>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_permanent_zip_code" readonly>
                                        <label for="fr_permanent_zip_code" class="col-form-label fw-bold">Zip Code:</label>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-1 border-black fw-bold" id="fr_sss_number">
                                        <label for="fr_sss_number" class="col-form-label fw-bold">SSS Number:</label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-1 border-black fw-bold" id="fr_tin_number">
                                        <label for="fr_tin_number" class="col-form-label fw-bold">Tax Identification Number:</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-1 border-black fw-bold" id="fr_philhealth_number">
                                        <label for="fr_philhealth_number" class="col-form-label fw-bold">Philhealth Number:</label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-1 border-black fw-bold" id="fr_pagibig_number">
                                        <label for="fr_pagibig_number" class="col-form-label fw-bold">HDMF Pag-Ibig Number:</label>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row mt-2">
                                <span class="fs-18 fw-bold text-black-50">Name of Spouse <span class="fs-13">(If Married)</span></span>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_spouse_surname" readonly>
                                        <label for="fr_spouse_surname" class="col-form-label fw-bold">Surname:</label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_spouse_firstname" readonly>
                                        <label for="fr_spouse_firstname" class="col-form-label fw-bold">First name:</label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_spouse_middlename" readonly>
                                        <label for="fr_spouse_middlename" class="col-form-label fw-bold">Middle name:</label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_spouse_name_extension" readonly>
                                        <label for="fr_spouse_name_extension" class="col-form-label fw-bold">Name Extension:</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_spouse_occupation" readonly>
                                        <label for="fr_spouse_occupation" class="col-form-label fw-bold">Current Occupation of Spouse:</label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_spouse_employer" readonly>
                                        <label for="fr_spouse_employer" class="col-form-label fw-bold">Employer:</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <span class="fs-18 fw-bold text-black-50">Children/s</span>
                            </div>
                            <div class="spouse_child_container"></div>
                            <hr>
                            <div class="row mb-1">
                                <span class="fs-20 fw-bold text-black-50">II. FAMILY HISTORY AND INFORMATION</span>
                            </div>
                            <div class="row">
                                <span class="fs-18 fw-bold text-black-50">Full name of Father</span>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_father_surname" readonly>
                                        <label for="fr_father_surname" class="col-form-label fw-bold">Surname:</label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_father_firstname" readonly>
                                        <label for="fr_father_firstname" class="col-form-label fw-bold">First name:</label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_father_middlename" readonly>
                                        <label for="fr_father_middlename" class="col-form-label fw-bold">Middle name:</label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_father_name_extension" readonly>
                                        <label for="fr_father_name_extension" class="col-form-label fw-bold">Name Extension:</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_father_occupation" readonly>
                                        <label for="fr_father_occupation" class="col-form-label fw-bold">Occupation:</label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_father_employer" readonly>
                                        <label for="fr_father_employer" class="col-form-label fw-bold">Employer:</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <span class="fs-18 fw-bold text-black-50">Full name of Mother</span>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_mother_surname" readonly>
                                        <label for="fr_mother_surname" class="col-form-label fw-bold">Surname:</label>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_mother_firstname" readonly>
                                        <label for="fr_mother_firstname" class="col-form-label fw-bold">First name:</label>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_mother_middlename" readonly>
                                        <label for="fr_mother_middlename" class="col-form-label fw-bold">Middle name:</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_mother_occupation" readonly>
                                        <label for="fr_mother_occupation" class="col-form-label fw-bold">Occupation:</label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_mother_employer" readonly>
                                        <label for="fr_mother_employer" class="col-form-label fw-bold">Employer:</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <span class="fs-18 fw-bold text-black-50">Brother/s and Sister/s</span>
                            </div>
                            <div class="brother_sister_container"></div>
                            <hr>
                            <div class="row mb-1 mt-2">
                                <span class="fs-20 fw-bold text-black-50">III. EDUCATIONAL ATTAINMENT</span>
                            </div>
                            <div class="row">
                                <span class="fs-18 fw-bold text-black-50">Tertiary Course/s</span>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_tertiary_school" readonly>
                                        <label for="fr_tertiary_school" class="col-form-label fw-bold">Name of School:</label>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_tertiary_address" readonly>
                                        <label for="fr_tertiary_address" class="col-form-label fw-bold">Address:</label>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_tertiary_from" readonly>
                                        <label for="fr_tertiary_from" class="col-form-label fw-bold">From:</label>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_tertiary_to" readonly>
                                        <label for="fr_tertiary_to" class="col-form-label fw-bold">To:</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <span class="fs-18 fw-bold text-black-50">Sr. High School</span>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_senior_high_school" readonly>
                                        <label for="fr_senior_high_school" class="col-form-label fw-bold">Name of School:</label>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_senior_high_address" readonly>
                                        <label for="fr_senior_high_address" class="col-form-label fw-bold">Address:</label>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_senior_high_from" readonly>
                                        <label for="fr_senior_high_from" class="col-form-label fw-bold">From:</label>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_senior_high_to" readonly>
                                        <label for="fr_senior_high_to" class="col-form-label fw-bold">To:</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <span class="fs-18 fw-bold text-black-50">Jr. High School</span>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_junior_high_school" readonly>
                                        <label for="fr_junior_high_school" class="col-form-label fw-bold">Name of School:</label>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_junior_high_address" readonly>
                                        <label for="fr_junior_high_address" class="col-form-label fw-bold">Address:</label>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_junior_high_from" readonly>
                                        <label for="fr_junior_high_from" class="col-form-label fw-bold">From:</label>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_junior_high_to" readonly>
                                        <label for="fr_junior_high_to" class="col-form-label fw-bold">To:</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <span class="fs-18 fw-bold text-black-50">Elementary</span>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_elementary_school" readonly>
                                        <label for="fr_elementary_school" class="col-form-label fw-bold">Name of School:</label>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_elementary_address" readonly>
                                        <label for="fr_elementary_address" class="col-form-label fw-bold">Address:</label>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_elementary_from" readonly>
                                        <label for="fr_elementary_from" class="col-form-label fw-bold">From:</label>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_elementary_to" readonly>
                                        <label for="fr_elementary_to" class="col-form-label fw-bold">To:</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <span class="fs-18 fw-bold text-black-50">Seminar's/Training's/Workshop's Attended</span>
                            </div>
                            <div class="seminar_training_container"></div>
                            <hr>
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_special_skills" readonly>
                                <label for="fr_special_skills" class="col-form-label fw-bold">Special Skills:</label>
                            </div>
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_award_honor_license" readonly>
                                <label for="fr_award_honor_license" class="col-form-label fw-bold">Awards/Honors/License Recieved:</label>
                            </div>
                            <hr>
                            <div class="row mb-3">
                                <span class="fs-20 fw-bold text-black-50">IV. EMPLOYMENT HISTORY <span class="fs-15 fw-bold text-black-50">(Please list down your employment history within the last seven years and
                                        kindly explain any employment gaps from one company to other)</span></span>
                            </div>
                            <div class="employment_history_container"></div>
                            <div class="row mb-3">
                                <span class="fs-20 fw-bold text-black-50">V. CHARACTER REFERENCES</span>
                            </div>
                            <div class="char_reference_container"></div>

                            <hr>
                            <div class="row mb-3">
                                <span class="fs-20 fw-bold text-black-50">VI. CONTACT PERSON IN CASE OF EMERGENCY</span>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_emergency_name" readonly>
                                        <label for="fr_emergency_name" class="col-form-label fw-bold">Name:</label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_emergency_relationship" readonly>
                                        <label for="fr_emergency_relationship" class="col-form-label fw-bold">Relationship:</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_emergency_address" readonly>
                                        <label for="fr_emergency_address" class="col-form-label fw-bold">Address:</label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_emergency_tell_cell_number" readonly>
                                        <label for="fr_emergency_tell_cell_number" class="col-form-label fw-bold">Cellphone No.:</label>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <span class="col-form-label col-sm-6 fw-bold">Do you have any relatives working with any local telecom?</span>
                                <div class="col-sm mt-2">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="working_in_telecom_radio" id="working_in_telecom_yes" value="Yes" disabled>
                                        <label class="form-check-label fw-bold" for="working_in_telecom_yes">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="working_in_telecom_radio" id="working_in_telecom_no" value="No" disabled>
                                        <label class="form-check-label fw-bold" for="working_in_telecom_no">No</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <span class="fs-14 fw-bold text-black-50">If yes, please state the following:</span>
                                <div class="col-sm-6">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_telecom_name" readonly>
                                        <label for="fr_telecom_name" class="col-form-label fw-bold">Name:</label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_telecom_relationship" readonly>
                                        <label for="fr_telecom_relationship" class="col-form-label fw-bold">Relationship:</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_telecom_company" readonly>
                                <label for="fr_telecom_company" class="col-form-label fw-bold">Name of Telecom:</label>
                            </div>
                            <hr>
                            <div class="row">
                                <span class="col-form-label col-sm-9 fw-bold">Are you or any of your relatives up to 2nd consaguinity(parents,spouse,children,siblings) engage in the sale of Globe, Smart,Sun cell cards?</span>
                                <div class="col-sm mt-2">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="working_in_cell_radio" id="working_in_cell_card_yes" value="Yes" disabled>
                                        <label class="form-check-label fw-bold" for="working_in_cell_card_yes">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="working_in_cell_radio" id="working_in_cell_card_no" value="No" disabled>
                                        <label class="form-check-label fw-bold" for="working_in_cell_card_no">No</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <span class="fs-14 fw-bold text-black-50">If yes, please state the following:</span>
                                <div class="col-sm-6">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_cellcards_name">
                                        <label for="fr_cellcards_name" class="col-form-label fw-bold">Name:</label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_cellcards_relationship">
                                        <label for="fr_cellcards_relationship" class="col-form-label fw-bold">Relationship:</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_cellcards_sold">
                                <label for="fr_cellcards_sold" class="col-form-label fw-bold">Type of Card/s being sold:</label>
                            </div>
                            <hr>
                            <div class="row">
                                <span class="col-form-label col-sm-9 fw-bold">Do you have any relatives employed by Banner Plasticard Inc.?</span>
                                <div class="col-sm mt-2">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="banner_relatives_radio" id="banner_relatives_yes" value="Yes" disabled>
                                        <label class="form-check-label fw-bold" for="banner_relatives_yes">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="banner_relatives_radio" id="banner_relatives_no" value="No" disabled>
                                        <label class="form-check-label fw-bold" for="banner_relatives_no">No</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <span class="fs-14 fw-bold text-black-50">If yes, please state name and relationship:</span>
                            </div>
                            <div class="banner_relative_container"></div>
                            <hr>
                            <div class="row">
                                <span class="col-form-label col-sm-6 fw-bold">Have you ever been employed by Banner Plasticard, Inc.?</span>
                                <div class="col-sm mt-2">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="banner_employed_radio" id="banner_employed_yes" value="Yes" disabled>
                                        <label class="form-check-label fw-bold" for="banner_employed_yes">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="banner_employed_radio" id="banner_employed_no" value="No" disabled>
                                        <label class="form-check-label fw-bold" for="banner_employed_no">No</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <span class="fs-14 fw-bold text-black-50">If yes, please state the following:</span>
                                <div class="col-sm-3">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_employeed_from" readonly>
                                        <label for="fr_employeed_from" class="col-form-label fw-bold">From:</label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_employeed_to" readonly>
                                        <label for="fr_employeed_to" class="col-form-label fw-bold">To:</label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_employeed_position" readonly>
                                        <label for="fr_employeed_position" class="col-form-label fw-bold">Position:</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_employeed_reason_seperation" readonly>
                                <label for="fr_employeed_reason_seperation" class="col-form-label fw-bold">Reason for seperation:</label>
                            </div>
                            <hr>
                            <div class="row">
                                <span class="col-form-label col-sm-6 fw-bold">Have you ever been charged and/or convicted of any crime/s?</span>
                                <div class="col-sm mt-2">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="convicted_crime_radio" id="convicted_crime_yes" value="Yes" disabled>
                                        <label class="form-check-label fw-bold" for="convicted_crime_yes">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="convicted_crime_radio" id="convicted_crime_no" value="No" disabled>
                                        <label class="form-check-label fw-bold" for="convicted_crime_no">No</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-floating mb-2">
                                <textarea id="fr_convicted_details" class="form-control border-0 border-bottom border-1 border-black fw-bold" style="resize:none;height: 80px" readonly></textarea>
                                <label for="fr_convicted_details" class="col-form-label fw-bold">If yes, please state the detail/s:</label>
                            </div>
                            <hr>
                            <div class="row">
                                <span class="col-form-label col-sm-7 fw-bold">Are you an active member of any private,civic or similar organization/s?</span>
                                <div class="col-sm mt-2">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="private_organization_radio" id="private_organization_yes" value="Yes" disabled>
                                        <label class="form-check-label fw-bold" for="private_organization_yes">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="private_organization_radio" id="private_organization_no" value="No" disabled>
                                        <label class="form-check-label fw-bold" for="private_organization_no">No</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <span class="fs-14 fw-bold text-black-50">If yes, please have them enumerated:</span>
                            </div>
                            <div class="private_organization_container"></div>
                            <hr>
                            <div class="row">
                                <span class="col-form-label col-sm-8 fw-bold">Do you have an existing financial obligation to any commercial banks and/or financial lending institution? (e.g SSS.Pag-IBIG etc.)</span>
                                <div class="col-sm mt-2">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="financial_loan_radio" id="financial_loan_yes" value="Yes" disabled>
                                        <label class="form-check-label fw-bold" for="financial_loan_yes">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="financial_loan_radio" id="financial_loan_no" value="No" disabled>
                                        <label class="form-check-label fw-bold" for="financial_loan_no">No</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <span class="fs-14 fw-bold text-black-50">If yes, please have them enumerated:</span>
                            </div>
                            <div class="financial_loan_container"></div>
                            <hr>
                            <div class="row">
                                <span class="fs-18 fw-bold text-black-50">List of previous residence/s within the last seven(7) years</span>
                            </div>
                            <div class="prev_address_container mb-3"></div>
                        </div>
                        <div class="gap-2 col-sm-12 ps-2 pe-2 mb-3 d-flex justify-content-center flex-column">
                            <button type="button" class="btn btn-success col-sm-12 text-light fw-bold rounded-pill btnUpdateDetails" onclick="updateDetails(this.value);">Update</button>
                            <button type="button" class="btn btn-danger col-sm-12 text-light fw-bold rounded-pill" data-bs-dismiss="modal" onclick="clearValues();">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- =============== Submit Form Modal =============== -->
            <div class="modal fade" id="referralDetailsModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-8">
                            <h4 class="modal-title text-uppercase fw-bold text-light">REFERRAL DETAILS</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control fw-bold" id="referred_by">
                                <div class="invalid-feedback"></div>
                                <label for="referred_by" class="col-form-label fw-bold">Referred By:</label>
                            </div>
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control fw-bold" id="referred_by_relationship">
                                <div class="invalid-feedback"></div>
                                <label for="referred_by_relationship" class="col-form-label fw-bold">Relationship:</label>
                            </div>
                        </div>
                        <div class="gap-2 col-sm-12 ps-2 pe-2 mb-3 d-flex justify-content-center flex-column">
                            <button type="button" class="btn btn-success col-sm-12 text-light fw-bold rounded-pill btnSaveReferral" onclick="saveReferral(this.value);">Save</button>
                            <button type="button" class="btn btn-danger col-sm-12 text-light fw-bold rounded-pill" data-bs-dismiss="modal" onclick="clearValues();">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- content section end -->
            <div class="position-absolute bottom-0 end-0 d-block d-md-none">
                <button class="btn btn-primary rounded-circle m-4 fs-4" onclick="menuNav();"><i class="fa-solid fa-bars"></i></button>
            </div>
        </div> <!-- Closing tag of content -->
        <div class="col-12 col-sm-12 col-md-3 p-3 menu-card d-none d-md-block">
            <div class="card card-8 border-0 shadow">
                <div class="d-flex justify-content-between justify-content-md-end mt-1 me-3 align-items-center">
                    <button class="btn btn-transparent text-white d-block d-md-none fs-2" onclick="menuPanelClose();"><i class="fa-solid fa-bars"></i></button>
                    <a href="../Landing_Page.php" class="text-white fs-2">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                </div>
                <div class="position-absolute app-title-wrapper">
                    <span class="fw-bold app-title text-nowrap">HUMAN RESOURCES</span>
                </div>
                <div class="card-body menu" style="height: 85vh; overflow-y:auto;">
                </div>
            </div>
        </div>
    </div>
</div>
<?php include './../includes/footer.php';
include './../helper/input_validation.php'; ?>
<script>
    $("#fr_sss_number").mask("99-9999999-9");
    $("#fr_tin_number").mask("999-999-999");
    $("#fr_philhealth_number").mask("99-999999999-9");
    $("#fr_pagibig_number").mask("9999-999999-99");

    loadPhsApplicantTable();

    function loadPhsApplicantTable() {
        var phs_applicant_table = $('#phs_applicant_table').DataTable({
            'autoWidth': false,
            'responsive': true,
            'deferRender': true,
            'processing': true,
            'serverSide': true,
            'ajax': {
                url: '../controller/human_resources_controller/hr_phs_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'load_personal_history_statement_applicant'
                }
            },
            'columnDefs': [{
                targets: 0,
                className: 'dt-body-middle-center',
                width: '20%'
            }, {
                targets: 1,
                className: 'dt-body-middle-left'
            }, {
                targets: 2,
                className: 'dt-body-middle-left',
                width: '20%',
            }, {
                targets: 3,
                className: 'dt-nowrap-center',
                width: '15%',
                orderable: false,
                render: function(data, type, row, meta) {
                    let btnAction = '';
                    if (data[1] == '-') {
                        btnAction += `<button type="button" class="btn btn-info col btnReferal" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Referal Details" onclick="referedBy('${data[0]}');"><i class="fa-solid fa-user-tag fa-flip"></i></button> `;
                    } else {
                        btnAction += `<button type="button" class="btn btn-secondary col" disabled><i class="fa-solid fa-user-tag"></i></button> `;
                    }
                    btnAction += `<button type="button" class="btn btn-primary col btnModify" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Modify Details" onclick="modifyDetails('${data[0]}');"><i class="fa-solid fa-file-pen fa-bounce"></i></button>
                    <button type="button" class="btn btn-dark col btnPreview" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Preview Details" onclick="previewDetails('${data[0]}');"><i class="fa-solid fa-file-pdf fa-beat"></i></button>
                    <button type="button" class="btn btn-danger col btnDelete" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Remove Record" onclick="removeRecord('${data[0]}');"><i class="fa-solid fa-trash-can fa-shake"></i></button>`;
                    return btnAction;
                }
            }]
        });
        phs_applicant_table.on('draw', function() {
            setTimeout(function() {
                $('[data-bs-toggle="tooltip"]').tooltip(); //* ======== Initialize tooltip ========
                $('[id^="tooltip"]').remove(); //* ======== Remove tooltip every table draw ========
                $('[data-bs-toggle="tooltip"]').on('click', function() { //* ======= Hide tooltip upon click =======
                    $(this).tooltip('hide');
                });
            }, 1000);
        });
        setInterval(function() {
            phs_applicant_table.ajax.reload(null, false);
        }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function referedBy(applicantid) {
        $('#referralDetailsModal').modal('show');
        $('.btnSaveReferral').val(applicantid);
    }

    function saveReferral(applicantid) {
        if (inputValidation('referred_by', 'referred_by_relationship')) {
            $.ajax({
                url: '../controller/human_resources_controller/hr_phs_contr.class.php',
                type: 'POST',
                data: {
                    action: 'save_referral',
                    applicantid: applicantid,
                    referred_by: $('#referred_by').val(),
                    referred_by_relationship: $('#referred_by_relationship').val()
                },
                success: result => {
                    Swal.fire({
                        position: 'top',
                        icon: 'success',
                        title: 'Save Successfully.',
                        text: '',
                        showConfirmButton: false,
                        timer: 1000
                    });
                    $('#phs_applicant_table').DataTable().ajax.reload(null, false);
                    $('#referralDetailsModal').modal('hide');
                    clearValues();
                }
            });
        }
    }

    function modifyDetails(applicantid) {
        $('#personalHistoryStatementModal').modal('show');
        $.ajax({
            url: '../controller/human_resources_controller/hr_phs_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'preview_phs_details',
                applicantid: applicantid
            },
            success: result => {
                $('.btnUpdateDetails').val(applicantid);
                $('.hr_applicant_container').html('<img src="data:image/jpeg;base64,' + result.applicant_image + '" class="hr_applicant_image" id="hr_applicant_image">');
                $('#fr_surname').val(result.applicant_sname);
                $('#fr_firstname').val(result.applicant_fname);
                $('#fr_extname').val(result.applicant_extname);
                $('#fr_middlename').val(result.applicant_mname);
                $('#fr_maiden_surname').val(result.applicant_maiden_sname);
                $('#fr_maiden_firstname').val(result.applicant_maiden_fname);
                $('#fr_maiden_middlename').val(result.applicant_maiden_mname);
                $('#fr_maiden_surname').val(result.applicant_maiden_sname);
                $('#fr_maiden_firstname').val(result.applicant_maiden_fname);
                $('#fr_maiden_middlename').val(result.applicant_maiden_mname);
                $('#fr_maiden_surname').val(result.applicant_maiden_sname);
                $('#fr_maiden_firstname').val(result.applicant_maiden_fname);
                $('#fr_maiden_middlename').val(result.applicant_maiden_mname);
                $('#fr_maiden_surname').val(result.applicant_maiden_sname);
                $('#fr_maiden_firstname').val(result.applicant_maiden_fname);
                $('#fr_maiden_middlename').val(result.applicant_maiden_mname);
                $('#fr_nickname_alias').val(result.applicant_alias);
                $('#fr_sex').val(result.applicant_gender);
                $('#fr_citizenship').val(result.applicant_citizenship);
                $('#fr_religion').val(result.applicant_religion);
                $('#fr_blood_type').val(result.applicant_bloodtype);
                $('#fr_height').val(result.applicant_height);
                $('#fr_weight').val(result.applicant_weight);
                $('#fr_eye_color').val(result.applicant_eye_color);
                $('#fr_hair_color').val(result.applicant_hair_color);
                $('#fr_date_of_birth').val(result.applicant_birth_date);
                $('#fr_age').val(result.applicant_age);
                $('#fr_birth_place').val(result.applicant_birth_place);
                $('#fr_contact_number').val(result.applicant_contact_no);
                $('#fr_email_address').val(result.applicant_email_address);
                $('#fr_civil_status').val(result.applicant_civil_status);
                $('#fr_present_unit_room_floor').val(result.applicant_present_unit);
                $('#fr_present_lot_block_house_bldg').val(result.applicant_present_lot);
                $('#fr_present_street').val(result.applicant_present_street);
                $('#fr_present_subdivision_village').val(result.applicant_present_sub_village);
                $('#fr_present_region').val(result.applicant_present_region);
                $('#fr_present_province').val(result.applicant_present_province);
                $('#fr_present_city_municipality').val(result.applicant_present_city);
                $('#fr_present_barangay').val(result.applicant_present_barangay);
                $('#fr_present_zip_code').val(result.applicant_present_zipcode);
                $('#fr_permanent_unit_room_floor').val(result.applicant_permanent_unit);
                $('#fr_permanent_lot_block_house_bldg').val(result.applicant_permanent_lot);
                $('#fr_permanent_street').val(result.applicant_permanent_street);
                $('#fr_permanent_subdivision_village').val(result.applicant_permanent_sub_village);
                $('#fr_permanent_region').val(result.applicant_permanent_region);
                $('#fr_permanent_province').val(result.applicant_permanent_province);
                $('#fr_permanent_city_municipality').val(result.applicant_permanent_city);
                $('#fr_permanent_barangay').val(result.applicant_permanent_barangay);
                $('#fr_permanent_zip_code').val(result.applicant_permanent_zipcode);
                $('#fr_sss_number').val(result.applicant_sss_no);
                $('#fr_tin_number').val(result.applicant_philhealth_no);
                $('#fr_philhealth_number').val(result.applicant_tin_no);
                $('#fr_pagibig_number').val(result.applicant_pagibig_no);
                $('#fr_spouse_surname').val(result.applicant_spouse_fname);
                $('#fr_spouse_firstname').val(result.applicant_spouse_mname);
                $('#fr_spouse_middlename').val(result.applicant_spouse_sname);
                $('#fr_spouse_name_extension').val(result.applicant_spouse_extname);
                $('#fr_spouse_occupation').val(result.applicant_spouse_occupation);
                $('#fr_spouse_employer').val(result.applicant_spouse_employer);
                $('#fr_father_surname').val(result.applicant_father_fname);
                $('#fr_father_firstname').val(result.applicant_father_mname);
                $('#fr_father_middlename').val(result.applicant_father_sname);
                $('#fr_father_name_extension').val(result.applicant_father_extname);
                $('#fr_father_occupation').val(result.applicant_father_occupation);
                $('#fr_father_employer').val(result.applicant_father_employer);
                $('#fr_mother_surname').val(result.applicant_mother_fname);
                $('#fr_mother_firstname').val(result.applicant_mother_mname);
                $('#fr_mother_middlename').val(result.applicant_mother_sname);
                $('#fr_mother_occupation').val(result.applicant_mother_occupation);
                $('#fr_mother_employer').val(result.applicant_mother_employer);
                $('#fr_tertiary_school').val(result.applicant_tertiary_school);
                $('#fr_tertiary_address').val(result.applicant_tertiary_address);
                $('#fr_tertiary_from').val(result.applicant_tertiary_date_from);
                $('#fr_tertiary_to').val(result.applicant_tertiary_date_to);
                $('#fr_senior_high_school').val(result.applicant_sr_high_school);
                $('#fr_senior_high_address').val(result.applicant_sr_high_address);
                $('#fr_senior_high_from').val(result.applicant_sr_high_date_from);
                $('#fr_senior_high_to').val(result.applicant_sr_high_date_to);
                $('#fr_junior_high_school').val(result.applicant_jr_high_school);
                $('#fr_junior_high_address').val(result.applicant_jr_high_address);
                $('#fr_junior_high_from').val(result.applicant_jr_high_date_from);
                $('#fr_junior_high_to').val(result.applicant_jr_high_date_to);
                $('#fr_elementary_school').val(result.applicant_elem_school);
                $('#fr_elementary_address').val(result.applicant_elem_address);
                $('#fr_elementary_from').val(result.applicant_elem_date_from);
                $('#fr_elementary_to').val(result.applicant_elem_date_to);
                $('#fr_special_skills').val(result.applicant_special_skills);
                $('#fr_award_honor_license').val(result.applicant_awards_received);
                $('#fr_emergency_name').val(result.applicant_emergency_name);
                $('#fr_emergency_relationship').val(result.applicant_emergency_relationship);
                $('#fr_emergency_address').val(result.applicant_emergency_address);
                $('#fr_emergency_tell_cell_number').val(result.applicant_emergency_cell_no);
                frRadioButtons(result.applicant_relative_telecom, 'working_in_telecom_yes', 'working_in_telecom_no');
                $('#fr_telecom_name').val(result.applicant_relative_name);
                $('#fr_telecom_relationship').val(result.applicant_relative_relationship);
                $('#fr_telecom_company').val(result.applicant_relative_telecompany);
                frRadioButtons(result.applicant_relative_sale_cell_cards, 'working_in_cell_card_yes', 'working_in_cell_card_no');
                $('#fr_cellcards_name').val(result.applicant_relative_sale_name);
                $('#fr_cellcards_relationship').val(result.applicant_relative_sale_relationship);
                $('#fr_cellcards_sold').val(result.applicant_relative_sale_card_type);
                frRadioButtons(result.applicant_relative_banner, 'banner_relatives_yes', 'banner_relatives_no');
                frRadioButtons(result.applicant_employ_banner, 'banner_employed_yes', 'banner_employed_no');
                $('#fr_employeed_from').val(result.applicant_employ_banner_date_from);
                $('#fr_employeed_to').val(result.applicant_employ_banner_date_to);
                $('#fr_employeed_position').val(result.applicant_employ_banner_position);
                $('#fr_employeed_reason_seperation').val(result.applicant_employ_banner_seperation);
                frRadioButtons(result.applicant_convicted_crime, 'convicted_crime_yes', 'convicted_crime_no');
                $('#fr_convicted_details').val(result.applicant_convicted_details);
                frRadioButtons(result.applicant_active_organizations, 'private_organization_yes', 'private_organization_no');
                frRadioButtons(result.applicant_existing_financial, 'financial_loan_yes', 'financial_loan_no');
            }
        });

        $.ajax({
            url: '../controller/human_resources_controller/hr_phs_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'preview_phs_spouse_children',
                applicantid: applicantid
            },
            success: result => {
                let html_children = '';
                let count = 0;
                if (result.length > 0) {
                    $.each(result, (key, value) => {
                        count++;
                        html_children += `<div class="row mt-2">
                        <div class="col-sm-4">
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold fr_spouse_childname" name="fr_spouse_childname[]" id="fr_spouse_childname` + count + `" value="` + value[0] + `" readonly>
                                <label for="fr_spouse_childname` + count + `" class="col-form-label fw-bold">Name:</label>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold fr_spouse_childbirth" name="fr_spouse_childbirth[]" id="fr_spouse_childbirth` + count + `" value="` + value[1] + `" readonly>
                                <label for="fr_spouse_childbirth` + count + `" class="col-form-label fw-bold">Date of Birth:</label>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold fr_spouse_childaddress" name="fr_spouse_childaddress[]" id="fr_spouse_childaddress` + count + `" value="` + value[2] + `" readonly>
                                <label for="fr_spouse_childaddress` + count + `" class="col-form-label fw-bold">Address:</label>
                            </div>
                        </div>
                    </div>`;
                    });
                } else {
                    html_children += `<div class="row mt-2">
                        <div class="col-sm-4">
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold fr_spouse_childname" name="fr_spouse_childname[]" id="fr_spouse_childname" value="N/A" readonly>
                                <label for="fr_spouse_childname" class="col-form-label fw-bold">Name:</label>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold fr_spouse_childbirth" name="fr_spouse_childbirth[]" id="fr_spouse_childbirth" value="N/A" readonly>
                                <label for="fr_spouse_childbirth" class="col-form-label fw-bold">Date of Birth:</label>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold fr_spouse_childaddress" name="fr_spouse_childaddress[]" id="fr_spouse_childaddress" value="N/A" readonly>
                                <label for="fr_spouse_childaddress" class="col-form-label fw-bold">Address:</label>
                            </div>
                        </div>
                    </div>`;
                }
                $('.spouse_child_container').html(html_children);
            }
        });

        $.ajax({
            url: '../controller/human_resources_controller/hr_phs_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'preview_phs_siblings',
                applicantid: applicantid
            },
            success: result => {
                let html_sibling = '';
                let count = 0;
                if (result.length > 0) {
                    $.each(result, (key, value) => {
                        count++;
                        html_sibling += `<div class="row mt-2">
                        <div class="col-sm-4">
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold fr_broSis_name" name="fr_broSis_name[]" id="fr_broSis_name` + count + `" value="` + value[0] + `" readonly>
                                <label for="fr_broSis_name` + count + `" class="col-form-label fw-bold">Name:</label>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold fr_broSis_occupation" name="fr_broSis_occupation[]" id="fr_broSis_occupation` + count + `" value="` + value[1] + `" readonly>
                                <label for="fr_broSis_occupation` + count + `" class="col-form-label fw-bold">Occupation:</label>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold fr_broSis_employer" name="fr_broSis_employer[]" id="fr_broSis_employer` + count + `" value="` + value[2] + `" readonly>
                                <label for="fr_broSis_employer` + count + `" class="col-form-label fw-bold">Employer:</label>
                            </div>
                        </div>
                    </div>`;
                    });
                } else {
                    html_sibling += `<div class="row mt-2">
                        <div class="col-sm-4">
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold fr_broSis_name" name="fr_broSis_name[]" id="fr_broSis_name" value="N/A" readonly>
                                <label for="fr_broSis_name" class="col-form-label fw-bold">Name:</label>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold fr_broSis_occupation" name="fr_broSis_occupation[]" id="fr_broSis_occupation" value="N/A" readonly>
                                <label for="fr_broSis_occupation" class="col-form-label fw-bold">Occupation:</label>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold fr_broSis_employer" name="fr_broSis_employer[]" id="fr_broSis_employer" value="N/A" readonly>
                                <label for="fr_broSis_employer" class="col-form-label fw-bold">Employer:</label>
                            </div>
                        </div>
                    </div>`;
                }
                $('.brother_sister_container').html(html_sibling);
            }
        });

        $.ajax({
            url: '../controller/human_resources_controller/hr_phs_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'preview_phs_extraculicular',
                applicantid: applicantid
            },
            success: result => {
                let html_extraculicular = '';
                let count = 0;
                if (result.length > 0) {
                    $.each(result, (key, value) => {
                        count++;
                        html_extraculicular += `<div class="row mt-2">
                        <div class="col-sm-4">
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold fr_seminar_title" name="fr_seminar_title[]" id="fr_seminar_title` + count + `" value="` + value[0] + `" readonly>
                                <label for="fr_seminar_title` + count + `" class="col-form-label fw-bold">Title:</label>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold fr_seminar_place" name="fr_seminar_place[]" id="fr_seminar_place` + count + `" value="` + value[1] + `" readonly>
                                <label for="fr_seminar_place` + count + `" class="col-form-label fw-bold">Place:</label>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold fr_seminar_date" name="fr_seminar_date[]" id="fr_seminar_date` + count + `" value="` + value[2] + `" readonly>
                                <label for="fr_seminar_date` + count + `" class="col-form-label fw-bold">Date:</label>
                            </div>
                        </div>
                    </div>`;
                    });
                } else {
                    html_extraculicular += `<div class="row mt-2">
                        <div class="col-sm-4">
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold fr_seminar_title" name="fr_seminar_title[]" id="fr_seminar_title" value="N/A" readonly>
                                <label for="fr_seminar_title" class="col-form-label fw-bold">Title:</label>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold fr_seminar_place" name="fr_seminar_place[]" id="fr_seminar_place" value="N/A" readonly>
                                <label for="fr_seminar_place" class="col-form-label fw-bold">Place:</label>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold fr_seminar_date" name="fr_seminar_date[]" id="fr_seminar_date" value="N/A" readonly>
                                <label for="fr_seminar_date" class="col-form-label fw-bold">Date:</label>
                            </div>
                        </div>
                    </div>`;
                }
                $('.seminar_training_container').html(html_extraculicular);
            }
        });

        $.ajax({
            url: '../controller/human_resources_controller/hr_phs_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'preview_phs_employment',
                applicantid: applicantid
            },
            success: result => {
                let html_employment = '';
                let count = 0;
                if (result.length > 0) {
                    $.each(result, (key, value) => {
                        count++;
                        html_employment += `
                        <div class="form-floating mb-2">
                            <textarea class="form-control fw-bold border-0 border-bottom border-1 border-black fr_employment_gaps" name="fr_employment_gaps[]" id="fr_employment_gaps` + count + `" style="resize:none;height: 80px" readonly>` + value[0] + `</textarea>
                            <label for="fr_employment_gaps` + count + `" class="col-form-label fw-bold">Explanation on employment gap/s:</label>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-floating mb-2">
                                    <input type="text" name="fr_company_address[]" class="form-control fw-bold border-0 border-bottom border-1 border-black fr_company_address" id="fr_company_address` + count + `" value="` + value[1] + `" readonly>
                                    <label for="fr_company_address` + count + `" class="col-form-label fw-bold">Company Address:</label>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-floating mb-2">
                                    <input type="text" name="fr_position_held[]" class="form-control fw-bold border-0 border-bottom border-1 border-black fr_position_held" id="fr_position_held` + count + `" value="` + value[2] + `" readonly>
                                    <label for="fr_position_held` + count + `" class="col-form-label fw-bold">Position Held:</label>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-floating mb-2">
                                    <input type="text" name="fr_employment_from[]" class="form-control fw-bold border-0 border-bottom border-1 border-black fr_employment_from" id="fr_employment_from` + count + `" value="` + moment(value[3]).format('MMMM YYYY') + `" readonly>
                                    <label for="fr_employment_from` + count + `" class="col-form-label fw-bold">From:</label>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-floating mb-2">
                                    <input type="text" name="fr_employment_to[]" class="form-control fw-bold border-0 border-bottom border-1 border-black fr_employment_to" id="fr_employment_to` + count + `" value="` + moment(value[4]).format('MMMM YYYY') + `" readonly>
                                    <label for="fr_employment_to` + count + `" class="col-form-label fw-bold">To:</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-floating mb-2">
                            <textarea class="form-control fw-bold border-0 border-bottom border-1 border-black fr_reason_for_leaving" name="fr_reason_for_leaving[]" id="fr_reason_for_leaving` + count + `" style="resize:none;height: 80px" readonly>` + value[5] + `</textarea>
                            <label for="fr_reason_for_leaving` + count + `" class="col-form-label fw-bold">Reason for Leaving:</label>
                        </div><hr>`;
                    });
                } else {
                    html_employment += `
                    <div class="form-floating mb-2">
                        <textarea class="form-control fw-bold border-0 border-bottom border-1 border-black fr_employment_gaps" name="fr_employment_gaps[]" id="fr_employment_gaps" style="resize:none;height: 80px" readonly>N/A</textarea>
                        <label for="fr_employment_gaps" class="col-form-label fw-bold">Explanation on employment gap/s:</label>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-floating mb-2">
                                <input type="text" name="fr_company_address[]" class="form-control fw-bold border-0 border-bottom border-1 border-black fr_company_address" id="fr_company_address" value="N/A" readonly>
                                <label for="fr_company_address" class="col-form-label fw-bold">Company Address:</label>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-floating mb-2">
                                <input type="text" name="fr_position_held[]" class="form-control fw-bold border-0 border-bottom border-1 border-black fr_position_held" id="fr_position_held" value="N/A" readonly>
                                <label for="fr_position_held" class="col-form-label fw-bold">Position Held:</label>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-floating mb-2">
                                <input type="text" name="fr_employment_from[]" class="form-control fw-bold border-0 border-bottom border-1 border-black fr_employment_from" id="fr_employment_from" value="N/A" readonly>
                                <label for="fr_employment_from" class="col-form-label fw-bold">From:</label>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-floating mb-2">
                                <input type="text" name="fr_employment_to[]" class="form-control fw-bold border-0 border-bottom border-1 border-black fr_employment_to" id="fr_employment_to" value="N/A" readonly>
                                <label for="fr_employment_to" class="col-form-label fw-bold">To:</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-floating mb-2">
                        <textarea class="form-control fw-bold border-0 border-bottom border-1 border-black fr_reason_for_leaving" name="fr_reason_for_leaving[]" id="fr_reason_for_leaving" style="resize:none;height: 80px" readonly>N/A</textarea>
                        <label for="fr_reason_for_leaving" class="col-form-label fw-bold">Reason for Leaving:</label>
                    </div> <hr>`;
                }
                $('.employment_history_container').html(html_employment);
            }
        });

        $.ajax({
            url: '../controller/human_resources_controller/hr_phs_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'preview_phs_char_ref',
                applicantid: applicantid
            },
            success: result => {
                let html_char_ref = '';
                let count = 0;
                if (result.length > 0) {
                    $.each(result, (key, value) => {
                        count++;
                        html_char_ref += `<div class="row mt-2">
                        <div class="col-sm-3">
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold fr_char_ref_name" name="fr_char_ref_name[]" id="fr_char_ref_name` + count + `" value="` + value[0] + `" readonly>
                                <label for="fr_char_ref_name` + count + `" class="col-form-label fw-bold">Name:</label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold fr_char_ref_relation" name="fr_char_ref_relation[]" id="fr_char_ref_relation` + count + `" value="` + value[1] + `" readonly>
                                <label for="fr_char_ref_relation` + count + `" class="col-form-label fw-bold">Relationship:</label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold fr_char_ref_occupation" name="fr_char_ref_occupation[]" id="fr_char_ref_occupation` + count + `" value="` + value[2] + `" readonly>
                                <label for="fr_char_ref_occupation` + count + `" class="col-form-label fw-bold">Occupation:</label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold fr_char_ref_contact" name="fr_char_ref_contact[]" id="fr_char_ref_contact` + count + `" value="` + value[3] + `" readonly>
                                <label for="fr_char_ref_contact` + count + `" class="col-form-label fw-bold">Contact No.:</label>
                            </div>
                        </div>
                    </div>`;
                    });
                } else {
                    html_char_ref += `<div class="row mt-2">
                        <div class="col-sm-3">
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold fr_char_ref_name" name="fr_char_ref_name[]" id="fr_char_ref_name" value="N/A" readonly>
                                <label for="fr_char_ref_name" class="col-form-label fw-bold">Name:</label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold fr_char_ref_relation" name="fr_char_ref_relation[]" id="fr_char_ref_relation" value="N/A" readonly>
                                <label for="fr_char_ref_relation" class="col-form-label fw-bold">Relationship:</label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold fr_char_ref_occupation" name="fr_char_ref_occupation[]" id="fr_char_ref_occupation" value="N/A" readonly>
                                <label for="fr_char_ref_occupation" class="col-form-label fw-bold">Occupation:</label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold fr_char_ref_contact" name="fr_char_ref_contact[]" id="fr_char_ref_contact" value="N/A" readonly>
                                <label for="fr_char_ref_contact" class="col-form-label fw-bold">Contact No.:</label>
                            </div>
                        </div>
                    </div>`;
                }
                $('.char_reference_container').html(html_char_ref);
            }
        });

        $.ajax({
            url: '../controller/human_resources_controller/hr_phs_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'preview_phs_banner_relative',
                applicantid: applicantid
            },
            success: result => {
                let html_banner_relative = '';
                let count = 0;
                if (result.length > 0) {
                    $.each(result, (key, value) => {
                        count++;
                        html_banner_relative += `<div class="row">
                        <div class="col-sm-6">
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold fr_banner_relative_name" name="fr_banner_relative_name[]" id="fr_banner_relative_name` + count + `" value="` + value[0] + `" readonly>
                                <label for="fr_banner_relative_name` + count + `" class="col-form-label fw-bold">Title:</label>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold fr_banner_relative_relationship" name="fr_banner_relative_relationship[]" id="fr_banner_relative_relationship` + count + `" value="` + value[1] + `" readonly>
                                <label for="fr_banner_relative_relationship` + count + `" class="col-form-label fw-bold">Place:</label>
                            </div>
                        </div>
                    </div>`;
                    });
                } else {
                    html_banner_relative += `<div class="row">
                        <div class="col-sm-6">
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_banner_relative_name" value="N/A">
                                <label for="fr_banner_relative_name" class="col-form-label fw-bold">Name:</label>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold" id="fr_banner_relative_relationship" value="N/A">
                                <label for="fr_banner_relative_relationship" class="col-form-label fw-bold">Relationship:</label>
                            </div>
                        </div>
                    </div>`;
                }
                $('.banner_relative_container').html(html_banner_relative);
            }
        });

        $.ajax({
            url: '../controller/human_resources_controller/hr_phs_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'preview_phs_organization',
                applicantid: applicantid
            },
            success: result => {
                let html_organization = '';
                let count = 0;
                if (result.length > 0) {
                    $.each(result, (key, value) => {
                        count++;
                        html_organization += `<div class="row mt-2">
                        <div class="col-sm-4">
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold fr_civic_org_name" name="fr_civic_org_name[]" id="fr_civic_org_name` + count + `" value="` + value[0] + `" readonly>
                                <label for="fr_civic_org_name` + count + `" class="col-form-label fw-bold">Name of Organization:</label>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold fr_civic_org_address" name="fr_civic_org_address[]" id="fr_civic_org_address` + count + `" value="` + value[1] + `" readonly>
                                <label for="fr_civic_org_address` + count + `" class="col-form-label fw-bold">Address:</label>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold fr_civic_org_number" name="fr_civic_org_number[]" id="fr_civic_org_number` + count + `" value="` + value[2] + `" readonly>
                                <label for="fr_civic_org_number` + count + `" class="col-form-label fw-bold">Contact No.:</label>
                            </div>
                        </div>
                    </div>`;
                    });
                } else {
                    html_organization += `<div class="row mt-2">
                        <div class="col-sm-4">
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold fr_civic_org_name" name="fr_civic_org_name[]" id="fr_civic_org_name" value="N/A" readonly>
                                <label for="fr_civic_org_name" class="col-form-label fw-bold">Name of Organization:</label>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold fr_civic_org_address" name="fr_civic_org_address[]" id="fr_civic_org_address" value="N/A" readonly>
                                <label for="fr_civic_org_address" class="col-form-label fw-bold">Address:</label>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold fr_civic_org_number" name="fr_civic_org_number[]" id="fr_civic_org_number" value="N/A" readonly>
                                <label for="fr_civic_org_number" class="col-form-label fw-bold">Contact No.:</label>
                            </div>
                        </div>
                    </div>`;
                }
                $('.private_organization_container').html(html_organization);
            }
        });

        $.ajax({
            url: '../controller/human_resources_controller/hr_phs_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'preview_phs_financial',
                applicantid: applicantid
            },
            success: result => {
                let html_financial_loan = '';
                let count = 0;
                if (result.length > 0) {
                    $.each(result, (key, value) => {
                        count++;
                        html_financial_loan += `<div class="row mt-2">
                        <div class="col-sm-6">
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold fr_bank_name" name="fr_bank_name[]" id="fr_bank_name` + count + `" value="` + value[0] + `" readonly>
                                <label for="fr_bank_name` + count + `" class="col-form-label fw-bold">Name of the bank and/or financial institution:</label>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold fr_loan_type" name="fr_loan_type[]" id="fr_loan_type` + count + `" value="` + value[1] + `" readonly>
                                <label for="fr_loan_type` + count + `" class="col-form-label fw-bold">Type of Loan:</label>
                            </div>
                        </div>
                    </div>`;
                    });
                } else {
                    html_financial_loan += `<div class="row mt-2">
                        <div class="col-sm-6">
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold fr_bank_name" name="fr_bank_name[]" id="fr_bank_name" value="N/A" readonly>
                                <label for="fr_bank_name" class="col-form-label fw-bold">Name of the bank and/or financial institution:</label>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold fr_loan_type" name="fr_loan_type[]" id="fr_loan_type" value="N/A" readonly>
                                <label for="fr_loan_type" class="col-form-label fw-bold">Type of Loan:</label>
                            </div>
                        </div>
                    </div>`;
                }
                $('.financial_loan_container').html(html_financial_loan);
            }
        });

        $.ajax({
            url: '../controller/human_resources_controller/hr_phs_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'preview_phs_prev_residences',
                applicantid: applicantid
            },
            success: result => {
                let html_previous_address = '';
                let count = 0;
                if (result.length > 0) {
                    $.each(result, (key, value) => {
                        count++;
                        html_previous_address += `<div class="row mt-2">
                        <div class="col-sm-3">
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold fr_previous_address" name="fr_previous_address[]" id="fr_previous_address` + count + `" value="` + value[0] + `" readonly>
                                <label for="fr_previous_address` + count + `" class="col-form-label fw-bold">Address:</label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold fr_previous_from" name="fr_previous_from[]" id="fr_previous_from` + count + `" value="` + value[1] + `" readonly>
                                <label for="fr_previous_from` + count + `" class="col-form-label fw-bold">Date From:</label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold fr_previous_to" name="fr_previous_to[]" id="fr_previous_to` + count + `" value="` + value[2] + `" readonly>
                                <label for="fr_previous_to` + count + `" class="col-form-label fw-bold">Date To:</label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold fr_previous_own_rent" name="fr_previous_own_rent[]" id="fr_previous_own_rent` + count + `" value="` + value[3] + `" readonly>
                                <label for="fr_previous_own_rent` + count + `" class="col-form-label fw-bold">Own/Rented:</label>
                            </div>
                        </div>
                    </div>`;
                    });
                } else {
                    html_previous_address += `<div class="row mt-2">
                        <div class="col-sm-3">
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold fr_previous_address" name="fr_previous_address[]" id="fr_previous_address" value="N/A" readonly>
                                <label for="fr_previous_address" class="col-form-label fw-bold">Address:</label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold fr_previous_from" name="fr_previous_from[]" id="fr_previous_from" value="N/A" readonly>
                                <label for="fr_previous_from" class="col-form-label fw-bold">Date From:</label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold fr_previous_to" name="fr_previous_to[]" id="fr_previous_to" value="N/A" readonly>
                                <label for="fr_previous_to" class="col-form-label fw-bold">Date To:</label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control border-0 border-bottom border-1 border-black fw-bold fr_previous_own_rent" name="fr_previous_own_rent[]" id="fr_previous_own_rent" value="N/A" readonly>
                                <label for="fr_previous_own_rent" class="col-form-label fw-bold">Own/Rented:</label>
                            </div>
                        </div>
                    </div>`;
                }
                $('.prev_address_container').html(html_previous_address);
            }
        });
    }

    function updateDetails(applicantid) {
        $.ajax({
            url: '../controller/human_resources_controller/hr_phs_contr.class.php',
            type: 'POST',
            data: {
                action: 'update_phs_details',
                applicantid: applicantid,
                bloodtype: $('#fr_blood_type').val(),
                sss_number: $('#fr_sss_number').val(),
                tin_number: $('#fr_tin_number').val(),
                philhealth_number: $('#fr_philhealth_number').val(),
                pagibig_number: $('#fr_pagibig_number').val()
            },
            success: result => {
                $('#personalHistoryStatementModal').modal('hide');
                Swal.fire({
                    position: 'top',
                    icon: 'success',
                    title: 'Record Successfully Updated.',
                    text: '',
                    showConfirmButton: false,
                    timer: 1000
                });
            }
        });
    }

    function previewDetails(applicantid) {
        var strLink = `hr_personal_history_statement_pdf.php?ad=${applicantid}`;
        window.open(strLink, '_blank');
    }

    function removeRecord(applicantid) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to modify this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, remove it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '../controller/human_resources_controller/hr_phs_contr.class.php',
                    type: 'POST',
                    data: {
                        action: 'remove_phs_record',
                        applicantid: applicantid
                    },
                    success: result => {
                        Swal.fire(
                            'Remove!',
                            'Record Successfully remove.',
                            'success'
                        );
                        $('#phs_applicant_table').DataTable().ajax.reload(null, false);
                    }
                });
            }
        });
    }

    function frRadioButtons(inValue, inRadioYes, inRadioNo) {
        if (inValue == true) {
            $('#' + inRadioYes).prop('checked', true);
        } else {
            $('#' + inRadioNo).prop('checked', true);
        }
    }

    function clearAttributes() {
        $('input').removeClass('is-valid is-invalid');
        $('select').removeClass('is-valid is-invalid');
        $('textarea').removeClass('is-valid is-invalid');
    }

    function clearValues() {
        $('input').val('');
        $('textarea').val('');
        clearAttributes();
    }
</script>
</body>
<html>