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
    <script src="{{ asset('assets/js/pages-profile.js') }}"></script>
    <script src="{{ asset('assets/js/app-access-roles.js') }}"></script>
    <script src="{{ asset('assets/js/modal-add-role.js') }}"></script>
    <script src="{{ asset('assets/js/toast-message.js') }}"></script>
    <script src="https://code.jquery.com/jquery-2.2.4.min.js" type="text/javascript"></script>
@endsection

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="user-profile-header-banner">
                    <img src="https://media.istockphoto.com/id/1152097010/photo/beautiful-abstract-pink-and-blue-sky-landscape-background-and-wallpaper.jpg?s=2048x2048&w=is&k=20&c=AgO0hGjs2Felk21Fw69PQfJxN7WGF-XIWQ2wUqVkSvo="
                        alt="Image
                        1" class="rounded-top">
                </div>
                <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-4">
                    <div class="flex-shrink-0 mt-n2 mx-sm-0 mx-auto">
                        <img src="{{ asset('assets/img/avatars/14.png') }}" alt="user image"
                            class="d-block h-auto ms-0 ms-sm-4 rounded user-profile-img">
                    </div>
                    <div class="flex-grow-1 mt-3 mt-sm-5">
                        <div
                            class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-4 flex-md-row flex-column gap-4">
                            <div class="user-profile-info">
                                <h4>{{ $user->first_name }} {{ $user->last_name }}</h4>
                                <ul
                                    class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-2">
                                    <li class="list-inline-item d-flex">
                                        <i class="fa-solid fa-toggle-on"></i>
                                        @if ($user->is_active)
                                            <p><span class="m-3 mb-0 mt-0">Active</span></p>
                                        @else
                                            <p><span class="m-3 mb-0 mt-0">Inactive</span></p>
                                        @endif
                                    </li>
                                </ul>
                            </div>
                            <div class="d-flex">
                                <a href="#" data-route="{{ route('user.edit') }}" data-bs-target="#editUser"
                                    data-bs-toggle="modal" class="btn btn-primary h-50 m-2 " id="edit-user"><i
                                        class="ti ti-pencil me-1"></i>Edit</a>
                                <a href="#" data-route="{{ route('user.resetPassword') }}"
                                    data-id="{{ Auth::id() }}" data-bs-target="#addRoleModal" data-bs-toggle="modal"
                                    class="btn btn-primary h-50 m-2" id="reset-password"><i class="ti ti-key me-1"></i>
                                    Reset
                                    Password</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card bg-label-primary  p-4">
        <div class="card">
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
