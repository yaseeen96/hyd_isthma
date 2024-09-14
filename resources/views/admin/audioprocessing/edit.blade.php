@extends('layouts.app', ['ptype' => 'child', 'purl' => request()->route()->getName(), 'id' => $audioProcessing->id ?? '', 'ptitle' => 'Audio Processing', 'ctitle' => $audioProcessing->id ? 'Edit' : 'Add'])
@section('pagetitle', 'Dashboard')
@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container  mt-3 px-3  rounded-2 ">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $audioProcessing->id ? 'Edit' : 'Add' }} Audio Processing
                    </h3>
                </div>
                <form
                    action="{{ $audioProcessing->id ? route('audioProcessing.update', $audioProcessing->id) : route('members.store') }}"
                    method="post" enctype="multipart/form-data">
                    @csrf
                    {{ $audioProcessing->id ? method_field('PUT') : '' }}
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <label class="h3 mb-2">English Transscript</label>
                                <textarea name="english_transcript" id="english_transcript" rows="50" class="form-control mb-2">{{ $english_transcript ?? '' }}</textarea>
                            </div>
                            <div class="col-lg-12">
                                <label class="h3 mb-2">Telugu Transscript</label>
                                <textarea name="telugu_transcript" id="telugu_transcript" rows="50" class="form-control mb-2">{{ $telugu_transcript ?? '' }}</textarea>
                            </div>
                            <div class="col-lg-12">
                                <label class="h3 mb-2">Tamil Transscript</label>
                                <textarea name="tamil_transcript" id="tamil_transcript" rows="50" class="form-control mb-2">{{ $tamil_transcript ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-lg-12">
                                <button class="btn btn-purple">Start English Translation</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
