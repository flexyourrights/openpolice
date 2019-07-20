<!-- resources/views/vendor/openpolice/nodes/1712-report-inc-staff-tools-first-review.blade.php -->
<form name="firstReviewForm" 
	src="?firstReview=1{{ (($GLOBALS['SL']->REQ->has('frame')) ? '&frame=1' : '') }}">
<div class="row">
    <div class="col-sm-6">

        <label for="n1712fld0" id="n1712fld0lab" class="finger">
            <div class="disIn mR5">
                <input id="n1712fld0" value="296" type="radio" name="n1712fld" 
                    data-nid="1712" class="nCbox1712 slTab slNodeChange ntrStp n1712fldCls" 
                    autocomplete="off" tabindex="1">
            </div> Police Complaint
        </label>
        <label for="n1712fld0" id="n1712fld0lab" class="finger">
            <div class="disIn mR5">
                <input id="n1712fld1" value="297" type="radio" name="n1712fld" 
                    data-nid="1712" class="nCbox1712 slTab slNodeChange ntrStp n1712fldCls" 
                    autocomplete="off" tabindex="2">
            </div> Not About Police
        </label>
        <label for="n1712fld0" id="n1712fld0lab" class="finger">
            <div class="disIn mR5">
                <input id="n1712fld2" value="301" type="radio" name="n1712fld" 
                    data-nid="1712" class="nCbox1712 slTab slNodeChange ntrStp n1712fldCls" 
                    autocomplete="off" tabindex="3">
            </div> Not Sure
        </label>

    </div>
    <div class="col-sm-6">

        <label for="n1712fld0" id="n1712fld0lab" class="finger">
            <div class="disIn mR5">
                <input id="n1712fld0" value="298" type="radio" name="n1712fld" 
                    data-nid="1712" class="nCbox1712 slTab slNodeChange ntrStp n1712fldCls" 
                    autocomplete="off" tabindex="1">
            </div> Abuse
        </label>
        <label for="n1712fld0" id="n1712fld0lab" class="finger">
            <div class="disIn mR5">
                <input id="n1712fld1" value="299" type="radio" name="n1712fld" 
                    data-nid="1712" class="nCbox1712 slTab slNodeChange ntrStp n1712fldCls" 
                    autocomplete="off" tabindex="2">
            </div> Spam
        </label>
        <label for="n1712fld0" id="n1712fld0lab" class="finger">
            <div class="disIn mR5">
                <input id="n1712fld2" value="300" type="radio" name="n1712fld" 
                    data-nid="1712" class="nCbox1712 slTab slNodeChange ntrStp n1712fldCls" 
                    autocomplete="off" tabindex="3">
            </div> Test
        </label>

    </div>
</div>

<div class="pT15 mB15">
    <input type="submit" class="btn btn-primary btn-lg slTab"
        {{ $GLOBALS['SL']->tabInd() }} value="Set Complaint Type">
</div>

<div class="mT20 mB10">{!! $GLOBALS["SL"]->printAccordTxt(
    'About these complaint types',
    view('vendor.openpolice.nodes.1712-report-inc-staff-tools-first-about-types')->render()
) !!}</div>

</form>