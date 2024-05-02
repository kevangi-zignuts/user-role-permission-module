@extends('layouts/layoutMaster')

@section('title', 'Role Create')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/forms-selects.js') }}"></script>
@endsection


@section('content')

    {{-- @if (Session::has('errors'))
        <div class="alert alert-danger">
            <ul>
                @foreach (Session::get('errors')->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
                {{ Session::forget('errors') }}
            </ul>
        </div>
    @endif --}}
    {{-- @if ($errors->any())
        <div class="alert alert-danger" role="alert-error">
            @foreach ($errors->all() as $error)
                {{ $error }}
            @endforeach
        </div>
    @endif --}}

    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner py-4 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <div class="app-brand justify-content-center mb-4 mt-2">
                            <span class="app-brand-text demo text-body fw-bold ms-1">Create Role</span>
                        </div>
                        <form action="{{ route('roles.store') }}" method="post" class="mb-3">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Role Name *</label>
                                <input type="text" class="form-control" name="role_name" autofocus>
                            </div>
                            @error('role_name')
                                <div class="alert alert-danger" role="alert-error">
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="form-group mb-3">
                                <label>Description</label>
                                <textarea class="form-control" rows="3" name="description"></textarea>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Select Permissions :-</label>
                                <div class="select2-primary">
                                    <select class="select2 form-select" name="permissions[]" multiple>
                                        @foreach ($permissions as $permission)
                                            <option value="{{ $permission->id }}">{{ $permission->permission_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary me-2">Create</button>
                                    <a href="{{ route('roles.index') }}" class="btn btn-label-secondary">Cancle</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
