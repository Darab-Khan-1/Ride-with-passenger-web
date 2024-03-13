@include('includes/header')
<!--begin::Content-->
<style>
    /* Remove default list styles */
    ul {
        list-style: none;

    }

    /* Style odd list items with a light background color */
    ul li:nth-child(odd) {
        background-color: #f2f2f2;
    }

    /* Style even list items with a darker background color */
    ul li:nth-child(even) {
        background-color: #ffffff;
    }

    ul li {
        border-top: 1px solid #bbbbbb;
        border-left: 1px solid #bbbbbb;
    }
</style>
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">

    <div class="card">
        <div class="card-header flex-wrap border-0 pt-6 pb-0 row">
            <div class="card-title col-md-4">
                <h2>{{ __('messages.create_customer') }}</h2>
            </div>
            <div class="col-md-6">
            </div>
            <div class="card-toolbar col-md-2">
                <!--begin::Button-->
                <button type="submit" form="tripForm" class="btn  mr-2"
                    style="background: #ffc500">{{ __('messages.register') }}</button>
                <a href="{{ url('/customer') }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>

                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <!--begin::Entry-->
                <!--begin::Container-->
                <div class="px-5 col-md-12">
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
                            <form action="{{ url('register/link') }}" id="tripForm" method="POST" class="">
                                @csrf
                                <div class="form-group col-md-12">
                                    <label>{{ __('messages.name') }}:</label>
                                    <input type="text" name="link_name" class="form-control col-md-12" required
                                        placeholder="{{ __('messages.enter_name') }}" />
                                </div>
                                <div class=" col-md-12">
                                    <label>{{ __('messages.select_trips') }}:</label>
                                    <ul>
                                        @foreach ($trips as $trip)
                                            <li class="my-9 px-5 ">
                                                {{-- <label for="trip_{{ $trip->id }}">
                                                    {{ $trip->unique_id }} - {{ $trip->pickup_date  }} -  {{ $trip->event_name }}
                                                </label> --}}
                                                <div class="d-flex align-items-center">
                                                    <!--begin::Bullet-->
                                                    <span
                                                        class="bullet bullet-bar bg-secondary align-self-stretch"></span>
                                                    <!--end::Bullet-->
                                                    <!--begin::Checkbox-->
                                                    <label
                                                        class="checkbox checkbox-lg checkbox-light-success checkbox-inline flex-shrink-0 m-0 mx-4">
                                                        <input type="checkbox" id="trip_{{ $trip->id }}"
                                                            name="trips[]" value="{{ $trip->id }}">
                                                        <span></span>
                                                    </label>
                                                    <!--end::Checkbox-->
                                                    <!--begin::Text-->
                                                    <div class="d-flex flex-column flex-grow-1">
                                                        <a href="#"
                                                            class="text-dark-75 text-hover-primary font-weight-bold font-size-lg mb-1">Create
                                                            FireStone Logo</a>
                                                        <span class="text-muted font-weight-bold">Due in 2 Days</span>
                                                    </div>
                                                    <!--end::Text-->
                                                    <!--begin::Dropdown-->

                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Container-->
</div>



<!--end::Content-->




@include('includes/footer')

<script
    src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places&callback=initMap"
    defer></script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.trip-checkbox');
        const selectedTripIdsInput = document.getElementById('selected_trips');

        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                const selectedTripIds = Array.from(document.querySelectorAll(
                    '.trip-checkbox:checked')).map(function(checkbox) {
                    return checkbox.value;
                });
                selectedTripIdsInput.value = JSON.stringify(selectedTripIds);
            });
        });
    });
</script>
