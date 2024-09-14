@extends('layouts.app', ['ptype' => 'parent', 'purl' => request()->route()->getName(), 'ptitle' => 'Audio Processing'])
@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container  mt-3 px-3  rounded-2 ">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">
                        Audio Processing
                    </h3>
                </div>
                <form id="audio-processing" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="youtube_url">Youtube URL</label>
                                    <input type="text" class="form-control" id="youtube_url" name="youtube_url"
                                        placeholder="Enter Youtube URL">
                                </div>
                                <div class="form-group">
                                    <label for="language_1">Language 1</label>
                                    <select class="form-control" id="language_1" name="language_1">
                                        <option value="mr">Marathi</option>
                                        <option value="te">Telugu</option>
                                        <option value="be">Bengali</option>
                                        <option value="ta">Tamil</option>
                                        <option value="ka">Kannada</option>
                                        <option value="ma">Malayalam</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="language_2">Language 2</label>
                                    <select class="form-control" id="language_2" name="language_2">
                                        <option value="mr">Marathi</option>
                                        <option value="te">Telugu</option>
                                        <option value="be">Bengali</option>
                                        <option value="ta">Tamil</option>
                                        <option value="ka">Kannada</option>
                                        <option value="ma">Malayalam</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer row">
                        <div class="loader loader-hide mx-2"></div>
                        <button type="submit" class="btn btn-primary audio-processing mx-2">Start Processing</button>
                        <a href="{{ route('audioProcessing.index') }}" class="btn btn-secondary mx-2">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script type="text/javascript">
        $(function() {
            $.validator.setDefaults({
                submitHandler: function() {
                    alert("Form successful submitted!");
                }
            });
            $('#audio-processing').validate({
                rules: {
                    youtube_url: {
                        required: true,
                    },
                },
                messages: {
                    youtube_url: {
                        required: "Please enter youtube url",
                    },
                },
                submitHandler: function(form) {
                    $('button[type="submit"]').hide();
                    $('.loader').removeClass('loader-hide');
                    $.ajax({
                        url: "{{ route('audioProcessing.store') }}",
                        type: 'POST',
                        data: $('#audio-processing').serialize(),
                        success: function(response) {
                            if (response.status == 200) {
                                Toast.fire({
                                    icon: 'success',
                                    title: 'Successfully.'
                                })
                                window.location.href = response.redirect_url;
                                $('#audio-processing').trigger('reset');
                            } else {
                                Toast.fire({
                                    icon: 'error',
                                    title: 'Encountered an error while processing the audio.'
                                })
                            }
                            $('button[type="submit"]').show();
                            $('.loader').addClass('loader-hide');
                        }
                    })
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });
        });
    </script>
@endpush
