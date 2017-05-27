@extends('layouts.app')


@section('HTMLContent')
    <table>
        <tbody>
            <tr>
                <td><h5>Employ No:</h5></td>
                <td><h5>&nbsp;{{ $staff->employNo }}</h5></td>
            </tr>
            <tr>
                <td><h5>Name:</h5></td>
                <td><h5>&nbsp;{{ $staff->uEngName }}</h5></td>
            </tr>
            <tr>
                <td><h5>Department:</h5></td>
                <td><h5>&nbsp;{{ $staff->department }}</h5></td>
            </tr>
            <tr>
                <td><h5>Section:</h5></td>
                <td><h5>&nbsp;{{ $staff->section }}</h5></td>
            </tr>
            <tr>
                <td><h5>Join Date:</h5></td>
                <td><h5>&nbsp;{{ $staff->joinDate }}</h5></td>
            </tr>
            <tr>
                <td><h5>Leave Date:</h5></td>
                <td><h5>&nbsp;{{ $staff->leaveDate or 'In Service' }}</h5></td>
            </tr>
        </tbody>
    </table>
    <br>

    @if(is_null($pendingCaseLog))
        @if($isMaker)
            <button class="btn btn-primary btn-block" 
                    onclick=window.open('{{ route('KYECaseCreate', ['empNo' => $staff->employNo]) }}')>
                File One KYE Case
            </button>
        @endif
    @else
        <div class="alert alert-info alert-xs" style="padding: 0">
            @if($isChecker)
                <a href="{{ route('KYECaseChecker', ['logID' => $pendingCaseLog->id]) }}" target="_blank" class="alert-link">
            @endif
                <table class="table" style="margin: 0">
                    <tr>
                        <td>Pending Case:</td>
                        <td>{{ $pendingCaseLog->to->department }}</td>
                        <td>{{ $pendingCaseLog->to->section }}</td>
                        <td>Overall Risk: {{ $pendingCaseLog->to->overallRisk }}</td>
                    </tr>
                </table>
            @if($isChecker)
            </a>
            @endif
        </div>
    @endif
    <br>

    <h5>Completed Cases:</h5>
    <table class="table table-striped">
        <tbody>
            @foreach($approvedCases as $case)
                <tr>
                    <td>{{ $case->department }}</td>
                    <td>{{ $case->section }}</td>
                    <td>Overall Risk: {{ $case->overallRisk }}</td>
                    <td>{{ $case->getElapsedTimeString() }}</td>
                    <td>
                        <button class="btn btn-primary btn-xs"
                                onclick=window.open("{{ route('KYECaseView', ['caseID' => $case->id]) }}")>
                            View
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection


@section('javascriptContent')

@endsection