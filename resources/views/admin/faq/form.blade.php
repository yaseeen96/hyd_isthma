@extends('layouts.app', ['ptype' => 'child', 'purl' => request()->route()->getName(), 'id' => $faq->id ?? '', 'ptitle' => 'Program Speaker', 'ctitle' => $faq->id ? 'Edit' : 'Add'])
@section('pagetitle', 'Faq')
@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container mt-3 px-3 rounded-2 ">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $faq->id ? 'Edit' : 'Add' }} Faq
                    </h3>
                </div>
                <form action="{{ $faq->id ? route('faq.update', $faq->id) : route('faq.store') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    {{ $faq->id ? method_field('PUT') : '' }}
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-lg-6">
                                {{-- question --}}
                                <div class="form-group row">
                                    <div class="col-lg-12">
                                        <label for="question">Question</label>
                                        <input type="text" class="form-control" name="question" id="question"
                                            value="{{ old('question', $faq->question) }}">
                                        @if ($errors->has('question'))
                                            <span class="text-danger">
                                                {{ $errors->first('question') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                {{-- Attchment --}}
                                <div class="form-group row">
                                    <div class="col-lg-12">
                                        <label for="bio">Attachment</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="faq_attachment"
                                                    name="faq_attachment">
                                                <label class="custom-file-label" for="faq_attachment">Choose
                                                    file</label>
                                            </div>
                                        </div>
                                        @if ($errors->has('faq_attachment'))
                                            <span class="text-danger">
                                                {{ $errors->first('faq_attachment') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                {{-- Answer --}}
                                <div class="form-group row">
                                    <div class="col-lg-12">
                                        <label for="answer">Answer</label>
                                        <textarea type="text" class="form-control" name="answer" id="answer" value="{{ old('answer', $faq->answer) }}">{{ old('answer', $faq->answer) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-lg-12 text-right">
                                <button class="btn btn-purple  mr-2">{{ $faq->id ? 'Update' : 'Save' }}</button>
                                <a href="{{ route('faq.index') }}" class="btn btn-secondary ">Cancel</a>
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
