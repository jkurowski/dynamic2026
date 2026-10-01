@extends('admin.layout')

@section('content')
    <div class="container-fluid">
        <div class="card-head container-fluid">
            <div class="row">
                <div class="col-6 pl-0">
                    <h4 class="page-title"><i class="fe-check-circle"></i><a href="{{route('admin.crm.clients.index')}}">Klienci</a><span class="d-inline-flex me-2 ms-2">/</span><a href="{{ route('admin.crm.clients.show', $client->id) }}">{{$client->name}}</a><span class="d-inline-flex me-2 ms-2">/</span>Zgody RODO</h4>
                </div>
                <div class="col-6 d-flex justify-content-end align-items-center form-group-submit"></div>
            </div>
        </div>
        @include('admin.crm.client.client_shared.menu')
        <div class="row">
            <div class="col-4">
                @include('admin.crm.client.client_shared.aside')
            </div>
            <div class="col-8">
                <div class="card mt-3">
                    <div class="card-body card-body-rem">
                        <div class="table-overflow">
                            <table class="table mb-0" id="sortable">
                                <thead class="thead-default">
                                <tr>
                                    <th>Treść</th>
                                    <th>Zmiany dokonał</th>
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
                                        <td class="small">{{ $p->text }}</td>
                                        <td class="small">
                                            @if ($p->activityLogs->isNotEmpty())
                                                @foreach ($p->activityLogs as $log)
                                                    {{ causerType($log->causer_type) }}
                                                @endforeach
                                            @else
                                                -.
                                            @endif</td>
                                        <td class="text-center">{{ $p->created_at }}</td>
                                        <td class="word-break">{{ $p->source }}</td>
                                        <td class="text-center">{{ date('Y-m-d', $p->duration) }}</td>
                                        <td class="text-center">{{ $p->ip }}</td>
                                        <td class="text-center">{!! status($p->status) !!}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>

        </script>
    @endpush
@endsection
