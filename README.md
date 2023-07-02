the entire code was written on Xampp server with mysql.

api.php - 

file for the api service, build as a skeleton for full api service but as request, only contains get functionality.
also contains protection from sql injections, intergation with the DB and dynamic query building.
the api endpoint - localhost/omcfinal/inc/api.php
relevant errors will return for non relevnt dates and non-existing coin codes

paramters to send - startdate, enddate, FromCurrency.

examples - 

basic call -
URL - http://localhost/omcfinal/inc/api.php?startdate=2023-03-06&enddate=2023-03-08&fromcurrency=DLR
response - {"status":200,"message":"Exchange rates fatched succesfully","data":[{"date":"2023-03-06 00:00:00","rate":"3.58900","FromCur":"DLR","ToCur":"ILS"}]}


basic call without enddate, will get the data until the last day in the DB - 

URL -http://localhost/omcfinal/inc/api.php?startdate=2023-03-06&fromcurrency=DLR
response - {"status":200,"message":"Exchange rates fatched succesfully","data":[{"date":"2023-06-15 00:00:00","rate":"3.58400","FromCur":"DLR","ToCur":"ILS"},{"date":"2023-06-16 00:00:00","rate":"3.55300","FromCur":"DLR","ToCur":"ILS"},{"date":"2023-06-19 00:00:00","rate":"3.60200","FromCur":"DLR","ToCur":"ILS"},{"date":"2023-06-20 00:00:00","rate":"3.60900","FromCur":"DLR","ToCur":"ILS"},{"date":"2023-06-21 00:00:00","rate":"3.60400","FromCur":"DLR","ToCur":"ILS"},{"date":"2023-06-22 00:00:00","rate":"3.62800","FromCur":"DLR","ToCur":"ILS"},{"date":"2023-06-23 00:00:00","rate":"3.62800","FromCur":"DLR","ToCur":"ILS"},{"date":"2023-06-26 00:00:00","rate":"3.62500","FromCur":"DLR","ToCur":"ILS"},{"date":"2023-06-27 00:00:00","rate":"3.63800","FromCur":"DLR","ToCur":"ILS"},{"date":"2023-06-28 00:00:00","rate":"3.67900","FromCur":"DLR","ToCur":"ILS"}]}







omc_db.sql - 

Database file, contains the creation of tables and inserting the data into them.
two tables are in the database.

* currency - table for coins, contains metadata for coins, name, code and wheater or not the coin is digital (not done, but prefebly connected to a table that contains countries that use the coins)
* daily_rates - table for the exchange rates, contains the rate, date and two foreign keys for the coins relevent to the exchange


index.php - 

main UI part, as request, some basic HTML code for a table, a form used to get the fields required for the api call and an api call with json parsing written in php

conn.php - 

start connaction with the DB.
