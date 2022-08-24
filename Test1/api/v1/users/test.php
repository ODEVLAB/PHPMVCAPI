<?php

function run()
{
    $header = array(
        'Content-Type: application/json',
        'Access-Control-Allow-Origin: *',
        'Access-Control-Allow-Methods: GET',
        'Access-Control-Max-Age: 3600',
        'Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With'
    );
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://hub.dummyapis.com/employee?noofRecords=10&idStarts=1001",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
      CURLOPT_HTTPHEADER => $header
    ));
    
    $response = curl_exec($curl);
    
    curl_close($curl);
    echo $response;
}

?>