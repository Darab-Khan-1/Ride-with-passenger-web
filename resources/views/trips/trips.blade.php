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
    <!--begin::Container-->

    <div class="px-5">
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

            <div class="card-header flex-wrap border-0 pt-6 pb-0 counter-mirror">
                <div class="card-title">
                    <h3 class="card-label text-success">{{ __('messages.available_trips') }}
                        ({{ $data['available'] }})
                    </h3>
                    <h3 class="card-label text-danger">&nbsp;{{ __('messages.incomplete_trips') }}
                        ({{ $data['incomplete'] }})
                    </h3>
                </div>
                <div class="card-toolbar col-md-12">
                    @can('create_trip')
                        <button class="menu-link btn btn-warning mx-2 " style="width: 150px; height:35px;"
                            data-toggle="modal" data-target="#syncEventsModal" id="openSyncModal">
                            <span class="menu-text counter-mirror ">
                                Sync Events</span>
                        </button>
                    @endcan
                    <select id="customer" class="select2 form-control mx-1" style="width: 300px">
                        <option value="0">-- All Customers --</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->user_id }}">{{ $customer->name }}</option>
                        @endforeach
                    </select>
                    <input type="date" class="form-control col-md-4" id="from" style="width: 300px"
                        value="{{ date('Y-m-d', strtotime('-1 day')) }}">
                    <input type="date" class="form-control col-md-4" id="to" style="width: 300px"
                        value="{{ date('Y-m-d', strtotime('now')) }}">
                </div>
            </div>
            <div class="card-body p-5 counter-mirror" style="overflow-x: scroll;">

                <table class="table" id="table"></table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content counter-mirror">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">{{ __('messages.delete') }} {{ __('messages.trips') }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <p>{{ __('messages.delete_trip') }}</p>
            </div>
            <div class="modal-footer">
                <a id="deleteUrl" class="btn btn-primary font-weight-bold">{{ __('messages.yes') }}</a>
                <button type="button" class="btn btn-light-primary font-weight-bold"
                    data-dismiss="modal">{{ __('messages.no') }}</button>
            </div>
        </div>
    </div>
</div>
<!--end::Content-->
@include('includes/footer')

<script type="text/javascript">
    $(".trips-nav").click()
    $(".trips-nav").addClass("menu-item-active");


    var table
    $(document).ready(function() {
        table = $('#table').DataTable({
            paging: true,
            "ordering": false,
            // pageLength : parseInt(vv),
            responsive: false,
            processing: false,
            serverSide: false,

            ajax: {
                url: "{{ url('available/trips') }}" + "/" + $('#from').val() + "/" + $('#to').val() +
                    "/" + $('#customer').val()
            },

            columns: [{
                    data: 'unique_id',
                    title: '{{ __('messages.trip_id') }}',
                    width: '150px',
                    render: function(data, type, row) {
                        return '<span class="font-weight-bold ">' + data + '</span>'
                    }
                },
                {
                    data: 'event_name',
                    title: '{{ __('messages.event_name') }}',
                    render: function(data, type, row) {
                        return '<span class="font-weight-bold ">' + data + '</span>'
                    }
                },
                {
                    data: 'driver',
                    title: '{{ __('messages.driver') }}',
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
                    title: '{{ __('messages.pickup_details') }}',
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
                    title: '{{ __('messages.delivery_details') }}',
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
                    title: '{{ __('messages.details') }}',
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
                    title: '{{ __('messages.customer') }}',
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
                    title: '{{ __('messages.stops') }}',
                    width: "250px",
                    render: function(data, tye, row) {
                        let html = "";
                        if (data.length > 2) {
                            data.forEach((element, index) => {
                                if (index > 0 && index < data.length - 1)
                                    html += '<b>Stop ' + (index - 1) + ':<b> ' + element
                                    .location + (element.description != null ??
                                        "<small>(" + element.description + ")</small>"
                                    ) + '<br>'
                            });
                        } else {
                            html += "<b>NO&nbsp;STOP<b>"
                        }
                        return html
                    }
                },
                {
                    data: 'description',
                    title: '{{ __('messages.description') }}',
                    render: function(data, type, row) {
                        return '<span class="font-weight-bold ">' + data + '</span>'
                    }
                },
                {
                    data: 'status',
                    title: '{{ __('messages.status') }}',
                    render: function(data, type, row) {
                        let html =
                            '<span class="font-weight-bold text-danger">{{ __('messages.incomplete') }}</span>'
                        if (data != null) {
                            html = data.toUpperCase()
                        }
                        return html
                    }
                },
                {
                    data: "id",
                    title: '{{ __('messages.action') }}',
                    width: 150,
                    render: function(data, type, row) {
                        let url = "{{ url('edit/trip') }}" + "/" + data
                        let dupplicate_url = "{{ url('duplicate/trips') }}" + "/" + data
                        let html = ''
                        html += '@can('update_trip') <a href="' + url +
                            '" class="edit-load btn btn-sm btn-clean btn-icon mr-2" title="Edit details"><span class="svg-icon svg-icon-md"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><path d="M8,17.9148182 L8,5.96685884 C8,5.56391781 8.16211443,5.17792052 8.44982609,4.89581508 L10.965708,2.42895648 C11.5426798,1.86322723 12.4640974,1.85620921 13.0496196,2.41308426 L15.5337377,4.77566479 C15.8314604,5.0588212 16,5.45170806 16,5.86258077 L16,17.9148182 C16,18.7432453 15.3284271,19.4148182 14.5,19.4148182 L9.5,19.4148182 C8.67157288,19.4148182 8,18.7432453 8,17.9148182 Z" fill="#000000" fill-rule="nonzero" transform="translate(12.000000, 10.707409) rotate(-135.000000) translate(-12.000000, -10.707409) "></path><rect fill="#000000" opacity="0.3" x="5" y="20" width="15" height="2" rx="1"></rect></g></svg></span></a> @endcan'
                        if (row.status == 'available' || row.status == null)
                            html +=
                            '@can('delete_trip') <a href="javascript:void(0);" trip_id="' +
                            data +
                            '" class="delete-trip btn btn-sm btn-clean btn-icon" title="Delete">	                            <span class="svg-icon svg-icon-md">	                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">	                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">	                                        <rect x="0" y="0" width="24" height="24"></rect>	                                        <path d="M6,8 L6,20.5 C6,21.3284271 6.67157288,22 7.5,22 L16.5,22 C17.3284271,22 18,21.3284271 18,20.5 L18,8 L6,8 Z" fill="#000000" fill-rule="nonzero"></path>	                                        <path d="M14,4.5 L14,4 C14,3.44771525 13.5522847,3 13,3 L11,3 C10.4477153,3 10,3.44771525 10,4 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3"></path>	                                    </g>	                                </svg>	                            </span>	                        </a> @endcan'

                        html +=
                            `<a href="${dupplicate_url}" class="btn btn-sm btn-icon btn-clean" title="duplicate"> <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo1\dist/../src/media/svg/icons\General\Duplicate.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24"/>
                                    <path d="M15.9956071,6 L9,6 C7.34314575,6 6,7.34314575 6,9 L6,15.9956071 C4.70185442,15.9316381 4,15.1706419 4,13.8181818 L4,6.18181818 C4,4.76751186 4.76751186,4 6.18181818,4 L13.8181818,4 C15.1706419,4 15.9316381,4.70185442 15.9956071,6 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                    <path d="M10.1818182,8 L17.8181818,8 C19.2324881,8 20,8.76751186 20,10.1818182 L20,17.8181818 C20,19.2324881 19.2324881,20 17.8181818,20 L10.1818182,20 C8.76751186,20 8,19.2324881 8,17.8181818 L8,10.1818182 C8,8.76751186 8.76751186,8 10.1818182,8 Z" fill="#000000"/>
                                </g>
                            </svg><!--end::Svg Icon--></span>`

                        if (row.tracking_links.length > 0) {

                            let id = 'copy_url' + row.id
                            html +=
                                `<span onclick="copyToClipboard('${id}-copy')" class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo7\dist/../src/media/svg/icons\General\Clipboard.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"/>
                                        <path d="M8,3 L8,3.5 C8,4.32842712 8.67157288,5 9.5,5 L14.5,5 C15.3284271,5 16,4.32842712 16,3.5 L16,3 L18,3 C19.1045695,3 20,3.8954305 20,5 L20,21 C20,22.1045695 19.1045695,23 18,23 L6,23 C4.8954305,23 4,22.1045695 4,21 L4,5 C4,3.8954305 4.8954305,3 6,3 L8,3 Z" fill="#000000" opacity="0.3"/>
                                        <path d="M11,2 C11,1.44771525 11.4477153,1 12,1 C12.5522847,1 13,1.44771525 13,2 L14.5,2 C14.7761424,2 15,2.22385763 15,2.5 L15,3.5 C15,3.77614237 14.7761424,4 14.5,4 L9.5,4 C9.22385763,4 9,3.77614237 9,3.5 L9,2.5 C9,2.22385763 9.22385763,2 9.5,2 L11,2 Z" fill="#000000"/>
                                        <rect fill="#000000" opacity="0.3" x="7" y="10" width="5" height="2" rx="1"/>
                                        <rect fill="#000000" opacity="0.3" x="7" y="14" width="9" height="2" rx="1"/>
                                    </g>
                                </svg><!--end::Svg Icon--></span><span style="display:none;position:absolute" class="btn btn-sm btn-info" id="${id}-copy">${row.tracking_links[0].url}</span>`;
                        }

                        return html;
                    }
                }
            ],

            rowCallback: function(row, data) {
                $(row).addClass('kt_demo_panel_toggle');
            },
            "autoWidth": false,
            "ordering": false,

            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            dom: 'Bfrtip',
            buttons: [{
                    extend: 'pdfHtml5',
                    text: '{{ __('messages.pdf') }}',
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
                    text: '{{ __('messages.print') }}',
                    title: $('h3').text(),
                    exportOptions: {
                        modifier: {
                            page: 'all'
                        },
                        columns: ':visible:not(:last-child)'
                    }
                }, {
                    extend: 'excel',
                    text: '{{ __('messages.excel') }}',
                    title: $('h3').text(),
                    exportOptions: {
                        modifier: {
                            page: 'all'
                        },
                        columns: ':visible:not(:last-child)'
                    }
                }, {
                    extend: 'copy',
                    text: '{{ __('messages.copy') }}',
                    title: $('h3').text(),
                    exportOptions: {
                        modifier: {
                            page: 'all'
                        },
                        columns: ':visible:not(:last-child)'
                    }
                },
                {
                    extend: 'csv',
                    text: '{{ __('messages.csv') }}',
                    title: $('h3').text(),
                    exportOptions: {
                        modifier: {
                            page: 'all'
                        },
                        columns: ':visible:not(:last-child)'
                    }
                },

            ]
        });


        $('#customer, #from, #to').on('change', function() {
            // alert()
            table.ajax.url("{{ url('available/trips') }}" + "/" + $('#from').val() + "/" + $('#to')
                .val() + "/" + $('#customer').val()).load()
        });



    function copyToClipboard(elementId) {
        var element = document.getElementById(elementId);
        navigator.clipboard.writeText(element.innerText)
            .then(() => {
                toastr.success("Text copied to clipboard");
            })
            .catch(err => {
                console.error('Failed to copy text: ', err);
            });
    }



        $(document).on('click', '.delete-trip', function() {
            let trip = $(this).attr('trip_id');
            $("#deleteUrl").attr('href', "{{ url('/delete/trip') }}" + "/" + trip);
            $("#deleteModal").modal('show');
        });

        function getReport() {
            let newUrl = "{{ url('all/trips') }}" + "/" + $("#from").val() + "/" + $("#to").val()
            table.ajax.url(newUrl).load();
        }

        $(document).on('click', '#kt_demo_panel_close', function() {
            // alert()
            $("#kt_demo_panel").removeClass('offcanvas-on')
            // var rowData = table.row(this).data();
            // renderPanel(rowData);
        });

        // Toggle panel upon row click
        $(document).on('click', '.kt_demo_panel_toggle', function() {
            $("#kt_demo_panel").addClass('offcanvas-on')

            $('#table tbody tr').removeClass('active');
            $(this).addClass('active');

            var rowData = table.row(this).data();
            renderPanel(rowData);
        });

        function renderPanel(rowData) {
            console.log(rowData);
            $('#panel').empty();

            // Create HTML for table
            var tableHTML = '<table class="table">';
            tableHTML += '<tr><th>Field</th><th>Value</th></tr>';
            for (var key in rowData) {
                if (rowData.hasOwnProperty(key)) {
                    tableHTML += '<tr><td>' + key + '</td><td>' + (rowData[key] ? rowData[key] : 'N/A') +
                        '</td></tr>';
                }
            }
            tableHTML += '</table>';

            // Append table to panel
            $('#panel').html(tableHTML);
            // Update other table cells similarly
        }

        var rowData
        // Function to handle the click event on a row
        $(document).on('click', '#table tbody tr', function() {
            // Remove 'active' class from all rows
            $('#table tbody tr').removeClass('active');

            // Add 'active' class to the clicked row
            $(this).addClass('active');

            // Get data for the clicked row
            var rowData = table.row(this).data();

            // Update panel with data from the clicked row
            renderPanel(rowData);

            // Update DataTable's page number to match the clicked row
            var clickedRowIndex = table.row(this).index();
            var currentPage = Math.floor(clickedRowIndex / table.page.info().length);
            table.page(currentPage).draw(false);
        });
        // Function to handle the click event on the previous button
        $(document).on('click', '#previousBtn', function() {
            // Get data for the previous row
            var prevRowData = getPreviousRowData();

            // Update panel with data from the previous row
            if (prevRowData) {
                // Render the panel with the previous row data
                renderPanel(prevRowData);

                // Highlight the corresponding row in the table
                var prevRowIndex = table.row('.active').index() - 1;
                $('#table tbody tr').removeClass('active');
                table.row(prevRowIndex).nodes().to$().addClass('active');
            }
        });

        // Function to handle the click event on the next button
        $(document).on('click', '#nextBtn', function() {
            // Get data for the next row
            var nextRowData = getNextRowData();

            // Update panel with data from the next row
            if (nextRowData) {
                // Render the panel with the next row data
                renderPanel(nextRowData);

                // Highlight the corresponding row in the table
                var nextRowIndex = table.row('.active').index() + 1;
                $('#table tbody tr').removeClass('active');
                table.row(nextRowIndex).nodes().to$().addClass('active');
            }
        });


        // Function to update table rows with data
        function updateTableRows(rowData) {
            $('#pickup_location').text(rowData.pickup_location);
            $('#pickup_date').text(rowData.pickup_date);
            // Update other table cells similarly
        }

        // Function to get data for the previous row
        function getPreviousRowData() {
            var prevRowIndex = table.row('.active').index() - 1;
            if (prevRowIndex >= 0) {
                return table.row(prevRowIndex).data();
            }
            return null;
        }

        // Function to get data for the next row
        function getNextRowData() {
            var nextRowIndex = table.row('.active').index() + 1;
            if (nextRowIndex < table.rows().count()) {
                return table.row(nextRowIndex).data();
            }
            return null;
        }
    });
</script>
