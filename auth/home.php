<?php
session_start();
require_once 'class.user.php';
$user_home = new USER();

if (!$user_home->is_logged_in()) {
    $user_home->redirect('index.php');
}

$stmt = $user_home->runQuery("SELECT * FROM tbl_users WHERE userID=:uid");
$stmt->execute(array(":uid" => $_SESSION['userSession']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>

<head>
    <title><?php echo $row['userEmail']; ?></title>
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
            <div id="map" style="height:500px; width:100%"></div>
        </div>

        <div class="col-md-4 col=lg-4">
            <div id="form" class="panel panel-default">
                <div class="panel-heading">Report a problem</div>
                <div class="panel-body">
                    <form method="post" action="phpsqlinfo_addrow.php">
                        <div class="form-group">

                            <label for="name">Your Name</label>
                            <input class="form-control" type='text' id='name' name='name'
                                   value="<?php echo $row['userName']; ?>"/>
                            <label for="email">Email address</label>

                            <input type="email" id="email" name='email' class="form-control"
                                   value="<?php echo $row['userEmail']; ?>"/>

                            <label for="address">Address of problem!</label>
                            <input class="form-control" type='text' id='address' name='address'/>

                            <label for="type">What type of Problem?</label>
                            <select class="form-control" id='type' name='type'> +
                                <option value='pothole' SELECTED>pothole</option>
                                <option value='noise'>noise complaint</option>
                                <option value='garbage'>garbage loose!</option>
                                <option value='poop'>dog poop</option>
                            </select>

                            <h4>How should we contact you? </h4>
                            <label for="phone">Phone Number</label>
                            <input type="tel" class="form-control" name='phone' id="phone" placeholder="123.456.7890">
                            <h4>Upload a picture of the issue</h4>
                            <input type="file" class="form-control-file " aria-describedby="fileHelp"/>
                            <h4>Coordinates</h4>
                            (click the map where the problem is)<br>
                            <input type="text" name="lat" id="lat" placeholder="lattitude">
                            <input type="text" name="lng" id="lng" placeholder="longitude">
                            <hr>
                            <input class="btn btn-success form-control" type='submit' value='Save'/>
                        </div>
                    </form>
                </div>
            </div>
        </div>


    </div>

    <script>
        var map;
        var marker;
        var infowindow;
        var messagewindow;
        var ctaLayer;
        var markers = [];
        //populate field with coords from the marker.

        function grabCoords(e) {
            document.getElementById('lat').value = e.latLng.lat();
            document.getElementById('lng').value = e.latLng.lng();
        }

        function initMap() {
            var oronoMaine = {lat: 44.88798544802555, lng: -68.70643615722656};
            //this thing holds labels fro markers by problem type.
            var customLabel = {
                'pothole': {label: 'POTHOLE'},
                'noise': {label: 'NOISE'},
                'garbage': {label: 'TRASH'},
                'poop': {label: 'POOP'}
            };
            //the map where we draw things and interact.
            map = new google.maps.Map(document.getElementById('map'), {
                center: oronoMaine,
                zoom: 13
            });
            infowindow = new google.maps.InfoWindow({content:"yay"});
            messagewindow = new google.maps.InfoWindow({content:"YAY"});
            //outline of town boundary for reference.
            ctaLayer = new google.maps.KmlLayer({
                url: 'http://sixtycycles.github.io/orono.kml',
                map: map,
                preserveViewport: true
            });


            downloadUrl('dump.php', function(data) {
                var xml = data.responseXML;
                var markers = xml.documentElement.getElementsByTagName('marker');
                Array.prototype.forEach.call(markers, function(markerElem) {
                    var id = markerElem.getAttribute('id');
                    var name = markerElem.getAttribute('name');
                    var address = markerElem.getAttribute('address');
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

                    var addr = document.createElement('addr');
                    addr.textContent = address;
                    infowincontent.appendChild(addr);
                    infowincontent.appendChild(document.createElement('br'));

                    var icon = customLabel[type] || {};

                    var marker = new google.maps.Marker({
                        map: map,
                        position: point,
                        label: icon.label

                    });
                    marker.addListener('click', function() {
                        infowindow.setContent(infowincontent);
                        infowindow.open(map, marker);
                    });


                });
            });
            google.maps.event.addListener(map, 'click', function(event) {

                grabCoords(event);
                addMarker(event.latLng);


                google.maps.event.addListener(marker, 'click', function() {
                    infowindow.open(map, marker);
                });

            });
        } //END OF INIT MAP DUMMY


//handle markers added by user, while keeping existing problems. (use array)
        function addMarker(location) {
            clearMarkers();
            var marker = new google.maps.Marker({
                position: location,
                map: map
            });
            markers.push(marker);
        }
        function setMapOnAll(map) {
            for (var i = 0; i < markers.length; i++) {
                markers[i].setMap(map);
            }
        }
        function clearMarkers() {
            setMapOnAll(null);
        }

        function downloadUrl(url,callback) {
            var request = window.ActiveXObject ?
                new ActiveXObject('Microsoft.XMLHTTP') :
                new XMLHttpRequest;

            request.onreadystatechange = function() {
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