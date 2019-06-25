<!-- resources/views/vendor/openpolice/nodes/1712-report-inc-staff-tools-first-review.blade.php -->
<div class="row">
    <div class="col-sm-6">

        <label for="n1712fld0" id="n1712fld0lab" class="fingerAct">
            <div class="disIn mR5">
                <input id="n1712fld0" value="296" type="radio" name="n1712fld" checked="" 
                    data-nid="1712" class="nCbox1712 slTab slNodeChange ntrStp n1712fldCls" 
                    autocomplete="off" tabindex="1">
            </div> Police Complaint
        </label>
        <label for="n1712fld0" id="n1712fld0lab" class="fingerAct">
            <div class="disIn mR5">
                <input id="n1712fld1" value="297" type="radio" name="n1712fld" checked="" 
                    data-nid="1712" class="nCbox1712 slTab slNodeChange ntrStp n1712fldCls" 
                    autocomplete="off" tabindex="2">
            </div> Not About Police
        </label>
        <label for="n1712fld0" id="n1712fld0lab" class="fingerAct">
            <div class="disIn mR5">
                <input id="n1712fld2" value="301" type="radio" name="n1712fld" checked="" 
                    data-nid="1712" class="nCbox1712 slTab slNodeChange ntrStp n1712fldCls" 
                    autocomplete="off" tabindex="3">
            </div> Not Sure
        </label>

    </div>
    <div class="col-sm-6">

        <label for="n1712fld0" id="n1712fld0lab" class="fingerAct">
            <div class="disIn mR5">
                <input id="n1712fld0" value="298" type="radio" name="n1712fld" checked="" 
                    data-nid="1712" class="nCbox1712 slTab slNodeChange ntrStp n1712fldCls" 
                    autocomplete="off" tabindex="1">
            </div> Abuse
        </label>
        <label for="n1712fld0" id="n1712fld0lab" class="fingerAct">
            <div class="disIn mR5">
                <input id="n1712fld1" value="299" type="radio" name="n1712fld" checked="" 
                    data-nid="1712" class="nCbox1712 slTab slNodeChange ntrStp n1712fldCls" 
                    autocomplete="off" tabindex="2">
            </div> Spam
        </label>
        <label for="n1712fld0" id="n1712fld0lab" class="fingerAct">
            <div class="disIn mR5">
                <input id="n1712fld2" value="300" type="radio" name="n1712fld" checked="" 
                    data-nid="1712" class="nCbox1712 slTab slNodeChange ntrStp n1712fldCls" 
                    autocomplete="off" tabindex="3">
            </div> Test
        </label>

    </div>
</div>

<div class="pT15 pB15">
    <a class="btn btn-primary btn-lg slTab nFormNext" href="javascript:;"
        {{ $GLOBALS['SL']->tabInd() }} >Set Complaint Type</a>
</div>

<hr>
<div class="mBn20">{!! $GLOBALS["SL"]->printAccordian(
    '<i class="fa fa-info-circle" aria-hidden="true"></i> About these complaint types',
    view('vendor.openpolice.nodes.1712-report-inc-staff-tools-first-about-types')->render()
) !!}</div>