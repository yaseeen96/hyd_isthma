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
                                {{-- Topic --}}
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
                                                        {{ \Carbon\Carbon::parse($theme->from_time)->format('h:i A') }} -
                                                        {{ \Carbon\Carbon::parse($theme->to_time)->format('h:i A') }}
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
                            {{-- <div class="col-lg-6"> --}}
                            {{-- Date --}}
                            {{-- <div class="form-group row">
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
                                </div> --}}
                            {{-- </div> --}}
                            <div class="col-lg-6">
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
                            {{-- <div class="col-lg-6">
                                <label for="bio mt-2"> Program Copy</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="program_copy"
                                            name="program_copy">
                                        <label class="custom-file-label" for="program_copy">Choose
                                            file</label>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="col-lg-12 mt-5">
                                <h5 class="font-weight-bold">Translations Details</h5>
                            </div>
                            {{-- English Progam Copy --}}
                            <div class="col-lg-12">
                                <h6 class="font-weight-bold my-3 bg-purple px-2 py-2">ENGLISH</h6>
                            </div>
                            <div class="col-lg-6">
                                {{-- English Topic --}}
                                <div class="form-group row">
                                    <div class="col-lg-12">
                                        <label for="english_topic">English Topic</label>
                                        <input type="text" class="form-control" name="english_topic" id="english_topic"
                                            value="{{ old('english_topic', $program->english_topic) }}">
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="col-lg-6">
                                <label for="bio mt-2">English Program Copy</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" type="file" class="custom-file-input"
                                            id="english_program_copy" name="english_program_copy">
                                        <label class="custom-file-label" for="english_program_copy">Choose
                                            file</label>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="col-lg-6">
                                <label for="bio mt-2">Translation Audio File</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="english_translation"
                                            name="english_translation">
                                        <label class="custom-file-label" for="english_translation">Choose
                                            file</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 mt-2">
                                <label for="bio mt-2">Transcript</label>
                                <textarea type="english_transcript" class="form-control" rows="5" id="english_transcript"
                                    name="english_transcript">{{ old('english_transcript', $program->english_transcript) }}</textarea>
                            </div>

                            {{-- Malyalam Progam Copy --}}
                            <div class="col-lg-12">
                                <h6 class="font-weight-bold my-3 bg-purple px-2 py-2">MALYALAM</h6>
                            </div>
                            <div class="col-lg-6">
                                {{-- Malyalam Topic --}}
                                <div class="form-group row">
                                    <div class="col-lg-12">
                                        <label for="malyalam_topic">Malyalam Topic</label>
                                        <input type="text" class="form-control" name="malyalam_topic"
                                            id="malyalam_topic"
                                            value="{{ old('malyalam_topic', $program->malyalam_topic) }}">
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="col-lg-6">
                                <label for="bio mt-2">Malyalam Program Copy</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="malyalam_program_copy"
                                            name="malyalam_program_copy">
                                        <label class="custom-file-label" for="malyalam_program_copy">Choose
                                            file</label>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="col-lg-6">
                                <label for="bio mt-2">Translation Audio File</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="malyalam_translation"
                                            name="malyalam_translation">
                                        <label class="custom-file-label" for="malyalam_translation">Choose
                                            file</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 mt-2">
                                <label for="bio mt-2">Transcript</label>
                                <textarea type="malyalam_transcript" class="form-control" rows="5" id="malyalam_transcript"
                                    name="malyalam_transcript">{{ old('malyalam_transcript', $program->malyalam_transcript) }}</textarea>
                            </div>

                            {{-- Bengali Progam Copy --}}
                            <div class="col-lg-12">
                                <h6 class="font-weight-bold my-3 bg-purple px-2 py-2">BENGALI</h6>
                            </div>
                            <div class="col-lg-6">
                                {{-- Bengali Topic --}}
                                <div class="form-group row">
                                    <div class="col-lg-12">
                                        <label for="bengali_topic">Bengali Topic</label>
                                        <input type="text" class="form-control" name="bengali_topic"
                                            id="bengali_topic"
                                            value="{{ old('bengali_topic', $program->bengali_topic) }}">
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="col-lg-6">
                                <label for="bio mt-2">Bengali Program Copy</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="bengali_program_copy"
                                            name="bengali_program_copy">
                                        <label class="custom-file-label" for="bengali_program_copy">Choose
                                            file</label>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="col-lg-6">
                                <label for="bio mt-2">Translation Audio File</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="bengali_translation"
                                            name="bengali_translation">
                                        <label class="custom-file-label" for="bengali_translation">Choose
                                            file</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 mt-2">
                                <label for="bio mt-2">Transcript</label>
                                <textarea type="bengali_transcript" class="form-control" rows="5" id="bengali_transcript"
                                    name="bengali_transcript">{{ old('bengali_transcript', $program->bengali_transcript) }}</textarea>
                            </div>

                            {{-- Tamil Progam Copy --}}
                            <div class="col-lg-12">
                                <h6 class="font-weight-bold my-3 bg-purple px-2 py-2">TAMIL</h6>
                            </div>
                            <div class="col-lg-6">
                                {{-- Tamil Topic --}}
                                <div class="form-group row">
                                    <div class="col-lg-12">
                                        <label for="tamil_topic">Tmail Topic</label>
                                        <input type="text" class="form-control" name="tamil_topic" id="tamil_topic"
                                            value="{{ old('tamil_topic', $program->tamil_topic) }}">
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="col-lg-6">
                                <label for="bio mt-2">Tamil Program Copy</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="tamil_program_copy"
                                            name="tamil_program_copy">
                                        <label class="custom-file-label" for="tamil_program_copy">Choose
                                            file</label>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="col-lg-6">
                                <label for="bio mt-2">Translation Audio File</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="tamil_translation"
                                            name="tamil_translation">
                                        <label class="custom-file-label" for="tamil_translation">Choose
                                            file</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 mt-2">
                                <label for="bio mt-2">Transcript</label>
                                <textarea type="tamil_transcript" class="form-control" rows="5" id="tamil_transcript"
                                    name="tamil_transcript">{{ old('tamil_transcript', $program->tamil_transcript) }}</textarea>
                            </div>

                            {{-- Kannada Progam Copy --}}
                            <div class="col-lg-12">
                                <h6 class="font-weight-bold my-3 bg-purple px-2 py-2">KANNADA</h6>
                            </div>
                            <div class="col-lg-6">
                                {{-- Kannada Topic --}}
                                <div class="form-group row">
                                    <div class="col-lg-12">
                                        <label for="kannada_topic">Kannada Topic</label>
                                        <input type="text" class="form-control" name="kannada_topic"
                                            id="kannada_topic"
                                            value="{{ old('kannada_topic', $program->kannada_topic) }}">
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="col-lg-6">
                                <label for="bio mt-2">Kannada Program Copy</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="kannada_program_copy"
                                            name="kannada_program_copy">
                                        <label class="custom-file-label" for="kannada_program_copy">Choose
                                            file</label>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="col-lg-6">
                                <label for="bio mt-2">Translation Audio File</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="kannada_translation"
                                            name="kannada_translation">
                                        <label class="custom-file-label" for="kannada_translation">Choose
                                            file</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 mt-2">
                                <label for="bio mt-2">Transcript</label>
                                <textarea type="kannada_transcript" class="form-control" rows="5" id="kannada_transcript"
                                    name="kannada_transcript">{{ old('kannada_transcript', $program->kannada_transcript) }}</textarea>
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
