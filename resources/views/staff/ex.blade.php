@extends('layouts.app')


@section('HTMLContent')

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
                <th>Employ No</th>
                <th>Name</th>
                <th>Department</th>
                <th>Section</th>
                <th>Join Date</th>
                <th>Leave Date</th>
            </tr>
        </thead>
        <tbody id="staff-list-body">
            <?php $i=1; ?>
            @foreach($staff as $staffEntry)
                <tr class="staff-entry" data-dept="{{ $staffEntry->department }}" 
                    data-employ-no="{{ $staffEntry->employNo }}">
                    <td>{{ $i++ }}</td>
                    <td>{{ $staffEntry->employNo }}</td>
                    <td>{{ $staffEntry->uEngName }}</td>
                    <td>{{ $staffEntry->department }}</td>
                    <td>{{ $staffEntry->section }}</td>
                    <td>{{ $staffEntry->joinDate }}</td>
                    <td>{{ $staffEntry->leaveDate }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $staff->links() }}

@endsection


@section('javascriptContent')
<script type="text/javascript">
    $(document).ready(function () {

        $("#select-dept").change(function () {
            location.href = "{{ route('StaffViewEx') }}"+'/'+$(this).val();
        });
    });
</script>
@endsection