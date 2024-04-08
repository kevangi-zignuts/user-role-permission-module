@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')


@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/animate-css/animate.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/toastr/toastr.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/toastr/toastr.js') }}"></script>
@endsection


@section('title', 'Role')

@section('content')

    @if (session('success'))
        <div class="alert alert-success" id="alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger" role="alert-error">
            {{ session('message') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between m-5 mb-2">
            <a href="{{ route('roles.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus p-2 pt-0 pb-0"></i>Add
                a
                Role</a>
            <div class="search-container ">
                <form action="{{ route('roles.index') }}" method="GET">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search Role..." name="search"
                            value="">
                        <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                    </div>
                </form>
            </div>
            <form action="{{ route('roles.index') }}" method="GET">
                <div class="input-group ">
                    <select name="filter" class="form-select" id="inputGroupSelect04"
                        aria-label="Example select with button addon" equired>
                        <option value="all" {{ $filter == 'all' ? 'selected' : '' }}>All Role</option>
                        <option value="1" {{ $filter == '1' ? 'selected' : '' }}>Activated Role</option>
                        <option value="0" {{ $filter == '0' ? 'selected' : '' }}>InActivated Role</option>
                    </select>
                    <button class="btn btn-outline-primary" type="submit">Filter</button>
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive text-nowrap p-2">

                <table class="table">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Description</th>
                            <th>Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($roles->isEmpty())
                            <tr>
                                <td colspan="5" class="text-center text-danger h5">No data available</td>
                            </tr>
                        @endif
                        @foreach ($roles as $role)
                            <tr>
                                <td>{{ $role->role_name }}</td>
                                <td>{{ $role->description }}</td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input data-id="{{ $role->id }}" class="form-check-input toggle-class"
                                            type="checkbox" role="switch" id="switchCheckDefault" data-onstyle="danger"
                                            data-offstyle="info" data-toggle="toggle" data-on="Pending" data-off="Approved"
                                            {{ $role->is_active == 1 ? 'checked' : '' }}>
                                    </div>
                                </td>
                                <td class="pt-0">
                                    <div class="dropdown" style="position: absolute">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown"><i class="ti ti-dots-vertical"></i></button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item"
                                                href="{{ route('roles.edit', ['id' => $role->id]) }}"><i
                                                    class="ti ti-pencil me-1"></i> Edit</a>
                                            <button data-id="{{ $role->id }}" class="btn text-danger delete-class"
                                                type="button"><i class="ti ti-trash me-1"></i> Delete</button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $roles->links('pagination::bootstrap-5') }}
        </div>
    </div>

    <!--/ Toast message -->
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
    <!--/ Toast message -->


@endsection

@section('page-script')
    <script src="{{ asset('assets/js/ui-toasts.js') }}"></script>
    <script src="{{ asset('assets/js/extended-ui-sweetalert2.js') }}"></script>
    <script src="https://code.jquery.com/jquery-2.2.4.min.js" type="text/javascript"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            toggleSwitches = document.querySelectorAll('.toggle-class');
            toggleSwitches.forEach(function(toggleSwitch) {
                toggleSwitch.addEventListener('click', function() {
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
                            var status = $(toggleSwitch).prop('checked') == true ? 1 : 0;
                            var id = $(toggleSwitch).data('id');
                            $.ajax({
                                type: "GET",
                                dataType: "json",
                                url: "/admin/roles/status/" + id,
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
                                    }
                                }
                            });
                        } else {
                            var currentState = $(toggleSwitch).prop('checked');
                            $(toggleSwitch).prop('checked', !currentState);
                        }
                    });
                });
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            deleteButtons = document.querySelectorAll('.delete-class');
            deleteButtons.forEach(function(deleteButton) {
                deleteButton.addEventListener('click', function() {
                    var row = this.closest('tr');
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
                            var id = $(deleteButton).data('id');
                            $.ajax({
                                type: "GET",
                                dataType: "json",
                                url: "/admin/roles/delete/" + id,
                                success: function(data) {
                                    if (data.success) {
                                        row.remove();
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Status Updated!!',
                                            text: data.message,
                                            customClass: {
                                                confirmButton: 'btn btn-success'
                                            }
                                        });
                                    }
                                }
                            });
                        }
                    });
                })
            });
        });
    </script>

    <!-- Script to handle toast display -->
    <script>
        $(document).ready(function() {
            // Function to get URL parameter by name
            function getUrlParameter(name) {
                name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
                var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
                var results = regex.exec(location.search);
                return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
            };

            // Check if success parameter exists and is true
            var successParam = getUrlParameter('success');
            if (successParam == '1') {
                var messageParam = getUrlParameter('message');
                var toastAnimationExample = document.querySelector('.toast-ex');
                var selectedType = 'text-success';
                var selectedAnimation = 'animate__tada';
                toastAnimationExample.classList.add(selectedAnimation);
                toastAnimationExample.querySelector('.ti').classList.add(selectedType);
                var Message = document.querySelector('.toast-body');
                Message.innerText = messageParam;
                toastAnimation = new bootstrap.Toast(toastAnimationExample);
                toastAnimation.show();
            }
        });
    </script>



@endsection
