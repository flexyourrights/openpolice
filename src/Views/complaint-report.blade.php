<!-- Stored in resources/views/openpolice/complaint-report.blade.php -->

<div class="slReport">

@if ($isOwner || $view == 'Investigate')
    <div id="reportTakeActions" class="disNon">
        <!---
        <div class="row">
            <div class="col-md-3">
                <b class="f18">Actions:</b><br />
                @if ($sessData['Complaints'][0]->ComPrivacy != 304)
                    @if ($view == 'Investigate')
                        Investigator View <i class="fa fa-check"></i> - 
                        <a class="f10" href="/complaint-report/{{ $complaintID }}/?publicView=1">Public View</a>
                    @else
                        Public View <i class="fa fa-check"></i> - 
                        <a class="f10" href="/complaint-report/{{ $complaintID }}">Investigator View</a>
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
<!--- isOwner: {{ (($isOwner) ? 'true' : 'false') }} , view: {{ $view }} , ComPrivacy: {{ $sessData['Complaints'][0]->ComPrivacy }} <br /> --->

@if (!$isAdmin && $ComSlug == '/63/baltimore-md')
    <!--- <img src="http://databasingmodels.com/vid/mario.png" border=0 width=100% class="mTn5" > --->
@endif

<style>
.investigateStatus { font-size: 17px; }
@media screen and (max-width: 768px) {
    .investigateStatus { font-size: 12px; }
}
</style>

@if (!isset($hideDisclaim) || !$hideDisclaim)
    <div class="round20 brdRed mT20 mB20 pL20 pR20 pT10 pB10 f20 slRedDark investigateStatus">
        @if (true || $sessData['Complaints'][0]->ComStatus == 296)
            We do not know if this complaint has been investigated yet. 
        @endif
        The events described here are allegations, which may or may not be factually accurate. 
    </div>
@endif

@if (trim($sessData['Complaints'][0]->ComHeadline) != '')
    <a href="/complaint-report/{{ $complaintID }}"><h1 class="slBlueDark m0">
        {!! $sessData['Complaints'][0]->ComHeadline !!}</h1></a>
    <div class="f18 gry4 mTn5 mBn5">Misconduct Complaint ID: {{ $complaintID }}</div>
@else
    <a href="/complaint-report/{{ $complaintID }}"><h1 class="slBlueDark m0">
        Misconduct Complaint ID: {{ $complaintID }}</h1></a>
@endif

<?php /*
@if ($featureImg != '')
    <img src="{{ $featureImg }}" border=0 width=100% >
@else
    <div class="p20 m20"></div>
@endif
*/ ?>

@if (isset($sessData["Departments"]) && sizeof($sessData["Departments"]) > 0)
    @foreach ($sessData["Departments"] as $dept)
        <h2 class="slBlueDark">{{ $dept->DeptName }}</h2>
        <div class="reportMiniBlockDeets">
            {{ $dept->DeptAddress }}, {{ $dept->DeptAddressCity }}, 
            {{ $dept->DeptAddressState }} 
            {{ $dept->DeptAddressZip }}@if (trim($dept->DeptPhoneWork) != ''), {{ $dept->DeptPhoneWork }} @endif
        </div>
    @endforeach
@endif

<div class="row mT20">
    <div class="col-md-4">
        <div class="reportMiniBlockLabel">Allegations</div>
        <div class="reportMiniBlockDeets">
            {!! str_replace(', ', '<br />', $basicAllegationListF) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="reportMiniBlockLabel">Incident</div>
        <div class="reportMiniBlockDeets">
            @if (in_array($sessData['Complaints'][0]->ComPrivacy, [306, 307]))
                {{ date('F Y', strtotime($sessData["Incidents"][0]->IncTimeStart)) }}
            @else
                {{ date('n/j/Y', strtotime($sessData["Incidents"][0]->IncTimeStart)) }}
            @endif
            <br />{{ $sessData["Incidents"][0]->IncAddressCity }}, {{ $sessData["Incidents"][0]->IncAddressState }}
        </div>
        <div class="reportMiniBlockLabel">Submitted</div>
        <div class="reportMiniBlockDeets">
            @if (in_array($sessData['Complaints'][0]->ComPrivacy, [306, 307]))
                {{ date('F Y', strtotime($comDate)) }}
            @else
                {{ date('n/j/Y', strtotime($comDate)) }}
            @endif
            <br />by {!! str_replace('Subject #1:', '', $complainantName) !!}
            <!-- <a href="javascript:void(0)" class="mR10 gryA"><i class="fa fa-info-circle f12"></i></a> -->
        </div>
    </div>
    <div class="col-md-4">
        <div class="reportMiniBlockLabel">Complaint Status</div>
        <div class="reportMiniBlockDeets">
            {{ $GLOBALS['SL']->getDefValue('Complaint Status', $sessData['Complaints'][0]->ComStatus) }} 
            @if ($sessData['Complaints'][0]->ComAwardMedallion == 'Gold')
                <br />Gold-Star Complaint
            @endif
        </div>
        <div class="reportMiniBlockLabel">Evidence Uploaded</div>
        <div class="reportMiniBlockDeets">
            <nobr>0 videos,</nobr> <nobr>0 photos,</nobr> <nobr>0 documents</nobr>
        </div>
    </div>
</div>

<div class="row mT20 mB20">
    <div class="col-md-12">
        <div class="reportMiniBlockLabel">Story:</div>
        <div class="reportMiniBlockDeets">
            {!! str_replace("\n", '<br />', $sessData['Complaints'][0]->ComSummary) !!}
        </div>
    </div>
</div>

<div class="row mT20">
    <div class="col-md-4">
        <div class="reportSectHead2">Who's Involved...</div>
        {!! $civBlocks !!}
        {!! $offBlocks !!}
    </div>
    <div class="col-md-4">
        {!! $printwhatHaps !!}
    </div>
    <div class="col-md-4">
        {!! $fullAllegations !!}
    </div>
</div>

<div class="complaintFooter">
    @if (!in_array($sessData['Complaints'][0]->ComStatus, [294, 295, 298, 299]))
        <center><div class="row pB10 pT20 mT20">
            <div class="col-md-6 taR">
                <a href="https://twitter.com/share" class="twitter-share-button" 
                    data-text="Check out this police complaint!" data-via="opencomplaints" 
                    data-url="{{ $GLOBALS['SL']->sysOpts['app-url'] }}/complaint-report/{{ $complaintID }}" >Tweet</a>
            </div>
            <div class="col-md-6 taL">
                <div class="fb-share-button" data-layout="button_count" 
                    data-href="{{ $GLOBALS['SL']->sysOpts['app-url'] }}/complaint-report/{{ $complaintID }}"></div>
            </div>
        </div></center>
    @endif
    <center><a class="f12 noUnd" href="/complaint-report/{{ $complaintID }}"
        >{{ $GLOBALS['SL']->sysOpts['app-url'] }}/complaint-report/{{ $complaintID }}</a></center>
</div>

</div>