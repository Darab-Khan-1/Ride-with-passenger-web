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

    .status-dot.online {
        background-color: lime;
        /* Green dot for online status */
    }

    .status-dot.offline {
        background-color: red;
        /* Orange dot for offline status */
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
        <div class="px-5">
            <div class="card card-custom">
                <div class="card-body p-5">
                    <div class="row">
                        <div class="col-xl-3">
                            <!--begin::Tiles Widget 12-->
                            <div class="card card-custom gutter-b" style="height: 150px">
                                <div class="card-body">
                                    <span class="svg-icon svg-icon-3x ">
                                        <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Group.svg-->
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                                            viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                                <path
                                                    d="M18,14 C16.3431458,14 15,12.6568542 15,11 C15,9.34314575 16.3431458,8 18,8 C19.6568542,8 21,9.34314575 21,11 C21,12.6568542 19.6568542,14 18,14 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z"
                                                    fill="#000000" fill-rule="nonzero" opacity="0.3"></path>
                                                <path
                                                    d="M17.6011961,15.0006174 C21.0077043,15.0378534 23.7891749,16.7601418 23.9984937,20.4 C24.0069246,20.5466056 23.9984937,21 23.4559499,21 L19.6,21 C19.6,18.7490654 18.8562935,16.6718327 17.6011961,15.0006174 Z M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z"
                                                    fill="#000000" fill-rule="nonzero"></path>
                                            </g>
                                        </svg>
                                        <!--end::Svg Icon-->
                                    </span>
                                    <div class="text-dark font-weight-bolder font-size-h2 mt-3">{{ $total }}
                                    </div>
                                    <a href="#"
                                        class="text-muted text-hover-primary font-weight-bold font-size-lg mt-1">Total
                                        Drivers</a>
                                </div>
                            </div>
                            <!--end::Tiles Widget 12-->
                        </div>
                    </div>
                </div>
            </div>
            <div class="card card-custom my-5">
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
                    <div class="card-title">
                        <h3 class="card-label">Drivers
                            {{-- <span class="d-block text-muted pt-2 font-size-sm">Companies made easy</span> --}}
                        </h3>
                    </div>
                    <div class="card-toolbar">
                        <!--begin::Button-->
                        <button data-toggle="modal" data-target="#addModal" class="btn  font-weight-bolder"
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
                            </span>Add New
                        </button>

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
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Register Driver</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <form class="form" action="{{ url('/register/driver') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Full Name:</label>
                                <input type="text" name="name" class="form-control form-control-solid" required
                                    placeholder="Enter full name" />
                            </div>
                            <div class="form-group col-md-6">
                                <label>Email:</label>
                                <input type="email" name="email" class="form-control form-control-solid" required
                                    placeholder="Enter email" />
                            </div>
                            <div class="form-group col-md-6">
                                <label>Password:</label>
                                <input type="password" name="password" class="form-control form-control-solid"
                                    required placeholder="Enter password" />
                            </div>
                            <div class="form-group col-md-6">
                                <label>Phone:</label>
                                <input type="text" name="phone" class="form-control form-control-solid" required
                                    placeholder="Enter phone" />
                            </div>
                            <div class="form-group col-md-6">
                                <label>License Number:</label>
                                <input type="text" name="license_no" class="form-control form-control-solid"
                                    required placeholder="Enter license number" />
                            </div>
                            {{-- <div class="form-group col-md-6">
                                <label>License expiry:</label>
                                <input type="date" name="license_expiry" class="form-control form-control-solid"
                                    required placeholder="Enter license expiry" />
                            </div> --}}
                            <div class="form-group row">
                                <label class="col-xl-3 col-lg-3 col-form-label">Avatar: </label>
                                <div class="col-lg-9 col-xl-6">
                                    <div class="image-input image-input-outline" id="kt_profile_avatar"
                                        style="background-image: url({{ asset('assets/media/users/blank.png') }})">
                                        <div class="image-input-wrapper"
                                            style="background-image: url({{ asset('assets/media/users/blank.png') }})">
                                        </div>
                                        <label
                                            class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                            data-action="change" data-toggle="tooltip" title=""
                                            data-original-title="Change avatar">
                                            <i class="fa fa-pen icon-sm text-muted"></i>
                                            <input type="file" name="profile_avatar" accept=".png, .jpg, .jpeg">
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
                                    <span class="form-text text-muted">Allowed files .png .jpg .jpeg</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn  mr-2" style="background: #ffc500">Register</button>
                        <button type="reset" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
            {{-- <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary font-weight-bold">Save changes</button>
            </div> --}}
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Driver</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <form class="form" action="{{ url('/update/driver') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Full Name:</label>
                                <input type="text" name="name" id="name"
                                    class="form-control form-control-solid" required placeholder="Enter full name" />
                            </div>
                            <div class="form-group col-md-6">
                                <label>Email:</label>
                                <input type="email" name="email" id="email"
                                    class="form-control form-control-solid" required placeholder="Enter email" />
                            </div>
                            <div class="form-group col-md-6">
                                <label>Phone:</label>
                                <input type="text" name="phone" id="phone"
                                    class="form-control form-control-solid" required placeholder="Enter phone" />
                            </div>
                            <div class="form-group col-md-6">
                                <label>License Number:</label>
                                <input type="text" name="license_no" id="license_number"
                                    class="form-control form-control-solid" required
                                    placeholder="Enter license number" />
                            </div>
                            {{-- <div class="form-group col-md-6">
                                <label>License expiry:</label>
                                <input type="date" name="license_expiry" id="license_expiry"
                                    class="form-control form-control-solid" required
                                    placeholder="Enter license expiry" />
                            </div> --}}
                            <div class="form-group col-md-6">
                                <div class="row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">Avatar: </label>
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
                                        <span class="form-text text-muted">Allowed files .png .jpg .jpeg</span>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>

            </div>
            <div class="card-footer">
                <input type="hidden" name="user_id" id="user_id">
                <button type="submit" class="btn  mr-2" style="background: #ffc500">Update</button>
                <button type="reset" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
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
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete Driver</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure to delete this driver?</p>
            </div>
            <div class="modal-footer">
                <a id="deleteUrl" class="btn btn-primary font-weight-bold">Yes</a>
                <button type="button" class="btn btn-light-primary font-weight-bold"
                    data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="passChangeModal" tabindex="-1" role="dialog" aria-labelledby="passChangeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="passChangeModalLabel">Change Password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <form action="{{ url('/change/password') }}" method="post" id="changePasswordForm">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="user_id" id="changePassUser" />
                    <div class="form-group">
                        <label>New Password:</label>
                        <input type="password" name="password" minlength="8" id="password"
                            class="form-control form-control-solid" required placeholder="" />
                    </div>
                    <div class="form-group">
                        <label>Confirm Password:</label>
                        <input type="password" name="password" minlength="8" id="confirm_password"
                            class="form-control form-control-solid" required placeholder="" />
                    </div>
                </div>
                <div class="card-footer">
                    <button type="button" id="change_password_button" class="btn btn-primary mr-2">Change</button>
                    <button type="reset" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
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
                <h5 class="modal-title" id="deleteModalLabel">Approve Driver</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure to approve this driver?</p>
            </div>
            <div class="modal-footer">
                <a id="approveUrl" class="btn btn-primary font-weight-bold">Yes</a>
                <button type="button" class="btn btn-light-primary font-weight-bold"
                    data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>
<!--end::Content-->
@include('includes/footer')

<script type="text/javascript">
    $(".users-nav").click()
    $("#drivers_nav").addClass("menu-item-active");


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
        $("#deleteUrl").attr('href', "{{ url('/delete/driver') }}" + "/" + user);
        $("#deleteModal").modal('show');
    });
    $(document).on('click', '.change-pass', function() {
        let user = $(this).attr('user_id');
        $("#changePassUser").val(user);
        $("#passChangeModal").modal('show');
    });
    $(document).on('click', '.approve-user', function() {
        let user = $(this).attr('user_id');
        $("#approveUrl").attr('href', "{{ url('/approve/driver') }}" + "/" + user);
        $("#approveModal").modal('show');
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



    $(document).on('click', '.edit-user', function() {
        let user = $(this).attr('user_id');
        // $("input[type=checkbox]").removeAttr('checked')
        $.ajax({
            url: "{{ url('/get/driver') }}" + "/" + user,
            method: "GET",
            beforeSend: function() {
                $("#editModal").modal('show');
            },
            success: function(data) {
                console.log(data);
                $("#user_id").val(data.user_id)
                $("#name").val(data.name)
                $("#email").val(data.user.email)
                $("#phone").val(data.phone)
                $("#license_number").val(data.license_no)
                // $("#license_expiry").val(data.license_expiry)

                $("#kt_profile_avatar_edit").css('background-image', 'url(' + data.avatar + ')');
            }
        });
    });
    var table = $('#table').DataTable({
        paging: true,
        // pageLength : parseInt(vv),
        responsive: false,
        processing: false,
        serverSide: false,

        ajax: {
            url: "{{ url('/drivers') }}"
        },

        columns: [{
                data: 'avatar',
                title: 'Avatar',
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
                title: 'Name'
            },
            {
                data: 'user',
                title: 'Contact',
                render: function(data, type, row) {
                    let html = ''
                    html += '<span class="font-weight-bold">Email: </span>' + data.email
                    html += '<br><span class="font-weight-bold">  Phone: </span>' + row.phone
                    return html
                }
            },
            {
                data: 'license_no',
                title: 'License',
                render: function(data, type, row) {
                    let html = ''
                    html += '<span class="font-weight-bold">' + data + '</span>'
                    return html
                }
            },
            // {
            //     data: 'approved',
            //     title: 'Status',
            //     render: function(data, type, row) {
            //         let html = ''
            //         if (data) {
            //             html +=
            //                 '<br><span class="font-weight-bold badge badge-secondary">Approved</span>'
            //         } else {
            //             html +=
            //                 '<br><span class="font-weight-bold badge badge-primary approve-user cursor-pointer" user_id="' +
            //                 row.user_id + '"><i class="fa fa-check"></i>&nbsp;Approve</span>'
            //         }
            //         return html
            //     }
            // },
            {
                data: "user_id",
                title: "Action",
                width: 150,
                render: function(data, type, row) {
                    let permission_icon =
                        '{{ asset('/assets/media/svg/icons/Communication/Shield-user.svg') }}'
                    let url = "{{ url('delete/driver') }}" + "/" + data
                    let html = ''
                    html += '<div class="row">'

                    html += '<a href="javascript:void(0);" user_id="' + data +
                        '" class="delete-user btn btn-sm btn-clean btn-icon" title="Delete">	                            <span class="svg-icon svg-icon-md">	                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">	                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">	                                        <rect x="0" y="0" width="24" height="24"></rect>	                                        <path d="M6,8 L6,20.5 C6,21.3284271 6.67157288,22 7.5,22 L16.5,22 C17.3284271,22 18,21.3284271 18,20.5 L18,8 L6,8 Z" fill="#000000" fill-rule="nonzero"></path>	                                        <path d="M14,4.5 L14,4 C14,3.44771525 13.5522847,3 13,3 L11,3 C10.4477153,3 10,3.44771525 10,4 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3"></path>	                                    </g>	                                </svg>	                            </span>	                        </a>'


                    html += '<a href="javascript:;" user_id=' + data +
                        ' class="edit-user btn btn-sm btn-clean btn-icon mr-2" title="Edit details">	                            <span class="svg-icon svg-icon-md">	                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">	                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">	                                        <rect x="0" y="0" width="24" height="24"></rect>	                                        <path d="M8,17.9148182 L8,5.96685884 C8,5.56391781 8.16211443,5.17792052 8.44982609,4.89581508 L10.965708,2.42895648 C11.5426798,1.86322723 12.4640974,1.85620921 13.0496196,2.41308426 L15.5337377,4.77566479 C15.8314604,5.0588212 16,5.45170806 16,5.86258077 L16,17.9148182 C16,18.7432453 15.3284271,19.4148182 14.5,19.4148182 L9.5,19.4148182 C8.67157288,19.4148182 8,18.7432453 8,17.9148182 Z" fill="#000000" fill-rule="nonzero" transform="translate(12.000000, 10.707409) rotate(-135.000000) translate(-12.000000, -10.707409) "></path>	                                        <rect fill="#000000" opacity="0.3" x="5" y="20" width="15" height="2" rx="1"></rect>	                                    </g>	                                </svg>	                            </span></a>'
                    // console.log(row.blocked);
                    html +=
                        '&nbsp;<a class="btn btn-sm btn-clean btn-icon mr-2" href="javascript:void(0);"><span class="svg-icon svg-icon-md"><img title="Change Password" user_id=' +
                        data +
                        ' class="change-pass cursor-pointer" src="{{ asset('/assets/media/svg/icons/Code/Lock-overturning.svg') }}"/></span></a>'

                    html += `<a href="` + "{{ url('live/location') }}" + "/" + row.device_id + `"><span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo1\dist/../src/media/svg/icons\Map\Marker1.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24"/>
                                    <path d="M5,10.5 C5,6 8,3 12.5,3 C17,3 20,6.75 20,10.5 C20,12.8325623 17.8236613,16.03566 13.470984,20.1092932 C12.9154018,20.6292577 12.0585054,20.6508331 11.4774555,20.1594925 C7.15915182,16.5078313 5,13.2880005 5,10.5 Z M12.5,12 C13.8807119,12 15,10.8807119 15,9.5 C15,8.11928813 13.8807119,7 12.5,7 C11.1192881,7 10,8.11928813 10,9.5 C10,10.8807119 11.1192881,12 12.5,12 Z" fill="#000000" fill-rule="nonzero"/>
                                </g>
                            </svg><!--end::Svg Icon--></span></a>`

                    html += '</div>'
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
            text: 'PDF',
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
            text: 'PRINT',
            title: $('h3').text(),
            exportOptions: {
                modifier: {
                    page: 'all'
                },
                columns: ':visible:not(:first-child):visible:not(:last-child):visible:not(:nth-child(5))'
            }
        }, {
            extend: 'excel',
            text: 'EXCEL',
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
            text: 'COPY',
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
