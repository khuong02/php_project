@extends('layouts/contentNavbarLayout')

@section('title', 'Perfect Scrollbar - Extended UI')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css')}}" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/extended-ui-perfect-scrollbar.js')}}"></script>
@endsection

@section('content')
<h4 class="fw-bold py-3 mb-4">
  <span class="text-muted fw-light">Questions Management /</span> Questions
</h4>
  <div class="card">
    <h5 class="card-header">Quản lý câu hỏi</h5>
    <div class="table-responsive text-nowrap">
      <table class="table">
        <thead>
          <tr>
            <th>Question</th>
            <th>Topic</th>
            <th>Difficult</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
          @foreach($listQuestions as $question)
          <tr>
            <td><i class="fab fa-angular fa-lg text-danger me-3"></i> <strong>{{$question->question}}</strong></td>
            <td><i class="fab fa-angular fa-lg text-danger me-3"></i> {{$question->quizz_id}}</td>
            <td><i class="fab fa-angular fa-lg text-danger me-3"></i> {{$question->difficulty_id}}</td>
            {{-- @if($topic->deleted_at === null)
            <td><span class="badge bg-label-primary me-1">
              Active
              @else
              <td><span class="badge bg-label-warning me-1">
              Inactive
            @endif --}}
            </span></td>
            <td>
              <div class="dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                <div class="dropdown-menu">
                  <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-detail me-1"></i> Detail</a>
                  <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-edit-alt me-1"></i> Edit</a>
                  <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> Delete</a>
                </div>
              </div>
            </td>
          </tr>
          @endforeach

        </tbody>
      </table>
    </div>
</div>
<br/>
{{$listQuestions->links('content.mypartial.my-paginate')}}

    @endsection
