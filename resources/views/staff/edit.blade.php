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


<script type="text/javascript">
    $(document).ready(function () {
        $('#edit-staff-modal').on('show.bs.modal', function (event) {
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
    });
</script>