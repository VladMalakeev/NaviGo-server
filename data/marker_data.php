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
    
    //$city = array($_GET['city']."Markers" => $markerData);
    echo json_encode( $markerData, JSON_UNESCAPED_UNICODE )."json_string";
}
else echo "город не задан";

?>