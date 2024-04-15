@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'User Dashboard')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/animate-css/animate.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
@endsection



@section('content')

    <div class="card">
        <div class="card-header d-flex justify-content-between m-5 mb-2">
            <div class="link">
                @if ($access['add'])
                    <a href="{{ route('people.create') }}" class="btn btn-primary"><i
                            class="fa-solid fa-plus p-2 pt-0 pb-0"></i>Add
                        a People</a>
                @endif
                <a href="{{ route('people.index') }}" class="btn btn-secondary"><i
                        class="fa-solid fa-xmark p-1 pt-0 pb-0"></i> Clear</a>
            </div>

            <div class="search-filter">
                <form action="{{ route('people.index') }}" method="GET" class="d-flex">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search People..." name="search"
                            value="">
                    </div>
                    <div class="input-group ">
                        <select name="filter" class="form-select" id="inputGroupSelect04"
                            aria-label="Example select with button addon" equired>
                            <option value="all" {{ $filter == 'all' ? 'selected' : '' }}>All People</option>
                            <option value="1" {{ $filter == '1' ? 'selected' : '' }}>Activated People</option>
                            <option value="0" {{ $filter == '0' ? 'selected' : '' }}>InActivated People</option>
                        </select>
                    </div>
                    <button class="btn btn-outline-primary" type="submit">Filter</button>
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive text-nowrap p-2">

                <table class="table">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Designation</th>
                            <th scope="col">Address</th>
                            <th>Status</th>
                            @if ($access['edit'] || $access['delete'])
                                <th scope="col">Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @if ($peoples->isEmpty())
                            <tr>
                                <td colspan="5" class="text-center text-danger h5">No data available</td>
                            </tr>
                        @endif
                        @foreach ($peoples as $people)
                            <tr>
                                <td>{{ $people->name }}</td>
                                <td>{{ $people->designation }}</td>
                                <td>{{ $people->address }}</td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input data-id="{{ $people->id }}" class="form-check-input toggle-class"
                                            type="checkbox" role="switch" id="switchCheckDefault" data-onstyle="danger"
                                            data-offstyle="info" data-toggle="toggle" data-on="Pending" data-off="Approved"
                                            {{ $people->is_active == 1 ? 'checked' : '' }}>
                                    </div>
                                </td>
                                @if ($access['edit'] || $access['delete'])
                                    <td class="pt-0">
                                        <div class="dropdown" style="position: absolute">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                data-bs-toggle="dropdown"><i class="ti ti-dots-vertical"></i></button>
                                            <div class="dropdown-menu">
                                                @if ($access['edit'])
                                                    <a class="dropdown-item"
                                                        href="{{ route('people.edit', ['id' => $people->id]) }}"><i
                                                            class="ti ti-pencil me-1"></i>
                                                        Edit</a>
                                                @endif
                                                @if ($access['delete'])
                                                    <button data-id="{{ $people->id }}"
                                                        class="btn text-danger delete-class" type="button"><i
                                                            class="ti ti-trash me-1"></i> Delete</button>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $peoples->links('pagination::bootstrap-5') }}
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
    <script src="{{ asset('assets/js/app-access-roles.js') }}"></script>
    <script src="{{ asset('assets/js/modal-add-role.js') }}"></script>


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
                        confirmButtonText: 'Yes, change it!',
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
                                url: "/user/people/status/" + id,
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
                                url: "/user/people/delete/" + id,
                                success: function(data) {
                                    if (data.success) {
                                        row.remove();
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Data Deleted!!',
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
