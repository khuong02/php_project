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
    <div class="modal fade" id="createAccount" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">CREATE ACCOUNT</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="#">
                    @csrf
                    <div class="row">
                        <div class="demo-vertical-spacing demo-only-element col-md-8 ">

                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon11">@</span>
                                <input type="text" class="form-control" placeholder="Username" aria-label="Username"
                                    aria-describedby="basic-addon11" />
                            </div>

                            <div class="form-password-toggle">
                                <label class="form-label" for="basic-default-password12">Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="basic-default-password12"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-describedby="basic-default-password" />
                                    <span id="basic-default-password" class="input-group-text cursor-pointer"><i
                                            class="bx bx-hide"></i></span>
                                </div>
                            </div>

                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Recipient's username"
                                    aria-label="Recipient's username" aria-describedby="basic-addon13" />
                                <span class="input-group-text" id="basic-addon13">@example.com</span>
                            </div>

                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon14">https://example.com/users/</span>
                                <input type="text" class="form-control" placeholder="URL" id="basic-url1"
                                    aria-describedby="basic-addon14" />
                            </div>

                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="text" class="form-control" placeholder="Amount"
                                    aria-label="Amount (to the nearest dollar)" />
                                <span class="input-group-text">.00</span>
                            </div>

                            <div class="input-group">
                                <span class="input-group-text">With textarea</span>
                                <textarea class="form-control" aria-label="With textarea" placeholder="Comment"></textarea>
                            </div>

                        </div>
                    </div>
                </form>
                {{-- <form method="POST" action="#">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col mb-3">
                                <label for="nameBasic" class="form-label">Name</label>
                                <input type="text" id="nameBasic" name="nametopic" class="form-control"
                                    placeholder="Enter Name">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form> --}}
            </div>
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
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
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
                        // alert(response.responseJSON.message);
                        location.reload(true);
                    },
                })
            });
        });
    </script>

@endsection
