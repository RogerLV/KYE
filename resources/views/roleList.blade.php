@extends('layouts.app')


@section('HTMLContent')
	<table class="table table-striped table-hover">
		<thead>
			<tr>
				<td>#</td>
				<td>Name</td>
				<td>Ext.</td>
				<td>Role</td>
			</tr>
		</thead>
		<tbody>
			<?php $i=1 ?>
			@foreach($allUserRoles as $userRoleIns)
				<tr>
					<td>{{ $i++ }}</td>
					<td>{{ $userRoleIns->user->getDualName() }}</td>
					<td>{{ $userRoleIns->user->IpPhone }}</td>
					<td>{{ $userRoleIns->enName }}</td>
				</tr>
			@endforeach
		</tbody>
	</table>
@endsection


@section('javascriptContent')

@endsection