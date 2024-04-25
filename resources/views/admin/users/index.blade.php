@extends('layouts/layoutMaster')

@section('title', 'Permission')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/animate-css/animate.css') }}" />
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success" id="alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger" role="alert-error">
            {{ session('error') }}
        </div>
    @endif
    <div class="search-filter m-3 w-75 mx-auto">
        <form action="{{ route('users.index') }}" method="GET" class="d-flex">
            <div class="input-group m-2">
                <input type="text" class="form-control" placeholder="Search User..." name="search"
                    value="{{ $search }}">
            </div>
            <div class="input-group w-px-500 m-2">
                <select name="filter" class="form-select" required>
                    <option value="all" {{ $filter == 'all' ? 'selected' : '' }}>All User</option>
                    <option value="active" {{ $filter == 'active' ? 'selected' : '' }}>Activated User</option>
                    <option value="inactive" {{ $filter == 'inactive' ? 'selected' : '' }}>InActivated User</option>
                </select>
            </div>
            <button class="btn btn-primary m-2 w-25" type="submit">Filter</button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary m-2 w-25"><i
                    class="fa-solid fa-xmark p-1 pt-0 pb-0"></i> Clear</a>
        </form>
    </div>
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <div class="link">
                <a href="{{ route('users.create') }}" class="btn btn-primary m-3 mt-0 mb-0"><i
                        class="fa-solid fa-plus p-2 pt-0 pb-0"></i>Add
                    a User</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive text-nowrap p-2">

                <table class="table">
                    <thead class="bg-purple">
                        <tr>
                            <th scope="col" class="text-white">Name</th>
                            <th scope="col" class="text-white">Role</th>
                            <th scope="col" class="text-white">Status</th>
                            <th scope="col" class="text-white">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($users->isEmpty())
                            <tr>
                                <td colspan="5" class="text-center text-danger h5">No data available</td>
                            </tr>
                        @endif
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->first_name }}</td>
                                <td>
                                    @php
                                        $total = $user->role->count();
                                        $count = 0;
                                    @endphp
                                    @foreach ($user->role as $role)
                                        @if ($count++ < 2)
                                            {{ $role->role_name . ', ' }}
                                        @else
                                            {{ ' +' . $total - 2 }} more
                                        @break;
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                <div class="form-check form-switch">
                                    <input data-route="{{ route('users.status', ['id' => $user->id]) }}"
                                        class="form-check-input" type="checkbox" role="switch"
                                        id="userSwitch{{ $user->id }}" data-onstyle="danger" data-offstyle="info"
                                        data-toggle="toggle" data-on="Pending" data-off="Approved"
                                        {{ $user->is_active == 1 ? 'checked' : '' }}>
                                </div>
                            </td>
                            <td class="pt-0">
                                <div class="dropdown position-absolute">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown"><i class="ti ti-dots-vertical"></i></button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item"
                                            href="{{ route('users.edit', ['id' => $user->id]) }}"><i
                                                class="ti ti-pencil me-1"></i> Edit</a>
                                        <button data-route="{{ route('users.delete', ['id' => $user->id]) }}"
                                            class="btn text-danger" id="deleteUser{{ $user->id }}"
                                            type="button"><i class="ti ti-trash me-1"></i> Delete</button>
                                        <a href="#" data-route="{{ route('users.resetPassword') }}"
                                            data-email="{{ $user->email }}" data-id="{{ $user->id }}"
                                            class="dropdown-item add-new-role" data-bs-target="#addRoleModal"
                                            data-bs-toggle="modal" class="dropdown-item add-new-role"><i
                                                class="ti ti-key me-1"></i> Reset
                                            Password</a>
                                        <button data-route="{{ route('users.forceLogout', ['id' => $user->id]) }}"
                                            class="btn text-danger forced-logout-class" type="button"><i
                                                class='ti ti-logout me-2'></i> Force Logout</button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $users->links('pagination::bootstrap-5') }}
    </div>
</div>

<!--/ Toast message -->
<div class="bs-toast toast toast-ex animate__animated animate__tada my-2" role="alert" aria-live="assertive"
    aria-atomic="true" data-bs-delay="2000">
    <div class="toast-header">
        <i class="ti ti-bell ti-xs me-2 text-success"></i>
        <div class="me-auto fw-semibold">Success</div>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body">
        Hello, world! This is a toast message.
    </div>
</div>
<!--/ Toast message -->

@include('admin.users.resetPassword')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var links = document.querySelectorAll('a[data-bs-toggle="modal"]');
        links.forEach(function(link) {
            link.addEventListener('click', function(event) {
                event.preventDefault(); // Prevent the default action of the link
                var email = this.getAttribute('data-email');
                var id = this.getAttribute('data-id');
                var form = document.getElementById('resetPasswordForm');
                if (form) {
                    var emailInput = document.getElementById('email');
                    var UserId = document.getElementById('id');
                    if (emailInput && id) {
                        emailInput.value = email;
                        UserId.value = id;
                        console.log(UserId);
                        var route = this.getAttribute('data-route');
                        if (route) {
                            form.setAttribute('action',
                                route); // Set form action to the route URL
                        } else {
                            console.error('Data-route attribute not found on link.');
                        }
                    } else {
                        console.error('Email input not found.');
                    }
                } else {
                    console.error('Form not found.');
                }
            });
        });
    });
</script>
@endsection

@section('page-script')
<script src="{{ asset('assets/js/toast-message.js') }}"></script>
<script src="https://code.jquery.com/jquery-2.2.4.min.js" type="text/javascript"></script>

{{-- Script for switch --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        @foreach ($users as $user)
            const switch{{ $user->id }} = document.getElementById('userSwitch{{ $user->id }}');
            switch{{ $user->id }}.addEventListener('click', function() {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, change it!',
                    customClass: {
                        confirmButton: 'btn btn-primary me-3',
                        cancelButton: 'btn btn-label-secondary'
                    },
                    buttonsStyling: false
                }).then(function(result) {
                    if (result.isConfirmed) {
                        var status = $(switch{{ $user->id }}).prop('checked') == true ? 1 :
                            0;
                        var route = $(switch{{ $user->id }}).data('route');
                        $.ajax({
                            type: "GET",
                            dataType: "json",
                            url: route,
                            success: function(data) {
                                if (data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Status Updated!!',
                                        text: data.message,
                                        customClass: {
                                            confirmButton: 'btn btn-success'
                                        }
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        text: data.message,
                                        customClass: {
                                            confirmButton: 'btn btn-danger'
                                        }
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: 'An error occurred while processing your request. Please try again later.',
                                    customClass: {
                                        confirmButton: 'btn btn-danger'
                                    }
                                });
                            }
                        });
                    } else {
                        var currentState = $(switch{{ $user->id }}).prop('checked');
                        $(switch{{ $user->id }}).prop('checked', !currentState);
                    }
                });
            });
        @endforeach
    });
</script>
{{-- Script for switch --}}

{{-- Script for delete user --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        @foreach ($users as $user)
            const delete{{ $user->id }} = document.getElementById('deleteUser{{ $user->id }}');
            delete{{ $user->id }}.addEventListener('click', function() {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    customClass: {
                        confirmButton: 'btn btn-primary me-3',
                        cancelButton: 'btn btn-label-secondary'
                    },
                    buttonsStyling: false
                }).then(function(result) {
                    if (result.isConfirmed) {
                        var route = $(delete{{ $user->id }}).data('route');
                        $.ajax({
                            type: "GET",
                            dataType: "json",
                            url: route,
                            success: function(data) {
                                if (data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Status Updated!!',
                                        text: data.message,
                                        customClass: {
                                            confirmButton: 'btn btn-success'
                                        }
                                    }).then(function() {
                                        window.location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        text: data.message,
                                        customClass: {
                                            confirmButton: 'btn btn-danger'
                                        }
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: 'An error occurred while processing your request. Please try again later.',
                                    customClass: {
                                        confirmButton: 'btn btn-danger'
                                    }
                                });
                            }
                        });
                    }
                });
            });
        @endforeach
    });
</script>
{{-- Script for delete user --}}
@endsection
