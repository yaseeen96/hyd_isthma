@extends('layouts.app', ['ptype' => 'parent', 'purl' => request()->route()->getName(), 'ptitle' => 'Program Registrations'])
@section('content')
    <x-content-wrapper>
        <x-slot:title>
            Program Regisrations
        </x-slot>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12 mb-5">
                    <button class="btn btn-purple float-right" type="button" data-toggle="collapse" data-target="#regFilters"
                        aria-expanded="false" aria-controls="regFilters">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <button class="btn btn-purple float-right mr-2" onclick="clearFilters()"> <i class="fas fa-filter "></i>
                        Clear
                        Filters</button>
                </div>
                <div class="collapse container" id="regFilters">
                    <div class="card card-body shadow-none">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Program</label>
                                    <select class="form-control select2bs4" style="width: 100%;" id="program_name"
                                        onchange="setFilter()">
                                        <option value="">All</option>
                                        @foreach ($programs as $program)
                                            <option value="{{ $program->id }}"> {{ $program->topic }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Program Speaker</label>
                                    <select class="form-control select2bs4" style="width: 100%;" id="program_speaker"
                                        onchange="setFilter()">
                                        <option value="">All</option>
                                        @foreach ($speakers as $speaker)
                                            <option value="{{ $speaker->id }}">{{ $speaker->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Session Theme</label>
                                    <select class="form-control select2bs4" style="width: 100%;" id="session_theme"
                                        placeholder="Select Session Theme" onchange="setFilter()">
                                        <option value="">All</option>
                                        @foreach ($sessionThemes as $theme)
                                            <option value="{{ $theme->id }}">{{ $theme->theme_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Session Type</label>
                                    <select class="form-control select2bs4" style="width: 100%;" id="theme_type"
                                        placeholder="Select Session Theme" onchange="setFilter()">
                                        <option value="">All</option>
                                        <option value="fixed">Fixed</option>
                                        <option value="parallel">Parallel</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <x-table id="program-registrations-table">
                <th>SL.No</th>
                <th>Rukun Name</th>
                <th>Rukun ID</th>
                <th>Program</th>
                <th>Speaker</th>
                <th>Speaker Name</th>
                <th>Session Theme</th>
                <th>Theme Type</th>
                <th>Date&Time</th>
                <th>Action</th>
            </x-table>
        </div>
    </x-content-wrapper>
@endsection
@push('scripts')
    <script type="text/javascript">
        // clear filters
        function clearFilters() {
            $('#program_name').val('').trigger('change');
            $('#program_speaker').val('').trigger('change');
            $('#session_theme').val('').trigger('change');
            $('#theme_type').val('').trigger('change');
            setFilter();
        }
        $(function() {
            programRegistrationsTable = $('#program-registrations-table').DataTable({
                ajax: {
                    url: "{{ route('programRegistration.index') }}",
                    data: function(d) {
                        d.program_name = $('#program_name').val();
                        d.program_speaker = $('#program_speaker').val();
                        d.session_theme = $('#session_theme').val();
                        d.theme_type = $('#theme_type').val();
                    }
                },
                columns: [
                    dtIndexCol(),
                    {
                        data: 'member.name',
                    },
                    {
                        data: 'member.user_number'
                    },
                    {
                        data: 'program.topic'
                    },
                    {
                        data: 'speaker_image'
                    },
                    {
                        data: 'speaker'
                    },
                    {
                        data: 'session_theme'
                    },
                    {
                        data: 'theme_type'
                    },
                    {
                        data: 'program_date_time'
                    },
                    {
                        data: 'action'
                    }
                ],
            });
        })
        $('table').on('click', '.programRegistration-delete', function(e) {
            $.ajax({
                url: $(this).data('href'),
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'JSON',
                success: function(data) {
                    Toast.fire({
                        icon: 'success',
                        title: data.message
                    })
                    setFilter();
                },
                error: function(error) {
                    Toast.fire({
                        icon: 'error',
                        title: error.responseJSON.message
                    })
                }
            })
        });

        function setFilter() {
            programRegistrationsTable.draw();
        }
    </script>
@endpush
