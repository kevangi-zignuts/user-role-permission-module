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
            @if ($access['add'])
                <a href="{{ route('company.create') }}" class="btn btn-primary"><i
                        class="fa-solid fa-plus p-2 pt-0 pb-0"></i>Add
                    a Company</a>
            @endif
            <div class="search-container ">
                <form action="{{ route('people.index') }}" method="GET">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search Role..." name="search"
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
                                                        href="{{ route('people.edit', ['id' => $company->id]) }}"><i
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
