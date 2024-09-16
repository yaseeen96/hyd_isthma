@extends('layouts.app', ['ptype' => 'child', 'purl' => request()->route()->getName(), 'id' => $operator->id ?? '', 'ptitle' => 'Qr Operator', 'ctitle' => $operator->id ? 'Edit' : 'Add'])
@section('pagetitle', 'Operators')
@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container  mt-3 px-3  rounded-2 ">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $operator->id ? 'Edit' : 'Add' }} Operators
                    </h3>
                </div>
                <form action="{{ $operator->id ? route('qrOperators.update', $operator->id) : route('qrOperators.store') }}"
                    method="post" enctype="multipart/form-data">
                    @csrf
                    {{ $operator->id ? method_field('PUT') : '' }}
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-lg-6">
                                {{-- Name --}}
                                <div class="form-group row">
                                    <div class="col-lg-12">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control" name="name" id="name"
                                            value="{{ old('name', $operator->name) }}">
                                        @if ($errors->has('name'))
                                            <span class="text-danger">
                                                {{ $errors->first('name') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                {{-- Phone Number --}}
                                <div class="form-group row">
                                    <div class="col-lg-12">
                                        <label for="phone_number">Phone Number</label>
                                        <input type="text" class="form-control" name="phone_number" id="phone_number"
                                            value="{{ old('phone_number', $operator->phone_number) }}">
                                        @if ($errors->has('phone_number'))
                                            <span class="text-danger">
                                                {{ $errors->first('phone_number') }}
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
                                <button class="btn btn-purple  mr-2">{{ $operator->id ? 'Update' : 'Save' }}</button>
                                <a href="{{ route('qrOperators.index') }}" class="btn btn-secondary ">Cancel</a>
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
