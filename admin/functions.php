<?php
//если была нажата кнопочка добавить новый город
if(isset($_POST['saveCity'])){

    // то мы достаем все данные с заполненой формы
    $cityName = $_POST['cityName'];
    $north = $_POST['north'];
    $east = $_POST['east'];
    $south = $_POST['south'];
    $west = $_POST['west'];
    $startLat = $_POST['startLat'];
    $startLon = $_POST['startLon'];
    $minZoom = $_POST['minZoom'];
    $maxZoom = $_POST['maxZoom'];

    //и пише их в бд в таблицу которая содержит список существующих городов

    $sql="INSERT INTO city(north, east, south, west, start_lat, start_lon, min_zoom, max_zoom, name)
          VALUES('$north', '$east', '$south', '$west', '$startLat', '$startLon', '$minZoom', '$maxZoom','$cityName' )";
    // если все успешно, то мы создаем дополнительную таблицу для маркеров созданого нами города
    if($db->exec($sql))
    {
        $cityName = $cityName."_markers";

        $creteTable="CREATE TABLE $cityName (
        id INT( 11 ) AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR (30),
        type VARCHAR (20),
        longetude VARCHAR (10),
        latitude VARCHAR (10),
        description VARCHAR (1500),
        image VARCHAR (255),
        video VARCHAR (255),
        count_people INT (11),
        count_mark INT (11),
        comment VARCHAR (255) )";

        $db->exec($creteTable);

    }
    else echo "error city table";
}

//сохранение нового маркера какого либо города
if(isset($_POST['saveMarker'])){
    $currentCity = $_POST['current_city'];;
    $error = array();


    // извлекаем данные с формы
    $markerName = $_POST['markerName'];
    $markerLat = $_POST['markerLat'];
    $markerLon = $_POST['markerLon'];
    $markerType = $_POST['type'];
    $descript = $_POST['descript'];

    $path_name='photo/'.$currentCity;
    //настройки загрузки фото
    if(!is_dir($path_name)){
        mkdir("$path_name", 0777);
    }
    if(isset($_POST['photo'])) {
        $path_photo='photo/'.$currentCity.'/'.$markerName;
        $url = $_POST['photo'];
        $photo_name = '1.jpg';
        if(!is_dir($path_photo)){
            mkdir("$path_photo", 0777);
        }
        $photo = $path_photo.'/'.$photo_name;
        file_put_contents($photo, file_get_contents($url));
    }
    else{
        $path_photo='photo/'.$currentCity.'/'.$markerName;
        if(!is_dir($path_photo)){
            mkdir("$path_photo", 0777);
        }
          $path = $path_photo.'/';
          $tmp_path = 'tmp/';

          $types = array('image/gif', 'image/png', 'image/jpeg');
          $size = 1024000;

        //если нет ошибок при загрузке фото
        if (!in_array($_FILES['picture']['type'], $types)){
            $error[]='Запрещённый тип файла';
        }

        if ($_FILES['picture']['size'] > $size){
            $error[]='Слишком большой размер файла';
        }
        if (!@copy($_FILES['picture']['tmp_name'], $path . $_FILES['picture']['name'])){
            $error[]='ошибка чтения картинки';
        }

        $photo = "https://navigo.ga/".$path.$_FILES['picture']['name'];
    }

    $tableName = $currentCity."_markers";
    // или других ошибок
    if(count($error)>0){
        foreach ($error as $val){
            echo $val;
        }
    } else {
      //  echo $tableName." ".$markerName." ".$markerLat." ".$markerLon." ".$markerType." ".$descript." ".$photo;
// то дынные о маркере пишем в бд
    $createMarker = "INSERT INTO $tableName (name, type, longetude, latitude, description, image) VALUES(
    '$markerName', '$markerType', '$markerLon','$markerLat' , '$descript','$photo') ";
    $db->exec($createMarker);

    }

}


// при нажатии на существующий город  js файл обращается к этой функции и говорит, данные о каком городе он хочет получить
if(isset($_GET['clickItem'])){
    include ('../config.php');
    $data = $_GET['clickItem'];
    //вытаскиваем днные с бд
    $result = $db->query("SELECT * FROM city WHERE name = '$data'");
    $city=$result->fetch(PDO::FETCH_ASSOC);
    // и отсылаем их обратно в js как ответ для дальнейшей обработки
    echo $city['north']."/";
    echo $city['east']."/";
    echo $city['south']."/";
    echo $city['west']."/";
    echo $city['start_lat']."/";
    echo $city['start_lon']."/";
      echo $data;

}

//тоже самое только это удаление города, получаем город который надо удалить и делаем запрос к бд.
if(isset($_GET['delete_city'])){
    include ('../config.php');
    $city = $_GET['delete_city'];
    $table = $city."_markers";
    $deleteTable = "DROP TABLE $table";
    $deleteCity = "DELETE  FROM city WHERE name = '$city'";
    $db->exec($deleteCity);
    $db->exec($deleteTable);
    header('Location:'.HOST );
}
