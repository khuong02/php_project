@extends('layouts/contentNavbarLayout')

@section('title', 'Tables - Basic Tables')

@section('content')
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Managetment /</span> Account Admin
    </h4>

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
                                <a href="/account/edit/{{ $itemAcc->id }}"><i
                                        class="fab fa-angular fa-lg text-danger me-3"></i><strong>{{ $itemAcc->username }}</strong></a>
                            </td>
                            <td>{{ $itemAcc->email }}</td>
                            <td>
                                <ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center">
                                    <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                                        class="avatar avatar-xs pull-up" title="Lilian Fuller">
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
                                <form class="deleteAcc" action="{{ route('delete-account-admin') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="deleteIdValue" value="{{ $itemAcc->id }}" />
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
    <!--/ Responsive Table -->
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
