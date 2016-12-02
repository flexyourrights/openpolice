<!-- Stored in resources/views/openpolice/complaint-report.blade.php -->

@if ($isOwner || $view == 'Investigate')
	<div id="reportTakeActions" class="disNon">
		<!---
		<div class="row">
			<div class="col-md-3">
				<b class="f18">Actions:</b><br />
				@if ($sessData["Complaints"][0]->ComPrivacy != 304)
					@if ($view == 'Investigate')
						Investigator View <i class="fa fa-check"></i> - 
						<a class="f10" href="/report{{ $ComSlug }}/?publicView=1">Public View</a>
					@else
						Public View <i class="fa fa-check"></i> - 
						<a class="f10" href="/report{{ $ComSlug }}">Investigator View</a>
					@endif
				@endif
			</div>
			<div class="col-md-9">
				@if ($isOwner)
					
					
				@else
				
				
				@endif
			</div>
		</div>
		--->
		@if ($view == 'Investigate')
			
		@else
			
		@endif
	</div>
@endif
<!--- isOwner: {{ (($isOwner) ? 'true' : 'false') }} , view: {{ $view }} , ComPrivacy: {{ $sessData["Complaints"][0]->ComPrivacy }} <br /> --->

@if (!$isAdmin && $ComSlug == '/63/baltimore-md')
	<!--- <img src="http://databasingmodels.com/vid/mario.png" border=0 width=100% class="mTn5" > --->
@endif

<style>
.investigateStatus { font-size: 17px; font-style: italic; }
@media screen and (max-width: 768px) {
	.investigateStatus { font-size: 12px; }
}
</style>

<div class="round20 brdRed mT20 mB20 pL20 pR20 pT10 pB10 f20 slRedDark investigateStatus">
	@if (true || $sessData["Complaints"][0]->ComStatus == 296)
		We do not know if this complaint has been investigated yet. 
	@endif
	The events described here are allegations, which may or may not be factually accurate. 
</div>

<div class="row mB20 complaintReportTitle">
	<div class="col-md-8">
		<div class="mTn20 pB10">
		
			@if (trim($sessData["Complaints"][0]->ComHeadline) != '')
				<a href="/report{{ $ComSlug }}"><h1 class="slBlueDark">{!! $sessData["Complaints"][0]->ComHeadline !!}</h1></a>
				<div class="f18 gry4 mTn5 mBn5">Complaint ID: {{ $complaintID }}</div>
			@else
				<a href="/report{{ $ComSlug }}"><h1 class="slBlueDark">Complaint ID: {{ $complaintID }}</h1></a>
			@endif
			
		</div>
	</div>
	<div class="col-md-4">
	
		@if ($isAdmin)
			
			@if ($ComSlug == '/63/baltimore-md')
				<img src="http://databasingmodels.com/vid/mario.png" border=0 width=100% class="mTn5" >
			@endif
			
		@endif
	
	</div>
</div>

<?php /*



@if ( complaint role == 'Upset'

<p>
This site might not be for you. You said you are upset about police brutality on the news or on the internet. 
But this site is for people who <i>experienced</i> or <i>witnessed</i> police misconduct <i>in-person</i>.
</p><p>
We do appreciate your interest in police accountability. Here are some other ways you can help:
<ul>
<li>Contact the police department you're upset about. <a href="#" target="_blank">Click here to search for the department involved</a>.</li>
<li>Write to your representatives. <a href="http://www.joincampaignzero.org/action" target="_blank">Click here to urge them to support better police accountability</a>.</li>
<li>Check out <a href="http://FlexYourRights.org">FlexYourRights.org</a> for helpful info on how to handle police encounters.</li>
</ul>
<p>
If you <i>did</i> experience or witness misconduct in-person, <a href="#" target="_blank">click here to resume your complaint</a>.
</p>

@endif







@if ($featureImg != '')
	<img src="{{ $featureImg }}" border=0 width=100% >
@else
	<div class="p20 m20"></div>
@endif
*/ ?>
		
<div class="row">
	<div class="col-md-4 pB10">
	
		@if (isset($sessData["Departments"]["-"]) && sizeof($sessData["Departments"]["-"]) > 0)
			@foreach ($sessData["Departments"]["-"] as $dept)
				<div class="mTn5 pB20">
					<div class="f22">{{ $dept->DeptName }}</div>
					<div class="f12 gry9 pL10">
						{{ $dept->DeptAddress }}, {{ $dept->DeptAddressCity }}, {{ $dept->DeptAddressState }} {{ $dept->DeptAddressZip }}
						@if (trim($dept->DeptPhoneWork) != '') &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $dept->DeptPhoneWork }} @endif
					</div>
				</div>
			@endforeach
		@endif
		<div class="f18 gry9">Allegations:</div>
		<div class="f22 pL10"><ul class="mLn20">
			<li>{!! str_replace(', ', '</li><li>', $basicAllegationListF) !!}</li>
		</ul></div>
		
	</div>
	<div class="col-md-4 pB20 f16">
		
		<div class="gry9">Incident:</div>
		<div class="pL10 pB20">
			@if (in_array($sessData["Complaints"][0]->ComPrivacy, [306, 307]))
				{{ date('F Y', strtotime($sessData["Incidents"][0]->IncTimeStart)) }}
			@else
				{{ date('n/j/Y', strtotime($sessData["Incidents"][0]->IncTimeStart)) }}
			@endif
			<br />{{ $sessData["Incidents"][0]->IncAddressCity }}, {{ $sessData["Incidents"][0]->IncAddressState }}
		</div>
		
		<div class="gry9">Submitted:</div>
		<div class="pL10">
			@if (in_array($sessData["Complaints"][0]->ComPrivacy, [306, 307]))
				{{ date('F Y', strtotime($comDate)) }}
			@else
				{{ date('n/j/Y', strtotime($comDate)) }}
			@endif
			<br /><span class="gry9">by</span> 
			{!! $complainantName !!}
			<a href="javascript:void(0)" class="mR10 gryA"><i class="fa fa-info-circle f12"></i></a>
		</div>
		
		
		
	</div>
	<div class="col-md-3 pB20 f16">
	
		<div class="gry9">Complaint Status:</div>
		<div class="pL10 pB20">
			{{ $GLOBALS["DB"]->getDefValue('Complaint Status', $sessData["Complaints"][0]->ComStatus) }} 
			<a href="javascript:void(0)" class="mR10 gryA"><i class="fa fa-info-circle f12"></i></a>
			@if ($sessData["Complaints"][0]->ComAwardMedallion == 'Gold')
				<div class="gry9 f12">Gold-level info provided!</div>
			@endif
		</div>
		<div class="gry9">Evidence Uploaded:</div>
		<div class="pL10 f14 gry4">
				<nobr>0 videos,</nobr> <nobr>0 photos,</nobr> <nobr>0 documents</nobr>
		</div>
		
	</div>
</div>
		
<div class="pT20 f18 gry9">Story:</div>
<div class="pL10 pR10 f18">
	{!! str_replace("\n", '<br />', $sessData["Complaints"][0]->ComSummary) !!}
</div>

@if (sizeof($whoBlocks["Subjects"]) > 0)
	<div class="reportSectHead">Subject{{ ((sizeof($whoBlocks["Subjects"]) > 1) ? 's' : '') }}:</div>
	@foreach ($whoBlocks["Subjects"] as $who) 
		{!! $who !!}
	@endforeach
@endif
@if (sizeof($whoBlocks["Witnesses"]) > 0)
	<div class="reportSectHead">Witness{{ ((sizeof($whoBlocks["Witnesses"]) > 1) ? 'es' : '') }}:</div>
	@foreach ($whoBlocks["Witnesses"] as $who) 
		{!! $who !!}
	@endforeach
@endif
@if (sizeof($whoBlocks["Officers"]) > 0)
	<div class="reportSectHead">Officer{{ ((sizeof($whoBlocks["Officers"]) > 1) ? 's' : '') }}:</div>
	@foreach ($whoBlocks["Officers"] as $who)
		{!! $who !!}
	@endforeach
@endif
		


@if ($sessData["Complaints"][0]->ComAwardMedallion == 'Silver')

	<div class="reportSectHead">Allegations:</div>
	<div class="reportBlock">
		{!! $basicAllegationList !!}
	</div>
	
@else

	<div class="reportSectHead">What Happened:</div>
	{!! $printwhatHaps !!}
	
	@if ($hasMedicalCare)
		<div class="reportSectHead">
			Medical Care:
		</div>
		@if (sizeof($injuries) > 0)
			@foreach ($injuries as $civID => $inj) 
				@if (sizeof($inj[2]) > 0)
					<div class="reportBlock">
						<div class="row">
							<div class="col-md-4">
								<div class="f20">{!! $inj[0] !!}</div>
								<table class="table"><tr class="disNon"></tr>
								@forelse ($inj[2][0] as $deet)
									<tr><td>{!! $deet !!}</td></tr>
								@empty
								@endforelse
								</table>
							</div>
							<div class="col-md-4 pL20">
								<table class="table">
								@forelse ($inj[2][1] as $i => $deet)
									@if ($i == 0) <tr><td colspan=2 >{!! $deet !!}</td></tr>
									@else <tr><td>{!! $deet !!}</td></tr>
									@endif
								@empty
								@endforelse
								</table>
							</div>
							<div class="col-md-4 pL20">
								<table class="table">
								@forelse ($inj[2][2] as $i => $deet)
									@if ($i == 0) <tr><td colspan=2 >{!! $deet !!}</td></tr>
									@else <tr><td>{!! $deet !!}</td></tr>
									@endif
								@empty
								@endforelse
								</table>
							</div>
						</div>
					</div>
				@endif
			@endforeach
		@endif
	@endif
	
@endif

<div class="complaintFooter">

	@if (!in_array($sessData["Complaints"][0]->ComStatus, [294, 295, 298, 299]))
		<center><div class="row pB10 pT20 mT20">
			<div class="col-md-6 taR">
				<a href="https://twitter.com/share" class="twitter-share-button" 
					data-text="Check out this police complaint!" data-via="opencomplaints" 
					data-url="https://app.openpolicecomplaints.org/report{{ $ComSlug }}" >Tweet</a>
			</div>
			<div class="col-md-6 taL">
				<div class="fb-share-button" data-layout="button_count" 
					data-href="https://app.openpolicecomplaints.org/report{{ $ComSlug }}"></div>
			</div>
		</div>
	@endif
	
	<center><a class="f12 noUnd" href="/report{{ $ComSlug }}">https://app.openpolicecomplaints.org/report{{ $ComSlug }}</a></center>

</div>