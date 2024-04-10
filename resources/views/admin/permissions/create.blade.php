@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Create Permission')

@section('content')

    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner py-4 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <div class="app-brand justify-content-center mb-4 mt-2">
                            <span class="app-brand-text demo text-body fw-bold ms-1">Create Permission</span>
                        </div>
                        <form id="formAuthentication" class="mb-3" action="{{ route('permissions.store') }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label>Permission Name *</label>
                                <input type="text" class="form-control" name="permission_name" autofocus required>
                                <div class="valid-feedback"> Looks good! </div>
                                @error('permission_name')
                                    <div class="text-danger pt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="exampleFormControlTextarea1">Description</label>
                                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="description"></textarea>
                            </div>

                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Module Name</th>
                                        <th>Add</th>
                                        <th>View</th>
                                        <th>Edit</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    @foreach ($modules as $module)
                                        <tr>
                                            <td><strong>{{ $module->module_name }}</strong></td>
                                            <td>
                                                <input type="hidden" name="modules[{{ $module->code }}][add_access]"
                                                    value="0">
                                                <input class="form-check-input"
                                                    name="modules[{{ $module->code }}][add_access]" type="checkbox"
                                                    value="1">
                                            </td>
                                            <td>
                                                <input type="hidden" name="modules[{{ $module->code }}][view_access]"
                                                    value="0">
                                                <input class="form-check-input"
                                                    name="modules[{{ $module->code }}][view_access]" type="checkbox"
                                                    value="1">
                                            </td>
                                            <td>
                                                <input type="hidden" name="modules[{{ $module->code }}][edit_access]"
                                                    value="0">
                                                <input class="form-check-input"
                                                    name="modules[{{ $module->code }}][edit_access]" type="checkbox"
                                                    value="1">
                                            </td>
                                            <td>
                                                <input type="hidden" name="modules[{{ $module->code }}][delete_access]"
                                                    value="0">
                                                <input class="form-check-input"
                                                    name="modules[{{ $module->code }}][delete_access]" type="checkbox"
                                                    value="1">
                                            </td>
                                        </tr>
                                        @foreach ($module->submodules as $submodule)
                                            @if ($submodule->is_active == '1')
                                                <tr>
                                                    <td class="p-5 pb-0 pt-0">{{ $submodule->module_name }}</td>
                                                    <td>
                                                        <input type="hidden"
                                                            name="modules[{{ $submodule->code }}][add_access]"
                                                            value="0">
                                                        <input class="form-check-input"
                                                            name="modules[{{ $submodule->code }}][add_access]"
                                                            type="checkbox" value="1">
                                                    </td>
                                                    <td>
                                                        <input type="hidden"
                                                            name="modules[{{ $submodule->code }}][view_access]"
                                                            value="0">
                                                        <input class="form-check-input"
                                                            name="modules[{{ $submodule->code }}][view_access]"
                                                            type="checkbox" value="1">
                                                    </td>
                                                    <td>
                                                        <input type="hidden"
                                                            name="modules[{{ $submodule->code }}][edit_access]"
                                                            value="0">
                                                        <input class="form-check-input"
                                                            name="modules[{{ $submodule->code }}][edit_access]"
                                                            type="checkbox" value="1">
                                                    </td>
                                                    <td>
                                                        <input type="hidden"
                                                            name="modules[{{ $submodule->code }}][delete_access]"
                                                            value="0">
                                                        <input class="form-check-input"
                                                            name="modules[{{ $submodule->code }}][delete_access]"
                                                            type="checkbox" value="1">
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="row">
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary me-2">Create</button>
                                    <button type="button" class="btn btn-label-secondary"><a
                                            href="{{ route('permissions.index') }}">Cancle</a></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /Register -->
            </div>
        </div>
    </div>

@endsection
