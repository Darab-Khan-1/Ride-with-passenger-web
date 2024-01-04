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
                        <div class="card card-custom " style="height:90vh;box-shadow: inset 1px 1px 10px 1px #c9c9c9;">
                            <div class="card-body py-2">
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <h3 class="text-center py-2">Drivers</h3>
                                        <button class="btn btn-primary w-100" onclick="showAllLocations()">Show
                                            All</button>
                                        <input type="text" id="searchInput" class="form-control mb-2"
                                            placeholder="Search by name or phone number" style="border:none">
                                        <ul class="user-list">
                                            @foreach ($drivers as $value)
                                                <li id="{{ 'USER' . $value->device_id }}" class="user-item"
                                                    data-name="{{ $value->name }}" device_id="{{ $value->device_id }}"
                                                    data-phone="{{ $value->phone }}">
                                                    <div class="user-profile">
                                                        {{-- <span
                                                            class="status-dot {{ $value->online === 1 ? 'online' : 'offline' }}"></span> --}}
                                                        <img src="{{ $value->avatar }}" alt="Profile Image"
                                                            class="user-avatar">
                                                    </div>
                                                    <div class="user-details">
                                                        <p class="user-name">{{ $value->name }}</p>
                                                        <p class="user-number">{{ $value->phone }}</p>
                                                    </div>
                                                </li>
                                            @endforeach


                                        </ul>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="card card-custom" id="infoCard" style="display:none;box-shadow: inset 1px 1px 10px 1px #c9c9c9;">
                            <div class="card-body p-5">
                                <div class="row">
                                    <div class="col-xl-4" style="margin-bottom: -25px;">
                                        <!--begin::Tiles Widget 12-->
                                        <div class="card card-custom  gutter-b"
                                            style="height: 150px;box-shadow: inset 1px 1px 10px 1px #c9c9c9;">
                                            <div class="card-body">
                                                <div class="text-dark font-weight-bolder font-size-h4 mt-3"
                                                    id="driver_info">-
                                                </div>
                                                {{-- <a href="#"
                                                    class="text-muted text-hover-primary font-weight-bold font-size-lg mt-1">Driver</a> --}}
                                            </div>
                                        </div>
                                        <!--end::Tiles Widget 12-->
                                    </div>
                                    <div class="col-xl-8" style="margin-bottom: -25px;">
                                        <!--begin::Tiles Widget 12-->
                                        <div class="card card-custom  gutter-b"
                                            style="height: 150px;box-shadow: inset 1px 1px 10px 1px #c9c9c9;">
                                            <div class="card-body">
                                                <div class="text-dark font-weight-bolder font-size-h4 mt-3"
                                                    id="position_info">-
                                                </div>
                                                {{-- <a href="#"
                                                    class="text-muted text-hover-primary font-weight-bold font-size-lg mt-1">Position</a> --}}
                                            </div>
                                        </div>
                                        <!--end::Tiles Widget 12-->
                                    </div>
                                    {{-- <div class="col-xl-4" style="margin-bottom: -25px;">
                                        <div class="card card-custom  gutter-b"
                                            style="height: 150px;box-shadow: inset 1px 1px 10px 1px #c9c9c9;">
                                            <div class="card-body">
                                                <div class="text-dark font-weight-bolder font-size-h4 mt-3"
                                                    id="time_info"> -
                                                </div>
                                            </div>
                                        </div>
                                        <!--end::Tiles Widget 12-->
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                        <div class="card card-custom  my-5" style="box-shadow: inset 1px 1px 10px 1px #c9c9c9;">
                            <div class="card-body p-5">
                                <div id="map" style="height: 85vh"></div>
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
    $("#live_nav").addClass("menu-item-active");


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
            document.getElementById("map").style.height = '60vh'
            document.getElementById("infoCard").style.display = 'block'


            var name = element.attr('data-name')
            var phone = element.attr('data-phone')
            // document.getElementById("time_info").innerHTML = '-'
            document.getElementById("position_info").innerHTML = '-'
            var timeInfoDiv = document.getElementById("driver_info");

            var table = "<table>";
            table += "<tr><td>Name: </td><td>" + name + "</td></tr>";
            table += "<tr><td>Phone: </td><td>" + phone + "</td></tr>";
            table += "</table>";

            timeInfoDiv.innerHTML = table;
            const selectedDriver = element.attr('device_id');
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
    function updateMarker(lat, lng) {
        if (firstCall) {
            firstCall = false
            // Reinitialize marker and polyline
            marker = new google.maps.Marker({
                map: googleMap,
                position: {
                    lat: lat,
                    lng: lng
                },
                icon: {
                    url: 'data:image/svg+xml,' + encodeURIComponent(svgContent),
                    size: new google.maps.Size(24, 24) // Set the size
                }

            });

            polyline = new google.maps.Polyline({
                map: googleMap,
                strokeColor: '#FF0000',
                strokeOpacity: 1.0,
                strokeWeight: 2
            });
        } else {
            marker.setPosition({
                lat,
                lng
            }, 14);
        }
    }

    // Function to start live tracking
    function startLiveTracking(driver) {
        interval = setInterval(function() {
            ajaxCall(driver)
        }, 5000); // Update every 5 seconds
    }

    function ajaxCall(driver) {
        $.ajax({
            url: "{{ url('/live/location/') }}" + "/" + driver,
            method: "GET",
            success: function(data) {
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
                    // timeInfoDiv.textContent = data.serverTime;
                    updateMarker(data.latitude, data.longitude);
                    googleMap.setCenter(marker.getPosition());
                    const path = polyline.getPath();
                    path.push(new google.maps.LatLng(data.latitude, data.longitude));
                    googleMap.setCenter({
                        lat: data.latitude,
                        lng: data.longitude
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

    var popupTemplate = `
    <div class="popup-card">
        <div class="card-header" style="display: flex; align-items: center;">
            <img class="" src="{avatar}" alt="Driver Avatar" style="border-radius:50%;width: 50px; height: 50px;">
            <h3 style="font-size: 14px; margin-left: 10px; text-align: right; flex-grow: 1;">{name} </h3>
            <a href="{href}" style="position: absolute;top: 20px;right: 40px;">Live Track</a>
        </div>
        <div class="card-body">
            <p style="font-size: 12px;"><strong>Phone:</strong> {phone}</p>
            <p style="font-size: 12px;"><strong>Speed:</strong> {speed}</p>
            <p style="font-size: 12px;"><strong>Time:</strong> {time}</p>
        </div>
    </div>
`;


    // function showAll() {

    //     document.getElementById("driver_info").innerHTML = '-'
    //     // document.getElementById("time_info").innerHTML = '-'
    //     document.getElementById("position_info").innerHTML = '-'
    //     clearInterval(interval);
    //     refreshMap()
    //     clearMarkers()
    //     markers = [];
    //     $.ajax({
    //         url: "{{ url('all/live/location/') }}",
    //         method: "GET",
    //         success: function(dataArray) {
    //             if (dataArray.length > 0) {
    //                 dataArray.forEach(function(data) {
    //                     var marker = new google.maps.Marker({
    //                         position: {
    //                             lat: data.latitude,
    //                             lng: data.longitude
    //                         },
    //                         icon: {
    //                             url: 'data:image/svg+xml,' + encodeURIComponent(svgContent),
    //                             size: new google.maps.Size(24, 24) // Set the size
    //                         },
    //                         map: googleMap,
    //                     });

    //                     markers.push(marker)

    //                     let online = '<span class="status-dot offline"></span>'
    //                     if (data.online) {
    //                         online = '<span class="status-dot online"></span>'
    //                     }
    //                     // Replace placeholders in the popup template with data
    //                     var popupContent = popupTemplate
    //                         // .replace('{online}', online)
    //                         .replace('{href}', "{{ url('live/location') }}" + "/" + data.device_id)
    //                         .replace('{avatar}', data.avatar)
    //                         .replace('{name}', data.name)
    //                         .replace('{phone}', data.phone)
    //                         .replace('{speed}', (data.speed * 1.85).toFixed(1) + " kph")
    //                         .replace('{time}', data.serverTime)
    //                         .replace('{address}', data.address);

    //                     // Create a popup for the marker
    //                     var infowindow = new google.maps.InfoWindow({
    //                         content: popupContent,
    //                     });

    //                     // Add a click event to open the popup when the marker is clicked
    //                     marker.addListener('click', function() {
    //                         infowindow.open(googleMap, marker);
    //                     });
    //                 });

    //                 var bounds = new google.maps.LatLngBounds();

    //                 // Loop through the markers and extend the bounds for each marker's position
    //                 markers.forEach(function(marker) {
    //                     bounds.extend(marker.getPosition());
    //                 });

    //                 // Fit the map to the bounds
    //                 googleMap.fitBounds(bounds);
    //             } else {
    //                 clearInterval(interval); // Clear previous interval
    //                 console.log("No data found.");
    //             }
    //         },
    //         error: function() {
    //             clearInterval(interval); // Clear previous interval
    //             toastr.error("Driver data not found")

    //         }
    //     });
    // }


    const driverMarkersMap = new Map();

    function showAllLocations() {
        $(".user-item").removeClass("active");
        document.getElementById("map").style.height = '85vh'
        document.getElementById("infoCard").style.display = 'none'
        firstCall = true
        showAll()
        refreshMap();
        clearMarkers();
        clearInterval(showInterval)
        clearInterval(interval);
        showInterval = setInterval(function() {
            showAll()
        }, 5000);
    }

    function showAll() {
        document.getElementById("driver_info").innerHTML = '-';
        document.getElementById("position_info").innerHTML = '-';
        $.ajax({
            url: "{{ url('all/live/location/') }}",
            method: "GET",
            success: function(dataArray) {
                if (dataArray.length > 0) {
                    dataArray.forEach(function(data) {
                        // Check if a marker already exists for this driver
                        if (driverMarkersMap.has(data.device_id)) {
                            // Update the existing marker's position
                            const existingMarker = driverMarkersMap.get(data.device_id);
                            existingMarker.setPosition({
                                lat: data.latitude,
                                lng: data.longitude
                            });

                            // Update the content of the info window
                            const infowindow = existingMarker.infowindow;
                            const popupContent = getPopupContent(data);
                            infowindow.setContent(popupContent);
                        } else {
                            // Create a new marker for the driver
                            const marker = new google.maps.Marker({
                                position: {
                                    lat: data.latitude,
                                    lng: data.longitude
                                },
                                icon: {
                                    url: 'data:image/svg+xml,' + encodeURIComponent(
                                        svgContent),
                                    size: new google.maps.Size(24, 24)
                                },
                                map: googleMap,
                            });

                            // Add the new marker to the map
                            driverMarkersMap.set(data.device_id, marker);

                            // Create a new info window for the marker
                            const infowindow = new google.maps.InfoWindow({
                                content: getPopupContent(data),
                            });

                            // Attach the info window to the marker
                            marker.infowindow = infowindow;

                            // Add a click event to open the info window when the marker is clicked
                            marker.addListener('click', function() {
                                infowindow.open(googleMap, marker);
                            });
                        }
                    });

                    var bounds = new google.maps.LatLngBounds();

                    // Loop through the markers and extend the bounds for each marker's position
                    driverMarkersMap.forEach(function(marker) {
                        bounds.extend(marker.getPosition());
                    });

                    // Fit the map to the bounds
                    if (firstCall) {
                        firstCall = false
                        googleMap.fitBounds(bounds);
                    }
                } else {
                    clearInterval(interval);
                    console.log("No data found.");
                }
            },
            error: function() {
                clearInterval(interval);
                toastr.error("Driver data not found");
            }
        });
    }

    function getPopupContent(data) {
        let online = '<span class="status-dot offline"></span>';
        if (data.online) {
            online = '<span class="status-dot online"></span>';
        }

        return popupTemplate
            .replace('{href}', "{{ url('live/location') }}" + "/" + data.device_id)
            .replace('{avatar}', data.avatar)
            .replace('{name}', data.name)
            .replace('{phone}', data.phone)
            .replace('{speed}', (data.speed * 3.6).toFixed(1) + " kph")
            .replace('{time}', data.serverTime);
    }


    $(document).on('click', '.user-item', function() {
        $(".user-item").removeClass("active");
        $(this).addClass("active");
        clearMarkers()

        document.getElementById("map").style.height = '60vh'
        document.getElementById("infoCard").style.display = 'block'
        clearInterval(showInterval);
        firstCall = true

        var name = $(this).attr('data-name')
        var phone = $(this).attr('data-phone')
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
</script>
