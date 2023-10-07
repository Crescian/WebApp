<?php
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    include_once '../../model/perso_monitoring_model/perso_announcement_model.class.php';
    $persoAnnouncement = new PersoAnnouncement();
    $action = trim($_POST['action']);

    switch ($action) {
        case 'load_announcement_list_table':
            $searchValue = $_POST['search']['value'];
            $announce_by_empno = trim($_POST['announce_by_empno']);
            echo $persoAnnouncement->loadAnnouncementListTable($php_fetch_perso_api, $searchValue, $announce_by_empno);
            break;

        case 'load_announcement_history_list_table':
            $searchValue = $_POST['search']['value'];
            echo $persoAnnouncement->loadAnnouncementHistoryListTable($php_fetch_perso_api, $php_fetch_bannerweb_api, $searchValue);
            break;

        case 'load_section_list':
            echo $persoAnnouncement->loadSectionList($php_fetch_perso_api, $php_fetch_bannerweb_api);
            break;

        case 'save_announcement_header':
            $announce_recipients = trim($_POST['announce_recipients']);
            $announce_header = trim($_POST['announce_header']);
            $announce_body = trim($_POST['announce_body']);
            $announce_by = trim($_POST['announce_by']);
            $announce_by_empno = trim($_POST['announce_by_empno']);
            $announce_by_job_title = trim($_POST['announce_by_job_title']);
            echo $persoAnnouncement->saveAnnouncementHeader($php_fetch_bannerweb_api, $php_insert_perso_api, $php_fetch_perso_api,  $announce_recipients, $announce_header, $announce_body, $announce_by, $announce_by_empno, $announce_by_job_title);
            break;

        case 'save_announcement_recipients':
            $announcementid = trim($_POST['announcementid']);
            $strRecipients = trim($_POST['strRecipients']);
            $persoAnnouncement->saveAnnouncementDetail($php_fetch_perso_api, $php_fetch_bannerweb_api, $php_insert_perso_api, $announcementid,  $strRecipients);
            break;

        case 'load_announcement_list':
            $announce_to = trim($_POST['announce_to']);
            echo $persoAnnouncement->loadAnnouncementList($php_fetch_perso_api, $php_fetch_bannerweb_api, $announce_to);
            break;

        case 'acknowledge_announcement':
            $announce_recieverid = trim($_POST['announce_recieverid']);
            $persoAnnouncement->updateAnnouncement($php_update_perso_api, $announce_recieverid);
            break;

        case 'load_announcement_info':
            $announcementid = trim($_POST['announcementid']);
            echo $persoAnnouncement->loadAnnouncementInfo($php_fetch_perso_api, $announcementid);
            break;

        case 'update_announcement':
            $announce_header = trim($_POST['announce_header']);
            $announce_details = trim($_POST['announce_details']);
            $announcementid = trim($_POST['announcementid']);
            $persoAnnouncement->updateAnnouncementInfo($php_update_perso_api, $announce_header, $announce_details, $announcementid);
            break;

        case 'delete_announcement':
            $announcementid = trim($_POST['announcementid']);
            $persoAnnouncement->deleteAnnouncement($php_update_perso_api, $announcementid);
            break;
    }
}
