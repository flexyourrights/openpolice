<!-- Stored in resources/views/openpolice/complaint-report.blade.php -->

<style>
.investigateStatus { font-size: 17px; }
@media screen and (max-width: 768px) { .investigateStatus { font-size: 12px; } }
ul.glossaryList li { margin-bottom: 10px; }
</style>

<div class="slReport">

@if (!isset($GLOBALS["isPrintPDF"]) || !$GLOBALS["isPrintPDF"])
    @if ($sessData['Complaints'][0]->ComStatus == $GLOBALS["SL"]->getDefID('Complaint Status', 'New'))
        <div class="round5 brdRed mT20 mB20 pL20 pR20 pT10 pB10 f20 slRedDark investigateStatus">
        We are reviewing this complaint. If there are no problems, we will try to file it with the department's 
        oversight agency. 
        @if ($sessData['Complaints'][0]->ComPrivacy == $GLOBALS["SL"]->getDefID('Privacy Types', 'Submit Publicly'))
            Once it has been submitted, we can publish the complete report.
        @endif
        </div>
    @endif    
    @if ($sessData['Complaints'][0]->ComPrivacy == $GLOBALS["SL"]->getDefID('Privacy Types', 'Submit Publicly')
        && in_array($sessData['Complaints'][0]->ComStatus, [
            $GLOBALS["SL"]->getDefID('Complaint Status', 'Reviewed'),
            $GLOBALS["SL"]->getDefID('Complaint Status', 'Pending Attorney'),
            $GLOBALS["SL"]->getDefID('Complaint Status', 'Attorney\'d'),
            $GLOBALS["SL"]->getDefID('Complaint Status', 'OK to Submit to Oversight')
            ]))
        <div class="round5 brdRed mT20 mB20 pL20 pR20 pT10 pB10 f20 slRedDark investigateStatus">
            We are waiting for the user to confirm that they submitted it to the department's oversight agency. 
            Only then can we publish the complete report. 
            <?php /* or, "More details of this complaint will be made public after complainant 
                          officially submits this complaint with the oversight agency." */ ?>
        </div>
    @endif
    @if (!isset($hideDisclaim) || !$hideDisclaim)
        <div class="round5 brdRed mT20 mB20 pL20 pR20 pT10 pB10 f20 slRedDark investigateStatus">
            @if (in_array($sessData['Complaints'][0]->ComStatus, [
                $GLOBALS["SL"]->getDefID('Complaint Status', 'OK to Submit to Oversight'),
                $GLOBALS["SL"]->getDefID('Complaint Status', 'Submitted to Oversight')
                ])) We do not know if this complaint has been investigated yet. 
            @endif
            The events described here are allegations, which may or may not be factually accurate. 
        </div>
    @endif
@endif

@if (!isset($GLOBALS["isPrintPDF"]) || !$GLOBALS["isPrintPDF"])
    <div class="row mT20">
        <div class="col-md-8">
@else
    <table border=0 class="table"><tr><td class="w66">
@endif
        <div class="reportMiniBlockLabel">Allegations</div>
        <div class="reportMiniBlockDeets">
            {!! $basicAllegationListF !!}
        </div>
        @if ($view != 'Anon')
            <div class="reportMiniBlockLabel">Story</div>
            <div class="reportMiniBlockDeets">
                {!! str_replace("\n", '<br />', $sessData['Complaints'][0]->ComSummary) !!}
            </div>
        @else
            {!! $incDeets !!}
        @endif
@if (!isset($GLOBALS["isPrintPDF"]) || !$GLOBALS["isPrintPDF"])
    </div><div class="col-md-4">
@else
    </td><td class="w33">
@endif
    
        @if ($view != 'Anon')
            {!! $incDeets !!}
        @endif
        
        <div class="reportMiniBlockLabel">Date Submitted</div>
        <div class="reportMiniBlockDeets">
            @if (!$isOwner && !$isAdmin && in_array($sessData['Complaints'][0]->ComPrivacy, [306, 307]))
                {{ date('F Y', strtotime($comDate)) }}
            @else
                {{ date('n/j/Y', strtotime($comDate)) }}
            @endif
            @if ($view != 'Anon')
                <br />by {!! str_replace('Subject #1:', '', $complainantName) !!}
            @endif
            @if ($sessData['Complaints'][0]->ComPrivacy == $GLOBALS["SL"]->getDefID('Privacy Types', 'Submit Publicly'))
                <br />Full Transparency
            @elseif ($sessData['Complaints'][0]->ComPrivacy 
                == $GLOBALS["SL"]->getDefID('Privacy Types', 'Names Visible to Police but not Public'))
                <br />No Names Public
            @elseif ($sessData['Complaints'][0]->ComPrivacy 
                == $GLOBALS["SL"]->getDefID('Privacy Types', 'Completely Anonymous'))
                <br />Anonymous
            @endif
            <!-- <a href="javascript:void(0)" class="mR10 gryA"><i class="fa fa-info-circle f12"></i></a> -->
        </div>
    
        <div class="reportMiniBlockLabel">Complaint Status</div>
        <div class="reportMiniBlockDeets">
            <!-- {{ $GLOBALS['SL']->getDefValue('Complaint Status', $sessData['Complaints'][0]->ComStatus) }} -->
            @if ($sessData['Complaints'][0]->ComAwardMedallion == 'Gold') <div>Gold-Star Complaint</div> @endif
            @if (!isset($GLOBALS["isPrintPDF"]) || !$GLOBALS["isPrintPDF"])
                <div class="checkbox"><label>
                @if (in_array($sessData['Complaints'][0]->ComStatus, [
                    $GLOBALS["SL"]->getDefID('Complaint Status', 'Submitted to Oversight'),
                    $GLOBALS["SL"]->getDefID('Complaint Status', 'Received by Oversight'),
                    $GLOBALS["SL"]->getDefID('Complaint Status', 'Declined To Investigate (Closed)'),
                    $GLOBALS["SL"]->getDefID('Complaint Status', 'Investigated (Closed)')
                    ]))
                    <input type="checkbox" class="mR10" DISABLED CHECKED > Submitted to Oversight Agency
                @else
                    <input type="checkbox" class="mR10" DISABLED > <span style="color: #999;">Submitted to Oversight Agency</span>
                @endif
                </label></div>
                <div class="checkbox"><label>
                @if (in_array($sessData['Complaints'][0]->ComStatus, [
                    $GLOBALS["SL"]->getDefID('Complaint Status', 'Received by Oversight'),
                    $GLOBALS["SL"]->getDefID('Complaint Status', 'Declined To Investigate (Closed)'),
                    $GLOBALS["SL"]->getDefID('Complaint Status', 'Investigated (Closed)')
                    ]))
                    <input type="checkbox" class="mR10" DISABLED CHECKED > Received by Oversight Agency
                @else
                    <input type="checkbox" class="mR10" DISABLED > <span style="color: #999;">Received by Oversight Agency</span>
                @endif
                </label></div>
                <div class="checkbox"><label>
                @if ($sessData['Complaints'][0]->ComStatus == $GLOBALS["SL"]->getDefID('Complaint Status', 'Investigated (Closed)'))
                    <input type="checkbox" class="mR10" DISABLED CHECKED > Investigated by Oversight Agency
                @else
                    <input type="checkbox" class="mR10" DISABLED 
                        > <span style="color: #999;">Investigated by Oversight Agency</span>
                @endif
                </label></div>
                @if ($sessData['Complaints'][0]->ComStatus == $GLOBALS["SL"]->getDefID('Complaint Status', 'Declined To Investigate (Closed)'))
                    <div class="checkbox"><label>
                        <input type="checkbox" class="mR10" DISABLED CHECKED > Oversight Agency Declined to Investigate
                    </label></div>
                @endif
            @else
                <?php /* isPrintPDF */ ?>
            @endif
            
        </div>
        
        <div class="reportMiniBlockLabel">Misconduct Complaint ID Number</div>
        <div class="reportMiniBlockDeets">
            @if (!isset($GLOBALS["isPrintPDF"]) || !$GLOBALS["isPrintPDF"])
                <a href="/complaint-report/{{ $sessData['Complaints'][0]->ComPublicID }}"
                    >{{ $sessData['Complaints'][0]->ComPublicID }}</a>
            @else
                {{ $sessData['Complaints'][0]->ComPublicID }}<br />{{ $GLOBALS['SL']->sysOpts['app-url'] 
                    }}/complaint-read/{{ $sessData['Complaints'][0]->ComPublicID }}
            @endif
        </div>
@if (!isset($GLOBALS["isPrintPDF"]) || !$GLOBALS["isPrintPDF"])
        </div>
    </div>
@else
    </td></tr></table>
@endif

@if ($view != 'Anon' && isset($uploads) && sizeof($uploads) > 0)
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

@if (!isset($GLOBALS["isPrintPDF"]) || !$GLOBALS["isPrintPDF"])
    <div class="row mT20">
        <div class="col-md-4">
            <div class="reportSectHead2">Who's Involved?</div>{!! $civBlocks !!}{!! $offBlocks !!}
        </div>
        <div class="col-md-4">{!! $printwhatHaps !!}</div>
        <div class="col-md-4">{!! $fullAllegations !!}</div>
    </div>
@else
    <table border=0 class="table"><tr>
        <td class="w33">
            <div class="reportSectHead2">Who's Involved?</div>{!! $civBlocks !!}{!! $offBlocks !!}
        </td>
        <td class="w33">{!! $printwhatHaps !!}</td>
        <td class="w33">{!! $fullAllegations !!}</td>
    </tr></table>
@endif

@if (!isset($GLOBALS["isPrintPDF"]) || !$GLOBALS["isPrintPDF"])
    <div class="complaintFooter">
        @if (!in_array($sessData['Complaints'][0]->ComStatus, [294, 295, 298, 299]))
            @if (isset($emojiTags) && trim($emojiTags) != '') <div class="fL">{!! $emojiTags !!}</div> @endif
            <div class="fL" style="padding: 13px 10px 0px 20px;">
                <a href="https://twitter.com/share" class="twitter-share-button" 
                    data-text="Check out this police complaint!" data-via="opencomplaints" 
                    data-url="{{ $GLOBALS['SL']->sysOpts['app-url'] }}/complaint-read/{{ 
                        $sessData['Complaints'][0]->ComPublicID }}" >Tweet</a>
            </div>
            <div class="fL" style="padding: 11px 10px 0px 10px;">
                <div class="fb-share-button" data-layout="button_count" 
                    data-href="{{ $GLOBALS['SL']->sysOpts['app-url'] }}/complaint-read/{{ 
                        $sessData['Complaints'][0]->ComPublicID }}"></div>
            </div>
        @endif
        <a class="fL noUnd" style="padding: 12px 10px 0px 10px;" href="/complaint-read/{{ 
            $sessData['Complaints'][0]->ComPublicID }}/xml"
            target="_blank"><i class="fa fa-file-code-o" aria-hidden="true"></i> Download As XML File</a>
        <a class="fL noUnd" style="padding: 12px 10px 0px 10px;" href="/complaint-read/{{ 
            $sessData['Complaints'][0]->ComPublicID }}"
            ><i class="fa fa-link" aria-hidden="true"></i> 
            {{ $GLOBALS['SL']->sysOpts['app-url'] }}/complaint-read/{{ $sessData['Complaints'][0]->ComPublicID }}</a>
        <div class="fC"></div>
    </div>
@endif

</div>

<div class="p10"></div>

<h4>Glossary</h4>
<ul class="glossaryList">
@if ($sessData['Complaints'][0]->ComPrivacy == $GLOBALS["SL"]->getDefID('Privacy Types', 'Submit Publicly'))
    <li><b>Privacy Setting: Full Transparency</b>
        - User opts to publish all the names of civilians and police officers on this website.</li>
@elseif ($sessData['Complaints'][0]->ComPrivacy 
    == $GLOBALS["SL"]->getDefID('Privacy Types', 'Names Visible to Police but not Public'))
    <li><b>Privacy Setting: No Names Public</b>
        - User doesn't want to publish any names on this website. 
        This includes police officers' names and badge numbers too.</li>
@elseif ($sessData['Complaints'][0]->ComPrivacy == $GLOBALS["SL"]->getDefID('Privacy Types', 'Completely Anonymous'))
    <li><b>Privacy Setting: Anonymous</b>
        - User needs complaint to be completely anonymous, even though it will be harder to investigate. 
        No names will be published on this website. Neither OPC staff nor investigators will be able to contact them. 
        Any details that could be used for personal identification may be deleted from the database.</li>
@endif
@foreach ($glossary as $i => $g)
    <li><b>{!! $g[0] !!}</b> - {!! $g[1] !!}</li>
@endforeach
</ul>

@if ($view != 'Investigate') {!! $flexArticles !!} @endif

<div class="p20"></div>

@if (isset($GLOBALS["isPrintPDF"]) && $GLOBALS["isPrintPDF"])
    <script type="text/javascript">
    @if (!$isOwner && $view != 'Investigate')
        alert("Make sure you are logged in, so that the full complaint is visible here. Then use your browser's print tools to save this page as a PDF.");
    @endif
    setTimeout("window.print()", 1000);
    </script>
@endif
<!--- isOwner: {{ (($isOwner) ? 'true' : 'false') }} , view: {{ $view }} , ComPrivacy: {{ $sessData['Complaints'][0]->ComPrivacy }} <br /> --->