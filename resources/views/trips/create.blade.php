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
                <div class="card-header flex-wrap border-0 pt-6 pb-0 counter-mirror">
                    <h3>{{ __('messages.create_trip') }}</h3>
                </div>
                <form action="{{ url('trip/create') }}" method="POST" class="counter-mirror">
                    @csrf
                    <div class="card-body p-5" style="overflow: auto;">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>{{ __('messages.select_customer') }}:</label>
                                <select class="form-control " name="customer_id" id="customers">
                                    <option value="" selected>--{{ __('messages.select_customer') }}--</option>
                                    @foreach ($customers as $value)
                                        <option value="{{ $value->user_id }}" data-locations="{{ json_encode($value) }}"
                                            @if (old('user_id') == $value->user_id) selected @endif>{{ $value->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label>{{ __('messages.customer_name') }}:</label>
                                <input type="text" name="customer_name" id="customer_name" required
                                    class="form-control " value="{{ old('customer_name') }}"
                                    placeholder="{{ __('messages.enter_value_here') }}" />
                            </div>
                            <div class="form-group col-md-6">
                                <label>{{ __('messages.customer_phone') }}:</label>
                                <input type="text" name="customer_phone" id="customer_phone" required
                                    class="form-control " value="{{ old('customer_phone') }}"
                                    placeholder="{{ __('messages.enter_value_here') }}" />
                            </div>
                            <div class="form-group col-md-6">
                                <label>{{ __('messages.company') }}:</label>
                                <input type="text" name="customer_company" id="customer_company" required
                                    class="form-control " value="{{ old('customer_company') }}"
                                    placeholder="{{ __('messages.enter_value_here') }}" />
                            </div>

                            <div class="form-group col-md-6">
                                <label>{{ __('messages.pickup_date') }}:</label>
                                <input type="datetime-local" name="pickup_date" id="pickup_date" required
                                    value="{{ old('pickup_date') ? old('pickup_date') : now()->format('Y-m-d H:i') }}"
                                    class="form-control" placeholder="{{ __('messages.enter_value_here') }}" />
                                <br>
                            </div>
                            <div class="form-group col-md-6">
                                <label>{{ __('messages.delivery_date') }}:</label>
                                <input type="datetime-local" name="delivery_date" class="form-control" required
                                    value="{{ old('delivery_date') }}"
                                    placeholder="{{ __('    .enter_value_here') }}" />
                            </div>
                            <div class="form-group col-md-6">
                                <label>{{ __('messages.pickup_location') }}:</label>
                                <input type="text" name="pickup_location" id="pickup_location"
                                    value="{{ old('pickup_location') }}"
                                    class="form-control form-control-solid start-end-location" required readonly
                                    placeholder="{{ __('messages.enter_value_here') }}" />
                            </div>
                            <div class="form-group col-md-6">
                                <label>{{ __('messages.delivery_location') }}:</label>
                                <input type="text" name="delivery_location" id="delivery_location" readonly required
                                    value="{{ old('delivery_location') }}"
                                    class="form-control-solid form-control start-end-location"
                                    placeholder="{{ __('messages.enter_value_here') }}" />
                            </div>

                            <div class="form-group col-md-6">
                                <label>{{ __('messages.estimated_distance') }}:</label>
                                <input type="text" name="estimated_distance" id="estimated_distance" readonly
                                    required class="form-control form-control-solid"
                                    value="{{ old('estimated_distance') }}"
                                    placeholder="{{ __('messages.enter_value_here') }}" />
                            </div>
                            <div class="form-group col-md-6">
                                <label>{{ __('messages.estimated_time') }}:</label>
                                <input type="text" name="estimated_time" id="estimated_time" readonly required
                                    class="form-control form-control-solid" value="{{ old('estimated_time') }}"
                                    placeholder="{{ __('messages.enter_value_here') }}" />
                            </div>
                            <div class="form-group col-md-6">
                                <label>{{ __('messages.select_driver') }}:</label>
                                <select class="form-control " name="user_id">
                                    <option value="" selected>--{{ __('messages.select_driver') }}--</option>
                                    @foreach ($drivers as $value)
                                        <option value="{{ $value->user_id }}"
                                            @if (old('user_id') == $value->user_id) selected @endif>{{ $value->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label>{{ __('messages.event_name') }}:</label>
                                <input type="text" name="event_name" id="event_name" required class="form-control "
                                    value="{{ old('event_name') }}"
                                    placeholder="{{ __('messages.enter_value_here') }}" />
                            </div>
                            <div class="form-group col-md-6">
                                <label>{{ __('messages.event_description') }}:</label>
                                <textarea class="form-control " required name="description" cols="30" rows="5"></textarea>
                            </div><br>
                        </div>
                        <div id="tripfields">
                            <div class="row">
                                <div class="col-md-1">
                                    <label for="driver">{{ __('messages.driver_value') }}</label>
                                    <input type="checkbox" class="driver-visible-checkbox"><br>
                                    <input type="hidden" name="driver_value[]" value="0">
                                </div>
                                <div class="col-md-3">
                                    <label for="name">{{ __('messages.name') }}</label>
                                    <input type="text" name="name[]" class="form-control"
                                        placeholder="{{ __('messages.name') }}" required /><br>
                                </div>
                                <div class="col-md-3">
                                    <label for="values">{{ __('messages.value') }}</label>
                                    <input type="text" name="value[]" class="form-control"
                                        placeholder="{{ __('messages.value') }}" required /><br>
                                </div>
                                <div class="col-md-3 pt-7 remove">
                                    <button type="button"
                                        class="btn btn-danger btn-clean">{{ __('messages.remove') }}</button><br>
                                </div>
                            </div>
                        </div><br>

                        <div class="add_trip">
                            <button type="button" class="btn btn-secondary btn-clean"
                                id="add_fields">{{ __('messages.add') }}</button>
                        </div>
                    </div>
                    <div class="card-footer">
                        <input type="hidden" name="stops" id="stops_array" value="{{ old('stops') }}">
                        <input type="hidden" name="stop_descriptions" id="stop_descriptions"
                            value="{{ old('stop_descriptions') }}">
                        <input type="hidden" name="start_description" id="s_description">
                        <input type="hidden" name="end_description" id="e_description">
                        <input type="hidden" name="lat" id="lat">
                        <input type="hidden" name="long" id="long">
                        <input type="hidden" name="drop_lat" id="drop_lat">
                        <input type="hidden" name="drop_long" id="drop_long">
                        <button type="submit" class="btn btn-primary mr-2">Submit</button>
                        <a href="{{ url('/trips') }}" class="btn btn-secondary" data-dismiss="modal">Cancel</a>
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
        <div class="modal-content counter-mirror">
            <div class="modal-header">
                <h4 class="modal-title">{{ __('messages.select_trip') }}</h4>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="map_box_container">
                        <div class="mb-3">
                            <label for="start" class="form-label">{{ __('messages.start_location') }}:</label>
                            <span class="btn btn-secondary previous-location btn-sm m-1" data-toggle="modal"
                                data-target="#locationModal"
                                title="Add from previously saved locations">+</span><input type="text"
                                id="start" class="form-control"
                                placeholder="{{ __('messages.start_location') }}">
                        </div>
                        <div class="mb-3">
                            <label for="start" class="form-label">{{ __('messages.start_desc') }}:</label>
                            <textarea name="start_description" id="start_description" cols="30" rows="1" class="form-control "
                                placeholder="{{ __('messages.enter_start_desc') }}"></textarea>
                        </div>
                        <div class="mb-3">
                            <div id="stopsContainer">
                                <div class="row my-3">
                                    <label for="stop" class="col-md-1"
                                        style="margin-top: 5px;">{{ __('messages.stop') }}</label>
                                    <span class="btn btn-secondary previous-location btn-sm m-1" data-toggle="modal"
                                        data-target="#locationModal"
                                        title="Add from previously saved locations">+</span> <input type="text"
                                        class="stop col-md-8 form-control mx-3"
                                        placeholder="{{ __('messages.enter_location') }}" name="stops[]" required>
                                    <button type="button"
                                        class="removeStop btn btn-danger btn-sm col-md-2">{{ __('messages.remove_stop') }}</button>
                                    <label class="col-md-1"></label><label for="description" class="col-md-2"
                                        style="margin-top: 20px;">{{ __('messages.description') }}:</label>
                                    <textarea name="descriptions[]" cols="30" rows="2"
                                        class="stop_description form-control col-md-8  mt-2 ml-6" placeholder="{{ __('messages.enter_description') }}"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="end" class="form-label">{{ __('messages.end_location') }}:</label>
                            <span class="btn btn-secondary previous-location btn-sm m-1" data-toggle="modal"
                                data-target="#locationModal" title="Add from previously saved locations">+</span>
                            <input type="text" id="end" class="form-control"
                                placeholder="{{ __('messages.end_location') }}">
                        </div>
                        <div class="mb-3">
                            <label for="start" class="form-label">{{ __('messages.end_point_desc') }}:</label>
                            <textarea name="end_description" id="end_description" cols="30" rows="1" class="form-control "
                                placeholder="{{ __('messages.end_point_desc') }}"></textarea>
                        </div>
                        <button type="button" id="addStop"
                            class="btn btn-secondary my-3">{{ __('messages.add_field') }}
                            {{ __('messages.stop') }}</button>
                        <button id="calculate-route"
                            class="btn btn-success m-2">{{ __('messages.calculate_route') }}</button>
                        <div class="mb-3" id="map-overlay">{{ __('messages.distance') }}:
                            <br>
                            {{ __('messages.duration') }}:
                        </div>
                        <div id="map"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="locationModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content counter-mirror">
            <div class="modal-header">
                <h4 class="modal-title">{{ __('messages.location') }}</h4>
                <button class="close" data-dismiss="modal">&times</button>
            </div>
            <div class="modal-body">
                <table class="table table-hover text-center">
                    <tbody id="locations">

                    </tbody>
                </table>
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
    $('#add_fields').on('click', function() {
        let addtrips = $(`<div class="row">
                                <div class="col-md-1">
                                    <label for="driver">{{ __('messages.driver_value') }}</label>
                                    <input type="checkbox" class="driver-visible-checkbox" ><br>
                                    <input type="hidden" name="driver_value[]" value="0">
                                </div>
                                <div class="col-md-3">
                                    <label for="name">{{ __('messages.name') }}</label>
                                    <input type="text" name="name[]" class="form-control"
                                        placeholder="{{ __('messages.name') }}" required /><br>
                                </div>
                                <div class="col-md-3">
                                    <label for="values">{{ __('messages.value') }}</label>
                                    <input type="text" name="value[]" class="form-control"
                                        placeholder="{{ __('messages.value') }}" required /><br>
                                </div>
                                <div class="col-md-3 remove">
                                    <button type="button"
                                        class="btn btn-danger btn-clean">{{ __('messages.remove') }}</button><br>
                                </div>
                            </div>`)
        $('#tripfields').append(addtrips)
    })

    $('#tripfields').on('click', '.remove', function() {
        $(this).parent().remove();
    });

    $(document).on('change', '.driver-visible-checkbox', function() {
        var hiddenInput = $(this).closest('.row').find('input[name="driver_value[]"]');
        console.log(hiddenInput);
        hiddenInput.val(this.checked ? '1' : '0');
    });

    $(document).on('change', '#customers', function() {
        var customer = $('#customers option:selected').attr('data-locations')
        customer = JSON.parse(customer)
        console.log(customer);
        // console.log(JSON.parse(customer));
        let html = ''

        $("#customer_phone").val(customer.phone)
        $("#customer_name").val(customer.name)
        $("#customer_company").val(customer.company_name)
        if (customer.locations.length > 0) {
            customer.locations.forEach(function(item, index) {
                html +=
                    `<tr data-dismiss="modal" class="py-3 customer-location-address"><td><b>${item.name}:</b>${item.location}</td></tr>`
            })
        } else {
            html = '<div class="text-center">No Locations found</div>';
        }
        $("#locations").html(html)
    });

    var closestInput
    $(document).on('click', '.customer-location-address', function() {
        let string = $(this).html();
        // console.log(html);

        var stringWithoutTags = string.replace(/(<([^>]+)>)/ig, '');

        // Find the position of the colon (:) character
        var colonPosition = stringWithoutTags.indexOf(':');

        // Extract the location after the colon
        var location = "";
        if (colonPosition !== -1) {
            location = stringWithoutTags.substring(colonPosition + 1).trim();
            closestInput.value = location;
            // $("#locationModal").modal('close');
            $("#locationModal").modal('hide');
        }


    });

    document.addEventListener('click', function(event) {
        // Check if the clicked element has the class 'previous-location'
        if (event.target.classList.contains('previous-location')) {
            // Find the closest input element within the ancestor form
            closestInput = event.target.nextElementSibling;

            console.log(closestInput);
            if (closestInput && closestInput.tagName === 'INPUT') {
                // Set the value of the closest input element
                // closestInput.value = 'Your Value Here';

                // Add your additional logic here
                // For example, you can perform other actions or trigger events
            }
        }
    });

    $(".trips-nav").click()
    $(".new-trip-nav").addClass("menu-item-active");

    document.querySelector('.start-end-location').addEventListener('click', function() {
        $('#formModal').modal('show');
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
                '<div class="row my-3"><label for="stop" class="col-md-1" style="margin-top: 5px;">Stop:</label><span class="btn btn-secondary previous-location btn-sm m-1" data-toggle="modal" data-target="#locationModal" title="Add from previously saved locations">+</span> <input type="text"  placeholder="Enter stop location" class="stop col-md-8 form-control mx-3" name="stops[]" required><button type="button" class="removeStop btn btn-danger btn-sm col-md-2">Remove Stop</button><label class="col-md-1" ></label><label for="description" class="col-md-2" style="margin-top: 20px;">Description:</label><textarea name="descriptions[]" cols="30" rows="2" class="stop_description form-control col-md-8  mt-2 ml-6" placeholder="Enter description"></textarea></div>'
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
            var stops = [],
                stop_descriptions = [];

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
                    '<span class="badge badge-success bg-success mr-2 p-2">Destination</span>');

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
