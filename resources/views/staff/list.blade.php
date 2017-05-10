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
        <p style="color:red"><i>* File uploaded must be csv formatted.</i></p>
        <div class="form-group">
            <label>Upload Staff List:</label>
            <input type="file" class="file-input" id="upload-staff-list" name="upload-staff-list">
        </div>

        <div id="confirm-staff-modal" class="modal fade">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button class="close" type="button" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Add Staff</h4>
                    </div>
                    <div class="modal-body">
                        <h4>Staff to be Added</h4>
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <td>EmpNo</td>
                                    <td>Name</td>
                                    <td>Department</td>
                                    <td>Section</td>
                                    <td>JoinDate</td>
                                </tr>
                            </thead>
                            <tbody id="add-staff-table-body">
                                
                            </tbody>
                        </table>

                        <h4>Staff to be Updated</h4>
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <td>EmpNo</td>
                                    <td>Name</td>
                                    <td>Department</td>
                                    <td>Section</td>
                                    <td>JoinDate</td>
                                </tr>
                            </thead>
                            <tbody id="update-staff-table-body">
                                
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal" id="confirm-staff-button">Confirm</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
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
        <tbody>
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

    @if($editable)
        <div id="remove-staff-modal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button class="close" type="button" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Remove Staff</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Employ No</label>
                            <input type="text" class="form-control employ-no-input" readonly>
                        </div>
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control employ-name-input" readonly>
                        </div>
                        <div class="form-group">
                            <label>Department</label>
                            <input type="text" class="form-control department-input" readonly>
                        </div>
                        <div class="form-group">
                            <label>Section</label>
                            <input type="text" class="form-control section-input" readonly>
                        </div>
                        <div class="form-group">
                            <label>Join Date</label>
                            <input type="text" class="form-control join-date-input" readonly>
                        </div>
                        <div class="form-group required">
                            <label for='leave-date'>Leave Date</label>
                            <input type="text" id="leave-date" class="form-control datepicker" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" 
                                data-dismiss="modal" id="remove-staff-button">Remove</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div id="edit-staff-modal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button class="close" type="button" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Edit Staff</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Employ No</label>
                            <input type="text" class="form-control employ-no-input" readonly>
                        </div>
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control employ-name-input">
                        </div>
                        <div class="form-group">
                            <label>Department</label>
                            <input type="text" class="form-control department-input">
                        </div>
                        <div class="form-group">
                            <label>Section</label>
                            <input type="text" class="form-control section-input">
                        </div>
                        <div class="form-group">
                            <label>Join Date</label>
                            <input type="text" class="form-control join-date-input datepicker">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" 
                                data-dismiss="modal" id="edit-staff-button">Complete</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection


@section('javascriptContent')
<script type="text/javascript">
    $(document).ready(function () {

        $("#select-dept").change(function () {
            var deptName = $(this).val();
            if ('all' == deptName) {
                $("tr.staff-entry").show();
            } else {
                $("tr.staff-entry").hide();
                $("tr.staff-entry[data-dept='"+deptName+"']").show();
            }
        });

        @if($editable)

            $(".datepicker").datepicker({
                dateFormat: "yy-mm-dd"
            });

            $('#remove-staff-modal, #edit-staff-modal').on('show.bs.modal', function (event) {
                var tr = $(event.relatedTarget).parents('tr');
                var employNo = tr.find("td.employ-no").text();
                var employName = tr.find("td.employ-name").text();
                var department = tr.find("td.department").text();
                var section = tr.find('td.section').text();
                var joinDate = tr.find("td.join-date").text();

                $(this).find("input.employ-no-input").val(employNo);
                $(this).find("input.employ-name-input").val(employName);
                $(this).find("input.department-input").val(department);
                $(this).find("input.section-input").val(section);
                $(this).find("input.join-date-input").val(joinDate);
            });

            $('#remove-staff-button').click(function () {
                var employNo = $('#remove-staff-modal').find('input.employ-no-input').val();
                var leaveDate = $('#leave-date').val();

                if (0 == leaveDate) {
                    setAlertText('Filling in leave date is mandatory.');
                    $('#alert-modal').modal('show');
                    return;
                }

                $.ajax({
                    headers: headers,
                    url: "{{ route('StaffRemove') }}",
                    data: {
                        employno: employNo,
                        leavedate: leaveDate
                    },
                    type: 'POST',
                    success: function (data) {
                        $("tr.staff-entry[data-employ-no='"+employNo+"']").hide();
                    }
                });
            });

            $('#edit-staff-button').click(function () {
                var modal = $('#edit-staff-modal');
                var employNo = modal.find('input.employ-no-input').val();
                var name = modal.find('input.employ-name-input').val();
                var department = modal.find('input.department-input').val();
                var section = modal.find('input.section-input').val();
                var joinDate = modal.find('input.join-date-input').val();

                $.ajax({
                    headers: headers,
                    url: "{{ route('StaffEdit') }}",
                    data: {
                        employno: employNo,
                        name: name,
                        department: department,
                        section: section,
                        joindate: joinDate
                    },
                    type: 'POST',
                    success: function (data) {
                        handleReturn(data, function () {
                            var tr = $("tr.staff-entry[data-employ-no='"+data.instance.employNo+"']");
                            tr.find('td.employ-name').text(data.instance.uEngName);
                            tr.find('td.department').text(data.instance.department);
                            tr.find('td.section').text(data.instance.section);
                            tr.find('td.join-date').text(data.instance.joinDate);
                        });
                    }
                });
            });

            var toBeUpdatedStaff = [];
            var tobeAddedStaff = [];

            $('#upload-staff-list').fileinput({
                uploadUrl: "{{ route('StaffUpload') }}",
                dropZoneEnabled: false,
                maxFileCount: 1,
                uploadExtraData: {
                    _token: $("meta[name='csrf-token']").attr('content')
                }
            });

            $('#upload-staff-list').on('fileuploaded', function (event, data) {
                handleReturn(data.response, function () {
                    $.each(data.response.toBeAdded, function (idx, val) {
                        var staffIns = {};
                        $('#add-staff-table-body').append("<tr>"+
                            "<td>"+(staffIns.empno = val[0])+"</td>"+
                            "<td>"+(staffIns.name = val[1])+"</td>"+
                            "<td>"+(staffIns.dept = val[2])+"</td>"+
                            "<td>"+(staffIns.section = val[3])+"</td>"+
                            "<td>"+(staffIns.joindate = val[4])+"</td>"+
                            "</tr>");
                        tobeAddedStaff.push(staffIns);
                    });

                    var staffAttr = {
                        1 : 'name',
                        2 : 'dept',
                        3 : 'section',
                        4 : 'joindate',
                    };

                    $.each(data.response.toBeUpdated, function (idx, val) {
                        var staffIns = {};
                        var tr = $('<tr/>').append($('<td/>', {
                            text: (staffIns.empno = val['instance'][0])
                        }));

                        $.each(staffAttr, function (key, attr) {
                            tr.append($("<td/>", {
                                bgcolor: val['updatedCols'][key] ? 'grey' : '',
                                text: (staffIns[attr] = val['instance'][key])
                            }));
                        });

                        $('#update-staff-table-body').append(tr);

                        toBeUpdatedStaff.push(staffIns);
                    });

                    $('#confirm-staff-modal').modal('show');
                });
            });

            $('#confirm-staff-button').click(function () {
                $.ajax({
                    headers:headers,
                    url: "{{ route('StaffConfirm') }}",
                    data: {
                        tobeaddedstaff: JSON.stringify(tobeAddedStaff),
                        tobeupdatedstaff: JSON.stringify(toBeUpdatedStaff)
                    },
                    type: "POST",
                    success: function (data) {
                        handleReturn(data);
                    }
                });
            });

            $('#confirm-staff-modal').on('hidden.bs.modal', function () {
                toBeUpdatedStaff = [];
                tobeAddedStaff = [];
            });
        @endif
    });
</script>
@endsection