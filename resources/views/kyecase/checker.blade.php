@extends('layouts.app')


@section('HTMLContent')
    <p>{{ $log->to->employNo }}: {{ $log->to->name }}</p>
    <p>{{ $log->to->department }}-{{ $log->to->section }}</p>
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
                <td>{{ $log->to->occupationalRisk }}</td>
            </tr>
            <tr>
                <td>Relationship Risk</td>
                <td>{{ $log->to->relationshipRisk }}</td>
            </tr>
            <tr>
                <td>Special Factors</td>
                <td>{{ $log->to->specialFactors }}</td>
            </tr>
            <tr>
                <td>Overall Risk</td>
                <td>{{ $log->to->overallRisk }}</td>
            </tr>
        </tbody>
    </table>
    <br>

    <p>Created by 
        <font size="4" color="CadetBlue"><i>{{ $maker->uEngName }}</i></font> 
        at 
        <font size="4" color="CadetBlue"><i>{{ $log->created_at }}</i></font>
    </p>
    <br>

    <div class="col-md-12">
        <div class="col-md-3"></div>
        <div class="col-md-2">
            <button class="btn btn-primary btn-lg" id="btn-approve">
                Approve
            </button>
        </div>
        <div class="col-md-2"></div>
        <div class="col-md-2">
            <button class="btn btn-danger btn-lg" id="btn-reject">
                Reject
            </button>
        </div>
        <div class="col-md-3"></div>
    </div>
    <br>
    <br>
@endsection


@section('javascriptContent')
<script type="text/javascript">
    $(document).ready(function () {
        var data = {logid: "{{ $log->id }}"};

        $('#btn-approve').click(function () {
            $.ajax({
                headers: headers,
                url: "{{ route('KYECaseApprove') }}",
                data: data,
                type: 'POST',
                success: function (data) {
                    handleReturn(data, function () {
                        window.location.replace(data.url);
                    });
                }
            });
        });

        $('#btn-reject').click(function () {
            $.ajax({
                headers: headers,
                url: "{{ route('KYECaseReject') }}",
                data: data,
                type: 'POST',
                success: function (data) {
                    handleReturn(data);
                }
            });
        });
    });
</script>
@endsection