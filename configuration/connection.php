<?php
$php_fetch_bannerdata_api = "http://192.107.29.92:7001/api/query";
$php_update_bannerdata_api = "http://192.107.29.92:7001/api/update";
$php_insert_bannerdata_api = "http://192.107.29.92:7001/api/insert";

$php_fetch_whpo_api = "http://192.107.29.92:7002/api/query";
$php_update_whpo_api = "http://192.107.29.92:7002/api/update";
$php_insert_whpo_api = "http://192.107.29.92:7002/api/insert";

$php_fetch_canteen_api = "http://192.107.29.92:7003/api/query";
$php_update_canteen_api = "http://192.107.29.92:7003/api/update";
$php_insert_canteen_api = "http://192.107.29.92:7003/api/insert";

$php_fetch_cms_api = "http://192.107.29.92:7004/api/query";
$php_update_cms_api = "http://192.107.29.92:7004/api/update";
$php_insert_cms_api = "http://192.107.29.92:7004/api/insert";

$php_fetch_cms_data_api = "http://192.107.29.92:7005/api/query";
$php_update_cms_data_api = "http://192.107.29.92:7005/api/update";
$php_insert_cms_data_api = "http://192.107.29.92:7005/api/insert";

$php_fetch_itasset_api = "http://192.107.29.92:7006/api/query";
$php_update_itasset_api = "http://192.107.29.92:7006/api/update";
$php_insert_itasset_api = "http://192.107.29.92:7006/api/insert";

$php_fetch_bannerweb_api = "http://192.107.29.92:7007/api/query";
$php_update_bannerweb_api = "http://192.107.29.92:7007/api/update";
$php_insert_bannerweb_api = "http://192.107.29.92:7007/api/insert";

$php_fetch_info_sec_api = "http://192.107.29.92:7008/api/query";
$php_update_info_sec_api = "http://192.107.29.92:7008/api/update";
$php_insert_info_sec_api = "http://192.107.29.92:7008/api/insert";

$php_fetch_phd_api = "http://192.107.29.92:7009/api/query";
$php_update_phd_api = "http://192.107.29.92:7009/api/update";
$php_insert_phd_api = "http://192.107.29.92:7009/api/insert";

$php_fetch_perso_api = "http://192.107.29.92:7010/api/query";
$php_update_perso_api = "http://192.107.29.92:7010/api/update";
$php_insert_perso_api = "http://192.107.29.92:7010/api/insert";

$php_fetch_perso_archive_api = "http://192.107.29.92:7011/api/query";
$php_update_perso_archive_api = "http://192.107.29.92:7011/api/update";
$php_insert_perso_archive_api = "http://192.107.29.92:7011/api/insert";

$php_fetch_manufacturing_api = "http://192.107.29.92:7012/api/query";
$php_update_manufacturing_api = "http://192.107.29.92:7012/api/update";
$php_insert_manufacturing_api = "http://192.107.29.92:7012/api/insert";

$php_fetch_it_repair_api = "http://192.107.29.92:7013/api/query";
$php_update_it_repair_api = "http://192.107.29.92:7013/api/update";
$php_insert_it_repair_api = "http://192.107.29.92:7013/api/insert";

$php_fetch_ims_express_api = "http://192.107.29.92:7014/api/query";
$php_update_ims_express_api = "http://192.107.29.92:7014/api/update";
$php_insert_ims_express_api = "http://192.107.29.92:7014/api/insert";

$php_fetch_human_resources = "http://192.107.29.92:7015/api/query";
$php_update_human_resources = "http://192.107.29.92:7015/api/update";
$php_insert_human_resources = "http://192.107.29.92:7015/api/insert";

$php_fetch_ltoserial = "http://192.107.29.92:7016/api/query";
$php_update_ltoserial = "http://192.107.29.92:7016/api/update";
$php_insert_ltoserial = "http://192.107.29.92:7016/api/insert";

$php_fetch_payroll = "http://192.107.29.92:7017/api/query";
$php_update_payroll = "http://192.107.29.92:7017/api/update";
$php_insert_payroll = "http://192.107.29.92:7017/api/insert";

class Connection
{
    //* Database Connection
    public function db_connection(string $server_ip, string $db_name, string $db_username, string $db_pass)
    {
        try {
            $db_conn = new PDO("pgsql:host=" . $server_ip . ";port=5432; dbname= " . $db_name . ";", $db_username, $db_pass);
            $db_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $db_conn;
        } catch (PDOException $e) {
            die("ERROR: Could not connect " . $db_name . " - " . $e->getMessage());
        }
    }

    //* banner_web Connection
    public function db_conn_bannerweb()
    {
        return self::db_connection('192.107.29.95', 'banner_web', 'postgres', '123456789');
        // return self::db_connection('localhost', 'banner_web_live', 'postgres', 'B@nn3r2022');
        // return self::db_connection('192.107.17.49', 'banner_web', 'postgres', 'B@nn3r2023');
    }
    //* banner_web Connection
    public function db_conn_human_resources()
    {
        return self::db_connection('localhost', 'human_resources', 'postgres', 'B@nn3r2022');
        // return self::db_connection('192.107.17.49', 'banner_web', 'postgres', 'B@nn3r2023');
    }
    //* manufacturing Connection
    public function db_conn_manufacturing()
    {
        return self::db_connection('localhost', 'manufacturing', 'postgres', 'B@nn3r2022');
        // return self::db_connection('192.107.17.49', 'manufacturing', 'postgres', 'B@nn3r2023');
    }
    //* personalization_database Connection
    public function db_conn_personalization()
    {
        return self::db_connection('192.107.29.95', 'personalization_database', 'postgres', '123456789');
        // return self::db_connection('192.107.17.49', 'personalization', 'postgres', 'B@nn3r2023');
    }
    //* personalization_archive Connection
    public function db_conn_personalization_archive()
    {
        return self::db_connection('192.107.29.95', 'personalization_archive', 'postgres', '123456789');
        // return self::db_connection('192.107.17.49', 'personalization_archive', 'postgres', 'B@nn3r2023');
    }
    //* email_logs Connection
    public function db_conn_email_logs()
    {
        return self::db_connection('192.107.16.248', 'email_logs', 'postgres', '12345678');
    }
    //* Bannerdata Connection
    public function db_conn_bannerdata()
    {
        return self::db_connection('192.107.17.220', 'Bannerdata', 'ERPUSER2020', '@RPBANN3R2020');
    }
    //* info_security Connection
    public function db_conn_info_security()
    {
        return self::db_connection('192.107.29.95', 'info_security', 'postgres', '123456789');
        // return self::db_connection('localhost', 'info_security_live_v2', 'postgres', 'B@nn3r2022');
        // return self::db_connection('192.107.17.49', 'info_security', 'postgres', 'B@nn3r2023');
    }
    //* CMS Connection
    public function db_conn_cms()
    {
        return self::db_connection('192.107.16.248', 'CMS', 'postgres', '12345678');
    }
    //* cms_data Connection
    public function db_conn_cms_data()
    {
        return self::db_connection('192.107.16.248', 'cms_data', 'postgres', '12345678');
    }
    //* Physical Security Connection
    public function db_conn_physical_security()
    {
        return self::db_connection('192.107.29.95', 'physical_security', 'postgres', '123456789');
        // return self::db_connection('localhost', 'physical_security_live', 'postgres', 'B@nn3r2022');
        // return self::db_connection('192.107.17.49', 'physical_security', 'postgres', 'B@nn3r2023');
    }
    //* IT Repair Request Connection
    public function db_conn_it_repair_request()
    {
        return self::db_connection('192.107.29.95', 'it_repair_request', 'postgres', '123456789');
        // return self::db_connection('localhost', 'it_repair_request_live', 'postgres', 'B@nn3r2022');
        // return self::db_connection('192.107.17.49', 'it_repair_request', 'postgres', 'B@nn3r2023');
    }
    //* IT Asset Connection
    public function db_conn_it_asset()
    {
        // return self::db_connection('192.107.17.49', 'itassetdb_new', 'postgres', 'B@nn3r2023');
        return self::db_connection('localhost', 'itassetdb_new', 'postgres', 'B@nn3r2022');
    }
    //* IMS Express Connection
    public function db_conn_ims_express()
    {
        return self::db_connection('localhost', 'ims_express', 'postgres', 'B@nn3r2022');
        // return self::db_connection('192.107.17.49', 'ims_express', 'postgres', 'B@nn3r2023');
    }
    //* Warehouse Connection
    public function db_conn_whpo()
    {
        return self::db_connection('192.107.29.95', 'WHPO', 'postgres', '123456789');
        // return self::db_connection('192.107.17.49', 'ims_express', 'postgres', 'B@nn3r2023');
    }
    //* ITX Connection
    public function db_conn_itassetExtention()
    {
        return self::db_connection('192.107.29.95', 'itassetdb_new', 'postgres', '123456789');
        // return self::db_connection('localhost', 'itassetdb_new', 'postgres', 'B@nn3r2022');
        // return self::db_connection('192.107.17.49', 'ims_express', 'postgres', 'B@nn3r2023');
    }
}

//* Initialize Connection
$conn = new Connection();
