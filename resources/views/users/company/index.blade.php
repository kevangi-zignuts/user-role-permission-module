@extends('layouts/layoutMaster')

@section('title', 'Company Dashboard')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/animate-css/animate.css') }}" />
@endsection
@section('content')

    <div class="search-container m-3 w-50 mx-auto">
        <form action="{{ route('company.index') }}" method="GET" class="d-flex">
            <div class="input-group m-2">
                <input type="text" class="form-control" placeholder="Search Company..." name="search"
                    value="{{ $search }}">
            </div>
            <button class="btn  btn-primary m-2 w-25" type="submit"><i class="fas fa-search p-1 pt-0 pb-0"></i>
                Search</button>
            <a href="{{ route('company.index') }}" class="btn btn-secondary m-2 w-25"><i
                    class="fa-solid fa-xmark p-1 pt-0 pb-0"></i> Clear</a>
        </form>
    </div>
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <div class="link">
                @if ($access['add'])
                    <a href="{{ route('company.create') }}" class="btn btn-primary m-2 mb-0 mt-0"><i
                            class="fa-solid fa-plus p-2 pt-0 pb-0"></i>Add
                        a Company</a>
                @endif

            </div>

        </div>
        <div class="card-body">
            <div class="table-responsive text-nowrap p-2">

                <table class="table">
                    <thead style="background: linear-gradient(72.47deg, #7367f0 22.16%, rgba(115, 103, 240, 0.7) 76.47%);">
                        <tr>
                            <th scope="col" class="text-white">Company Name</th>
                            <th scope="col" class="text-white">Owner Name</th>
                            <th scope="col" class="text-white">Industry</th>
                            @if ($access['edit'] || $access['delete'])
                                <th scope="col" class="text-white">Action</th>
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
                                                    <button
                                                        data-route="{{ route('company.delete', ['id' => $company->id]) }}"
                                                        class="btn text-danger" id="deleteCompany{{ $company->id }}"
                                                        type="button"><i class="ti ti-trash me-1"></i> Delete</button>
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






@endsection


@section('page-script')
    <script src="{{ asset('assets/js/toast-message.js') }}"></script>
    <script src="https://code.jquery.com/jquery-2.2.4.min.js" type="text/javascript"></script>

    {{-- Script for delete company details --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            @foreach ($companies as $company)
                const delete{{ $company->id }} = document.getElementById('deleteCompany{{ $company->id }}');
                delete{{ $company->id }}.addEventListener('click', function() {
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
                            var route = $(delete{{ $company->id }}).data('route');
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
    {{-- Script for delete company details --}}



@endsection
