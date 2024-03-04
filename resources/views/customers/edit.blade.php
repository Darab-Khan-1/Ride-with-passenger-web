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
    <form action="{{ url('update/customer') }}" method="POST" class="counter-mirror" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header flex-wrap border-0 pt-6 pb-0 row">
                <div class="card-title col-md-4">
                    <h2>{{ __('messages.create_customer') }}</h2>
                </div>
                <div class="col-md-6">
                </div>
                <div class="card-toolbar col-md-2">
                    <!--begin::Button-->
                    <button type="submit" class="btn  mr-2"
                        style="background: #ffc500">{{ __('messages.update') }}</button>
                    <a href="{{ url('/customer') }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>

                    <!--end::Button-->
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <!--begin::Entry-->
                    <!--begin::Container-->
                    <div class="px-5 col-md-4">
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
                            <div class="card-body">
                                <div>
                                    <input type="hidden" name="user_id" id="user_id" value="{{ $customer->user_id }}">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>{{ __('messages.full_name') }}:</label>
                                    <input type="text" name="customer_name" class="form-control col-md-12" required
                                        value="{{ $customer->name }}" />
                                </div>
                                <div class="form-group col-md-12">
                                    <label>{{ __('messages.email') }}:</label>
                                    <input type="email" name="email" class="form-control col-md-12" required
                                        value="{{ $customer->user->email }}"" />
                                </div>
                                <div class="form-group col-md-12">
                                    <label>{{ __('messages.phone') }}:</label>
                                    <input type="text" name="phone" class="form-control" required
                                        value="{{ $customer->phone }}" />
                                </div>
                                <div class="form-group col-md-12">
                                    <label>{{ __('messages.address') }}:</label>
                                    <input type="text" name="address" class="form-control" required
                                        value="{{ $customer->address }}" />
                                </div>
                                <div class="form-group col-md-12">
                                    <label>{{ __('messages.company') }}:</label>
                                    <input type="text" name="company_name" class="form-control" required
                                        value="{{ $customer->company_name }}" />
                                </div>
                                <div class="form-group col-md-12">
                                    <label>{{ __('messages.company_phone') }}:</label>
                                    <input type="text" name="company_phone" class="form-control"
                                        value="{{ $customer->company_phone }}" />
                                </div>

                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ __('messages.avatar') }}:
                                    </label>
                                    <div class="col-lg-9 col-xl-6">
                                        <div class="image-input image-input-outline" id="kt_profile_avatar"
                                            style="background-image: url({{ asset('assets/media/users/blank.png') }})">
                                            <div class="image-input-wrapper"
                                                style="background-image: url({{ asset($customer->avatar) }})">
                                            </div>
                                            <label
                                                class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                                data-action="change" data-toggle="tooltip" title=""
                                                data-original-title="Change avatar">
                                                <i class="fa fa-pen icon-sm text-muted"></i>
                                                <input type="file" value="" name="profile_avatar"
                                                    accept=".png, .jpg, .jpeg">
                                                <input type="hidden" name="profile_avatar_remove" >
                                            </label>
                                            <span
                                                class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                                data-action="cancel" data-toggle="tooltip" title=""
                                                data-original-title="Cancel avatar">
                                                <i class="ki ki-bold-close icon-xs text-muted"></i>
                                            </span>
                                            <span
                                                class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                                data-action="remove" data-toggle="tooltip" title=""
                                                data-original-title="Remove avatar">
                                                <i class="ki ki-bold-close icon-xs text-muted"></i>
                                            </span>
                                        </div>
                                        <span class="form-text text-muted">{{ __('messages.allowed_files') }} .png
                                            .jpg
                                            .jpeg</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-5 col-md-8">
                        <div class="card card-custom">
                            <div>
                                <h4 class="modal-title" style="text-align: center">
                                    {{ __('messages.select_customer') }}</h4>
                                <div class="container">
                                    <div class="map_box_container">
                                        <div class="mb-3" id="map-overlay">
                                        </div>
                                        <div id="map" style="height: 558px;"></div>
                                        <div class="mb-3">
                                            <div id="stopsContainer">
                                                @foreach ($customer->locations as $customer_location)
                                                <div class="row my-3">
                                                        <input type="hidden" name="location_id[]"
                                                            value="{{ $customer_location->id }}"
                                                            id="customer_location_id">
                                                        <label for="stop" class="col-md-2"
                                                            style="margin-top: 5px;">{{ __('messages.name') }}</label>
                                                        <input type="text" class=" col-md-7 form-control mx-3"
                                                            placeholder="{{ __('messages.name_location') }}"
                                                            name="name[]" value="{{ $customer_location->name }}"
                                                            required>
                                                        <button type="button"
                                                            class="removeStop btn btn-danger btn-sm col-md-2">{{ __('messages.remove') }}</button>
                                                        <label class="col-md-2"></label><label for="location"
                                                            style="margin-top: 20px;">{{ __('messages.location') }}:</label>
                                                        <input name="location[]"
                                                            value="{{ $customer_location->location }}" cols="30"
                                                            rows="2"
                                                            class="stop  form-control col-md-7  mt-2 ml-6"
                                                            placeholder="{{ __('messages.customer_location') }}"
                                                            required>
                                                        <input type="hidden" value="{{ $customer_location->latlng }}"
                                                            name="latlong[]" class="lat">
                                                        </div>
                                                        @endforeach
                                            </div>
                                        </div>
                                        <button type="button" id="addStop"
                                            class="btn btn-secondary my-3">{{ __('messages.add') }}
                                            {{ __('messages.location') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </form>
</div>
<!--end::Container-->
</div>



<!--end::Content-->




@include('includes/footer')

<script
    src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places&callback=initMap"
    defer></script>


<script>
    var KTProfile = function() {
        // Elements
        var avatar;
        // Private functions
        var _initAside = function() {
            // Mobile offcanvas for mobile mode
            offcanvas = new KTOffcanvas('kt_profile_aside', {
                overlay: true,
                baseClass: 'offcanvas-mobile',
                //closeBy: 'kt_user_profile_aside_close',
                toggleBy: 'kt_subheader_mobile_toggle'
            });
        }

        var _initForm = function() {
            avatar = new KTImageInput('kt_profile_avatar');
            avatar = new KTImageInput('kt_profile_avatar_edit');
        }

        return {
            // public functions
            init: function() {
                _initAside();
                _initForm();
            }
        };
    }();

    $(document).ready(function() {
        KTProfile.init();
    });

    $('#addStop').on('click', function() {
        var stopInput = $(
            ` <div class="row my-3">
                                                    <input type="hidden" name="location_id[]"
                                                     value="0">
                                                    <label for="stop" class="col-md-2"
                                                        style="margin-top: 5px;">{{ __('messages.name') }}</label>
                                                    <input type="text" class=" col-md-7 form-control mx-3"
                                                        placeholder="{{ __('messages.name_location') }}"
                                                        name="name[]" required>
                                                    <button type="button"
                                                        class="removeStop btn btn-danger btn-sm col-md-2">{{ __('messages.remove') }}</button>

                                                    <label class="col-md-2"></label><label for="location"
                                                        style="margin-top: 20px;">{{ __('messages.location') }}:</label>
                                                    <input name="location[]" cols="30" rows="2"
                                                        class="stop  form-control col-md-7  mt-2 ml-6"
                                                        placeholder="{{ __('messages.customer_location') }}" required>
                                                    <input type="hidden" name="latlong[]" class="lat" >
                                                </div>`
        );
        $('#stopsContainer').append(stopInput);
        // Enable autocomplete for the new stop input
        enableAutocomplete(stopInput.find('.stop')[0]);
    });

    // Add event listener to remove stop button
    $('#stopsContainer').on('click', '.removeStop', function() {
        $(this).parent().remove();
    });

    var map;

    function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
            center: {
                lat: 37.7749,
                lng: -122.4194
            }, // Default center (San Francisco)
            zoom: 12
        });

        $('.stop').each(function() {
            enableAutocomplete(this);
        });
    }

    function enableAutocomplete(input) {
        var autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.setTypes(['geocode']);
        autocomplete.addListener('place_changed', function() {
            var place = autocomplete.getPlace();

            createMarker(place.geometry.location, place.name, place.geometry.location.lat(), place.geometry
                .location.lng());
            place.geometry.location.lat()


            let latlng = place.geometry.location.lat() + ", " + place.geometry.location.lng()

            var hiddenLatInput = $(input).closest('.row').find('.lat');
            hiddenLatInput.val(latlng);


        });
    }

    function createMarker(location, name, lat, lng) {
        var marker = new google.maps.Marker({
            position: location,
            map: map,
            title: `${name} (Lat: ${lat}, Lng: ${lng})`
        });
        map.setCenter(location);
    }

    $(document).on('click', '.location_id', function() {
        let id = $(this).attr('user_id');
        alert(id);
        $('#customer_location_id').val(id)
        console.log(id);
    });
</script>
