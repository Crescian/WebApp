<?php
class ProdMachineName
{
    public $machineid;
    public $machine_name;

    public function fetchData($prod)
    {
        $sqlstring = "SELECT machineid,machine_name FROM prod_machine_name";
        $result_stmt = $prod->prepare($sqlstring);
        $result_stmt->execute(); 
        $prod = null; //* ======== Close Connection ========
    }

    public function insertData($prod, $process_name)
    {
        // $sqlstring = "";
        // $result_stmt = $connection->prepare($sqlstring);
        // $result_stmt->execute();
    }

    public function updateData($prod)
    {
        // $sqlstring = "";
        // $result_stmt = $connection->prepare($sqlstring);
        // $result_stmt->execute();
    }

    public function deleteData($prod)
    {
        // $sqlstring = "";
        // $result_stmt = $connection->prepare($sqlstring);
        // $result_stmt->execute();
    }
}
