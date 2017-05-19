<button class="btn btn-primary btn-block" data-toggle="modal" data-target="#add-occupational-risk-modal">
    Add New Entry
</button>
<br>

<div id="add-occupational-risk-modal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" type="button" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add New Occupational Risk Entry</h4>
            </div>
            <div class="modal-body">
                <div class="form-group required">
                    <label>Department</label>
                    <input type="text" class="form-control occupational-risk-dept">
                </div>
                <div class="form-group required">
                    <label>Section</label>
                    <input type="text" class="form-control occupational-risk-section">
                </div>
                <div class="form-group required">
                    <label>Description</label>
                    <textarea class="form-control occupational-risk-description"></textarea>
                </div>
                <div class="form-group required">
                    <label>Risk Level</label>
                    <select class="form-control occupational-risk-level">
                        <option disabled selected></option>
                        <option value="High">High</option>
                        <option value="Low">Low</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" 
                        data-dismiss="modal" id="add-occupational-risk-button">Add</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('#add-occupational-risk-button').click(function () {
            var modal = $(this).parents('div.modal-content');
            var department = modal.find('input.occupational-risk-dept').val();
            var section = modal.find('input.occupational-risk-section').val();
            var description = modal.find('textarea.occupational-risk-description').val();
            var riskLevel = modal.find('select.occupational-risk-level').val();

            if (0 == department.length || 0 == section.length 
                    || riskLevel == null || 0 == riskLevel.length) {
                setAlertText('All Fields are Mandatory.');
                $('#alert-modal').modal('show');
                return;
            }

            $.ajax({
                headers: headers,
                url: "{{ route('OccupationalRiskAdd') }}",
                data: {
                    department: department,
                    section: section,
                    description: description,
                    risklevel: riskLevel
                },
                type: "POST",
                success: function (data) {
                    handleReturn(data, function () {
                        setAlertText('Add operation is successful and pending to check.');
                        $('#alert-modal').modal('show');
                    });
                }
            });
        });
    });
</script>