@extends('layouts.app')


@section('head')
    @if($editable)
        {{-- jQuery UI Dependencies--}}
        <link rel="stylesheet" href="{{ ASSET_DIR.'css/jquery-ui.min.css' }}">
        <script type="text/javascript" src="{{ ASSET_DIR.'js/jquery-ui.min.js' }}"></script>
    @endif

    <style type="text/css">
        .ui-autocomplete { z-index:2147483647; }
    </style>
@endsection


@section('HTMLContent')
    @if($editable)
        <h6>
            <a href="{{ route('OccupationalRiskMaker') }}" target="_blank">
                <u>View List Pending for Check</u>
            </a>
        </h6>
        <br>
    @endif

    @if($canCheck)
        <h6>
            <a href="{{ route('OccupationalRiskChecker') }}" target="_blank">
                <u>View List Pending for Check</u>
            </a>
        </h6>
        <br>
    @endif

    <h4>Select Department</h4>
    <select class="form-control" id="select-dept">
        <option selected value="all">Show all</option>
        @foreach($deptOptions as $deptOption)
            <option value="{{ $deptOption->department }}" 
                    {{ $selectedDept == $deptOption->department ? "selected" : "" }}>
                {{ $deptOption->department }}
            </option>
        @endforeach
    </select>
    <br>

    <table class="table table-hover table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Department</th>
                <th>Section</th>
                <th>Description</th>
                <th>Risk Level</th>
                @if($editable)
                    <th>
                        Edit
                    </th>
                @endif
            </tr>
        </thead>
        <tbody>
            <?php $i=1; ?>
            @foreach($entries as $entry)
                <tr data-entry-id="{{ $entry->id }}">
                    <td>{{ $i++ }}</td>
                    <td class="department">{{ $entry->department }}</td>
                    <td class="section">{{ $entry->section }}</td>
                    <td class="description">{!! nl2br($entry->description) !!}</td>
                    <td class="risk-level">{{ $entry->riskLevel }}</td>
                    @if($editable)
                        <td>
                            <button class="btn btn-info btn-xs" data-toggle='modal' 
                                    data-target="#edit-occupational-risk-modal">
                                <span class="glyphicon glyphicon-edit"></span>
                            </button>
                        </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>

    @if($editable)
        
        @include('occupational.add')

        @include('occupational.edit')

    @endif

@endsection


@section('javascriptContent')
<script type="text/javascript">
    $(document).ready(function () {
        $("#select-dept").change(function () {
            location.href = "{{ route('OccupationalRiskList') }}"+'/'+$(this).val();
        });
    });

    @if($editable)
        var deptOption = JSON.parse("{{ $deptOptions->pluck('department')->toJson() }}".replace(/&quot;/g,'"'));
        var entryid;

        $('input.occupational-risk-dept').autocomplete({
            source: deptOption
        });

    @endif
</script>
@endsection