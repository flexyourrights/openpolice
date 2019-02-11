<!-- resources/views/vendor/openpolice/nodes/1225-volun-dept-edit-header.blade.php -->
<div class="nodeAnchor"><a id="deptContact" name="deptContact"></a></div>
<div id="fixedHeader" class="fixed">
    <div class="row mT5">
        <div class="col-9">
            <h2 class="slBlueDark m0">{{ str_replace('Department', 'Dept', $deptRow->DeptName) }}</h2>
        </div><div class="col-3 taR">
            <a href="javascript:;" id="hidivBtnScoreDeet" class="fR disBlo hidivBtn pT10">
                <h3 class="m0 slGreenLight"><nobr>OPC Accessibility Score: <b id="opcScore" class="mL10">{{ 
                    intVal($deptRow->DeptScoreOpenness) }}</b></nobr></h3></a>
            <div class="fC"></div>
        </div>
    </div>
    <div class="slGrey mB10">
        {{ $deptRow->DeptAddressCity }}, {{ $deptRow->DeptAddressState }} {{ $deptRow->DeptAddressZip }}
        {!! $editsSummary[0] !!}
    </div>
</div>
<style>
#pageTopGapID { display: none; }
.nodeAnchor a { top: -100px; }
#mainBody, #fixedHeader, .fixed, #fixedHeader.fixed { background: #F5FBFF; }
#leftSideWrap, #leftAdmMenu, #admMenuCustom { background: #FCFEFF; }
</style>
<script type="text/javascript">
$(document).ready(function(){
    function chkDeptScore() {
        if (document.getElementById("n1226FldID") && document.getElementById("opcScore")) {
            var score = 0;
            if (document.getElementById("n1272FldID") && document.getElementById("n1272FldID").value.trim() != '') {
                score+={{ $deptScores->vals["HasWebsite"]->score }};
            }
            if (document.getElementById("n1274FldID") && document.getElementById("n1274FldID").value.trim() != '') {
                score+={{ $deptScores->vals["HasFace"]->score }};
            }
            if (document.getElementById("n1275FldID") && document.getElementById("n1275FldID").value.trim() != '') {
                score+={{ $deptScores->vals["HasTwit"]->score }};
            }
            if (document.getElementById("n1276FldID") && document.getElementById("n1276FldID").value.trim() != '') {
                score+={{ $deptScores->vals["HasYou"]->score }};
            }
            if (document.getElementById("n1278fld0") && document.getElementById("n1278fld0").checked) {
                score+={{ $deptScores->vals["WebInfoHome"]->score }};
            }
            if (document.getElementById("n1279FldID") && document.getElementById("n1279FldID").value.trim() != '') {
                score+={{ $deptScores->vals["WebInfo"]->score }};
            }
            if (document.getElementById("n1280FldID") && document.getElementById("n1280FldID").value.trim() != '') {
                score+={{ $deptScores->vals["PdfForm"]->score }};
            }
            if (document.getElementById("n1286FldID") && document.getElementById("n1286FldID").value.trim() != '') {
                score+={{ $deptScores->vals["WebForm"]->score }};
            }
            if (document.getElementById("n1285fld0") && document.getElementById("n1285fld0").checked) {
                score+={{ $deptScores->vals["ByEmail"]->score }};
            }
            if (document.getElementById("n1285fld1") && document.getElementById("n1285fld1").checked) {
                score+={{ $deptScores->vals["ByPhone"]->score }};
            }
            if (document.getElementById("n1285fld2") && document.getElementById("n1285fld2").checked) {
                score+={{ $deptScores->vals["ByPostal"]->score }};
            }
            if (document.getElementById("n1287fld0") && document.getElementById("n1287fld0").checked) {
                score+={{ $deptScores->vals["OfficForm"]->score }};
            }
            if (document.getElementById("n1287fld1") && document.getElementById("n1287fld1").checked) {
                score+={{ $deptScores->vals["Anonymous"]->score }};
            }
            if (document.getElementById("n1287fld2") && document.getElementById("n1287fld2").checked) {
                score{{ str_replace('-', '-=', $deptScores->vals["Notary"]->score) }};
            }
            document.getElementById("n1226FldID").value = score;
            document.getElementById("opcScore").innerHTML = score;
        }
        if (document.getElementById("n1329fld0") && document.getElementById("n1329fld0").checked) {
            if (document.getElementById("saveStar1")) document.getElementById("saveStar1").src="/openpolice/star1.png";
        } else {
            if (document.getElementById("saveStar1")) document.getElementById("saveStar1").src="/openpolice/star1-gry.png";
        }
        if (document.getElementById("n1329fld1") && document.getElementById("n1329fld1").checked) {
            if (document.getElementById("saveStar2")) document.getElementById("saveStar2").src="/openpolice/star1.png";
            if (document.getElementById("saveStar2b")) document.getElementById("saveStar2b").src="/openpolice/star1.png";
            if (document.getElementById("saveStar2c")) document.getElementById("saveStar2c").src="/openpolice/star1.png";
        } else {
            if (document.getElementById("saveStar2")) document.getElementById("saveStar2").src="/openpolice/star1-gry.png";
            if (document.getElementById("saveStar2b")) document.getElementById("saveStar2b").src="/openpolice/star1-gry.png";
            if (document.getElementById("saveStar2c")) document.getElementById("saveStar2c").src="/openpolice/star1-gry.png";
        }
        if (document.getElementById("n1329fld2") && document.getElementById("n1329fld2").checked) {
            if (document.getElementById("saveStar3")) document.getElementById("saveStar3").src="/openpolice/star1.png";
            if (document.getElementById("saveStar3b")) document.getElementById("saveStar3b").src="/openpolice/star1.png";
            if (document.getElementById("saveStar3c")) document.getElementById("saveStar3c").src="/openpolice/star1.png";
        } else {
            if (document.getElementById("saveStar3")) document.getElementById("saveStar3").src="/openpolice/star1-gry.png";
            if (document.getElementById("saveStar3b")) document.getElementById("saveStar3b").src="/openpolice/star1-gry.png";
            if (document.getElementById("saveStar3c")) document.getElementById("saveStar3c").src="/openpolice/star1-gry.png";
        }
        if (document.getElementById("admMenuCustBot")) {
            var newHgt = Math.round($(window).height())-335;
            document.getElementById("admMenuCustBot").style.height=""+newHgt+"px";
        }
        setTimeout(function() { chkDeptScore(); }, 3000);
    }
    setTimeout(function() { chkDeptScore(); }, 100);
    
	$(document).on("click", "#closeScripts", function() {
        $("#hidivScripts").slideUp("fast");
        setTimeout(function() { $("#hidivBtnScripts").slideDown("fast"); }, 600);
	});
    
});
</script>