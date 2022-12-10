@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Account - Admin')

@section('vendor-script')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css"
        integrity="sha512-wJgJNTBBkLit7ymC6vvzM1EcSWeM9mmOu+1USHaRBbHkm6W9EgM0HY27+UtUaprntaYQJF75rc8gjxllKs5OIQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('content')
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Account Settings /</span> Account
    </h4>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h5 class="card-header">Question Details</h5>
                <form id="formQuestion" method="POST" action="{{ route('update-question', ['id' => $questionData->id]) }}">
                    @method('PUT')
                    @csrf
                    <input type="hidden" value="{{ $questionData->id }}" name="idUpdate">
                    <div class="card-body">
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label for="question" class="form-label">Question</label>
                                <input class="form-control" type="text" id="question" name="question"
                                    value="{{ $questionData->question }}" autofocus />
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="email" class="form-label">Topic</label>
                                <select class="form-control" name="topic" id="topic">
                                    @foreach ($listTopic as $topic)
                                        @if ($topic['id'] == $questionData->quizz_id)
                                            <option value="{{ $topic['id'] }}" selected>{{ $topic['name'] }}</option>
                                        @else
                                            <option value="{{ $topic['id'] }}">{{ $topic['name'] }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="email" class="form-label">Diff</label>
                                <select class="form-control" name="diff" id="diff">
                                    @foreach ($listDiff as $diff)
                                        @if ($diff['id'] == $questionData->difficulty_id)
                                            <option value="{{ $diff['id'] }}" selected>{{ $diff['name'] }}</option>
                                        @else
                                            <option value="{{ $diff['id'] }}">{{ $diff['name'] }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <hr class="my-0">
                    <div class="card-body">
                        <div class="row">
                            {{-- {{ dd($listAnswer) }} --}}
                            @if (count($listAnswer) == 0)
                                <input hidden name="questionNew" value="true">
                                <div class="mb-3 col-md-6">
                                    <label for="Answer1" class="form-label">Answer</label>
                                    <input class="form-control" type="text" id="Answer1" name="Answer1" value=""
                                        autofocus />
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="Answer2" class="form-label">Answer</label>
                                    <input class="form-control" type="text" id="Answer2" name="Answer2" value=""
                                        autofocus />
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="Answer3" class="form-label">Answer</label>
                                    <input class="form-control" type="text" id="Answer3" name="Answer3" value=""
                                        autofocus />
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="correctAnswer" class="form-label">Correct Answer</label>
                                    <input class="form-control" type="text" id="correctAnswer" name="correctAnswer"
                                        value="" autofocus />
                                </div>
                            @else
                                <input hidden name="questionNew" value="false">
                                <?php $count = 0; ?>
                                @foreach ($listAnswer as $answer)
                                    @if ($answer->correct_answer == 0)
                                        <?php $count++; ?>
                                        <div class="mb-3 col-md-6">
                                            <label for="Answer{{ $count }}" class="form-label">Answer</label>
                                            <input hidden name="idAnswer{{ $count }}" value="{{ $answer->id }}">
                                            <input class="form-control" type="text" id="Answer{{ $count }}"
                                                name="Answer{{ $count }}" value="{{ $answer->answer }}"
                                                autofocus />
                                        </div>
                                    @else
                                        <div class="mb-3 col-md-6">
                                            <label for="correctAnswer" class="form-label">Correct Answer</label>
                                            <input hidden name="idCorrectAnswer" value="{{ $answer->id }}">
                                            <input class="form-control" type="text" id="correctAnswer"
                                                name="correctAnswer" value="{{ $answer->answer }}" autofocus />
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                            <div class="mb-3 col-md-6">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-control" name="status">
                                    @if ($questionData->deleted_at == null)
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
                        </div>
                </form>
                <!-- /Account -->
            </div>
        </div>
    </div>
@endsection


@section('page-script')
    <script src="{{ asset('assets/js/jquery.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js"
        integrity="sha512-zlWWyZq71UMApAjih4WkaRpikgY9Bz1oXIW5G0fED4vk14JjGlQ1UmkGM392jEULP8jbNMiwLWdM8Z87Hu88Fw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(function() {
            $("#formQuestion").on("submit", function(e) { //id of form
                e.preventDefault();

                var checkValueAnswer1vsAnswer2 = $('#Answer1').val() == $('#Answer2').val();
                var checkValueAnswer1vsAnswer3 = $('#Answer1').val() == $('#Answer3').val();
                var checkValueAnswer1vscorrectAnswer = $('#Answer1').val() == $('#correctAnswer').val();
                var checkValueAnswer2vsAnswer3 = $('#Answer2').val() == $('#Answer3').val();
                var checkValueAnswer2vscorrectAnswer = $('#Answer2').val() == $('#correctAnswer').val();
                var checkValueAnswer3vscorrectAnswer = $('#Answer3').val() == $('#correctAnswer').val();

                var checkEmptyAnswer1 = $('#Answer1').val() == '';
                var checkEmptyAnswer2 = $('#Answer2').val() == '';
                var checkEmptyAnswer3 = $('#Answer3').val() == '';
                var checkEmptyCorrectAnswer = $('#correctAnswer').val() == '';

                if (checkValueAnswer1vsAnswer2 || checkValueAnswer1vsAnswer3 ||
                    checkValueAnswer1vscorrectAnswer || checkValueAnswer2vsAnswer3 ||
                    checkValueAnswer2vscorrectAnswer || checkValueAnswer3vscorrectAnswer ||
                    checkEmptyAnswer1 || checkEmptyAnswer2 || checkEmptyAnswer3 || checkEmptyCorrectAnswer
                ) {
                    $.toast({
                        heading: 'Error',
                        text: 'Duplicate or empty answer !!!',
                        position: 'top-right',
                        icon: 'error',
                        stack: false
                    });
                } else {
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
                            console.log(response);
                            $('#diff').val(response.questionData.difficulty_id).change();
                            $('#topic').val(response.questionData.quizz_id).change();
                            $.toast({
                                heading: 'Success',
                                text: response.message,
                                position: 'top-right',
                                icon: 'success',
                                stack: false
                            });
                        },
                        error: function(response) {
                            $.toast({
                                heading: 'Error',
                                text: err.responseJSON.message.name,
                                position: 'top-right',
                                icon: 'error',
                                stack: false
                            });
                        },
                    })
                }
            });
        });
    </script>
@endsection
