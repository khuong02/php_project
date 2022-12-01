@extends('layouts/contentNavbarLayout')

@section('title', 'Users - Management')

@section('page-script')
<script src="{{asset('assets/js/form-basic-inputs.js')}}"></script>
@endsection

@section('content')
<h4 class="fw-bold py-3 mb-4">
  <span class="text-muted fw-light">User Management /</span> Users
</h4>
  <div class="card">
    <h5 class="card-header">Quản lý người dùng</h5>
    <div class="table-responsive text-nowrap">
      <table class="table">
        <thead>
          <tr>
            <th>Username</th>
            <th>Email</th>
            <th>Cost</th>
            <th>Avatar</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
          @foreach($listUser as $user)
          <tr>
            <td><i class="fab fa-angular fa-lg text-danger me-3"></i> <strong>{{$user->username}}</strong></td>
            <td><i class="fab fa-angular fa-lg text-danger me-3"></i> {{$user->email}}</td>
            <td><i class="fab fa-angular fa-lg text-danger me-3"></i> {{$user->cost}}</td>
            <td><img src="{{$user->avatar}}" style="width: 50px;height: 50px" class="rounded-circle"/> </td>
            <td><i class="fab fa-angular fa-lg text-danger me-3"></i> {{$user->cost}}</td>
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
@endsection
