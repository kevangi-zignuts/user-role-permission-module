@extends('layouts/layoutMaster')

@section('title', 'Announcement Create')

@section('content')

    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner py-4 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <div class="app-brand justify-content-center mb-4 mt-2">
                            <span class="app-brand-text demo text-body fw-bold ms-1 ">
                                <p class="">Create Announcement</p>
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('announcements.store') }}" method="post" id="formAuthentication"
                            class="mb-3">
                            @csrf
                            <div class="form-group mb-3">
                                <label class="form-label">Message *</label>
                                <textarea class="form-control" rows="3" name="message" required></textarea>
                                @error('message')
                                    <div class="pt-2 text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Date *</label>
                                    <input type="date" class="form-control" name="date" id="date" autofocus
                                        required>
                                    @error('date')
                                        <div class="pt-2 text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Time *</label>
                                    <input type="time" class="form-control" name="time" id="time" autofocus
                                        required>
                                    @error('time')
                                        <div class="text-danger pt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary me-2">Add</button>
                                    <a href="{{ route('announcements.index') }}" class="btn btn-label-secondary">Cancle</a>
                                </div>
                            </div>
                        </form>
                    </div>
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
    {{-- Script for toast message --}}
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
