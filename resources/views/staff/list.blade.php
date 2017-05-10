@extends('layouts.app')


@section('head')
    @if($editable)
        {{-- jQuery UI Dependencies--}}
        <link rel="stylesheet" href="{{ ASSET_DIR.'css/jquery-ui.min.css' }}">
        <script type="text/javascript" src="{{ ASSET_DIR.'js/jquery-ui.min.js' }}"></script>
    @endif
@endsection


@section('HTMLContent')
    @if($editable)

        @include('staff.batch')

        @include('staff.add')

    @endif

    <h4>Select Department</h4>
    <select class="form-control" id="select-dept">
        <option selected value="all">Show all</option>
        @foreach($deptOptions as $deptOption)
            <option value="{{ $deptOption->department }}">{{ $deptOption->department }}</option>
        @endforeach
    </select>
    <br>

    <table class="table table-hover table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Employ No</th>
                <th>Name</th>
                <th>Department</th>
                <th>Section</th>
                <th>Join Date</th>
                @if($editable)
                    <th>Edit</th>
                    <th>Remove</th>
                @endif
            </tr>
        </thead>
        <tbody id="staff-list-body">
            <?php $i=1; ?>
            @foreach($staff as $staffEntry)
                <tr class="staff-entry" data-dept="{{ $staffEntry->department }}" 
                    data-employ-no="{{ $staffEntry->employNo }}">
                    <td>{{ $i++ }}</td>
                    <td class="employ-no">{{ $staffEntry->employNo }}</td>
                    <td class="employ-name">{{ $staffEntry->uEngName }}</td>
                    <td class="department">{{ $staffEntry->department }}</td>
                    <td class="section">{{ $staffEntry->section }}</td>
                    <td class="join-date">{{ $staffEntry->joinDate }}</td>
                    @if($editable)
                        <td>
                            <button type="button" class="btn btn-info btn-xs"
                                    data-toggle="modal" data-target="#edit-staff-modal">
                                <span class="glyphicon glyphicon-edit"></span>
                            </button>
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btn-xs" 
                                    data-toggle="modal" data-target="#remove-staff-modal">
                                <span class="glyphicon glyphicon-remove"></span>
                            </button>
                        </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $staff->links() }}

    @if($editable)

        @include('staff.remove')

        @include('staff.edit')

    @endif
@endsection


@section('javascriptContent')
<script type="text/javascript">
    $(document).ready(function () {

        $("#select-dept").change(function () {
            location.href = "{{ route('StaffList') }}"+'/'+$(this).val();
        });

        @if($editable)

            $(".datepicker").datepicker({
                dateFormat: "yy-mm-dd"
            });

        @endif
    });
</script>
@endsection