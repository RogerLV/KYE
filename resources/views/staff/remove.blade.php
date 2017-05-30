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

<script type="text/javascript">
    $(document).ready(function () {
        $('#remove-staff-modal').on('show.bs.modal', function (event) {
            var tr = $(event.relatedTarget).parents('tr');
            var employNo = tr.find("td.employ-no").find('a').text();
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

            if (0 == leaveDate.length) {
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
    });
</script>