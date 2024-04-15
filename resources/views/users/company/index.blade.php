@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Company Dashboard')

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
                    <a href="{{ route('company.create') }}" class="btn btn-primary"><i
                            class="fa-solid fa-plus p-2 pt-0 pb-0"></i>Add
                        a Company</a>
                @endif
                <a href="{{ route('company.index') }}" class="btn btn-secondary"><i
                        class="fa-solid fa-xmark p-1 pt-0 pb-0"></i> Clear</a>
            </div>
            <div class="search-container ">
                <form action="{{ route('company.index') }}" method="GET">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search Company..." name="search"
                            value="">
                        <button class="btn  btn-primary" type="submit"><i class="fas fa-search"></i></button>
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive text-nowrap p-2">

                <table class="table">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">Company Name</th>
                            <th scope="col">Owner Name</th>
                            <th scope="col">Industry</th>
                            @if ($access['edit'] || $access['delete'])
                                <th scope="col">Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @if ($companies->isEmpty())
                            <tr>
                                <td colspan="5" class="text-center text-danger h5">No data available</td>
                            </tr>
                        @endif
                        @foreach ($companies as $company)
                            <tr>
                                <td>{{ $company->company_name }}</td>
                                <td>{{ $company->owner_name }}</td>
                                <td>{{ $company->industry }}</td>
                                @if ($access['edit'] || $access['delete'])
                                    <td class="pt-0">
                                        <div class="dropdown" style="position: absolute">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                data-bs-toggle="dropdown"><i class="ti ti-dots-vertical"></i></button>
                                            <div class="dropdown-menu">
                                                @if ($access['edit'])
                                                    <a class="dropdown-item"
                                                        href="{{ route('company.edit', ['id' => $company->id]) }}"><i
                                                            class="ti ti-pencil me-1"></i>
                                                        Edit</a>
                                                @endif
                                                @if ($access['delete'])
                                                    <button data-id="{{ $company->id }}"
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
            {{ $companies->links('pagination::bootstrap-5') }}
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
                                url: "/user/company/delete/" + id,
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
