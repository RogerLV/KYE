@extends('layouts.app')


@section('HTMLContent')
    <p>{{ $case->employNo }}: {{ $case->staff->uEngName }}</p>
    <p>{{ $case->department }}-{{ $case->section }}</p>
    <br>

    <h5 align="center">Dow Jones Report</h5>
    <iframe src="{{ $dowJonesReport->getUrl() }}" allowfullscreen width="700px" height="500px"></iframe>
    <br>
    <a href="{{ $dowJonesReport->getUrl() }}" target="iframe_a">View in new tab</a>
    <br><br>

    <h5 align="center">Questnet Report</h5>
    <iframe src="{{ $questnetReport->getUrl() }}" allowfullscreen width="700px" height="500px"></iframe>
    <br>
    <a href="{{ $questnetReport->getUrl() }}" target="iframe_a">View in new tab</a>
    <br><br>

    @if(is_null($creditBureauReport))
        <p>Credit Bureau Report is not available.</p>
    @else
        <h5 align="center">Credit Bureau Report</h5>
        <iframe src="{{ $creditBureauReport->getUrl() }}" allowfullscreen width="700px" height="500px"></iframe>
        <br>
        <a href="{{ $creditBureauReport->getUrl() }}" target="iframe_a">View in new tab</a>
        <br><br>
    @endif

    <h5 align="center">Risk Level</h5>
    <table class="table">
        <tbody>
            <tr>
                <td width="200">Occupational Risk</td>
                <td>{{ $case->occupationalRisk }}</td>
            </tr>
            <tr>
                <td>Relationship Risk</td>
                <td>{{ $case->relationshipRisk }}</td>
            </tr>
            <tr>
                <td>Special Factors</td>
                <td>{{ $case->specialFactors }}</td>
            </tr>
            <tr>
                <td>Overall Risk</td>
                <td>{{ $case->overallRisk }}</td>
            </tr>
        </tbody>
    </table>
    <br>

    <p>Created by 
        <font size="4" color="CadetBlue"><i>{{ $maker->uEngName }}</i></font> 
        at 
        <font size="4" color="CadetBlue"><i>{{ $case->log->created_at }}</i></font>
    </p>
    <p>
    	@if($case->log->checkedResult)
    		Confirmed By
    	@else
    		Rejected By
    	@endif
    	<font size="4" color="CadetBlue"><i>{{ $checker->uEngName }}</i></font> 
    	at
        <font size="4" color="CadetBlue"><i>{{ $case->log->updated_at }}</i></font>
    </p>
    <br>
@endsection