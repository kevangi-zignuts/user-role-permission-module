@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'User Dashboard')

@section('page-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-profile.css') }}" />
@endsection

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/animate-css/animate.css') }}" />
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
    <script src="{{ asset('assets/js/toast-message.js') }}"></script>
    <script src="https://code.jquery.com/jquery-2.2.4.min.js" type="text/javascript"></script>
@endsection

@section('content')

    <div class="card bg-label-primary  p-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="d-flex">
                    <img src="{{ asset('assets/img/avatars/3.png') }}" alt="Avatar Image" class="rounded-circle w-px-75" />
                    <div>
                        <h4 class="card-title m-3 mb-0">{{ $user->first_name }} {{ $user->last_name }}</h4>
                        @if ($user->is_active)
                            <p><span class="badge bg-label-success m-3 mb-0 mt-0">Active</span></p>
                        @else
                            <p><span class="badge bg-label-success m-3 mb-0 mt-0">Inactive</span></p>
                        @endif
                    </div>
                </div>
                <div class="d-flex">
                    <a href="#" data-route="{{ route('user.edit') }}" data-bs-target="#editUser"
                        data-bs-toggle="modal" class="btn btn-primary h-50 m-2 " id="edit-user"><i
                            class="ti ti-pencil me-1"></i>Edit</a>
                    <a href="#" data-route="{{ route('user.resetPassword') }}" data-id="{{ Auth::id() }}"
                        data-bs-target="#addRoleModal" data-bs-toggle="modal" class="btn btn-primary h-50 m-2"
                        id="reset-password"><i class="ti ti-key me-1"></i>
                        Reset
                        Password</a>
                </div>
            </div>
            <div class="card-body">
                <div class="w-100 mt-4 pb-0 border-bottom d-flex">
                    <p class="fw-bold mb-2">First Name :- </p>
                    <p class="m-5 mb-2 mt-0">{{ $user->first_name }}</p>
                </div>
                <div class="w-100 mt-4 pb-0 border-bottom d-flex">
                    <p class="fw-bold mb-2">Last Name :- </p>
                    <p class="m-5 mb-2 mt-0">{{ $user->last_name }}</p>
                </div>
                <div class="w-100 mt-4 pb-0 border-bottom d-flex">
                    <p class="fw-bold mb-2">Email :- </p>
                    <p class="m-5 mb-2 mt-0">{{ $user->email }}</p>
                </div>
                <div class="w-100 mt-4 pb-0 border-bottom d-flex">
                    <p class="fw-bold mb-2">Contact No. :- </p>
                    <p class="m-5 mb-2 mt-0">{{ $user->contact_no }}</p>
                </div>
                <div class="w-100 mt-4 pb-0 border-bottom d-flex">
                    <p class="fw-bold mb-2">Address :- </p>
                    <p class="m-5 mb-2 mt-0">{{ $user->address }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bs-toast toast toast-ex animate__animated my-2" role="alert" aria-live="assertive" aria-atomic="true"
        data-bs-delay="2000">
        <div class="toast-header">
            <i class="ti ti-bell ti-xs me-2"></i>
            <div class="me-auto fw-semibold">Success</div>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            Hello, world! This is a toast message.
        </div>
    </div>

    <div class="bs-toast toast toast-ex animate__animated my-2 error-message" role="alert" aria-live="assertive"
        aria-atomic="true" data-bs-delay="2000">
        <div class="toast-header">
            <i class="ti ti-bell ti-xs me-2"></i>
            <div class="me-auto fw-semibold">Error</div>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body toast-body-error">
            Hello, world! This is a toast message.
        </div>
    </div>

    @include('admin.users.resetPassword')
    @include('users.edit')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var link = document.querySelector('a[data-bs-toggle="modal"][id="edit-user"]');
            link.addEventListener('click', function(event) {
                event.preventDefault();
                var form = document.getElementById('editForm');
                if (form) {
                    var route = this.getElementById('editForm');
                    if (form) {
                        var route = this.getAttribute('data-route');
                        if (route) {
                            form.setAttribute('action', route);
                        } else {
                            console.error('Data-route attribute not found on link.');
                        }
                    } else {
                        console.error('Form not found.');
                    }
                }
            })
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var link = document.querySelector('a[data-bs-toggle="modal"][id="reset-password"]');
            link.addEventListener('click', function(event) {
                console.log(link);
                event.preventDefault();
                var form = document.getElementById('resetPasswordForm');
                var id = this.getAttribute('data-id');
                if (form) {
                    var route = this.getAttribute('data-route');
                    var UserId = document.getElementById('id');
                    if (UserId && route) {
                        UserId.value = id;
                        form.setAttribute('action', route);
                        console.log(form);
                    } else {
                        console.error('Data-route attribute not found on link.');
                    }
                } else {
                    console.error('Form not found.');
                }

            });
        });
    </script>


@endsection
