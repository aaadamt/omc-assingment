<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exchange rates UI</title>

    <link rel="stylesheet" href="styles.css">
	<title>OMC assignment</title>
</head>
<body>
    <center>
    <form action="index.php" method = "get">
        <label>Start date</label><br>
        <input type="date" name="startdate"><br>
        <label>End date</label><br>
        <input type="date" name="enddate"><br>
        <label>Base currency code</label><br>
        <input type="text" name="fromcurrency"><br><br>
        <input type="submit" value="get rates">
    </form><br>
    </center>
   <table>
      <tr>
         <th>Date</th>
         <th>Rate</th>
         <th>From</th>
         <th>To</th>
         </tr>
        <?php

            $apiUrl = 'localhost/omcfinal/inc/api.php';
            $startDate = $_GET['startdate'];
            $endDate = $_GET['enddate'];
            $FromCurrency= $_GET['fromcurrency'];
            $apiUrlWithArgs = $apiUrl . '?' . http_build_query(array('startdate' => $startDate,
                                             'enddate' => $endDate,'fromcurrency'=>$FromCurrency
                                            ));

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $apiUrlWithArgs);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $responseJson = curl_exec($ch);
            curl_close($ch);
            $responseArray = json_decode($responseJson,true);
            $rates = $responseArray["data"];
            if(count($rates) != 0)
            {
                foreach ($rates as $rate) 
                {
                        echo("<tr>");
                            echo "<td>" . $rate['date'] . "</td>";
                            echo "<td>" . $rate['rate'] . "</td>";
                            echo "<td>" . $rate['FromCur'] . "</td>";
                            echo "<td>" . $rate['ToCur'] . "</td>";
                        echo("<tr>");
                }
            }
        ?>
</table>
</body>
</html>
