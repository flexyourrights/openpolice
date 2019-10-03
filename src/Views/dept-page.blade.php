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
                <a href="/join-beta-test/{{ $d['deptRow']->DeptSlug }}"
            <?php /* href="/share-complaint-or-compliment/{{ $d['deptRow']->DeptSlug }}" */ ?>
                    class="btn btn-primary btn-lg w100">Share Your Complaint or Compliment with the {!! 
                        str_replace('Police Dept', '<nobr>Police Dept</nobr>', 
                            str_replace('Department', 'Dept', $d["deptRow"]->DeptName))
                    !!}</a>
            </div>
            
            <div class="slCard mT20 mB20">
                <h3 class="mT0">Recent Complaints & Compliments</h3>
                {!! $previews !!}
                <div class="mBn5"><p>
                <a href="/join-beta-test/{{ $d['deptRow']->DeptSlug }}">
            <?php /* href="/share-complaint-or-compliment/{{ $d['deptRow']->DeptSlug }}" */ ?>
                OpenPolice.org is currently beta testing with individual complainants.
                </a></p></div>
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
                    Accessibility Score: <b class="mL5" style="font-weight: bold;">{{ 
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
            
            {!! view('vendor.openpolice.dept-page-filing-instructs', [ "d" => $d ])->render() !!}
            
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
