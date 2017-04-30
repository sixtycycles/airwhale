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
    <!-- Import common header -->
    <?php require_once "../partials/head.php"; ?>
    <title>Orono Problem Reporter</title>
</head>


<body>

<!-- Import navbar -->
<?php require_once "../partials/navbar.php"; ?>

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
            <div class="well" id="filterArea">
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

                    //$query = "SELECT id, type_id, COUNT(type_id) FROM Problems GROUP BY type_id ASC;";
                    $query = "SELECT id, Problems.type_id, type_name, COUNT(Problems.type_id)
                            FROM Problems, tbl_problem_types
                            WHERE (Problems.type_id=tbl_problem_types.type_id)
                            GROUP BY Problems.type_id ASC
                            ;";


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
                        onclick=\'hide(' . '"' . $row['type_id'] . '"' . ')\' 
                        id=\'' . $row['type_id'] . '\'> '
                            . $row['type_name'] .
                            " <span class='badge'> " . $row['COUNT(Problems.type_id)'] . " </span>" . ' 
                        </button> ';

                    };
                    echo "</div>";

                    //
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
                            <input class="form-control" type='text' id='name' name='name' placeholder="Your Name"
                                   value="<?php
                                   echo $row1['userName'];
                                   ?>"/>

                            <label for="email">Email Address</label>
                            <input type="email" id="email" name='email' class="form-control"
                                   placeholder="Email" value="<?php echo $row1['userEmail']; ?>"/>

                            <label for="type">What type of problem?</label>
                            <select class="form-control" id='type' name='type'>
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
                                    $query = "SELECT * FROM tbl_problem_types;";
                                    $result = mysqli_query($connection, $query);

                                    if (!$result) {
                                        die('Invalid query: ' . mysqli_error($connection));
                                    }

                                    // Make each row in the "problem type" dropdown
                                    $index = 0;
                                    while ($row = @mysqli_fetch_assoc($result)) {
                                        $id = $row['type_id'];
                                        $name = $row['type_name'];
                                        $selected = $index == 0 ? "SELECTED" : ""; // Select the first row
                                        echo "<option ${selected} value='${id}'>${name}</option>";
                                        $index = $index + 1;
                                    }
                                }

                                ?>
                            </select>

                            <label for="description">Description of problem</label>
                            <input class="form-control" type='text' id='description' name='description'/>

                            <!--                            <h4>How should we contact you? </h4>-->
                            <!--                            <label for="phone">Phone Number</label>-->
                            <!--                            <input type="tel" class="form-control" name='phone' id="phone" placeholder="123.456.7890">-->
                            <h4>Upload a picture of the issue</h4>
                            <input type="file" class="form-control-file " aria-describedby="fileHelp" name="file"/>

                            <h4>Coordinates</h4>
                            (or click the map where the problem is)<br>
                            <div class="input-group">
                                <input class="form-control" type="text" name="lat" id="lat" placeholder="Latitude">
                                <input class="form-control" type="text" name="lng" id="lng" placeholder="Longitude">
                            </div>
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
    //var surroundingTowns;
    //var boundary;
    var markers = [];

    //grab  lat lng data form click on map, also replace "you are here" with this location. (prevents stacking of user markers.
    function grabCoords(event) {

        document.getElementById('lat').value = event.latLng.lat();
        document.getElementById('lng').value = event.latLng.lng();
        //this removes the last click marker from  the user, or if no clicks yet, the auto geolocated marker is moved.
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
            type: "myproblem",
            draggable: true

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
                //console.log(category)
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
                //console.log(category)
            }
        }
        //change buttons and onclick functions to the right one after click.
        document.getElementById(category).setAttribute('class', 'btn btn-default');
        document.getElementById(category).setAttribute('onclick', "show('" + category + "')");

    }

    function updateLikes(id)
    {

        $(function()
        {
            $.ajax({
                type:"GET",
                url: 'second.php',
                data: {
                    id:id
                },
                success:function(response){
                    // process on data
                    document.getElementById('likeArea').textContent = "Likes: " + response;

                }

            })
            ;

        });
    }
    //draw map, import xml data of problems and add markers and info to infoindows.
    function initMap() {
        var oronoMaine = {lat: 44.882390656052756, lng: -68.71810913085938};
        //this thing holds labels fro markers by problem type.
//        var customLabel = {
//            'pothole': {label: 'Pothole'},
//            'streetlight': {label: 'Street Light'},
//            'fireHydrant': {label: 'Fire Hydrant'},
//            'grafitti': {label: 'Grafitti'},
//            'other': {label: 'Other'}
//        };

        //the map where we draw things and interact.
        map = new google.maps.Map(document.getElementById('map'), {
            center: oronoMaine,
            zoom: 13
        });
        infowindow = new google.maps.InfoWindow({content: ""});
        messagewindow = new google.maps.InfoWindow({content: ""});

        map.data.loadGeoJson('../assets/GIS/OronoBoundary.geojson');

        // KML LAYERS
        roads = new google.maps.KmlLayer({
            url: 'http://sixtycycles.github.io/CPR_KML/Orono_Roads.kml',
            map: map,
            preserveViewport: true,
            clickable: false

        });
        // surroundingTowns = new google.maps.KmlLayer({
        //     url: 'http://sixtycycles.github.io/CPR_KML/TownswithoutOrono.kmz',
        //     map: map,
        //     preserveViewport: true,
        //     info: "<h4>Please Select an Area In Orono</h4>"
        // });
        // boundary = new google.maps.KmlLayer({
        //     url: 'http://sixtycycles.github.io/CPR_KML/OronoBoundary.kmz',
        //     map: map,
        //     preserveViewport: true,
        //     clickable: false
        // });

        //this guy grabs the xml file from the db, and adds all the problems to the markers array
        downloadUrl('dump.php', function (data) {
            var xml = data.responseXML;
            var downloadedMarkers = xml.documentElement.getElementsByTagName('marker');

            Array.prototype.forEach.call(downloadedMarkers, function (markerElem) {
                var point = new google.maps.LatLng(
                    parseFloat(markerElem.getAttribute('lat')),
                    parseFloat(markerElem.getAttribute('lng')));
                var type = markerElem.getAttribute('type_id');

                // var label = customLabel[type] || {};

                var iconBase = '../assets/icons/png/';

                var icon = {
                    url: iconBase + (markerElem.getAttribute('markerImage') || 'alert.png'), // url
                    scaledSize: new google.maps.Size(25, 25), // scaled size
                    // origin: new google.maps.Point(0, 0), // origin
                    // anchor: new google.maps.Point(0, 0) // anchor
                };

                //we add the property type to the marker object to filter by problem type later
                var marker = new google.maps.Marker({
                    map: map,
                    position: point,
                    //label: label.label,
                    icon: icon,
                    type: type
                });
                markers.push(marker);
                //opens infowin when clicking marker.
                marker.addListener('click', function () {
                    infowindow.open(map, marker);
                    infowindow.setContent(MakeMarkerWindow(markerElem));

                });

            });
        });

        /**
         * Makes an HTML div containing the information used for the popup
         * when you click on a marker.
         */
        function MakeMarkerWindow(markerElem) {
            var id = markerElem.getAttribute('id');
            var name = markerElem.getAttribute('name');
            var description = markerElem.getAttribute('description');
            var type = markerElem.getAttribute('type_name');
            var timestamp = markerElem.getAttribute('timestamp');
            var status = markerElem.getAttribute('problemStatus');
            var imageFile = markerElem.getAttribute('img');
            var likes = markerElem.getAttribute('likes');
//            var point = new google.maps.LatLng(
//                parseFloat(markerElem.getAttribute('lat')),
//                parseFloat(markerElem.getAttribute('lng')));
            var infowincontent = document.createElement('div');
            infowincontent.setAttribute('class', 'well');
            infowincontent.setAttribute('style', 'width:200px; height:auto');

            var likeButton = document.createElement('button');
            likeButton.setAttribute('class', 'btn btn-default');
            likeButton.setAttribute('id', id);
            likeButton.setAttribute('name','like_button');
            likeButton.setAttribute('type', 'submit');
            likeButton.innerHTML = "second this problem";
            likeButton.setAttribute('onclick','updateLikes('+id+')');

            infowincontent.appendChild(likeButton);
            infowincontent.appendChild(document.createElement('br'));

            var likeArea = document.createElement('text');
            likeArea.textContent = "Likes: " + likes;
            likeArea.setAttribute('id',"likeArea");
            infowincontent.appendChild((likeArea));
            infowincontent.appendChild(document.createElement('br'));

            var strong = document.createElement('strong');
            strong.textContent = "User: " + name;
            infowincontent.appendChild(strong);
            infowincontent.appendChild(document.createElement('br'));
            //make a lil form for the second function.
//            var likeForm = document.createElement('form');
//            likeForm.setAttribute('method','GET');
//            likeForm.setAttribute('action','second.php');
//
//                var hiddenValue = document.createElement('input');
//                hiddenValue.setAttribute('type', 'hidden');
//                hiddenValue.setAttribute('value',id);
//                hiddenValue.setAttribute('name','secondProblem');
//
//
//                var submit = document.createElement('button');
//                submit.setAttribute('class', 'btn btn-default');
//                submit.setAttribute('type', 'submit');
//                submit.innerHTML = "Second This Problem";
//
//            likeForm.appendChild(hiddenValue);
//            likeForm.appendChild(submit);
//
//            infowincontent.appendChild(likeForm);
//            infowincontent.appendChild(document.createElement('br'));
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

            var problemStatus = document.createElement('text');
            problemStatus.textContent = "Status: " + status;
            infowincontent.appendChild(problemStatus);
            infowincontent.appendChild(document.createElement('br'));

            var problemImage = document.createElement('image');
            problemImage.innerHTML = '<img alt="' + imageFile + '" class="img-fluid img-thumbnail" style="width: 100%; " src="uploads/' + imageFile + '" /> ';
            infowincontent.appendChild(problemImage);
            infowincontent.appendChild(document.createElement('br'));

            return infowincontent;
        }

        //locate the user form the browser
        function locateMe() {
            navigator.geolocation.getCurrentPosition(function (position) {

                var pos = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);

                marker = new google.maps.Marker({
                    position: pos,
                    draggable: true,
                    animation: google.maps.Animation.DROP,
                    map: map,
                    label: "YOU ARE HERE",
                    type: 'myproblem',
                    preserveViewport: true
                });

                markers.push(marker);
                document.getElementById('lat').value = position.coords.latitude;
                document.getElementById('lng').value = position.coords.longitude;

            })
        }

        locateMe();
        //add listener to have user place problem marker. .
        google.maps.event.addListener(map, 'click', function (event) {
            grabCoords(event);
            //this one opens the info when you click a marker
            google.maps.event.addListener(marker, 'click', function () {
                infowindow.open(map, marker);
            });

        });

    }

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

    function doNothing() {
    }

</script>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCLpwvDNIXNMVz7GRsggZOfRMDGQE-pdPE&callback=initMap">
</script>
<!--END Main area -->

</body>

</html>