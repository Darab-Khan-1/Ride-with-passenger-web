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
        height: 67vh;
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

    .driver-map-icon {
        width: 30px !important;
        height: 30px !important;
        user-select: none !important;
        border: 0px !important;
        padding: 0px !important;
        margin: 0px !important;
        border-radius: 50% !important;
        max-width: none !important;
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
        background-color: rgba(0, 94, 255, 0.96);
        /* Set the online status color */
    }

    .offline {
        background-color: red;
        /* Set the offline status color */
    }

    .custom-marker-label {
        font-size: 10px !important;
    }
</style>
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> --}}


{{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" /> --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css"
    rel="stylesheet" />
<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid " id="kt_content">
    <!--begin::Entry-->
    <div>
        <!--begin::Container-->
        <div class="m-5">
            <div class="p-5">
                <div class="row">
                    <div class="col-md-3 counter-mirror">
                        <div class="card card-custom mb-2 py-4 " style="box-shadow: inset 1px 1px 10px 1px #c9c9c9;">
                            <div class="card-body py-2">
                                <button class="btn btn-primary w-100 btn-lg" onclick="showAllLocations()">Show All
                                    Drivers
                                </button>
                            </div>
                        </div>

                        <div class="card card-custom "
                            style="height:1000px;box-shadow: inset 1px 1px 10px 1px #c9c9c9;">
                            <div class="card-body py-2">
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        {{-- <h3 class="text-center py-2">{{ __('messages.drivers') }}</h3> --}}

                                        <input type="text" id="searchInput" class="form-control text-center mb-2"
                                            placeholder="Or search by email or phone"
                                            style="border: none;border-bottom:2 px solid gray !important;">
                                        <ul class="user-list">
                                            @foreach ($drivers as $value)
                                                <li id="{{ 'USER' . $value->device_id }}" class="user-item"
                                                    data-name="{{ $value->name }}" device_id="{{ $value->device_id }}"
                                                    data-phone="{{ $value->address }}">
                                                    <div class="user-profile">
                                                        {{-- <span
                                                            class="status-dot {{ $value->online === 1 ? 'online' : 'offline' }}"></span> --}}
                                                        <img src="{{ $value->avatar }}" alt="Profile Image"
                                                            class="user-avatar">
                                                    </div>
                                                    <div class="user-details">
                                                        <p class="user-name">{{ $value->name }}</p>
                                                        <p class="user-number">{{ $value->address }}</p>
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
                        <div class="card card-custom" id="infoCard"
                            style="display:none;box-shadow: inset 1px 1px 10px 1px #c9c9c9;">
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
                        <div class="card card-custom  my-5" id="estimated_times"
                            style="box-shadow: inset 1px 1px 10px 1px #c9c9c9;display:none">
                            <div class="card-body p-5">
                                <div id="times_all" class="row">

                                </div>
                            </div>
                            {{-- <div class="card-footer">
                        </div> --}}
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
    $(".live-nav").addClass("menu-item-active");


    let googleMap;
    let marker;
    let polyline;
    let interval;
    let showInterval;
    let firstCall;
    let allLive = null;
    let markers = [];


    $(document).ready(function() {
        $(document).on('click', '.share_link_button', function() {
            let copyGfGText =
                document.getElementById("sharedLink");

            copyGfGText.select();
            document.execCommand("copy");

            document.querySelector('#share_link_button').value = 'Link copied';
            toastr.success("Link Copied")

        });

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
            document.getElementById("map").style.height = '850px'
            document.getElementById("infoCard").style.display = 'block'


            var name = element.attr('data-name')
            var phone = element.attr('data-phone')
            // document.getElementById("time_info").innerHTML = '-'
            document.getElementById("position_info").innerHTML = '-'
            var timeInfoDiv = document.getElementById("driver_info");

            var table = "<table>";
            table += "<tr><td>{{ __('messages.name') }}: </td><td>" + name + "</td></tr>";
            table += "<tr><td>{{ __('messages.phone') }}: </td><td>" + phone + "</td></tr>";
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
                lat: 31.9539494,
                lng: 35.910635
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
                size: new google.maps.Size(4, 4) // Set the size
            },
            label: {
                text: '-', // Label text from the data
                className: 'badge badge-sm badge-warning  custom-marker-label', // Custom class name for the label
            },

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

    function ajaxCall(driver) {
        $.ajax({
            url: "{{ url('/live/location/') }}" + "/" + driver,
            method: "GET",
            beforeSend: function() {
                if (allLive != null) {
                    clearInterval(allLive)
                }
            },
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
                    if (response['slug'] != '' && response['trip'] != null) {
                        table +=
                            '<tr><td><button id="share_link_button"  class="btn share_link_button  font-weight-bolder" style="background: #ffc500">Copy</td><td><input type="text" id="sharedLink" class="form-control form-control-solid" style="width:455px" placeholder="Share link" value="' +
                            response['slug'] + '" disabled /></td></tr>'
                    }
                    table += "</table>";

                    positionInfoDiv.innerHTML = table;
                    // timeInfoDiv.textContent = data.serverTime;
                    updateMarker(data.latitude, data.longitude, response['driver'].avatar);
                    googleMap.setCenter(marker.getPosition());
                    const path = polyline.getPath();
                    path.push(new google.maps.LatLng(data.latitude, data.longitude));
                    googleMap.setCenter({
                        lat: data.latitude,
                        lng: data.longitude
                    });

                    // Example usage
                    var driverLat = data.latitude; // Driver's latitude
                    var driverLng = data.longitude; // Driver's longitude
                    if (response.trip != null) {
                        var stops = response.trip.stops

                        calculateEstimatedTime(driverLat, driverLng, stops)
                            .then(function(estimatedTimes) {
                                // console.log('Estimated times:', estimatedTimes);
                                let html = ''
                                if (estimatedTimes.length > 0) {
                                    estimatedTimes.forEach((element, index) => {
                                        html +=
                                            `<span class="col-md-3 px-2">${element.stop.substring(0,25)}...<b class="text-success">ETA ${element.estimatedTime}</b></span>`
                                    })
                                    $("#estimated_times").show()
                                    $("#times_all").html(html)
                                }
                            })
                            .catch(function(error) {
                                console.error('Error calculating estimated times:', error);
                            });
                    } else {
                        $("#estimated_times").hide()
                        $("#times_all").html('')
                    }

                } else {
                    clearInterval(interval); // Clear previous interval
                    toastr.error("<span class='counter-mirror'>Driver data not found.</span>");
                }
            },
            error: function() {
                clearInterval(interval); // Clear previous interval
                toastr.error("<span class='counter-mirror'>Driver data not found</span>")

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




    const driverMarkersMap = new Map();

    function showAllLocations() {
        $(".user-item").removeClass("active");
        document.getElementById("map").style.height = '1030px'
        document.getElementById("infoCard").style.display = 'none'
        firstCall = true
        showAll()
        refreshMap();
        clearMarkers();
        clearInterval(showInterval)
        clearInterval(interval);

        // allLive = setInterval(function() {
        //     showAll()
        // }, 5000); 


    }

    function showAll() {
        document.getElementById("driver_info").innerHTML = '-';
        document.getElementById("position_info").innerHTML = '-';
        $.ajax({
            url: "{{ url('all/live/location/') }}",
            method: "GET",
            success: function(dataArray) {
                if (dataArray.length > 0) {
                    var bounds = new google.maps.LatLngBounds();
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
                            var userImageURL = data.avatar;

                            const shape = {
                                coords: [1, 1, 1, 20, 18, 20, 18, 1],
                                type: "poly",
                            };

                            // Create the round marker icon
                            const markerImage = new Image();
                            markerImage.src = data.avatar; // Set the marker image URL
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
                                context.drawImage(markerImage, 0, 0, 40,
                                    40); // Draw the image onto the canvas

                                // Draw a border around the rounded marker image
                                context.strokeStyle = '#198a16cf'; // Set the border color
                                context.lineWidth = 3; // Set the border width
                                context.stroke(); // Draw the border
                                context.drawImage(markerImage, 0, 0, 40,
                                    40); // Draw the image onto the canvas

                                const roundedMarkerImage = canvas
                                    .toDataURL(); // Convert the canvas content to a data URL

                                // Create the marker with the rounded image
                                const marker = new google.maps.Marker({
                                    position: {
                                        lat: data.latitude,
                                        lng: data.longitude
                                    },
                                    map: googleMap,
                                    icon: {
                                        url: roundedMarkerImage,
                                        scaledSize: new google.maps.Size(40, 40),
                                    },

                                    label: {
                                        text: (data.speed * 3.6).toFixed(1) + " km/h",
                                        className: 'badge badge-sm badge-warning ml-10 mb-10 custom-marker-label', // Custom class name for the label
                                    },
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

                                // Extend the bounds to include the marker's position
                                bounds.extend(marker.getPosition());

                                // Fit the map to the updated bounds
                                googleMap.fitBounds(bounds);
                            };
                        }
                    });

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

    function createRoundMarkerIcon(imageUrl, size) {
        // Create a canvas element
        var canvas = document.createElement('canvas');
        canvas.width = size;
        canvas.height = size;
        var ctx = canvas.getContext('2d');

        // Draw a circle on the canvas as a mask
        ctx.beginPath();
        ctx.arc(size / 2, size / 2, size / 2, 0, Math.PI * 2);
        ctx.closePath();
        ctx.clip();

        // Load the image onto the canvas
        var image = new Image();
        image.onload = function() {
            ctx.drawImage(image, 0, 0, size, size); // Draw the image
        };
        image.src = imageUrl; // Set the image source

        // Return the canvas as a data URL
        return canvas.toDataURL();
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

        document.getElementById("map").style.height = '850px'
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

    // Function to calculate the estimated time to reach each stop from the driver's position
    async function calculateEstimatedTime(driverLat, driverLng, stops) {
        var estimatedTimes = [];

        for (let i = 0; i < stops.length; i++) {
            if (stops[i].datetime === null) {
                try {
                    let duration = await calculateDuration(driverLat + ',' + driverLng, stops[i].lat + ',' + stops[
                        i].long);
                    estimatedTimes.push({
                        stop: stops[i].location,
                        estimatedTime: duration
                    });
                } catch (error) {
                    console.error('Error calculating estimated time:', error);
                    // Handle error
                }
            }
        }

        return estimatedTimes;
    }
</script>
