@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Modules')

@section('content')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/animate-css/animate.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave-phone.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
@endsection

@section('content')

    @if (session('success'))
        <div class="alert alert-success" id="alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger" role="alert-error">
            {{ session('error') }}
        </div>
    @endif
    @php
        $i = 1;
    @endphp
    <div class="card">
        <div class="card-header d-flex justify-content-between m-5 mb-2">
            <div class="search-container ">
                <form action="{{ route('modules.index') }}" method="GET">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search modules..." name="search"
                            value="">
                        <button class="btn  btn-primary" type="submit"><i class="fas fa-search"></i></button>
                    </div>
                </form>
            </div>
            <form action="{{ route('modules.index') }}" method="GET">
                <div class="input-group ">
                    <select name="filter" class="form-select" id="inputGroupSelect04"
                        aria-label="Example select with button addon" equired>
                        <option value="all" {{ $filter == 'all' ? 'selected' : '' }}>All Modules</option>
                        <option value="1" {{ $filter == '1' ? 'selected' : '' }}>Activated Modules</option>
                        <option value="0" {{ $filter == '0' ? 'selected' : '' }}>InActivated Modules</option>
                    </select>
                    <button class="btn btn-outline-primary" type="submit">Filter</button>
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive text-nowrap p-2">
                <table class="table">
                    <thead class="table-dark">
                        <tr>
                            <th></th>
                            <th scope="col">Name</th>
                            <th scope="col">Description</th>
                            <th>Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($modules->isEmpty())
                            <tr>
                                <td colspan="5" class="text-center text-danger h5">No data available</td>
                            </tr>
                        @endif
                        @foreach ($modules as $module)
                            <tr>
                                <td class="clickable" data-bs-toggle="collapse"
                                    data-bs-target="#collapseExample_{{ $module->code }}" aria-expended="false"
                                    aria-controls="collapseExample_{{ $module->code }}"><button
                                        class="btn btn-default btn-xs"><i class="fa-solid fa-caret-down"></i></button>
                                </td>
                                <td>{{ $module->module_name }}</td>
                                <td>{{ $module->description }}</td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input data-id="{{ $module->code }}" class="form-check-input toggle-class"
                                            type="checkbox" role="switch" id="switchCheckDefault" data-onstyle="danger"
                                            data-offstyle="info" data-toggle="toggle" data-on="Pending" data-off="Approved"
                                            {{ $module->is_active == 1 ? 'checked' : '' }}>
                                    </div>
                                </td>
                                <td><a href="{{ route('modules.edit', ['code' => $module->code]) }}"><i
                                            class="fa-solid fa-pen-to-square"></i></a></td>
                            </tr>
                            <tr class="collapse" id = "collapseExample_{{ $module->code }}">
                                <td colspan="5">
                                    <table class="table">
                                        <thead class="table-light">
                                            <tr class="info">
                                                <th scope="col">Submodule Name</th>
                                                <th scope="col">Description</th>
                                                <th>Status</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($module->submodules as $submodule)
                                                <tr>
                                                    <td>{{ $submodule->module_name }}</td>
                                                    <td>{{ $submodule->description }}</td>
                                                    <td>
                                                        <div class="form-check form-switch">
                                                            <input data-id="{{ $submodule->code }}"
                                                                class="form-check-input toggle-class" type="checkbox"
                                                                role="switch" id="switchCheckDefault" data-onstyle="danger"
                                                                data-offstyle="info" data-toggle="toggle" data-on="Pending"
                                                                data-off="Approved"
                                                                {{ $submodule->is_active == 1 ? 'checked' : '' }}>
                                                        </div>
                                                    </td>
                                                    <td><a
                                                            href="{{ route('modules.edit', ['code' => $submodule->code]) }}"><i
                                                                class="fa-solid fa-pen-to-square"></i></a></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            @php
                                $i++;
                            @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        {{ $modules->links('pagination::bootstrap-5') }}
    </div>
    </div>

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
    </div

@endsection

@section('page-script')
    <script src="{{ asset('assets/js/form-layouts.js') }}"></script>
    <script src="{{ asset('assets/js/extended-ui-sweetalert2.js') }}"></script>
    <script src="https://code.jquery.com/jquery-2.2.4.min.js" type="text/javascript"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            toggleSwitches = document.querySelectorAll('.toggle-class');
            toggleSwitches.forEach(function(toggleSwitch) {
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
                            var code = $(toggleSwitch).data('id');
                            $.ajax({
                                type: "GET",
                                dataType: "json",
                                url: "/admin/modules/status/" + code,
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
                    })
                });
            })
        })
    </script>


    <!-- Script to handle toast display -->
    <script>
        $(document).ready(function() {
            // Function to get URL parameter by name
            function getUrlParameter(name) {
                name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
                var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
                var results = regex.exec(location.search);
                return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
            };

            // Check if success parameter exists and is true
            var successParam = getUrlParameter('success');
            if (successParam == '1') {
                var messageParam = getUrlParameter('message');
                var toastAnimationExample = document.querySelector('.toast-ex');
                var selectedType = 'text-success';
                var selectedAnimation = 'animate__tada';
                toastAnimationExample.classList.add(selectedAnimation);
                toastAnimationExample.querySelector('.ti').classList.add(selectedType);
                var Message = document.querySelector('.toast-body');
                Message.innerText = messageParam;
                toastAnimation = new bootstrap.Toast(toastAnimationExample);
                toastAnimation.show();
            }
        });
    </script>
@endsection
