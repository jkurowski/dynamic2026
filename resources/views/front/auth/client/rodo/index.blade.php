@extends('front.auth.client.layouts.layout')

@section('content')
    <div class="container-fluid">
        <div class="card-head container-fluid">
            <div class="row">
                <div class="col-12 pl-0">
                    <h4 class="page-title"><i class="fe-list"></i>RODO</h4>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body card-body-rem p-0">
                <div class="table-overflow">
                    <table class="table mb-0" id="sortable">
                        <thead class="thead-default">
                        <tr>
                            <th class="w-50">Treść</th>
                            <th class="text-center">Data podpisania</th>
                            <th>Miejsce podpisania</th>
                            <th class="text-center">Termin wygaśniecia</th>
                            <th class="text-center">IP</th>
                            <th class="text-center">Status</th>
                        </tr>
                        </thead>
                        <tbody class="content">
                        @foreach ($list as $p)
                            <tr>
                                <td class="small">{!! $p->text !!}</td>
                                <td class="text-center">{{ $p->created_at }}</td>
                                <td class="word-break">{{ $p->source }}</td>
                                <td class="text-center">
                                    @if($p->status == 1)
                                    {{ date('Y-m-d', $p->duration) }}
                                    @else
                                        Anulowano: <br>{{ $p->canceled_at }}
                                    @endif
                                </td>
                                <td class="text-center">{{ $p->ip }}</td>
                                <td class="text-center">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input status-radio" type="radio" id="true{{ $p->id }}" value="1" @if($p->status == 1) checked @endif data-id="{{ $p->id }}" name="rule-{{ $p->id }}">
                                        <label class="form-check-label" for="true{{ $p->id }}">Tak</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input status-radio" type="radio" id="false{{ $p->id }}" value="2" @if($p->status == 2) checked @endif data-id="{{ $p->id }}" name="rule-{{ $p->id }}">
                                        <label class="form-check-label" for="false{{ $p->id }}">Nie</label>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $('.status-radio').change(function() {
            const status = $(this).val();
            const entryId = $(this).data('id');
            $.ajax({
                url: '{{ route('front.client.area.rodo.change-rule') }}', // Replace with your route
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: entryId,
                    status: status
                },
                success: function(response) {
                    console.log('Status updated successfully');
                    //location.reload();
                },
                error: function(xhr, status, error) {
                    console.error('An error occurred:', error);
                    // Handle error if needed
                }
            });
        });
    </script>
@endpush