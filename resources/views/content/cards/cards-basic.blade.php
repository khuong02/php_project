@extends('layouts/contentNavbarLayout')

@section('title', 'Topics - Management')

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/masonry/masonry.js')}}"></script>
@endsection

@section('content')
<div class="card">
  <h5 class="card-header">Quản lý câu hỏi</h5>
  <div class="table-responsive text-nowrap">
    <table class="table">
      <thead>
        <tr>
          <th>Topic name</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        @foreach($listTopic as $topic)
        <tr>
          <td><i class="fab fa-angular fa-lg text-danger me-3"></i> <strong>{{$topic->name}}</strong></td>
          @if($topic->deleted_at === null)  
          <td><span class="badge bg-label-primary me-1">
            Active
            @else
            <td><span class="badge bg-label-warning me-1">
            Inactive
          @endif
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
@endsection
