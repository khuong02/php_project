@extends('layouts/contentNavbarLayout')

@section('title', 'Tables - Basic Tables')

@section('content')
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Managetment /</span> Account Admin
    </h4>
    <p>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createAccount">
            Create a new Account
        </button>
    </p>
    <!-- Basic Bootstrap Table -->
    <div class="card">
        <h5 class="card-header">Account Admin</h5>
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
                    @foreach ($listAcc as $itemAcc)
                        <tr>
                            <td>
                                <a href="/account-admin/edit/{{ $itemAcc->id }}"><i
                                        class="fab fa-angular fa-lg text-danger me-3"></i><strong>{{ $itemAcc->username }}</strong></a>
                            </td>
                            <td>{{ $itemAcc->email }}</td>
                            <td>
                                <ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center">
                                    <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                                        class="avatar avatar-xs pull-up" title="{{ $itemAcc->username }}">
                                        <img src="{{ $itemAcc->avatar }}" alt="Avatar" class="rounded-circle">
                                    </li>
                                </ul>
                            </td>
                            <td>
                                @if ($itemAcc->deleted_at == null)
                                    <span class="badge bg-label-primary me-1">Active</span>
                                @else
                                    <span class="badge bg-label-warning me-1">InActive</span>
                                @endif
                            </td>
                            <td>
                                @if ($itemAcc->deleted_at == null)
                                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal"
                                        id="delete" data-bs-target="#deleteAccount" data-id="{{ $itemAcc->id }}">
                                        <i class="bx bx-trash me-1"></i> Delete
                                    </button>
                                @else
                                    <button type="button" class="btn btn-outline-danger" disabled data-bs-toggle="modal"
                                        id="delete" data-bs-target="#deleteAccount" data-id="{{ $itemAcc->id }}">
                                        <i class="bx bx-trash me-1"></i> Delete
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <!--/ Responsive Table -->


    <!-- Start Modal Create -->
    <!-- Modal -->
    <div class="modal fade" id="createAccount" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="fromCraeteAccount" action="{{ url('/api/registerAdmin') }}" method="post">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel1">Create Account Admin</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col mb-3">
                                <label for="nameBasic" class="form-label">User Name</label>
                                <input type="text" name="username" id="userName" class="form-control"
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
                                <input type="password" name="password" id="emailBasic" class="form-control"
                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;">
                            </div>
                            <div class="col mb-3    ">
                                <label for="dobBasic" class="form-label">Confirm Password</label>
                                <input type="password" name="password_confirmation" id="dobBasic" class="form-control"
                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;">
                            </div>
                        </div>
                    </div>
                    <input hidden name="token" value="{{ Cookie::get('token') }}">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Craete account</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    {{-- End modal create --}}


    <!-- Toast with Placements -->
    <div class="bs-toast toast toast-placement-ex m-2 top-0 end-0 " role="alert" aria-live="assertive"
        aria-atomic="true" data-delay="2000">
        <div class="toast-header">
            <i class='bx bx-bell me-2'></i>
            <div class="me-auto fw-semibold">Bootstrap</div>
            <small>11 mins ago</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            Fruitcake chocolate bar tootsie roll gummies gummies jelly beans cake.
        </div>
    </div>
    <!-- Toast with Placements -->


    {{-- Start modal delete --}}
    <div class="modal fade" id="deleteAccount" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">DELETE CONFIRM</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="fromDeleteAccount" action="{{ route('delete-account-admin') }}" method="post">
                    @csrf
                    <input type="hidden" name="deleteIdValue" value="" id="valueDelete" />
                    <div class="modal-body">
                        <div class="row">
                            <div class="col mb-3">
                                <h5>Are you sure want to delete this Account!!</h5>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- End modal delete --}}

    <script src="{{ asset('assets/js/jquery.js') }}"></script>
    <script type="text/javascript">
        $(document).on("click", "#delete", function() {
            var eventId = $(this).data('id');
            document.getElementById('valueDelete').value = eventId;

        });
        // delete account
        $(function() {
            $("#fromDeleteAccount").on("submit", function(e) { //id of form
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
                        // location.reload(true);
                    },
                })
            });
        });
        // craete account
        $(function() {
            $("#fromCraeteAccount").on("submit", function(e) { //id of form
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
