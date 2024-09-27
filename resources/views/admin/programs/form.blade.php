@extends('layouts.app', ['ptype' => 'child', 'purl' => request()->route()->getName(), 'id' => $program->id ?? '', 'ptitle' => 'Programs', 'ctitle' => $program->id ? 'Edit' : 'Add'])
@section('pagetitle', 'Programs')
@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container mt-3 px-3 rounded-2 row">
            <div class="card shadow-sm col-lg-12">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $program->id ? 'Edit' : 'Add' }} Programs
                    </h3>
                </div>
                <form action="{{ $program->id ? route('programs.update', $program->id) : route('programs.store') }}"
                    autocomplete="off" method="post" enctype="multipart/form-data">
                    @csrf
                    {{ $program->id ? method_field('PUT') : '' }}
                    <div class="card-body ">
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
                                        <label for="topic">Topic</label>
                                        <input type="text" class="form-control" name="topic" id="topic"
                                            value="{{ old('topic', $program->topic) }}">
                                        @if ($errors->has('topic'))
                                            <span class="text-danger">
                                                {{ $errors->first('topic') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                {{-- Speaker Details --}}
                                <div class="form-group row">
                                    <div class="col-lg-12">
                                        <label>Speaker Name</label>
                                        <select class="form-control select2bs4" style="width: 100%;" id="program_speaker_id"
                                            name="program_speaker_id">
                                            @isset($program_speakers)
                                                <option value="">All</option>
                                                @foreach ($program_speakers as $speaker)
                                                    <option
                                                        {{ old('program_speaker_id', $program->program_speaker_id) == $speaker->id ? 'selected' : '' }}
                                                        value="{{ $speaker->id }}"> {{ $speaker->name }}</option>
                                                @endforeach
                                            @endisset
                                        </select>
                                        @if ($errors->has('program_speaker_id'))
                                            <span class="text-danger">
                                                {{ $errors->first('program_speaker_id') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                {{-- Session Theme --}}
                                <div class="form-group row">
                                    <div class="col-lg-12">
                                        <label>Session Theme</label>
                                        <select class="form-control select2bs4" style="width: 100%;" id="session_theme_id"
                                            name="session_theme_id">
                                            @isset($session_themes)
                                                <option value="">All</option>
                                                @foreach ($session_themes as $theme)
                                                    <option
                                                        {{ old('session_theme_id', $program->session_theme_id) == $theme->id ? 'selected' : '' }}
                                                        value="{{ $theme->id }}"> {{ $theme->theme_name }} |
                                                        {{ \Carbon\Carbon::parse($theme->from_time)->format('h:m a') }} -
                                                        {{ \Carbon\Carbon::parse($theme->to_time)->format('h:m a') }}
                                                    </option>
                                                @endforeach
                                            @endisset
                                        </select>
                                        @if ($errors->has('session_theme_id'))
                                            <span class="text-danger">
                                                {{ $errors->first('session_theme_id') }}
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
                                            value="{{ old('date', $program->date) }}" />
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
                                            value="{{ old('from_time', $program->from_time) }}">
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
                                            value="{{ old('to_time', $program->to_time) }}">
                                        @if ($errors->has('to_time'))
                                            <span class="text-danger">
                                                {{ $errors->first('to_time') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                {{-- Status --}}
                                <div class="form-group row">
                                    <div class="col-lg-12">
                                        <label for="status">Status</label>
                                        <select class="form-control" name="status" id="status">
                                            @foreach (config('program-status') as $status)
                                                <option value="{{ $status }}"
                                                    {{ old('status', $program->status) == $status ? 'selected' : '' }}>
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
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-lg-12 text-right">
                                <button class="btn btn-purple  mr-2">{{ $program->id ? 'Update' : 'Save' }}</button>
                                <a href="{{ route('programs.index') }}" class="btn btn-secondary ">Cancel</a>
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
