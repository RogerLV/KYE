@extends('layouts.app')


@section('HTMLContent')
    <table class="table table-hover">
        <thead>
            <tr>
                <th class="text-center" bgcolor="grey" colspan="7">New Occupational Risk Pending for Check</th>
            </tr>
            <tr>
                <th>#</th>
                <th>Department</th>
                <th>Section</th>
                <th>Description</th>
                <th>Risk Level</th>
                <th>Approve</th>
                <th>Reject</th>
            </tr>
        </thead>
        <tbody>
            <?php $i=1; ?>
            @foreach($pendingAdd as $entry)
                <tr data-pending-id="{{ $entry->id }}" class="pending-entry">
                    <td>{{ $i++ }}</td>
                    <td>{{ $entry->to->department }}</td>
                    <td>{{ $entry->to->section }}</td>
                    <td>{!! nl2br($entry->to->description) !!}</td>
                    <td>{{ $entry->to->riskLevel }}</td>
                    <td>
                        <button class="btn btn-primary btn-xs approve-pending">
                            <span class="glyphicon glyphicon-ok"></span>
                        </button>
                    </td>
                    <td>
                        <button class="btn btn-danger btn-xs removing-pending">
                            <span class="glyphicon glyphicon-remove"></span>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="table table-hover">
        <thead>
            <tr>
                <th class="text-center" bgcolor="grey" colspan="7">Updated Occupational Risk Pending for Check</th>
            </tr>
            <tr>
                <th>#</th>
                <th>Department</th>
                <th>Section</th>
                <th>Description</th>
                <th>Risk Level</th>
                <th>Approve</th>
                <th>Reject</th>
            </tr>
        </thead>
        <tbody>
            <?php $i=1; ?>
            @foreach($pendingUpdate as $entry)
                <tr data-pending-id="{{ $entry->id }}" class="pending-entry">
                    <td>{{ $i++ }}</td>

                    @if(isset($entry->to->department))
                        <td bgcolor="pink">{{ $entry->to->department }}</td>
                    @else
                        <td>{{ $entry->from->department }}</td>
                    @endif

                    @if(isset($entry->to->section))
                        <td bgcolor="pink">{{ $entry->to->section }}</td>
                    @else
                        <td>{{ $entry->from->section }}</td>
                    @endif

                    @if(isset($entry->to->description))
                        <td bgcolor="pink">{!! nl2br($entry->to->description) !!}</td>
                    @else
                        <td>{!! nl2br($entry->from->description) !!}</td>
                    @endif

                    @if(isset($entry->to->riskLevel))
                        <td bgcolor="pink">{{ $entry->to->riskLevel }}</td>
                    @else
                        <td>{{ $entry->from->riskLevel }}</td>
                    @endif

                    <td>
                        <button class="btn btn-primary btn-xs approve-pending">
                            <span class="glyphicon glyphicon-ok"></span>
                        </button>
                    </td>
                    <td>
                        <button class="btn btn-danger btn-xs removing-pending">
                            <span class="glyphicon glyphicon-remove"></span>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="table table-hover">
        <thead>
            <tr>
                <th class="text-center" bgcolor="grey" colspan="7">Deleted Occupational Risk Pending for Check</th>
            </tr>
            <tr>
                <th>#</th>
                <th>Department</th>
                <th>Section</th>
                <th>Description</th>
                <th>Risk Level</th>
                <th>Approve</th>
                <th>Reject</th>
            </tr>
        </thead>
        <tbody>
            <?php $i=1; ?>
            @foreach($pendingRemove as $entry)
                <tr data-pending-id="{{ $entry->id }}" class="pending-entry">
                    <td>{{ $i++ }}</td>
                    <td>{{ $entry->from->department }}</td>
                    <td>{{ $entry->from->section }}</td>
                    <td>{!! nl2br($entry->from->description) !!}</td>
                    <td>{{ $entry->from->riskLevel }}</td>
                    <td>
                        <button class="btn btn-primary btn-xs approve-pending">
                            <span class="glyphicon glyphicon-ok"></span>
                        </button>
                    </td>
                    <td>
                        <button class="btn btn-danger btn-xs removing-pending">
                            <span class="glyphicon glyphicon-remove"></span>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <br>

    @if($pendingAdd->count() !=0 || $pendingUpdate->count()!=0 || $pendingRemove->count() != 0)
        <div class="col-md-12">
            <div class="col-md-2"></div>
            <div class="col-md-3">
                <button class="btn btn-primary btn-lg" id="approve-all">
                    Approve All
                </button>
            </div>
            <div class="col-md-2"></div>
            <div class="col-md-3">
                <button class="btn btn-danger btn-lg" id="reject-all">
                    Reject All
                </button>
            </div>
            <div class="col-md-2"></div>
        </div>
    @endif
@endsection


@section('javascriptContent')
<script type="text/javascript">
    $(document).ready(function () {
        $('button.approve-pending').click(function () {
            var tr = $(this).parents('tr');

            $.ajax({
                headers: headers,
                url: "{{ route('OccupationalRiskCheckerApprove') }}",
                data: {pendingid: tr.data('pending-id')},
                type: 'POST',
                success: function (data) {
                    handleReturn(data, function () {
                        tr.hide();
                    });
                }
            });
        });

        $('button.removing-pending').click(function () {
            var tr = $(this).parents('tr');

            $.ajax({
                headers: headers,
                url: "{{ route('OccupationalRiskCheckerReject') }}",
                data: {pendingid: tr.data('pending-id')},
                type: 'POST',
                success: function (data) {
                    handleReturn(data, function () {
                        tr.hide();
                    });
                }
            });
        });

        $('#approve-all').click(function () {
            $.ajax({
                headers: headers,
                url: "{{ route('OccupationalApproveAll') }}",
                type: 'post',
                success: function(data) {
                    handleReturn(data, function () {
                        $('tr.pending-entry').hide();
                    });
                }
            });
        });

        $('#reject-all').click(function () {
            $.ajax({
                headers: headers,
                url: "{{ route('OccupationalRejectAll') }}",
                type: 'post',
                success: function(data) {
                    handleReturn(data, function () {
                        $('tr.pending-entry').hide();
                    });
                }
            });
        });
    });
</script>
@endsection