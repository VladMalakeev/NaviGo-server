<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
if(isset($_GET['city']))
{

    $markerName = array() ;
    $markerType = array() ;
    $markerLat = array() ;
    $markerLon = array() ;
    $markerDescript = array() ;
    $markerImage = array() ;
    $markerVideo = array() ;
    $markerPeople = array() ;
    $markerMark = array() ;
    $markerComment = array() ;

    include("../config.php");
    $cityName =$_GET['city']."_markers";
    $sql = "SELECT * FROM $cityName";
    $result = $db->query($sql);


    $i = 0;
    while($marker=$result->fetch(PDO::FETCH_ASSOC)){
        $markerName[$i] = $marker['name'];
        $markerType[$i] = $marker['type'];
        $markerLat[$i] = (double)$marker['latitude'];
        $markerLon[$i] = (double)$marker['longetude'];
        $markerDescript[$i] = $marker['description'];
        $markerImage[$i] = $marker['image'];
        $markerVideo[$i] = $marker['video'];
        $markerPeople[$i] = (integer)$marker['count_people'];
        $markerMark[$i] = (integer)$marker['count_mark'];
        $markerComment[$i] = $marker['comment'];
        $i++;

    }
    $markerData = array();
    for($j = 0; $j<$i; $j++){
        $markerData[$j] = array("name" => $markerName[$j],
            "type" => $markerType[$j],
            "lat" => $markerLat[$j],
            "lon" => $markerLon[$j],
            "descript" => $markerDescript[$j],
            "image" => $markerImage[$j],
            "video" => $markerVideo[$j],
            "count_people" => $markerPeople[$j],
            "summ_mark" => $markerMark[$j],
            "comment" => $markerComment[$j]);
    }
    $markers = array();
    $markers = $markerData;
    $data = array("startLat" => 46.478145,
        "startLon" => 30.741650,
        "northLat" => 46.500699,
        "eastLon" => 30.767735,
        "southLat" => 46.355437,
        "westLon" => 30.671887,
        "minZoom" => 12,
        "maxZoom" => 13);
    //$cityData = array($cityName."Data" => $data);
    $city = array($_GET['city']."Markers" => $markers, $_GET['city']."Data" => $data);
    echo "json_string".json_encode( $markers, JSON_UNESCAPED_UNICODE )."json_string";
}
else echo "город не задан";

?>