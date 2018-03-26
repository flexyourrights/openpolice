<!-- resources/views/vendor/openpolice/volun/stars.blade.php -->

@extends('vendor.survloop.master')

@section('content')

<div class="row mB20">
    <div class="col-md-8">
        <h1><img id="saveStar1" src="/openpolice/star1.png" border=0 height=40 class="mTn10" > Volunteering All-Stars!</h1>
    </div>
    <div class="col-md-4 taR f16 pT5">
        Online Research <img src="/openpolice/star1.png" border=0 height=20 class="mTn5" ><br />
        Called Department <img src="/openpolice/star1.png" border=0 height=20 class="mTn5" ><img src="/openpolice/star1.png" border=0 height=20 class="mTn5 mLn5" ><img src="/openpolice/star1.png" border=0 height=20 class="mTn5 mLn5" ><br />
        Called Internal Affairs <img src="/openpolice/star1.png" border=0 height=20 class="mTn5" ><img src="/openpolice/star1.png" border=0 height=20 class="mTn5 mLn5" ><img src="/openpolice/star1.png" border=0 height=20 class="mTn5 mLn5" >
    </div>
</div>

<table class="table table-striped taC f22">
    <tr class="f18">
        <th class="taL"><i class="fa fa-users"></i> Volunteers</th>
        <th class="taC">Stars<br />Earned</th>
        <th class="taC f14">Online<br />Research</th>
        <th class="taC f14">Called<br />Department</th>
        <th class="taC f14">Called<br />Internal Affairs</th>
        <th class="taC">Unique<br />Departments</th>
        <th class="taC f14">Average Time<br />Per Department</th>
        <th class="taC">State</th>
    </tr>
    <!---
    <tr class="f26 slBlueDark">
        <td class="taC"><b>You</b></td>
        <td class="taC">{{ $yourStats->UserInfoStars }}</td>
        <td>{{ $yourStats->UserInfoStars1 }}</td>
        <td>{{ $yourStats->UserInfoStars2 }}</td>
        <td>{{ $yourStats->UserInfoStars3 }}</td>
        <td>{{ $yourStats->UserInfoDepts }}</td>
    </tr>
    --->
    @foreach ($leaderboard->UserInfoStars as $i => $u)
        <tr @if ((1+$i)%10 == 0) style="border-bottom: 2px #000 dotted;" @endif >
            <td class="taL">
            @if ($u->userID == $yourStats->userID) <img src="/openpolice/star1.png" border=0 height=26 class="mTn5" > @endif 
            <a href="/dashboard/volunteer/user/{{ $u->userID }}"
                @if ($u->userID == $yourStats->userID) class="bld f24" @endif >{{ $u->name }}</a></td>
            <td class=" @if ($u->userID == $yourStats->userID) bld f24 @endif " >
                @if ($u->UserInfoStars > 0)
                    <nobr><img src="/openpolice/star1.png" border=0 height=16 class="mTn5" > <b>{{ number_format($u->UserInfoStars) }}</b> <img src="/openpolice/star1.png" border=0 height=16 class="mTn5" ></nobr>
                @else
                    <span class="gryC">0</span>
                @endif
            </td>
            <td class=" @if ($u->userID == $yourStats->userID) bld f24 @endif @if ($u->UserInfoStars1 == 0) gryC @endif " >{{ $u->UserInfoStars1 }}</td>
            <td class=" @if ($u->userID == $yourStats->userID) bld f24 @endif @if ($u->UserInfoStars2 == 0) gryC @endif " >{{ $u->UserInfoStars2 }}</td>
            <td class=" @if ($u->userID == $yourStats->userID) bld f24 @endif @if ($u->UserInfoStars3 == 0) gryC @endif " >{{ $u->UserInfoStars3 }}</td>
            <td class=" @if ($u->userID == $yourStats->userID) bld f24 @endif @if ($u->UserInfoDepts == 0) gryC @endif " >{{ $u->UserInfoDepts }}</td>
            <td class=" @if ($u->userID == $yourStats->userID) bld @else @endif @if ($u->UserInfoAvgTimeDept == 0) gryC @endif " ><span class="f14"><nobr>
                @if (($u->UserInfoAvgTimeDept/60) < 10)
                    {{ number_format($u->UserInfoAvgTimeDept/60, 1) }}
                @else
                    {{ number_format($u->UserInfoAvgTimeDept/60, 0) }}
                @endif
                min</nobr></span></td>
            <td> @if (isset($u->PrsnAddressState)) {{ $u->PrsnAddressState }} @else <span class="gryC">-</span> @endif </td>
        </tr>
    @endforeach
</table>

<div class="adminFootBuff"></div>

@endsection