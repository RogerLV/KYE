@extends('layouts.app')


@section('HTMLContent')
    <table class="table">
        <tbody>
            <tr>
                <th class="text-center" colspan="4">Occupational Risk</th>
            </tr>
            <tr>
                <th>High:</th>
                <td>
                    <select>
                        @foreach($options as $option)
                            <option value="{{ $option }}" 
                                    <?php if($option == $reviewPeriods['OccupationalRisk']['high']) echo "selected"; ?>>
                                    {{ $option }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <th>Low:</th>
                <td>
                    <select>
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
            <tr>
                <th>High:</th>
                <td>
                    <select>
                        @foreach($options as $option)
                            <option value="{{ $option }}" 
                                    <?php if($option == $reviewPeriods['RelationshipRisk']['high']) echo "selected"; ?>>
                                    {{ $option }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <th>Low:</th>
                <td>
                    <select>
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
            <tr>
                <th>High:</th>
                <td>
                    <select>
                        @foreach($options as $option)
                            <option value="{{ $option }}" 
                                    <?php if($option == $reviewPeriods['SpecialFactorRisk']['high']) echo "selected"; ?>>
                                    {{ $option }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <th>Low:</th>
                <td>
                    <select>
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

@endsection