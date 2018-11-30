/* объявляем все переменные испоьзуемые в коде
функция document.getElementById() означает что мы ищем id в html документе и присваеваем переменной для дальнейшего пользования
 */
var map;
var mapView;
var rectangle;
var blackRectangle;
var centerMarker;
var newMarker;
var north = document.getElementById('north');
var east = document.getElementById('east');
var south = document.getElementById('south');
var west = document.getElementById('west');
var centerLat = document.getElementById('centerLat');
var centerLon = document.getElementById('centerLon');

var markerLat = document.getElementById('markerLat');
var markerLon = document.getElementById('markerLon');

var addCity = document.getElementById('addCity');
var cityArea = document.getElementById('map');
var citySettings = document.getElementById('citySettings');
var btnSaveBounds = document.getElementById('btnSaveBounds');
var btnDeleteBounds = document.getElementById('btnDeleteBounds');
var btnDrawBounds = document.getElementById('btnDrawBounds');

var listItem = document.getElementsByClassName('listCity');
var markerSettings = document.getElementById('marker');
var generateMarker = document.getElementById('generateMarker');
var cityMenu = document.getElementById('cityMenu');

var itemValue = document.getElementById('current_city');

var generateItem = document.getElementsByClassName('selectBox');

var  service;

var places  = [];
var startLat =null ;
var startLon =null ;
var northView = null;
var eastView = null;
var southView = null;
var westView = null;
var itemCity;
var markerActive = false;

// эти кнопки изначально не активны
btnDeleteBounds.disabled = true;
btnSaveBounds.disabled = true;

// берем конкретный блок div и прячем его с помощью css свойства none, block -показать
cityArea.style.display = 'none';
citySettings.style.display = 'none';
cityMenu.style.display = 'none';
markerSettings.style.display = 'none';
generateMarker.style.display = 'none';


//добавить  город
//при нажатии на кнопочку добавить город некоторые блоки прячатся а другие появляются
addCity.addEventListener("click", function() {
    cityArea.style.display = 'block';
    citySettings.style.display = 'block';
    markerSettings.style.display = 'none';
    cityMenu.style.display = 'none';
    generateMarker.style.display = 'none';
    markerActive=false;
    createMap();
});

// нажатие на кнопку добавить маркер
function createMarker() {
    markerSettings.style.display = 'block';
    cityMenu.style.display = 'none';
    markerActive = true;
	TdLeftBarId.style.display = 'none';
	TdMapId.style = "width:30%";
}

function generate() {
    console.log("click");
    generateMarker.style.display = 'block';
    cityMenu.style.display = 'none';

}

function checkAll() {
    for(var i =0; generateItem.length;i++){
        generateItem[i].checked = true;
    }
}
function startSearch1() {
    for(var i =0; generateItem.length;i++){
        if(generateItem[i].checked == true){
            var request = {
                bounds: {
                    north: +northView,
                    south: +southView,
                    east:  +eastView,
                    west:  +westView},
                types: [generateItem[i].value]
            };
            //запускаем поиск и-й категории
            service.nearbySearch(request, callback);
        }
    }
}

function callback(results, status, pagination) {
    if (status == google.maps.places.PlacesServiceStatus.OK) {
        for (var i = 0; i < results.length; i++) {
            var place = results[i];
            request={
                placeId: place.place_id
            }
            //отобоазить только один маркер
           placeMarker(place);

        }
    }
}

    function placeMarker(place) {


           // console.log("name = "+ place.name +"url" +place.photos[0].getUrl({'maxWidth': 35, 'maxHeight': 35}));

            var placeMarker = new google.maps.Marker({
                map: mapView,
                position: place.geometry.location,
                icon: {
                    url: 'https://developers.google.com/maps/documentation/javascript/images/circle.png',
                    anchor: new google.maps.Point(10, 10),
                    scaledSize: new google.maps.Size(10, 17)
                }
            });


       $.ajax({
            type: "POST",
            url: "",
            data: {saveMarker:'true',
                current_city: itemCity,
                markerName: place.name,
                markerLat: place.geometry.location.lat(),
                markerLon: place.geometry.location.lng(),
                type: place.types[0],
                descript: place.vicinity,
                photo:place.photos[0].getUrl({'maxWidth': 2500, 'maxHeight': 250})},
            success: function (data) {
                // console.log(data);
            }
        });




    }

    function saveMarkersToDB() {
         console.log(places.length);
   /* for(var i = 0;i<places.length;i++)
         $.ajax({
               type: "POST",
               url: "",
               data: {saveMarker:'true',
                      current_city: itemCity,
                      markerName: places[i].name,
                      markerLat: places[i].geometry.location.lat(),
                      markerLon: places[i].geometry.location.lng(),
                      type: places[i].types[0],
                      descript: places[i].vicinity,
                      photo:places[i].photos[0].getUrl({'maxWidth': 2500, 'maxHeight': 250})},
               success: function (data) {
                  // console.log(data);
               }
           });*/
    }



// нажатие на кнопку удалить город
//используя ajax мы обращаемся к php файлу который в свою очередь обращается к бд и удаляет город.
function  deleteCity(city) {
    $.ajax({
        type: "GET",
        url: "admin/functions.php",
        data: {delete_city: city},
        success: function (data) {
          console.log(data);
        }
    });
}

// нажатие на любой город из списка
/*
в name мы приниаем город на который нажали, потом обращаемся к php файлу и говорим достать нам данные с бд
ответ php файла передается в переенную data которую мы разбиваем на массив и читаем данные
 */
function clickOnItem(name) {

    console.log("click");
    cityArea.style.display = 'block';
    cityMenu.style.display = 'block';
    citySettings.style.display = 'none';
    markerSettings.style.display = 'none';
    generateMarker.style.display = 'none';
    itemCity = name;
    itemValue.value = itemCity;
    markerActive=false;
    $.ajax({
        type: "GET",
        url: "admin/functions.php",
        data: {clickItem: name},
        success: function (data) {
          var arr = data.split('/');
          northView = arr[0];
          eastView = arr[1];
          southView = arr[2];
          westView = arr[3];
          startLat = arr[4];
          startLon = arr[5];
          itemCity = arr[6];
            console.log(itemCity);
            viewMap();
        }
    });
}


//функция которая отображает маркеры в том городе на который мы нажали, чхема та же: получили ответ от php но уже
// в json формате и создали циклом маркеры.
function viewCityMarkers(newMap) {
    $.ajax({
        type: "GET",
        url: "data/admin_data.php",
        data: {city: itemCity},
        success: function (data) {
            var json = data.split('json_string');
            var jsonString = JSON.parse(json[1]);
            for(var i = 0; i<jsonString.length;i++) {
                var viewMarker = new google.maps.Marker({
                    position: {lat:+jsonString[i].lat, lng:+jsonString[i].lon},
                    map: newMap,
                    title: jsonString[i].name
                });

                var image = jsonString[i].image;
                var contentString = '<img src="'+image+'" width="200px" height="150px" ><br><h2>'+jsonString[i].name+'</h2>'

                var infowindow = new google.maps.InfoWindow({
                    content: contentString
                });

                viewMarker.addListener('click', function() {
                    infowindow.open(newMap,viewMarker);
                });
            }

        }
    });
}


// при клике на созданый город рисуется карта того же города

function  viewMap() {
    var elementView = document.getElementById('map');
    var optionsView = {
        zoom: 14,
        center: {lat:+startLat, lng:+startLon}
    };

    mapView = new google.maps.Map(elementView, optionsView);
    service = new google.maps.places.PlacesService(mapView);
    //при клике по карте мы ставим маркер
    mapView.addListener('click', function(events) {
        setMarkers(events.latLng);
    });


    //после прорисовки карты отобразить на ней созданые маркеры
    viewCityMarkers(mapView);

}

// при нажатии на кнопку добавить город рисуется эта карта
    function createMap() {
        var element = document.getElementById('map');
        var options = {
            zoom: 5,
            center: {lat: 49.498369, lng: 19.431777}
        };

         map = new google.maps.Map(element, options);
        console.log("new map");
        map.addListener('click', function(event) {
            setCenter(event.latLng);
        });
    }

    //нарисовать рамку при клике на кнопочку в правой менюшке, логика сложная особо вникать не надо
    function drawBorder() {
        var minConst = 0.7;
        var bounds = map.getBounds();
        var mapNorthLat = bounds.getNorthEast().lat();
        var mapEastLon = bounds.getNorthEast().lng();
        var mapSouthLat = bounds.getSouthWest().lat();
        var mapWestLon = bounds.getSouthWest().lng();

       var center = {
            lat: mapSouthLat + ((mapNorthLat - mapSouthLat) / 2),
            lon: mapWestLon + ((mapEastLon - mapWestLon) / 2),
        };
       console.log("center: " + center.lat + ", " + center.lon);

        var halfDiagonal ={
           nLat: (mapNorthLat - center.lat) * minConst,
           eLon: (mapEastLon - center.lon) * minConst,
           sLat: (center.lat - mapSouthLat) * minConst,
           wLon: (center.lon - mapWestLon) * minConst
        };

        var final = {
           nLat: center.lat + halfDiagonal.nLat,
           eLon: center.lon + halfDiagonal.eLon,
           sLat: center.lat - halfDiagonal.sLat,
           wLon: center.lon - halfDiagonal.wLon
        };

         rectangle = new google.maps.Rectangle({
            strokeColor: '#FF0000',
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: '#FF0000',
            fillOpacity: 0.05,
            map: map,
             editable:true,
             draggable:true,
            bounds: {
                north: final.nLat,
                south: final.sLat,
                east:  final.eLon,
                west:  final.wLon
            }
        });
        getCoordinates();
        rectangle.addListener('bounds_changed', getCoordinates);
        btnSaveBounds.disabled = false;
        btnDeleteBounds.disabled = false;
        btnDrawBounds.disabled = true;
    }


// у рамки есть 4 координаты, этой функцией мы их получаем
    function getCoordinates(){
     north.value = rectangle.getBounds().getNorthEast().lat();
     east.value  = rectangle.getBounds().getNorthEast().lng();
     south.value = rectangle.getBounds().getSouthWest().lat();
     west.value = rectangle.getBounds().getSouthWest().lng();
    }

    //удалить ранее нарисованую рамку
    function deleteBorder() {
        rectangle.setMap(null);
        north.value = null;
        east.value =null;
        south.value=null;
        west.value=null;

        btnSaveBounds.disabled = true;
        btnDeleteBounds.disabled = true;
        btnDrawBounds.disabled = false;

       if(blackRectangle!=null){
           blackRectangle.setMap(null);
       }
        centerMarker.setMap(null);
        centerLat.value = null;
        centerLon.value = null;
    }

    //сохранение рамки
    function  saveBorder() {
        map.fitBounds(rectangle.getBounds());
        drawSavedBounds();
        rectangle.setMap(null);

        btnSaveBounds.disabled = true;
        btnDeleteBounds.disabled = false;
        btnDrawBounds.disabled = true;
        markerActive = true;

    }
//нарисовать сохраненую рамку на карте
function drawSavedBounds() {
    var pathNorthLat = rectangle.getBounds().getNorthEast().lat();
    var pathEastLon = rectangle.getBounds().getNorthEast().lng();
    var pathSouthLat = rectangle.getBounds().getSouthWest().lat();
    var pathWestLon = rectangle.getBounds().getSouthWest().lng();
    Path = [
        {lat:pathNorthLat,lng:pathEastLon},
        {lat:pathNorthLat,lng:pathWestLon},
        {lat:pathSouthLat,lng:pathWestLon},
        {lat:pathSouthLat,lng:pathEastLon},
        {lat:pathNorthLat,lng:pathEastLon}
    ];

    blackRectangle = new google.maps.Polyline({
        strokeColor: '#FF0000',
        strokeOpacity: 1.0,
        strokeWeight: 2,
        path: Path,
        map: map
    });

}

//задать центр ка карте при добавлении нового города

    function setCenter(latLng) {
        if(markerActive == true) {
            centerMarker = new google.maps.Marker({
                position: latLng,
                map: map,
                title: 'center',
                draggable: true
            });
            centerLat.value = centerMarker.getPosition().lat();
            centerLon.value = centerMarker.getPosition().lng();
            markerActive = false;
        }

            centerMarker.addListener('click', function () {
                centerMarker.setMap(null);
                markerActive = true;
                centerLat.value = null;
                centerLon.value = null;
            });


        centerMarker.addListener('drag', function () {
            centerLat.value = centerMarker.getPosition().lat();
            centerLon.value = centerMarker.getPosition().lng();
        })

    }

    //нарисовать маркер на карте при добавлении достоприечательности
function setMarkers(latLng) {
if(markerActive == true) {
    newMarker = new google.maps.Marker({
        position: latLng,
        map: mapView,
        title: 'newMarker',
        draggable: true
    });
    markerLat.value = newMarker.getPosition().lat();
    markerLon.value = newMarker.getPosition().lng();
    markerActive = false;

}
    newMarker.addListener('click', function(){
        newMarker.setMap(null);
        markerActive = true;
        markerLat.value = null;
        markerLon.value = null;
    });

    newMarker.addListener('drag', function () {
        markerLat.value = newMarker.getPosition().lat();
        markerLon.value = newMarker.getPosition().lng();
    });

}

function  addPhoto() {
    $('.inputFile').click().change(function() {
        var lastIndexOfSep = this.value.lastIndexOf('\\'); // Windows
        if (lastIndexOfSep == -1)
            lastIndexOfSep = this.value.lastIndexOf('/'); // Linux
        var fileName = this.value.substring(lastIndexOfSep + 1, this.length);
       // $("#file_name").val(fileName);
        var placeName = document.getElementById("placeName").value;
        document.getElementById('addImage').style.display="none";
        var path = "photo/"+itemCity+"/"+placeName+"/"+fileName;
        console.log(path);
        document.getElementById('image_box').style.backgroundImage="url('"+path+"')";

    });

}