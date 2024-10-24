@extends('layouts.app', ['ptype' => 'child', 'purl' => request()->route()->getName(), 'id' => $speaker->id ?? '', 'ptitle' => 'Program Speaker', 'ctitle' => $speaker->id ? 'Edit' : 'Add'])
@section('pagetitle', 'Program Speaker')
@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container mt-3 px-3 rounded-2 ">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $speaker->id ? 'Edit' : 'Add' }} Theme Session
                    </h3>
                </div>
                <form
                    action="{{ $speaker->id ? route('programSpeakers.update', $speaker->id) : route('programSpeakers.store') }}"
                    method="post" enctype="multipart/form-data">
                    @csrf
                    {{ $speaker->id ? method_field('PUT') : '' }}
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-lg-6">
                                {{-- Name --}}
                                <div class="form-group row">
                                    <div class="col-lg-12">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control" name="name" id="name"
                                            value="{{ old('name', $speaker->name) }}">
                                        @if ($errors->has('name'))
                                            <span class="text-danger">
                                                {{ $errors->first('name') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                {{-- Speaker Image --}}
                                <div class="form-group row">
                                    <div class="col-lg-12">
                                        <label for="bio">Profile Photo</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="speaker_image"
                                                    name="speaker_image">
                                                <label class="custom-file-label" for="speaker_image">Choose
                                                    file</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                {{-- Bio --}}
                                <div class="form-group row">
                                    <div class="col-lg-12">
                                        <label for="bio">BIO</label>
                                        <textarea type="text" class="form-control" name="bio" id="bio" value="{{ old('bio', $speaker->bio) }}">{{ old('bio', $speaker->bio) }}</textarea>
                                        @if ($errors->has('bio'))
                                            <span class="text-danger">
                                                {{ $errors->first('bio') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            {{-- English Progam Copy --}}
                            <div class="col-lg-12">
                                <h6 class="font-weight-bold my-3 bg-purple px-2 py-2">ENGLISH</h6>
                            </div>
                            <div class="col-lg-6">
                                {{-- English Name --}}
                                <div class="form-group row">
                                    <div class="col-lg-12">
                                        <label for="english_name">Name</label>
                                        <input type="text" class="form-control" name="english_name" id="english_name"
                                            value="{{ old('english_name', $speaker->english_name) }}">
                                        @if ($errors->has('english_name'))
                                            <span class="text-danger">
                                                {{ $errors->first('english_name') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                {{-- English Bio --}}
                                <div class="form-group row">
                                    <div class="col-lg-12">
                                        <label for="english_bio">Bio</label>
                                        <textarea type="text" class="form-control" name="english_bio" id="english_bio"
                                            value="{{ old('english_bio', $speaker->english_bio) }}">{{ old('english_bio', $speaker->english_bio) }}</textarea>
                                    </div>
                                </div>
                            </div>
                            {{-- Malyalam Progam Copy --}}
                            <div class="col-lg-12">
                                <h6 class="font-weight-bold my-3 bg-purple px-2 py-2">Malyalam</h6>
                            </div>
                            <div class="col-lg-6">
                                {{-- Malyalam Name --}}
                                <div class="form-group row">
                                    <div class="col-lg-12">
                                        <label for="malyalam_name">Name</label>
                                        <input type="text" class="form-control" name="malyalam_name" id="malyalam_name"
                                            value="{{ old('malyalam_name', $speaker->malyalam_name) }}">
                                        @if ($errors->has('malyalam_name'))
                                            <span class="text-danger">
                                                {{ $errors->first('malyalam_name') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                {{-- Malyalam Bio --}}
                                <div class="form-group row">
                                    <div class="col-lg-12">
                                        <label for="malyalam_bio">Bio</label>
                                        <textarea type="text" class="form-control" name="malyalam_bio" id="malyalam_bio"
                                            value="{{ old('malyalam_bio', $speaker->malyalam_bio) }}">{{ old('malyalam_bio', $speaker->malyalam_bio) }}</textarea>
                                    </div>
                                </div>
                            </div>
                            {{-- Bengali Progam Copy --}}
                            <div class="col-lg-12">
                                <h6 class="font-weight-bold my-3 bg-purple px-2 py-2">Bengali</h6>
                            </div>
                            <div class="col-lg-6">
                                {{-- Bengali Name --}}
                                <div class="form-group row">
                                    <div class="col-lg-12">
                                        <label for="bengali_name">Name</label>
                                        <input type="text" class="form-control" name="bengali_name" id="bengali_name"
                                            value="{{ old('bengali_name', $speaker->bengali_name) }}">
                                        @if ($errors->has('bengali_name'))
                                            <span class="text-danger">
                                                {{ $errors->first('bengali_name') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                {{-- Bengali Bio --}}
                                <div class="form-group row">
                                    <div class="col-lg-12">
                                        <label for="bengali_bio">Bio</label>
                                        <textarea type="text" class="form-control" name="bengali_bio" id="bengali_bio"
                                            value="{{ old('bengali_bio', $speaker->bengali_bio) }}">{{ old('bengali_bio', $speaker->bengali_bio) }}</textarea>
                                    </div>
                                </div>
                            </div>
                            {{-- Tmail Progam Copy --}}
                            <div class="col-lg-12">
                                <h6 class="font-weight-bold my-3 bg-purple px-2 py-2">Tmail</h6>
                            </div>
                            <div class="col-lg-6">
                                {{-- Tmail Name --}}
                                <div class="form-group row">
                                    <div class="col-lg-12">
                                        <label for="tamil_name">Name</label>
                                        <input type="text" class="form-control" name="tamil_name" id="tamil_name"
                                            value="{{ old('tamil_name', $speaker->tamil_name) }}">
                                        @if ($errors->has('tamil_name'))
                                            <span class="text-danger">
                                                {{ $errors->first('tamil_name') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                {{-- Tamil Bio --}}
                                <div class="form-group row">
                                    <div class="col-lg-12">
                                        <label for="tamil_bio">Bio</label>
                                        <textarea type="text" class="form-control" name="tamil_bio" id="tamil_bio"
                                            value="{{ old('tamil_bio', $speaker->tamil_bio) }}">{{ old('tamil_bio', $speaker->tamil_bio) }}</textarea>
                                    </div>
                                </div>
                            </div>
                            {{-- Kannada Progam Copy --}}
                            <div class="col-lg-12">
                                <h6 class="font-weight-bold my-3 bg-purple px-2 py-2">Kannada</h6>
                            </div>
                            <div class="col-lg-6">
                                {{-- Kannada Name --}}
                                <div class="form-group row">
                                    <div class="col-lg-12">
                                        <label for="kannada_name">Name</label>
                                        <input type="text" class="form-control" name="kannada_name" id="kannada_name"
                                            value="{{ old('kannada_name', $speaker->kannada_name) }}">
                                        @if ($errors->has('kannada_name'))
                                            <span class="text-danger">
                                                {{ $errors->first('kannada_name') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                {{-- Kannada Bio --}}
                                <div class="form-group row">
                                    <div class="col-lg-12">
                                        <label for="kannada_bio">Bio</label>
                                        <textarea type="text" class="form-control" name="kannada_bio" id="kannada_bio"
                                            value="{{ old('kannada_bio', $speaker->kannada_bio) }}">{{ old('kannada_bio', $speaker->kannada_bio) }}</textarea>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-lg-12 text-right">
                                <button class="btn btn-purple  mr-2">{{ $speaker->id ? 'Update' : 'Save' }}</button>
                                <a href="{{ route('programSpeakers.index') }}" class="btn btn-secondary ">Cancel</a>
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
