@extends('layouts.app')


@section('CSSContent')
<style type="text/css">
    button.page-link {
        height: 80px;
        width: 140px;
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

    <!-- Alert zone -->
    @if($isChecker)

        @if($alertInfo['hasPendingRisk'])
            <h4>You have occupational risk to be checked.</h4>
            <h4><a href="{{ route('OccupationalRiskChecker') }}" target="_blank">Click here to operate.</a></h4>
        @else
            <h4>You have no occupational risk to be checked.</h4>
        @endif
        <br>

        @if($alertInfo['hasPendingKYE'])
            <h4>You have KYE cases to be checked.</h4>
            <h4><a href="{{ route('KYECaseListPending') }}" target="_blank">Click here to operate.</a></h4>
        @else
            <h4>You have no KYE case to be checked.</h4>
        @endif
        <br>

    @endif

    @if($isMaker)

        @if(count($alertInfo['expired']))
            <h4>The following staff KYE info has expired:</h4>
            <table class="table table-bordered">
                @foreach($alertInfo['expired'] as $idx => $entry)
                    @if($idx % 3 == 0)
                        <tr>
                    @endif
                        <td>
                            <a href="{{ route('StaffInfo', ['empNo' => $entry->employNo]) }}" target="_blank">
                                {{ $entry->employNo }}&nbsp;{{ $entry->uEngName }}
                                </a>
                        </td>
                        <td>{{ \App\Logic\Util::getElapsedTimeString($entry->lastConduct) }}</td>
                    @if($idx % 3 == 2)
                        </tr>
                    @endif
                @endforeach
            </table>
            <br>
        @endif

        @if(count($alertInfo['withinThisWeek']))
            <h4>Filing KYE for below staff is required by this week:</h4>
            <table class="table table-bordered">
                @foreach($alertInfo['withinThisWeek'] as $idx => $entry)
                    @if($idx % 3 == 0)
                        <tr>
                    @endif
                        <td>
                            <a href="{{ route('StaffInfo', ['empNo' => $entry->employNo]) }}" target="_blank">
                                {{ $entry->employNo }}&nbsp;{{ $entry->uEngName }}
                                </a>
                        </td>
                        <td>{{ \App\Logic\Util::getElapsedTimeString($entry->lastConduct) }}</td>
                    @if($idx % 3 == 2)
                        </tr>
                    @endif
                @endforeach
            </table>
            <br>
        @endif

        @if(count($alertInfo['withinThisMonth']))
            <h4>Filing KYE for below staff is required by this month:</h4>
            <table class="table table-bordered">
                @foreach($alertInfo['withinThisMonth'] as $idx => $entry)
                    @if($idx % 3 == 0)
                        <tr>
                    @endif
                        <td>
                            <a href="{{ route('StaffInfo', ['empNo' => $entry->employNo]) }}" target="_blank">
                                {{ $entry->employNo }}&nbsp;{{ $entry->uEngName }}
                                </a>
                        </td>
                        <td>{{ \App\Logic\Util::getElapsedTimeString($entry->lastConduct) }}</td>
                    @if($idx % 3 == 2)
                        </tr>
                    @endif
                @endforeach
            </table>
            <br>
        @endif

    @endif

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