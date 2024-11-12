@extends('layouts.app', ['ptype' => 'parent', 'purl' => request()->route()->getName(), 'ptitle' => 'Faqs'])
@section('content')
    <x-content-wrapper>
        <x-slot:title>
            Faqs
        </x-slot>
        <div class="card-body">
            @php
                $message = Session::get('success') ?? Session::get('error');
            @endphp
            @if ($message)
                <div class="alert {{ Session::get('success') ? 'alert-success' : 'alert-danger' }} alert-block">
                    <button type="button" class="close text-white" data-dismiss="alert">X</button>
                    <strong>{{ $message }}</strong>
                </div>
            @endif
            <div class="row">
                <div class="col-lg-12">
                    @if (auth()->user()->id == 1 || auth()->user()->can('Create Faq'))
                        <a href="{{ route('faq.create') }}" class="btn btn-purple float-right"><i
                                class="fas fa-plus mr-2"></i>Add
                            FAQ</a>
                    @endif
                </div>
            </div>
            <x-table id="faq-table">
                <th>SL.No</th>
                <th>Order</th>
                <th>Question</th>
                <th>Answer</th>
                <th>Attachment</th>
                <th>Action</th>
            </x-table>
        </div>
    </x-content-wrapper>
@endsection
@push('scripts')
    <script type="text/javascript">
        $(function() {
            faqTable = $('#faq-table').DataTable({
                ajax: {
                    url: "{{ route('faq.index') }}",
                },
                columns: [
                    dtIndexCol(),
                    {
                        data: 'order',
                    },
                    {
                        data: 'question',
                    },
                    {
                        data: 'answer',
                    },
                    {
                        data: "faq_attachment",
                    },
                    {
                        data: 'action',
                        orderable: false
                    },
                ],
            });
        });
        $('table').on('click', '.faq-delete', function(e) {
            $.ajax({
                url: $(this).data('href'),
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'JSON',
                success: function(data) {
                    if (data.status == 200) {
                        Toast.fire({
                            icon: 'success',
                            title: 'Successfully.'
                        })
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: 'Encountered an error while deleting the FAQ.'
                        })
                    }
                    faqTable.draw();

                }
            })
        });
    </script>
@endpush
