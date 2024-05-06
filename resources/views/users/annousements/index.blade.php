@extends('layouts/layoutMaster')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/animate-css/animate.css') }}" />
@endsection

@section('title', 'Annousements')

@section('content')

    <div class="search-filter m-3 w-75 mx-auto">
        <form action="{{ route('user.annousements.index') }}" method="GET" class="d-flex">
            <div class="input-group w-px-500 m-2">
                <select name="filter" class="form-select" required>
                    <option value="all" {{ $filter == 'all' ? 'selected' : '' }}>All Annouments</option>
                    <option value="past" {{ $filter == 'active' ? 'selected' : '' }}>Past Annouments</option>
                    <option value="upcoming" {{ $filter == 'inactive' ? 'selected' : '' }}>Upcoming Annouments</option>
                </select>
            </div>
            <button class="btn btn-primary m-2 w-25" type="submit">Filter</button>
            <a href="{{ route('user.annousement.index') }}" class="btn btn-secondary m-2 w-25"><i
                    class="fa-solid fa-xmark p-1 pt-0 pb-0"></i> Clear</a>
        </form>
    </div>
    <div class="card">
        <div class="card-header d-flex justify-content-between">
        </div>
        <div class="card-body">
            <div class="table-responsive text-nowrap p-2">

                <table class="table">
                    <thead class="bg-purple">
                        <tr>
                            <th scope="col" class="text-white">Message</th>
                            <th scope="col" class="text-white">Date</th>
                            <th scope="col" class="text-white">Time</th>
                            <th scope="col" class="text-white">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($annousments->isEmpty())
                            <tr>
                                <td colspan="4" class="text-center text-danger h5">No data available</td>
                            </tr>
                        @endif
                        @foreach ($annousments as $annousment)
                            <tr>
                                <td>{{ \Illuminate\Support\Str::limit($annousment->message, 45, ' ...') }}</td>
                                <td>{{ $annousment->date }} </td>
                                <td>{{ $annousment->time }} </td>
                                <td class="pt-0">
                                    <div class="dropdown position-absolute">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown"><i class="ti ti-dots-vertical"></i></button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item"
                                                href="{{ route('user.annousement.view', ['id' => $annousement->id]) }}"><i
                                                    class="ti ti-pencil me-1"></i> view</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $annousements->links('pagination::bootstrap-5') }}
        </div>
    </div>
    </div>

    <!--/ Toast message -->
    <div class="bs-toast toast toast-ex animate__animated animate__tada my-2" role="alert" aria-live="assertive"
        aria-atomic="true" data-bs-delay="2000">
        <div class="toast-header">
            <i class="ti ti-bell ti-xs me-2 text-success"></i>
            <div class="me-auto fw-semibold toast-title"></div>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body"></div>
    </div>
    <!--/ Toast message -->

@endsection

@section('page-script')
    <script src="https://code.jquery.com/jquery-2.2.4.min.js" type="text/javascript"></script>

    {{-- Script for delete annousment --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            @foreach ($annousments as $annousment)
                const delete{{ $annousment->id }} = document.getElementById(
                    'deleteAnnousement{{ $annousment->id }}');
                delete{{ $permission->id }}.addEventListener('click', function() {
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
                            var route = $(delete{{ $annousment->id }}).data('route');
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
    {{-- Script for delete annousment --}}

    {{-- Script for toast message --}}
    @if (session('success'))
        @php
            $successMessage = session('success');
            session()->forget('success');
        @endphp

        <script>
            var successMessage = {!! json_encode($successMessage) !!};
            $('.toast-body').text(successMessage);
            $('i.ti-bell').addClass('text-success');
            $('.toast-title').text('Success');
            new bootstrap.Toast($('.toast-ex')[0]).show();
        </script>
    @endif

    @if (session('error'))
        @php
            $errorMessage = session('error');
            session()->forget('error');
        @endphp

        <script>
            var errorMessage = {!! json_encode($errorMessage) !!};
            $('.toast-body').text(errorMessage);
            $('i.ti-bell').addClass('text-danger');
            $('.toast-title').text('Error');
            new bootstrap.Toast($('.toast-ex')[0]).show();
        </script>
    @endif
    {{-- Script for toast message --}}

@endsection
