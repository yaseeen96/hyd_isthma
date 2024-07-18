@extends('layouts.app', ['ptype' => 'child', 'purl' => request()->route()->getName(), 'id' => $role->id ?? '', 'ptitle' => 'Permissions', 'ctitle' => $role->id ? 'Edit' : 'Add'])
@section('pagetitle', 'Dashboard')
@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container  mt-3 px-3  rounded-2 ">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $role->id ? 'Edit' : 'Add' }} Permission
                    </h3>
                </div>
                <form action="{{ $role->id ? route('permissions.update', $role->id) : route('permissions.store') }}"
                    method="post" enctype="multipart/form-data">
                    @csrf
                    {{ $role->id ? method_field('PUT') : '' }}
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group row">
                                    <label class="form-label" for="role">Role</label>
                                    <input type="text" class="form-control" readonly value="{{ old('name', $role->name)}}" required true/>
                                    @if ($errors->has('name'))
                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group row">
                                    <div class="table-responsive">
                                        <table class=" nowrap table table-bordered table-hover  dtr-inline collapsed text-center">
                                            <thead>
                                                <tr>
                                                    <th>Module</th>
                                                    <th>Create</th>
                                                    <th>Edit</th>
                                                    <th>Delete</th>
                                                    <th>View</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach (config('permissionsgroups') as $group)
                                                    <tr>
                                                        <td>{{ $group }}</td>
                                                        @foreach ($permissions as $permission)
                                                            @if (str_contains($permission, $group))
                                                             @if (str_contains($group, "Report"))
                                                                 <td></td>
                                                                 <td></td>
                                                                 <td></td>
                                                             @endif
                                                            <td>
                                                                <input type="checkbox" name="permissions[]"  {{ in_array($permission->name, $role_permissions) ? 'checked="checked"' : '' }} value="{{ $permission->name }}">
                                                            </td>
                                                            @endif
                                                         @endforeach
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>     
                                    </div>               
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <button class="btn btn-primary  mr-2">{{ $role->id ? 'Update' : 'Save' }}</button>
                                <a href="{{ route('permissions.index') }}" class="btn btn-secondary ">Cancel</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection