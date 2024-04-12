@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'User Dashboard')

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
        <div class="card-header d-flex justify-content-between m-5 mb-2">
            <a class="btn btn-primary" href="{{ route('user.edit') }}"><i class="ti ti-pencil me-1"></i> Edit</a>

            <a href="#" data-route="{{ route('user.resetPassword') }}" data-id="{{ Auth::id() }}" class="btn btn-primary add-new-role"
                data-bs-target="#addRoleModal" data-bs-toggle="modal" class="dropdown-item add-new-role"><i
                    class="ti ti-key me-1"></i> Reset
                Password</a>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">First Name *</label>
                    <input type="text" class="form-control" name="first_name" value="{{ $user->first_name }}"
                        disabled />
                    @error('first_name')
                        <div class="text-danger pt-2">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Last Name</label>
                    <input type="text" class="form-control phone-mask" name="last_name" value="{{ $user->last_name }}"
                        disabled />
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Email *</label>
                <input type="email" class="form-control" name="email" value="{{ $user->email }}" autofocus disabled>
            </div>
            <div class="mb-3">
                <label class="form-label">contact no</label>
                <input type="tel" class="form-control" name="contact_no" value="{{ $user->contact_no }}" autofocus
                    disabled>
            </div>
            <div class="form-group mb-3">
                <label for="exampleFormControlTextarea1">Address</label>
                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="address" disabled>{{ $user->address }}</textarea>
            </div>
        </div>
    </div>




    @include('admin.users.resetPassword')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var links = document.querySelectorAll('a[data-bs-toggle="modal"]');
            links.forEach(function(link) {
                link.addEventListener('click', function(event) {
                    event.preventDefault(); // Prevent the default action of the link
                    var form = document.getElementById('resetPasswordForm');
                    var id = this.getAttribute('data-id');
                    if (form) {
                        var route = this.getAttribute('data-route');
                        var UserId = document.getElementById('id');
                        if (UserId && route) {
                            UserId.value = id;
                            form.setAttribute('action',
                                route); // Set form action to the route URL
                        } else {
                            console.error('Data-route attribute not found on link.');
                        }
                    } else {
                        console.error('Form not found.');
                    }
                });
            });
        });
    </script>


@endsection
