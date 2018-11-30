<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
if(isset($_GET['city']))
{
    include("../config.php");
    $cityName =$_GET['city']."_markers";
    $sql = "SELECT image FROM $cityName";
    $result = $db->query($sql);

    $zip = new ZipArchive(); // подгружаем библиотеку zip
    $zip_name = $cityName.".zip"; // имя файла
    $file_folder = "../"; // папка с файлами

    if($zip->open($zip_name, ZIPARCHIVE::CREATE)!==TRUE) {
    $error .= "* Sorry ZIP creation failed at this time";
    }
    else {
        $i = 0;
        while ($marker = $result->fetch(PDO::FETCH_ASSOC)) {
            $zip->addFile($file_folder.$marker['image']); // добавляем файлы в zip архив
        }
        $zip->close();

        if(file_exists($zip_name))
        {
           echo "файл успешно создан";
            header('Content-type: application/zip');
            header('Content-Disposition: attachment; filename="'.$zip_name.'"');
            readfile($zip_name);
        }


}

}
else echo "город не задан";