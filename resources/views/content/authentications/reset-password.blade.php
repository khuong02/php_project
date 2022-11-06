@extends('layouts/blankLayout')

@section('title', 'Forgot Password Basic - Pages')

@section('page-style')
<!-- Page -->
<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/page-auth.css')}}">
@endsection

@section('content')
<div class="container-xxl">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner py-4">

      <!-- Forgot Password -->
      <div class="card">
        <div class="card-body">
          <!-- Logo -->
          <div class="app-brand justify-content-center">
            <a href="#" class="app-brand-link gap-2">
              <span class="app-brand-logo demo">@include('_partials.macros',['width'=>25,'withbg' => "#696cff"])</span>
              <span class="app-brand-text demo text-body fw-bolder">{{ config('variables.templateName') }}</span>
            </a>
          </div>
          <!-- /Logo -->
          <h4 class="mb-2">Reset Password? 🔒</h4>
          <p class="mb-4">Enter your new password and we will update it for you</p>
          <form id="formAuthentication" class="mb-3" action="{{ route('change-password') }}" method="post">
            @csrf
            <div class="mb-3">
              <input type="hidden" name ="token" id="token" value = "{{$token}}" >
              <label for="password" class="form-label">Password</label>
              <input type="text" class="form-control" id="password" name="password" placeholder="Enter your new password" autofocus>
              <label for="password" class="form-label">password confirm</label>
              <input type="text" class="form-control" id="password_confirm" name="password_confirm" placeholder="Enter your new password confirm" autofocus>
            </div>
            <button class="btn btn-primary d-grid w-100">Reset Password</button>
          </form>
        </div>
      </div>
      <!-- /Forgot Password -->
    </div>
  </div>
</div>
@endsection
