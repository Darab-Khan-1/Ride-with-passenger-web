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
                    <div class="card card-custom card-stretch">

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
                        @error('old_password')
                            <div class="alert alert-danger m-2">
                                {{ $message }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @enderror
                        <!--begin::Header-->
                        <div class="card-header py-3">
                            <div class="card-title align-items-start flex-column">
                                <h3 class="card-label font-weight-bolder text-dark">Change your password</h3>
                                <span class="text-muted font-weight-bold font-size-sm mt-1"></span>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-xl-3 col-lg-3 col-form-label">Email</label>
                                <div class="col-lg-9 col-xl-6">
                                    <input class="form-control form-control-lg form-control-solid" name="name"
                                        type="text" value="{{ Auth::user()->email }}" disabled>
                                </div>
                            </div>
                            <form method="post" action="{{ url('/profile/password/update') }}"
                                id="passwordChange">
                                @csrf
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">Old Password</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <div class="input-group input-group-lg  ">
                                            <input type="password" name="old_password"
                                                class="form-control  old_password"
                                                placeholder="Old password" value="{{ auth::user()->city }}">
                                            <div class="input-group-append">
                                                <span class="input-group-text">
                                                    <i class="toggle-password fas fa-eye" id="old_password"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">New Password</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <div class="input-group input-group-lg ">
                                            <input type="password" name="new_password" minlength="8"
                                                class="form-control  new_password"
                                                placeholder="New password" value="{{ auth::user()->state }}">
                                            <div class="input-group-append">
                                                <span class="input-group-text">
                                                    <i class="toggle-password fas fa-eye" id="new_password"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">Confirm Password</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <div class="input-group input-group-lg ">
                                            <input type="password" name="confirm_password"
                                                class="form-control  confirm_password"
                                                placeholder="Confirm password" value="{{ auth::user()->state }}">
                                            <div class="input-group-append">
                                                <span class="input-group-text">
                                                    <i class="toggle-password fas fa-eye" id="confirm_password"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="error-message text-center invalid-feedback"
                                        style="display: none;">Passwords
                                        do not match</span>
                                </div>
                                <div class="card-toolbar">
                                    <button form="passwordChange"type="submit" id="btnPasswordChange"
                                        style="background: #ffc500" class="btn  mr-2 btnPasswordChange">Update Password</button>
                                </div>
                            </form>
                            <!--end::Form-->
                        </div>
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
    $(document).on('click', '.changePassword', function() {

        $("#modalPassword").modal('show');
    })
    // Toggle password visibility
    $('.btnPasswordChange').prop('disabled', true);
    var pass_fields = document.querySelectorAll('.toggle-password');
    pass_fields.forEach(function(button) {
        button.addEventListener('click', function(e) {

            var passwordFieldType = $('.' + e.target.id).attr('type');

            if (passwordFieldType === 'password') {
                $('.' + e.target.id).prop('type', 'text');
                this.classList.remove('fa-eye');
                this.classList.add('fa-eye-slash');
            } else {
                $('.' + e.target.id).prop('type', 'password');
                this.classList.remove('fa-eye-slash');
                this.classList.add('fa-eye');
            }
        });

    });
    var newPasswordInput = document.querySelector('.new_password');
    var confirmPasswordInput = document.querySelector('.confirm_password');
    var errorMessage = document.querySelector('.error-message');

    confirmPasswordInput.addEventListener('input', function() {
        var newPassword = newPasswordInput.value;
        var confirmPassword = confirmPasswordInput.value;

        if (newPassword === confirmPassword) {
            errorMessage.style.display = 'none';
            $('.btnPasswordChange').prop('disabled', false);
        } else {

            $('.btnPasswordChange').prop('disabled', true);
            errorMessage.style.display = 'inline'; // Show the error message if passwords don't match
        }
    });

    // Prevent form submission when passwords don't match
    document.querySelector('form').addEventListener('submit', function(event) {
        var newPassword = newPasswordInput.value;
        var confirmPassword = confirmPasswordInput.value;

        if (newPassword !== confirmPassword) {
            // Prevent form submission
            errorMessage.style.display = 'inline'; // Show the error message
        }
    });
</script>
