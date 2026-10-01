@extends('front.auth.client.layouts.layout')

@section('content')
    <div class="container-fluid">
        <div class="card-head container-fluid">
            <div class="row">
                <div class="col-12 pl-0">
                    <h4 class="page-title"><i class="fe-file-text"></i>Oferty</h4>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body card-body-rem p-0">
                <div class="table-overflow">
                    <table class="table data-table mb-0 w-100">
                        <thead class="thead-default">
                        <tr>
                            <th>Wysyłający</th>
                            <th>Temat</th>
                            <th class="text-center">Data wysłania</th>
                            <th class="text-center">Data wygaśnięcia</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody class="content">
                        @foreach($offers as $offer)
                            <tr class="odd">
                                <td>{{ $offer->user->name }} {{ $offer->user->surname }}</td>
                                <td>{{ $offer->title }}</td>
                                <td class="text-center">
                                    {{ $offer->sended_at }}
                                </td>
                                <td class="text-center">
                                    {{ $offer->date_end }}
                                </td>
                                <td class="option-120">
                                    <div class="btn-group">
                                        <a href="{{ route('front.show', $offer) }}" class="btn action-button me-1" data-toggle="tooltip" data-placement="top" title="Otwórz ofertę" target="_blank"><i class="fe-link"></i></a>
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
