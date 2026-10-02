@extends('admin.layout')
@section('meta_title', '- Zmiany w bazie')

@section('content')
    @php $oczekujace = collect($pliki)->whereNull('wykonano')->count(); @endphp
    <div class="container-fluid">
        <div class="card">
            <div class="card-head container-fluid">
                <div class="row">
                    <div class="col-6 pl-0">
                        <h4 class="page-title row"><i class="fe-database"></i>Zmiany w bazie (database/sql)</h4>
                    </div>
                    <div class="col-6 d-flex justify-content-end align-items-center form-group-submit">
                        @if($oczekujace)
                            <form method="POST" action="{{ route('admin.sql.wykonaj') }}" onsubmit="return confirm('Wykonać {{ $oczekujace }} plik(ów) SQL na tej bazie? Zrób wcześniej kopię bazy.')">
                                @csrf
                                <button type="submit" class="btn btn-primary">Wykonaj wszystkie oczekujące ({{ $oczekujace }})</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <p class="mb-2">Pliki wykonują się po kolei, każdy tylko raz. Przy błędzie wykonywanie się zatrzymuje, a plik z błędem zostaje oczekujący.
                    <strong>Przed wykonaniem zrób kopię bazy</strong> - zmiany struktury (ALTER, CREATE) nie cofają się same.</p>
                <p class="mb-0 text-muted">Plik puszczony wcześniej ręcznie (phpMyAdmin, konsola mysql) - „Oznacz jako wykonany”, żeby nie poszedł drugi raz.</p>

                @if(session('wykonane'))
                    <div class="alert alert-success mt-3 mb-0">
                        @foreach(session('wykonane') as $linia)<div>OK: {{ $linia }}</div>@endforeach
                    </div>
                @endif
                @if(session('blad'))
                    <div class="alert alert-danger mt-3 mb-0"><strong>Błąd - zatrzymano:</strong> {{ session('blad') }}</div>
                @endif
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body card-body-rem p-0">
                <div class="table-overflow">
                    <table class="table mb-0">
                        <thead class="thead-default">
                        <tr>
                            <th>Plik</th>
                            <th>Stan</th>
                            <th>Kto</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody class="content">
                        @foreach($pliki as $p)
                            <tr>
                                <td><code>{{ $p['plik'] }}</code></td>
                                <td>
                                    @if($p['wykonano'])
                                        <span class="badge bg-success">wykonany {{ $p['wykonano'] }}</span>
                                    @else
                                        <span class="badge bg-warning text-dark">czeka</span>
                                    @endif
                                </td>
                                <td>{{ $p['kto'] }}</td>
                                <td class="text-end">
                                    @unless($p['wykonano'])
                                        <form method="POST" action="{{ route('admin.sql.oznacz') }}" class="d-inline" onsubmit="return confirm('Oznaczyć {{ $p['plik'] }} jako wykonany BEZ uruchamiania?')">
                                            @csrf
                                            <input type="hidden" name="plik" value="{{ $p['plik'] }}">
                                            <button type="submit" class="btn btn-sm btn-outline-secondary">Oznacz jako wykonany</button>
                                        </form>
                                    @endunless
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
