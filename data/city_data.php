<?php

ini_set('display_errors',1);
error_reporting(E_ALL);
include("../config.php");

//запрос на выборку городов
$name = array();
$north = array();
$east = array();
$south = array();
$west = array();
$start_lat = array();
$start_lon = array();
$min_zoom = array();
$max_zoom = array();
//делаем запрос к бд
$city_sql = "SELECT * FROM city";
$city_result = $db->query($city_sql);

$i = 0;
while($city_data=$city_result->fetch(PDO::FETCH_ASSOC)){
    $name[$i] = $city_data['name'];
    $north[$i] = $city_data['north'];
    $east[$i] = $city_data['east'];
    $south[$i] = $city_data['south'];
    $west[$i] = $city_data['west'];
    $start_lat[$i] = $city_data['start_lat'];
    $start_lon[$i] = $city_data['start_lon'];
    $min_zoom[$i] = $city_data['min_zoom'];
    $max_zoom[$i] = $city_data['max_zoom'];
    $i++;
}
$cityArray = array();
for($j = 0; $j<$i; $j++){
    $cityArray[$j] = array(
        "name" =>  $name[$j],
        "startLat" =>  $start_lat[$j],
        "startLon" =>  $start_lon[$j],
        "northLat" => $north[$j],
        "eastLon" => $east[$j],
        "southLat" =>  $south[$j],
        "westLon" =>  $west[$j],
        "minZoom" => $min_zoom[$j],
        "maxZoom" => $max_zoom[$j]);
}

$print_data = array("Cities" => $cityArray);
echo json_encode( $cityArray, JSON_UNESCAPED_UNICODE )."json_string";

