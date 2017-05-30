@extends('layouts.app')


@section('HTMLContent')
    <ul class="nav nav-pills">
        <li><a href="#kye-case-div" data-toggle='pill' id="key-case-link">KYE Cases</a></li>
        <li><a href="#staff-div" data-toggle='pill' id="staff-link">Staff</a></li>
        <li><a href="#occupational-risk-div" data-toggle='pill' id="occupational-risk-link">Occupational Risks</a></li>
        <li><a href="#parameter-div" data-toggle='pill' id="parameter-link">Parameters</a></li>
    </ul>
    <br>

    <div class="tab-content">
        <div class="tab-pane fade in active" id="kye-case-div">
            <ul class="list-group">
                @foreach($KYECaseLogs as $log)
                    <li class="list-group-item">
                        {!! $log->showRecord() !!}
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="tab-pane fade" id="staff-div">
            <ul class="list-group">
                @foreach($staffLogs as $log)
                    <li class="list-group-item">
                        {{ $log->showRecord() }}
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="tab-pane fade" id="occupational-risk-div">
            <ul class="list-group">
                @foreach($riskLogs as $log)
                    <li class="list-group-item">
                        {!! $log->showRecord() !!}
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="tab-pane fade" id="parameter-div">
            <ul class="list-group">
                @foreach($paraLogs as $log)
                <li class="list-group-item">
                    {{ $log->showRecord() }}
                </li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection