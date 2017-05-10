<div class="well">
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
</div>
<br>


<script type="text/javascript">
    $(document).ready(function () {
        
        var toBeUpdatedStaff = [];
        var tobeAddedStaff = [];

        $('#upload-staff-list').fileinput({
            uploadUrl: "{{ route('StaffUpload') }}",
            dropZoneEnabled: false,
            maxFileCount: 1,
            uploadExtraData: {
                _token: $("meta[name='csrf-token']").attr('content')
            }
        }).on('fileuploaded', function (event, data) {
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
    });
</script>