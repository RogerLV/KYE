@extends('layouts.app')


@section('HTMLContent')
    <table class="table table-stripe table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>EmployNo</th>
                <th>Name</th>
                <th>Overall Risk</th>
                <th>Conducted at</th>

                @if($isMaker)
                    <th>Delete</th>
                @endif

                @if($isChecker)
                    <th>Checker</th>
                @endif
            </tr>
        </thead>
        <tbody>
            <?php $i=1 ?>
            @foreach($entries as $entry)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $entry->to->employNo }}</td>
                    <td>{{ $entry->to->name }}</td>
                    <td>{{ $entry->to->overallRisk }}</td>
                    <td>{{ $entry->created_at}}</td>

                    @if($isMaker)
                        <td>
                            <button class="btn btn-danger btn-xs delete-pending" data-entry-id='{{ $entry->id }}'>
                                <span class="glyphicon glyphicon-remove"></span>
                            </button>
                        </td>
                    @endif

                    @if($isChecker)
                        <td>
                            @if($userLanID == $entry->madeBy)
                                <button class="btn btn-primary btn-xs" disabled>Check</button>
                            @else
                                <button class="btn btn-primary btn-xs">Check</button>
                            @endif
                        </td>
                    @endif

                </tr>
            @endforeach
        </tbody>
    </table>
@endsection


@section('javascriptContent')
<script type="text/javascript">
    $(document).ready(function () {
        $('button.delete-pending').click(function () {
            var tr = $(this).parents('tr');
            $.ajax({
                headers: headers,
                url: "{{ route('KYECaseDelete') }}",
                data: {entryid: $(this).data('entry-id')},
                type: 'POST',
                success: function (data) {
                    handleReturn(data, function () {
                        tr.hide();
                    });
                }
            });
        });
    });
</script>
@endsection