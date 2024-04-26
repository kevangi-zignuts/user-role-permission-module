@extends('layouts/layoutMaster')

@section('title', 'Modules')

@section('content')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/animate-css/animate.css') }}" />
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

    <div class="search-filter m-3 w-75 mx-auto">
        <form action="{{ route('modules.index') }}" method="GET" class="d-flex">
            <div class="input-group m-2">
                <input type="text" class="form-control" placeholder="Search User..." name="search"
                    value="{{ $search }}">
            </div>
            <div class="input-group w-px-500 m-2">
                <select name="filter" class="form-select" id="filterSelect" required>
                    <option value="all" {{ $filter == 'all' ? 'selected' : '' }}>All Modules</option>
                    <option value="active" {{ $filter == 'active' ? 'selected' : '' }}>Activated Modules</option>
                    <option value="inactive" {{ $filter == 'inactive' ? 'selected' : '' }}>InActivated Modules</option>
                </select>
            </div>
            <button class="btn btn-primary m-2 w-25" type="submit">Filter</button>
            <a href="{{ route('modules.index') }}" class="btn btn-secondary m-2 w-25"><i
                    class="fa-solid fa-xmark p-1 pt-0 pb-0"></i> Clear</a>
        </form>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive text-nowrap p-2">
                <table class="table">
                    <thead class="bg-purple">
                        <tr>
                            <th></th>
                            <th scope="col" class="text-white">Name</th>
                            <th scope="col" class="text-white">Description</th>
                            <th scope="col" class="text-white">Status</th>
                            <th scope="col" class="text-white">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($modules->isEmpty() || $modules->where('parent_code', null)->isEmpty())
                            <tr>
                                <td colspan="5" class="text-center text-danger h5">No data available</td>
                            </tr>
                        @endif
                        @foreach ($modules as $module)
                            @if ($module->parent_code === null)
                                <tr>
                                    <td class="clickable" data-bs-toggle="collapse"
                                        data-bs-target="#collapse_{{ $module->code }}" aria-expended="false"
                                        aria-controls="collapse_{{ $module->code }}"><button
                                            class="btn btn-default btn-xs"><i class="fa-solid fa-caret-down"></i></button>
                                    </td>
                                    <td>{{ $module->module_name }}</td>
                                    <td>
                                        <span class="truncated-address">
                                            {{ \Illuminate\Support\Str::limit($module->description, 45, ' ...') }}
                                        </span>
                                        <span class="full-address" style="display: none;">
                                            {{ $module->description }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input data-route="{{ route('modules.status', ['code' => $module->code]) }}"
                                                class="form-check-input" type="checkbox" role="switch"
                                                id="moduleSwitch{{ $module->code }}" data-onstyle="danger"
                                                data-offstyle="info" data-toggle="toggle" data-on="Pending"
                                                data-off="Approved" {{ $module->is_active == 1 ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    <td><a href="{{ route('modules.edit', ['code' => $module->code]) }}"><i
                                                class="fa-solid fa-pen-to-square"></i></a></td>
                                </tr>
                                <tr class="collapse" id = "collapse_{{ $module->code }}">
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
                                                    @if (in_array($submodule, $modules->all()))
                                                        <tr>
                                                            <td>{{ $submodule->module_name }}</td>
                                                            <td>
                                                                <span data-bs-toggle="tooltip" data-bs-placement="top"
                                                                    title="{{ $submodule->description }}"
                                                                    data-bs-custom-class="tooltip-primary">
                                                                    {{ \Illuminate\Support\Str::limit($submodule->description, 45, ' ...') }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <div class="form-check form-switch">
                                                                    <input
                                                                        data-route="{{ route('modules.status', ['code' => $submodule->code]) }}"
                                                                        class="form-check-input "
                                                                        id="moduleSwitch{{ $submodule->code }}"
                                                                        type="checkbox" role="switch" data-onstyle="danger"
                                                                        data-offstyle="info" data-toggle="toggle"
                                                                        data-on="Pending" data-off="Approved"
                                                                        {{ $submodule->is_active == 1 ? 'checked' : '' }}>
                                                                </div>
                                                            </td>
                                                            <td><a
                                                                    href="{{ route('modules.edit', ['code' => $submodule->code]) }}"><i
                                                                        class="fa-solid fa-pen-to-square"></i></a></td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        {{-- {{ $modules->links('pagination::bootstrap-5') }} --}}
    </div>
    </div>

    <div class="bs-toast toast toast-ex animate__animated animate__tada my-2" role="alert" aria-live="assertive"
        aria-atomic="true" data-bs-delay="2000">
        <div class="toast-header">
            <i class="ti ti-bell ti-xs me-2 text-success"></i>
            <div class="me-auto fw-semibold">Success</div>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            Hello, world! This is a toast message.
        </div>
    </div>

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

@endsection


@section('page-script')
    <script src="{{ asset('assets/js/toast-message.js') }}"></script>
    <script src="https://code.jquery.com/jquery-2.2.4.min.js" type="text/javascript"></script>

    {{-- Script for switch --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            @foreach ($modules as $module)
                const switch{{ $module->code }} = document.getElementById('moduleSwitch{{ $module->code }}');
                switch{{ $module->code }}.addEventListener('click', function() {
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
                            var status = $(switch{{ $module->code }}).prop('checked') == true ? 1 :
                                0;
                            var route = $(switch{{ $module->code }}).data('route');
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
                        } else {
                            var currentState = $(switch{{ $module->code }}).prop('checked');
                            $(switch{{ $module->code }}).prop('checked', !currentState);
                        }
                    });
                });
            @endforeach
        });
    </script>


< @endsection
