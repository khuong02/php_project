@extends('layouts/contentNavbarLayout')

@section('title', 'Account Admin - Management')

@section('vendor-script')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css"
        integrity="sha512-wJgJNTBBkLit7ymC6vvzM1EcSWeM9mmOu+1USHaRBbHkm6W9EgM0HY27+UtUaprntaYQJF75rc8gjxllKs5OIQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="{{ asset('assets/vendor/libs/masonry/masonry.js') }}"></script>
@endsection

@section('content')
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Managetment /</span> Account User
    </h4>
    <p>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createAccount">
            Create a new Account
        </button>
    </p>
    <!-- Start Modal Create -->
    <div class="modal fade" id="createAccount" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            {{-- <form id="fromCraeteAccount" action="{{ url('/api/registerAdmin') }}" method="post">
                @csrf --}}
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Create Account Admin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-3">
                            <label for="nameBasic" class="form-label">User Name</label>
                            <input type="text" name="username" id="username" class="form-control"
                                placeholder="Enter UserName">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="nameBasic" class="form-label">Email</label>
                            <input type="text" name="email" id="email" class="form-control"
                                placeholder="Enter Email">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="emailBasic" class="form-label">Password</label>
                            <input type="password" name="password" id="password" class="form-control"
                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;">
                        </div>
                        <div class="col mb-3    ">
                            <label for="dobBasic" class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="cf_password" class="form-control"
                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;">
                        </div>
                    </div>
                </div>
                <input hidden name="token" id="token" value="{{ Cookie::get('token') }}">
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="btnCraete">Craete account</button>
                </div>
            </div>
            {{-- </form> --}}
        </div>
    </div>
    {{-- End modal create --}}
    {{-- Start modal delete --}}
    <div class="modal fade" id="deleteAccount" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">DELETE CONFIRM</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <input type="hidden" name="deleteIdValue" value="" id="valueDelete" />
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-3">
                            <h5>Are you sure want to delete this Account!!</h5>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="btnDelete">Delete</button>
                </div>
            </div>
        </div>
    </div>
    {{-- End modal delete --}}
    <div class="card">
        <h5 class="card-header">Quản Lý Account User</h5>
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>UserName</th>
                        <th>Email</th>
                        <th>Avatar</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/jquery.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js"
        integrity="sha512-zlWWyZq71UMApAjih4WkaRpikgY9Bz1oXIW5G0fED4vk14JjGlQ1UmkGM392jEULP8jbNMiwLWdM8Z87Hu88Fw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            getUserData();

            function getUserData() {
                $.ajax({
                    type: 'GET',
                    url: '/account/adminlist',
                    dataType: 'json',
                    success: function(resource) {
                        $('tbody').html('');
                        var appendata = '';
                        $.each(resource.users, function(key, value) {
                            appendata += `<tr>
                                <td>
                                    <a href="/account/admin/update/${value.id}"><i
                                    class="fab fa-angular fa-lg text-danger me-3"></i><strong>${value.username}</strong></a>
                                </td>
                                <td>${value.email}</td>
                                <td>
                                    <ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center">
                                        <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                                            class="avatar avatar-xs pull-up" title="${value.username}">
                                            <img src=" ${value.avatar}" alt="Avatar" class="rounded-circle">
                                        </li>
                                    </ul>
                                </td>
                                `;
                            if (value.deleted_at == null) {
                                appendata += `
                                <td>
                                    <span class="badge bg-label-primary me-1">Active</span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal"
                                        id="delete" data-bs-target="#deleteAccount" data-id="${value.id}">
                                        <i class="bx bx-trash me-1"></i> Delete
                                    </button>
                                </td>
                                    `;
                            } else {
                                appendata += `
                                <td>
                                    <span class="badge bg-label-warning me-1">InActive</span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal"
                                        id="delete" data-bs-target="#deleteAccount" disabled data-id="${value.id}">
                                        <i class="bx bx-trash me-1"></i> Delete
                                    </button>
                                </td>
                                    `;
                            }
                            $('tbody').html(appendata);
                        })
                    }
                })
            };
            //click delete account
            $(document).on("click", "#delete", function(e) {
                var eventId = $(this).data('id');
                document.getElementById('valueDelete').value = eventId;
            });
            // click cf delete account
            $(document).on("click", "#btnDelete", function(e) {
                e.preventDefault();
                var idDelete = $("#valueDelete").val();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'DELETE',
                    url: '/account/admin/' + idDelete,
                    dataType: 'json',
                    success: function(response) {
                        $.toast({
                            heading: 'Success',
                            text: response.message,
                            position: 'top-right',
                            icon: 'success',
                            stack: false
                        });
                        $("#deleteAccount").modal('hide');
                        $("#deleteAccount").find('input').val("");
                        getUserData();
                    },
                    error: function(err) {
                        console.log(err);
                        $.toast({
                            heading: 'Error',
                            text: responseJSON.message.name,
                            position: 'top-right',
                            icon: 'error',
                            stack: false
                        });
                        $("#deleteAccount").modal('hide');
                        $("#deleteAccount").find('input').val("");
                        getUserData();

                    }
                });
            });
            $(document).on("click", "#btnCraete", function(e) {
                e.preventDefault();
                var data = {
                    'username': $('#username').val(),
                    'email': $('#email').val(),
                    'password': $('#password').val(),
                    'password_confirmation': $('#cf_password').val(),
                    'token': $('#token').val()
                };
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'POST',
                    url: '/account/admin',
                    data: data,
                    dataType: 'json',
                    success: function(response) {
                        $.toast({
                            heading: 'Success',
                            text: response.message,
                            position: 'top-right',
                            icon: 'success',
                            stack: false,
                        });
                        $("#createAccount").modal('hide');
                        $("#createAccount").find('input').val("");
                        getUserData();
                    },
                    error: function(err) {
                        $.toast({
                            heading: 'Error',
                            text: err.responseJSON.message,
                            position: 'top-right',
                            icon: 'error',
                            stack: false,
                        });
                        $("#createAccount").modal('hide');
                        $("#createAccount").find('input').val("");
                        getUserData();
                    }
                });
            });
        })
        // click create acc
    </script>
@endsection
