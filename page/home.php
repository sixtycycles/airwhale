<?php
session_start();
require_once '../php/class.user.php';
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

<!-- Main area -->
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
                <!-- This renders the filter buttons from problem types in DB -->
                <?php
                if ($_SESSION['userSession']) {
                    $query = "SELECT id, Problems.type_id, type_name, COUNT(Problems.type_id)
                            FROM Problems, tbl_problem_types
                            WHERE (Problems.type_id=tbl_problem_types.type_id)
                            GROUP BY Problems.type_id ASC
                            ;";

                    $stmt = $user_home->runQuery($query);
                    $stmt->execute();

                    echo "<div class='form-group'>";
                    echo "<h4>Filters</h4>";

                    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {

                        echo '<button 
                            name=\'filters_params\' 
                            class=\'btn btn-primary\' 
                            onclick=\'hide(' . '"' . $row['type_id'] . '"' . ')\' 
                            id=\'' . $row['type_id'] . '\'> ' .
                            $row['type_name'] .
                            " <span class='badge'> " . $row['COUNT(Problems.type_id)'] . " </span>" . ' 
                        </button> ';

                    };
                    echo "</div>";

                    mysqli_close($connection);

                }
                ?>
            </div>
            <!-- END problem Filter area-->
        </div>

        <!-- problem entry area-->
        <div class="col-md-4 col-lg-4">

            <div id="form" class="panel panel-default">
                <div class="panel-heading"><h4>Report a problem</h4></div>
                <div class="panel-body">
                    <form method="post" action="../php/phpsqlinfo_addrow.php" enctype="multipart/form-data">
                        <div class="form-group">

                            <div class="form-group">
                                <label for="name">Your Name</label>
                                <input class="form-control" readonly type='text' id='name' name='name' placeholder="Your Name"
                                    value="<?php echo $row1['userName']; ?>" autocomplete="off"/>
                            </div>

                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input class="form-control" readonly type="email" id="email" name='email'
                                    placeholder="Email" value="<?php echo $row1['userEmail']; ?>" autocomplete="off"/>
                            </div>

                            <div class="form-group">
                            <label for="type">Problem Type</label>
                                <select class="form-control" id='type' name='type'>
                                    <?php
                                        $query = "SELECT * FROM tbl_problem_types;";
                                        $stmt = $user_home->runQuery($query);
                                        $stmt->execute();

                                        // Make each row in the "problem type" dropdown
                                        $index = 0;
                                        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                                            $id = $row['type_id'];
                                            $name = $row['type_name'];
                                            $selected = $index == 0 ? "SELECTED" : ""; // Select the first row
                                            echo "<option ${selected} value='${id}'>${name}</option>";
                                            $index = $index + 1;
                                        }

                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="description">Description of Problem</label>
                                <input class="form-control" type='text' id='description' name='description'
                                       autocomplete="off" style="resize: vertical; overflow: auto;"/>
                            </div>

                            <h4>Picture of the Issue (optional)</h4>
                            <input type="file" accept="image/*" class="btn btn-sm form-control-file" aria-describedby="fileHelp" name="file"/>

                            <h4>Coordinates</h4>
                            <p>(or click the map where the problem is)</p>
                            <div class="form-group">
                                <label for="lat" class="sr-only">Latitude</label>
                                <input class="form-control" type="text" name="lat" id="lat" placeholder="Latitude" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label for="lng" class="sr-only">Longitude</label>
                                <input class="form-control" type="text" name="lng" id="lng" placeholder="Longitude" autocomplete="off">
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

    var eventMarker;

    // show all markers of a particular category, styles buttons
    function show(category) {
        for (var i = 0; i < markers.length; i++) {
            if (markers[i].type === category) {
                markers[i].setMap(map);
            }

        }
        // change buttons and onclick functions to the right one after click.
        document.getElementById(category).setAttribute('class', 'btn btn-primary');
        document.getElementById(category).setAttribute('onclick', "hide('" + category + "')");
    }

    // hides all markers of a particular category, styles buttons
    function hide(category) {
        for (var i = 0; i < markers.length; i++) {
            if (markers[i].type === category) {
                markers[i].setMap(null);
            }
        }
        //change buttons and onclick functions to the right one after click.
        document.getElementById(category).setAttribute('class', 'btn btn-default');
        document.getElementById(category).setAttribute('onclick', "show('" + category + "')");

    }

    function updateLikes(id) {
        $.ajax({
            type:"GET",
            url: '../php/second.php',
            data: {
                id:id
            },
            success: function(response){
                // process on data
                window.likesField.SetText(response);
                //document.getElementById(id).setAttribute('style','visibility:hidden;');

            }

        });

    }

    /**
     * Makes the "you are here" marker if it doesn't exist, then moves it.
     */
    function SetEventMarkerPos(latLng){
        if(!eventMarker){
            eventMarker = new google.maps.Marker({
                position: latLng,
                map: map,
                animation: google.maps.Animation.DROP,
                draggable: true

            });
            eventMarker.addListener('click', function () {
                infowindow.setContent("Your problem's location");
                infowindow.open(map, eventMarker);
            });
            google.maps.event.addListener(eventMarker, 'dragend', function(event){
                document.getElementById('lat').value = event.latLng.lat();
                document.getElementById('lng').value = event.latLng.lng();
            });
        }

        eventMarker.setPosition(latLng);
        //map.panTo(latLng);

    }

    //draw map, import xml data of problems and add markers and info to infoindows.
    function initMap() {
        var oronoMaine = {lat: 44.882390656052756, lng: -68.71810913085938};

        //the map where we draw things and interact.
        map = new google.maps.Map(document.getElementById('map'), {
            center: oronoMaine,
            zoom: 13,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });
        infowindow = new google.maps.InfoWindow({content: ""});
        messagewindow = new google.maps.InfoWindow({content: ""});

        map.data.loadGeoJson('../assets/GIS/OronoBoundary.geojson');

        // Set the style properties for polygons in the map
        map.data.setStyle({
          strokeWeight: 1
        });

        // Road KML layer
        roads = new google.maps.KmlLayer({
            url: 'http://sixtycycles.github.io/CPR_KML/Orono_Roads_wgs84.kmz',
            map: map,
            preserveViewport: true,
            clickable: false

        });

        // this guy grabs the xml file from the db, and adds all the problems to the markers array
        downloadUrl('../php/dump.php', function (data) {
            var xml = data.responseXML;
            var downloadedMarkers = xml.documentElement.getElementsByTagName('marker');

            Array.prototype.forEach.call(downloadedMarkers, function (markerElem) {
                var point = new google.maps.LatLng(
                    parseFloat(markerElem.getAttribute('lat')),
                    parseFloat(markerElem.getAttribute('lng')));
                var type = markerElem.getAttribute('type_id');

                var iconBase = '../assets/icons/png/';

                var icon = {
                    url: iconBase + (markerElem.getAttribute('markerImage') || 'alert.png'), // url
                    scaledSize: new google.maps.Size(25, 25), // scaled size
                    // origin: new google.maps.Point(0, 0), // origin
                    // anchor: new google.maps.Point(0, 0) // anchor
                };

                // we add the property type to the marker object to filter by problem type later
                var newMarker = new google.maps.Marker({
                    map: map,
                    position: point,
                    icon: icon,
                    type: type
                });
                markers.push(newMarker);
                // opens infowin when clicking marker.
                newMarker.addListener('click', function () {
                    infowindow.open(map, newMarker);
                    infowindow.setContent(MakeMarkerWindow(markerElem));

                });

            });
        });

        /**
         * Creates a centering button for the specified map
         * inside of the given div.
         */
        function CenterControl(controlDiv, map) {

            // Set CSS for the control border.
            var controlUI = document.createElement('div');
            controlUI.style.backgroundColor = '#fff';
            controlUI.style.border = '2px solid #fff';
            controlUI.style.borderRadius = '3px';
            controlUI.style.boxShadow = '0 1px 3px rgba(0,0,0,.3)';
            controlUI.style.cursor = 'pointer';
            controlUI.style.margin = '11px 11px';

            controlUI.style.textAlign = 'center';
            controlUI.title = 'Click to recenter the map';
            controlDiv.appendChild(controlUI);

            // Set CSS for the control interior.
            var controlText = document.createElement('div');
            controlText.style.color = 'rgb(25,25,25)';
            controlText.style.fontFamily = 'Roboto,Arial,sans-serif';
            controlText.style.fontSize = '12px';
            controlText.style.lineHeight = '24px';
            controlText.style.paddingLeft = '10px';
            controlText.style.paddingRight = '10px';
            controlText.innerHTML = 'Center';
            controlUI.appendChild(controlText);

            // Setup the click event listeners: simply set the map to Chicago.
            controlUI.addEventListener('click', function() {
                map.setCenter(oronoMaine);
            });

        }

        // Add a "Center" button to top right of the map.
        var centerControlDiv = document.createElement('div');
        var centerControl = new CenterControl(centerControlDiv, map);
        centerControlDiv.index = 1;
        map.controls[google.maps.ControlPosition.TOP_RIGHT].push(centerControlDiv);

        // Add click listener to map to allow the user to place their location manually
        google.maps.event.addListener(map, 'click', function (event) {
            // Close the infowindow if it's up
            infowindow.close();

            // Set fields in form
            document.getElementById('lat').value = event.latLng.lat();
            document.getElementById('lng').value = event.latLng.lng();

            SetEventMarkerPos(event.latLng);
        });

        // Locate the user from the browser
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                // Success callback
                function (position) {
                    var pos = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);

                    document.getElementById('lat').value = position.coords.latitude;
                    document.getElementById('lng').value = position.coords.longitude;

                    SetEventMarkerPos(pos);

                }, 
                // Failure callback
                function(error){
                    console.warn("ERROR(" + error.code + "): "+ error.message );
                },
                // Options
                {
                    maximumAge:5000, timeout:5000, enableHighAccuracy:true
                }
            );

        } else {
            console.log("Geolocation is not supported.");
        }

        $("#lat").on('input', function(){
            var latValue = document.getElementById('lat').value;
            var lngValue = document.getElementById('lng').value;
            SetEventMarkerPos(new google.maps.LatLng(latValue, lngValue));
        });
        $("#lng").on('input', function(){
            var latValue = document.getElementById('lat').value;
            var lngValue = document.getElementById('lng').value;
            SetEventMarkerPos(new google.maps.LatLng(latValue, lngValue));
        });

    }

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
        
        var infowincontent = document.createElement('div');
        //infowincontent.setAttribute('class', 'well');
        infowincontent.setAttribute('style', 'width:200px; height:auto; padding-right: 2em;');

        // Little class for making labels that can be restyled.
        function LabelField(label, text){
            this.label = label;
            this.text = text;

            this.element = document.createElement('text');
            infowincontent.appendChild(this.element);
            infowincontent.appendChild(document.createElement('br'));

            this.Update();
        }
        LabelField.prototype.Update = function() {
            this.element.innerHTML = "<strong>" + this.label + "</strong>: " + this.text;
        }
        LabelField.prototype.SetLabel = function(label) {
            this.label = label;
            this.Update();
        }
        LabelField.prototype.SetText = function(text) {
            this.text = text;
            this.Update();
        }

        function Divider() {
            var divider = document.createElement('hr');
            divider.style.margin = "0.5em 0em";
            divider.style.padding = "0";
            infowincontent.appendChild(divider);
        }

        var typeField = new LabelField("Problem Type", type);
        var statusField = new LabelField("Status", status);
        var descriptionField = new LabelField("Problem Description", "<br /> " + description);

        Divider();

        var userField = new LabelField("User", name);
        var timeField = new LabelField("Time", timestamp);

        Divider();

        window.likesField = new LabelField("Likes", likes);

        infowincontent.appendChild(document.createElement('br'));

        var likeButton = document.createElement('button');
        likeButton.setAttribute('class', 'btn btn-sm btn-default');
        likeButton.setAttribute('id', id);
        likeButton.setAttribute('name','like_button');
        likeButton.setAttribute('type', 'submit');
        likeButton.innerHTML = "Second this problem";
        likeButton.setAttribute('onclick','updateLikes('+id+')');

        infowincontent.appendChild(likeButton);
        infowincontent.appendChild(document.createElement('br'));
        infowincontent.appendChild(document.createElement('br'));

        // Append the image file only if we have one specified.
        if(imageFile){
            var problemImage = document.createElement('image');
            problemImage.innerHTML = '<img alt="Image of the problem" class="img-fluid img-thumbnail" style="width: 100%; " src="../auth/uploads/' + imageFile + '" /> ';
            infowincontent.appendChild(problemImage);
            infowincontent.appendChild(document.createElement('br'));
        }

        return infowincontent;
    }

    // for grabbing xml
    function downloadUrl(url, callback) {
        var request = window.ActiveXObject ?
            new ActiveXObject('Microsoft.XMLHTTP') :
            new XMLHttpRequest;

        request.onreadystatechange = function () {
            if (request.readyState === 4) {
                request.onreadystatechange = function() {} ;
                callback(request, request.status);
            }
        };

        request.open('GET', url, true);
        request.send(null);
    }

</script>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCLpwvDNIXNMVz7GRsggZOfRMDGQE-pdPE&callback=initMap">
</script>
<!--END Main area -->

</body>

</html>