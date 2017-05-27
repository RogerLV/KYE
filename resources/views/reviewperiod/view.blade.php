@extends('layouts.app')


@section('HTMLContent')
    <table class="table">
        <tbody>
            <tr>
                <th class="text-center" colspan="4">Occupational Risk</th>
            </tr>
            <tr>
                <th>High:</th>
                <td>{{ $reviewPeriods['OccupationalRisk']['high'] }}</td>
                <th>Low:</th>
                <td>{{ $reviewPeriods['OccupationalRisk']['low'] }}</td>
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
            <tr>
                <th>High:</th>
                <td>{{ $reviewPeriods['RelationshipRisk']['high'] }}</td>
                <th>Low:</th>
                <td>{{ $reviewPeriods['RelationshipRisk']['low'] }}</td>
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
            <tr>
                <th>High:</th>
                <td>{{ $reviewPeriods['SpecialFactorRisk']['high'] }}</td>
                <th>Low:</th>
                <td>{{ $reviewPeriods['SpecialFactorRisk']['low'] }}</td>
            </tr>
            <tr><td colspan="4"></td></tr>
        </tbody>
    </table>
    <br><br>

    @if($isAdmin)
        <button class="btn btn-primary btn-block"
                onclick=location.href="{{ route('ReviewPeriodEdit') }}">
            Edit
        </button>
    @endif
@endsection