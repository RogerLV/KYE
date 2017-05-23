@extends('layouts.app')


@section('HTMLContent')
    <table>
        <tbody>
            <tr>
                <td><h5>Employ No:</h5></td>
                <td><h5>&nbsp;{{ $staff->employNo }}</h5></td>
            </tr>
            <tr>
                <td><h5>Name:</h5></td>
                <td><h5>&nbsp;{{ $staff->uEngName }}</h5></td>
            </tr>
            <tr>
                <td><h5>Department:</h5></td>
                <td><h5>&nbsp;{{ $staff->department }}</h5></td>
            </tr>
            <tr>
                <td><h5>Section:</h5></td>
                <td><h5>&nbsp;{{ $staff->section }}</h5></td>
            </tr>
            <tr>
                <td><h5>Join Date:</h5></td>
                <td><h5>&nbsp;{{ $staff->joinDate }}</h5></td>
            </tr>
            <tr>
                <td><h5>Leave Date:</h5></td>
                <td><h5>&nbsp;{{ $staff->leaveDate or 'In Service' }}</h5></td>
            </tr>
        </tbody>
    </table>
    <br>

    @if($editable)
        <button class="btn btn-primary btn-block" onclick=window.open('{{ route('KYECaseCreate') }}')>
            File One KYE Case
        </button>
    @endif
@endsection


@section('javascriptContent')

@endsection