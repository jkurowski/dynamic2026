@extends('admin.layout')

@section('content')
    <div class="container-fluid">
        <div class="card-head container-fluid">
            <div class="row">
                <div class="col-6 pl-0">
                    <h4 class="page-title"><i class="fe-book-open"></i>Przeglądaj zaplanowane zadania</h4>
                </div>
                <div class="col-6 d-flex justify-content-end align-items-center form-group-submit">

                </div>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-body card-body-rem p-0">
                <div class="table-overflow">
                    <table class="table">
                        <thead class="thead-default">
                        <tr>
                            <th>ID</th>
                            <th>Kolejka</th>
                            <th>Ładunek</th>
                            <th>Próby</th>
                            <th>Zaplanowane</th>
                            <th>Utworzone</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody class="content">
                        @foreach ($queuedJobs as $job)
                            <tr>
                                <td>{{ $job->id }}</td>
                                <td>{{ $job->queue }}</td>
                                <td><pre style="width:300px">{{ json_encode($job->payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre></td>
                                <td>{{ $job->attempts }}</td>
                                <td>{{ $job->available_at }}</td>
                                <td>{{ $job->created_at }}</td>
                                <td class="option-120">
                                    <div class="btn-group">
                                        <form method="POST" action="{{route('admin.crm.jobs.removeJob', $job->id)}}">
                                            {{ csrf_field() }}
                                            <button type="submit" class="btn action-button confirm" data-bs-toggle="tooltip" data-placement="top" data-bs-title="Usuń wpis" data-id="{{ $job->id }}"><i class="fe-trash-2"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <table class="table">
                        <thead class="thead-default">
                        <tr>
                            <th>ID</th>
                            <th>Kolejka</th>
                            <th>Ładunek</th>
                            <th>Błąd</th>
                            <th>Utworzone</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody class="content">
                        @foreach ($failedJobs as $job)
                            <tr>
                                <td>{{ $job->id }}</td>
                                <td>{{ $job->queue }}</td>
                                <td><pre style="width:300px">{{ json_encode($job->payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre></td>
                                <td class="w-25 text-break">{{ \Illuminate\Support\Str::limit($job->exception, 190) }}</td>
                                <td>{{ $job->failed_at }}</td>
                                <td class="option-120">
                                    <div class="btn-group">
                                        <form method="POST" action="{{ route('admin.crm.jobs.retry', $job->id) }}">
                                            {{ csrf_field() }}
                                            {{ method_field('POST') }}
                                            <button type="submit" class="btn action-button confirm" data-bs-toggle="tooltip" data-placement="top" data-bs-title="Powtórz zadanie" data-id="{{ $job->id }}"><i class="fe-repeat"></i></button>
                                        </form>

                                        <form method="POST" action="{{route('admin.crm.jobs.removeFailedJob', ['job' => $job->id])}}">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                            <button type="submit" class="btn action-button confirm ms-2" data-bs-toggle="tooltip" data-placement="top" data-bs-title="Usuń zadanie" data-id="{{ $job->id }}"><i class="fe-trash-2"></i></button>
                                        </form>
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
    @push('scripts')
        <script>
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
            @if (session('success')) toastr.options={closeButton:!0,progressBar:!0,positionClass:"toast-bottom-right",timeOut:"3000"};toastr.success("{{ session('success') }}"); @endif
        </script>
    @endpush
@endsection
