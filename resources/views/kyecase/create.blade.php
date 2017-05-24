@extends('layouts.app')


@section('HTMLContent')
    <h3>{{ $staff->employNo }}: {{ $staff->uEngName }}</h3>
    <hr>

    <h4>{{ $staff->department }}-{{ $staff->section }}</h4>
    <h4>OccupationalRisk: {{ $occupationalRisk->riskLevel or 'No Matching In Occupational Risk List. Please Modify Related Info before Proceeding!' }}</h4>
    <hr>

    <div class="form-group required">
        <h4>Dow Jones Report:</h4>
        <input type="file" class="file-input" id="dow-jones-report" name="dow-jones-report">
    </div>

    <div class="form-group required">
        <h4>Questnet Report:</h4>
        <input type="file" class="file-input" id="questnet-report" name="questnet-report">
    </div>

    <div class="form-group">
        <h4>Credit Bureau Report:</h4>
        <input type="file" class="file-input" id="credit-bureau-report" name="credit-bureau-report">
    </div>

    <div class="form-group required">
        <h4>Relationship Risk</h4>
        <select class="form-control" id="relationship-risk">
            <option selected disabled></option>
            <option value="low">Low</option>
            <option value="high">High</option>
        </select>
    </div>

    <div class="form-group required">
        <h4>Special Factor</h4>
        <select class="form-control" id="special-factor">
            <option selected disabled></option>
            <option value="low">Low</option>
            <option value="high">High</option>
        </select>
    </div>
    <hr>

    <div class="form-group required">
        <h4>Overall Rating</h4>
        <select class="form-control" id="overall-rating">
            <option selected disabled></option>
            <option value="low">Low</option>
            <option value="high">High</option>
        </select>
    </div>

    <button class="btn btn-primary btn-block" id="submit-kye-case">
        Submit
    </button>
    <br>
    <br>

@endsection


@section('javascriptContent')
    <script type="text/javascript">
        $(document).ready(function () {
            $('input.file-input').fileinput({
                showUpload: false
            });
        });
    </script>
@endsection