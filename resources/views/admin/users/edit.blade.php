@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'User Edit')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/tagify/tagify.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/typeahead-js/typeahead.css')}}" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
<script src="{{asset('assets/vendor/libs/tagify/tagify.js')}}"></script>
<script src="{{asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js')}}"></script>
<script src="{{asset('assets/vendor/libs/typeahead-js/typeahead.js')}}"></script>
<script src="{{asset('assets/vendor/libs/bloodhound/bloodhound.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/forms-selects.js')}}"></script>
<script src="{{asset('assets/js/forms-tagify.js')}}"></script>
<script src="{{asset('assets/js/forms-typeahead.js')}}"></script>
@endsection


@section('content')

<div class="container-xxl">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner py-4 mx-auto">
      <div class="card">
        <div class="card-body">
          <div class="app-brand justify-content-center mb-4 mt-2">
            <span class="app-brand-text demo text-body fw-bold ms-1">Edit User</span>
          </div>
          <form action="{{ route('users.update', ['id' => $user->id]) }}" method="post"  id="formAuthentication" class="mb-3">
            @csrf
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">First Name *</label>
                <input type="text" class="form-control" name="first_name" value="{{ $user->first_name }}" required/>
                @error('first_name')
                  <div class="text-danger pt-2">{{ $message }}</div>
                @enderror
              </div>
              <div class="mb-3">
                <label class="form-label">Email *</label>
                <input type="email" class="form-control" name="email" value="{{ $user->email }}" autofocus required disabled>
              </div>
              <div class="col-md-6">
                <label class="form-label">Last Name</label>
                <input type="text" class="form-control phone-mask" name="last_name" value="{{ $user->last_name }}" />
              </div>
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
            <div class="col-md-6 mb-4">
              <label for="select2Primary" class="form-label">Select Role</label>
              <div class="select2-primary">
                <select id="select2Primary" class="select2 form-select" name="roles[]" multiple>
                  @foreach ($roles as $role)
                    <option value="{{ $role->id }}" {{ $user->role->contains($role->id) ? 'selected' : '' }}>{{ $role->role_name }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="row">
              <div class="mt-3">
                <button type="submit" class="btn btn-primary me-2">Update</button>
                <a href="{{ route('users.index') }}" class="btn btn-label-secondary">Cancle</a>
              </div>
            </div>
          </form>
        </div>
      </div>
      <!-- /Register -->
    </div>
  </div>
</div>

@endsection
