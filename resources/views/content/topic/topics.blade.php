@extends('layouts/contentNavbarLayout')

@section('title', 'Topics - Management')


@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css"
        integrity="sha512-wJgJNTBBkLit7ymC6vvzM1EcSWeM9mmOu+1USHaRBbHkm6W9EgM0HY27+UtUaprntaYQJF75rc8gjxllKs5OIQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/paginationjs/2.1.4/pagination.css" rel="stylesheet" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/masonry/masonry.js') }}"></script>
@endsection

@section('content')

    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Questions Management /</span> Topics
    </h4>
    <p>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createtopic">
            Create a new topic
        </button>
    </p>

    <!-- Start Modal Create -->
    <div class="modal fade" id="createtopic" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">CREATE TOPIC</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                {{-- <form id="formCreate" action="{{ route('topic-store') }}" method="POST">
            @csrf --}}
                <div class="modal-body">
                    <ul id="errorList"></ul>
                    <div class="row">
                        <div class="form-group col mb-3">
                            <label for="nameBasic" class="form-label">Name</label>
                            <input type="text" id="nametopic" class="form-control" placeholder="Enter Name">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="btnCreate" class="btn btn-primary">Create</button>
                </div>
                {{-- </form> --}}
            </div>
        </div>
    </div>
    {{-- End modal create --}}

    {{-- Start modal edit --}}
    <div class="modal fade" id="edittopic" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">EDIT AND UPDATE TOPIC</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-3">
                            <input type="hidden" id="editid" />
                            <label for="nameBasic" class="form-label">Name</label>
                            <input type="text" id="editName" name="name" class="form-control"
                                placeholder="Enter Name">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="btnEdit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>
    {{-- End modal edit --}}

    {{-- Start modal delete --}}
    <div class="modal fade" id="deletetopic" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">DELETE CONFIRM</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-3">
                            <input type="hidden" id="delid" />
                            <h5>Are you sure want to delete this topic!!</h5>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="btnDel">Delete</button>
                </div>
            </div>
        </div>
    </div>
    {{-- End modal delete --}}


    <div class="card">
        <h5 class="card-header">Quản lý lĩnh vực</h5>
        <div class="table-responsive text-nowrap" id="tableTopic">
            <table class="table">
                <thead>
                    <tr>
                        <th>Topic name</th>
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
    <script src="https://pagination.js.org/dist/2.4.1/pagination.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js"
        integrity="sha512-zlWWyZq71UMApAjih4WkaRpikgY9Bz1oXIW5G0fED4vk14JjGlQ1UmkGM392jEULP8jbNMiwLWdM8Z87Hu88Fw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(document).ready(function() {
            getTopicData();

            function getTopicData() {
                $.ajax({
                    type: 'GET',
                    url: '/topiclist',
                    dataType: 'json',
                    success: function(res) {
                        $('#tableTopic').pagination({
                            dataSource: res.topic,
                            pageSize: 6,
                            formatResult: function(data) {},
                            callback: function(data, pagination) {
                                var appenddata = '';
                                $.each(data, function(key, value) {
                                    appenddata +=
                                        '<tr>\
                                                                                                    <td>\
                                                                                                    <a href="" data-bs-toggle="modal" id="edit" data-bs-target="#edittopic" data-id="' +
                                        value.id +
                                        '">\
                                                                                                    <strong>' +
                                        value
                                        .name +
                                        '</strong>\
                                                                                                    </a>\</td>';
                                    if (value.deleted_at == null) {
                                        appenddata +=
                                            '<td><span class="badge bg-label-primary me-1">\
                                                                                                        Active';
                                        appenddata +=
                                            '</span></td>\
                                                                                                        <td>\
                                                                                                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" id="delete" data-bs-target="#deletetopic" data-id="' +
                                            value.id +
                                            '">\
                                                                                                        <i class="bx bx-trash me-1"></i> Delete\
                                                                                                            </button>\
                                                                                                            </div>\
                                                                                                        </td>\
                                                                                                            </tr>';
                                    } else {
                                        appenddata +=
                                            '<td><span class="badge bg-label-warning me-1">\
                                                                                                        Inactive';
                                        appenddata +=
                                            '</span></td>\
                                                                                                                <td>\
                                                                                                            <button type="button" class="btn btn-outline-danger" disabled data-bs-toggle="modal" id="delete" data-bs-target="#deletetopic" data-id="' +
                                            value.id +
                                            '">\
                                                                                                            <i class="bx bx-trash me-1"></i> Delete\
                                                                                                            </button>\
                                                                                                            </div>\
                                                                                                            </td>\
                                                                                                            </tr>';
                                    }
                                });
                                $('tbody').html(appenddata);
                            }
                        })
                    }
                });
            }

            $(document).on("click", "#edit", function(e) {
                e.preventDefault();
                var topicid = $(this).data('id');
                $.ajax({
                    type: 'GET',
                    url: '/topics/' + topicid,
                    dataType: 'json',
                    success: function(res) {
                        $("#editName").val(res.topic.name);
                        $("#editid").val(res.topic.id);

                    }
                });
            });


            $(document).on("click", "#btnCreate", function(e) {
                e.preventDefault();
                var data = {
                    'name': $("#nametopic").val(),
                };
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'POST',
                    url: '/topics',
                    data: data,
                    dataType: 'json',
                    success: function(response) {
                        $.toast({
                            heading: 'Success',
                            text: response.message,
                            position: 'top-right',
                            icon: 'success',
                            stack: false
                        });
                        $("#createtopic").modal('hide');
                        $("#createtopic").find('input').val("");
                        getTopicData();

                    },
                    error: function(err) {
                        $.toast({
                            heading: 'Error',
                            text: err.responseJSON.message.name,
                            position: 'top-right',
                            icon: 'error',
                            stack: false
                        });
                        $("#createtopic").modal('hide');
                        $("#createtopic").find('input').val("");
                    }
                });
            });


            $(document).on("click", "#btnEdit", function(e) {
                e.preventDefault();
                var editid = $("#editid").val();
                var data = {
                    'name': $("#editName").val(),
                };
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'PUT',
                    url: '/topics/' + editid,
                    data: data,
                    dataType: 'json',
                    success: function(response) {
                        $.toast({
                            heading: 'Success',
                            text: response.message,
                            position: 'top-right',
                            icon: 'success',
                            stack: false
                        });

                        $("#edittopic").modal('hide');
                        $("#edittopic").find('input').val("");
                        getTopicData();


                    },
                    error: function(err) {
                        $.toast({
                            heading: 'Error',
                            text: err.responseJSON.message.name,
                            position: 'top-right',
                            icon: 'error',
                            stack: false
                        });
                        $("#edittopic").modal('hide');
                        $("#edittopic").find('input').val("");
                    }
                });
            });

            $(document).on("click", "#delete", function(e) {
                var eventId = $(this).data('id');
                $("#delid").val(eventId);
            });

            $(document).on("click", "#btnDel", function(e) {
                e.preventDefault();
                var id = $("#delid").val();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'DELETE',
                    url: '/topics/' + id,
                    dataType: 'json',
                    success: function(response) {
                        $.toast({
                            heading: 'Success',
                            text: response.message,
                            position: 'top-right',
                            icon: 'success',
                            stack: false
                        });

                        $("#deletetopic").modal('hide');
                        $("#deletetopic").find('input').val("");
                        getTopicData();


                    },
                    error: function(err) {
                        $.toast({
                            heading: 'Error',
                            text: err.responseJSON.message.name,
                            position: 'top-right',
                            icon: 'error',
                            stack: false
                        });
                        $("#deletetopic").modal('hide');
                        $("#deletetopic").find('input').val("");
                    }
                });
            });
            $("#seach").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#tableTopic tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>


@endsection
