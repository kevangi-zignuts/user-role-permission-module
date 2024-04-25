@extends('layouts/layoutMaster')

@section('title', 'Edit Permission')

@section('content')

    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner py-4 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <div class="app-brand justify-content-center mb-4 mt-2">
                            <span class="app-brand-text demo text-body fw-bold ms-1">Edit Permission</span>
                        </div>
                        <form id="formAuthentication" class="mb-3"
                            action="{{ route('permissions.update', ['id' => $permission->id]) }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Permission Name *</label>
                                <input type="text" class="form-control" id="email" name="permission_name"
                                    value="{{ $permission->permission_name }}" autofocus>
                                @error('permission_name')
                                    <div class="text-danger pt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="exampleFormControlTextarea1">Description</label>
                                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="description">{{ $permission->description }}</textarea>
                            </div>

                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Module_name</th>
                                        <th>Add</th>
                                        <th>View</th>
                                        <th>Edit</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    @foreach ($modules as $module)
                                        @php
                                            $permissionModules = $permission->module ?? collect();
                                            $moduleData = $permissionModules->firstWhere('code', $module->code);
                                            $pivotData = $moduleData ? $moduleData->pivot : null;
                                        @endphp
                                        <tr>
                                            <td><strong>{{ $module->module_name }}</strong></td>
                                            <td>
                                                <input type="hidden" name="modules[{{ $module->code }}][add_access]"
                                                    value="0">
                                                <input class="form-check-input"
                                                    name="modules[{{ $module->code }}][add_access]" type="checkbox"
                                                    id="flexCheckChecked"
                                                    {{ optional($pivotData)->add_access == '1' ? 'checked' : '' }}
                                                    value="1">
                                            </td>
                                            <td>
                                                <input type="hidden" name="modules[{{ $module->code }}][view_access]"
                                                    value="0">
                                                <input class="form-check-input"
                                                    name="modules[{{ $module->code }}][view_access]" type="checkbox"
                                                    id="flexCheckChecked"
                                                    {{ optional($pivotData)->view_access == '1' ? 'checked' : '' }}
                                                    value="1">
                                            </td>
                                            <td>
                                                <input type="hidden" name="modules[{{ $module->code }}][edit_access]"
                                                    value="0">
                                                <input class="form-check-input"
                                                    name="modules[{{ $module->code }}][edit_access]" type="checkbox"
                                                    id="flexCheckChecked"
                                                    {{ optional($pivotData)->edit_access == '1' ? 'checked' : '' }}
                                                    value="1">
                                            </td>
                                            <td>
                                                <input type="hidden" name="modules[{{ $module->code }}][delete_access]"
                                                    value="0">
                                                <input class="form-check-input"
                                                    name="modules[{{ $module->code }}][delete_access]" type="checkbox"
                                                    id="flexCheckChecked"
                                                    {{ optional($pivotData)->delete_access == '1' ? 'checked' : '' }}
                                                    value="1">
                                            </td>

                                        </tr>
                                        @foreach ($module->submodules as $submodule)
                                            @if ($submodule->is_active == '1')
                                                @php
                                                    $permissionModules = $permission->module ?? collect();
                                                    $moduleData = $permissionModules->firstWhere(
                                                        'code',
                                                        $submodule->code,
                                                    );
                                                    $pivotData = $moduleData ? $moduleData->pivot : null;
                                                @endphp
                                                <tr class="submodule">
                                                    <td class="p-5 pb-0 pt-0">{{ $submodule->module_name }}</td>
                                                    <td>
                                                        <input type="hidden"
                                                            name="modules[{{ $submodule->code }}][add_access]"
                                                            value="0">
                                                        <input class="form-check-input"
                                                            name="modules[{{ $submodule->code }}][add_access]"
                                                            type="checkbox" id="flexCheckChecked"
                                                            {{ optional($pivotData)->add_access == '1' ? 'checked' : '' }}
                                                            value="1">
                                                    </td>
                                                    <td>
                                                        <input type="hidden"
                                                            name="modules[{{ $submodule->code }}][view_access]"
                                                            value="0">
                                                        <input class="form-check-input"
                                                            name="modules[{{ $submodule->code }}][view_access]"
                                                            type="checkbox" id="flexCheckChecked"
                                                            {{ optional($pivotData)->view_access == '1' ? 'checked' : '' }}
                                                            value="1">
                                                    </td>
                                                    <td>
                                                        <input type="hidden"
                                                            name="modules[{{ $submodule->code }}][edit_access]"
                                                            value="0">
                                                        <input class="form-check-input"
                                                            name="modules[{{ $submodule->code }}][edit_access]"
                                                            type="checkbox" id="flexCheckChecked"
                                                            {{ optional($pivotData)->edit_access == '1' ? 'checked' : '' }}
                                                            value="1">
                                                    </td>
                                                    <td>
                                                        <input type="hidden"
                                                            name="modules[{{ $submodule->code }}][delete_access]"
                                                            value="0">
                                                        <input class="form-check-input"
                                                            name="modules[{{ $submodule->code }}][delete_access]"
                                                            type="checkbox" id="flexCheckChecked"
                                                            {{ optional($pivotData)->delete_access == '1' ? 'checked' : '' }}
                                                            value="1">
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="row">
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary me-2">Update</button>
                                    <a href="{{ route('permissions.index') }}" class="btn btn-label-secondary">Cancle</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <script src="https://code.jquery.com/jquery-2.2.4.min.js" type="text/javascript"></script>
    <script>
        $(document).ready(function() {
            $('input[type="checkbox"]').change(function() {
                var $checkbox = $(this);
                var module = $checkbox.attr('name');
                var module_access = module.substring(module.lastIndexOf("[") + 1, module
                    .lastIndexOf("]"));
                var isSubmodule = $checkbox.closest('tr').hasClass(
                    'submodule'); // Check if it's a submodule

                if (isSubmodule) {
                    // Find the main module checkbox
                    var $mainModuleRow = $checkbox.closest('tr').prevAll('tr:not(.submodule)').first();

                    var nameAttribute = $mainModuleRow.find('input[type="checkbox"]').attr('name');

                    var moduleCode = nameAttribute.substring(nameAttribute.indexOf("[") + 1, nameAttribute
                        .indexOf("]"));

                    var mainModuleCheckbox = document.querySelector('input[name="modules[' +
                        moduleCode +
                        '][view_access]"][type="checkbox"]');

                    if (mainModuleCheckbox && !mainModuleCheckbox.checked) {
                        mainModuleCheckbox.checked = true;
                    }

                    if (module_access !== 'view_access') {
                        mainModuleCheckbox = document.querySelector('input[name="modules[' +
                            moduleCode +
                            '][' + module_access + ']"][type="checkbox"]');
                        if (mainModuleCheckbox && !mainModuleCheckbox.checked) {
                            mainModuleCheckbox.checked = true;
                        }
                    }
                }
            });
        });
    </script>
@endsection
