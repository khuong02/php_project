@extends('layouts/contentNavbarLayout')

@section('title', 'Tables - Basic Tables')
@section('content')
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Managetment /</span> Account User
    </h4>

    <!-- Basic Bootstrap Table -->
    <div class="card">
        <h5 class="card-header">Account User</h5>
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Cost</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($listUser as $user)
                        <tr>
                            <td>
                                <a href="/account-user/edit/{{ $user->id }}"><i
                                        class="fab fa-angular fa-lg text-danger me-3"></i><strong>{{ $user->username }}</strong></a>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->cost }}</td>
                            <td>
                                <ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center">
                                    <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                                        class="avatar avatar-xs pull-up" title="{{ $user->username }}">
                                        <img src="{{ $user->avatar }}" alt="Avatar" class="rounded-circle">
                                    </li>
                                </ul>
                            </td>
                            <td>

                                @if ($user->deleted_at == null)
                                    <span class="badge bg-label-primary me-1">Active</span>
                                @else
                                    <span class="badge bg-label-warning me-1">InActive</span>
                                @endif
                            </td>
                            <td>


                                @if ($user->deleted_at == null)
                                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal"
                                        id="delete" data-bs-target="#deleteAccount" data-id="{{ $user->id }}">
                                        <i class="bx bx-trash me-1"></i> Delete
                                    </button>
                                @else
                                    <button type="button" class="btn btn-outline-danger" disabled data-bs-toggle="modal"
                                        id="delete" data-bs-target="#deleteAccount" data-id="{{ $user->id }}">
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
    <!--/ Basic Bootstrap Table -->


    {{-- Start modal delete --}}
    <div class="modal fade" id="deleteAccount" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">DELETE CONFIRM</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="fromDeleteAccount" action="{{ route('delete-account-user') }}" method="post">
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
