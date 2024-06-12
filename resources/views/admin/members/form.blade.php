@extends('layouts.app', ['ptype' => 'child', 'purl' => request()->route()->getName(), 'id' => $advertisement->id ?? '', 'ptitle' => 'Advertisement', 'ctitle' => $advertisement->id ? 'Edit' : 'Add'])
@section('pagetitle', 'Dashboard')
@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container  mt-3 px-3  rounded-2 ">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $advertisement->id ? 'Edit' : 'Add' }} Advertisement
                    </h3>
                </div>
                <form
                    action="{{ $advertisement->id ? route('advertisement.update', $advertisement->id) : route('advertisement.store') }}"
                    method="post" enctype="multipart/form-data">
                    @csrf
                    {{ $advertisement->id ? method_field('PUT') : '' }}
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-lg-6">
                                {{-- position --}}
                                <div class="form-group row">
                                    <div class="col-lg-12">
                                        <label for="position">Position <span class="text-danger">*</span> :</label>
                                        <input type="text" class="form-control" name="position" id="position"
                                            value="{{ old('position', $advertisement->position) }}">
                                        @if ($errors->has('position'))
                                            <span class="text-danger">
                                                {{ $errors->first('position') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                {{-- url --}}
                                <div class="form-group row">
                                    <div class="col-lg-12">
                                        <label for="url">Url <span class="text-danger">*</span> :</label>
                                        <input type="text" name="url" id="url"
                                            value="{{ old('url', $advertisement->url) }}" class="form-control"
                                            id="url">
                                        @if ($errors->has('url'))
                                            <span class="text-danger">
                                                {{ $errors->first('url') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                {{-- new tab --}}
                                <div class="form-group row">
                                    <div class="col-lg-3">
                                        <div class="custom-control custom-checkbox d-flex  align-content-center ">
                                            <input
                                                class="custom-control-input custom-control-input-danger custom-control-input-outline"
                                                type="checkbox" id="customCheckbox5" name="newtab"
                                                {{ $advertisement->newtab ? 'checked' : 'unchecked' }}>
                                            <label for="customCheckbox5" class="custom-control-label">New Tab</label>
                                        </div>
                                        @if ($errors->has('newtab'))
                                            <p class="text-danger">
                                                {{ $errors->first('newtab') }}
                                            </p>
                                        @endif
                                    </div>
                                    {{-- Status --}}
                                    <div class="col-lg-9">
                                        <div class="form-group">
                                            <div
                                                class="custom-control custom-switch custom-switch-off-gray custom-switch-on-success">
                                                <input type="checkbox" class="custom-control-input" id="customSwitch3"
                                                    name="status" {{ $advertisement->status ? 'checked' : 'unchecked' }}>
                                                <label class="custom-control-label" for="customSwitch3">Status</label>
                                            </div>
                                            @if ($errors->has('status'))
                                                <span class="text-danger">
                                                    {{ $errors->first('status') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- featured image --}}
                            <div class="col-lg-6">
                                <div class="form-group row">
                                    <div class="col-lg-12">
                                        <label for="featured_img">Image <span class="text-danger">*</span> :</label>
                                        <input type="file" name="featured_img" id="featured_img" class="form-control">
                                        <input type="hidden" name="old_featured_image"
                                            value="{{ isset($featured) ? $featured->id : '' }}">
                                        @if ($errors->has('featured_img'))
                                            <span class="text-danger">
                                                {{ $errors->first('featured_img') }}
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
                                <button class="btn btn-primary  mr-2">{{ $advertisement->id ? 'Update' : 'Save' }}</button>
                                <a href="{{ route('advertisement.index') }}" class="btn btn-secondary ">Cancel</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection
