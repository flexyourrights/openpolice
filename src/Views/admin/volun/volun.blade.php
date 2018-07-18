<!-- resources/views/admin/volun.blade.php -->

@extends('vendor.survloop.master')

@section('content')

<div class="row">
    <div class="col-md-6 taL">
        <h1><i class="fa fa-users"></i> Volunteers 
        <i class="gry9 f16">( {{ number_format(sizeof($printVoluns[0])) }} )</i></h1>
    </div>
    <div class="col-md-6 taR p20">
        <a href="/dashboard/users" class="btn btn-default">Manage Users</i></a>
        <a href="/dashboard/volun/email" class="btn btn-default">Email Users</i></a>
    </div>
</div>

<table class="table table-striped f18">
<tr>
    <th>Name</th>
    <th class="taC">Stars</th>
    <th class="taC f14">Average Time<br />Per Dept</th>
    <th class="taC f14">Online<br />Research</th>
    <th class="taC f14">Called<br />Department</th>
    <th class="taC f14">Called<br />Internal Affairs</th>
    <th class="taC f14">Unique<br />Departments</th>
    <th>State</th>
    <th>Email</th>
    <th>Phone</th>
</tr>

@forelse($printVoluns[0] as $i => $volun)
    <tr>
    <td class="f18 slBlueDark"><i class="f8 gry9">{{ (1+$i) }}</i> <b>{!! $volun[1]->printUsername(true, '/dashboard/volun/user/') !!}</b></td>
    <td class="taC">
        @if ($volun[0]->UserInfoStars > 0)
            <nobr><img src="/openpolice/star1.png" border=0 height=16 class="mTn5" > <b>{{ number_format($volun[0]->UserInfoStars) }}</b> <img src="/openpolice/star1.png" border=0 height=16 class="mTn5" ></nobr>
        @else
            <span class="gryC">0</span>
        @endif
    </td>
    <td class="taC @if ($volun[0]->UserInfoAvgTimeDept == 0) gryC @endif " ><nobr>
        @if (($volun[0]->UserInfoAvgTimeDept/60) < 10)
            {{ number_format($volun[0]->UserInfoAvgTimeDept/60, 1) }}
        @else
            {{ number_format($volun[0]->UserInfoAvgTimeDept/60, 0) }}
        @endif
        min</nobr></td>
    <td class="taC @if ($volun[0]->UserInfoStars1 == 0) gryC @endif ">{{ number_format($volun[0]->UserInfoStars1) }} @if ($volun[0]->UserInfoStars1 > 0) <i class="fa fa-laptop"></i> @endif </td>
    <td class="taC @if ($volun[0]->UserInfoStars2 == 0) gryC @endif ">{{ number_format($volun[0]->UserInfoStars2) }} @if ($volun[0]->UserInfoStars2 > 0) <i class="fa fa-phone"></i> @endif </td>
    <td class="taC @if ($volun[0]->UserInfoStars3 == 0) gryC @endif ">{{ number_format($volun[0]->UserInfoStars3) }} @if ($volun[0]->UserInfoStars3 > 0) <i class="fa fa-phone"></i> @endif </td>
    <td class="taC @if ($volun[0]->UserInfoDepts == 0) gryC @endif "><b>{{ number_format($volun[0]->UserInfoDepts) }}</b></td>
    <td> @if (isset($volun[2]->PrsnAddressState)) {{ $volun[2]->PrsnAddressState }} @else <span class="gryC">-</span> @endif </td>
    <td> @if (isset($volun[2]->PrsnEmail)) <a href="mailto:{{ $volun[2]->PrsnEmail }}" class="f12 lH13">{{ $volun[2]->PrsnEmail }}</a> @endif </td>
    <td> @if (isset($volun[2]->PrsnPhoneMobile)) {{ $volun[2]->PrsnPhoneMobile }} @endif </td>
    </tr>
@empty
    <tr><td colspan=8 >No stats found.</td></tr>
@endforelse

</table>
<h2>Evaluators <i class="gry9 f16">( {{ number_format(sizeof($printVoluns[1])) }} )</i></h2>
<table class="table table-striped">
<tr>
    <th>Name</th>
    <th class="taC">Stars</th>
    <th class="taC f14">Average Time<br />Per Dept</th>
    <th class="taC f14">Online<br />Research</th>
    <th class="taC f14">Called<br />Department</th>
    <th class="taC f14">Called<br />Internal Affairs</th>
    <th class="taC f14">Unique<br />Departments</th>
    <th>State</th>
    <th>Email</th>
    <th>Phone</th>
</tr>

@forelse($printVoluns[1] as $i => $volun)
    <tr>
    <td class="f18 slBlueDark"><b>{!! $volun[1]->printUsername(true, '/dashboard/volun/user/') !!}</b></td>
    <td class="taC">
        @if ($volun[0]->UserInfoStars > 0)
            <nobr><img src="/openpolice/star1.png" border=0 height=16 class="mTn5" > <b>{{ number_format($volun[0]->UserInfoStars) }}</b> <img src="/openpolice/star1.png" border=0 height=16 class="mTn5" ></nobr>
        @else
            <span class="gryC">0</span>
        @endif
    </td>
    <td class="taC @if ($volun[0]->UserInfoAvgTimeDept == 0) gryC @endif " ><nobr>
        @if (($volun[0]->UserInfoAvgTimeDept/60) < 10)
            {{ number_format($volun[0]->UserInfoAvgTimeDept/60, 1) }}
        @else
            {{ number_format($volun[0]->UserInfoAvgTimeDept/60, 0) }}
        @endif
        min</nobr></td>
    <td class="taC @if ($volun[0]->UserInfoStars1 == 0) gryC @endif ">{{ number_format($volun[0]->UserInfoStars1) }} 
        @if ($volun[0]->UserInfoStars1 > 0) <i class="fa fa-laptop"></i> @endif </td>
    <td class="taC @if ($volun[0]->UserInfoStars2 == 0) gryC @endif ">{{ number_format($volun[0]->UserInfoStars2) }} 
        @if ($volun[0]->UserInfoStars2 > 0) <i class="fa fa-phone"></i> @endif </td>
    <td class="taC @if ($volun[0]->UserInfoStars3 == 0) gryC @endif ">{{ number_format($volun[0]->UserInfoStars3) }} 
        @if ($volun[0]->UserInfoStars3 > 0) <i class="fa fa-phone"></i> @endif </td>
    <td class="taC @if ($volun[0]->UserInfoDepts == 0) gryC @endif ">
        <b>{{ number_format($volun[0]->UserInfoDepts) }}</b></td>
    <td> @if (isset($volun[2]->PrsnAddressState)) {{ $volun[2]->PrsnAddressState }} 
        @else <span class="gryC">-</span> @endif </td>
    <td> @if (isset($volun[2]->PrsnEmail)) <a href="mailto:{{ $volun[2]->PrsnEmail }}" class="f12 lH13"
        >{{ $volun[2]->PrsnEmail }}</a> @endif </td>
    <td> @if (isset($volun[2]->PrsnPhoneMobile)) {{ $volun[2]->PrsnPhoneMobile }} @endif </td>
    </tr>
@empty
    <tr><td colspan=8 >No stats found.</td></tr>
@endforelse

</table>
<h2>Admin <i class="gry9 f16">( {{ number_format(sizeof($printVoluns[2])) }} )</i></h2>
<table class="table table-striped">
<tr>
    <th>Name</th>
    <th class="taC">Stars</th>
    <th class="taC f14">Average Time<br />Per Dept</th>
    <th class="taC f14">Online<br />Research</th>
    <th class="taC f14">Called<br />Department</th>
    <th class="taC f14">Called<br />Internal Affairs</th>
    <th class="taC f14">Unique<br />Departments</th>
    <th>State</th>
    <th>Email</th>
    <th>Phone</th>
</tr>

@forelse($printVoluns[2] as $i => $volun)
    <tr>
    <td class="f18 slBlueDark"><b>{!! $volun[1]->printUsername(true, '/dashboard/volun/user/') !!}</b></td>
    <td class="taC">
        @if ($volun[0]->UserInfoStars > 0)
            <nobr><img src="/openpolice/star1.png" border=0 height=16 class="mTn5" > <b>{{ number_format($volun[0]->UserInfoStars) }}</b> <img src="/openpolice/star1.png" border=0 height=16 class="mTn5" ></nobr>
        @else
            <span class="gryC">0</span>
        @endif
    </td>
    <td class="taC @if ($volun[0]->UserInfoAvgTimeDept == 0) gryC @endif " ><nobr>
        @if (($volun[0]->UserInfoAvgTimeDept/60) < 10)
            {{ number_format($volun[0]->UserInfoAvgTimeDept/60, 1) }}
        @else
            {{ number_format($volun[0]->UserInfoAvgTimeDept/60, 0) }}
        @endif
        min</nobr></td>
    <td class="taC @if ($volun[0]->UserInfoStars1 == 0) gryC @endif ">{{ number_format($volun[0]->UserInfoStars1) }} @if ($volun[0]->UserInfoStars1 > 0) <i class="fa fa-laptop"></i> @endif </td>
    <td class="taC @if ($volun[0]->UserInfoStars2 == 0) gryC @endif ">{{ number_format($volun[0]->UserInfoStars2) }} @if ($volun[0]->UserInfoStars2 > 0) <i class="fa fa-phone"></i> @endif </td>
    <td class="taC @if ($volun[0]->UserInfoStars3 == 0) gryC @endif ">{{ number_format($volun[0]->UserInfoStars3) }} @if ($volun[0]->UserInfoStars3 > 0) <i class="fa fa-phone"></i> @endif </td>
    <td class="taC @if ($volun[0]->UserInfoDepts == 0) gryC @endif "><b>{{ number_format($volun[0]->UserInfoDepts) }}</b></td>
    <td> @if (isset($volun[2]->PrsnAddressState)) {{ $volun[2]->PrsnAddressState }} @else <span class="gryC">-</span> @endif </td>
    <td> @if (isset($volun[2]->PrsnEmail)) <a href="mailto:{{ $volun[2]->PrsnEmail }}" class="f12 lH13">{{ $volun[2]->PrsnEmail }}</a> @endif </td>
    <td> @if (isset($volun[2]->PrsnPhoneMobile)) {{ $volun[2]->PrsnPhoneMobile }} @endif </td>
    </tr>
@empty
    <tr><td colspan=8 >No stats found.</td></tr>
@endforelse

</table>

<div class="adminFootBuff"></div>

@endsection