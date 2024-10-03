@extends('layouts.app', ['ptype' => 'child', 'purl' => request()->route()->getName(), 'id' => $theme->id ?? '', 'ptitle' => 'Theme Session', 'ctitle' => $theme->id ? 'Edit' : 'Add'])
@section('pagetitle', 'Theme Sessions')
@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container mt-3 px-3 rounded-2 ">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $theme->id ? 'Edit' : 'Add' }} Theme Session
                    </h3>
                </div>
                <form action="{{ $theme->id ? route('sessiontheme.update', $theme->id) : route('sessiontheme.store') }}"
                    autocomplete="off" method="post" enctype="multipart/form-data">
                    @csrf
                    {{ $theme->id ? method_field('PUT') : '' }}
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                @if (session('success'))
                                    <div class="alert alert-success alert-dismissible fade show">
                                        {{ session('success') }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif
                                @if (session('warning'))
                                    <div class="alert alert-warning alert-dismissible fade show">
                                        {{ session('warning') }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif
                            </div>
                            <div class="col-lg-6">
                                {{-- Name --}}
                                <div class="form-group row">
                                    <div class="col-lg-12">
                                        <label for="theme_name">Session Theme Name</label>
                                        <input type="text" class="form-control" name="theme_name" id="theme_name"
                                            value="{{ old('theme_name', $theme->theme_name) }}">
                                        @if ($errors->has('theme_name'))
                                            <span class="text-danger">
                                                {{ $errors->first('theme_name') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                {{-- Session Type --}}
                                <div class="form-group row">
                                    <div class="col-lg-12">
                                        <label for="theme_type">Session Type</label>
                                        <select class="form-control" name="theme_type" id="theme_type">
                                            <option value="">-- Theme Role --</option>
                                            <option value="fixed"
                                                {{ old('theme_type', $theme->theme_type) == 'fixed' ? 'selected' : '' }}>
                                                Fixed</option>
                                            <option value="parallel"
                                                {{ old('theme_type', $theme->theme_type) == 'parallel' ? 'selected' : '' }}>
                                                Parallel</option>
                                        </select>
                                        @if ($errors->has('theme_type'))
                                            <span class="text-danger">
                                                {{ $errors->first('theme_type') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                {{--  Convener --}}
                                <div class="form-group row">
                                    <div class="col-lg-12">
                                        <label for="convener">Convener</label>
                                        <select class="form-control" name="convener" id="convener">
                                            <option value=""> Select convener </option>
                                            @foreach ($conveners as $convener)
                                                <option
                                                    {{ old('convener', $theme->convener) == $convener ? 'selected' : '' }}
                                                    value="{{ $convener }}">{{ $convener }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('convener'))
                                            <span class="text-danger">
                                                {{ $errors->first('convener') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                {{-- Date --}}
                                <div class="form-group row">
                                    <div class="col-lg-12">
                                        <label for="date">Session Date</label>
                                        <input type="text" name="date" id="date" class="form-control date_time"
                                            data-toggle="datetimepicker" data-target="#date_time"
                                            value="{{ old('date', $theme->date) }}" />
                                        @if ($errors->has('date'))
                                            <span class="text-danger">
                                                {{ $errors->first('date') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                {{-- From Time --}}
                                <div class="form-group row">
                                    <div class="col-lg-12">
                                        <label for="from_time">From time</label>
                                        <input type="text" name="from_time" id="from_time" class="form-control time"
                                            data-toggle="datetimepicker" data-target="#time"
                                            value="{{ old('from_time', $theme->from_time) }}">
                                        @if ($errors->has('from_time'))
                                            <span class="text-danger">
                                                {{ $errors->first('from_time') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                {{-- From Time --}}
                                <div class="form-group row">
                                    <div class="col-lg-12">
                                        <label for="to_time">To time</label>
                                        <input type="text" name="to_time" id="to_time" class="form-control time"
                                            data-toggle="datetimepicker" data-target="#time"
                                            value="{{ old('to_time', $theme->to_time) }}">
                                        @if ($errors->has('to_time'))
                                            <span class="text-danger">
                                                {{ $errors->first('to_time') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                {{-- Status --}}
                                <div class="form-group row">
                                    <div class="col-lg-12">
                                        <label for="status">Status</label>
                                        <select class="form-control" name="status" id="status">
                                            @foreach (config('program-status') as $status)
                                                <option value="{{ $status }}"
                                                    {{ old('status', $theme->status) == $status ? 'selected' : '' }}>
                                                    {{ $status }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('status'))
                                            <span class="text-danger">
                                                {{ $errors->first('status') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                {{-- Hall Name --}}
                                <div class="form-group row">
                                    <div class="col-lg-12">
                                        <label for="hall_name">Hall Name</label>
                                        <select class="form-control" name="hall_name" id="hall_name">
                                            @foreach (config('session-halls') as $hall)
                                                <option value="{{ $hall }}"
                                                    {{ old('hall_name', $theme->hall_name) == $hall ? 'selected' : '' }}>
                                                    {{ $hall }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('hall_name'))
                                            <span class="text-danger">
                                                {{ $errors->first('hall_name') }}
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
                                <button class="btn btn-purple  mr-2">{{ $theme->id ? 'Update' : 'Save' }}</button>
                                <a href="{{ route('sessiontheme.index') }}" class="btn btn-secondary ">Cancel</a>
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