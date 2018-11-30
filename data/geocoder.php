<?php

if(isset($_GET['lat']) && isset($_GET['lng'])){
    $latitude =$_GET['lat'];
    $longetude =$_GET['lng'];
   $i =0;
   $json = connect($latitude,$longetude,$i);

    $city = $json->results[0]->address_components[3]->long_name;
    $country = $json->results[0]->address_components[6]->long_name;

    $result= array("city"=>$city,"country"=>$country);
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}
else echo "error";

function connect($latitude,$longetude,$i)
{
    ++$i;
    $response = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?language=en&latlng=' . $latitude . ',%20' . $longetude);
    $json = json_decode($response);

    $city = $json->results[0]->address_components[3]->long_name;
    $country = $json->results[0]->address_components[6]->long_name;

    if($city==null && $i<5){
        connect($latitude,$longetude,$i);
    }
    else {
        return $json;
    }
}