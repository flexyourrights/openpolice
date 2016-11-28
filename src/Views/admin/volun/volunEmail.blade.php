<!-- resources/views/vendor/openpolice/admin/volun/volunEmail.blade.php -->

@extends('vendor.survloop.admin.admin')

@section('content')

<div class="row">
	<div class="col-md-6 taL">
		<h1><i class="fa fa-users"></i> Email Users</h1>
	</div>
	<div class="col-md-6 taR p20">
		<a href="/dashboard/volun/stars" class="btn btn-default">Back To Volunteer Stats</i></a>
	</div>
</div>

<table class="table table-striped">

@foreach ($printVoluns as $i => $userSet)
	<tr><th>
		@if ($i == 0) Administrators
		@elseif ($i == 1) Databasers
		@elseif ($i == 2) Branchers
		@elseif ($i == 3) Evaluators
		@elseif ($i == 4) Volunteers
		@endif
	</th><td>
	@forelse ($userSet as $volun)
		<a href="mailto:{{ $volun->email }}">{{ $volun->email }}</a>, 
	@empty
	@endforelse
	</td></tr>
@endforeach
</table>

<div class="adminFootBuff"></div>

@endsection