<!-- resources/views/vendor/openpolice/nodes/2844-report-staff-form-first-review.blade.php -->

<form name="firstReviewForm" id="firstReviewFormID" method="get" 
    src="/complaint-toolbox?cid={{ $complaintRec->com_id 
    }}&ajax=1&firstReview=1">
@if ($GLOBALS['SL']->REQ->has('frame'))
    <input type="hidden" name="frame" value="1">
@endif
<input type="hidden" name="cidi" value="{{ $complaintRec->com_id }}">
<input type="hidden" name="ajax" value="1">
<input type="hidden" name="refresh" value="1">
<input type="hidden" name="firstReview" value="1">

<div class="row">
    <div class="col-sm-6">

        <label for="n1712fld0" id="n1712fld0lab" class="finger">
            <div class="disIn mR5">
                <input id="n1712fld0" value="296" type="radio" name="n1712fld" 
                    class="nCbox1712 slTab slNodeChange ntrStp n1712fldCls" 
                    data-nid="1712" autocomplete="off" tabindex="1">
            </div> Police Complaint
        </label>
        <label for="n1712fld1" id="n1712fld1lab" class="finger">
            <div class="disIn mR5">
                <input id="n1712fld1" value="297" type="radio" name="n1712fld" 
                    class="nCbox1712 slTab slNodeChange ntrStp n1712fldCls" 
                    data-nid="1712" autocomplete="off" tabindex="2">
            </div> Not About Police
        </label>
        <label for="n1712fld2" id="n1712fld2lab" class="finger">
            <div class="disIn mR5">
                <input id="n1712fld2" value="722" type="radio" name="n1712fld" 
                    class="nCbox1712 slTab slNodeChange ntrStp n1712fldCls" 
                    data-nid="1712" autocomplete="off" tabindex="4">
            </div> Corrections Case
        </label>

    </div>
    <div class="col-sm-6">

        <label for="n1712fld3" id="n1712fld3lab" class="finger">
            <div class="disIn mR5">
                <input id="n1712fld3" value="298" type="radio" name="n1712fld" 
                    class="nCbox1712 slTab slNodeChange ntrStp n1712fldCls" 
                    data-nid="1712" autocomplete="off" tabindex="6">
            </div> Abuse
        </label>
        <label for="n1712fld4" id="n1712fld4lab" class="finger">
            <div class="disIn mR5">
                <input id="n1712fld4" value="299" type="radio" name="n1712fld" 
                    class="nCbox1712 slTab slNodeChange ntrStp n1712fldCls" 
                    data-nid="1712" autocomplete="off" tabindex="7">
            </div> Spam
        </label>
        <label for="n1712fld5" id="n1712fld5lab" class="finger">
            <div class="disIn mR5">
                <input id="n1712fld5" value="300" type="radio" name="n1712fld" 
                    class="nCbox1712 slTab slNodeChange ntrStp n1712fldCls" 
                    data-nid="1712" autocomplete="off" tabindex="8">
            </div> Test
        </label>

    </div>
</div>

<div class="pT15">
    <input type="submit" class="btn btn-primary btn-lg slTab"
        value="Set Submission Type" {{ $GLOBALS['SL']->tabInd() }} >
</div>

</form>

<script type="text/javascript"> $(document).ready(function(){

function postToolboxFirstReview() {
    if (document.getElementById('complaintToolbox') && document.firstReviewForm.n1712fld && document.firstReviewForm.n1712fld.value && document.firstReviewForm.n1712fld.value > 0) {
        var url = "/complaint-toolbox?cidi={{ $complaintRec->com_id }}&ajax=1&refresh=1&open=status&firstReview=1&n1712fld="+document.firstReviewForm.n1712fld.value;
        $("#complaintToolbox").load(url);
    }
    return false;
}

$("#firstReviewFormID").submit(function( event ) {
    postToolboxFirstReview();
    event.preventDefault();
});

}); </script>
