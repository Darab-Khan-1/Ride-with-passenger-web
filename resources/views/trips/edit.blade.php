@include('includes/header')
<!--begin::Content-->
<style>
    .pac-container {
        z-index: 1000000000 !important;
    }

    #map {
        height: 700px;
        width: 100%;
    }
</style>

<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Entry-->
    <div>
        <!--begin::Container-->
        <div class="px-5">
            <div class="card card-custom">
                @if (session('success'))
                    <div class="alert alert-success m-2">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
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
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <h3>Edit Trip</h3>
                </div>
                <form action="{{ url('trip/update') }}" method="POST" onsubmit="return validateForm()">
                    @csrf
                    <div class="card-body p-5" style="overflow: auto;">
                        <div class="row">

                            <div class="form-group col-md-6">
                                <label>Customer Name:</label>
                                <input type="text" name="customer_name" id="customer_name" required
                                    class="form-control "
                                    value="{{ old('customer_name') ? old('customer_name') : $trip->customer_name }}"
                                    placeholder="Enter value here" />
                            </div>
                            <div class="form-group col-md-6">
                                <label>Customer Phone:</label>
                                <input type="text" name="customer_phone" id="customer_phone" required
                                    class="form-control "
                                    value="{{ old('customer_phone') ? old('customer_phone') : $trip->customer_phone }}"
                                    placeholder="Enter value here" />
                            </div>
                            <div class="form-group col-md-6">
                                <label>Pickup Date:
                                </label>
                                <input type="datetime-local" name="pickup_date" id="pickup_date" required
                                    value="{{ old('pickup_date') ? old('pickup_date') : $trip->pickup_date }}"
                                    class="form-control" placeholder="Enter value here" />
                                <br>

                            </div>
                            <div class="form-group col-md-6">
                                <label>Delivery Date:</label>
                                <input type="datetime-local" name="delivery_date" class="form-control" required
                                    value="{{ old('delivery_date') ? old('delivery_date') : $trip->delivery_date }}"
                                    placeholder="Enter value here" />
                            </div>
                            <div class="form-group col-md-6">
                                <label>Pickup Location:</label>
                                <input type="text" name="pickup_location" id="pickup_location"
                                    value="{{ old('pickup_location') ? old('pickup_location') : $trip->pickup_location }}"
                                    class="form-control form-control-solid start-end-location" required readonly
                                    placeholder="Enter value here" />
                            </div>
                            <div class="form-group col-md-6">
                                <label>Delivery Location:</label>
                                <input type="text" name="delivery_location" id="delivery_location" readonly required
                                    value="{{ old('delivery_location') ? old('delivery_location') : $trip->delivery_location }}"
                                    class="form-control-solid form-control start-end-location"
                                    placeholder="Enter value here" />
                            </div>

                            <div class="form-group col-md-6">
                                <label>Estimated Distance:</label>
                                <input type="text" name="estimated_distance" id="estimated_distance" readonly
                                    required class="form-control form-control-solid"
                                    value="{{ old('estimated_distance') ? old('estimated_distance') : $trip->estimated_distance }}"
                                    placeholder="Enter value here" />
                            </div>
                            <div class="form-group col-md-6">
                                <label>Estimated Time:</label>
                                <input type="text" name="estimated_time" id="estimated_time" readonly required
                                    class="form-control form-control-solid"
                                    value="{{ old('estimated_time') ? old('estimated_time') : $trip->estimated_time }}"
                                    placeholder="Enter value here" />
                            </div>
                            <div class="form-group col-md-6">
                                <label>Assign Driver:</label>
                                <select class="form-control " name="user_id">
                                    <option value="" selected>--No Driver Attached--</option>
                                    @foreach ($drivers as $value)
                                        <option value="{{ $value->user_id }}"
                                            @if ($trip->user_id == $value->user_id) selected @endif>{{ $value->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Event Name:</label>
                                <input type="text" name="event_name" id="event_name" required class="form-control "
                                    value="{{ old('event_name') ? old('event_name') : $trip->event_name }}"
                                    placeholder="Enter value here" />
                            </div>
                            <div class="form-group col-md-6">
                                <label>Event Description:</label>
                                <textarea class="form-control " required name="description" cols="30" rows="5">{{ $trip->description }}</textarea>
                            </div>



                        </div>
                    </div>
                    <div class="card-footer">
                        <input type="hidden" name="stops" id="stops_array"
                            value="{{ old('stops') ? old('stops') : json_encode($trip->stops) }}">
                        <input type="hidden" name="stop_descriptions" id="stop_descriptions" value="{{ old('stop_descriptions') }}">
                        <input type="hidden" name="start_description" id="s_description" >
                        <input type="hidden" name="end_description" id="e_description">
                        <input type="hidden" name="lat" id="lat" value="{{ $trip->lat }}">
                        <input type="hidden" name="long" id="long" value="{{ $trip->long }}">
                        <input type="hidden" name="drop_lat" id="drop_lat" value="{{ $trip->drop_lat }}">
                        <input type="hidden" name="drop_long" id="drop_long" value="{{ $trip->drop_long }}">
                        <input type="hidden" name="trip_id" value="{{ $trip->id }}">
                        <input type="hidden" name="event_id" value="{{ $trip->event_id }}">
                        <button type="submit" class="btn btn-primary mr-2">Submit</button>
                        <a href="{{ URL::previous() }}" class="btn btn-secondary" data-dismiss="modal">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
        <!--end::Container-->
    </div>
    <!--end::Entry-->
</div>

<div id="formModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Select Trip Start And End Location</h4>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="map_box_container">
                        <div class="mb-3">
                            <label for="start" class="form-label">Start Location:</label>
                            <input type="text" id="start" class="form-control"
                                value="{{ $trip->pickup_location }}" placeholder="Enter start location">
                        </div>
                        <div class="mb-3">
                            <label for="start" class="form-label">Start Point Description:</label>
                            <textarea name="start_description" id="start_description" cols="30" rows="1" class="form-control " placeholder="Enter start description">{{ $trip->status != null ?  $trip->stops[0]->description : '' }}</textarea>
                        </div>
                        <div class="mb-3">
                            <div id="stopsContainer">
                                @foreach ($trip->stops as $item)
                                    @if ($item->type == 'stop')
                                        <div class="row my-3">
                                            <label for="stop" class="col-md-1"
                                                style="margin-top: 5px;">Stop:</label>
                                            <input type="text" class="stop col-md-8 form-control mx-3"
                                                value="{{ $item->location }}" placeholder="Enter stop location"
                                                name="stops[]" required>
                                            <button type="button"
                                                class="removeStop btn btn-danger btn-sm col-md-2">Remove
                                                Stop</button>
                                                <label class="col-md-1" ></label><label for="description" class="col-md-2" style="margin-top: 20px;">Description:</label><textarea name="descriptions[]" cols="30" rows="2" class="stop_description form-control col-md-8  mt-2 ml-6" placeholder="Enter description">{{ $item->description }}</textarea>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="end" class="form-label">End Location:</label>
                            <input type="text" id="end" class="form-control"
                                value="{{ $trip->delivery_location }}" placeholder="Enter end location">
                        </div>
                        <div class="mb-3">
                            <label for="start" class="form-label">End Point Description:</label>
                            <textarea name="end_description" id="end_description" cols="30" rows="1" class="form-control " placeholder="Enter end description">{{  $trip->status != null ?  $trip->stops[count($trip->stops) - 1]->description : '' }}</textarea>
                        </div>
                        <button type="button" id="addStop" class="btn btn-secondary my-3">Add Stop</button>
                        <button id="calculate-route" class="btn btn-success m-2">Calculate Route</button>
                        <div class="mb-3" id="map-overlay">Distance:
                            <br>
                            Duration:
                        </div>
                        <div id="map"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--end::Content-->
@include('includes/footer')

<script
    src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places&callback=initMap"
    defer></script>

<script>
    $(document).ready(function() {
        $('#calculate-route').click();
    })

    function validateForm() {
        var fieldValue = document.getElementById('pickup_location').value;

        if (!fieldValue) {
            toastr.warning('Pickup Location is required!');
            return false;
        }
        return true;
    }

    document.querySelector('.start-end-location').addEventListener('click', function() {
        $('#formModal').modal('show');
    });

    $('#formModal').on('hidden.bs.modal',function(){
        $('#calculate-route').click();
    });
    var map;
    var directionsService;
    var directionsRenderer;
    var markers = [];
    var totalDistance = 0;
    var totalDuration = 0;

    function initMap() {
        // Enable autocomplete for start input
        enableAutocomplete(document.getElementById('start'));

        // Enable autocomplete for destination input
        enableAutocomplete(document.getElementById('end'));

        // Enable autocomplete for existing stop inputs
        $('.stop').each(function() {
            enableAutocomplete(this);
        });
        map = new google.maps.Map(document.getElementById('map'), {
            center: {
                lat: 37.7749,
                lng: -122.4194
            }, // Default center (San Francisco)
            zoom: 12
        });

        directionsService = new google.maps.DirectionsService();
        directionsRenderer = new google.maps.DirectionsRenderer({
            map: map,
            suppressMarkers: true,
            polylineOptions: {
                strokeColor: 'green'
            }
        });

        // Add event listener to add stop button
        $('#addStop').on('click', function() {
            var stopInput = $(
                '<div class="row my-3"><label for="stop" class="col-md-1" style="margin-top: 5px;">Stop:</label><input type="text"  placeholder="Enter stop location" class="stop col-md-8 form-control mx-3" name="stops[]" required><button type="button" class="removeStop btn btn-danger btn-sm col-md-2">Remove Stop</button><label class="col-md-1" ></label><label for="description" class="col-md-2" style="margin-top: 20px;">Description:</label><textarea name="descriptions[]" cols="30" rows="2" class="stop_description form-control col-md-8  mt-2 ml-6" placeholder="Enter description"></textarea></div>'
            );
            $('#stopsContainer').append(stopInput);
            // Enable autocomplete for the new stop input
            enableAutocomplete(stopInput.find('.stop')[0]);
        });

        // Add event listener to remove stop button
        $('#stopsContainer').on('click', '.removeStop', function() {
            $(this).parent().remove();
        });

        // Handle form submission
        $('#calculate-route').on('click', function(e) {
            e.preventDefault();

            var start = $('#start').val();
            var destination = $('#end').val();
            var stops = [],stop_descriptions=[];

            $('.stop').each(function() {
                stops.push($(this).val());
            });

            $('.stop_description').each(function() {
                // console.log($(this).val())
                stop_descriptions.push($(this).val());
            });

            $("#stop_descriptions").val(JSON.stringify(stop_descriptions))
            $("#s_description").val($("#start_description").val())
            $("#e_description").val($("#end_description").val())


            calculateRoute(start, destination, stops);
        });
    }

    function enableAutocomplete(input) {
        var autocomplete = new google.maps.places.Autocomplete(input);

        // Set the types of predictions to display
        autocomplete.setTypes(['geocode']);
    }


    function calculateRoute(start, destination, stops) {
        var waypoints = [];
        var stops_array = [];

        // Convert stop addresses to waypoints
        // console.log(stops);
        stops.forEach(function(stop) {
            // stops_array.push(stop);
            waypoints.push({
                location: stop
            });
        });
        // console.log(waypoints);

        // Create request for directions service
        var request = {
            origin: start,
            destination: destination,
            waypoints: waypoints,
            optimizeWaypoints: true,
            travelMode: 'DRIVING'
        };

        // Send request to directions service
        directionsService.route(request, function(result, status) {
            if (status === 'OK') {
                // Display route on the map
                directionsRenderer.setDirections(result);

                // Clear existing markers
                markers.forEach(function(marker) {
                    marker.setMap(null);
                });
                markers = [];

                // Add marker for start
                var startMarker = new google.maps.Marker({
                    position: result.routes[0].legs[0].start_location,
                    map: map
                });
                markers.push(startMarker);

                var startLocation = result.routes[0].legs[0].start_location;
                var startLat = startLocation.lat();
                var startLng = startLocation.lng();

                addPopup(startMarker, '<span class="badge badge-warning bg-warning mr-2 p-2">Start</span>');

                // Add markers for stops
                for (var i = 0; i < result.routes[0].legs.length - 1; i++) {

                    var stopLocation = result.routes[0].legs[i].end_location;
                    var stopLat = stopLocation.lat();
                    var stopLng = stopLocation.lng();

                    // Update the stops array with lat/lng for each stop
                    stops_array.push({
                        'stop': stops[i],
                        'lat': stopLat,
                        'lng': stopLng
                    });
                    // stops_array[i].latitude = stopLat;
                    // stops_array[i].longitude = stopLng;

                    var stopMarker = new google.maps.Marker({
                        position: result.routes[0].legs[i].end_location,
                        map: map
                    });
                    markers.push(stopMarker);


                    // Add popup for marker with lat/lng information
                    addPopup(stopMarker, '<span class="badge badge-danger bg-danger mr-2 p-2">Stop ' + (i + 1) +
                        "</span>");
                }

                // Add marker for destination
                var destinationMarker = new google.maps.Marker({
                    position: result.routes[0].legs[result.routes[0].legs.length - 1].end_location,
                    map: map
                });
                markers.push(destinationMarker);

                // Access latitude and longitude for destination
                var destinationLat = result.routes[0].legs[result.routes[0].legs.length - 1].end_location.lat();
                var destinationLng = result.routes[0].legs[result.routes[0].legs.length - 1].end_location.lng();

                // Add popup for destination marker with lat/lng information
                addPopup(destinationMarker,
                    '<span class="badge badge-success bg-success mr-2 p-2">Destination</span><br>Lat: ' +
                    destinationLat + '<br>Lng: ' + destinationLng);

                totalDistance = result.routes[0].legs.reduce(function(acc, leg) {
                    return acc + leg.distance.value;
                }, 0);
                totalDuration = result.routes[0].legs.reduce(function(acc, leg) {
                    return acc + leg.duration.value;
                }, 0);

                // Convert total distance and duration to desired formats
                var formattedDistance = (totalDistance / 1000).toFixed(2); // Convert meters to kilometers
                var formattedDuration = convertSecondsToHMS(
                    totalDuration); // Convert seconds to HH:MM:SS format

                // Display the total distance and duration
                var totalInfo = document.getElementById('map-overlay');
                totalInfo.innerHTML = 'Distance: ' + formattedDistance + ' km<br>Duration: ' +
                    formattedDuration;

                // console.log(stops_array);
                $("#stops_array").val(JSON.stringify(stops_array))
                document.getElementById('lat').value = startLat;
                document.getElementById('long').value = startLng;
                document.getElementById('drop_lat').value = destinationLat;
                document.getElementById('drop_long').value = destinationLng;

                document.getElementById('estimated_distance').value = formattedDistance;
                document.getElementById('estimated_time').value = formattedDuration;
                document.getElementById('pickup_location').value = start;
                document.getElementById('delivery_location').value = destination;
            }
        });

        function addPopup(marker, content) {
            var infowindow = new google.maps.InfoWindow({
                content: content
            });

            marker.addListener('click', function() {
                infowindow.open(map, marker);
            });
        }

        function convertSecondsToHMS(seconds) {
            var hours = Math.floor(seconds / 3600);
            var minutes = Math.floor((seconds % 3600) / 60);
            var secs = Math.floor(seconds % 60);

            var formattedTime = '';
            if (hours > 0) {
                formattedTime += hours.toString().padStart(2, '0') + ':';
            }
            formattedTime += minutes.toString().padStart(2, '0') + ':';
            formattedTime += secs.toString().padStart(2, '0');

            return formattedTime;
        }
    }
    
</script>
