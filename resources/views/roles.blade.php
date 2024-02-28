@include('includes/header')
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> --}}


{{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" /> --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css"
    rel="stylesheet" />
<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid counter-mirror" id="kt_content">
    <!--begin::Entry-->
    <div>
        <!--begin::Container-->
        <div class="px-5">
            <div class="card card-custom my-5">
                @if (session('success'))
                    <div class="alert alert-success m-2 ">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger m-2 ">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">{{__('messages.roles')}} ({{ $total }})
                            {{-- <span class="d-block text-muted pt-2 font-size-sm">Companies made easy</span> --}}
                        </h3>
                    </div>
                    <div class="card-toolbar">
                        <!--begin::Button-->
                        @can("create_role")
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
                            </span>{{__('messages.add_new')}}
                        </button>
                        @endcan
                    </div>
                </div>
                <div class="card-body p-5 " style="overflow-x: scroll;">

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
<div class="modal fade " id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title counter-mirror" id="addModalLabel">{{__('messages.register_driver')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body ">
                <form class="form" action="{{ url('/register/role') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">

                        <div class="row">
                            <div class="form-group col-md-12 counter-mirror">
                                <label>{{__('messages.name')}}:</label>
                                <input type="text" name="name" class="form-control form-control-solid" required
                                    placeholder="{{__('messages.enter_full_name')}}" />
                            </div>
                            <div class="col-md-12 counter-mirror">
                                <div class="form-group row">
                                    <label class="col-3 col-form-label">{{__('messages.drivers')}}</label>
                                    <div class="col-9 col-form-label">
                                        <div class="checkbox-inline">
                                            <label class="checkbox checkbox-success">
                                                <input type="checkbox" name="create_driver" />
                                                <span></span>
                                                {{__('messages.add')}}
                                            </label>
                                            <label class="checkbox checkbox-primary">
                                                <input type="checkbox" name="view_driver" />
                                                <span></span>
                                                 {{__('messages.view')}}
                                            </label>
                                            <label class="checkbox checkbox-info">
                                                <input type="checkbox" name="update_driver" />
                                                <span></span>
                                                 {{__('messages.update')}}
                                            </label>
                                            <label class="checkbox checkbox-danger">
                                                <input type="checkbox" name="delete_driver" />
                                                <span></span>
                                                 {{__('messages.delete')}}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 counter-mirror">
                                <div class="form-group row">
                                    <label class="col-3 col-form-label">{{__('messages.trips')}}</label>
                                    <div class="col-9 col-form-label">
                                        <div class="checkbox-inline">
                                            <label class="checkbox checkbox-success">
                                                <input type="checkbox" name="create_trip" />
                                                <span></span>
                                                 {{__('messages.add')}}
                                            </label>
                                            <label class="checkbox checkbox-primary">
                                                <input type="checkbox" name="view_trip" />
                                                <span></span>
                                                 {{__('messages.view')}}
                                            </label>
                                            <label class="checkbox checkbox-info">
                                                <input type="checkbox" name="update_trip" />
                                                <span></span>
                                                 {{__('messages.update')}}
                                            </label>
                                            <label class="checkbox checkbox-danger">
                                                <input type="checkbox" name="delete_trip" />
                                                <span></span>
                                                 {{__('messages.delete')}}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 counter-mirror">
                                <div class="form-group row">
                                    <label class="col-3 col-form-label">{{__('messages.employees')}}</label>
                                    <div class="col-9 col-form-label">
                                        <div class="checkbox-inline">
                                            <label class="checkbox checkbox-success">
                                                <input type="checkbox" name="create_employee" />
                                                <span></span>
                                                 {{__('messages.add')}}
                                            </label>
                                            <label class="checkbox checkbox-primary">
                                                <input type="checkbox" name="view_employee" />
                                                <span></span>
                                                 {{__('messages.view')}}
                                            </label>
                                            <label class="checkbox checkbox-info">
                                                <input type="checkbox" name="update_employee" />
                                                <span></span>
                                                 {{__('messages.update')}}
                                            </label>
                                            <label class="checkbox checkbox-danger">
                                                <input type="checkbox" name="delete_employee" />
                                                <span></span>
                                                 {{__('messages.delete')}}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 counter-mirror">
                                <div class="form-group row">
                                    <label class="col-3 col-form-label">{{__('messages.roles')}}</label>
                                    <div class="col-9 col-form-label">
                                        <div class="checkbox-inline">
                                            <label class="checkbox checkbox-success">
                                                <input type="checkbox" name="create_role" />
                                                <span></span>
                                                 {{__('messages.add')}}
                                            </label>
                                            <label class="checkbox checkbox-primary">
                                                <input type="checkbox" name="view_role" />
                                                <span></span>
                                                 {{__('messages.view')}}
                                            </label>
                                            <label class="checkbox checkbox-info">
                                                <input type="checkbox" name="update_role" />
                                                <span></span>
                                                 {{__('messages.update')}}
                                            </label>
                                            <label class="checkbox checkbox-danger">
                                                <input type="checkbox" name="delete_role" />
                                                <span></span>
                                                 {{__('messages.delete')}}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 counter-mirror">
                                <div class="form-group row">
                                    <label class="col-3 col-form-label">{{__('messages.tracking')}}</label>
                                    <div class="col-9 col-form-label">
                                        <div class="checkbox-inline">
                                            <label class="checkbox checkbox-success">
                                                <input type="checkbox" name="live_tracking" />
                                                <span></span>
                                                 {{__('messages.live_tracking')}}
                                            </label>
                                            <label class="checkbox checkbox-primary">
                                                <input type="checkbox" name="playback" />
                                                <span></span>
                                                {{__('messages.play_back')}}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer counter-mirror">
                        <button type="submit" class="btn  mr-2" style="background: #ffc500">{{__('messages.register')}}</button>
                        <button type="reset" class="btn btn-secondary" data-dismiss="modal">{{__('messages.cancel')}}</button>
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

<div class="modal fade " id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title counter-mirror" id="editModalLabel">{{__('messages.edit_role')}}</h5>
                <button type="button" class="close " data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body counter-mirror">
                <form class="form" action="{{ url('/update/role') }}" id="editForm" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="card-body ">

                        <div class="row">
                            <div class="form-group col-md-12">
                                <label>{{__('messages.name')}}:</label>
                                <input type="text" name="name" id="name" class="form-control form-control-solid" required
                                    placeholder="Enter full name" />
                            </div>
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-3 col-form-label">{{__('messages.drivers')}}</label>
                                    <div class="col-9 col-form-label">
                                        <div class="checkbox-inline">
                                            <label class="checkbox checkbox-success">
                                                <input type="checkbox" name="create_driver" id="create_driver" />
                                                <span></span>
                                                {{__('messages.add')}}
                                            </label>
                                            <label class="checkbox checkbox-primary">
                                                <input type="checkbox" name="view_driver" id="view_driver" />
                                                <span></span>
                                                {{__('messages.view')}}
                                            </label>
                                            <label class="checkbox checkbox-info">
                                                <input type="checkbox" name="update_driver" id="update_driver" />
                                                <span></span>
                                                {{__('messages.update')}}
                                            </label>
                                            <label class="checkbox checkbox-danger">
                                                <input type="checkbox" name="delete_driver" id="delete_driver" />
                                                <span></span>
                                                {{__('messages.delete')}}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-3 col-form-label">{{__('messages.trips')}}</label>
                                    <div class="col-9 col-form-label">
                                        <div class="checkbox-inline">
                                            <label class="checkbox checkbox-success">
                                                <input type="checkbox" name="create_trip" id="create_trip" />
                                                <span></span>
                                                {{__('messages.add')}}
                                            </label>
                                            <label class="checkbox checkbox-primary">
                                                <input type="checkbox" name="view_trip" id="view_trip" />
                                                <span></span>
                                                {{__('messages.view')}}
                                            </label>
                                            <label class="checkbox checkbox-info">
                                                <input type="checkbox" name="update_trip" id="update_trip" />
                                                <span></span>
                                                {{__('messages.update')}}
                                            </label>
                                            <label class="checkbox checkbox-danger">
                                                <input type="checkbox" name="delete_trip" id="delete_trip" />
                                                <span></span>
                                                {{__('messages.delete')}}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-3 col-form-label">{{__('messages.employees')}}</label>
                                    <div class="col-9 col-form-label">
                                        <div class="checkbox-inline">
                                            <label class="checkbox checkbox-success">
                                                <input type="checkbox" name="create_employee" id="create_employee" />
                                                <span></span>
                                                {{__('messages.add')}}
                                            </label>
                                            <label class="checkbox checkbox-primary">
                                                <input type="checkbox" name="view_employee" id="view_employee" />
                                                <span></span>
                                                {{__('messages.view')}}
                                            </label>
                                            <label class="checkbox checkbox-info">
                                                <input type="checkbox" name="update_employee" id="update_employee" />
                                                <span></span>
                                                {{__('messages.update')}}
                                            </label>
                                            <label class="checkbox checkbox-danger">
                                                <input type="checkbox" name="delete_employee" id="delete_employee" />
                                                <span></span>
                                                {{__('messages.delete')}}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-3 col-form-label">{{__('messages.roles')}}</label>
                                    <div class="col-9 col-form-label">
                                        <div class="checkbox-inline">
                                            <label class="checkbox checkbox-success">
                                                <input type="checkbox" name="create_role" id="create_role" />
                                                <span></span>
                                                {{__('messages.add')}}
                                            </label>
                                            <label class="checkbox checkbox-primary">
                                                <input type="checkbox" name="view_role" id="view_role" />
                                                <span></span>
                                                {{__('messages.view')}}
                                            </label>
                                            <label class="checkbox checkbox-info">
                                                <input type="checkbox" name="update_role" id="update_role" />
                                                <span></span>
                                                {{__('messages.update')}}
                                            </label>
                                            <label class="checkbox checkbox-danger">
                                                <input type="checkbox" name="delete_role" id="delete_role" />
                                                <span></span>
                                                {{__('messages.delete')}}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-3 col-form-label">{{__('messages.tracking')}}</label>
                                    <div class="col-9 col-form-label">
                                        <div class="checkbox-inline">
                                            <label class="checkbox checkbox-success">
                                                <input type="checkbox" name="live_tracking" id="live_tracking" />
                                                <span></span>
                                               {{__('messages.live_tracking')}}
                                            </label>
                                            <label class="checkbox checkbox-primary">
                                                <input type="checkbox" name="playback" id="playback" />
                                                <span></span>
                                                 {{__('messages.playback')}}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

            </div>
            <div class="card-footer counter-mirror">
                <input type="hidden" name="role_id" id="role_id">
                <button type="submit" class="btn  mr-2" style="background: #ffc500">{{__('messages.update')}}</button>
                <button type="reset" class="btn btn-secondary" data-dismiss="modal">{{__('messages.cancel')}}</button>
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

<div class="modal fade " id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title counter-mirror" id="deleteModalLabel">{{__('messages.delete_role')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body counter-mirror">
                <p>{{__('messages.are_you_sure_to_delete_this_role?')}}</p>
            </div>
            <div class="modal-footer counter-mirror">
                <a id="deleteUrl" class="btn btn-primary font-weight-bold">{{__('messages.yes')}}</a>
                <button type="button" class="btn btn-light-primary font-weight-bold"
                    data-dismiss="modal">{{__('messages.no')}}</button>
            </div>
        </div>
    </div>
</div>

<!--end::Content-->
@include('includes/footer')

<script type="text/javascript">
    $(".users-nav").click()
    $(".roles-nav").addClass("menu-item-active");


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



    $(document).on('click', '.delete-role', function() {
        let user = $(this).attr('role_id');
        $("#deleteUrl").attr('href', "{{ url('/delete/role') }}" + "/" + user);
        $("#deleteModal").modal('show');
    });

    $(document).on('click', '.edit-role', function() {
        let role = $(this).attr('role_id');
        // $("input[type=checkbox]").removeAttr('checked')
        $.ajax({
            url: "{{ url('/get/role') }}" + "/" + role,
            method: "GET",
            beforeSend: function() {
                document.getElementById("editForm").reset();
                $("#editModal").modal('show');
            },
            success: function(data) {
                console.log(data);
                $("#role_id").val(data.id)
                $("#name").val(data.name)

                data.permissions.forEach(element => {
                    document.getElementById(element.name).checked = true;
                });
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
            url: "{{ url('/roles') }}"
        },

        columns: [
            {
                data: 'name',
                title: "{{__('messages.name')}}"
            },

            {
                data: "id",
                title: "{{__('messages.action')}}",
                render: function(data, type, row) {

                    let html = ''
                    html += '<div class="row">'

                    html += '@can("delete_role")<a href="javascript:void(0);" role_id="' + data +
                        '" class="delete-role btn btn-sm btn-clean btn-icon" title="Delete">	                            <span class="svg-icon svg-icon-md">	                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">	                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">	                                        <rect x="0" y="0" width="24" height="24"></rect>	                                        <path d="M6,8 L6,20.5 C6,21.3284271 6.67157288,22 7.5,22 L16.5,22 C17.3284271,22 18,21.3284271 18,20.5 L18,8 L6,8 Z" fill="#000000" fill-rule="nonzero"></path>	                                        <path d="M14,4.5 L14,4 C14,3.44771525 13.5522847,3 13,3 L11,3 C10.4477153,3 10,3.44771525 10,4 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3"></path>	                                    </g>	                                </svg>	                            </span>	                        </a>@endcan'


                    html += '@can("update_role")<a href="javascript:;" role_id=' + data +
                        ' class="edit-role btn btn-sm btn-clean btn-icon mr-2" title="Edit details">	                            <span class="svg-icon svg-icon-md">	                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">	                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">	                                        <rect x="0" y="0" width="24" height="24"></rect>	                                        <path d="M8,17.9148182 L8,5.96685884 C8,5.56391781 8.16211443,5.17792052 8.44982609,4.89581508 L10.965708,2.42895648 C11.5426798,1.86322723 12.4640974,1.85620921 13.0496196,2.41308426 L15.5337377,4.77566479 C15.8314604,5.0588212 16,5.45170806 16,5.86258077 L16,17.9148182 C16,18.7432453 15.3284271,19.4148182 14.5,19.4148182 L9.5,19.4148182 C8.67157288,19.4148182 8,18.7432453 8,17.9148182 Z" fill="#000000" fill-rule="nonzero" transform="translate(12.000000, 10.707409) rotate(-135.000000) translate(-12.000000, -10.707409) "></path>	                                        <rect fill="#000000" opacity="0.3" x="5" y="20" width="15" height="2" rx="1"></rect>	                                    </g>	                                </svg>	                            </span></a>@endcan'
                    // console.log(row.blocked);

                    html += '</div>'
                    return html;
                }
            }
        ],
        "autoWidth": true,

        "lengthMenu": [
            [10, 25, 50, -1],
            [10, 25, 50, "All"]
        ],
        dom: 'Bfrtip',
        buttons: [{
            extend: 'pdfHtml5',
            text: '{{__("messages.pdf")}}',
            title: $('h3').text(),
            orientation: 'potrait',
            pageSize: 'LEGAL',
            exportOptions: {
                stripHtml: true,

                modifier: {
                    page: 'all'
                },
                columns: 'visible:not(:last-child)'
            },
            customize: function(doc) {
                doc.content[1].table.widths = Array(doc.content[1].table.body[0]
                    .length + 1).join('*').split('');
                doc.defaultStyle.alignment = 'center';
                doc.styles.tableHeader.alignment = 'center';
            }
        }, {
            extend: 'print',
            text: '{{__("messages.print")}}',
            title: $('h3').text(),
            exportOptions: {
                modifier: {
                    page: 'all'
                },
                columns: 'visible:not(:last-child)'
            }
        }, {
            extend: 'excel',
            text: '{{__("messages.excel")}}',
            title: $('h3').text(),
            exportOptions: {
                stripHtml: true,

                modifier: {
                    page: 'all'
                },
                columns: 'visible:not(:last-child)'
            }
        }, {
            extend: 'copy',
            text: '{{__("messages.copy")}}',
            title: $('h3').text(),
            exportOptions: {
                stripHtml: true,

                modifier: {
                    page: 'all'
                },
                columns: 'visible:not(:last-child)'
            }
        }]
    });
</script>
