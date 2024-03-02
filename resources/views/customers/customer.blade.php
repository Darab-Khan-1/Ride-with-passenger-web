@include('includes/header')
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css"
    rel="stylesheet" />

<style>
    .pac-container {
        z-index: 1000000000 !important;
    }

    #map {
        height: 700px;
        width: 100%;
    }
</style>
<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid counter-mirror" id="kt_content">
    <!--begin::Entry-->
    <div>
        <!--begin::Container-->
        <div class="px-5">
            <div class="card card-custom my-5">
                @if (session('success'))
                    <div class="alert alert-success m-2">
                        {{ session('success') }}
                        <button type="button" class="close counter-mirror" data-dismiss="alert" aria-label="Close">
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
                    <div class="card-title">
                        <h3 class="card-label">{{ __('messages.customer') }} ({{ $total }})
                        </h3>
                    </div>
                    <div class="card-toolbar">
                        <!--begin::Button-->
                        @can('create_employee')
                            <a href="{{ url('create/customer') }}" class="add_new btn  font-weight-bolder"
                                style="background: #ffc500">
                                <span class="svg-icon svg-icon-md">
                                    <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Flatten.svg-->
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24"></rect>
                                            <circle fill="#000000" cx="9" cy="15" r="6"></circle>
                                            <path
                                                d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z"
                                                fill="#000000" opacity="0.3"></path>
                                        </g>
                                    </svg>
                                    <!--end::Svg Icon-->
                                </span>{{ __('messages.add_new') }}
                            </a>
                        @endcan

                        <!--end::Button-->
                    </div>
                </div>
                <div class="card-body p-5" style="overflow-x: scroll;">

                    <table class="table" id="table"></table>
                </div>
                {{-- <div class="card-footer">
                </div> --}}
            </div>
        </div>
        <!--end::Container-->
    </div>
    <!--end::Entry-->
</div>


<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content counter-mirror">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">{{ __('messages.edit_customer') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <form class="form" action="{{ url('/update/customer') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            {{-- <div class="form-group col-md-6">
                                <label>{{__('messages.select_role')}}:</label> 
                                 <select class="form-control" name="role" id="role">
                                    <option value="">-- {{__('messages.select_role')}} --</option>
                                    @foreach ($roles as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select> 
                            </div>  --}}
                            <div class="form-group col-md-6">
                                <label>{{ __('messages.full_name') }}:</label>
                                <input type="text" name="name" id="name" class="form-control " required>
                            </div>
                            <div class="form-group col-md-6">
                                <label>{{ __('messages.email') }}:</label>
                                <input type="email" name="email" id="email" class="form-control " required
                                    placeholder="{{ __('messages.enter_email') }}" />
                            </div>
                            <div class="form-group col-md-6">
                                <label>{{ __('messages.phone') }}:</label>
                                <input type="text" name="phone" id="phone" class="form-control " required
                                    placeholder="{{ __('messages.phone') }}" />
                            </div>
                            <div class="form-group col-md-6">
                                <label>{{ __('messages.address') }}:</label>
                                <input type="text" name="address" id="address" class="form-control " required
                                    placeholder="{{ __('messages.address') }}" />
                            </div>
                            {{-- <div class="form-group col-md-6">
                                <label>License expiry:</label>
                                <input type="date" name="license_expiry" id="license_expiry" class="form-control "
                                    required placeholder="Enter license expiry" />
                            </div> --}}
                            <div class="form-group col-md-6">
                                <div class="row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ __('messages.avatar') }}:
                                    </label>
                                    <div class="col-lg-9 col-xl-6">
                                        <div class="image-input image-input-outline " id="kt_profile_avatar_edit"
                                            style="background-image: url({{ asset('assets/media/users/blank.png') }})">
                                            <div class="image-input-wrapper">
                                            </div>
                                            <label
                                                class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                                data-action="change" data-toggle="tooltip" title=""
                                                data-original-title="Change avatar">
                                                <i class="fa fa-pen icon-sm text-muted"></i>
                                                <input type="file" name="profile_avatar"
                                                    accept=".png, .jpg, .jpeg">
                                                <input type="hidden" name="profile_avatar_remove">
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

            </div>
            <div class="card-footer counter-mirror">
                <input type="hidden" name="user_id" id="user_id">
                <button type="submit" class="btn  mr-2 counter-mirror"
                    style="background: #ffc500">{{ __('messages.update') }}</button>
                <button type="reset" class="btn btn-secondary counter-mirror"
                    data-dismiss="modal">{{ __('messages.cancel') }}</button>
            </div>
            </form>
        </div>
        {{-- <div class="modal-footer">
            <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary font-weight-bold">Save changes</button>
        </div> --}}
    </div>
</div>
{{-- </div> --}}

<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content counter-mirror">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">{{ __('messages.delete_customer_2') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <p>{{ __('messages.delete_customer') }}</p>
            </div>
            <div class="modal-footer">
                <a id="deleteUrl" class="btn btn-primary font-weight-bold">{{ __('messages.yes') }}</a>
                <button type="button" class="btn btn-light-primary font-weight-bold"
                    data-dismiss="modal">{{ __('messages.no') }}</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="passChangeModal" tabindex="-1" role="dialog" aria-labelledby="passChangeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content counter-mirror">
            <div class="modal-header">
                <h5 class="modal-title" id="passChangeModalLabel">{{ __('messages.change_password') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <form action="{{ url('/change/customer/password') }}" method="post" id="changePasswordForm">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="user_id" id="changePassUser" />
                    <div class="form-group">
                        <label>{{ __('messages.new_password') }}:</label>
                        <input type="password" name="password" minlength="8" id="password" class="form-control "
                            required placeholder="" />
                    </div>
                    <div class="form-group">
                        <label>{{ __('messages.confirm_password') }}:</label>
                        <input type="password" name="password" minlength="8" id="confirm_password"
                            class="form-control " required placeholder="" />
                    </div>
                </div>
                <div class="card-footer">
                    <button type="button" id="change_password_button"
                        class="btn btn-primary mr-2">{{ __('messages.change') }}</button>
                    <button type="reset" class="btn btn-secondary"
                        data-dismiss="modal">{{ __('messages.cancel') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="approveModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">{{ __('messages.approve_driver') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body counter-mirror">
                <p>{{ __('messages.approve_driver_con') }}</p>
            </div>
            <div class="modal-footer counter-mirror">
                <a id="approveUrl" class="btn btn-primary font-weight-bold">{{ __('messages.yes') }}</a>
                <button type="button" class="btn btn-light-primary font-weight-bold"
                    data-dismiss="modal">{{ __('messages.no') }}</button>
            </div>
        </div>
    </div>
</div>




<!--end::Content-->
@include('includes/footer')

<script type="text/javascript">
    $(".users-nav").click()
    $(".customer-nav").addClass("menu-item-active");


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

    $(document).on('click', '.delete-user', function() {
        let user = $(this).attr('user_id');
        $("#deleteUrl").attr('href', "{{ url('/delete/customer') }}" + "/" + user);
        $("#deleteModal").modal('show');
    });
    $(document).on('click', '.change-pass', function() {
        let user = $(this).attr('user_id');
        $("#changePassUser").val(user);
        $("#passChangeModal").modal('show');
    });

    $("#change_password_button").on('click', function() {
        let password = $("#password").val()
        let confirm_password = $("#confirm_password").val()
        if (password.length < 8) {
            swal.fire({
                text: "Password must be atleast 8 character long",
                icon: "error",
                buttonsStyling: false,
                confirmButtonText: "Ok!",
                customClass: {
                    confirmButton: "btn font-weight-bold btn-light-danger"
                }
            }).then(function() {});
        } else if (password != confirm_password) {
            swal.fire({
                text: "Password do not match",
                icon: "error",
                buttonsStyling: false,
                confirmButtonText: "Ok!",
                customClass: {
                    confirmButton: "btn font-weight-bold btn-light-danger"
                }
            }).then(function() {});
        } else {
            $("#changePasswordForm").submit()
        }
    })

    $(document).on('click', '.add_new', function() {
        let role_id = ("#role_id").val()
    })


    $(document).on('click', '.edit-customer', function() {
        let user = $(this).attr('user_id');
    });
    var table = $('#table').DataTable({
        paging: true,
        // pageLength : parseInt(vv),
        responsive: false,
        processing: false,
        serverSide: false,

        ajax: {
            url: "{{ url('/customer') }}"
        },

        columns: [{
                data: 'avatar',
                title: '{{ __('messages.avatar') }}',
                render: function(data, type, row) {
                    // html = '<img src="' + data + '" height="100" width="100"></img>'
                    // if(row.online){
                    //     html += '<br><span class="font-weight-bold badge badge-success">Online</span>'
                    // }else{
                    //     html += '<br><span class="font-weight-bold badge badge-danger">Offline</span>'
                    // }
                    // return html
                    html = `<div class="image-wrapper">
                        <img src="` + data + `" alt="User Image" width="100" height="100">`
                    // if (row.online)
                    //     html += '<span class="status-dot online"></span>'
                    // else {
                    //     html += '<span class="status-dot offline"></span>'

                    // }
                    html += '</div>'
                    return html
                }
            },
            {
                data: 'name',
                title: '{{ __('messages.name') }}'
            },
            {
                data: 'user_id',
                title: '{{ __('messages.contact') }}',
                render: function(data, type, row) {
                    let html = ''
                    html += '<span class="font-weight-bold">Email: </span>' + row.user.email
                    html += '<br><span class="font-weight-bold">  Phone: </span>' + row.phone
                    html += '<br><span class="font-weight-bold">  Address: </span>' + row.address
                    return html
                }
            },
            {
                data: 'role_id',
                title: '{{ __('messages.role') }}',
                render: function(data, type, row) {
                    let html = ''
                    if (data != null)
                        html += '<span class="font-weight-bold">' + row.role.name + '</span>'
                    else
                        html += '<br><span class="text-danger">  NOT ASSIGNED </span>'
                    return html
                }
            },
            {
                data: 'company_name',
                title: '{{ __('messages.company') }}',
                render: function(data, type, row) {
                    let html = ''
                    html += '<span class="font-weight-bold"> Name: </span>' + row.company_name + '<br>'
                    if (row.company_phone != '')
                        html += '<span class="font-weight-bold"> Phone: </span>' + row
                        .company_phone
                    return html
                }
            },
            {
                data: "user_id",
                title: '{{ __('messages.action') }}',
                width: 150,
                render: function(data, type, row) {
                    let permission_icon =
                        `{{ asset('/assets/media/svg/icons/Communication/Shield-user.svg') }}`
                    let url = "{{ url('delete/employee') }}" + "/" + data
                    let edit_url = "{{ url('edit/customer') }}" + '/' + row.id

                    let html = ''
                    html += '<div class="row">'

                    html +=
                        '@can('delete_employee')<a href="javascript:void(0);" user_id="' +
                        data +
                        '" class="delete-user btn btn-sm btn-clean btn-icon" title="Delete">	                            <span class="svg-icon svg-icon-md">	                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">	                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">	                                        <rect x="0" y="0" width="24" height="24"></rect>	                                        <path d="M6,8 L6,20.5 C6,21.3284271 6.67157288,22 7.5,22 L16.5,22 C17.3284271,22 18,21.3284271 18,20.5 L18,8 L6,8 Z" fill="#000000" fill-rule="nonzero"></path>	                                        <path d="M14,4.5 L14,4 C14,3.44771525 13.5522847,3 13,3 L11,3 C10.4477153,3 10,3.44771525 10,4 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3"></path>	                                    </g>	                                </svg>	                            </span>	                        </a>@endcan'


                    html += '@can('update_employee')<a href="'+ edit_url +'" class="edit-customer btn btn-sm btn-clean btn-icon mr-2" title="Edit details">	                            <span class="svg-icon svg-icon-md">	                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">	                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">	                                        <rect x="0" y="0" width="24" height="24"></rect>	                                        <path d="M8,17.9148182 L8,5.96685884 C8,5.56391781 8.16211443,5.17792052 8.44982609,4.89581508 L10.965708,2.42895648 C11.5426798,1.86322723 12.4640974,1.85620921 13.0496196,2.41308426 L15.5337377,4.77566479 C15.8314604,5.0588212 16,5.45170806 16,5.86258077 L16,17.9148182 C16,18.7432453 15.3284271,19.4148182 14.5,19.4148182 L9.5,19.4148182 C8.67157288,19.4148182 8,18.7432453 8,17.9148182 Z" fill="#000000" fill-rule="nonzero" transform="translate(12.000000, 10.707409) rotate(-135.000000) translate(-12.000000, -10.707409) "></path>	                                        <rect fill="#000000" opacity="0.3" x="5" y="20" width="15" height="2" rx="1"></rect>	                                    </g>	                                </svg>	                            </span></a>@endcan'
                    // console.log(row.blocked);
                    html +=
                        '&nbsp;@can('update_employee')<a class="change-pass btn btn-sm btn-clean btn-icon mr-2" href="javascript:void(0);" user_id=' +
                        data +
                        ' ><span class="svg-icon svg-icon-md"><img title="Change Password" class="cursor-pointer" src="{{ asset('/assets/media/svg/icons/Code/Lock-overturning.svg') }}"/></span></a>@endcan'
                    return html;
                }
            }
        ],
        "autoWidth": false,

        "lengthMenu": [
            [10, 25, 50, -1],
            [10, 25, 50, "All"]
        ],
        dom: 'Bfrtip',
        buttons: [{
            extend: 'pdfHtml5',
            text: '{{ __('messages.pdf') }}',
            title: $('h3').text(),
            orientation: 'potrait',
            pageSize: 'LEGAL',
            exportOptions: {
                stripHtml: true,

                modifier: {
                    page: 'all'
                },
                columns: ':visible:not(:first-child):visible:not(:last-child):visible:not(:nth-child(5))'
            },
            customize: function(doc) {
                doc.content[1].table.widths = Array(doc.content[1].table.body[0]
                    .length + 1).join('*').split('');
                doc.defaultStyle.alignment = 'center';
                doc.styles.tableHeader.alignment = 'center';
            }
        }, {
            extend: 'print',
            text: '{{ __('messages.print') }}',
            title: $('h3').text(),
            exportOptions: {
                modifier: {
                    page: 'all'
                },
                columns: ':visible:not(:first-child):visible:not(:last-child):visible:not(:nth-child(5))'
            }
        }, {
            extend: 'excel',
            text: '{{ __('messages.excel') }}',
            title: $('h3').text(),
            exportOptions: {
                stripHtml: true,

                modifier: {
                    page: 'all'
                },
                columns: ':visible:not(:first-child):visible:not(:last-child):visible:not(:nth-child(5))'
            }
        }, {
            extend: 'copy',
            text: '{{ __('messages.copy') }}',
            title: $('h3').text(),
            exportOptions: {
                stripHtml: true,

                modifier: {
                    page: 'all'
                },
                columns: ':visible:not(:first-child):visible:not(:last-child):visible:not(:nth-child(5))'
            }
        }]
    });
</script>

<script
    src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places&callback=initMap"
    defer></script>

<script>
    // let inputdata = [];
    $("#addStop").on('click', function() {
        let add = $(
            ` <div class="row my-3">
                                        <label for="stop" class="col-md-1"
                                            style="margin-top: 5px;">{{ __('messages.name') }}</label>
                                        <input type="text" class=" col-md-8 form-control mx-3"
                                            placeholder="{{ __('messages.name_location') }}" name="name[]"
                                            required>
                                        <button type="button"
                                            class="removeStop btn btn-danger btn-sm col-md-2">{{ __('messages.remove') }}</button>
                                        <label class="col-md-1"></label><label for="location" class="col-md-2"
                                            style="margin-top: 20px;">{{ __('messages.location') }}:</label>
                                        <input name="location[]" cols="30" rows="2"
                                            class="stop  form-control col-md-8  mt-2 ml-6"
                                            placeholder="{{ __('messages.customer_location') }}" required>
                                        <input type="hidden" name="latlong[]" class="lat">
                                    </div>`
        );
        $('#stopsContainer').append(add);
        enableAutocomplete(add.find('.stop')[0]);
    });

    $("#stopsContainer").on('click', '.removeStop', function() {
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
