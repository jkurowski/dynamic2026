@extends('admin.layout')

@section('content')
    <div class="container-fluid">
        <div class="card-head container-fluid">
            <div class="row">
                <div class="col-6 pl-0">
                    <h4 class="page-title"><i class="fe-book-open"></i>Przeglądaj zadania</h4>
                </div>
                <div class="col-6 d-flex justify-content-end align-items-center form-group-submit">

                </div>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-body card-body-rem p-0">
                <div class="table-overflow">
                    <h1>Job Details</h1>
                    <table class="table">
                        <tr>
                            <th>ID</th>
                            <td>{{ $job->id }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>{{ $job->status }}</td>
                        </tr>
                        <tr>
                            <th>Payload</th>
                            <td>{{ $job->payload }}</td>
                        </tr>
                        @if ($job->status == 'failed')
                            <tr>
                                <th>Exception</th>
                                <td>{{ $job->exception }}</td>
                            </tr>
                            <tr>
                                <th>Failed At</th>
                                <td>{{ $job->failed_at }}</td>
                            </tr>
                        @else
                            <tr>
                                <th>Queue</th>
                                <td>{{ $job->queue }}</td>
                            </tr>
                            <tr>
                                <th>Attempts</th>
                                <td>{{ $job->attempts }}</td>
                            </tr>
                            <tr>
                                <th>Reserved At</th>
                                <td>{{ $job->reserved_at }}</td>
                            </tr>
                            <tr>
                                <th>Available At</th>
                                <td>{{ $job->available_at }}</td>
                            </tr>
                            <tr>
                                <th>Created At</th>
                                <td>{{ $job->created_at }}</td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
