@extends('layouts.app')


@section('HTMLContent')
    <p style="color:red"><i>* File uploaded must be csv formatted.</i></p>
    <div class="form-group">
        <label>Upload Occupational Risk List:</label>
        <input type="file" class="file-input" id="upload-occupation-list" name="upload-occupation-list">
    </div>

    <table class="table table-hover">
        <thead>
            <tr>
                <th class="text-center" bgcolor="grey" colspan="6">New Occupational Risk Pending for Check</th>
            </tr>
            <tr>
                <th>#</th>
                <th>Department</th>
                <th>Section</th>
                <th>Description</th>
                <th>Risk Level</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php $i=1; ?>
            @foreach($pendingAdd as $entry)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $entry->to->department }}</td>
                    <td>{{ $entry->to->section }}</td>
                    <td>{!! nl2br($entry->to->description) !!}</td>
                    <td>{{ $entry->to->riskLevel }}</td>
                    <td>
                        <button class="btn btn-danger btn-xs removing-pending" data-pending-id="{{ $entry->id }}">
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
                <th class="text-center" bgcolor="grey" colspan="6">Updated Occupational Risk Pending for Check</th>
            </tr>
            <tr>
                <th>#</th>
                <th>Department</th>
                <th>Section</th>
                <th>Description</th>
                <th>Risk Level</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php $i=1; ?>
            @foreach($pendingUpdate as $entry)
                <tr>
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
                        <button class="btn btn-danger btn-xs removing-pending" data-pending-id="{{ $entry->id }}">
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
                <th class="text-center" bgcolor="grey" colspan="6">Deleted Occupational Risk Pending for Check</th>
            </tr>
            <tr>
                <th>#</th>
                <th>Department</th>
                <th>Section</th>
                <th>Description</th>
                <th>Risk Level</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php $i=1; ?>
            @foreach($pendingRemove as $entry)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $entry->from->department }}</td>
                    <td>{{ $entry->from->section }}</td>
                    <td>{!! nl2br($entry->from->description) !!}</td>
                    <td>{{ $entry->from->riskLevel }}</td>
                    <td>
                        <button class="btn btn-danger btn-xs removing-pending" data-pending-id="{{ $entry->id }}">
                            <span class="glyphicon glyphicon-remove"></span>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection


@section('javascriptContent')
<script type="text/javascript">
    $(document).ready(function () {
        $('#upload-occupation-list').fileinput({
            uploadUrl: "{{ route('OccupationalBatchReceive') }}",
            dropZoneEnabled: false,
            maxFileCount: 1,
            uploadExtraData: {
                _token: $("meta[name='csrf-token']").attr('content')
            }
        }).on('fileuploaded', function (event, data) {
            handleReturn(data.response);
        });

        $('button.removing-pending').click(function () {
            var button = $(this);
            $.ajax({
                headers: headers,
                url: "{{ route('OccupationalRiskDeletePending') }}",
                data: {pendingid: $(this).data('pending-id')},
                type: 'POST',
                success: function (data) {
                    handleReturn(data, function () {
                        button.parents('tr').hide();
                    });
                }
            });
        });
    });
</script>
@endsection