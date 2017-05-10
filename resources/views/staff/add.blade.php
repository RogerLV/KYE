<button class="btn btn-primary btn-block" data-toggle="modal" data-target="#add-staff-modal">
    Add Staff Entry
</button>

<div id="add-staff-modal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" type="button" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Staff Entry</h4>
            </div>
            <div class="modal-body">
                <div class="form-group required">
                    <label>Employ No</label>
                    <input type="number" class="form-control employ-no-input">
                </div>
                <div class="form-group required">
                    <label>Name</label>
                    <input type="text" class="form-control employ-name-input">
                </div>
                <div class="form-group required">
                    <label>Department</label>
                    <input type="text" class="form-control department-input">
                </div>
                <div class="form-group required">
                    <label>Section</label>
                    <input type="text" class="form-control section-input">
                </div>
                <div class="form-group required">
                    <label>Join Date</label>
                    <input type="text" class="form-control join-date-input datepicker">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" 
                        data-dismiss="modal" id="add-staff-button">Add</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<br>

<script type="text/javascript">
    $(document).ready(function () {

        $('#add-staff-button').click(function () {
            var modal = $('#add-staff-modal');
            var employNo = modal.find('input.employ-no-input').val();
            var name = modal.find('input.employ-name-input').val();
            var department = modal.find('input.department-input').val();
            var section = modal.find('input.section-input').val();
            var joinDate = modal.find('input.join-date-input').val();

            if (0 == employNo.length 
                || 0 == name.length 
                || 0 == department.length
                || 0 == section.length
                || 0 == joinDate.length) {
                setAlertText('Please fill in mandatory fields.');
                $('#alert-modal').modal('show');
                return;
            }

            $.ajax({
                headers: headers,
                url: "{{ route('StaffAdd') }}",
                data: {
                    employno: employNo,
                    name: name,
                    department: department,
                    section: section,
                    joindate: joinDate
                },
                type: 'POST',
                success: function (data) {
                    handleReturn(data, function() {
                        $('#staff-list-body').prepend($('<tr/>', {
                            class: 'staff-entry',
                        }).data('dept', data.instance.department)
                        .data('employ-no', data.instance.employNo)
                        .append($('<td/>'))
                            .append($('<td/>', {
                                text: data.instance.employNo,
                                class: 'employ-no'
                            })).append($('<td/>', {
                                text: data.instance.uEngName,
                                class: 'employ-name'
                            })).append($('<td/>', {
                                text: data.instance.department,
                                class: 'department'
                            })).append($('<td/>', {
                                text: data.instance.section,
                                class: 'section'
                            })).append($('<td/>', {
                                text: data.instance.joinDate,
                                class: 'join-date'
                            })).append($('<td/>'))
                            .append($('<td/>'))
                        );

                        // remove modal fields
                        modal.find('input.employ-no-input').val('');
                        modal.find('input.employ-name-input').val('');
                        modal.find('input.department-input').val('');
                        modal.find('input.section-input').val('');
                        modal.find('input.join-date-input').val('');
                    });
                }
            });
        });
    });
</script>