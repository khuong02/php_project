{{-- {{-- @extends('layouts/contentNavbarLayout')

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
{{-- <div class="modal fade" id="deleteAccount" tabindex="-1" aria-hidden="true">
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
    </div> --}}
{{-- End modal delete --}}

<!-- Toast with Placements -->
{{-- <div class="bs-toast toast toast-placement-ex m-2 top-0 end-0 " role="alert" aria-live="assertive" aria-atomic="true"
        id="delete-success" data-delay="500">
        <div class="toast-header">
            <i class='bx bx-bell me-2'></i>
            <div class="me-auto fw-semibold">Bootstrap</div>
            <small>11 mins ago</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            Fruitcake chocolate bar tootsie roll gummies gummies jelly beans cake.
        </div>
    </div> --}}
<!-- Toast with Placements -->

{{-- 
    <script src="{{ asset('assets/js/jquery.js') }}"></script>
    <script src="{{ asset('assets/js/ui-toasts.js') }}"></script>


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
    </script> --}}
{{-- @endsection --}}
@extends('layouts/contentNavbarLayout')

@section('title', 'Account User - Management')

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
                        <th>Username</th>
                        <th>Email</th>
                        <th>Cost</th>
                        <th>Avatar</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                </tbody>
            </table>
        </div>
    </div>

    <script src="{{ asset('assets/js/jquery.js') }}"></script>
@endsection

@section('page-script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js"
        integrity="sha512-zlWWyZq71UMApAjih4WkaRpikgY9Bz1oXIW5G0fED4vk14JjGlQ1UmkGM392jEULP8jbNMiwLWdM8Z87Hu88Fw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(document).ready(function() {
            getUserData();

            function getUserData() {
                $.ajax({
                    type: 'GET',
                    url: '/account/userlist',
                    dataType: 'json',
                    success: function(resource) {
                        $('tbody').html('');
                        var appendata = '';
                        $.each(resource.users, function(key, value) {
                            appendata += `<tr>
                                <td>
                                    <a href="/account/user/update/${value.id}"><i
                                    class="fab fa-angular fa-lg text-danger me-3"></i><strong>${value.username}</strong></a>
                                </td>
                                <td>${value.email}</td>
                                <td>${value.cost}</td>
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
            $(document).on("click", "#delete", function(e) {
                var eventId = $(this).data('id');
                document.getElementById('valueDelete').value = eventId;
            });

            $(document).on("click", "#btnDelete", function(e) {
                e.preventDefault();
                var idDelete = $("#valueDelete").val();
                console.log(idDelete);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'DELETE',
                    url: '/account/user/' + idDelete,
                    dataType: 'json',
                    success: function(response) {
                        $.toast({
                            heading: 'Success',
                            text: response.message,
                            position: 'top-right',
                            icon: 'success',
                            stack: false
                        });
                        // tiep tai day
                        $("#deleteAccount").modal('hide');
                        $("#deleteAccount").find('input').val("");
                        getUserData();
                    },
                    error: function(err) {
                        $.toast({
                            heading: 'Error',
                            text: err.responseJSON.message.name,
                            position: 'top-right',
                            icon: 'error',
                            stack: false
                        });
                        $("#deleteAccount").modal('hide');
                        $("#deleteAccount").find('input').val("");
                    }
                });
            })
        })
    </script>


@endsection
