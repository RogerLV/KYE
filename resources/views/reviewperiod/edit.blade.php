@extends('layouts.app')


@section('HTMLContent')
    <table class="table">
        <tbody>
            <tr>
                <th class="text-center" colspan="4">Occupational Risk</th>
            </tr>
            <tr data-category='OccupationalRisk'>
                <th>High:</th>
                <td data-level='high'>
                    <select class="risk-level">
                        @foreach($options as $option)
                            <option value="{{ $option }}" 
                                    <?php if($option == $reviewPeriods['OccupationalRisk']['high']) echo "selected"; ?>>
                                    {{ $option }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <th>Low:</th>
                <td data-level='low'>
                    <select class="risk-level">
                        @foreach($options as $option)
                            <option value="{{ $option }}" 
                                    <?php if($option == $reviewPeriods['OccupationalRisk']['low']) echo "selected"; ?>>
                                    {{ $option }}
                            </option>
                        @endforeach
                    </select>
                </td>
            </tr>
            <tr><td colspan="4"></td></tr>
        </tbody>
    </table>
    <br><br>

    <table class="table">
        <tbody>
            <tr>
                <th class="text-center" colspan="4">Relationship Risk</th>
            </tr>
            <tr data-category='RelationshipRisk'>
                <th>High:</th>
                <td data-level='high'>
                    <select class="risk-level">
                        @foreach($options as $option)
                            <option value="{{ $option }}" 
                                    <?php if($option == $reviewPeriods['RelationshipRisk']['high']) echo "selected"; ?>>
                                    {{ $option }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <th>Low:</th>
                <td data-level='low'>
                    <select class="risk-level">
                        @foreach($options as $option)
                            <option value="{{ $option }}" 
                                    <?php if($option == $reviewPeriods['RelationshipRisk']['low']) echo "selected"; ?>>
                                    {{ $option }}
                            </option>
                        @endforeach
                    </select>
                </td>
            </tr>
            <tr><td colspan="4"></td></tr>
        </tbody>
    </table>
    <br><br>

    <table class="table">
        <tbody>
            <tr>
                <th class="text-center" colspan="4">Special Factors Risk</th>
            </tr>
            <tr data-category='SpecialFactorRisk'>
                <th>High:</th>
                <td data-level='high'>
                    <select class="risk-level">
                        @foreach($options as $option)
                            <option value="{{ $option }}" 
                                    <?php if($option == $reviewPeriods['SpecialFactorRisk']['high']) echo "selected"; ?>>
                                    {{ $option }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <th>Low:</th>
                <td data-level='low'>
                    <select class="risk-level">
                        @foreach($options as $option)
                            <option value="{{ $option }}" 
                                    <?php if($option == $reviewPeriods['SpecialFactorRisk']['low']) echo "selected"; ?>>
                                    {{ $option }}
                            </option>
                        @endforeach
                    </select>
                </td>
            </tr>
            <tr><td colspan="4"></td></tr>
        </tbody>
    </table>
    <br><br>
@endsection


@section('javascriptContent')
<script type="text/javascript">
    $(document).ready(function () {
        $('select.risk-level').change(function () {
            var td = $(this).parent();
            var data = {
                category: td.parent().data('category'),
                level:td.data('level'),
                risk: $(this).val()
            };

            $.ajax({
                headers: headers,
                url: "{{ route('ReviewPeriodUpdate') }}",
                data: data,
                type: 'POST',
                success: function (data) {
                    handleReturn(data, function () {
                        setAlertText("Update Success.");
                        $("#alert-modal").modal('show');
                    });
                }
            });
        });
    });
</script>
@endsection