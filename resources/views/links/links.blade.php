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
                        <h3 class="card-label">Total Tracking Links ({{ $total }})
                        </h3>
                    </div>
                    <div class="card-toolbar">
                        <!--begin::Button-->
                        @can('create_employee')
                            <a href="{{ url('new/link') }}" class="add_new btn  font-weight-bolder"
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



<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content counter-mirror">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete Tracking Link</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure to delete this link?
                </p>
            </div>
            <div class="modal-footer">
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
    $(".trips-nav").click()
    $(".sharing-links-nav").addClass("menu-item-active");


    $(document).on('click', '.delete-link', function() {
        let link = $(this).attr('link_id');
        $("#deleteUrl").attr('href', "{{ url('/delete/link') }}" + "/" + link);
        $("#deleteModal").modal('show');
    });
    $(document).on('click', '.edit-link', function() {
        let user = $(this).attr('user_id');
    });
    var table = $('#table').DataTable({
        paging: true,
        // pageLength : parseInt(vv),
        responsive: false,
        processing: false,
        serverSide: false,

        ajax: {
            url: "{{ url('/links') }}"
        },

        columns: [{
                data: 'name',
                title: 'Name',
            },
            {
                data: 'url',
                title: 'URL',
                render: function(data, type, row) {
                    html = ''
                    let id = 'copy_url' + row.id
                    html += `<span onclick="copyToClipboard('${id}')" class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo7\dist/../src/media/svg/icons\General\Clipboard.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <rect x="0" y="0" width="24" height="24"/>
                            <path d="M8,3 L8,3.5 C8,4.32842712 8.67157288,5 9.5,5 L14.5,5 C15.3284271,5 16,4.32842712 16,3.5 L16,3 L18,3 C19.1045695,3 20,3.8954305 20,5 L20,21 C20,22.1045695 19.1045695,23 18,23 L6,23 C4.8954305,23 4,22.1045695 4,21 L4,5 C4,3.8954305 4.8954305,3 6,3 L8,3 Z" fill="#000000" opacity="0.3"/>
                            <path d="M11,2 C11,1.44771525 11.4477153,1 12,1 C12.5522847,1 13,1.44771525 13,2 L14.5,2 C14.7761424,2 15,2.22385763 15,2.5 L15,3.5 C15,3.77614237 14.7761424,4 14.5,4 L9.5,4 C9.22385763,4 9,3.77614237 9,3.5 L9,2.5 C9,2.22385763 9.22385763,2 9.5,2 L11,2 Z" fill="#000000"/>
                            <rect fill="#000000" opacity="0.3" x="7" y="10" width="5" height="2" rx="1"/>
                            <rect fill="#000000" opacity="0.3" x="7" y="14" width="9" height="2" rx="1"/>
                        </g>
                    </svg><!--end::Svg Icon--><button class="btn btn-sm btn-info" id="${id}">${data}</button></span>`;
                    return html 
                }
            } ,{
                data:'trips',
                title : "Trips",
                render:function(data,type,row){
                    html = '<span class="text-danger font-weight-bold">NO TRIP<>'
                    if(data.length > 0){
                        html = ''
                        data.forEach(element => {
                            html += `<b>${element.unique_id} - ${element.event_name}</b><br>`
                        });
                    }
                    return html
                }
            } ,{
                data:'active',
                title : "Status",
                render:function(data,type,row){
                    html = ''
                    let url = "{{ url('change/link/status') }}" + "/" + row.id
                    if(data){
                        html += `<a title="Click to disable" href="${url}" class="btn btn-success">ACTIVE</a>`
                    }else{
                        html += `<a title="Click to enable" href="${url}" class="btn btn-danger">DISABLED</a>`
                    }
                    return html
                }
            },{
                data: "user_id",
                title: '{{ __('messages.action') }}',
                width: 150,
                render: function(data, type, row) {
                    let url = "{{ url('delete/link') }}" + "/" + data
                    let edit_url = "{{ url('edit/link') }}" + '/' + row.id

                    let html = ''
                    html += '<div class="row">'

                    html +=
                        '<a href="javascript:void(0);" link_id="' +
                        row.id +
                        '" class="delete-link btn btn-sm btn-clean btn-icon" title="Delete">	                            <span class="svg-icon svg-icon-md">	                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">	                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">	                                        <rect x="0" y="0" width="24" height="24"></rect>	                                        <path d="M6,8 L6,20.5 C6,21.3284271 6.67157288,22 7.5,22 L16.5,22 C17.3284271,22 18,21.3284271 18,20.5 L18,8 L6,8 Z" fill="#000000" fill-rule="nonzero"></path>	                                        <path d="M14,4.5 L14,4 C14,3.44771525 13.5522847,3 13,3 L11,3 C10.4477153,3 10,3.44771525 10,4 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3"></path>	                                    </g>	                                </svg>	                            </span>	                        </a>'

                    html += '<a href="' + edit_url +
                        '" class="edit-link btn btn-sm btn-clean btn-icon mr-2" title="Edit details">	                            <span class="svg-icon svg-icon-md">	                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">	                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">	                                        <rect x="0" y="0" width="24" height="24"></rect>	                                        <path d="M8,17.9148182 L8,5.96685884 C8,5.56391781 8.16211443,5.17792052 8.44982609,4.89581508 L10.965708,2.42895648 C11.5426798,1.86322723 12.4640974,1.85620921 13.0496196,2.41308426 L15.5337377,4.77566479 C15.8314604,5.0588212 16,5.45170806 16,5.86258077 L16,17.9148182 C16,18.7432453 15.3284271,19.4148182 14.5,19.4148182 L9.5,19.4148182 C8.67157288,19.4148182 8,18.7432453 8,17.9148182 Z" fill="#000000" fill-rule="nonzero" transform="translate(12.000000, 10.707409) rotate(-135.000000) translate(-12.000000, -10.707409) "></path>	                                        <rect fill="#000000" opacity="0.3" x="5" y="20" width="15" height="2" rx="1"></rect>	                                    </g>	                                </svg>	                            </span></a>'
                    // console.log(row.blocked);

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
</script>
