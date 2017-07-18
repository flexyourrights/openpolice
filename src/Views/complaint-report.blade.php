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

<style>
.investigateStatus { font-size: 17px; }
@media screen and (max-width: 768px) {
    .investigateStatus { font-size: 12px; }
}
</style>


<!-- if not yet reviewed by OPC 
    <div class="round5 brdRed mT20 mB20 pL20 pR20 pT10 pB10 f20 slRedDark investigateStatus">
        We are reviewing this complaint. If there are no problems, we will try to file it with the department's 
        oversight agency. 
        <!-- if Fully Transparent 
            Once it has been submitted, we can publish the complete report.
    </div> -->
    
<!-- if Fully Transparent AND not yet submitted 
    <div class="round5 brdRed mT20 mB20 pL20 pR20 pT10 pB10 f20 slRedDark investigateStatus">
        This complaint was set to 'Full Transparency.' We are waiting for the user to confirm that they 
        submitted it to the department's oversight agency. Only then can we publish the complete report. 
        <a href="/login">Login</a>
    </div> -->
    
@if (!$isAdmin && (!isset($hideDisclaim) || !$hideDisclaim))
    <div class="round5 brdRed mT20 mB20 pL20 pR20 pT10 pB10 f20 slRedDark investigateStatus">
        @if (true || $sessData['Complaints'][0]->ComStatus == 296)
            We do not know if this complaint has been investigated yet. 
        @endif
        The events described here are allegations, which may or may not be factually accurate. 
    </div>
@endif

<?php /*

IF (Fully Transparent AND Not Reviewed)
THEN "More details of this complaint will be made public upon staff review."

IF (Fully Transparent AND Not Submitted Yet)
THEN "More details of this complaint will be made public after complainant officially submits this complaint with the oversight agency."

*/ ?>

<?php /*
@if ($featureImg != '')
    <img src="{{ $featureImg }}" border=0 width=100% >
@else
    <div class="p20 m20"></div>
@endif
*/ ?>

<div class="row mT20">
    <div class="col-md-8">
    
        <div class="reportMiniBlockLabel">Allegations</div>
        <div class="reportMiniBlockDeets">
            {!! $basicAllegationListF !!}
        </div>
        
        <div class="reportMiniBlockLabel">Story</div>
        <div class="reportMiniBlockDeets">
            {!! str_replace("\n", '<br />', $sessData['Complaints'][0]->ComSummary) !!}
        </div>
        
    </div>
    <div class="col-md-4">
    
        <div class="reportMiniBlockLabel">
            @if (isset($sessData["Departments"]) && sizeof($sessData["Departments"]) > 1) Departments
            @else Department @endif Involved
        </div>
        <div class="reportMiniBlockDeets">
        @if (isset($sessData["Departments"]) && sizeof($sessData["Departments"]) > 0)
            @foreach ($sessData["Departments"] as $dept)
                <a href="/dept/{{ $dept->DeptSlug }}">{{ $dept->DeptName }}</a><br />
            @endforeach
        @endif
        </div>

        <div class="reportMiniBlockLabel">Incident Time & Place</div>
        <div class="reportMiniBlockDeets">
            @if (!$isOwner && !$isAdmin && in_array($sessData['Complaints'][0]->ComPrivacy, [306, 307]))
                {{ date('F Y', strtotime($sessData["Incidents"][0]->IncTimeStart)) }}
            @else
                {{ date('n/j/Y g:ia', strtotime($sessData["Incidents"][0]->IncTimeStart)) }}
            @endif
            <br />{{ $sessData["Incidents"][0]->IncAddressCity }}, {{ $sessData["Incidents"][0]->IncAddressState }}
        </div>
        
        <div class="reportMiniBlockLabel">Date Submitted</div>
        <div class="reportMiniBlockDeets">
            @if (!$isOwner && !$isAdmin && in_array($sessData['Complaints'][0]->ComPrivacy, [306, 307]))
                {{ date('F Y', strtotime($comDate)) }}
            @else
                {{ date('n/j/Y', strtotime($comDate)) }}
            @endif
            <br />by {!! str_replace('Subject #1:', '', $complainantName) !!}
            <!-- <a href="javascript:void(0)" class="mR10 gryA"><i class="fa fa-info-circle f12"></i></a> -->
        </div>
    
        <div class="reportMiniBlockLabel">Misconduct Complaint ID Number</div>
        <div class="reportMiniBlockDeets">
            <a href="/complaint-report/{{ $complaintID }}">{{ $complaintID }}</a>
        </div>
        
        <div class="reportMiniBlockLabel">Complaint Status</div>
        <div class="reportMiniBlockDeets">
            <!-- {{ $GLOBALS['SL']->getDefValue('Complaint Status', $sessData['Complaints'][0]->ComStatus) }} -->
            @if ($sessData['Complaints'][0]->ComAwardMedallion == 'Gold')
                <br />Gold-Star Complaint
            @endif
            <div class="checkbox">
                <label><input type="checkbox" class="mR10" DISABLED > <span style="color: #999;">Submitted to Oversight Agency</span></label>
            </div>
            <div class="checkbox">
                <label><input type="checkbox" class="mR10" DISABLED > <span style="color: #999;">Received by Oversight Agency</span></label>
            </div>
            <div class="checkbox">
                <label><input type="checkbox" class="mR10" DISABLED > <span style="color: #999;">Investigated by Oversight Agency</span></label>
            </div>
            
        </div>
        
    </div>
</div>

@if (isset($uploads) && sizeof($uploads) > 0)
    <div class="reportMiniBlockLabel"> @if (sizeof($uploads) > 1) Uploads @else Upload @endif </div>
    <div class="reportMiniBlockDeets">
        <div class="row">
            @foreach ($uploads as $i => $up)
                @if ($i > 0 && $i%3 == 0) </div><div class="row"> @endif
                <div class="col-md-4">{!! $up !!}</div>
            @endforeach
        </div>
    </div>
@endif

@if ($sessData['Complaints'][0]->ComAwardMedallion == 'Gold')
    <div class="row mT20">
        <div class="col-md-4">
            <div class="reportSectHead2">Who's Involved?</div>
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
@else
    <div class="row mT20">
        <div class="col-md-4">
            <div class="reportSectHead2">Who's Involved?</div>
            {!! $civBlocks !!}
        </div>
        <div class="col-md-4">
            <div class="reportSectHead2">&nbsp;</div>
            {!! $offBlocks !!}
        </div>
        <div class="col-md-4">
            {!! $fullAllegations !!}
        </div>
    </div>
@endif

<div class="complaintFooter">
    @if (!in_array($sessData['Complaints'][0]->ComStatus, [294, 295, 298, 299]))
        @if (isset($emojiTags) && trim($emojiTags) != '') <div class="fL">{!! $emojiTags !!}</div> @endif
        <div class="fL" style="padding: 13px 20px 0px 40px;">
            <a href="https://twitter.com/share" class="twitter-share-button" 
                data-text="Check out this police complaint!" data-via="opencomplaints" 
                data-url="{{ $GLOBALS['SL']->sysOpts['app-url'] }}/complaint-report/{{ $complaintID }}" >Tweet</a>
        </div>
        <div class="fL" style="padding: 8px 10px 0px 20px;">
            <div class="fb-share-button" data-layout="button_count" 
                data-href="{{ $GLOBALS['SL']->sysOpts['app-url'] }}/complaint-report/{{ $complaintID }}"></div>
        </div>
        <div class="fC"></div>
    @endif
    <a class="f12 noUnd" href="/complaint-report/{{ $complaintID }}"
        >{{ $GLOBALS['SL']->sysOpts['app-url'] }}/complaint-report/{{ $complaintID }}</a>
</div>

</div>

<div class="p10"></div>

<h4>Privacy Setting: Full Transparency</h4>
<div class="mB20">User opts to publish all the names of civilians and police officers on this website.</div>

<h4>Wrongful Search</h4>
<div class="mB20">A search violated the protections provided by the 4th Amendment of the United States Constitution.</div>
