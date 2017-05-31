@extends('layouts.app')


@section('HTMLContent')
	@foreach($logs as $log)
		<div class="alert alert-warning">
			<a href="{{ route('DocErrorLog', ['logName' => $log->getFileName()]) }}" target="_blank">
				{{ $log->getFileName() }}
			</a>
		</div>
	@endforeach
@endsection