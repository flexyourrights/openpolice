<!-- resources/views/vendor/openpolice/dept-page.blade.php -->

<div id="blockWrap1099" class="w100"><center>
<div class="container" id="treeWrap1099">
<div class="fC"></div><div class="nodeAnchor"><a id="n1099" name="n1099"></a></div>
<div id="node1099" class="nodeWrap w100 taL">
<div class="p20"></div>

@if (isset($deptStuff["deptRow"]))
    <div class="row w100">
        <div class="col-md-8">
            <div class="slCard">
                <h2 class="m0 slBlueDark">{!! $deptStuff["deptRow"]->DeptName !!}</h2>
                <div class="row">
                    <div class="col-md-6">
                        <a href="/sharing-your-story/{{ $deptStuff['deptRow']->DeptSlug }}"
                            class="btn btn-primary btn-lg w100 mT20" >Share Your Story About The<br />
                            {!! str_replace('Department', 'Dept', $deptStuff["deptRow"]->DeptName) !!}</a>
                    </div>
                    <div class="col-md-6 pT20">
                        @if (isset($deptStuff["deptRow"]->DeptAddress) && trim($deptStuff["deptRow"]->DeptAddress) != '')
                            <a href="{{ $GLOBALS['SL']->mapsURL($deptStuff['deptAddy']) }}" target="_blank"
                                ><i class="fa fa-map-marker mR5" aria-hidden="true"></i> {{ $deptStuff["deptAddy"] }}</a>
                        @endif
                        @if (isset($deptStuff["deptRow"]->DeptPhoneWork) && trim($deptStuff["deptRow"]->DeptPhoneWork) != '')
                            <br /><i class="fa fa-phone mR5" aria-hidden="true"></i> {{ $deptStuff["deptRow"]->DeptPhoneWork }}
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="slCard mT20 mB20">
                <h3 class="mT0">Recent Complaints & Compliments</h3>
                <i>No complaints have been submitted for this deparment.</i>
            </div>
        </div>
        <div class="col-md-4">
            <div class="slCard">
            <style> tr.scoreRowOn td { color: #63C6FF; } tr.scoreRowOff td { color: #CCC; } </style>
            @if (isset($deptStuff["deptRow"]->DeptScoreOpenness) && intVal($deptStuff["deptRow"]->DeptScoreOpenness) > 0)
                <a class="toggleScoreInfo btn btn-info btn-lg w100" href="javascript:;"
                    >OPC Accessibility Score: <b class="mL5" style="font-weight: bold;">{{ 
                    $deptStuff["deptRow"]->DeptScoreOpenness }}</b></a>
                <div id="toggleScoreInfoDeets" class="disNon">
                    <table class="table table-striped mB0">
                    <tbody>
                    @if (isset($deptStuff["score"]) && sizeof($deptStuff["score"]) > 0)
                        @foreach ($deptStuff["score"] as $i => $s)
                            @if ($s[2])
                                <tr class="scoreRowOn">
                                <td class="tR"><i class="fa fa-check-circle mL5" aria-hidden="true"></i></td>
                                <td class="tC" style="width: 40px;">{{ $s[0] }}</td><td>{{ $s[1] }}</td></tr>
                            @else
                                <tr class="scoreRowOff"><td>&nbsp;</td>
                                <td class="tC" style="width: 40px;">{{ $s[0] }}</td><td>{{ $s[1] }}</td></tr>
                            @endif
                        @endforeach
                    @endif
                    </tbody>
                    </table>
                    <div class="p5">
                        Departments can earn a score of up to 150. 
                        <a href="/how-we-rate-departments">More about how we rate departments.</a>
                    </div>
                </div>
            @endif
            </div>
            
            <div class="slCard mT20">
                <h3 class="m0">Department Web Presence</h3>
                @if (isset($deptStuff[$deptStuff["whichOver"]]->OverWebsite) 
                    && trim($deptStuff[$deptStuff["whichOver"]]->OverWebsite) != '')
                    <a href="{{ $deptStuff[$deptStuff['whichOver']]->OverWebsite }}" target="_blank" 
                    class="fPerc125 disBlo mB10">{{ 
                    $GLOBALS["SL"]->urlClean($deptStuff[$deptStuff["whichOver"]]->OverWebsite) }}</a>
                @endif
                @if (isset($deptStuff[$deptStuff["whichOver"]]->OverFacebook) 
                    && trim($deptStuff[$deptStuff["whichOver"]]->OverFacebook) != '')
                    <a href="{{ $deptStuff[$deptStuff['whichOver']]->OverFacebook }}" target="_blank"
                    ><i class="fa fa-facebook-square mR5" aria-hidden="true"></i> {{ 
                    $GLOBALS["SL"]->urlClean($deptStuff[$deptStuff["whichOver"]]->OverFacebook) }}</a><br />
                @endif
                @if (isset($deptStuff[$deptStuff["whichOver"]]->OverTwitter) 
                    && trim($deptStuff[$deptStuff["whichOver"]]->OverTwitter) != '')
                    <a href="{{ $deptStuff[$deptStuff['whichOver']]->OverTwitter }}" target="_blank"
                    ><i class="fa fa-twitter-square mR5" aria-hidden="true"></i> {{ 
                    $GLOBALS["SL"]->urlClean($deptStuff[$deptStuff["whichOver"]]->OverTwitter) }}</a><br />
                @endif
                @if (isset($deptStuff[$deptStuff["whichOver"]]->OverYouTube) 
                    && trim($deptStuff[$deptStuff["whichOver"]]->OverYouTube) != '')
                    <a href="{{ $deptStuff[$deptStuff['whichOver']]->OverYouTube }}" target="_blank"
                    ><i class="fa fa-youtube-play mR5" aria-hidden="true"></i> {{ 
                    $GLOBALS["SL"]->urlClean($deptStuff[$deptStuff["whichOver"]]->OverYouTube) }}</a><br />
                @endif
            </div>
            
            <a name="how"></a>
            <div class="slCard mT20">
                <h3 class="m0">How To File A Complaint</h3>
                @if (isset($deptStuff[$deptStuff["whichOver"]]->OverOfficialFormNotReq) 
                    && intVal($deptStuff[$deptStuff["whichOver"]]->OverOfficialFormNotReq) == 1
                    && isset($deptStuff[$deptStuff["whichOver"]]->OverWaySubEmail) 
                    && intVal($deptStuff[$deptStuff["whichOver"]]->OverWaySubEmail) == 1
                    && isset($deptStuff[$deptStuff["whichOver"]]->OverEmail) 
                    && trim($deptStuff[$deptStuff["whichOver"]]->OverEmail) != '')
                    <div class="alert alert-success mT10" role="alert">
                        <h4 class="m0"><i class="fa fa-trophy mR5" aria-hidden="true"></i> OPC-Compatible Department</h4>
                    </div>
                    <p class="mB20">
                        This police department's policy permits them to investigate complaints sent via email. 
                        They also accept complaints filed on non-department forms. 
                        <b class="bld">That means OPC will automically file your report after you share your story!</b>
                    </p><p>
                        The information below includes other ways to submit a formal complaint or compliment to the 
                        oversight agency.
                    </p>
                @else
                    <p class="mB20">
                        This department does not investigate OPC reports sent by email. We recommend you share your story 
                        here on OpenPolice.org. Then use the information below to submit a formal complaint or compliment 
                        to the oversight agency for investigation.
                    </p>
                @endif
            </div>
            
            <div class="slCard mT20">
            @if ($deptStuff["whichOver"] == 'civRow')
                <h3 class="m0 slBlueDark">{{ $deptStuff[$deptStuff["whichOver"]]->OverAgncName }}</h3>
                <p class="gry9">
                This is the agency that collects complaints for the {!! $deptStuff["deptRow"]->DeptName !!}.
                This is where we recommend that OPC users file their complaints.
                </p>
                @if (isset($deptStuff["civAddy"]) && trim($deptStuff["civAddy"]) != '')
                    <a href="{{ $GLOBALS['SL']->mapsURL($deptStuff['civAddy']) }}" target="_blank"
                        ><i class="fa fa-map-marker mR5" aria-hidden="true"></i> {!! $deptStuff["civAddy"] !!}</a><br />
                @endif
            @else
                <h2 class="m0">Internal Affairs</h2>
                <div class="mBn5">&nbsp;</div>
                @if (isset($deptStuff["iaAddy"]) && trim($deptStuff["iaAddy"]) != '')
                    <a href="{{ $GLOBALS['SL']->mapsURL($deptStuff['iaAddy']) }}" target="_blank"
                        ><i class="fa fa-map-marker mR5" aria-hidden="true"></i> {!! $deptStuff["iaAddy"] !!}</a><br />
                @endif
            @endif
                
                    @if (isset($deptStuff[$deptStuff["whichOver"]]->OverPhoneWork) 
                        && trim($deptStuff[$deptStuff["whichOver"]]->OverPhoneWork) != '')
                        <i class="fa fa-phone mR5" aria-hidden="true"></i> 
                        {{ $deptStuff[$deptStuff["whichOver"]]->OverPhoneWork }}<br />
                    @endif
                    <br />
                    
                    @if (isset($deptStuff[$deptStuff["whichOver"]]->OverWaySubOnline) 
                        && intVal($deptStuff[$deptStuff["whichOver"]]->OverWaySubOnline) == 1
                        && isset($deptStuff[$deptStuff["whichOver"]]->OverComplaintWebForm) 
                        && trim($deptStuff[$deptStuff["whichOver"]]->OverComplaintWebForm) != '')
                        You can submit your complaint through this oversight agency's online complaint form. 
                        (TIP: Drop in a link to your OPC complaint too!)<br />
                   {!! $GLOBALS["SL"]->swapURLwrap($deptStuff[$deptStuff["whichOver"]]->OverComplaintWebForm, false) !!}
                        </a><br /><br />
                    @endif
                    @if (isset($deptStuff[$deptStuff["whichOver"]]->OverWaySubEmail) 
                        && intVal($deptStuff[$deptStuff["whichOver"]]->OverWaySubEmail) == 1
                        && isset($deptStuff[$deptStuff["whichOver"]]->OverEmail) 
                        && trim($deptStuff[$deptStuff["whichOver"]]->OverEmail) != '')
                        You can submit your complaint by emailing this oversight agency. 
                        (We recommend you include a link to your OPC complaint in your email.)<br />
                        <a href="mailto:{{ $deptStuff[$deptStuff['whichOver']]->OverEmail }}">{{ 
                        $deptStuff[$deptStuff["whichOver"]]->OverEmail }}</a><br /><br />
                    @endif
                    @if (isset($deptStuff[$deptStuff["whichOver"]]->OverComplaintPDF) 
                        && trim($deptStuff[$deptStuff["whichOver"]]->OverComplaintPDF) != '')
                        This oversight agency has a PDF form you can print out.<br />
                       {!! $GLOBALS["SL"]->swapURLwrap($deptStuff[$deptStuff["whichOver"]]->OverComplaintPDF, false) !!}
                        <br /><br />
                    @endif
                    
                    If you submit your complaint on paper, we recommend that you staple a copy of your full OPC complaint together 
                    with the department form.<br /><br />
                    
                    @if (isset($deptStuff[$deptStuff["whichOver"]]->OverWebComplaintInfo) 
                        && trim($deptStuff[$deptStuff["whichOver"]]->OverWebComplaintInfo) != '')
                            Complaint process information:<br />
                   {!! $GLOBALS["SL"]->swapURLwrap($deptStuff[$deptStuff["whichOver"]]->OverWebComplaintInfo, false) !!}
                        <br /><br />
                    @endif
                
                    <ul style="padding-left: 30px;">
                    @if (!isset($deptStuff[$deptStuff["whichOver"]]->OverOfficialFormNotReq) 
                        || intVal($deptStuff[$deptStuff["whichOver"]]->OverOfficialFormNotReq) == 0)
                        <li>Only complaints submitted on department forms will be investigated.</li>
                    @endif
                    @if (isset($deptStuff[$deptStuff["whichOver"]]->OverWaySubNotary) 
                        && intVal($deptStuff[$deptStuff["whichOver"]]->OverWaySubNotary) == 1)
                        <li>Submitted complaints may require a notary to be investigated.</li>
                    @endif
                    @if (!isset($deptStuff[$deptStuff["whichOver"]]->OverOfficialAnon) 
                        || intVal($deptStuff[$deptStuff["whichOver"]]->OverOfficialAnon) == 0)
                        <li>Anonymous complaints will not be investigated.</li>
                    @endif
                    @if (isset($deptStuff[$deptStuff["whichOver"]]->OverWaySubVerbalPhone) 
                        && intVal($deptStuff[$deptStuff["whichOver"]]->OverWaySubVerbalPhone) == 1)
                        <li>Complaints submitted by phone will be investigated.</li>
                    @endif
                    @if (isset($deptStuff[$deptStuff["whichOver"]]->OverWaySubPaperMail) 
                        && intVal($deptStuff[$deptStuff["whichOver"]]->OverWaySubPaperMail) == 1)
                        <li>Complaints submitted by snail mail will be investigated.</li>
                    @endif
                    @if (isset($deptStuff[$deptStuff["whichOver"]]->OverWaySubPaperInPerson) 
                        && intVal($deptStuff[$deptStuff["whichOver"]]->OverWaySubPaperInPerson) == 1)
                        <li>Complaints submitted in person will be investigated.</li>
                    @endif
                    @if (isset($deptStuff[$deptStuff["whichOver"]]->OverSubmitDeadline) 
                        && intVal($deptStuff[$deptStuff["whichOver"]]->OverSubmitDeadline) > 0)
                        <li>Complaints must be submitted within {{ 
                        number_format($deptStuff[$deptStuff["whichOver"]]->OverSubmitDeadline) 
                        }} days of your incident to be investigated.</li>
                    @endif
                    </ul>
                    
                @if ($deptStuff["whichOver"] == 'civRow')
                    <div class="mT20"></div>
                    <h3 class="m0">Internal Affairs Office</h3>
                    @if (isset($deptStuff["iaAddy"]) && trim($deptStuff["iaAddy"]) != '')
                        <a href="{{ $GLOBALS['SL']->mapsURL($deptStuff['iaAddy']) }}" target="_blank"
                            ><i class="fa fa-map-marker mR5" aria-hidden="true"></i> {!! $deptStuff["iaAddy"] !!}</a><br />
                    @endif
                    @if (isset($deptStuff["iaRow"]->OverPhoneWork) 
                        && trim($deptStuff["iaRow"]->OverPhoneWork) != '')
                        <i class="fa fa-phone mR5" aria-hidden="true"></i> {{ $deptStuff["iaRow"]->OverPhoneWork }}<br />
                    @endif
                @endif
            </div>
            
            <div class="slCard mT20">
                <h4 class="m0">See something inaccurate? Please <a href="/contact">contact us</a> or 
                <a href="/dashboard/volunteer">volunteer</a> to update these records.</h4>
            </div>
            
        </div>
    </div>
@endif


</div> <!-- end #node1099 -->
</div></center>
</div>
