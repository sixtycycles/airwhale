<?php
session_start();
require_once 'class.user.php';

$user_home = new USER();

if (!$user_home->is_admin()) {
    $user_home->redirect('index.php');
}

$stmt = $user_home->runQuery("SELECT * FROM tbl_users WHERE userID=:uid");
$stmt->execute(array(":uid" => $_SESSION['userSession']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);


//this bit grabs all the problems to display;

?>
<!DOCTYPE html>
<html>

<head>
    <title>Admin Portal | Orono Problem Reporter</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
            integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
            crossorigin="anonymous"></script>
</head>

<body>
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Town Of Orono Issue Tracker</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                       aria-expanded="false">Menu <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="home.php">Report Problems</a></li>
                        <?php
                        if ($_SESSION['isAdmin']) {
                            echo "<li> <a href='admin.php'>Admin Portal</a></li>";
                        }
                        ?>
                        <li role="separator" class="divider"></li>
                        <li><a href="logout.php">logout</a></li>
                    </ul>
                </li>

            </ul>


        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-lg-8">
            <div class="panel panel-default">
            <div id="map" style="height:500px; width:100%"></div>
                </div>
            </div>

        <div class="col-md-4 col=lg-4">
            <div id="form" class="panel panel-default">
                <div class="panel-heading">Administer Problems</div>
                <div class="panel-body">
                    <h3>Hide Types of Problem:</h3>
                    <hr>

                    <?php
                    require_once("phpsqlinfo_dbinfo.php");

                    $connection = mysqli_connect('localhost', $username, $password, $database, $port);
                    if (!$connection) {
                        die('Not connected : ' . mysqli_error($connection));
                    }

                    $db_selected = mysqli_select_db($connection, $database);
                    if (!$db_selected) {
                        die ('Can\'t use db : ' . mysqli_error($connection));
                    }
                    //$query = "SELECT id,type FROM Problems GROUP BY type ASC;";
                    $query = "SELECT id,type, COUNT(type) FROM Problems GROUP BY type ASC;";

                    $result = mysqli_query($connection,$query);

                    if (!$result) {
                        die('Invalid query: ' . mysqli_error($connection));
                    }

                    echo "<div class='form-group'>";
                    while ($row = @mysqli_fetch_assoc($result) ) {

                        echo '<button 
                        name=\'filters_params\' 
                        class=\'btn btn-primary\' 
                        onclick=\'btnSelect(' . '"' . $row['type'] . '"' . ')\' 
                        id=\'' . $row['type'] . '\'> '
                            . $row['type'] .
                            " <span class='badge'> " .$row['COUNT(type)']." </span>". ' 
                        </button> ';

                    };
                    echo "</div>";

                  ?>

                </div>
            </div>
        </div>
        <div style="visibility: hidden;">
            <input type="text" name="lat" id="lat" placeholder="lattitude">
            <input type="text" name="lng" id="lng" placeholder="longitude">
        </div>

    </div>

    <script>
        var map;
        var marker;
        var infowindow;
        var messagewindow;
        var roads;
        var boundary;
        var parcels;
        var markers = [];
        var filters = {};

        //populate field with coords from the marker.

        function grabCoords(e) {
            document.getElementById('lat').value = e.latLng.lat();
            document.getElementById('lng').value = e.latLng.lng();
            clearMarkers();
            var marker = new google.maps.Marker({
                position: e.latLng,
                map: map
            });
            markers.push(marker);
        }

        //this might be a weird way to do this
        function btnSelect(str) {

            if (document.getElementById(str).getAttribute("class") === "btn btn-primary") {
                if (!filters[str]){filters[str] = str}
                document.getElementById(str).setAttribute("class", "btn btn-default");
                initMap(str);

            }
            else {
                document.getElementById(str).setAttribute("class", "btn btn-primary");
                delete filters[str];
                initMap(str);
            }
            console.log(filters);

        }

        function initMap(str) {
            var oronoMaine = {lat: 44.88798544802555, lng: -68.70643615722656};
            //this thing holds labels fro markers by problem type.
            var customLabel = {
                'pothole': {label: 'Pothole'},
                'streetLight': {label: 'Street Light'},
                'fireHydrant': {label: 'Fire Hydrant'},
                'grafitti': {label: 'Grafitti'},
                'other': {label: 'other'}
            };
            //the map where we draw things and interact.
            map = new google.maps.Map(document.getElementById('map'), {
                center: oronoMaine,
                zoom: 12
            });
            //huh?
            infowindow = new google.maps.InfoWindow({content: "yay"});
            messagewindow = new google.maps.InfoWindow({content: "YAY"});

            //outline of town boundary for reference.
            roads = new google.maps.KmlLayer({
                url: 'http://sixtycycles.github.io/CPR_KML/Orono_Roads.kml',
                map: map,
                preserveViewport: true
            });

            boundary = new google.maps.KmlLayer({
                url: 'http://sixtycycles.github.io/CPR_KML/OronoBoundary.kml',
                map: map,
                preserveViewport: true
            });

            parcels = new google.maps.KmlLayer({
                url: 'http://sixtycycles.github.io/CPR_KML/Orono_Parcels.kml',
                map: map,
                preserveViewport: true
            });
            //this grabs all the problems to display!
            downloadUrl('dump.php', function (data) {
                var xml = data.responseXML;
                var markers = xml.documentElement.getElementsByTagName('marker');
                Array.prototype.forEach.call(markers, function (markerElem) {

                    var id = markerElem.getAttribute('id');
                    var name = markerElem.getAttribute('name');
                    var description = markerElem.getAttribute('description');
                    var type = markerElem.getAttribute('type');

                    var point = new google.maps.LatLng(
                        parseFloat(markerElem.getAttribute('lat')),
                        parseFloat(markerElem.getAttribute('lng')));

                    var infowincontent = document.createElement('div');
                    var strong = document.createElement('strong');
                    strong.textContent = name;
                    infowincontent.appendChild(strong);
                    infowincontent.appendChild(document.createElement('br'));

                    var text = document.createElement('text');
                    text.textContent = type + "-" + id;
                    infowincontent.appendChild(text);
                    infowincontent.appendChild(document.createElement('br'));

                    var desc = document.createElement('desc');
                    desc.textContent = description;
                    infowincontent.appendChild(desc);
                    infowincontent.appendChild(document.createElement('br'));

                    var icon = customLabel[type] || {};
                    if(!filters[type]) {
                        var marker = new google.maps.Marker({
                            map: map,
                            position: point,
                            label: icon.label
                        });


                        marker.addListener('click', function () {

                            infowindow.setContent(infowincontent);
                            infowindow.open(map, marker);
                        });
                    }

                });
            });


        } //END OF INIT MAP DUMMY

        function setMapOnAll(map) {
            for (var i = 0; i < markers.length; i++) {
                markers[i].setMap(map);
            }
        }
        function clearMarkers() {
            setMapOnAll(null);
        }
        //handle markers added by user, while keeping existing problems. (use array)

        function downloadUrl(url, callback) {
            var request = window.ActiveXObject ?
                new ActiveXObject('Microsoft.XMLHTTP') :
                new XMLHttpRequest;

            request.onreadystatechange = function () {
                if (request.readyState === 4) {
                    request.onreadystatechange = doNothing;
                    callback(request, request.status);
                }
            };

            request.open('GET', url, true);
            request.send(null);
        }
        function doNothing() {
        }


    </script>
    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCLpwvDNIXNMVz7GRsggZOfRMDGQE-pdPE&callback=initMap">
    </script>


</div>

</body>

</html>