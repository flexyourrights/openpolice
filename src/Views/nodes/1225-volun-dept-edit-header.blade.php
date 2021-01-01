<!-- resources/views/vendor/openpolice/nodes/1225-volun-dept-edit-header.blade.php -->
<div class="nodeAnchor"><a id="deptContact" name="deptContact"></a></div>
<div id="fixedHeadWidth" class="w100"> </div>
<div id="fixedHeader" class="fixed" style="margin-top: 38px;">
    <h3 class="disIn m0 slBlueDark">{{ $deptRow->dept_name }}</h3>
    <b class="mL20 slGreenDark"><nobr>Accessibility Score: 
        <div id="opcScore" class="disIn mL10">{{ 
            intVal($deptRow->dept_score_openness)
        }}</div></nobr>
    </b>
</div>
<div style="margin: 90px 0px -100px 0px;">
    <a href="/dept/{{ $deptRow->dept_slug }}" target="_blank">
        <i class="fa fa-external-link mR5" aria-hidden="true"></i>
        openpolice.org/dept/{{ $deptRow->dept_slug }}</a><br />
    {!! $editsSummary[0] !!}
</div>
<style>
#pageTopGapID { 
    display: none; 
}
#fixedHeader, #fixedHeader.fixed {
    margin: 55px 0px 0px -16px;
}
#slTopTabsWrap, #slTopTabsWrap .slTopTabs, #fixedHeader, #fixedHeader.fixed {
    background: #F5FBFF;
}
@media screen and (max-width: 480px) {

}
</style>
<script type="text/javascript">
anchorOffsetBonus = -140;
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
            document.getElementById("opcScore").innerHTML = "<b>"+score+"</b>";
        }
        if (document.getElementById("admMenuCustBot")) {
            var newHgt = Math.round($(window).height())-335;
            document.getElementById("admMenuCustBot").style.height=""+newHgt+"px";
        }
        setTimeout(function() { chkDeptScore(); }, 3000);
    }
    setTimeout(function() { chkDeptScore(); }, 100);
    
});
</script>