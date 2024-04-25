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

@section('page-script')
    <script src="{{ asset('assets/js/app-access-roles.js') }}"></script>
    <script src="{{ asset('assets/js/modal-add-role.js') }}"></script>
    <script src="{{ asset('assets/js/toggle-sweet-alert.js') }}"></script>
    <script src="{{ asset('assets/js/toast-message.js') }}"></script>
    <script src="https://code.jquery.com/jquery-2.2.4.min.js" type="text/javascript"></script>
@endsection

@section('content')

    @if (session('error'))
        <div class="alert alert-danger" role="alert-error">
            {{ session('error') }}
        </div>
    @endif

    <div class="search-filter m-3 w-75 mx-auto">
        <form action="{{ route('people.index') }}" method="GET" class="d-flex">
            <div class="input-group m-2">
                <input type="text" class="form-control" placeholder="Search People..." name="search"
                    value="{{ $search }}">
            </div>
            <div class="input-group w-px-500 m-2">
                <select name="filter" class="form-select" id="inputGroupSelect04"
                    aria-label="Example select with button addon" equired>
                    <option value="all" {{ $filter == 'all' ? 'selected' : '' }}>All People</option>
                    <option value="active" {{ $filter == 'active' ? 'selected' : '' }}>Activated People</option>
                    <option value="inactive" {{ $filter == 'inactive' ? 'selected' : '' }}>InActivated People</option>
                </select>
            </div>
            <button class="btn btn-primary m-2 w-25" type="submit"> Filter</button>
            <a href="{{ route('people.index') }}" class="btn btn-secondary m-2 w-25"><i
                    class="fa-solid fa-xmark p-1 pt-0 pb-0"></i> Clear</a>
        </form>
    </div>
    <div class="card">
        <div class="card-header d-flex justify-content-between ">
            <div class="link">
                @if ($access['add'])
                    <a href="{{ route('people.create') }}" class="btn btn-primary m-3 mt-0 mb-0"><i
                            class="fa-solid fa-plus p-2 pt-0 pb-0"></i>Add
                        a People</a>
                @endif

            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive text-nowrap p-2">

                <table class="table table-hover">
                    <thead style="background: linear-gradient(72.47deg, #7367f0 22.16%, rgba(115, 103, 240, 0.7) 76.47%);">
                        <tr>
                            <th scope="col" class="text-white">Name</th>
                            <th scope="col" class="text-white">Designation</th>
                            <th scope="col" class="text-white">Address</th>
                            <th class="text-white">Status</th>
                            @if ($access['edit'] || $access['delete'])
                                <th scope="col" class="text-white">Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @if ($peoples->isEmpty())
                            <tr>
                                <td colspan="5" class="text-center text-danger h5">No data available</td>
                            </tr>
                        @endif
                        @foreach ($peoples as $index => $people)
                            <tr>
                                <td>{{ $people->name }}</td>
                                <td>{{ $people->designation }}</td>
                                <td>
                                    <span class="truncated-address">
                                        {{ \Illuminate\Support\Str::limit($people->address, 45, ' ...') }}
                                    </span>
                                    <span class="full-address" style="display: none;">
                                        {{ $people->address }}
                                    </span>
                                <td>
                                    <div class="form-check form-switch">
                                        <input data-route="{{ route('people.status', ['id' => $people->id]) }}"
                                            class="form-check-input toggle-class" type="checkbox" role="switch"
                                            id="switchCheckDefault" data-onstyle="danger" data-offstyle="info"
                                            data-toggle="toggle" data-on="Pending" data-off="Approved"
                                            {{ $people->is_active == 1 ? 'checked' : '' }}>
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
                                                        href="{{ route('people.edit', ['id' => $people->id]) }}"><i
                                                            class="ti ti-pencil me-1"></i>
                                                        Edit</a>
                                                @endif
                                                @if ($access['delete'])
                                                    <button
                                                        data-route="{{ route('people.delete', ['id' => $people->id]) }}"
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
            {{ $peoples->links('pagination::bootstrap-5') }}
        </div>
    </div>


    <!--/ Toast message -->
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
    <!--/ Toast message -->


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
