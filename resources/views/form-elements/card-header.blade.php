@if($cardTitle)
    <div class="card-head container">
        <div class="row">
            <div class="col-6 pl-0">
                <h4 class="page-title"><i class="fe-edit"></i>{{ $cardTitle }}</h4>
            </div>
        </div>
    </div>
    <a href="/{{ $backButton }}" class="back">Wróć do listy</a>
@endif
