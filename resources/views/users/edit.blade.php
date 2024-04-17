@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Edit User Details')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js') }}"></script>
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/app-access-roles.js') }}"></script>
    <script src="{{ asset('assets/js/modal-add-role.js') }}"></script>
@endsection

@section('content')

<div class="card">
  <div class="card-header d-flex justify-content-center m-5 mb-2">
<h3>Edit Details</h3>
  </div>
  <div class="card-body">
    <form action="{{ route('user.update') }}" method="post"  id="formAuthentication" class="mb-3">
      @csrf
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">First Name *</label>
          <input type="text" class="form-control" name="first_name" value="{{ $user->first_name }}" required/>
          @error('first_name')
          <div class="text-danger pt-2">{{ $message }}</div>
          @enderror
        </div>
        <div class="col-md-6">
          <label class="form-label">Last Name</label>
          <input type="text" class="form-control phone-mask" name="last_name" value="{{ $user->last_name }}" />
        </div>
      </div>
      <div class="mb-3">
        <label class="form-label">Email *</label>
        <input type="email" class="form-control" name="email" value="{{ $user->email }}" autofocus disabled>
      </div>
      <div class="mb-3">
        <label class="form-label">contact no</label>
        <input type="tel" class="form-control" name="contact_no" value="{{ $user->contact_no }}" autofocus>
        @error('contact_no')
          <div class="text-danger pt-2">{{ $message }}</div>
        @enderror
      </div>
      <div class="form-group mb-3">
        <label for="exampleFormControlTextarea1">Address</label>
        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="address">{{ $user->address }}</textarea>
      </div>

      <div class="row">
        <div class="mt-3">
          <button type="submit" class="btn btn-primary me-2">Update</button>
          <a href="{{ route('user.dashboard') }}" class="btn btn-label-secondary">Cancle</a>
        </div>
      </div>
    </form>

  </div>
</div>

@endsection
