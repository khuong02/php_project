@extends('layouts/contentNavbarLayout')

@section('title', 'Account settings - Account')

@section('page-script')
    <script src="{{ asset('assets/js/pages-account-settings-account.js') }}"></script>
@endsection

@section('content')
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Account Settings /</span> Account
    </h4>

    <div class="row">
        <div class="col-md-12">
            <ul class="nav nav-pills flex-column flex-md-row mb-3">
                <li class="nav-item"><a class="nav-link active" href="javascript:void(0);"><i class="bx bx-user me-1"></i>
                        Account</a></li>
            </ul>
            <div class="card mb-4">
                <h5 class="card-header">Profile Details</h5>
                <!-- Account -->
                <form id="formAccountSettings" method="POST" action="{{ route('update-profile-admin') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="d-flex align-items-start align-items-sm-center gap-4">
                            <img src="{{ $admiProfile->avatar }}" alt="user-avatar" class="d-block rounded" height="100"
                                width="100" id="uploadedAvatar" />
                            <div class="button-wrapper">
                                <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                                    <span class="d-none d-sm-block">Upload new photo</span>
                                    <i class="bx bx-upload d-block d-sm-none"></i>
                                    <input type="file" id="upload" class="account-file-input" hidden
                                        accept="image/png, image/jpeg" name="avatar" />
                                </label>
                                <button type="button" class="btn btn-outline-secondary account-image-reset mb-4">
                                    <i class="bx bx-reset d-block d-sm-none"></i>
                                    <span class="d-none d-sm-block">Reset</span>
                                </button>

                                <p class="text-muted mb-0">Allowed JPG, GIF or PNG. Max size of 800K</p>
                            </div>
                        </div>
                    </div>
                    <hr class="my-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label for="firstName" class="form-label">User Name</label>
                                <input class="form-control" type="text" id="firstName" name="username"
                                    value="{{ $admiProfile->username }}" autofocus />
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="email" class="form-label">E-mail</label>
                                <input class="form-control" type="text" id="email" name="email"
                                    value="{{ $admiProfile->email }}" placeholder="john.doe@example.com" readonly />
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="address" class="form-label">Created At</label>
                                <input type="datetime" class="form-control" id="address" name="created_at"
                                    value="{{ $admiProfile->created_at }}" readonly />
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="address" class="form-label">Update Lần Cuối</label>
                                <input type="datetime" class="form-control" id="address" name="updated_at"
                                    value="{{ $admiProfile->updated_at }}" readonly />
                            </div>

                            <div class="mb-3 col-md-6">
                                <label for="current_password" class="form-label">Current password</label>
                                <input class="form-control" type="password" id="current_password" name="current_password"
                                    value="" autofocus />
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="password" class="form-label">New password</label>
                                <input class="form-control" type="password" id="new_pasword" name="password" value=""
                                    autofocus />
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="cf_password" class="form-label">Confirm new password</label>
                                <input class="form-control" type="password" id="password_confirmation" name="cf_password"
                                    value="" autofocus />
                            </div>
                            <div class="mt-2">
                                <button type="submit" class="btn btn-primary me-2">Save changes</button>
                                <button type="reset" class="btn btn-outline-secondary">Cancel</button>
                            </div>
                        </div>
                </form>
                <!-- /Account -->
            </div>
        </div>
    </div>


    <script src="{{ asset('assets/js/jquery.js') }}"></script>
    <script>
        $(function() {
            $("#formAccountSettings").on("submit", function(e) { //id of form
                e.preventDefault();
                var action = $(this).attr("action"); //get submit action from form
                var method = $(this).attr("method"); // get submit method
                var form_data = new FormData($(this)[0]); // convert form into formdata
                var form = $(this);
                $.ajax({
                    url: action,
                    dataType: 'json', // what to expect back from the server
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: form_data,
                    type: method,
                    success: function(response) {
                        if (!response.erro) {
                            location.reload(true);
                        }
                    },
                    error: function(response) { // handle the error
                        alert(response.responseJSON.message);
                    },
                })
            });
        });
    </script>
@endsection
