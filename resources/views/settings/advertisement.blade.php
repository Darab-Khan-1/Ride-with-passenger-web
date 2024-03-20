@include('includes/header')
<!--begin::Content-->
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
                    <div class="card-title">Advertisement for shared tracking links</div>
                    <div class="card-toolbar">
                            <button class="menu-link btn btn-warning mx-2 " style="width: 150px; height:35px;"
                                data-toggle="modal" form="addvertisementForm">
                                <span class="menu-text counter-mirror ">
                                    Save</span>
                            </button>
                    </div>
                </div>

                <div class="card-body flex-wrap border-0 pt-6 pb-0 "
                    style="box-shadow: inset 1px 1px 10px 1px #c9c9c9;">

                    <form action="{{ url('advertisement/save') }}" id="addvertisementForm" method="POST" class="">
                        @csrf
                        <div class="form-group col-md-12">
                            <textarea id="kt-ckeditor-1" name="addvertisement" class="form-control col-md-12" required cols="30" rows="5"
                                placeholder="Enter Name Here">{{ $add->value }} </textarea>
                        </div>
                    </form>


                </div>
            </div>
        </div>

        {{-- <div class="card-footer">
                </div> --}}
    </div>

</div>
<!--end::Container-->
</div>
<!--end::Entry-->
</div>

@include('includes/footer')


<script>
   
var KTCkeditor = function () {
    // Private functions
    var demos = function () {
        ClassicEditor
            .create( document.querySelector( '#kt-ckeditor-1' ) )
            .then( editor => {
                console.log( editor );
            } )
            .catch( error => {
                console.error( error );
            } );
    }

    return {
        // public functions
        init: function() {
            demos();
        }
    };
}();

// Initialization
jQuery(document).ready(function() {
    KTCkeditor.init();
});
</script>
