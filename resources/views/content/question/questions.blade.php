@extends('layouts/contentNavbarLayout')

@section('title', 'Question - Management')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css"
        integrity="sha512-wJgJNTBBkLit7ymC6vvzM1EcSWeM9mmOu+1USHaRBbHkm6W9EgM0HY27+UtUaprntaYQJF75rc8gjxllKs5OIQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
@endsection

@section('content')
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Questions Management /</span> Questions
    </h4>

    <p>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createquestion">
            Create a new question
        </button>
    </p>

    <!-- Start Modal Create -->
    <div class="modal fade" id="createquestion" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">CREATE TOPIC</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul id="errorList"></ul>
                    <div class="row">
                        <div class="form-group col mb-3">
                            <label for="question" class="form-label">Question content</label>
                            <input type="text" id="questioncontent" class="form-control" placeholder="Enter Name">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col mb-3">
                            <label for="Topic" class="form-label">Topic</label>
                            <select id="topic" class="form-control"></select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col mb-3">
                            <label for="Difficult" class="form-label">Difficult</label>
                            <select id="difficult" class="form-control"></select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="btnCreate" class="btn btn-primary">Create</button>
                </div>

            </div>
        </div>
    </div>
    {{-- End modal create --}}
    {{-- Start modal delete --}}
    <div class="modal fade" id="deletequestion" tabindex="-1" aria-hidden="true">
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
        <h5 class="card-header">Quản lý câu hỏi</h5>
        <div class="table-responsive text-nowrap">
            <table class="table" id="tablequestion">
                <thead>
                    <tr>
                        <th>Question</th>
                        <th>Topic</th>
                        <th>Difficult</th>
                        <th>Status</th>
                        <th>Action</th>
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
            getQuestionData();

            function getQuestionData() {
                $.ajax({
                    type: 'GET',
                    url: '/questionlist',
                    dataType: 'json',
                    success: function(res) {
                        $('tbody').html('');
                        $('#topic').html('');
                        $('#difficult').html('');

                        //Chèn dữ liệu từ database vào table
                        var appenddata = '';
                        $.each(res.questions, function(key, value) {
                            appenddata += '<tr>';
                            appenddata +=
                                '<td><a href=""><i class="fab fa-angular fa-lg text-danger me-3"></i> <strong>' +
                                value.question + '</strong></a></td>';
                            appenddata +=
                                '<td><i class="fab fa-angular fa-lg text-danger me-3"></i> ' +
                                value.topic + '</td>';
                            appenddata +=
                                '<td><i class="fab fa-angular fa-lg text-danger me-3"></i> ' +
                                value.difficult + '</td>';
                            if (value.deleted_at == null) {
                                appenddata +=
                                    '<td><span class="badge bg-label-primary me-1">\
                                                            Active';
                                appenddata +=
                                    '</span></td>\
                                                            <td>\
                                                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" id="delete" data-bs-target="#deletequestion" data-id="' +
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
                                                                <button type="button" class="btn btn-outline-danger" disabled data-bs-toggle="modal" id="delete" data-bs-target="#deletequestion" data-id="' +
                                    value.id +
                                    '">\
                                                                <i class="bx bx-trash me-1"></i> Delete\
                                                                </button>\
                                                                </div>\
                                                                </td>\
                                                                </tr>';
                            }
                        });

                        //Chèn dữ liệu topic vào select topic
                        var optiontopic = '';
                        $.each(res.topics, function(key, value) {
                            optiontopic +=
                                `<option value = "${value.id}">${value.name}</option>`;
                        });

                        //Chèn dữ liệu difficult vào select difficult
                        var optiondiff = '';
                        $.each(res.difficults, function(key, value) {
                            optiondiff +=
                                `<option value = "${value.id}">${value.name}</option>`;
                        });
                        $('#difficult').html(optiondiff);
                        $('#topic').html(optiontopic);
                        $('tbody').html(appenddata);
                    },
                    error: function(err) {
                        console.log(err);
                    }
                });
            }

            $(document).on("click", "#btnCreate", function(e) {
                e.preventDefault();
                var data = {
                    'question': $("#questioncontent").val(),
                    'quizz_id': $("#topic").val(),
                    'difficulty_id': $("#difficult").val(),
                };
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'POST',
                    url: '/questions',
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
                        $("#createquestion").modal('hide');
                        $("#createquestion").find('input').val("");
                        getQuestionData();

                    },
                    error: function(err) {
                        $.toast({
                            heading: 'Error',
                            text: err.responseJSON.message.name,
                            position: 'top-right',
                            icon: 'error',
                            stack: false
                        });
                        $("#createquestion").modal('hide');
                        $("#createquestion").find('input').val("");
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
                    url: '/questions/' + id,
                    dataType: 'json',
                    success: function(response) {
                        $.toast({
                            heading: 'Success',
                            text: response.message,
                            position: 'top-right',
                            icon: 'success',
                            stack: false
                        });

                        $("#deletequestion").modal('hide');
                        $("#deletequestion").find('input').val("");
                        getQuestionData();


                    },
                    error: function(err) {
                        $.toast({
                            heading: 'Error',
                            text: err.responseJSON.message.name,
                            position: 'top-right',
                            icon: 'error',
                            stack: false
                        });
                        $("#deletequestion").modal('hide');
                        $("#deletequestion").find('input').val("");
                    }
                });
            });
        });
    </script>

@endsection
