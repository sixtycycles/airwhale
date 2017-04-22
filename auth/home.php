<?php
session_start();
require_once 'class.user.php';
$user_home = new USER();

if (!$user_home->is_logged_in()) {
    $user_home->redirect('index.php');
}

$stmt = $user_home->runQuery("SELECT * FROM tbl_users WHERE userID=:uid");
$stmt->execute(array(":uid" => $_SESSION['userSession']));
$row1 = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Orono Problem Reporter</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
            integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
            crossorigin="anonymous"></script>
</head>
<body>
<!-- NAV-->
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
                        <li><a href="homeOLD.php">Report Problems</a></li>
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
<!-- END NAV-->
<!--Main area -->
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-lg-8">
            <div class="panel panel-default">
                <!-- MAP area-->
                <div id="map" style="height:500px; width:100%"></div>
                <!-- END MAP area-->
            </div>
            <!-- problem Filter area-->
            <div class="well">
                <!--This renders the filter buttons from problem types in DB -->
                <?php
                if ($_SESSION['userSession']) {
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

                    $result = mysqli_query($connection, $query);

                    if (!$result) {
                        die('Invalid query: ' . mysqli_error($connection));
                    }

                    echo "<div class='form-group'>";
                    echo "<h4>Filters</h4>";
                    while ($row = @mysqli_fetch_assoc($result)) {

                        echo '<button 
                        name=\'filters_params\' 
                        class=\'btn btn-primary\' 
                        onclick=\'hide(' . '"' . $row['type'] . '"' . ')\' 
                        id=\'' . $row['type'] . '\'> '
                            . $row['type'] .
                            " <span class='badge'> " . $row['COUNT(type)'] . " </span>" . ' 
                        </button> ';

                    };
                    echo "</div>";
                    // $connection->close();
                }
                ?>
            </div>
            <!-- END problem Filter area-->
        </div>
        <!-- problem entry area-->
        <div class="col-md-4 col=lg-4">

            <div id="form" class="panel panel-default">
                <div class="panel-heading"><h4>Report a problem</h4></div>
                <div class="panel-body">
                    <form method="post" action="phpsqlinfo_addrow.php" enctype="multipart/form-data">
                        <div class="form-group">

                            <label for="name">Your Name</label>
                            <input class="form-control" type='text' id='name' name='name'
                                   value="<?php
                                   echo $row1['userName'];
                                   ?>"/>

                            <label for="email">Email address</label>
                            <input type="email" id="email" name='email' class="form-control"
                                   value="<?php echo $row1['userEmail']; ?>"/>

                            <label for="type">What type of Problem?</label>
                            <select class="form-control" id='type' name='type'> +
                                <option value='pothole' SELECTED>Pothole in Road</option>
                                <option value='streetlight'>Streetlight Out</option>
                                <option value='fireHydrant'>Fire Hydrant Issues</option>
                                <option value='grafitti'>Grafitti/Vandalism</option>
                                <option value='other'>Other</option>
                            </select>

                            <label for="description">Description of problem</label>
                            <input class="form-control" type='text' id='description' name='description'/>

                            <h4>How should we contact you? </h4>
                            <label for="phone">Phone Number</label>
                            <input type="tel" class="form-control" name='phone' id="phone" placeholder="123.456.7890">
                            <h4>Upload a picture of the issue</h4>
                            <input type="file" class="form-control-file " aria-describedby="fileHelp" name="file"/>

                            <h4>Coordinates</h4>
                            (or click the map where the problem is)<br>
                            <input type="text" name="lat" id="lat" placeholder="lattitude">
                            <input type="text" name="lng" id="lng" placeholder="longitude">
                            <hr>
                            <input class="btn btn-success form-control" type='submit' value='Save'/>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- END problem entry area-->
    </div>
</div>
<!-- Scripts -->
<script>
    var map;
    var marker;
    var infowindow;
    var messagewindow;
    var roads;
    var surroundingTowns;
    var markers = [];

    //grab  lat lng data form click on map, also replace "you are here" with this location. (prevents stacking of user markers.
    function grabCoords(event) {

        document.getElementById('lat').value = event.latLng.lat();
        document.getElementById('lng').value = event.latLng.lng();
        //this removes the last click marker from  the user, or if no clicks yet, the geolocated marker.
        function moveMe() {
            for (var i = 0; i < markers.length; i++) {
                if (markers[i].type === 'myproblem') {
                    markers[i].setMap(null);
                }
            }
        }

        moveMe();
        var marker = new google.maps.Marker({
            position: event.latLng,
            map: map,
            animation: google.maps.Animation.DROP,
            type: "myproblem"

        });
        marker.addListener('click', function () {
            infowindow.setContent("your problem goes here");
            infowindow.open(map, marker);
        });
        markers.push(marker);
    }
    //  show all markers of a particular category, styles buttons
    function show(category) {
        for (var i = 0; i < markers.length; i++) {
            if (markers[i].type === category) {
                markers[i].setMap(map);
                console.log(category)
            }

        }
        //change buttons and onclick functions to the right one after click.
        document.getElementById(category).setAttribute('class', 'btn btn-primary');
        document.getElementById(category).setAttribute('onclick', "hide('" + category + "')");

    }

    //  hides all markers of a particular category, styles buttons
    function hide(category) {
        for (var i = 0; i < markers.length; i++) {
            if (markers[i].type === category) {
                markers[i].setMap(null);
                console.log(category)
            }
        }
        //change buttons and onclick functions to the right one after click.
        document.getElementById(category).setAttribute('class', 'btn btn-default');
        document.getElementById(category).setAttribute('onclick', "show('" + category + "')");

    }

    //draw map, import xml data of problems and add markers and info to infoindows.
    function initMap() {
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
        infowindow = new google.maps.InfoWindow({content: "yay"});
        messagewindow = new google.maps.InfoWindow({content: "YAY"});
        //outline of town boundary for reference.
        roads = new google.maps.KmlLayer({
            url: 'http://sixtycycles.github.io/CPR_KML/Orono_Roads.kml',
            map: map,
            preserveViewport: true

        });
        surroundingTowns = new google.maps.KmlLayer({
            url: 'http://sixtycycles.github.io/CPR_KML/TownswithoutOrono.kmz',
            map: map,
            preserveViewport: true

        });

        //this guy grabs the xml file from the db, and adds all the problems to the markers array
        downloadUrl('dump.php', function (data) {
            var xml = data.responseXML;
            var downloadedMarkers = xml.documentElement.getElementsByTagName('marker');
            Array.prototype.forEach.call(downloadedMarkers, function (markerElem) {

                var id = markerElem.getAttribute('id');
                var name = markerElem.getAttribute('name');
                var description = markerElem.getAttribute('description');
                var type = markerElem.getAttribute('type');
                var timestamp = markerElem.getAttribute('timestamp');
                var status = markerElem.getAttribute('problemStatus');
                var imageFile = markerElem.getAttribute('img');

                var point = new google.maps.LatLng(
                    parseFloat(markerElem.getAttribute('lat')),
                    parseFloat(markerElem.getAttribute('lng')));

                var infowincontent = document.createElement('div');

                var strong = document.createElement('strong');
                strong.textContent = "User: " + name;
                infowincontent.appendChild(strong);
                infowincontent.appendChild(document.createElement('br'));

                var text = document.createElement('text');
                text.textContent = "Problem Type: " + type + "-" + id;
                infowincontent.appendChild(text);
                infowincontent.appendChild(document.createElement('br'));

                var desc = document.createElement('text');
                desc.textContent = "Problem Description: " + description;
                infowincontent.appendChild(desc);
                infowincontent.appendChild(document.createElement('br'));

                var time = document.createElement('text');
                time.textContent = "Timestamp: " + timestamp;
                infowincontent.appendChild(time);
                infowincontent.appendChild(document.createElement('br'));

                var problemStatus = document.createElement('p');
                problemStatus.textContent = "Status: " + status;
                infowincontent.appendChild(problemStatus);
                infowincontent.appendChild(document.createElement('br'));

                var problemImage = document.createElement('p');
                problemImage.innerHTML = '<img src="uploads/' + imageFile + '" /> ';
                infowincontent.appendChild(problemImage);
                infowincontent.appendChild(document.createElement('br'));

                var icon = customLabel[type] || {};
                //we add the property type to the marker object to filter by problem type later
                var marker = new google.maps.Marker({
                    map: map,
                    position: point,
                    label: icon.label,
                    type: type
                });
                markers.push(marker);
                //opens infowin when clicking marker.
                marker.addListener('click', function () {
                    infowindow.setContent(infowincontent);
                    infowindow.open(map, marker);
                });

            });
        });
        //this uses the browser to locate you.
        function locateMe() {
            navigator.geolocation.getCurrentPosition(function (position) {

                var pos = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);

                marker = new google.maps.Marker({
                    position: pos,
                    draggable: true,
                    animation: google.maps.Animation.DROP,
                    map: map,
                    label: "YOU ARE HERE",
                    type: 'myproblem'

                });

                markers.push(marker);
                document.getElementById('lat').value = position.coords.latitude;
                document.getElementById('lng').value = position.coords.longitude;

            })
        }


        //add listener to have user place problem marker. .
        google.maps.event.addListener(map, 'click', function (event) {
            grabCoords(event);
            //this one opens the info when you clikc a marker
            google.maps.event.addListener(marker, 'click', function () {
                infowindow.open(map, marker);
            });

        });
        locateMe();
    } //END OF INIT MAP DUMMY
    //for grabbing xml
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
    //why not?
    function doNothing() {
    }

</script>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCLpwvDNIXNMVz7GRsggZOfRMDGQE-pdPE&callback=initMap">
</script>
<!--END Main area -->

</body>

</html>