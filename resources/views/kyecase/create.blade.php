@extends('layouts.app')


@section('HTMLContent')
    <h3>{{ $staff->employNo }}: {{ $staff->uEngName }}</h3>
    <hr>

    <h4>{{ $staff->department }}-{{ $staff->section }}</h4>

    @if(!isset($occupationalRisk->riskLevel))
        <h4>Occupational Risk: <font color="red">No Matching In Occupational Risk List. Please Modify Related Info before Proceeding!</font></h4>
    @else
        <h4>Occupational Risk: {{ $occupationalRisk->riskLevel }}</h4>
    @endif
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

    @if(!isset($occupationalRisk->riskLevel))
        <button class="btn btn-primary btn-block" id="submit-kye-case" disabled>
    @else
        <button class="btn btn-primary btn-block" id="submit-kye-case">
    @endif
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

            $('#submit-kye-case').click(function () {
                // check empty
                if (0 == $('#dow-jones-report')[0].files.length 
                    || 0 == $('#questnet-report')[0].files.length
                    || !$('#relationship-risk').val()
                    || !$('#special-factor').val()
                    || !$('#overall-rating').val()) {
                    
                    setAlertText("Please fill in all mandatory fields.");
                    $('#alert-modal').modal('show');
                }

                // assemble form data
                var form = new FormData();
                form.append('_token', $("meta[name='csrf-token']").attr('content'));
                form.append('employno', '{{ $staff->employNo }}');
                form.append('occupationalrisk', '{{ $occupationalRisk->riskLevel or null }}');
                form.append('dowjonesreport', $('#dow-jones-report')[0].files[0]);
                form.append('questnetreport', $('#questnet-report')[0].files[0]);
                form.append('relationshiprisk', $('#relationship-risk').val());
                form.append('specialfactor', $('#special-factor').val());
                form.append('overallrating', $('#overall-rating').val());

                if ($('#credit-bureau-report')[0].files.length) {
                    form.append('creditbureaureport', $('#credit-bureau-report')[0].files[0]);
                }

                // send to server side
                var xhr = new XMLHttpRequest();
                xhr.open('POST', "{{ route('KYECaseMake') }}", true);
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.onload = function () {
                    var data = JSON.parse(xhr.response);
                    handleReturn(data);
                }

                xhr.send(form)
            });
        });
    </script>
@endsection