@include('includes/header')
<style>
    .image-wrapper {
        position: relative;
        display: inline-block;
        /* Ensures the div only takes up the space it needs */
    }

    .status-dot {
        position: absolute;
        top: 0;
        /* Position at the top of the image */
        left: 0;
        /* Position at the left of the image */
        width: 12px;
        /* Adjust the width as needed */
        height: 12px;
        /* Adjust the height as needed */
        border-radius: 50%;
        /* Creates a circular dot */
        margin-top: -5px;
        margin-left: -5px;
        /* Adds some space between the dot and the image */
    }

    .user-list {
        list-style: none;
        padding: 0;
        height: 70vh;
        overflow-y: scroll;
    }

    .user-item {
        display: flex;
        align-items: center;
        border-top: 1px solid #ccc;
        padding: 10px;
        cursor: pointer;
        /* Add pointer cursor */
        transition: background-color 0.3s;
        /* Add a smooth transition for background color */
    }

    .user-item:hover {
        background-color: #dbdbdb;
        /* Change background color on hover */
    }

    /* Apply a different style when the item is clicked */
    .user-item.active {
        background-color: #dcdcdc;
        /* Change background color on click */
        /* color: #000000; Change text color on click */
    }


    .user-profile {
        margin-right: 10px;
    }

    .user-profile img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
    }

    .user-details {
        flex: 1;
    }

    .user-name {
        font-weight: bold;
    }

    .user-number {
        color: #777;
    }



    .user-profile {
        position: relative;
        display: inline-block;
    }

    .status-dot {
        position: absolute;
        top: 8px;
        left: 8px;
        width: 10px;
        /* Adjust the size as needed */
        height: 10px;
        /* Adjust the size as needed */
        border-radius: 50%;
    }

    .online {
        background-color: lime;
        /* Set the online status color */
    }

    .offline {
        background-color: red;
        /* Set the offline status color */
    }
</style>
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> --}}


{{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" /> --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css"
    rel="stylesheet" />
<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Entry-->
    <div>
        <!--begin::Container-->
        <div class="card card-custom m-4">
            <div class="p-5">
                <div class="row">
                    <div class="col-md-3">
                        <div class="card card-custom " style="height:80vh;box-shadow: inset 1px 1px 10px 1px #c9c9c9;">
                            <div class="card-body py-2">
                                <div class="row"> 
                                    <div class="form-group col-md-12 counter-mirror">
                                        <h3 class="text-center py-2">{{__('messages.drivers')}}</h3>
                                        {{-- <button class="btn btn-primary w-100" onclick="showAll()">Show All</button> --}}
                                        <input type="text" id="searchInput" class="form-control mb-2"
                                            placeholder="{{__('messages.search_by_name_or_phone_number')}}" style="border:none">
                                        <ul class="user-list">
                                            @foreach ($drivers as $value)
                                                <li id="{{ 'USER' . $value->device_id }}"
                                                    class="user-item {{ isset($service->id) && $service->driver->device_id == $value->device_id ? 'active' : '' }}"
                                                    data-name="{{ $value->name }}" device_id="{{ $value->device_id }}"
                                                    data-phone="{{ $value->phone }}">
                                                    <div class="user-profile">
                                                        {{-- <span
                                                            class="status-dot {{ $value->online === 1 ? 'online' : 'offline' }}"></span> --}}
                                                        <img src="{{ $value->avatar }}" alt="Profile Image"
                                                            class="user-avatar">
                                                    </div>
                                                    <div class="user-details row">
                                                        <div class="col-md-10">
                                                            <p class="user-name">{{ $value->name }}</p>
                                                            <p class="user-number">{{ $value->phone }}</p>

                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="dropdown dropdown-inline">
                                                                <a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    <i class="ki ki-bold-more-hor"></i>
                                                                </a>
                                                                <div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
                                                                    <!--begin::Naviigation-->
                                                                    <ul class="navi">
                                                                        @foreach ($value->trips as $trip)
                                                                        <li class="navi-item">
                                                                            <a href="{{ url('playback/index',$trip->id) }}" class="navi-link">
                                                                                <span class="navi-icon">
                                                                                    <img src="{{ asset('assets/media/svg/icons/Navigation/Route.svg') }}"/>
                                                                                </span>
                                                                                <span class="navi-text">{{ $trip->unique_id . ' - ' . $trip->event_name }}</span>
                                                                            </a>
                                                                        </li>
                                                                        @endforeach
                                                                    </ul>
                                                                    <!--end::Naviigation-->
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach


                                        </ul>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-9 counter-mirror">
                        <div class="card card-custom" style="box-shadow: inset 1px 1px 10px 1px #c9c9c9;">
                            <div class="card-body p-5">
                                <div class="row px-5">
                                    {{-- <div class="col-xl-4" style="margin-bottom: -25px;">
                                        <!--begin::Tiles Widget 12-->

                                        <!--end::Tiles Widget 12-->
                                    </div> --}}

                                    <input type="hidden"
                                        value="{{ isset($service->id) ? $service->driver->device_id : '' }}"
                                        id="device_id">

                                    <label for="" style="margin-top: 12px;">From</label>
                                    <input type="datetime-local" class="col-md-3 m-1 form-control" id="from"
                                        value="{{ isset($service->id) ? date('Y-m-d H:i:s', strtotime($service->started_at)) : date('Y-m-d H:i:s', strtotime('-12 hours')) }}">
                                    <label for="" style="margin-top: 12px;">To</label>
                                    <input type="datetime-local" class="col-md-3 m-1 form-control" id="to"
                                        value="{{ isset($service->id) ? date('Y-m-d H:i:s', strtotime($service->completed_at)) : date('Y-m-d H:i:s', strtotime('now')) }}">

                                    <button class="m-1 btn btn-primary text-light" id="fetchAndPlayButton"
                                        style="width: 130px;" onclick="fetchPositionsAndPlay()">&nbsp;{{__('messages.search')}}</button>
                                    <span style="padding-top: 15px;"><i style="display: none" id="spinner"
                                            class="fas fa-spinner fa-spin"></i></span>
                                    <div class="col-md-1">
                                        <select id="playback_speed" class="form-control" style="display: none">

                                            <option value="500">&times;1</option>
                                            <option value="300">&times;2</option>
                                            <option value="150" selected>&times;3</option>
                                            <option value="70">&times;4</option>
                                            <option value="30">&times;5</option>
                                        </select>
                                    </div>
                                    <button class="m-1 btn btn-warning" id="backwardButton" style="display: none"
                                        onclick="backward()"> <i class="fas fa-fast-backward"></i></button>
                                    <button class="m-1 btn btn-success" id="playButton" style="display: none"
                                        onclick="playPlayback()"> <i class="fas fa-play"></i></button>
                                    <button class="m-1 btn btn-danger" id="pauseButton" style="display: none"
                                        onclick="pausePlayback()"> <i class="fas fa-pause"></i></button>
                                    <button class="m-1 btn btn-warning" id="fastForwardButton" style="display: none"
                                        onclick="fastForward()">
                                        <i class="fas fa-fast-forward"></i></button>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <button class="m-1 btn btn-secondary" id="replayButton" style="display: none"
                                        onclick="replayPlayback()"> <i class="fas fa-redo"></i></button>



                                </div>
                            </div>
                        </div>
                        <div class="mt-5 row">
                            <button class="m-auto btn col-md-5 btn-secondary" style="height: 40px;" id="speed">-
                            </button>
                            {{-- <button class="m-auto btn col-md-5 btn-secondary" style="height: 40px;white-space: nowrap;overflow: hidden;text-overflow: ellipsis;"
                                id="address">-</button> --}}
                            <button class="m-auto btn col-md-5 btn-secondary" style="height: 40px;" id="time"> -
                            </button>
                        </div>
                        <div class="card card-custom  my-5" style="box-shadow: inset 1px 1px 10px 1px #c9c9c9;">
                            <div class="card-body p-5">
                                <div id="map" style="height: 60vh"></div>
                            </div>
                            {{-- <div class="card-footer">
                        </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Container-->
    </div>
    <!--end::Entry-->
</div>

<!--end::Content-->
@include('includes/footer')

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBCgMkgjHVW3WL4GD4M6FdLar-tjlIT8aU"></script>

<script>
    $(".map-nav").click()
    $(".playback-nav").addClass("menu-item-active");

    var service = null
    $(document).ready(function() {

        @if (isset($service->id))
            document.getElementById("fetchAndPlayButton").click()
            service = {!! json_encode($service) !!}
        @endif

        $("#searchInput").on("keyup", function() {
            var searchText = $(this).val().toLowerCase();

            $(".user-item").each(function() {
                var userName = $(this).find(".user-name").text().toLowerCase();
                var userNumber = $(this).find(".user-number").text().toLowerCase();

                if (userName.indexOf(searchText) > -1 || userNumber.indexOf(searchText) > -1) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

    });


    $(document).on('click', '.user-item', function() {
        service == null
        $(".user-item").removeClass("active");
        $(this).addClass("active");
        var device_id = $(this).attr('device_id');
        $("#device_id").val(device_id);
        // document.getElementById("spinner").style.display = "none";
        document.getElementById('playButton').style.display = "none";
        document.getElementById('replayButton').style.display = "none";
        document.getElementById('backwardButton').style.display = "none";
        document.getElementById('fastForwardButton').style.display = "none";
        document.getElementById('pauseButton').style.display = "none";
        // document.getElementById('fetchAndPlayButton').disabled = true;
        resetMapAndPlayback();

    });
    //    var fastForwardInterval = null; // Initialize to null
    // var backwardInterval = null; // Initialize to null

    // function startFastForward() {
    //     if (fastForwardInterval === null) { // Check if the interval is not already active
    //         fastForward(); // Call the function initially
    //         fastForwardInterval = setInterval(fastForward, 100); // Adjust the interval as needed
    //     }
    // }

    // function stopFastForward() {
    //     clearInterval(fastForwardInterval);
    //     fastForwardInterval = null; // Reset the interval variable
    // }

    // function startBackward() {
    //     if (backwardInterval === null) { // Check if the interval is not already active
    //         backward(); // Call the function initially
    //         backwardInterval = setInterval(backward, 100); // Adjust the interval as needed
    //     }
    // }

    // function stopBackward() {
    //     clearInterval(backwardInterval);
    //     backwardInterval = null; // Reset the interval variable
    // }


    var googleMap;
    var positions = [];
    var marker;
    var polyline;
    var currentPosition = 0;
    var playbackInterval;
    var playbackSpeed = 150; // Playback speed in milliseconds
    var playbackStarted = false; // Flag to track playback status
    var trips = [];
    var stops = [];
    var flags = [];

    $(document).on('change', '#playback_speed', function() {
        playbackSpeed = $(this).val()
    });

    function initializeMap() {
        googleMap = new google.maps.Map(document.getElementById('map'), {
            center: {
                lat: 50.000000,
                lng: -85.000000
            },
            zoom: 6
        });

        const trafficLayer = new google.maps.TrafficLayer();
        trafficLayer.setMap(googleMap);


        marker = new google.maps.Marker({
            map: googleMap
        });

        polyline = new google.maps.Polyline({
            map: googleMap,
            path: [], // Initialize the path with an empty array
            strokeColor: '#0000FF', // Trail color
            strokeOpacity: 0.7, // Trail opacity
            strokeWeight: 4 // Trail thickness
        });
    }


    var flagIcon = {
        url: '{{ asset('assets/media/svg/icons/Communication/Flag.svg') }}', // Replace with the path to your flag icon image
        scaledSize: new google.maps.Size(40, 40) // Adjust the size as needed
    };


    function playPlayback() {
        if (!playbackStarted) {
            playbackStarted = true;
            document.getElementById('playButton').style.display = 'none';
            document.getElementById('pauseButton').style.display = 'block';
            playbackInterval = setInterval(function() {
                if (currentPosition < positions.length) {
                    var position = positions[currentPosition];
                    marker.setPosition(position);
                    addPositionToPolyline(position);
                    panToLocation(position);
                    if (currentPosition == 0) {
                        // // Assuming you have already created a marker and map
                        // var flag = new google.maps.Marker({
                        //     position: position,
                        //     map: googleMap,
                        //     icon: flagIcon
                        // });
                        // var infoWindow = new google.maps.InfoWindow();

                        // // Data to display in the info window

                        // if (service == null) {
                        //     var infoContent = '<strong>Stop #:</strong> ' + (trips[currentPosition]['trip_no'] +
                        //             1) +
                        //         '<br>' + trips[currentPosition]['date_time']

                        // } else {
                        //     var infoContent = '<strong>Trip Started :</strong> ' +
                        //         '<br>' + trips[currentPosition]['date_time']

                        // }

                        // infoWindow.setContent(infoContent);
                        // google.maps.event.addListener(flag, 'click', function() {
                        //     infoWindow.open(googleMap, flag);
                        // });
                        // flags.push(flag)

                    }
                    if (currentPosition > 0 && trips[currentPosition]['trip_no'] != trips[currentPosition - 1][
                            'trip_no'
                        ]) {
                        // Assuming you have already created a marker and map
                        var flag = new google.maps.Marker({
                            position: position,
                            map: googleMap,
                            icon: flagIcon
                        });
                        var infoWindow = new google.maps.InfoWindow();

                        var infoContent = '<strong>Stop #:</strong> ' + (trips[currentPosition]['trip_no'] +
                                1) +
                            '<br>' +
                            '<strong>Trip Duration: </strong> ' + trips[currentPosition]['duration'] +
                            '<br>' +
                            '<strong>Updated Time: </strong> ' + trips[currentPosition]['updatedTime'];

                        infoWindow.setContent(infoContent);
                        google.maps.event.addListener(flag, 'click', function() {
                            infoWindow.open(googleMap, flag);
                        });
                        flags.push(flag)
                    }
                    if (currentPosition == (trips.length - 1)) {
                        // Assuming you have already created a marker and map
                        var flag = new google.maps.Marker({
                            position: position,
                            map: googleMap,
                            icon: flagIcon
                        });
                        var infoWindow = new google.maps.InfoWindow();

                        if (service == null) {

                            var infoContent = '<strong>Stop #:</strong> ' + (trips[currentPosition]['trip_no'] +
                                    1) +
                                '<br>' +
                                '<strong>Trip Duration: </strong> ' + trips[currentPosition]['duration'] +
                                '<br>' +
                                '<strong>Updated Time: </strong> ' + trips[currentPosition]['updatedTime'];
                        } else {
                            var infoContent = '<strong>Trip Ended:</strong>' +
                                '<br>' +
                                '<strong>Trip Duration: </strong> ' + trips[currentPosition]['duration'] +
                                '<br>' +
                                '<strong>Updated Time: </strong> ' + trips[currentPosition]['updatedTime'];
                        }
                        infoWindow.setContent(infoContent);
                        google.maps.event.addListener(flag, 'click', function() {
                            infoWindow.open(googleMap, flag);
                        });
                        flags.push(flag)
                    }
                    $("#speed").html("<strong>Speed: </strong>" + (trips[currentPosition]['speed'] * 3.6)
                        .toFixed(1) + " kph")
                    // $("#address").html("<strong>Adress: </strong>" + trips[currentPosition]['address'])
                    $("#time").html("<strong>Time: </strong>" + trips[currentPosition]['updatedTime'])
                    currentPosition++;
                } else {
                    clearInterval(playbackInterval);
                    playbackStarted = false;
                    document.getElementById('playButton').style.display = "none";
                    document.getElementById('pauseButton').style.display = "none";
                }
            }, playbackSpeed);
        }
    }

    function pausePlayback() {
        clearInterval(playbackInterval);
        playbackStarted = false;
        document.getElementById('playButton').style.display = "block";
        document.getElementById('pauseButton').style.display = "none";
    }

    function replayPlayback() {
        flags.forEach(element => {
            element.setMap(null)
        });
        flags = []
        currentPosition = 0;
        playbackStarted = false;
        resetPolyline();
        addStops()
        if (document.getElementById('playButton').style.display == "none" && document.getElementById('pauseButton')
            .style.display == "none") {
            document.getElementById('playButton').style.display = "block"
        }
    }

    function fastForward() {
        currentPosition += 20;
        if (currentPosition >= positions.length) {
            currentPosition = positions.length - 1;
        }
        var position = positions[currentPosition];
        marker.setPosition(position);
        panToLocation(position);

        updatePolylinePath(currentPosition);

        $("#speed").html("<strong>Speed: </strong>" + (trips[currentPosition]['speed'] * 3.6)
            .toFixed(1) + " kph")
        // $("#address").html("<strong>Adress: </strong>" + trips[currentPosition]['address'])
        $("#time").html("<strong>Time: </strong>" + trips[currentPosition]['updatedTime'])
    }

    function backward() {
        currentPosition -= 20;
        if (currentPosition < 0) {
            currentPosition = 0;
        }
        var position = positions[currentPosition];
        marker.setPosition(position);
        panToLocation(position);

        updatePolylinePath(currentPosition);

        $("#speed").html("<strong>Speed: </strong>" + (trips[currentPosition]['speed'] * 3.6)
            .toFixed(1) + " kph")
        // $("#address").html("<strong>Adress: </strong>" + trips[currentPosition]['address'])
        $("#time").html("<strong>Time: </strong>" + trips[currentPosition]['updatedTime'])
    }

    function updatePolylinePath(currentPosition) {
        polyline.setPath([]);
        var newPath = positions.slice(0, currentPosition + 1);
        polyline.setPath(newPath);
    }


    function resetMapAndPlayback() {
        flags.forEach(element => {
            element.setMap(null)
        });
        flags = []
        currentPosition = 0;
        clearInterval(playbackInterval);
        resetPolyline();
    }

    function panToLocation(position) {
        googleMap.panTo(position);
    }

    function addPositionToPolyline(position) {
        var path = polyline.getPath();
        path.push(position);
        polyline.setPath(path);
    }

    function resetPolyline() {
        var path = polyline.getPath();
        path.clear();
    }

    function addStops() {
        for (var i = 0; i < stops.length; i++) {
            var stop = stops[i];

            // Create a marker for the stop
            var flag = new google.maps.Marker({
                position: new google.maps.LatLng(stop.latitude, stop.longitude),
                map: googleMap,
                icon: flagIcon
            });

            // Create an info window for the marker
            var infoWindow = new google.maps.InfoWindow();

            // Create content for the info window
            var infoContent = '<strong>Stop #:</strong> ' + (i + 1) +
                '<br>' +
                '<strong>Stop Duration: </strong> ' + stop.duration +
                '<br>' +
                '<strong>Stop Start Time: </strong> ' + stop.startTime;
            '<br>' +
            '<strong>Stop End Time: </strong> ' + stop.endTime;

            infoWindow.setContent(infoContent);

            // Add a click event listener to open the info window
            google.maps.event.addListener(flag, 'click', function() {
                infoWindow.open(googleMap, flag);
            });

            // Push the flag marker to an array (if needed)
            flags.push(flag);
        }
    }

    function fetchPositionsAndPlay() {
        $("#speed").html('')
        // $("#address").html('')
        $("#time").html('')
        if ($("#device_id").val() == "") {
            toastr.error("Please select a driver first")
            return
        }
        document.getElementById("spinner").style.display = "block";
        document.getElementById('playButton').style.display = "none";
        document.getElementById('replayButton').style.display = "none";
        document.getElementById('backwardButton').style.display = "none";
        document.getElementById('fastForwardButton').style.display = "none";
        document.getElementById('pauseButton').style.display = "none";
        document.getElementById('playback_speed').style.display = "none";
        document.getElementById('fetchAndPlayButton').disabled = true;
        resetMapAndPlayback();
        $.ajax({
            url: "{{ url('playback/history') }}" + "/" + $("#device_id").val() + "/" + $("#from").val() + "/" +
                $("#to").val(),
            method: "GET",
            success: function(data) {
                document.getElementById("spinner").style.display = "none";
                if (data.latlong.length > 0) {
                    trips = data.latlong
                    console.log(data);
                    document.getElementById('playback_speed').style.display = "block";
                    document.getElementById('playButton').style.display = "block";
                    document.getElementById('replayButton').style.display = "block";
                    document.getElementById('backwardButton').style.display = "block";
                    document.getElementById('fastForwardButton').style.display = "block";
                    document.getElementById('fetchAndPlayButton').disabled = false;
                    positions = latlongs(data.latlong)
                    marker.setPosition(positions[0]);
                    googleMap.setCenter(positions[0]);
                    googleMap.setZoom(17);
                    panToLocation(positions[0]);
                    stops = data.stops
                    addStops()
                } else {
                    document.getElementById('fetchAndPlayButton').disabled = false;
                    toastr.warning("No data found");
                }
            }
        })
    }

    function latlongs(positions) {
        var path = [];
        for (var i = 0; i < positions.length; i++) {
            var lat = positions[i]['latitude'];
            var lng = positions[i]['longitude'];
            path.push(new google.maps.LatLng(lat, lng));
        }
        return path;
    }

    initializeMap();
</script>
