@extends('layouts.app', ['ptype' => 'child', 'purl' => request()->route()->getName(), 'id' => $user->id ?? '', 'ptitle' => 'User', 'ctitle' => $user->id ? 'Edit' : 'Add'])
@section('pagetitle', 'Users')
@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container  mt-3 px-3  rounded-2 ">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $user->id ? 'Edit' : 'Add' }} Member
                    </h3>
                </div>
                <form action="{{ $user->id ? route('user.update', $user->id) : route('user.store') }}"
                    method="post" enctype="multipart/form-data">
                    @csrf
                    {{ $user->id ? method_field('PUT') : '' }}
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-lg-6">
                                {{-- Name --}}
                                <div class="form-group row">
                                    <div class="col-lg-12">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control" name="name" id="name"
                                            value="{{ old('name', $user->name) }}">
                                        @if ($errors->has('name'))
                                            <span class="text-danger">
                                                {{ $errors->first('name') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                 {{-- email --}}
                                 <div class="form-group row">
                                    <div class="col-lg-12">
                                        <label for="email">Email</label>
                                        <input type="text" class="form-control" name="email" id="email"
                                            value="{{ old('email', $user->email) }}">
                                        @if ($errors->has('email'))
                                            <span class="text-danger">
                                                {{ $errors->first('email') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                {{-- User Role --}}
                                <div class="form-group row">
                                    <div class="col-lg-12">
                                        <label for="role">User Role</label>
                                        <select  class="form-control" name="role" id="role">
                                            <option value="">-- Select Role --</option>
                                            @foreach ($roles as $role)
                                                @if ($role->id != 1)
                                                    <option {{ (isset($user_role) && $user_role == $role->name) ? 'selected' : '' }} value="{{ $role->name }}">{{ $role->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @if ($errors->has('role'))
                                            <span class="text-danger">
                                                {{ $errors->first('role') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                {{-- Password --}}
                                <div class="form-group row">
                                    <div class="col-lg-12">
                                        <label for="password">Password</label>
                                        <input type="text" class="form-control" name="password" id="password"
                                            >
                                        @if ($errors->has('password'))
                                            <span class="text-danger">
                                                {{ $errors->first('password') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-lg-12 text-right">
                                <button class="btn btn-primary  mr-2">{{ $user->id ? 'Update' : 'Save' }}</button>
                                <a href="{{ route('user.index') }}" class="btn btn-secondary ">Cancel</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection
@push('scripts')
@endpush
