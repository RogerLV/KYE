@extends('layouts.app')


@section('CSSContent')
<style type="text/css">
    button.page-link {
        height: 80px;
        width: 120px;
        border-radius: 10px;
        border: 1px solid #bce8f1;
        background-color: #d9edf7;
    }

    button.page-link > .glyphicon {
        font-size: 40px;
        color: #31708f;
    }
</style>
@endsection


@section('HTMLContent')

    <h3>{{ $userInfo->uEngName }} {{ $userInfo->uCnName }}</h3>
    <br>

    <h4>请选择您在系统中的角色:</h4>
        <div class="list-group">
        @foreach($roleList as $roleIns)
            @if($roleIns->id == $selectedMapID)
                <a href="#" class="list-group-item active">
            @else
                <a href="#" class="list-group-item role-selection" data-mapid="{{ $roleIns->id }}">
            @endif
                {{ $roleIns->role->enName }}
            </a>
        @endforeach
        </div>
    <br>

    <h4>您可以访问以下页面:</h4>
    <br>
    @foreach($pages as $pageIns)
        <button target="_blank" class="page-link" onclick="window.open('{{ route($pageIns->name) }}')">
            <span class="{{ $pageIns->icon }}"></span><br>
            {{ $pageIns->title }}
        </button>&nbsp;
    @endforeach

@endsection


@section('javascriptContent')
<script type="text/javascript">
    $(document).ready(function () {
        $('.role-selection').click(function () {
            var mapid = $(this).data('mapid');

            $.ajax({
                headers: headers,
                url: "{{ route('RoleSelect') }}",
                data: {'mapid': mapid},
                type: 'POST',
                beforeSend: function () {
                    setAlertText('正在切换');
                    $('#alert-modal').modal('show');
                },
                success: function (data) {
                    $('#alert-modal').modal('hide');
                    handleReturn(data, function () {
                        window.location.reload();
                    });
                }
            });
        });
    });
</script>
@endsection