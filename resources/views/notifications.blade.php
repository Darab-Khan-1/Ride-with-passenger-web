@include('includes/header')
<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Entry-->
    <div>
        <!--begin::Container-->
        <div class="px-5">
            <!--begin::Profile Overview-->
            <div class="d-flex flex-row">
                <!--begin::Content-->
                <div class="flex-row-fluid ml-lg-8">
                    <!--begin::Card-->
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
                
               
                <div class="card-body p-5" style="overflow-x: scroll;">

                    <table class="table" id="table"></table>
                </div>
                {{-- <div class="card-footer">
                </div> --}}
            </div>
                </div>
                <!--end::Content-->
            </div>
            <!--end::Profile Overview-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Entry-->
</div>
<!--Password change model-->
{{-- <div class="modal fade " id="modalEmail" tabindex="-1" aria-labelledby="modalEmail" aria-hidden="true">
    <div class=" modal-dialog modal-xl">
        <div class="modal-content ">

            <div class="modal-header bg-blue-darker align-middle">
                <h5 class="modal-title text-dark" id=""> <b>
                        Email view.</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
            </div>
            <div class="modal-body">
                <div id="email_content">

                </div>

            </div>
        </div>
    </div>
</div> --}}
<!--end::Content-->
@include('includes/footer')

<script type="text/javascript">
    
    var table = $('#table').DataTable({
        paging: true,
        // pageLength : parseInt(vv),
        responsive: false,
        processing: false,
        serverSide: false,

        ajax: {
            url: "{{ url('/all/notifications') }}"
        },

        columns: [{
                data: 'id',
                title: '#',
            },
            {
                data: 'title',
                title: 'Title'
            },
            {
                data: 'notification',
                title: 'Notifications'
            },
           
        ],
        "autoWidth": false,

        "lengthMenu": [
            [10, 25, 50, -1],
            [10, 25, 50, "All"]
        ],
        
        
    });
   
   

   
</script>
