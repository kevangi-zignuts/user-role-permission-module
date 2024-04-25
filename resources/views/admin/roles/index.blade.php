@extends('layouts/layoutMaster')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/animate-css/animate.css') }}" />
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

    <div class="search-filter m-3 w-75 mx-auto">
        <form action="{{ route('roles.index') }}" method="GET" class="d-flex">
            <div class="input-group m-2">
                <input type="text" class="form-control" placeholder="Search Role..." name="search"
                    value="{{ $search }}">
            </div>
            <div class="input-group w-px-500 m-2">
                <select name="filter" class="form-select" id="inputGroupSelect04"
                    aria-label="Example select with button addon" equired>
                    <option value="all" {{ $filter == 'all' ? 'selected' : '' }}>All Role</option>
                    <option value="active" {{ $filter == 'active' ? 'selected' : '' }}>Activated Role</option>
                    <option value="inactive" {{ $filter == 'inactive' ? 'selected' : '' }}>InActivated Role</option>
                </select>
            </div>
            <button class="btn btn-primary m-2 w-25" type="submit">Filter</button>
            <a href="{{ route('roles.index') }}" class="btn btn-secondary m-2 w-25"><i
                    class="fa-solid fa-xmark p-1 pt-0 pb-0"></i> Clear</a>
        </form>
    </div>
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <div class="link">
                <a href="{{ route('roles.create') }}" class="btn btn-primary m-3 mt-0 mb-0"><i
                        class="fa-solid fa-plus p-2 pt-0 pb-0"></i>Add
                    a
                    Role</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive text-nowrap p-2">

                <table class="table">
                    <thead style="background: linear-gradient(72.47deg, #7367f0 22.16%, rgba(115, 103, 240, 0.7) 76.47%);">
                        <tr>
                            <th scope="col" class="text-white">Name</th>
                            <th scope="col" class="text-white">Description</th>
                            <th class="text-white">Status</th>
                            <th scope="col" class="text-white">Action</th>
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
                                <td>
                                    <span class="truncated-address">
                                        {{ \Illuminate\Support\Str::limit($role->description, 45, ' ...') }}
                                    </span>
                                    <span class="full-address" style="display: none;">
                                        {{ $role->description }}
                                    </span>
                                </td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input data-route="{{ route('roles.status', ['id' => $role->id]) }}"
                                            class="form-check-input" type="checkbox" role="switch"
                                            id="roleSwitch{{ $role->id }}" data-onstyle="danger" data-offstyle="info"
                                            data-toggle="toggle" data-on="Pending" data-off="Approved"
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
                                            <button data-route="{{ route('roles.delete', ['id' => $role->id]) }}"
                                                id="deleteRole{{ $role->id }}" class="btn text-danger"
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

    <script>
        $(document).ready(function() {
            $(".truncated-address").click(function() {
                $(this).siblings(".full-address").toggle();
                $(this).toggle();
            });

            $(".full-address").click(function() {
                $(this).siblings(".truncated-address").toggle();
                $(this).toggle();
            });

            var fullAddress = $(".full-address");
            var addressText = fullAddress.text().trim();

            var chunks = [];
            while (addressText.length > 0) {
                chunks.push(addressText.substring(0, 45));
                addressText = addressText.substring(45);
            }

            var formattedAddress = chunks.join("<br>");
            fullAddress.html(formattedAddress);
        });
    </script>

@endsection


@section('page-script')
    <script src="{{ asset('assets/js/toast-message.js') }}"></script>
    <script src="https://code.jquery.com/jquery-2.2.4.min.js" type="text/javascript"></script>

    {{-- Script for switch --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            @foreach ($roles as $role)
                const switch{{ $role->id }} = document.getElementById('roleSwitch{{ $role->id }}');
                switch{{ $role->id }}.addEventListener('click', function() {
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
                            var status = $(switch{{ $role->id }}).prop('checked') == true ? 1 :
                                0;
                            var route = $(switch{{ $role->id }}).data('route');
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
                            var currentState = $(switch{{ $role->id }}).prop('checked');
                            $(switch{{ $role->id }}).prop('checked', !currentState);
                        }
                    });
                });
            @endforeach
        });
    </script>
    {{-- Script for switch --}}

    {{-- Script for delete role --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            @foreach ($roles as $role)
                const delete{{ $role->id }} = document.getElementById('deleteRole{{ $role->id }}');
                delete{{ $role->id }}.addEventListener('click', function() {
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
                            var route = $(delete{{ $role->id }}).data('route');
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
    {{-- Script for delete role --}}
@endsection
