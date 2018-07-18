<!-- Stored in resources/views/openpolice/complaint-report.blade.php
    isOwner: {{ (($isOwner) ? 'true' : 'false') }} , view: {{ $view }} , 
    ComPrivacy: {{ $sessData['Complaints'][0]->ComPrivacy }} --->
@if (!$GLOBALS["SL"]->REQ->has('wdg'))
<style> #bodyContain { width: 100%; } </style>
<div class="container">
@endif
<div class="slReport">

@if (!isset($GLOBALS["SL"]->x["isPrintPDF"]) || !$GLOBALS["SL"]->x["isPrintPDF"])
    @if ($sessData['Complaints'][0]->ComStatus == $GLOBALS["SL"]->def->getID('Complaint Status', 'New')
        || ($sessData['Complaints'][0]->ComPrivacy == $GLOBALS["SL"]->def->getID('Privacy Types', 'Submit Publicly')
        && in_array($sessData['Complaints'][0]->ComStatus, [
            $GLOBALS["SL"]->def->getID('Complaint Status', 'Reviewed'),
            $GLOBALS["SL"]->def->getID('Complaint Status', 'Pending Attorney'),
            $GLOBALS["SL"]->def->getID('Complaint Status', 'Attorney\'d'),
            $GLOBALS["SL"]->def->getID('Complaint Status', 'OK to Submit to Oversight')
            ]))
        || (!isset($hideDisclaim) || !$hideDisclaim))
        <div class="p10"></div>
    @endif
    
    @if ($sessData['Complaints'][0]->ComStatus == $GLOBALS["SL"]->def->getID('Complaint Status', 'New'))
        <div class="alert alert-danger fade in alert-dismissible show">
        We are reviewing this complaint. If there are no problems, we will try to file it with the department's 
        oversight agency. 
        @if ($sessData['Complaints'][0]->ComPrivacy == $GLOBALS["SL"]->def->getID('Privacy Types', 'Submit Publicly'))
            Once it has been submitted, we can publish the complete report.
        @endif </div>
    @endif
    @if ($sessData['Complaints'][0]->ComPrivacy == $GLOBALS["SL"]->def->getID('Privacy Types', 'Submit Publicly')
        && in_array($sessData['Complaints'][0]->ComStatus, [
            $GLOBALS["SL"]->def->getID('Complaint Status', 'Reviewed'),
            $GLOBALS["SL"]->def->getID('Complaint Status', 'Pending Attorney'),
            $GLOBALS["SL"]->def->getID('Complaint Status', 'Attorney\'d'),
            $GLOBALS["SL"]->def->getID('Complaint Status', 'OK to Submit to Oversight')
            ]))
        <div class="alert alert-danger fade in alert-dismissible show">
        We are waiting for the user to confirm that they submitted it to the department's oversight agency. 
        Only then can we publish the complete report.</div>
        <?php /* or, "More details of this complaint will be made public after complainant 
                      officially submits this complaint with the oversight agency." */ ?>
    @endif
    @if (!isset($hideDisclaim) || !$hideDisclaim)
        <div class="alert alert-danger fade in alert-dismissible show">
        @if (in_array($sessData['Complaints'][0]->ComStatus, [
            $GLOBALS["SL"]->def->getID('Complaint Status', 'OK to Submit to Oversight'),
            $GLOBALS["SL"]->def->getID('Complaint Status', 'Submitted to Oversight')
            ])) We do not know if this complaint has been investigated yet. 
        @endif
        The events described here are allegations, which may or may not be factually accurate.</div>
    @endif
@endif

@if (isset($GLOBALS["SL"]->x["isPrintPDF"]) && $GLOBALS["SL"]->x["isPrintPDF"])
    <table border=0 class="table"><tr><td class="w66">
@else
    <div class="row mT20"><div class="col-md-7">
@endif
    
    @if ($view != 'Anon')
        <div class="reportMiniBlockLabel">Story</div>
        <div class="reportMiniBlockDeets">
            {!! str_replace("\n", '<br />', $sessData['Complaints'][0]->ComSummary) !!}
        </div>
    @else
        <div class="slCard reportBlock">{!! $incDeets !!}</div>
    @endif
        
@if (isset($GLOBALS["SL"]->x["isPrintPDF"]) && $GLOBALS["SL"]->x["isPrintPDF"])
    </td><td class="w33">
@else
    </div><div class="col-md-1"></div><div class="col-md-4">
@endif
    
    @if ($view != 'Anon') <div class="slCard reportBlock">{!! $incDeets !!}</div> @endif
    
    <div class="slCard reportBlock">
        <div class="reportMiniBlockLabel">Date Submitted</div>
        <div class="reportMiniBlockDeets">
            @if (!$isOwner && !$isAdmin && in_array($sessData['Complaints'][0]->ComPrivacy, [306, 307]))
                {{ date('F Y', strtotime($comDate)) }}
            @else
                {{ date('n/j/Y', strtotime($comDate)) }}
            @endif
            @if ($view != 'Anon') <br />by {!! str_replace('Subject #1:', '', $complainantName) !!} @endif
            <br />
            @if ($sessData['Complaints'][0]->ComPrivacy == $GLOBALS["SL"]->def->getID('Privacy Types', 'Submit Publicly'))
                @if (!isset($GLOBALS["SL"]->x["isPrintPDF"]) || !$GLOBALS["SL"]->x["isPrintPDF"])
                    <a id="hidivBtnPrivInfo2" class="hidivBtn" href="javascript:;"
                        >Full Transparency <i class="fa fa-info-circle slGrey" aria-hidden="true"></i></a>
                    <div id="hidivPrivInfo2" class="disNon slGrey">Privacy Setting:
                    User opts to publish the names of civilians and police officers on this website.</div>
                @else
                    Full Transparency
                @endif
            @elseif ($sessData['Complaints'][0]->ComPrivacy 
                == $GLOBALS["SL"]->def->getID('Privacy Types', 'Names Visible to Police but not Public'))
                @if (!isset($GLOBALS["SL"]->x["isPrintPDF"]) || !$GLOBALS["SL"]->x["isPrintPDF"])
                    <a id="hidivBtnPrivInfo2" class="hidivBtn" href="javascript:;"
                        >No Names Public <i class="fa fa-info-circle slGrey" aria-hidden="true"></i></a>
                    <div id="hidivPrivInfo2" class="disNon slGrey">Privacy Setting:
                    User doesn't want to publish any names on this website. 
                    This includes police officers' names and badge numbers too.</div>
                @else
                    No Names Public
                @endif
            @elseif ($sessData['Complaints'][0]->ComPrivacy 
                == $GLOBALS["SL"]->def->getID('Privacy Types', 'Completely Anonymous'))
                @if (!isset($GLOBALS["SL"]->x["isPrintPDF"]) || !$GLOBALS["SL"]->x["isPrintPDF"])
                    <a id="hidivBtnPrivInfo2" class="hidivBtn" href="javascript:;"
                        >Anonymous <i class="fa fa-info-circle slGrey" aria-hidden="true"></i></a>
                    <div id="hidivPrivInfo2" class="disNon slGrey">Privacy Setting:
                    User needs complaint to be completely anonymous, even though it will be harder to investigate. 
                    No names will be published on this website. Neither OPC staff nor investigators will be able 
                    to contact them. Any details that could be used for personal identification may be deleted from 
                    the database.</div>
                @else
                    Anonymous
                @endif
            @endif
        </div>
    
    @if (!$GLOBALS["SL"]->REQ->has('wdg'))
        <div class="reportMiniBlockLabel">Complaint Status</div>
        <div class="reportMiniBlockDeets">
            Misconduct Complaint ID #{{ $sessData['Complaints'][0]->ComPublicID }}
            <!-- {{ $GLOBALS['SL']->def->getVal('Complaint Status', $sessData['Complaints'][0]->ComStatus) }} -->
            @if (!isset($GLOBALS["SL"]->x["isPrintPDF"]) || !$GLOBALS["SL"]->x["isPrintPDF"])
                <div class="checkbox"><label>
                @if (in_array($sessData['Complaints'][0]->ComStatus, [
                    $GLOBALS["SL"]->def->getID('Complaint Status', 'Submitted to Oversight'),
                    $GLOBALS["SL"]->def->getID('Complaint Status', 'Received by Oversight'),
                    $GLOBALS["SL"]->def->getID('Complaint Status', 'Declined To Investigate (Closed)'),
                    $GLOBALS["SL"]->def->getID('Complaint Status', 'Investigated (Closed)')
                    ]))
                    <input type="checkbox" class="mR10" DISABLED CHECKED > Submitted to Oversight Agency
                @else
                    <input type="checkbox" class="mR10" DISABLED > 
                    <span style="color: #999;">Submitted to Oversight Agency</span>
                @endif
                </label></div>
                <div class="checkbox"><label>
                @if (in_array($sessData['Complaints'][0]->ComStatus, [
                    $GLOBALS["SL"]->def->getID('Complaint Status', 'Received by Oversight'),
                    $GLOBALS["SL"]->def->getID('Complaint Status', 'Declined To Investigate (Closed)'),
                    $GLOBALS["SL"]->def->getID('Complaint Status', 'Investigated (Closed)')
                    ]))
                    <input type="checkbox" class="mR10" DISABLED CHECKED > Received by Oversight Agency
                @else
                    <input type="checkbox" class="mR10" DISABLED > 
                    <span style="color: #999;">Received by Oversight Agency</span>
                @endif
                </label></div>
                <div class="checkbox"><label>
                @if ($sessData['Complaints'][0]->ComStatus 
                    == $GLOBALS["SL"]->def->getID('Complaint Status', 'Investigated (Closed)'))
                    <input type="checkbox" class="mR10" DISABLED CHECKED > Investigated by Oversight Agency
                @else
                    <input type="checkbox" class="mR10" DISABLED 
                        > <span style="color: #999;">Investigated by Oversight Agency</span>
                @endif
                </label></div>
                @if ($sessData['Complaints'][0]->ComStatus 
                    == $GLOBALS["SL"]->def->getID('Complaint Status', 'Declined To Investigate (Closed)'))
                    <div class="checkbox"><label>
                        <input type="checkbox" class="mR10" DISABLED CHECKED > Oversight Agency Declined to Investigate
                    </label></div>
                @endif
            @else
                <p>{{ $GLOBALS['SL']->sysOpts['app-url'] }}/complaint-read/{{ $sessData['Complaints'][0]->ComPublicID 
                    }}</a></p>
                <?php /* isPrintPDF */ ?>
            @endif
        </div>
    @endif
    </div>
    
@if (isset($GLOBALS["SL"]->x["isPrintPDF"]) && $GLOBALS["SL"]->x["isPrintPDF"])
    </td></tr></table>
@else
    </div></div>
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

@if ((!isset($GLOBALS["SL"]->x["isPrintPDF"]) || !$GLOBALS["SL"]->x["isPrintPDF"]) && $isOwner && !$GLOBALS["SL"]->REQ->has('wdg'))
    <div class="pull-right p10 mBn20">
        @if (!$GLOBALS["SL"]->REQ->has('publicView')) <a href="?publicView=public">Switch To Public View</a>
        @else <a href="?">Switch To Owner/Investigator View</a> @endif
        <a id="hidivBtnPrevPub" class="hidivBtn mL5 slGrey" href="javascript:;"
            ><i class="fa fa-info-circle" aria-hidden="true"></i></a>
        <div id="hidivPrevPub" class="disNon slGrey taL">
            The view can contain private information. (You are now logged in.) 
            Use this link to see the public view.
        </div>
    </div>
@endif
        <!-- <div class="reportSectHead2">Who's Involved?</div> -->
        {!! $civBlocks !!}
        {!! $offBlocks !!}
        {!! $printwhatHaps !!}
        {!! $fullAllegations !!}

@if ((!isset($GLOBALS["SL"]->x["isPrintPDF"]) || !$GLOBALS["SL"]->x["isPrintPDF"]) && !$isOwner && !$GLOBALS["SL"]->REQ->has('wdg'))
    <div class="slCard reportBlock">
        <div class="reportSectHead">Share, Print, Save...</div>
        <div class="row">
            <div class="col-md-6">
                <div class="mB5"><a class="noUnd" style="padding: 12px 10px 0px 10px;" 
                    href="/complaint-read/{{ $sessData['Complaints'][0]->ComPublicID }}" 
                    ><i class="fa fa-link mR5" aria-hidden="true"></i> {{ $GLOBALS['SL']->sysOpts['app-url'] 
                    }}/complaint-read/{{ $sessData['Complaints'][0]->ComPublicID }}</a></div>
                <div class="mB5"><a class="noUnd" style="padding: 12px 10px 0px 10px;" target="_blank"
                    href="/complaint-read/{{ $sessData['Complaints'][0]->ComPublicID }}/pdf"
                    ><i class="fa fa-print mR5" aria-hidden="true"></i> Print Complaint or Save as PDF</a></div>
                <div><a class="noUnd" style="padding: 12px 10px 0px 10px;" target="_blank"
                    href="/complaint-read/{{ $sessData['Complaints'][0]->ComPublicID }}/xml"
                    ><i class="fa fa-cloud-download mR5" aria-hidden="true"></i> 
                    Download Raw Complaint Data As XML File</a></div>
            </div>
            <div class="col-md-3">
            @if (!in_array($sessData['Complaints'][0]->ComStatus, [294, 295, 298, 299]))
                {!! view('vendor.openpolice.complaint-report-inc-social', [
                    "pubID" => $sessData['Complaints'][0]->ComPublicID ])->render() !!}
            </div>
            <div class="col-md-3 taR">
                @if (isset($emojiTags) && trim($emojiTags) != '') <div class="fR mTn5">{!! $emojiTags !!}</div> @endif
            @endif
            </div>
        </div>
    </div>
@endif

</div> <!-- end slReport -->

@if (!$GLOBALS["SL"]->REQ->has('wdg'))
    
    <div class="slCard reportBlock mT20">

    <div class="reportSectHead">Glossary</div>
    <ul class="glossaryList">
    @if ($sessData['Complaints'][0]->ComPrivacy == $GLOBALS["SL"]->def->getID('Privacy Types', 'Submit Publicly'))
        <li><b>Full Transparency (<a href="/complaint-privacy-options" target="_blank">Privacy Setting</a>):</b>
            User opts to publish the names of civilians and police officers on this website.</li>
    @elseif ($sessData['Complaints'][0]->ComPrivacy 
        == $GLOBALS["SL"]->def->getID('Privacy Types', 'Names Visible to Police but not Public'))
        <li><b>No Names Public (<a href="/complaint-privacy-options" target="_blank">Privacy Setting</a>):</b>
            User doesn't want to publish any names on this website. 
            This includes police officers' names and badge numbers too.</li>
    @elseif ($sessData['Complaints'][0]->ComPrivacy == $GLOBALS["SL"]->def->getID('Privacy Types', 'Completely Anonymous'))
        <li><b>Anonymous (<a href="/complaint-privacy-options" target="_blank">Privacy Setting</a>):</b>
            User needs complaint to be completely anonymous, even though it will be harder to investigate. 
            No names will be published on this website. Neither OPC staff nor investigators will be able to contact them. 
            Any details that could be used for personal identification may be deleted from the database.</li>
    @endif
    @if ($sessData['Complaints'][0]->ComAwardMedallion == 'Gold')
        <li><b>Gold-Star Complaint (<a href="/frequently-asked-questions#what-is-gold-star">Optional</a>):</b> 
        This user opted to share more complete details about their police experience than a Basic Complaint.</li>
    @endif
    @foreach ($glossary as $i => $g)
        <li><b>{!! $g[0] !!}:</b> {!! $g[1] !!}</li>
    @endforeach
    </ul>
    
    </div>
    
    @if ($view != 'Investigate' || $isPublicRead) <div class="slCard reportBlock">{!! $flexArticles !!}</div> @endif
    
    </div> <!-- end container -->
@endif

@if (isset($GLOBALS["SL"]->x["isPrintPDF"]) && $GLOBALS["SL"]->x["isPrintPDF"])
    <script type="text/javascript">
    @if (!$isOwner && $view != 'Investigate')
        alert("Make sure you are logged in, so that the full complaint is visible here. Then use your browser's print tools to save this page as a PDF.");
    @endif
    setTimeout("window.print()", 1000);
    </script>
@endif