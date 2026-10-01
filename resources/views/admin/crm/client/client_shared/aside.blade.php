<div class="card mt-3">
    <div class="card-border-header"><i class="fe-user"></i> Dane kontaktowe</div>
    <div class="card-body card-body-rem">
        <ul class="list-group list-group-flush">
            <li class="list-group-item">Imię <b class="float-end">{{$client->name}}</b></li>
            <li class="list-group-item">E-mail <b class="float-end">{{$client->mail}}</b></li>
            @if($client->phone)<li class="list-group-item">Telefon <b class="float-end">{{$client->phone}}</b></li>@endif
            @if($client->ip)<li class="list-group-item">IP <b class="float-end">{{$client->ip}}</b></li>@endif
            @if($client->host)<li class="list-group-item">Host <b class="float-end">{{$client->host}}</b></li>@endif
            @if($client->browser)<li class="list-group-item">Przeglądarka: <b class="float-end">{{$client->browser}}</b></li>@endif
            <li class="list-group-item">Data rejestracji: <b class="float-end">{{$client->created_at}}</b></li>
            @if($client->updated_at)<li class="list-group-item">Ostatnia aktualizacja: <b class="float-end">{{$client->updated_at}}</b></li>@endif
            <li class="list-group-item">RODO - Zgoda na telefon: {!! clientRule(1, $client->id) !!}</li>
            <li class="list-group-item">RODO - Zgoda na e-mail: {!! clientRule(2, $client->id) !!}</li>
            <li class="list-group-item">RODO - Zgoda na profilowanie: {!! clientRule(3, $client->id) !!}</li>
        </ul>
    </div>
</div>
