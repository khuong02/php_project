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
                <form id="formAccountSettings" method="POST" action="{{ route('edit-account-user') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" value="{{ $accountEdit->id }}" name="idUpdate">
                    <div class="card-body">
                        <div class="d-flex align-items-start align-items-sm-center gap-4">
                            <img src="{{ $accountEdit->avatar }}" alt="user-avatar" class="d-block rounded" height="100"
                                width="100" id="uploadedAvatar" />
                        </div>
                    </div>
                    <hr class="my-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label for="firstName" class="form-label">User Name</label>
                                <input class="form-control" type="text" id="firstName" name="username"
                                    value="{{ $accountEdit->username }}" autofocus />
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="email" class="form-label">E-mail</label>
                                <input class="form-control" type="text" id="email" name="email"
                                    value="{{ $accountEdit->email }}" placeholder="john.doe@example.com" />
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="email" class="form-label">Cost</label>
                                <input class="form-control" type="text" id="email" name="cost"
                                    value="{{ $accountEdit->cost }}" placeholder="99999999999" />
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="address" class="form-label">Created At</label>
                                <input type="datetime" class="form-control" id="address" name="created_at"
                                    placeholder="Address" value="{{ $accountEdit->created_at }}" readonly />
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="address" class="form-label">Update Lần Cuối</label>
                                <input type="datetime" class="form-control" id="address" name="updated_at"
                                    placeholder="Address" value="{{ $accountEdit->updated_at }}" readonly />
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="address" class="form-label">Trạng Thái User Hiện Tại</label>
                                <select class="form-control" name="status">
                                    @if ($accountEdit->deleted_at == null)
                                        <option value="1" selected>Hoạt động</option>
                                        <option value="0">Không Hoạt động</option>
                                    @else
                                        <option value="0" selected>Không Hoạt động</option>
                                        <option value="1">Hoạt động</option>
                                    @endif
                                </select>
                            </div>
                            <div class="mt-2">
                                <button type="submit" class="btn btn-primary me-2">Save changes</button>
                                <button type="reset" class="btn btn-outline-secondary">Cancel</button>
                            </div>
                            {{-- </form> --}}
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
