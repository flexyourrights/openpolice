<!-- resources/views/vendor/openpolice/admin/complaints/complaints-listing.blade.php -->

@extends('vendor.survloop.admin.admin')

@section('content')

<h1>
	<i class="fa fa-star"></i> 
	@if ($currPage == '/dashboard/complaints') 					All New Complaints, or Requiring Action
	@elseif ($currPage == '/dashboard/complaints/me') 			All Complaints Assigned To Me
	@elseif ($currPage == '/dashboard/complaints/waiting') 		All Complaints Waiting for Investigation
	@elseif ($currPage == '/dashboard/complaints/all') 			All Complete Complaints
	@elseif ($currPage == '/dashboard/complaints/incomplete') 	All Incomplete Complaints
	@endif
</h1>

<div class="p5"></div>

<table class="table">
<tr><th><i>ID#</i></th><th>Status</th><th>Type</th><th>Submitted</th><th>Incident</th><th>City</th><th>Complainant</th><th>Level</th><th>Privacy</th></tr>
@forelse ($complaints as $com)
	<tr>
		<td rowspan=2 class="taC" >
			<a href="/dashboard/complaint/{{ $com->ComID }}/review" 
			@if ($com->ComStatus == $GLOBALS["DB"]->getDefID('Complaint Status', 'New')) 
				class="btn btn btn-primary round20 p5 f22"
			@else
				class="btn btn btn-default round20 p5 f22 slBlueDark"
			@endif
			>#{{ number_format($com->ComID) }}</a>
		</td>
		<td class="f18">
			<nobr>
			@if ($GLOBALS["DB"]->getDefValue('Complaint Status', $com->ComStatus) == 'New')
				<i class="fa fa-star slRedDark"></i>
			@endif
			{{ $GLOBALS["DB"]->getDefValue('Complaint Status', $com->ComStatus) }}
			</nobr>
		</td>
		<td class="f18">
			<nobr>
			@if ($GLOBALS["DB"]->getDefValue('OPC Staff/Internal Complaint Type', $com->ComType) == 'Unreviewed')
				<i class="fa fa-star slRedDark"></i>
			@endif
			@if ($com->ComStatus != $GLOBALS["DB"]->getDefID('Complaint Status', 'Incomplete')) 
				{{ $GLOBALS["DB"]->getDefValue('OPC Staff/Internal Complaint Type', $com->ComType) }}
			@else 
				<span class="f12 gry9"><i>through node #{{ $com->ComSubmissionProgress }}</i></span>
			@endif
			</nobr>
		</td>
		<td class="f18">
			{{ $comInfo[$com->ComID]["comDate"] }}
		</td>
		<td class="f18">
			@if (trim($com->IncTimeStart) != '') {{ date("n/j/Y", strtotime($com->IncTimeStart)) }} @endif
		</td>
		<td>{{ $com->IncAddressCity }}, {{ $com->IncAddressState }}</td>
		<td>{{ $com->PrsnNameFirst }} {{ $com->PrsnNameLast }}</td>
		<td>{{ $com->ComAwardMedallion }}</td>
		<td>
			@if ($com->ComPrivacy == 204) Open
			@elseif ($com->ComPrivacy == 205) No Names
			@elseif ($com->ComPrivacy == 206) Anonymous
			@elseif ($com->ComPrivacy == 207) Anonymized
			@endif
		</td>
	</tr>
	<tr>
		<td colspan=2 class="pB20" style="border-top: 0px none;">
		
			@if (isset($r) && isset($r->ComRevReadability))
				<i class="fa fa-search-plus slBlueDark" aria-hidden="true"></i> {{ ($r->ComRevNotAnon + $r->ComRevOneIncident + $r->ComRevCivilianContact + $r->ComRevOneOfficer + $r->ComRevOneAllegation + $r->ComRevEvidenceUpload) }}
				@if (($r->ComRevReadability + $r->ComRevConsistency + $r->ComRevRealistic + $r->ComRevOutrage) >= 0)
					<i class="fa fa-thumbs-o-up mL20 pL20 slBlueDark" aria-hidden="true"></i> {{ ($r->ComRevReadability + $r->ComRevConsistency + $r->ComRevRealistic + $r->ComRevOutrage) }}
				@else
					<i class="fa fa-thumbs-o-down mL20 pL20 slBlueDark" aria-hidden="true"></i> {{ ($r->ComRevReadability + $r->ComRevConsistency + $r->ComRevRealistic + $r->ComRevOutrage) }}
				@endif
				@if ($r->ComRevMakeFeatured)
					<i class="fa fa-certificate mL20 pL20 slBlueDark" aria-hidden="true"></i>
				@endif
			@endif
			
		</td>
		<td colspan=5 class="pB20 gry9" style="border-top: 0px none;">
			{!! $comInfo[$com->ComID]["alleg"] !!}
		</td>
	</tr>
@empty
	<tr><td colspan=6 ><i>No complaints found in this filter</i></td></tr>
@endforelse
</table>


<div class="adminFootBuff"></div>

@endsection