@extends('layouts/layoutMaster')

@section('title', 'Meetings')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/animate-css/animate.css') }}" />
@endsection

@section('content')

    <div class="search-filter m-3 w-75 mx-auto">
        <form action="{{ route('meetings.index') }}" method="GET" class="d-flex">
            <div class="input-group m-2">
                <input type="text" class="form-control" placeholder="Search Meeting..." name="search"
                    value="{{ $search }}">
            </div>
            <div class="input-group w-px-500 m-2">
                <select name="filter" class="form-select" required>
                    <option value="all" {{ $filter == 'all' ? 'selected' : '' }}>All Meetings</option>
                    <option value="active" {{ $filter == 'active' ? 'selected' : '' }}>Activated Meetings</option>
                    <option value="inactive" {{ $filter == 'inactive' ? 'selected' : '' }}>InActivated Meetings</option>
                </select>
            </div>
            <button class="btn btn-primary m-2 w-25" type="submit">Filter</button>
            <a href="{{ route('meetings.index') }}" class="btn btn-secondary m-2 w-25"><i
                    class="fa-solid fa-xmark p-1 pt-0 pb-0"></i> Clear</a>
        </form>
    </div>
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <div class="link">
                @if ($access['add'])
                    <a href="{{ route('meetings.create') }}" class="btn btn-primary m-2 mb-0 mt-0"><i
                            class="fa-solid fa-plus p-2 pt-0 pb-0"></i>Add
                        a Meeting</a>
                @endif

            </div>

        </div>
        <div class="card-body">
            <div class="table-responsive text-nowrap p-2">

                <table class="table">
                    <thead class="bg-purple">
                        <tr>
                            <th scope="col" class="text-white">Title</th>
                            <th scope="col" class="text-white">Description</th>
                            <th scope="col" class="text-white">Date</th>
                            <th scope="col" class="text-white">Time</th>
                            <th scope="col" class="text-white">Status</th>
                            @if ($access['edit'] || $access['delete'])
                                <th scope="col" class="text-white">Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @if ($meetings->isEmpty())
                            <tr>
                                <td colspan="5" class="text-center text-danger h5">No data available</td>
                            </tr>
                        @endif
                        @foreach ($meetings as $meeting)
                            <tr>
                                <td>{{ $meeting->title }}</td>
                                <td>
                                    <span class="truncated-address">
                                        {{ \Illuminate\Support\Str::limit($meeting->description, 45, ' ...') }}
                                    </span>
                                    <span class="full-address" style="display: none;">
                                        {{ $meeting->description }}
                                    </span>
                                </td>
                                <td class="meetingDate" data-id="{{ $meeting->id }}">{{ $meeting->date }}</td>
                                <td class="meetingTime" data-id="{{ $meeting->id }}">{{ $meeting->time }}</td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input data-id="{{ $meeting->id }}"
                                            data-route="{{ route('meetings.status', ['id' => $meeting->id]) }}"
                                            class="form-check-input toggle-class" type="checkbox" role="switch"
                                            id="meetingSwitch{{ $meeting->id }}" data-onstyle="danger"
                                            data-offstyle="info" data-toggle="toggle" data-on="Pending" data-off="Approved"
                                            {{ $meeting->is_active == 1 ? 'checked' : '' }}>
                                    </div>
                                </td>
                                @if ($access['edit'] || $access['delete'])
                                    <td class="pt-0">
                                        <div class="dropdown position-absolute">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                data-bs-toggle="dropdown"><i class="ti ti-dots-vertical"></i></button>
                                            <div class="dropdown-menu">
                                                @if ($access['edit'])
                                                    <a class="dropdown-item edit-meeting-btn"
                                                        data-meeting-date="{{ $meeting->date }}"
                                                        data-meeting-time="{{ $meeting->time }}"
                                                        href="{{ route('meetings.edit', ['id' => $meeting->id]) }}"><i
                                                            class="ti ti-pencil me-1"></i>
                                                        Edit</a>
                                                @endif
                                                @if ($access['delete'])
                                                    <button
                                                        data-route="{{ route('meetings.delete', ['id' => $meeting->id]) }}"
                                                        class="btn text-danger delete-class"
                                                        id="deleteMeeting{{ $meeting->id }}" type="button"><i
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
            {{ $meetings->links('pagination::bootstrap-5') }}
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

    <!--/ Error Toast message -->
    <div class="bs-toast toast toast-ex animate__animated animate__tada my-2 error-message" role="alert"
        aria-live="assertive" aria-atomic="true" data-bs-delay="2000">
        <div class="toast-header">
            <i class="ti ti-bell ti-xs me-2 text-danger"></i>
            <div class="me-auto fw-semibold">Error</div>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body toast-body-error">
            Hello, world! This is a toast message.
        </div>
    </div>
    <!--/ Error Toast message -->


    <script>
        var dateElements = document.querySelectorAll('.meetingDate');
        dateElements.forEach(function(element) {
            var dateString = element.textContent;

            var date = new Date(dateString);

            var formattedDate = ("0" + date.getDate()).slice(-2) + "-" + ("0" + (date.getMonth() + 1)).slice(-2) +
                "-" + date.getFullYear();

            element.textContent = formattedDate;
        });
    </script>

    <script>
        $(document).ready(function() {
            $(".truncated-address").click(function() {
                $(this).siblings(".full-address").toggle();
                $(this).toggle();
            });

            $(".full-address").click(function() {
                $(this).siblings(".truncated-address").toggle();
                $(this).toggle();
            });

            var fullAddress = $(".full-address");
            var addressText = fullAddress.text().trim();

            var chunks = [];
            while (addressText.length > 0) {
                chunks.push(addressText.substring(0, 45));
                addressText = addressText.substring(45);
            }

            var formattedAddress = chunks.join("<br>");
            fullAddress.html(formattedAddress);
        });
    </script>

    <script>
        $(document).ready(function() {
            var currentTime = new Date();

            $(".edit-meeting-btn").each(function() {
                var meetingDate = $(this).data("meeting-date");
                var meetingTime = $(this).data("meeting-time");

                var meetingDatetime = new Date(meetingDate + " " + meetingTime);

                if (meetingDatetime <= currentTime) {
                    $(this).on("click", function(event) {
                        event.preventDefault();
                        $('.error-message')
                            .find('.toast-body-error')
                            .text('Meeting is Over, Cannot be added');
                        var toastAnimation = new bootstrap.Toast($('.error-message')[0]);
                        toastAnimation.show();
                    });
                }
            });
        });
    </script>

@endsection

@section('page-script')
    <script src="{{ asset('assets/js/toast-message.js') }}"></script>
    <script src="https://code.jquery.com/jquery-2.2.4.min.js" type="text/javascript"></script>

    {{-- Script for switch --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            @foreach ($meetings as $meeting)
                const switch{{ $meeting->id }} = document.getElementById('meetingSwitch{{ $meeting->id }}');
                switch{{ $meeting->id }}.addEventListener('click', function() {
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
                            var status = $(switch{{ $meeting->id }}).prop('checked') == true ? 1 :
                                0;
                            var route = $(switch{{ $meeting->id }}).data('route');
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
                                        });
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error!',
                                            text: data.message,
                                            customClass: {
                                                confirmButton: 'btn btn-danger'
                                            }
                                        }).then(function() {
                                            window.location.reload();
                                        });
                                    }
                                }
                            });
                        } else {
                            var currentState = $(switch{{ $meeting->id }}).prop('checked');
                            $(switch{{ $meeting->id }}).prop('checked', !currentState);
                        }
                    });
                });
            @endforeach
        });
    </script>
    {{-- Script for switch --}}

    {{-- Script for delete meeting --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            @foreach ($meetings as $meeting)
                const delete{{ $meeting->id }} = document.getElementById('deleteMeeting{{ $meeting->id }}');
                delete{{ $meeting->id }}.addEventListener('click', function() {
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
                            var route = $(delete{{ $meeting->id }}).data('route');
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
    {{-- Script for delete meeting --}}

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
