<!DOCTYPE html>
<html lang="en">

<head>
    <base href="">
    <meta charset="utf-8" />
    <title>Ride WITH Passenger | Dashboard</title>
    <meta name="description" content="Updates and statistics" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <link href="{{ asset('assets/plugins/custom/fullcalendar/fullcalendar.bundle.css?v=7.0.5') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css?v=7.0.5') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/custom/prismjs/prismjs.bundle.css?v=7.0.5') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('assets/css/style.bundle.css?v=7.0.5') }}" rel="stylesheet" type="text/css" />
    <link rel="shortcut icon" href="{{ asset('assets/ridewithpassngers.png') }}" />
    <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-messaging.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <style>
        .image-wrapper {
            position: relative;
            display: inline-block;
        }

        .status-dot {
            position: absolute;
            top: 0;
            left: 0;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-top: -5px;
            margin-left: -5px;
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
            transition: background-color 0.3s;
        }

        .user-item:hover {
            background-color: #dbdbdb;
        }

        .user-item.active {
            background-color: #dcdcdc;
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
            height: 10px;
            border-radius: 50%;
        }

        .online {
            background-color: lime;
        }

        .offline {
            background-color: red;
        }
    </style>
</head>

<body id="kt_body" class="header-fixed header-mobile-fixed page-loading">
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css"
        rel="stylesheet" />
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-custom" id="infoCard"
                        style="display:none;box-shadow: inset 1px 1px 10px 1px #c9c9c9;">
                        <div class="card-body p-5">
                            <div class="row">
                                @if (session('success'))
                                    <div class="alert alert-success m-2">
                                        {{ session('success') }}
                                        <button type="button" class="close counter-mirror" data-dismiss="alert"
                                            aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif
                                @if (session('error'))
                                    <div class="alert alert-danger m-2">
                                        {{ session('error') }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif
                                <div class="col-xl-12">

                                    <div class="text-dark font-weight-bolder my-2">
                                        <div class="row">
                                            <div class="col-md-1">
                                                <img alt="Logo" src="{{ asset('assets/ridewithpassngers.png') }}"
                                                    class="" height="75" width="150"
                                                    style="border-radius: 0.82rem" />

                                            </div>
                                            <div class="col-md-11">
                                                <div class="row">

                                                    @foreach ($link->trips as $trip)
                                                        <div class="col-md-2 p-3 text-center  font-weight-bolder @if ($trip->status == 'available') bg-secondary text-white @else bg-secondary text-dark @endif  m-1"
                                                            style="    border-radius: 25px;">
                                                            <a class=" @if ($trip->status == 'available') text-dark @else text-dark @endif"
                                                                @if ($trip->status == 'available' || $trip->status == 'completed') href="javascript:void(0)" onclick="toastr.error('Trip not available to track!')"
                                                                @else
                                                                href="{{ url('live/track/trip/' . $link->slug . '/' . $trip->id) }}" @endif>
                                                                {{ date('d M h:i a', strtotime($trip->pickup_date)) }}
                                                                @if ($trip->status == 'available')
                                                                    <span class="badge badge-danger">NOT
                                                                        STARTED</span>
                                                                @elseif($trip->status == 'completed')
                                                                    <span class="badge badge-success">COMPLETED</span>
                                                                @else
                                                                    <span class="badge badge-warning">STARTED</span>
                                                                @endif <br>
                                                                {{ $trip->unique_id }} - {{ $trip->event_name }}

                                                            </a>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">

                    <div class="card card-custom  my-5" style="box-shadow: inset 1px 1px 10px 1px #c9c9c9;">
                        <div class="card-body p-5">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="col-xl-12">
                                        <div class="card card-custom  gutter-b"
                                            style="box-shadow: inset 1px 1px 10px 1px #c9c9c9;">
                                            <div class="card-header">
                                                <div class="card-title">
                                                    <h4>Trip Details</h4>

                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <h4>Driver Details</h4>
                                                <div class="row">
                                                    <div class="col-md-6 text-dark font-weight-bolder mt-3"
                                                        id="driver_info">-
                                                    </div>
                                                    <div class="col-md-6 text-dark font-weight-bolder mt-3"
                                                        id="position_info">-
                                                    </div>
                                                </div>
                                                <br>
                                                <h4>Trip Details</h4>
                                                <div id="load_details">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-12">
                                        <div class="card card-custom  gutter-b"
                                            style="box-shadow: inset 1px 1px 10px 1px #c9c9c9;">
                                            <div class="card-body">
                                                <div id="estimated_times">

                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div id="map" style="height: 900px"></div>
                                </div>


                            </div>
                        </div>
                        {{-- <div class="card-footer">
                        </div> --}}
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
        let googleMap;
        let marker;
        let polyline;
        let interval;
        let showInterval;
        let firstCall;
        let markers = [];


        $(document).ready(function() {

            initMap();
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


            var navDevice = {!! json_encode($id) !!}
            if (navDevice != null && navDevice != 0) {
                var element = $("#USER" + navDevice);
                $(".user-item").removeClass("active");
                element.addClass("active");

                firstCall = true
                // document.getElementById("map").style.height = '1000px;'
                document.getElementById("infoCard").style.display = 'block'
                var name = '';
                var phone = '';
                name = "{{ $trip_details->driver->name }}";
                phone = "{{ $trip_details->driver->phone }}";


                // document.getElementById("time_info").innerHTML = '-'
                document.getElementById("position_info").innerHTML = '-'
                var timeInfoDiv = document.getElementById("driver_info");

                var table = "<table>";
                table += "<tr><td>Name: </td><td>" + name + "</td></tr>";
                table += "<tr><td>Phone: </td><td>" + phone + "</td></tr>";
                table += "</table>";

                timeInfoDiv.innerHTML = table;
                const selectedDriver = "{{ $id }}";
                if (interval) {
                    clearInterval(interval);
                    refreshMap();
                }
                ajaxCall(selectedDriver)
                startLiveTracking(selectedDriver);
            } else {
                showAllLocations()
            }

        });



        var svgContent = `<?xml version="1.0" encoding="UTF-8"?>
        <svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
            <!-- Generator: Sketch 50.2 (55047) - http://www.bohemiancoding.com/sketch -->
            <title>Stockholm-icons / Map / Marker2</title>
            <desc>Created with Sketch.</desc>
            <defs></defs>
            <g id="Stockholm-icons-/-Map-/-Marker2" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                <rect id="bound" x="0" y="0" width="48" height="48"></rect>
                <path d="M9.82829464,16.6565893 C7.02541569,15.7427556 5,13.1079084 5,10 C5,6.13400675 8.13400675,3 12,3 C15.8659932,3 19,6.13400675 19,10 C19,13.1079084 16.9745843,15.7427556 14.1717054,16.6565893 L12,21 L9.82829464,16.6565893 Z M12,12 C13.1045695,12 14,11.1045695 14,10 C14,8.8954305 13.1045695,8 12,8 C10.8954305,8 10,8.8954305 10,10 C10,11.1045695 10.8954305,12 12,12 Z" id="Combined-Shape" fill="#000000"></path>
            </g>
        </svg>`

        // Initialize the map
        function initMap() {
            googleMap = new google.maps.Map(document.getElementById('map'), {
                center: {
                    lat: 50.000000,
                    lng: -85.000000
                },
                zoom: 6,
                // mapTypeControl: true, 
                //     mapTypeControlOptions: {
                //         style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
                //         position: google.maps.ControlPosition.TOP_CENTER,
                //     },
            });

            const trafficLayer = new google.maps.TrafficLayer();
            trafficLayer.setMap(googleMap);

            // Initialize marker
            marker = new google.maps.Marker({
                map: googleMap,
                icon: {
                    url: 'data:image/svg+xml,' + encodeURIComponent(svgContent),
                    size: new google.maps.Size(24, 24) // Set the size
                }

            });

            // Initialize polyline for live track
            polyline = new google.maps.Polyline({
                map: googleMap,
                strokeColor: '#FF0000',
                strokeOpacity: 1.0,
                strokeWeight: 2
            });
        }

        // Function to update marker position
        function updateMarker(lat, lng, imageUrl) {
            if (firstCall) {
                firstCall = false;
                // Reinitialize marker and polyline
                const markerImage = new Image();
                markerImage.src = imageUrl; // Set the marker image URL
                markerImage.onload = function() {
                    // Once the image is loaded, create a canvas element to draw the rounded image
                    const canvas = document.createElement('canvas');
                    const context = canvas.getContext('2d');
                    canvas.width = 40; // Set the canvas width
                    canvas.height = 40; // Set the canvas height
                    context.beginPath();
                    context.arc(20, 20, 20, 0, Math.PI * 2); // Create a circle path
                    context.closePath();
                    context.clip(); // Clip the image to the circle path
                    context.drawImage(markerImage, 0, 0, 40, 40); // Draw the image onto the canvas


                    // Draw a border around the rounded marker image
                    context.strokeStyle = '#198a16cf'; // Set the border color
                    context.lineWidth = 3; // Set the border width
                    context.stroke(); // Draw the border
                    context.drawImage(markerImage, 0, 0, 40,
                        40); // Draw the image onto the canvas

                    const roundedMarkerImage = canvas.toDataURL(); // Convert the canvas content to a data URL

                    marker = new google.maps.Marker({
                        map: googleMap,
                        position: {
                            lat: lat,
                            lng: lng
                        },
                        icon: {
                            url: roundedMarkerImage,
                            scaledSize: new google.maps.Size(40, 40),
                        }
                    });

                    polyline = new google.maps.Polyline({
                        map: googleMap,
                        strokeColor: '#FF0000',
                        strokeOpacity: 1.0,
                        strokeWeight: 2
                    });

                    // Extend the bounds to include the marker's position
                    bounds.extend(marker.getPosition());

                    // Fit the map to the updated bounds
                    googleMap.fitBounds(bounds);
                };
            } else {
                marker.setPosition({
                    lat,
                    lng
                }, 14);

                // Extend the bounds to include the marker's position
                bounds.extend(marker.getPosition());

                // Fit the map to the updated bounds
                googleMap.fitBounds(bounds);
            }
        }


        // Function to start live tracking
        function startLiveTracking(driver) {
            interval = setInterval(function() {
                ajaxCall(driver)
            }, 5000); // Update every 5 seconds
        }


        var link = {!! json_encode($link) !!}

        function ajaxCall(driver) {
            $.ajax({
                url: "{{ url('live/track/trip') }}" + "/" + link.slug + "/" + driver,
                method: "GET",
                success: function(response) {
                    let data = response['position']
                    if (data && data.latitude && data.longitude) {

                        var positionInfoDiv = document.getElementById("position_info");
                        // var timeInfoDiv = document.getElementById("time_info");

                        var table = "<table>";
                        // for (var key in data) {
                        //     if (data.hasOwnProperty(key)) {
                        //     }
                        // }
                        table += "<tr><td>Speed: </td><td>" + (data.speed * 3.6).toFixed(1) +
                            " kph</td></tr>";
                        table += "<tr><td>Time: </td><td>" + data.serverTime + "</td></tr>";
                        // table += "<tr><td>Address: </td><td style='font-size:14px;'>" + data.address + "</td></tr>";
                        table += "</table>";

                        positionInfoDiv.innerHTML = table;


                        var tripInfoDiv = document.getElementById("load_details");
                        // var timeInfoDiv = document.getElementById("time_info");

                        var table = "<table class='table'><tbody>";
                        // for (var key in data) {
                        //     if (data.hasOwnProperty(key)) {
                        //     }
                        // }
                        table += "<tr><td colspan='2'><b>Customer Company:</b>" + (response['trip']
                                .customer_company) +
                            "</td></tr>";
                        table += "<tr><td><b>Customer Name:</b>" + (response['trip'].customer_name) +
                            " </td><td><b>Customer Phone:</b>" + (response['trip'].customer_phone) +
                            "</td></tr>";
                        table += "<tr><td><b>Pickup Date:</b>" + (response['trip'].pickup_date) +
                            " </td><td><b>Location:</b>" + (response['trip'].pickup_location) + "</td></tr>";
                        table += "<tr><td><b>Delivery Date :</b>" + (response['trip'].delivery_date) +
                            " </td><td><b>Location:</b>" + (response['trip'].delivery_location) + "</td></tr>";
                        table += "<tr><td><b>Event Name :</b>" + (response['trip'].event_name) +
                            " </td><td><b>Stops:</b>" + (response['trip'].stops.length) + "</td></tr>";
                        table += "<tr><td><b>Description :</b>" + (response['trip'].description) +
                            " </td><td><b>Status:</b>" + (response['trip'].status.toUpperCase()) + "</td></tr>";

                        table += "</tbody></table>";

                        tripInfoDiv.innerHTML = table;
                        // timeInfoDiv.textContent = data.serverTime;
                        updateMarker(data.latitude, data.longitude, response['driver'].avatar);
                        googleMap.setCenter(marker.getPosition());
                        const path = polyline.getPath();
                        path.push(new google.maps.LatLng(data.latitude, data.longitude));
                        googleMap.setCenter({
                            lat: data.latitude,
                            lng: data.longitude
                        });

                        var driverLat = data.latitude; // Driver's latitude
                        var driverLng = data.longitude; // Driver's longitude
                        var stops = response.trip.stops

                        calculateEstimatedTime(driverLat, driverLng, stops)
                            .then(function(estimatedTimes) {
                                // console.log('Estimated times:', estimatedTimes);
                                let html = '<table class="table table-striped"><tbody>'
                                if (estimatedTimes.length > 0) {
                                    estimatedTimes.forEach((element, index) => {
                                        let des
                                        console.log(element);
                                        if (element.trip_stop.datetime != null) {
                                            if (element.trip_stop.exit_time == null) {
                                                des =
                                                    `<span title="${element.stop}" class="text-warning">${element.stop.substring(0, 20)}... </span><b>Reached:${element.trip_stop.datetime}</b>`
                                            } else {
                                                des =
                                                    `<span title="${element.stop}" class="text-success">${element.stop.substring(0, 20)}... </span><b>${element.trip_stop.datetime} / ${element.trip_stop.exit_time}</b>`
                                            }
                                        } else {
                                            des = `<span title="${element.stop}">` + element.stop
                                                .substring(0, 20) +
                                                `... <b>ETA: ${element.estimatedTime}</b></span>`
                                        }
                                        html +=
                                            `<tr><th class="">${element.trip_stop.type.toUpperCase()}:</th><td>${des}</td></tr>`
                                    })
                                    html += '<tbody></table>'
                                    $("#estimated_times").html(html)
                                    console.log(html);
                                }
                            })
                            .catch(function(error) {
                                console.error('Error calculating estimated times:', error);
                            });



                    } else {
                        clearInterval(interval); // Clear previous interval
                        toastr.error("Driver data not found.");
                    }
                },
                error: function() {
                    clearInterval(interval); // Clear previous interval
                    toastr.error("Driver data not found")

                }
            });
        }





        $(document).on('click', '.user-item', function() {
            $(".user-item").removeClass("active");
            $(this).addClass("active");
            clearMarkers()

            document.getElementById("map").style.height = '60vh'
            document.getElementById("infoCard").style.display = 'block'
            clearInterval(showInterval);
            firstCall = true
            var name = '';
            var phone = '';

            name = "{{ $trip_details->driver->name }}";
            phone = "{{ $trip_details->driver->phone }}";

            // document.getElementById("time_info").innerHTML = '-'
            document.getElementById("position_info").innerHTML = '-'
            var timeInfoDiv = document.getElementById("driver_info");

            var table = "<table>";
            table += "<tr><td>Name: </td><td>" + name + "</td></tr>";
            table += "<tr><td>Phone: </td><td>" + phone + "</td></tr>";
            table += "</table>";

            // Render the table in the position_info div
            timeInfoDiv.innerHTML = table;

            const selectedDriver = $(this).attr('device_id');
            if (interval) {
                clearInterval(interval); // Clear previous interval
                refreshMap(); // Clear previous marker and polyline
            }
            ajaxCall(selectedDriver)
            startLiveTracking(selectedDriver);
        });

        function refreshMap() {
            marker.setMap(null);
            polyline.setMap(null);
            clearMarkers()
        }

        function clearMarkers() {
            driverMarkersMap.forEach(function(marker) {
                marker.setMap(null);
            });

            // Clear the markers map
            driverMarkersMap.clear();
        }




        function calculateDuration(start, end) {
            return new Promise(function(resolve, reject) {
                var request = {
                    origin: start,
                    destination: end,
                    travelMode: 'DRIVING'
                };

                var directionsService = new google.maps.DirectionsService();
                directionsService.route(request, function(result, status) {
                    if (status == 'OK') {
                        var route = result.routes[0];
                        var duration = route.legs[0].duration.text; // Duration in human-readable format
                        resolve(duration);
                    } else {
                        console.error('Error calculating duration:', status);
                        reject('Error'); // Pass error message if there's an error
                    }
                });
            });
        }

        let firstDirection = true


        async function calculateEstimatedTime(driverLat, driverLng, stops) {
            var estimatedTimes = [];
            for (let i = 0; i < stops.length; i++) {
                if (stops[i].datetime === null) {
                    try {
                        let duration = await calculateDuration(driverLat + ',' + driverLng, stops[i].lat + ',' + stops[
                            i].long);
                        let estimatedTime = duration;

                        // Draw route on the map
                        let request = {
                            origin: driverLat + ',' + driverLng,
                            destination: stops[i].lat + ',' + stops[i].long,
                            travelMode: 'DRIVING'
                        };
                        if (firstDirection == true) {
                            let directionsService = new google.maps.DirectionsService();
                            directionsService.route(request, function(result, status) {
                                if (status == 'OK') {
                                    let directionsRenderer = new google.maps.DirectionsRenderer({
                                        suppressMarkers: true // This option will suppress the default markers
                                    });
                                    directionsRenderer.setMap(
                                        googleMap); // Assuming 'map' is your Google Map object
                                    directionsRenderer.setDirections(result);
                                } else {
                                    console.error('Error drawing route:', status);
                                    // Handle error
                                }
                            });
                        }

                        // Add marker for each stop
                        let marker = new google.maps.Marker({
                            position: {
                                lat: parseFloat(stops[i].lat),
                                lng: parseFloat(stops[i].long)
                            },
                            map: googleMap,
                            title: stops[i].location
                        });


                        let des = ''
                        if (stops[i].datetime != null) {
                            if (stops[i].exit_time == null) {
                                des =
                                    `<span title="${stops[i].location}" class="text-warning">${stops[i].location.substring(0, 20)}... </span><b>Reached:${stops[i].trip_stop.datetime}</b>`
                            } else {
                                des =
                                    `<span title="${stops[i].location}" class="text-success">${stops[i].location.substring(0, 20)}... </span><b>${stops[i].trip_stop.datetime} / ${stops[i].trip_stop.exit_time}</b>`
                            }
                        } else {
                            des = `<span title="${stops[i].location}">` + stops[i].location
                                .substring(0, 20) +
                                `... <b>ETA: ${estimatedTime}</b></span>`
                        }


                        // Add info window for each marker
                        let infoWindow = new google.maps.InfoWindow({
                            content: des
                        });
                        marker.addListener('click', function() {
                            infoWindow.open(googleMap, marker);
                        });

                        estimatedTimes.push({
                            stop: stops[i].location,
                            trip_stop: stops[i],
                            estimatedTime: estimatedTime
                        });
                    } catch (error) {
                        console.error('Error calculating estimated time:', error);
                        // Handle error
                    }
                } else {
                    estimatedTimes.push({
                        stop: stops[i].location,
                        trip_stop: stops[i],
                        estimatedTime: '-'
                    });
                }
            }
            firstDirection = false

            return estimatedTimes;
        }
    </script>
