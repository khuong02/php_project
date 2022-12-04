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
                                <form class="deleteAcc" action="{{ route('delete-account-user') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="deleteIdValue" value="{{ $user->id }}" />
                                    <button type="submit" class="btn btn-outline-danger">
                                        <i class="bx bx-trash me-1"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <!--/ Basic Bootstrap Table -->

    <script src="{{ asset('assets/js/jquery.js') }}"></script>

    <script type="text/javascript">
        $(function() {
            $(".deleteAcc").on("submit", function(e) { //id of form
                e.preventDefault();

                if (confirm('Are you sure delete account ?')) {
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
                }
            });
        });

        $(function() {
            $("#editAcc").on("submit", function(e) { //id of form
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
