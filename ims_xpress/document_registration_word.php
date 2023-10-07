<?php
include '../configuration/connection.php';

$imsExpress = $conn->db_conn_ims_express(); //* IMS Express Database connection

$docregisteredid = $_GET['d'];

$sqlstring = "SELECT  encode(doc_word_file,'escape') AS word_file FROM ims_document_registered WHERE docregisteredid = ?";
$result_stmt = $imsExpress->prepare($sqlstring);
$result_stmt->execute([$docregisteredid]);
while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
    $doc_word = $row['word_file'];
}

$data = base64_decode($doc_word);
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
header('Content-Disposition:attachment;filename=Document_Registration-' . date('Y-m-d h:i:s') . '.docx');
echo $data;

$imsExpress = null; //* ======== Close Connection ========
