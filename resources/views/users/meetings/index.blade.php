@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'User Dashboard')

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
                    <a href="{{ route('meetings.create') }}" class="btn btn-primary"><i
                            class="fa-solid fa-plus p-2 pt-0 pb-0"></i>Add
                        a Meeting</a>
                @endif
                <a href="{{ route('meetings.index') }}" class="btn btn-secondary"><i
                        class="fa-solid fa-xmark p-1 pt-0 pb-0"></i> Clear</a>
            </div>
            <div class="search-filter">
                <form action="{{ route('meetings.index') }}" method="GET" class="d-flex">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search Meeting..." name="search"
                            value="">
                    </div>
                    <div class="input-group ">
                        <select name="filter" class="form-select" id="inputGroupSelect04"
                            aria-label="Example select with button addon" equired>
                            <option value="all" {{ $filter == 'all' ? 'selected' : '' }}>All Meetings</option>
                            <option value="1" {{ $filter == '1' ? 'selected' : '' }}>Activated Meetings</option>
                            <option value="0" {{ $filter == '0' ? 'selected' : '' }}>InActivated Meetings</option>
                        </select>
                    </div>
                    <button class="btn btn-outline-primary" type="submit">Filter</button>
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive text-nowrap p-2">

                <table class="table">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">Title</th>
                            <th scope="col">Description</th>
                            <th scope="col">Date</th>
                            <th scope="col">Time</th>
                            <th>Status</th>
                            @if ($access['edit'] || $access['delete'])
                                <th scope="col">Action</th>
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
                                <td>{{ $meeting->description }}</td>
                                <td class="meetingDate" data-id="{{ $meeting->id }}">{{ $meeting->date }}</td>
                                <td class="meetingTime" data-id="{{ $meeting->id }}">{{ $meeting->time }}</td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input data-id="{{ $meeting->id }}" class="form-check-input toggle-class"
                                            type="checkbox" role="switch" id="switchCheckDefault" data-onstyle="danger"
                                            data-offstyle="info" data-toggle="toggle" data-on="Pending" data-off="Approved"
                                            {{ $meeting->is_active == 1 ? 'checked' : '' }}>
                                    </div>
                                </td>
                                @if ($access['edit'] || $access['delete'])
                                    <td class="pt-0">
                                        <div class="dropdown" style="position: absolute">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                data-bs-toggle="dropdown"><i class="ti ti-dots-vertical"></i></button>
                                            <div class="dropdown-menu">
                                                @if ($access['edit'])
                                                    <a class="dropdown-item"
                                                        href="{{ route('meetings.edit', ['id' => $meeting->id]) }}"><i
                                                            class="ti ti-pencil me-1"></i>
                                                        Edit</a>
                                                @endif
                                                @if ($access['delete'])
                                                    <button data-route="{{ route('meetings.delete', ['id' => $meeting->id]) }}"
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
            {{ $meetings->links('pagination::bootstrap-5') }}
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



@endsection

@section('page-script')
    <script src="{{ asset('assets/js/app-access-roles.js') }}"></script>
    <script src="{{ asset('assets/js/modal-add-role.js') }}"></script>
    <script src="{{ asset('assets/js/toggle-sweet-alert.js') }}"></script>
    <script src="{{ asset('assets/js/toast-message.js') }}"></script>

    <script src="https://code.jquery.com/jquery-2.2.4.min.js" type="text/javascript"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            toggleSwitches = document.querySelectorAll('.toggle-class');
            toggleSwitches.forEach(function(toggleSwitch) {
                var meetingId = $(toggleSwitch).data('id');
                var isChecked = $(toggleSwitch).prop('checked');
                var dateString = $('.meetingDate[data-id="' + meetingId + '"]').text();
                var timeString = $('.meetingTime[data-id="' + meetingId + '"]').text();
                var dateParts = dateString.split('-');
                var timeParts = timeString.split(':');
                var day = parseInt(dateParts[0], 10);
                var month = parseInt(dateParts[1], 10) - 1;
                var year = parseInt(dateParts[2], 10);
                var hour = parseInt(timeParts[0], 10);
                var minute = parseInt(timeParts[1], 10);
                var second = parseInt(timeParts[2], 10);
                var meetingDate = new Date(year, month, day, hour, minute, second);
                // console.log(meetingDate);
                var currentDate = new Date();
                if (meetingDate < currentDate && isChecked) {
                    $(toggleSwitch).prop('checked', false);
                }
                console.log(meetingDate);
                // var meetingDate = new Date($('.meetingDate[data-id="' + meetingId + '"]').text());
                toggleSwitch.addEventListener('click', function() {
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
                            var status = $(toggleSwitch).prop('checked') == true ? 1 : 0;
                            var id = $(toggleSwitch).data('id');
                            $.ajax({
                                type: "GET",
                                dataType: "json",
                                url: "/user/meetings/status/" + id,
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
                                    }
                                }
                            });
                        } else {
                            var currentState = $(toggleSwitch).prop('checked');
                            $(toggleSwitch).prop('checked', !currentState);
                        }
                    });
                });
            });
        });
    </script>




@endsection
