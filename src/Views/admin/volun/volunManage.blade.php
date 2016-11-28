<!-- resources/views/vendor/openpolice/admin/volun/volunManage.blade.php -->

@extends('vendor.survloop.admin.admin')

@section('content')

<div class="row">
	<div class="col-md-6 taL">
		<h1><i class="fa fa-users"></i> Manage User Privileges</h1>
	</div>
	<div class="col-md-6 taR p20">
		<a href="/dashboard/volun/stars" class="btn btn-default">Back To Volunteer Stats</i></a>
	</div>
</div>

<div class="well">
	<b>Adding A User:</b> Use the public volunteer sign up form 
	(<a href="/auth/register" target="_blank">/auth/register</a>, while logged out, easiest in a separate browser) 
	to first create the new user. Then reload this page and change their privileges here as needed.
</div>

<table class="table table-striped">
<form name="manageUserRoles" action="/dashboard/volun/users" method="post">
<input type="hidden" name="_token" value="{{ csrf_token() }}">

<tr>
	<th>Name</th>
	<th class="taC">Volunteer</th>
	<th class="taC">Evaluators</th>
	<th class="taC">Brancher</th>
	<th class="taC">Databaser</th>
	<th class="taC">Admin</th>
	<th>Email</th>
</tr>

@foreach ($printVoluns as $userSet)
	@forelse ($userSet as $volun)
		<tr>
			<td><b>{!! $volun->printUsername(true, '/dashboard/volun/user/') !!}</b></td>
			<td class="taC"><input type="checkbox" name="user{{ $volun->id }}[]" value="volunteer" @if ($volun->hasRole('volunteer')) CHECKED @endif ></td>
			<td class="taC"><input type="checkbox" name="user{{ $volun->id }}[]" value="staff" {{ $disableAdmin }} @if ($volun->hasRole('staff')) CHECKED @endif ></td>
			<td class="taC"><input type="checkbox" name="user{{ $volun->id }}[]" value="brancher" {{ $disableAdmin }} @if ($volun->hasRole('brancher')) CHECKED @endif ></td>
			<td class="taC"><input type="checkbox" name="user{{ $volun->id }}[]" value="databaser" {{ $disableAdmin }} @if ($volun->hasRole('databaser')) CHECKED @endif ></td>
			<td class="taC"><input type="checkbox" name="user{{ $volun->id }}[]" value="administrator" {{ $disableAdmin }} @if ($volun->hasRole('administrator')) CHECKED @endif ></td>
			<td><a href="mailto:{{ $volun->email }}">{{ str_replace('@', ' @', $volun->email) }}</a></td>
		</tr>
	@empty
	@endforelse
@endforeach

</table><br />
<center><input type="submit" value=" Save All Changes " class="btn btn-lg btn-primary f30"></center>
</form>

<div class="adminFootBuff"></div>

@endsection