@include('includes/header')
<style>
    textarea#email-body {
        height: 340px;
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
            {{-- <div class="card card-custom">
                <div class="card-body p-5">
                    <div class="row">
                        <div class="col-xl-3">
                            <!--begin::Tiles Widget 12-->
                            <div class="card card-custom gutter-b" style="height: 150px">
                                <div class="card-body">
                                    <span
                                        class="svg-icon svg-icon-3x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo1\dist/../src/media/svg/icons\Layout\Layout-grid.svg--><svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                                            viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="#ffffff" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24" />
                                                <rect fill="#000000" opacity="0.3" x="4" y="4" width="4"
                                                    height="4" rx="1" />
                                                <path
                                                    d="M5,10 L7,10 C7.55228475,10 8,10.4477153 8,11 L8,13 C8,13.5522847 7.55228475,14 7,14 L5,14 C4.44771525,14 4,13.5522847 4,13 L4,11 C4,10.4477153 4.44771525,10 5,10 Z M11,4 L13,4 C13.5522847,4 14,4.44771525 14,5 L14,7 C14,7.55228475 13.5522847,8 13,8 L11,8 C10.4477153,8 10,7.55228475 10,7 L10,5 C10,4.44771525 10.4477153,4 11,4 Z M11,10 L13,10 C13.5522847,10 14,10.4477153 14,11 L14,13 C14,13.5522847 13.5522847,14 13,14 L11,14 C10.4477153,14 10,13.5522847 10,13 L10,11 C10,10.4477153 10.4477153,10 11,10 Z M17,4 L19,4 C19.5522847,4 20,4.44771525 20,5 L20,7 C20,7.55228475 19.5522847,8 19,8 L17,8 C16.4477153,8 16,7.55228475 16,7 L16,5 C16,4.44771525 16.4477153,4 17,4 Z M17,10 L19,10 C19.5522847,10 20,10.4477153 20,11 L20,13 C20,13.5522847 19.5522847,14 19,14 L17,14 C16.4477153,14 16,13.5522847 16,13 L16,11 C16,10.4477153 16.4477153,10 17,10 Z M5,16 L7,16 C7.55228475,16 8,16.4477153 8,17 L8,19 C8,19.5522847 7.55228475,20 7,20 L5,20 C4.44771525,20 4,19.5522847 4,19 L4,17 C4,16.4477153 4.44771525,16 5,16 Z M11,16 L13,16 C13.5522847,16 14,16.4477153 14,17 L14,19 C14,19.5522847 13.5522847,20 13,20 L11,20 C10.4477153,20 10,19.5522847 10,19 L10,17 C10,16.4477153 10.4477153,16 11,16 Z M17,16 L19,16 C19.5522847,16 20,16.4477153 20,17 L20,19 C20,19.5522847 19.5522847,20 19,20 L17,20 C16.4477153,20 16,19.5522847 16,19 L16,17 C16,16.4477153 16.4477153,16 17,16 Z"
                                                    fill="#000000" />
                                            </g>
                                        </svg><!--end::Svg Icon--></span>
                                    <div class="text-dark font-weight-bolder font-size-h2 mt-3">{{ $total }}
                                    </div>
                                    <a href="#"
                                        class="text-muted text-hover-primary font-weight-bold font-size-lg mt-1">Total
                                        Active</a>
                                </div>
                            </div>
                            <!--end::Tiles Widget 12-->
                        </div>
                    </div>
                </div>
            </div> --}}
            {{-- <div class="card card-custom mt-5" style="box-shadow: inset 1px 1px 10px 1px #c9c9c9;">
                <div class="card-body p-5">
                    <div class="row px-5">
                       
                        <input type="hidden" value="{{ isset($service->id) ? $service->driver->device_id : '' }}"
                            id="device_id">

                        <label for="" style="margin-top: 12px;">From</label>
                        <input type="date" class="col-md-5 m-1 form-control" id="from"
                            value="{{ date('Y-m-d', strtotime('-2 days')) }}">
                        <label for="" style="margin-top: 12px;">To</label>
                        <input type="date" class="col-md-5 m-1 form-control" id="to"
                            value="{{ date('Y-m-d', strtotime('now')) }}">

                        <button class="m-1 btn btn-primary text-light" id="fetchAndPlayButton" style="width: 130px;"
                            onclick="getReport()">&nbsp;Search</button>
                        <span style="padding-top: 15px;"><i style="display: none" id="spinner"
                                class="fas fa-spinner fa-spin"></i></span>

                    </div>
                </div>
            </div> --}}
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
                        <h3 class="card-label">Active Trips ({{ $total }})
                            {{-- <span class="d-block text-muted pt-2 font-size-sm">Companies made easy</span> --}}
                        </h3>
                    </div>
                    <div class="card-toolbar">
                        <!--begin::Button-->
                        {{-- <a href="{{ url('new/trip') }}" class="btn  font-weight-bolder" style="background: #ffc500">
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
                        </a> --}}

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

<!--end::Content-->
@include('includes/footer')

<script type="text/javascript">
    $(".trips-nav").click()
    $(".active-trips-nav").addClass("menu-item-active");


    var table
    $(document).ready(function() {
        table = $('#table').DataTable({
            paging: true,
            // pageLength : parseInt(vv),
            responsive: false,
            processing: false,
            serverSide: false,

            ajax: {
                url: "{{ url('/active/trips') }}"
            },

            columns: [{
                    data: 'unique_id',
                    title: 'Trip&nbsp;ID',
                    width: '150px',
                    render: function(data, type, row) {
                        return '<span class="font-weight-bold ">' + data + '</span>'
                    }
                },
                {
                    data: 'event_name',
                    title: 'Event&nbsp;Name',
                    render: function(data, type, row) {
                        return '<span class="font-weight-bold ">' + data + '</span>'
                    }
                },
                {
                    data: 'driver',
                    title: 'Driver',
                    render: function(data, type, row) {
                        let html =
                            '<span class="font-weight-bold text-danger">NOT&nbsp;ASSIGNED</span>'
                        if (data != null) {
                            html = '<span class="font-weight-bold">Name:</span>' + data.name
                            html += '<br><span class="font-weight-bold">Phone:</span>' + data
                                .phone
                        }
                        return html
                    }
                },
                {
                    data: 'pickup_date',
                    title: 'Pickup&nbsp;Details',
                    width: "250px",
                    render: function(data, type, row) {
                        let no_data =
                            '<span class="font-weight-bold text-danger">NOT&nbsp;SPECIFIED</span>'
                        let html = ''
                        if (row.pickup_location != null) {
                            html += "<b>Location:</b> " + row.pickup_location
                        } else {
                            html += "<b>Location:</b> " + no_data
                        }
                        if (data != null) {
                            html += "<br><b>Date/Time:</b>" + data
                        } else {
                            html += "<br><b>Date/Time:</b>" + no_data
                        }
                        return html
                    }
                },
                {
                    data: 'delivery_date',
                    title: 'Delivery&nbsp; Details',
                    width: "250px",
                    render: function(data, tye, row) {
                        let no_data =
                            '<span class="font-weight-bold text-danger">NOT&nbsp;SPECIFIED</span>'
                        let html = ''
                        if (row.delivery_location != null) {
                            html += "<b>Location:</b> " + row.delivery_location
                        } else {
                            html += "<b>Location:</b> " + no_data
                        }
                        if (data != null) {
                            html += "<br><b>Date/Time:</b>" + data
                        } else {
                            html += "<br><b>Date/Time:</b>" + no_data
                        }
                        return html
                    }
                },
                {
                    data: 'estimated_distance',
                    title: 'Details',
                    width: "250px",
                    render: function(data, tye, row) {
                        if (data != null) {
                            return "<b>Estimated&nbsp;Distance:</b>" + data +
                                " km<br><b>Estimated&nbsp;Time:</b>" + row.estimated_time
                        }
                        return '<span class="font-weight-bold text-danger">NOT&nbsp;SPECIFIED</span>'
                    }
                },
                {
                    data: 'customer_name',
                    title: 'Customer',
                    width: "250px",
                    render: function(data, tye, row) {
                        let html =
                            '<span class="font-weight-bold text-danger">NOT&nbsp;SPECIFIED</span>'
                        if (data != null) {
                            html = "<b>Name:</b>" + data + "<br><b>Phone:</b>" + row
                                .customer_phone
                        }
                        return html
                    }
                },
                {
                    data: 'stops',
                    title: 'Stops',
                    width: "250px",
                    render: function(data, tye, row) {
                        let html = "";
                        if (data.length > 0) {
                            data.forEach((element, index) => {
                                html += '<b>Stop ' + (index + 1) + ':<b> ' + element
                                    .location + '<br>'
                            });
                        } else {
                            html += "<b>NO&nbsp;STOP<b>"
                        }
                        return html
                    }
                },
                {
                    data: 'description',
                    title: 'Description',
                    render: function(data, type, row) {
                        return '<span class="font-weight-bold ">' + data + '</span>'
                    }
                },
                {
                    data: 'status',
                    title: 'Status',
                    render: function(data, type, row) {
                        let html = '<span class="font-weight-bold text-warning">ACTIVE</span>'
                        // if(data != null){
                        //     html =  data.toUpperCase()
                        // }
                        return html
                    }
                },
                {
                    data: "id",
                    title: "Action",
                    width: 150,
                    render: function(data, type, row) {
                        let url = "{{ url('edit/trip') }}" + "/" + data
                        let html = ''
                        // html += '<a href="' + url +
                        //     '" class="edit-load btn btn-sm btn-clean btn-icon mr-2" title="Edit details"><span class="svg-icon svg-icon-md"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><path d="M8,17.9148182 L8,5.96685884 C8,5.56391781 8.16211443,5.17792052 8.44982609,4.89581508 L10.965708,2.42895648 C11.5426798,1.86322723 12.4640974,1.85620921 13.0496196,2.41308426 L15.5337377,4.77566479 C15.8314604,5.0588212 16,5.45170806 16,5.86258077 L16,17.9148182 C16,18.7432453 15.3284271,19.4148182 14.5,19.4148182 L9.5,19.4148182 C8.67157288,19.4148182 8,18.7432453 8,17.9148182 Z" fill="#000000" fill-rule="nonzero" transform="translate(12.000000, 10.707409) rotate(-135.000000) translate(-12.000000, -10.707409) "></path><rect fill="#000000" opacity="0.3" x="5" y="20" width="15" height="2" rx="1"></rect></g></svg></span></a>'
                        if (row.driver != null) {
                            html += `<a href="` + "{{ url('live/location') }}" + "/" + row.driver.device_id + `"><span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo1\dist/../src/media/svg/icons\Map\Marker1.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"/>
                                        <path d="M5,10.5 C5,6 8,3 12.5,3 C17,3 20,6.75 20,10.5 C20,12.8325623 17.8236613,16.03566 13.470984,20.1092932 C12.9154018,20.6292577 12.0585054,20.6508331 11.4774555,20.1594925 C7.15915182,16.5078313 5,13.2880005 5,10.5 Z M12.5,12 C13.8807119,12 15,10.8807119 15,9.5 C15,8.11928813 13.8807119,7 12.5,7 C11.1192881,7 10,8.11928813 10,9.5 C10,10.8807119 11.1192881,12 12.5,12 Z" fill="#000000" fill-rule="nonzero"/>
                                    </g>
                                </svg><!--end::Svg Icon--></span></a>`
                        }
                        return html;
                    }
                }
            ],
            "autoWidth": false,
            "ordering": false,

            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            dom: 'Bfrtip',
            buttons: [{
                    extend: 'pdfHtml5',
                    text: 'PDF',
                    title: $('h3').text(),
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                    exportOptions: {
                        modifier: {
                            page: 'all'
                        },
                        columns: ':visible:not(:last-child)'
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
                        columns: ':visible:not(:last-child)'
                    }
                }, {
                    extend: 'excel',
                    text: 'EXCEL',
                    title: $('h3').text(),
                    exportOptions: {
                        modifier: {
                            page: 'all'
                        },
                        columns: ':visible:not(:last-child)'
                    }
                }, {
                    extend: 'copy',
                    text: 'COPY',
                    title: $('h3').text(),
                    exportOptions: {
                        modifier: {
                            page: 'all'
                        },
                        columns: ':visible:not(:last-child)'
                    }
                },
                'csv'
            ]
        });
    });
</script>
