@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'User Dashboard')

@section('page-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-profile.css') }}" />
@endsection

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

    {{-- <div class="card bg-label-primary  p-4">
        <div class="row g-4">
            <div class="col-xl-4 col-lg-6 col-md-6 w-25">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="mx-auto my-3">
                            <img src="{{ asset('assets/img/avatars/3.png') }}" alt="Avatar Image"
                                class="rounded-circle w-px-100" />
                        </div>
                        <h4 class="mb-3 card-title">{{ $user->first_name }} {{ $user->last_name }}</h4>
                        @if ($user->is_active)
                            <p><span class="badge bg-label-success">Active</span></p>
                        @else
                            <p><span class="badge bg-label-success">Inactive</span></p>
                        @endif
                        <div class="align-items-center justify-content-center">
                            <a class="btn btn-primary d-flex align-items-center mb-3" href="{{ route('user.edit') }}"><i
                                    class="ti ti-pencil me-1"></i> Edit</a>
                            <a href="#" data-route="{{ route('user.resetPassword') }}" data-id="{{ Auth::id() }}"
                                data-bs-target="#addRoleModal" data-bs-toggle="modal"
                                class="btn btn-primary add-new-role d-flex align-items-center mb-3"><i
                                    class="ti ti-key me-1"></i>
                                Reset
                                Password</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6 w-75">
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
        </div>
    </div> --}}




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
                    <a class="btn btn-primary h-50 m-2" href="{{ route('user.edit') }}"><i class="ti ti-pencil me-1"></i>
                        Edit</a>
                    <a href="#" data-route="{{ route('user.resetPassword') }}" data-id="{{ Auth::id() }}"
                        data-bs-target="#addRoleModal" data-bs-toggle="modal"
                        class="btn btn-primary add-new-role h-50 m-2"><i class="ti ti-key me-1"></i>
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
