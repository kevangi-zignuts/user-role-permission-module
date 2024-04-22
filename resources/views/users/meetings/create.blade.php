@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'User Create')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/tagify/tagify.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/typeahead-js/typeahead.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/tagify/tagify.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/typeahead-js/typeahead.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bloodhound/bloodhound.js') }}"></script>
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/forms-selects.js') }}"></script>
    <script src="{{ asset('assets/js/forms-tagify.js') }}"></script>
    <script src="{{ asset('assets/js/forms-typeahead.js') }}"></script>
@endsection


@section('content')

    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner py-4 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <div class="app-brand justify-content-center mb-4 mt-2">
                            <span class="app-brand-text demo text-body fw-bold ms-1 ">
                                <p class="">Add Meeting</p>
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('meetings.store') }}" method="post" id="formAuthentication" class="mb-3">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Title *</label>
                                <input type="text" class="form-control" name="title" autofocus required>
                                @error('title')
                                    <div class="pt-2 text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="exampleFormControlTextarea1">Description</label>
                                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="description"></textarea>
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
                                    <a href="{{ route('meetings.index') }}" class="btn btn-label-secondary">Cancle</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /Register -->
            </div>
        </div>
    </div>

    <script>
        // Get today's date
        var today = new Date();
        // Format the date as YYYY-MM-DD
        var formattedDate = today.toISOString().substr(0, 10);
        // Set the minimum date for the date input field
        document.getElementById('date').min = formattedDate;

        document.getElementById('date').addEventListener('change', function() {
            var selectedDate = new Date(this.value);
            var currentDate = new Date();

            // If the selected date is today, set the time input to the current time
            if (selectedDate.toDateString() === currentDate.toDateString()) {
                var currentHour = currentDate.getHours().toString().padStart(2, '0');
                var currentMinute = currentDate.getMinutes().toString().padStart(2, '0');
                document.getElementById('time').value = currentHour + ':' + currentMinute;
                document.getElementById('time').min = currentHour + ':' + currentMinute;
            } else {
                // Otherwise, allow any time for future dates
                document.getElementById('time').value = '';
                document.getElementById('time').min = '';
            }
        });
    </script>

@endsection
