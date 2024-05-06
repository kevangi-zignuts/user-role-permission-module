@extends('layouts/layoutMaster')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/animate-css/animate.css') }}" />
@endsection

@section('title', 'Annousement View')

@section('content')

    <div class="search-filter m-3 w-75 mx-auto">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="link">
                    <a href="{{ route('user.annousement.index') }}" class="btn btn-primary m-3 mt-0 mb-0"> Back</a>

                </div>
            </div>
            <div class="card-body">
                <div class="card message">
                    <div class="card-header message-title">Message</div>
                    <div class="card-body message-content">{{ $annousement->message }} </div>
                </div>
                <div class="d-flex">
                    <div class="card date m-3">
                        <div class="card-header card-date-lable">Date</div>
                        <div class="card-body card-date">{{ $annousement->date }} </div>
                    </div>
                    <div class="card time m-3">
                        <div class="card-header card-time-lable">Time</div>
                        <div class="card-body card-time">{{ $annousement->time }} </div>
                    </div>
                </div>
                <div class="card status">
                    <div class="card-header card-status-lable">Status</div>
                    <div class="card-body card-status">{{ $annousement->status }} </div>
                </div>
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
