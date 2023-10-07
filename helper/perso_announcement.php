<!-- =============== Announcement List Modal =============== -->
<div class="modal fade" id="announcementListModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header card-4 d-flex justify-content-center">
                <h4 class="modal-title text-uppercase fw-bold text-light">ANNOUNCEMENT</h4>
            </div>
            <div class="modal-body">
                <div class="accordion" id="accordionAnnouncement"></div>
            </div>

            <div class="gap-2 col-sm-12 ps-2 pe-2 mb-3 d-flex justify-content-center flex-column">
                <button type="button" class="btn btn-danger col-sm-12 text-light fw-bold rounded-pill" data-bs-dismiss="modal" onclick="reCallAnnouncement();">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    var announce_to_empno = '<?php echo $_SESSION['empno']; ?>';
    $(document).ready(function() {
        loadAnnouncement(announce_to_empno);
    });

    function loadAnnouncement(empno) {
        $.ajax({
            url: '../controller/perso_monitoring_controller/perso_announcement_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_announcement_list',
                announce_to: empno
            },
            success: result => {
                if (result != 'no record') {
                    $('#announcementListModal').modal('show');
                    var announce_html = '';
                    let i = 0;
                    $.each(result, (key, value) => {
                        i++;
                        announce_html += `
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed custom_accordion_button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse` + i + `" aria-expanded="false" aria-controls="collapse` + i + `">
                                    <div class="announce_read">
                                        <i class="fa-solid fa-circle-check ` + value.announce_check + `" id="acknowledge_check` + i + `"></i>
                                    </div>
                                    <img src="data:image/jpeg;base64,` + value.announce_by_pic + `" class="announce_by_pic" alt="Profile">
                                    <div class="button_header_details">
                                        <div class="announce_header">` + value.announce_header + `</div>
                                        <div class="announce_by_and_date">
                                            <span class="announce_by">` + value.announce_by + `</span>
                                            <span class="announce_header_date">Published : ` + value.announce_date_entry + `</span>
                                        </div>
                                    </div>
                                </button>
                            </h2>
                            <div id="collapse` + i + `" class="accordion-collapse collapse" data-bs-parent="#accordionAnnouncement">
                                <div class="accordion-body">
                                    <p>` + value.announce_body + `</p>
                                    <div class="d-flex justify-content-end ` + value.announce_btn + `" id="button_section` + i + `">
                                        <button type="button" class="btn btn-primary col-sm-2" onclick="announceAcknowledge('` + value.announce_recieverid + `','` + i + `');"><i class="fa-solid fa-thumbs-up fa-bounce p-r-8" style="--fa-animation-duration: 2.5s;"></i>Got It!</button>
                                    </div>
                                </div>
                            </div>
                        </div>`;
                    });
                    $('#accordionAnnouncement').html(announce_html);
                }
            }
        });
    }

    function reCallAnnouncement() {
        setTimeout(function() {
            loadAnnouncement(announce_to_empno);
        }, 120000);
    }

    function announceAcknowledge(announce_recieverid, count) {
        $('#button_section' + count).addClass('d-none');
        $('#acknowledge_check' + count).removeClass('d-none').addClass('icon_check');
        $.ajax({
            url: '../controller/perso_monitoring_controller/perso_announcement_contr.class.php',
            type: 'POST',
            data: {
                action: 'acknowledge_announcement',
                announce_recieverid: announce_recieverid
            }
        });
    }
</script>