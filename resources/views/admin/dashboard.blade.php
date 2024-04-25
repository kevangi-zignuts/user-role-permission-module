@extends('layouts/layoutMaster')

@section('title', 'Admin Dashboard')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/swiper/swiper.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}" />
@endsection

@section('page-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/cards-advance.css') }}">
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/swiper/swiper.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/cards-statistics.js') }}"></script>
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

@endsection
