<!-- resources/views/vendor/openpolice/dept-page.blade.php -->

<div id="blockWrap1099" class="w100"><center>
<div class="container" id="treeWrap1099">
<div class="fC"></div><div class="nodeAnchor"><a id="n1099" name="n1099"></a></div>
<div id="node1099" class="nodeWrap w100 taL">
<div class="p20"></div>

@if (isset($d["deptRow"]))
    <div class="row w100">
        <div class="col-md-8">
            <div class="slCard">
                <h2 class="m0 slBlueDark">{!! $d["deptRow"]->DeptName !!}</h2>
                <div class="row">
                    <div class="col-md-6">
                        <a href="/sharing-your-story/{{ $d['deptRow']->DeptSlug }}"
                            class="btn btn-primary btn-lg w100 mT20" >Share Your Story About The<br />
                            {!! str_replace('Department', 'Dept', $d["deptRow"]->DeptName) !!}</a>
                    </div>
                    <div class="col-md-6 pT20">
                        @if (isset($d["deptRow"]->DeptAddress) && trim($d["deptRow"]->DeptAddress) != '')
                            <a href="{{ $GLOBALS['SL']->mapsURL($d['deptAddy']) }}" target="_blank"
                                ><i class="fa fa-map-marker mR5" aria-hidden="true"></i> {{ $d["deptAddy"] }}</a>
                        @endif
                        @if (isset($d["deptRow"]->DeptPhoneWork) && trim($d["deptRow"]->DeptPhoneWork) != '')
                            <br /><i class="fa fa-phone mR5" aria-hidden="true"></i> {{ $d["deptRow"]->DeptPhoneWork }}
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="slCard mT20 mB20">
                <h3 class="mT0">Recent Complaints & Compliments</h3>
                {!! $previews !!}
                <p><i class="slGrey">OpenPolice.org is currently beta testing with individual complainants,
                and is not yet open to the public.</i></p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="slCard">
            @if (isset($d["deptRow"]->DeptScoreOpenness) && intVal($d["deptRow"]->DeptScoreOpenness) > 0)
                <div class="toggleScoreInfo round10 p20 taC fPerc133
                    @if ($d['deptRow']->DeptScoreOpenness >= 70) btn-primary-simple 
                    @else btn-danger-simple @endif w100 mB20">
                    <div class="pT10 mBn20">Accessibility Grade:</div>
                    <div class="icoHuge mBn5">{{ $GLOBALS["SL"]->calcGrade($d["deptRow"]->DeptScoreOpenness) }}</div>
                </div>
                
                <a class="toggleScoreInfo btn btn-default btn-lg w100 taL" href="javascript:;"
                    ><i id="scoreChev" class="fa fa-chevron-right" aria-hidden="true" style="width: 18px;"></i>
                    OPC Accessibility Score: <b class="mL5" style="font-weight: bold;">{{ 
                    $d["deptRow"]->DeptScoreOpenness }}</b></a>
                <div id="toggleScoreInfoDeets" class="disNon">
                    @if (isset($d["score"]) && sizeof($d["score"]) > 0)
                        @foreach ($d["score"] as $i => $s)
                            @if ($s[2])
                                <div class=" @if ($i%2 == 0) row2 @endif scoreRowOn"><div class="row">
                                <div class="col-md-1"><i class="fa fa-check-circle mL5" aria-hidden="true"></i></div>
                            @else
                                <div class=" @if ($i%2 == 0) row2 @endif scoreRowOff"><div class="row">
                                <div class="col-md-1">&nbsp;</div>
                            @endif
                                <div class="col-md-1 taR">{{ $s[0] }}</div>
                                <div class="col-md-9">{{ $s[1] }}</div>
                            </div></div>
                        @endforeach
                    @endif
                    <div class="p5">
                        Departments can earn a score of up to 100. 
                        <a href="/departments">More about how we rate departments.</a>
                    </div>
                </div>
            @endif
            @if (isset($d["deptRow"]->DeptVerified) && trim($d["deptRow"]->DeptVerified) != '')
                <div class="mT10 slGrey">
                Department info updated {{ date('n/j/y', strtotime($d["deptRow"]->DeptVerified)) }}</div>
            @endif
            </div>
            
            <div class="slCard mT20">
                <h3 class="m0">Department Web Presence</h3>
                @if (isset($d[$d["whichOver"]]->OverWebsite) 
                    && trim($d[$d["whichOver"]]->OverWebsite) != '')
                    <a href="{{ $d[$d['whichOver']]->OverWebsite }}" target="_blank" 
                    class="fPerc125 disBlo mB10">{{ 
                    $GLOBALS["SL"]->urlClean($d[$d["whichOver"]]->OverWebsite) }}</a>
                @endif
                @if (isset($d[$d["whichOver"]]->OverFacebook) 
                    && trim($d[$d["whichOver"]]->OverFacebook) != '')
                    <a href="{{ $d[$d['whichOver']]->OverFacebook }}" target="_blank"
                    ><i class="fa fa-facebook-square mR5" aria-hidden="true"></i> {{ 
                    $GLOBALS["SL"]->urlClean($d[$d["whichOver"]]->OverFacebook) }}</a><br />
                @endif
                @if (isset($d[$d["whichOver"]]->OverTwitter) 
                    && trim($d[$d["whichOver"]]->OverTwitter) != '')
                    <a href="{{ $d[$d['whichOver']]->OverTwitter }}" target="_blank"
                    ><i class="fa fa-twitter-square mR5" aria-hidden="true"></i> {{ 
                    $GLOBALS["SL"]->urlClean($d[$d["whichOver"]]->OverTwitter) }}</a><br />
                @endif
                @if (isset($d[$d["whichOver"]]->OverYouTube) 
                    && trim($d[$d["whichOver"]]->OverYouTube) != '')
                    <a href="{{ $d[$d['whichOver']]->OverYouTube }}" target="_blank"
                    ><i class="fa fa-youtube-play mR5" aria-hidden="true"></i> {{ 
                    $GLOBALS["SL"]->urlClean($d[$d["whichOver"]]->OverYouTube) }}</a><br />
                @endif
            </div>
            
            <a name="how"></a>
            <div class="slCard mT20">
                <h3 class="m0">How To File A Complaint</h3>
                @if (isset($d[$d["whichOver"]]->OverOfficialFormNotReq) 
                    && intVal($d[$d["whichOver"]]->OverOfficialFormNotReq) == 1
                    && isset($d[$d["whichOver"]]->OverWaySubEmail) 
                    && intVal($d[$d["whichOver"]]->OverWaySubEmail) == 1
                    && isset($d[$d["whichOver"]]->OverEmail) 
                    && trim($d[$d["whichOver"]]->OverEmail) != '')
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
            @if ($d["whichOver"] == 'civRow')
                <h3 class="m0 slBlueDark">{{ $d[$d["whichOver"]]->OverAgncName }}</h3>
                <p class="gry9">
                This is the agency that collects complaints for the {!! $d["deptRow"]->DeptName !!}.
                This is where we recommend that OPC users file their complaints.
                </p>
                @if (isset($d["civAddy"]) && trim($d["civAddy"]) != '')
                    <a href="{{ $GLOBALS['SL']->mapsURL($d['civAddy']) }}" target="_blank"
                        ><i class="fa fa-map-marker mR5" aria-hidden="true"></i> {!! $d["civAddy"] !!}</a><br />
                @endif
            @else
                <h2 class="m0">Internal Affairs</h2>
                <div class="mBn5">&nbsp;</div>
                @if (isset($d["iaAddy"]) && trim($d["iaAddy"]) != '')
                    <a href="{{ $GLOBALS['SL']->mapsURL($d['iaAddy']) }}" target="_blank"
                        ><i class="fa fa-map-marker mR5" aria-hidden="true"></i> {!! $d["iaAddy"] !!}</a><br />
                @endif
            @endif
                
                    @if (isset($d[$d["whichOver"]]->OverPhoneWork) 
                        && trim($d[$d["whichOver"]]->OverPhoneWork) != '')
                        <i class="fa fa-phone mR5" aria-hidden="true"></i> 
                        {{ $d[$d["whichOver"]]->OverPhoneWork }}<br />
                    @endif
                    <br />
                    
                    @if (isset($d[$d["whichOver"]]->OverWaySubOnline) 
                        && intVal($d[$d["whichOver"]]->OverWaySubOnline) == 1
                        && isset($d[$d["whichOver"]]->OverComplaintWebForm) 
                        && trim($d[$d["whichOver"]]->OverComplaintWebForm) != '')
                        You can submit your complaint through this oversight agency's online complaint form. 
                        (TIP: Drop in a link to your OPC complaint too!)<br />
                   {!! $GLOBALS["SL"]->swapURLwrap($d[$d["whichOver"]]->OverComplaintWebForm, false) !!}
                        </a><br /><br />
                    @endif
                    @if (isset($d[$d["whichOver"]]->OverWaySubEmail) 
                        && intVal($d[$d["whichOver"]]->OverWaySubEmail) == 1
                        && isset($d[$d["whichOver"]]->OverEmail) 
                        && trim($d[$d["whichOver"]]->OverEmail) != '')
                        You can submit your complaint by emailing this oversight agency. 
                        (We recommend you include a link to your OPC complaint in your email.)<br />
                        <a href="mailto:{{ $d[$d['whichOver']]->OverEmail }}">{{ 
                        $d[$d["whichOver"]]->OverEmail }}</a><br /><br />
                    @endif
                    @if (isset($d[$d["whichOver"]]->OverComplaintPDF) 
                        && trim($d[$d["whichOver"]]->OverComplaintPDF) != '')
                        This oversight agency has a PDF form you can print out.<br />
                       {!! $GLOBALS["SL"]->swapURLwrap($d[$d["whichOver"]]->OverComplaintPDF, false) !!}
                        <br /><br />
                    @endif
                    
                    If you submit your complaint on paper, we recommend that you staple a copy of your full OPC complaint together 
                    with the department form.<br /><br />
                    
                    @if (isset($d[$d["whichOver"]]->OverWebComplaintInfo) 
                        && trim($d[$d["whichOver"]]->OverWebComplaintInfo) != '')
                            Complaint process information:<br />
                   {!! $GLOBALS["SL"]->swapURLwrap($d[$d["whichOver"]]->OverWebComplaintInfo, false) !!}
                        <br /><br />
                    @endif
                
                    <ul style="padding-left: 30px;">
                    @if (!isset($d[$d["whichOver"]]->OverOfficialFormNotReq) 
                        || intVal($d[$d["whichOver"]]->OverOfficialFormNotReq) == 0)
                        <li>Only complaints submitted on department forms will be investigated.</li>
                    @endif
                    @if (isset($d[$d["whichOver"]]->OverWaySubNotary) 
                        && intVal($d[$d["whichOver"]]->OverWaySubNotary) == 1)
                        <li>Submitted complaints may require a notary to be investigated.</li>
                    @endif
                    @if (!isset($d[$d["whichOver"]]->OverOfficialAnon) 
                        || intVal($d[$d["whichOver"]]->OverOfficialAnon) == 0)
                        <li>Anonymous complaints will not be investigated.</li>
                    @endif
                    @if (isset($d[$d["whichOver"]]->OverWaySubVerbalPhone) 
                        && intVal($d[$d["whichOver"]]->OverWaySubVerbalPhone) == 1)
                        <li>Complaints submitted by phone will be investigated.</li>
                    @endif
                    @if (isset($d[$d["whichOver"]]->OverWaySubPaperMail) 
                        && intVal($d[$d["whichOver"]]->OverWaySubPaperMail) == 1)
                        <li>Complaints submitted by snail mail will be investigated.</li>
                    @endif
                    @if (isset($d[$d["whichOver"]]->OverWaySubPaperInPerson) 
                        && intVal($d[$d["whichOver"]]->OverWaySubPaperInPerson) == 1)
                        <li>Complaints submitted in person will be investigated.</li>
                    @endif
                    @if (isset($d[$d["whichOver"]]->OverSubmitDeadline) 
                        && intVal($d[$d["whichOver"]]->OverSubmitDeadline) > 0)
                        <li>Complaints must be submitted within {{ 
                        number_format($d[$d["whichOver"]]->OverSubmitDeadline) 
                        }} days of your incident to be investigated.</li>
                    @endif
                    </ul>
                    
                @if ($d["whichOver"] == 'civRow')
                    <div class="mT20"></div>
                    <h3 class="m0">Internal Affairs Office</h3>
                    @if (isset($d["iaAddy"]) && trim($d["iaAddy"]) != '')
                        <a href="{{ $GLOBALS['SL']->mapsURL($d['iaAddy']) }}" target="_blank"
                            ><i class="fa fa-map-marker mR5" aria-hidden="true"></i> {!! $d["iaAddy"] !!}</a><br />
                    @endif
                    @if (isset($d["iaRow"]->OverPhoneWork) 
                        && trim($d["iaRow"]->OverPhoneWork) != '')
                        <i class="fa fa-phone mR5" aria-hidden="true"></i> {{ $d["iaRow"]->OverPhoneWork }}<br />
                    @endif
                @endif
            </div>
            
            <div class="slCard mT20">
                <h4 class="m0">See something inaccurate? Please <a href="/contact">contact us</a> or 
                <a href="/volunteer">volunteer</a> to update these records.</h4>
            </div>
            
        </div>
    </div>
@endif


</div> <!-- end #node1099 -->
</div></center>
</div>

<script type="text/javascript">
$(document).ready(function(){
    $(document).on("click", ".toggleScoreInfo", function() {
        if (document.getElementById("toggleScoreInfoDeets") && document.getElementById("scoreChev")) {
            if (document.getElementById("toggleScoreInfoDeets").style.display != "block") {
                document.getElementById("scoreChev").className = "fa fa-chevron-down";
                $("#toggleScoreInfoDeets").slideDown("fast");
            } else {
                document.getElementById("scoreChev").className = "fa fa-chevron-right";
                $("#toggleScoreInfoDeets").slideUp("fast");
            }
        }
    });
});
</script>
