<!-- resources/views/vendor/openpolice/dept-page.blade.php -->
<div id="releaseNote" class="alert alert-danger w100 taC">
    See something inaccurate? Please <a href="/contact">contact us</a> or 
    <a href="/volunteer">volunteer</a> to update this record.
</div>

<div id="blockWrap1099" class="w100"><center>
<div class="container" id="treeWrap1099">
<div class="fC"></div><div class="nodeAnchor"><a id="n1099" name="n1099"></a></div>
<div id="node1099" class="nodeWrap w100 taL">
<div class="pT15"></div>

@if (isset($d["deptRow"]))
    <div class="row w100">
        <div class="col-lg-8">
            
            <div class="slCard">
                <h2 class="mT0">{!! $d["deptRow"]->DeptName !!}</h2>
                <p>
                @if (isset($d["deptRow"]->DeptAddress) && trim($d["deptRow"]->DeptAddress) != '')
                    <a href="{{ $GLOBALS['SL']->mapsURL($d['deptAddy']) }}" target="_blank" class="mR20"
                        ><i class="fa fa-map-marker mR5" aria-hidden="true"></i> {{ $d["deptAddy"] }}</a>
                @endif
                @if (isset($d["deptRow"]->DeptPhoneWork) && trim($d["deptRow"]->DeptPhoneWork) != '')
                    <br /><i class="fa fa-phone mR5" aria-hidden="true"></i> {{ $d["deptRow"]->DeptPhoneWork }}
                @endif
                </p>
                <div class="mT3">{!! 
                    $GLOBALS["SL"]->embedMapSimpRowAddy($nID, $d["deptRow"], 'Dept', $d["deptRow"]->DeptName, 250)
                !!}</div>
            </div>
            
            <div class="slCard mT20 mB20">
                <a href="/share-complaint-or-compliment"
                    <?php /* href="/share-complaint-or-compliment/{{ $d['deptRow']->DeptSlug }}" */ ?>
                    class="btn btn-primary btn-lg w100">Share Your Complaint or Compliment with the 
                    {!! str_replace('Department', 'Dept', $d["deptRow"]->DeptName) !!}</a>
            </div>
            
            <div class="slCard mT20 mB20">
                <h3 class="mT0">Recent Complaints & Compliments</h3>
                {!! $previews !!}
                <div class="mBn5"><p><i class="slGrey">OpenPolice.org is currently beta testing with individual complainants,
                and is not yet open to the public.</i></p></div>
            </div>
            
        </div>
        <div class="col-lg-4">
        
            <div class="slCard">
            @if (isset($d["deptRow"]->DeptScoreOpenness) && intVal($d["deptRow"]->DeptScoreOpenness) > 0)
                <div class="toggleScoreInfo round10 p20 taC fPerc133
                    @if ($d['deptRow']->DeptScoreOpenness >= 70) btn-primary-simple 
                    @else btn-danger-simple @endif w100 mB20">
                    <div class="pT10 mBn20">Accessibility Grade:</div>
                    <div class="icoHuge mBn5">{{ $GLOBALS["SL"]->calcGrade($d["deptRow"]->DeptScoreOpenness) }}</div>
                </div>
                
                <a class="toggleScoreInfo btn btn-secondary btn-lg w100 taL" href="javascript:;"
                    ><i id="scoreChev" class="fa fa-chevron-right" aria-hidden="true" style="width: 18px;"></i>
                    OPC Accessibility Score: <b class="mL5" style="font-weight: bold;">{{ 
                    $d["deptRow"]->DeptScoreOpenness }}</b></a>
                <div id="toggleScoreInfoDeets" class="disNon">
                    {!! view('vendor.openpolice.dept-inc-scores', [
                        "score" => ((isset($d["score"])) ? $d["score"] : null)
                        ])->render() !!}
                    <div class="p5">
                        Departments can earn a score of up to 100. 
                        <a href="/how-we-rate-departments">More about how we rate departments.</a>
                    </div>
                </div>
            @endif
            @if (isset($d["deptRow"]->DeptVerified) && trim($d["deptRow"]->DeptVerified) != '')
                <div class="mT10 slGrey">
                Department info updated {{ date('n/j/y', strtotime($d["deptRow"]->DeptVerified)) }}</div>
            @endif
            </div>
            
            <div class="slCard mT20">
                <h3 class="mT0">Web Presence</h3>
                @if (isset($d["iaRow"]->OverWebsite) && trim($d["iaRow"]->OverWebsite) != '')
                    <p><a href="{{ $d['iaRow']->OverWebsite }}" target="_blank" 
                        ><i class="fa fa-home mR5" aria-hidden="true"></i> {{ 
                        $GLOBALS["SL"]->urlCleanIfShort($d["iaRow"]->OverWebsite, 'Department Website') }}</a></p>
                @endif
                @if (isset($d["iaRow"]->OverFacebook) && trim($d["iaRow"]->OverFacebook) != '')
                    <p><a href="{{ $d['iaRow']->OverFacebook }}" target="_blank"
                        ><i class="fa fa-facebook-square mR5" aria-hidden="true"></i> Facebook</a></p>
                @endif
                @if (isset($d["iaRow"]->OverTwitter) && trim($d["iaRow"]->OverTwitter) != '')
                    <p><a href="{{ $d['iaRow']->OverTwitter }}" target="_blank"
                        ><i class="fa fa-twitter-square mR5" aria-hidden="true"></i> Twitter</a></p>
                @endif
                @if (isset($d["iaRow"]->OverYouTube) && trim($d["iaRow"]->OverYouTube) != '')
                    <p><a href="{{ $d['iaRow']->OverYouTube }}" target="_blank"
                        ><i class="fa fa-youtube-play mR5" aria-hidden="true"></i> YouTube</a></p>
                @endif
            </div>
            
            <a name="how"></a>
            <div class="slCard mT20">
                <h3 class="mT0">How to File Complaints with the {!! $d["deptRow"]->DeptName !!}</h3>
                @if (isset($d["iaRow"]->OverOfficialFormNotReq) && intVal($d["iaRow"]->OverOfficialFormNotReq) == 1
                    && isset($d["iaRow"]->OverWaySubEmail) && intVal($d["iaRow"]->OverWaySubEmail) == 1
                    && isset($d[$d["whichOver"]]->OverEmail) && trim($d[$d["whichOver"]]->OverEmail) != '')
                    <div class="alert alert-success mT10" role="alert"><h4 class="m0">
                        <i class="fa fa-trophy mR5" aria-hidden="true"></i> OPC-Compatible Department
                    </h4></div>
                    <p class="mB20">
                        This police department's policy permits them to investigate complaints sent via email. 
                        They also accept complaints filed on non-department forms. 
                        <b class="bld">That means OPC will automically file your report after you 
                        <a href="/share-complaint-or-compliment"
                            <?php /* href="/share-complaint-or-compliment/{{ $d['deptRow']->DeptSlug }}" */ ?>
                            >share your story</a>!</b>
                    </p><p>
                        The information below includes other ways to submit a formal complaint to the 
                        {{ $d[$d["whichOver"]]->OverAgncName }}.
                    </p>
                @else
                    <p>
                        This department does not investigate OPC reports sent by email. We recommend you 
                        <a href="/share-complaint-or-compliment"
                            <?php /* href="/share-complaint-or-compliment/{{ $d['deptRow']->DeptSlug }}" */ ?>
                            >share your story on OpenPolice.org</a>.
                        Then use the information below to submit a formal complaint 
                        to the {{ $d[$d["whichOver"]]->OverAgncName }}.
                    </p>
                @endif
            </div>
            
            <div class="slCard mT20">
            @if ($d["whichOver"] == 'civRow')
                <h3 class="mT0">{{ $d[$d["whichOver"]]->OverAgncName }}</h3>
                <p>
                This is the agency that collects complaints for the {!! $d["deptRow"]->DeptName !!}.
                This is where we recommend that OPC users file their complaints.
                </p>
                @if (isset($d["civAddy"]) && trim($d["civAddy"]) != '')
                    <p><a href="{{ $GLOBALS['SL']->mapsURL($d['civAddy']) }}" target="_blank"
                        ><i class="fa fa-map-marker mR5" aria-hidden="true"></i> {!! $d["civAddy"] !!}</a></p>
                @endif
            @elseif (isset($d["iaAddy"]) && trim($d["iaAddy"]) != '')
                <h2 class="mT0">Internal Affairs</h2>
                <p><a href="{{ $GLOBALS['SL']->mapsURL($d['iaAddy']) }}" target="_blank"
                    ><i class="fa fa-map-marker mR5" aria-hidden="true"></i> {!! $d["iaAddy"] !!}</a></p>
            @endif
                
                @if (isset($d[$d["whichOver"]]->OverPhoneWork) && trim($d[$d["whichOver"]]->OverPhoneWork) != '')
                    <p><i class="fa fa-phone mR5" aria-hidden="true"></i> {{ $d[$d["whichOver"]]->OverPhoneWork }}</p>
                @endif
                
                @if (isset($d["iaRow"]->OverWaySubOnline) && intVal($d["iaRow"]->OverWaySubOnline) == 1
                    && isset($d["iaRow"]->OverComplaintWebForm) && trim($d["iaRow"]->OverComplaintWebForm) != '')
                    <p>You can submit your complaint through this oversight agency's online complaint form. 
                    (TIP: Drop in a link to your OPC complaint too!)<br />
                    {!! $GLOBALS["SL"]->swapURLwrap($d["iaRow"]->OverComplaintWebForm, false) !!}</a></p>
                @endif
                @if (isset($d["iaRow"]->OverWaySubEmail) && intVal($d["iaRow"]->OverWaySubEmail) == 1
                    && isset($d[$d["whichOver"]]->OverEmail) && trim($d[$d["whichOver"]]->OverEmail) != '')
                    <p>You can submit your complaint by emailing this oversight agency. 
                    (We recommend you include a link to your OPC complaint in your email.)<br />
                    <a href="mailto:{{ $d[$d['whichOver']]->OverEmail }}">{{ 
                    $d[$d["whichOver"]]->OverEmail }}</a></p>
                @endif
                @if (isset($d["iaRow"]->OverComplaintPDF) && trim($d["iaRow"]->OverComplaintPDF) != '')
                    <p>This oversight agency has a PDF form you can print 
                    
                    @if (isset($d["iaRow"]->OverWaySubPaperMail) && intVal($d["iaRow"]->OverWaySubPaperMail) == 1)
                        out and mail.
                    @else out. @endif
                    <br />
                    {!! $GLOBALS["SL"]->swapURLwrap($d["iaRow"]->OverComplaintPDF, false) !!}</p>
                @endif
                
                <p>If you submit your complaint on paper, we recommend that you staple a copy of your full OPC complaint 
                together with the department form.</p>
                
                @if (isset($d["iaRow"]->OverWebComplaintInfo) && trim($d["iaRow"]->OverWebComplaintInfo) != '')
                    <p>Complaint process information:<br />
                    {!! $GLOBALS["SL"]->swapURLwrap($d["iaRow"]->OverWebComplaintInfo, false) !!}</p>
                @endif
            
                <ul style="padding-left: 30px;">
                @if (!isset($d["iaRow"]->OverOfficialFormNotReq) || intVal($d["iaRow"]->OverOfficialFormNotReq) == 0)
                    <li>Only complaints submitted on department forms will be investigated.</li>
                @endif
                @if (isset($d["iaRow"]->OverWaySubNotary) && intVal($d["iaRow"]->OverWaySubNotary) == 1)
                    <li>Submitted complaints may require a notary to be investigated.</li>
                @endif
                @if (!isset($d["iaRow"]->OverOfficialAnon) || intVal($d["iaRow"]->OverOfficialAnon) == 0)
                    <li>Anonymous complaints will not be investigated.</li>
                @endif
                @if (isset($d["iaRow"]->OverWaySubVerbalPhone) && intVal($d["iaRow"]->OverWaySubVerbalPhone) == 1)
                    <li>Complaints submitted by phone will be investigated.</li>
                @endif
                @if (isset($d["iaRow"]->OverWaySubPaperMail) && intVal($d["iaRow"]->OverWaySubPaperMail) == 1)
                    <li>Complaints submitted by snail mail will be investigated.</li>
                @endif
                @if (isset($d["iaRow"]->OverWaySubPaperInPerson) && intVal($d["iaRow"]->OverWaySubPaperInPerson) == 1)
                    <li>Complaints submitted in person will be investigated.</li>
                @endif
                @if (isset($d["iaRow"]->OverSubmitDeadline) && intVal($d["iaRow"]->OverSubmitDeadline) > 0)
                    <li>Complaints must be submitted within {{ number_format($d["iaRow"]->OverSubmitDeadline) 
                    }} days of your incident to be investigated.</li>
                @endif
                </ul>
            </div>
            
                
            @if ($d["whichOver"] == 'civRow' && isset($d["iaAddy"]) && trim($d["iaAddy"]) != '')
                 <div class="slCard mT20">
                    <h3>Internal Affairs Office</h3>
                    <p><a href="{{ $GLOBALS['SL']->mapsURL($d['iaAddy']) }}" target="_blank"
                        ><i class="fa fa-map-marker mR5" aria-hidden="true"></i> {!! $d["iaAddy"] !!}</a></p>
                    @if (isset($d["iaRow"]->OverPhoneWork) && trim($d["iaRow"]->OverPhoneWork) != '')
                        <p><i class="fa fa-phone mR5" aria-hidden="true"></i> {{ $d["iaRow"]->OverPhoneWork }}</p>
                    @endif
                </div>
            @endif
            
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
