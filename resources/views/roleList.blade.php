@extends('layouts.app')


@section('HTMLContent')
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <td>#</td>
                <td>Name</td>
                <td>Ext.</td>
                <td>Role</td>
                @if($editable)
                    <td>Delete</td>
                @endif
            </tr>
        </thead>
        <tbody>
            <?php $i=1 ?>
            @foreach($allUserRoles as $userRoleIns)
                <tr data-mapid="{{ $userRoleIns->mapid }}">
                    <td>{{ $i++ }}</td>
                    <td>{{ $userRoleIns->user->getDualName() }}</td>
                    <td>{{ $userRoleIns->user->IpPhone }}</td>
                    <td>{{ $userRoleIns->enName }}</td>
                    @if($editable)
                        <td>
                            <button class="btn btn-danger btn-xs" data-toggle="modal"
                                    data-target="#remove-modal">
                                <span class="glyphicon glyphicon-remove"></span>
                            </button>
                        </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
    <br>

    @if($editable)
        <button class="btn btn-primary btn-block" data-toggle="modal" data-target="#add-modal">
            Add User
        </button>
        
        <div id="remove-modal" class="modal fade">
            <div class="modal-dialog modal-sm">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Confirm to Delete?</h4>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" id="remove-map-button">Delete</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>

        <div id="add-modal" class="modal fade">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Add User</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label">Select Role</label>
                            <select id="select-role" class="form-control">
                                <option selected></option>
                                @foreach($roleOptions as $roleIns)
                                    <option value="{{ $roleIns->id }}">
                                        {{ $roleIns->enName }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Select User</label>
                            <select id="select-user" class="form-control">
                                <option selected></option>
                                @foreach($candidates as $candidate)
                                    <option value="{{ $candidate->lanID }}">
                                        {{ $candidate->getDualName() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal" id="add-button">Add</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Closs</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection


@section('javascriptContent')
    <script type="text/javascript">
        $(document).ready(function () {

            var mapid;

            $('#remove-modal').on('show.bs.modal', function (e) {
                var button = $(e.relatedTarget);
                mapid = button.parents('tr').data('mapid');
            });

            $('#remove-map-button').click(function() {
                $.ajax({
                    headers: headers,
                    url: "{{ route('RoleRemove') }}",
                    data: {mapid: mapid},
                    type: 'POST',
                    beforeSend: function() {
                        setAlertText('正在删除');
                        $('#alert-modal').modal('show');
                    },
                    success: function (data) {
                        $('#alert-modal').modal('hide');
                        handleReturn(data, function () {
                            $('tr[data-mapid="'+mapid+'"]').hide();
                        });
                    }
                });
            });

            $('#add-button').click(function () {
                var roleid = $('#select-role').val();
                var lanid = $('#select-user').val();

                if (0 == roleid.length || 0 == lanid.length) {
                    setAlertText('Role and User are mandatory');
                    $('#alert-modal').modal('show');
                    return;
                }

                $.ajax({
                    headers: headers,
                    url: "{{ route('RoleAdd') }}",
                    data: {
                        roleid: roleid,
                        lanid: lanid
                    },
                    type: 'POST',
                    beforeSend: function () {
                        setAlertText('正在添加');
                        $('#alert-modal').modal('show');
                    },
                    success: function (data) {
                        $('#alert-modal').modal('hide');
                        handleReturn(data);
                    }
                });
            });
        });
    </script>
@endsection