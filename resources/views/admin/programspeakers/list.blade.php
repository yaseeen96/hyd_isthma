@extends('layouts.app', ['ptype' => 'parent', 'purl' => request()->route()->getName(), 'ptitle' => 'Program Speakers'])
@section('content')
    <x-content-wrapper>
        <x-slot:title>
            Program Speakers
        </x-slot>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="col-lg-12">
                        <a href="{{ route('programSpeakers.create') }}" class="btn btn-purple float-right"><i
                                class="fas fa-plus mr-2"></i>Create</a>
                    </div>

                </div>
            </div>
            <x-table id="speakers-table">
                <th>SL.No </th>
                <th>Name</th>
                <th>Bio</th>
                <th>Image</th>
                <th>Action</th>
            </x-table>
        </div>
    </x-content-wrapper>
@endsection
@push('scripts')
    <script type="text/javascript">
        const speakersTable = $("#speakers-table").DataTable({
            ajax: {
                url: "{{ route('programSpeakers.index') }}",
            },
            columns: [
                dtIndexCol(),
                {
                    data: 'name',
                },
                {
                    data: 'bio'
                },
                {
                    data: 'speaker_image',
                },
                {
                    data: 'action'
                }
            ]
        })
        $('table').on('click', '.programSpeaker-delete', function(e) {
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
                    speakersTable.draw();
                },
                error: function(error) {
                    Toast.fire({
                        icon: 'error',
                        title: error.responseJSON.message
                    })
                }
            })
        });
    </script>
@endpush
