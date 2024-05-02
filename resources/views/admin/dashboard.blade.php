@extends('layouts/layoutMaster')

@section('title', 'Admin Dashboard')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
@endsection

@section('content')

    <div class="row mb-5">
        <div class="col-lg-3 col-sm-6 mb-4 w-50">
            <div class="card">
                <div class="card-body pb-0">
                    <div class="card-icon">
                        <span class="badge bg-label-success rounded-pill p-2">
                            <i class='ti ti-credit-card ti-sm'></i>
                        </span>
                    </div>
                    <h5 class="card-title mb-0 mt-2">Module Count :- {{ $module_count }}</h5>
                </div>
                <div id="revenueGenerated"></div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 mb-4 w-50">
            <div class="card">
                <div class="card-body pb-0">
                    <div class="card-icon">
                        <span class="badge bg-label-warning rounded-pill p-2">
                            <i class='ti ti-package ti-sm'></i>
                        </span>
                    </div>
                    <h5 class="card-title mb-0 mt-2">Permission Count :- {{ $permission_count }}</h5>
                </div>
                <div id="orderReceived"></div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 mb-4 w-50">
            <div class="card">
                <div class="card-body pb-0">
                    <div class="card-icon">
                        <span class="badge bg-label-danger rounded-pill p-2">
                            <i class='ti ti-shopping-cart ti-sm'></i>
                        </span>
                    </div>
                    <h5 class="card-title mb-0 mt-2">Role Count :- {{ $role_count }}</h5>
                </div>
                <div id="quarterlySales"></div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 mb-4 w-50">
            <div class="card">
                <div class="card-body pb-0">
                    <div class="card-icon">
                        <span class="badge bg-label-primary rounded-pill p-2">
                            <i class='ti ti-users ti-sm'></i>
                        </span>
                    </div>
                    <h5 class="card-title mb-0 mt-2">Users Count :- {{ $user_count }}</h5>
                </div>
                <div id="subscriberGained"></div>
            </div>
        </div>
    </div>

    <!--/ Toast message -->
    <div class="bs-toast toast toast-ex animate__animated animate__tada my-2" role="alert" aria-live="assertive"
        aria-atomic="true" data-bs-delay="2000">
        <div class="toast-header">
            <i class="ti ti-bell ti-xs me-2 "></i>
            <div class="me-auto fw-semibold toast-title"></div>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
        </div>
    </div>
    <!--/ Toast message -->
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/cards-statistics.js') }}"></script>
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
