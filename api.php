<?php
    include ("conn.php");
    header('Content-Type: application/json');

    function isValidDate($date)
    /*function to test a text is a date, basic prevention for sql injection*/
    {
        return (strtotime($date) !== false);
    }
    function isValidCurrency($Currency)
    {
        global $conn;
        $sql = "SELECT * from currency where name LIKE '" . $Currency . "'"; 
        $result = mysqli_query($conn, $sql);
        return (mysqli_num_rows($result) == 1);
    }

    function get_rates($startDate,$endDate,$CUR)
    /*function to run the query based on given dates, only called if params are valid*/
    {
        global $conn;
        $sql = "SELECT dr.date, dr.rate, cr1.name as FromCur, cr2.name as ToCur FROM daily_rates as dr
                join currency cr1 on dr.from_cur = cr1.ID
                join currency cr2 on dr.to_cur = cr2.ID 
                WHERE date >= '" . $startDate ."'" . "AND date <= '" . $endDate ."'"
                . "AND (cr1.name LIKE '".$CUR ."'" . "OR cr2.name LIKE '" . $CUR . "')"
                ;
        //echo $sql;
        $result = mysqli_query($conn, $sql);

        $rates_arr = array();

        if(mysqli_num_rows($result) > 0)
        {
            $I = 0;
            while($row = $result->fetch_assoc())
            {
                $rates_arr[$I] = array('date'=>$row['date'] ,'rate' => $row["rate"],'FromCur' => $row["FromCur"], 'ToCur' => $row["ToCur"] );
                $I++;
            }
            $data = [
                'status' => 200,
                'message' => 'Exchange rates fatched succesfully',
                'data' => $rates_arr
            ];
        }
        else
        {
            $data = [
                'status' => 200,
                'message' => 'NO ROWS FETCHED',
            ];
        }
        return $data;
    }

    function closeCall($data) /*fuction to close the api call, close the db connection and returns the json*/
    {
        global $conn;
        mysqli_close($conn);
        return json_encode($data);
    }

    $method = $_SERVER['REQUEST_METHOD'];
    $status = 200; //before verifying dates, assume valid
    switch($method) 
    {
        case 'GET':
            if(isset($_GET['startdate'])) //if startdate is set, verify, if not set status to 400
            {
                $startdate = $_GET['startdate'];
                if (isValidDate($startdate)) 
                {
                    $startdatefinal = date('Y/m/d',strtotime($startdate));
                } 
                else //start date is not valid, set status to 400 
                {
                    $status = 400;
                    $data = 
                    [
                        'status' => 400,
                        'message' => 'No Valid Start Date',
                    ];
                }
            }
            else 
            {
                $status = 400;
                $data = 
                [
                    'status' => 400,
                    'message' => 'No Valid Start Date',
                ];
            }
            if(isset($_GET['enddate'])) //if end date is set, verify, if not set status to 400
            {
                $enddate = $_GET['enddate'];
                if (isValidDate($enddate)) 
                {
                    $enddatefinal = date('Y/m/d',strtotime($enddate));
                }
                //end date is not valid, set status to 400 
                else
                {
                    $status = 400;
                    $data = 
                    [
                        'status' => 400,
                        'message' => 'Invalid end Date',
                    ];
                }
            }
            else //no end date selected, set to 1/1/24
            {
                $enddatefinal = date('Y/m/d',strtotime("01/01/2024"));
            }
            if($startdatefinal > $enddatefinal) // check that date range is valid, if not set status to 400
            {
                $status = 400;
                $data = 
                    [
                        'status' => 400,
                        'message' => 'Invalid date range',
                    ];
            }

            if(isset($_GET['FromCurrency']))
            {
                $CUR = mysqli_real_escape_string($conn, $_GET['FromCurrency']); // injection protection
                //echo $CUR;
                if(!isValidCurrency($CUR))
                {
                    $status = 400;
                    $data = 
                    [
                        'status' => 400,
                        'message' => 'Invalid Currency',
                    ];
                }
            }
            else
            {
                $status = 400;
                $data = 
                [
                    'status' => 400,
                    'message' => 'No Valid Base Currency',
                ];
            }
            if($status == 200)
            {
                $data = get_rates($startdatefinal,$enddatefinal,$CUR);
                echo (closeCall($data));
            }
            else
            {
                echo (closeCall($data));
            }
            break;
            // continue the api, as for post, put and delete
    }
        //return (closeCall($data));
?>