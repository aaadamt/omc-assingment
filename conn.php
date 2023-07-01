<?php
    $host = "localhost";
    $username="root";
    $password="";
    $dbname="omc_db";
    try
    {
        $conn = mysqli_connect($host,$username,$password,$dbname);
    }
    catch(mysqli_sql_exception)
    {
        $data = [
            'status' => 500,
            'message' => 'Internal server error, DB is down'
        ];
        echo json_encode($data);

    }
    if(!$conn)
    {
        die("Conn failed" . mysqli_connect_error());
    }
?>
