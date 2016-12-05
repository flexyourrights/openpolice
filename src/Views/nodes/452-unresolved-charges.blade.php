<!-- resources/views/vendor/openpolice/nodes/452-unresolved-charges.blade.php -->

<input type="hidden" name="n452Visible" id="n452VisibleID" value="1">
<input type="hidden" name="n442Visible" id="n442VisibleID" value="1">
<div class="fC"></div>

<div id="node452" class="nodeWrap">

    <div id="node442" class="nodeWrap">
        <div id="nLabel442" class="nPromptInstr">
            <label for="n442FldID">
                <h2 class="slBlueDark mBn20">Some very important questions before we begin...</h2>
            </label>
        </div>
    </div> <!-- end #node442 -->
    
    <div class="nodeGap"></div>
    <input type="hidden" name="n443Visible" id="n443VisibleID" value="1">
    <div class="fC"></div>
    
    <div id="node443" class="nodeWrap">
    
        <div id="nLabel443" class="nPrompt">
            Do you have a lawyer in regard to this event?<small class="red pL10 mTn10">*required</small>
        </div>
        <div class="nFld">
            <div class=" disIn mR20">
                <label for="n443fld0" class="mR10">
                    <div class="disIn mR5"><input id="n443fld0" value="Y" type="radio" name="n443fld"  class="n443fldCls" autocomplete="off" 
                        @if ($ComAttorneyHas == 'Y') CHECKED @endif onClick="checkNodeUp();" ></div> Yes
                </label>
            </div>
            <div class=" disIn mR20">
                <label for="n443fld1" class="mR10">
                    <div class="disIn mR5"><input id="n443fld1" value="N" type="radio" name="n443fld"  class="n443fldCls" autocomplete="off" 
                        @if ($ComAttorneyHas == 'N') CHECKED @endif onClick="checkNodeUp();" ></div> No
                </label>
            </div>
            <div class=" disIn mR20">
                <label for="n443fld2" class="mR10">
                    <div class="disIn mR5"><input id="n443fld2" value="?" type="radio" name="n443fld"  class="n443fldCls" autocomplete="off" 
                        @if ($ComAttorneyHas == '?') CHECKED @endif onClick="checkNodeUp();" ></div> Not sure
                </label>
            </div>
        </div>
    
        <div id="node443kidsN" class="nKids @if (in_array($ComAttorneyHas, ['N', '?'])) disBlo @else disNon @endif ">
        
            <input type="hidden" name="n444Visible" id="n444VisibleID" 
                @if (in_array($ComAttorneyHas, ['N', '?'])) value="1" @else value="0" @endif 
                >
            <div class="fC"></div>
            
            <div id="node444" class="nodeWrap">
                <div id="nLabel444" class="nPrompt">
                    Do you want help finding a lawyer?
                </div>
                <div class="nFld">
                    <div class=" disIn mR20">
                        <label for="n444fld0" class="mR10">
                            <div class="disIn mR5"><input id="n444fld0" value="Y" type="radio" name="n444fld" autocomplete="off" 
                                @if ($ComAttorneyWant == 'Y') CHECKED @endif onClick="checkNodeUp();" ></div> Yes
                        </label>
                    </div>
                    <div class=" disIn mR20">
                        <label for="n444fld1" class="mR10">
                            <div class="disIn mR5"><input id="n444fld1" value="N" type="radio" name="n444fld" autocomplete="off" 
                                @if ($ComAttorneyWant == 'N') CHECKED @endif onClick="checkNodeUp();" ></div> No
                        </label>
                    </div>
                    <div class=" disIn mR20">
                        <label for="n444fld2" class="mR10">
                            <div class="disIn mR5"><input id="n444fld2" value="?" type="radio" name="n444fld" autocomplete="off" 
                                @if ($ComAttorneyWant == '?') CHECKED @endif onClick="checkNodeUp();" ></div> Not sure
                        </label>
                    </div>
                </div>
            </div> <!-- end #node444 -->
            
            <div class="nodeGap"></div>
        </div>
    </div> <!-- end #node443 -->
    
    <div class="nodeGap"></div>
    <input type="hidden" name="n268Visible" id="n268VisibleID" value="1">
    <div class="fC"></div>
    
    <div id="node268" class="nodeWrap">
        <div id="nLabel268" class="nPrompt">
            Is anyone involved in this event now under arrest, OR has anyone been charged with a crime?<small class="red pL10 mTn10">*required</small>
        </div>
        <div class="nFld">
            <div class=" disIn mR20">
                <label for="n268fld0" class="mR10">
                    <div class="disIn mR5"><input id="n268fld0" value="Y" type="radio" name="n268fld"  class="n268fldCls" autocomplete="off"
                        @if ($ComAnyoneCharged == 'Y') CHECKED @endif onClick="checkNodeUp(); unresolveUpdate();" ></div> Yes
                </label>
            </div>
            <div class=" disIn mR20">
                <label for="n268fld1" class="mR10">
                    <div class="disIn mR5"><input id="n268fld1" value="N" type="radio" name="n268fld"  class="n268fldCls" autocomplete="off"
                        @if ($ComAnyoneCharged == 'N') CHECKED @endif onClick="checkNodeUp(); unresolveUpdate();" ></div> No
                </label>
            </div>
            <div class=" disIn mR20">
                <label for="n268fld2" class="mR10">
                    <div class="disIn mR5"><input id="n268fld2" value="?" type="radio" name="n268fld"  class="n268fldCls" autocomplete="off"
                        @if ($ComAnyoneCharged == '?') CHECKED @endif onClick="checkNodeUp(); unresolveUpdate();" ></div> Not sure
                </label>
            </div>
        </div>
        
        <div id="node268kids" class="nKids @if ($ComAnyoneCharged == 'Y') disBlo @else disNon @endif ">
            <input type="hidden" name="n563Visible" id="n563VisibleID" 
                @if ($ComAnyoneCharged == 'Y') value="1" @else value="0" @endif 
                >
            
            <div id="node563" class="nodeWrap">
                <div id="nLabel563" class="nPrompt">
                    Have ALL of these charges been resolved? <small class="red pL10 mTn10">*required</small>
                    <div class="fPerc80">(Resolved means that the charges have been dropped or the people charged have been found 'guilty' or 'not guilty' in court.)</div>
                </div>
                <div class="nFld">
                    <div class=" disIn mR20">
                        <label for="n563fld0" class="mR10">
                            <div class="disIn mR5"><input id="n563fld0" value="Y" type="radio" name="n563fld"  class="n563fldCls" autocomplete="off"
                                @if ($ComAllChargesResolved == 'Y') CHECKED @endif onClick="checkNodeUp(); unresolveUpdate();" ></div> Yes
                        </label>
                    </div>
                    <div class=" disIn mR20">
                        <label for="n563fld1" class="mR10">
                            <div class="disIn mR5"><input id="n563fld1" value="N" type="radio" name="n563fld"  class="n563fldCls" autocomplete="off"
                                @if ($ComAllChargesResolved == 'N') CHECKED @endif onClick="checkNodeUp(); unresolveUpdate();" ></div> No
                        </label>
                    </div>
                    <div class=" disIn mR20">
                        <label for="n563fld2" class="mR10">
                            <div class="disIn mR5"><input id="n563fld2" value="?" type="radio" name="n563fld"  class="n563fldCls" autocomplete="off"
                                @if ($ComAllChargesResolved == '?') CHECKED @endif onClick="checkNodeUp(); unresolveUpdate();" ></div> Not sure
                        </label>
                    </div>
                </div>
                
            </div> <!-- end #node563 -->
    
        </div>
    </div> <!-- end #node268 -->

    <div class="nodeGap"></div>
</div> <!-- end #node452 -->
        
<div id="node563kids" class="nKids @if (in_array($ComAllChargesResolved, ['N', '?'])) disBlo @else disNon @endif " style="padding: 0px;">
    <input type="hidden" name="n439Visible" id="n439VisibleID" 
        @if (in_array($ComAllChargesResolved, ['N', '?'])) value="1" @else value="0" @endif 
        >
    <div id="node439" class="nodeWrap jumbotron" style="padding: 40px 20px 0px 20px;">
        <div class="alert alert-danger" role="alert">
            <div class="row">
                <div class="col-md-1 taC">
                    <h1 class="m0"><i class="fa fa-hand-paper-o"></i></h1>
                </div>
                <div class="col-md-11 pT10">
                    <h3 id="StopY" class="m0 @if ($ComAllChargesResolved == 'N') disBlo @else disNon @endif "
                        >WARNING: We urge you to stop now. Please consult with a criminal lawyer 
                        first before submitting this complaint.</h3> 
                    <h3 id="StopQ" class="m0 @if ($ComAllChargesResolved == '?') disBlo @else disNon @endif "
                        >WARNING: Before submitting a complaint, you should try to check with everyone involved 
                        to ensure they do not have any unresolved criminal charges.</h3> 
                </div>
            </div>
        </div>
        <div class="p20">
            <h3 class="mT0">Why You Should Talk To A Lawyer First...</h3>
            <p class="f26">
            You might not know it, but information you provide in this complaint could hurt the criminal case of people involved. 
            That's not what you want to happen, so <b>you can do one of these three things</b> right now...
            </p>
            <div class="row pT20 pB20">
                <div class="col-md-1 taR f24 bld">1)</div>
                <div class="col-md-3 f24 slBlueDark">Save your story for later</div>
                <div class="col-md-5">
                    <p>To do this, <a href="javascript:void(0)" class="showStoryCopy"><u>copy the narrative</u></a> you just wrote, 
                    and paste it into an email that you send to yourself. After you've checked with the lawyers involved or 
                    all the charges are resolved, then you can come back later.</p>
                </div>
                <div class="col-md-3 pT10 taC">
                    <div id="copyClipWrap" class="disNon">
                        <textarea id="ComSummaryID" class="w100 pB20 f12" style="height: 80px;" 
                            >{{ $ComSummary }}</textarea>
                        <input type="button" value="Copy To Clipboard" class="btn btn-sm btn-default" id="copyToClip">
                        <div id="confirmCopied" class="disNon slBlueDark"><i>copied</i></div>
                    </div>
                </div>
            </div>
            <div class="row pT20 pB20">
                <div class="col-md-1 taR f24 bld">2)</div>
                <div class="col-md-3 f24 slBlueDark">Create a complete complaint – but for your eyes only</div>
                <div class="col-md-5">
                    <p>You can go all the way and create a professional-quality complaint. 
                    You can then print it out or save it as a file for your lawyer. 
                    But after you logout, most of your complaint will be deleted from our system. 
                    We will still preserve some anonymous data for police accountability researchers.</p>
                </div>
                <div class="col-md-3 pT20 taC">
                    <input type="button" value="Complete Complaint" class="btn btn-lg btn-primary" id="nFormComplete">
                </div>
            </div>
            <div class="row pT20 pB20">
                <div class="col-md-1 taR f24 bld">3)</div>
                <div class="col-md-3 f24 slBlueDark">Provide helpful anonymous complaint data</div>
                <div class="col-md-5">
                    <p>Your anonymous data will greatly help with efforts to improve police accountability, 
                    both in your neighborhood and nationwide. <i class="fa fa-heart-o"></i></p>
                </div>
                <div class="col-md-3 pT20 taC">
                    <input type="button" value="Anonymous Data Only" class="btn btn-lg btn-primary" id="nFormAnonymous">
                </div>
            </div>
        </div>
        <input id="n439hidden" name="n439fld" value="{{ $ComUnresolvedChargesActions }}" type="hidden" >
    </div> <!-- end #node439 -->
    
</div> <!-- end #node563kids -->


<script type="text/javascript">
addFld("n443fld0"); addFld("n443fld1"); addFld("n443fld2");
addFld("n444fld0"); addFld("n444fld1"); addFld("n444fld2");
addFld("n268fld0"); addFld("n268fld1"); addFld("n268fld2");
addFld("n563fld0"); addFld("n563fld1"); addFld("n563fld2");
addFld("n439fld0"); addFld("n439fld1"); addFld("n439fld2");

function unresolveUpdate() {
    var canDecide = true; // do we have enough responses to decide on the warning?..
    //alert(document.getElementById('n443fld0').checked);
    if (!document.getElementById('n443fld0').checked && !document.getElementById('n443fld1').checked && !document.getElementById('n443fld2').checked) {
        canDecide = false;
    }
    if (!document.getElementById('n268fld0').checked && !document.getElementById('n268fld1').checked && !document.getElementById('n268fld2').checked) {
        canDecide = false;
    }
    else if ( (document.getElementById('n268fld0').checked || document.getElementById('n268fld2').checked) 
        && !document.getElementById('n563fld0').checked && !document.getElementById('n563fld1').checked && !document.getElementById('n563fld2').checked) {
        canDecide = false;
    }
        
    if (canDecide) {
        var chargesUnresolved = false;
        if ( (document.getElementById('n268fld0').checked || document.getElementById('n268fld2').checked)
            && (document.getElementById('n563fld1').checked || document.getElementById('n563fld2').checked) ) {
            chargesUnresolved = true;
        }
        if (chargesUnresolved) {
            dispWarnings('show');
            if (document.getElementById('n563fld1').checked) {
                document.getElementById('StopY').style.display='block';
                document.getElementById('StopQ').style.display='none';
            }
            else {
                document.getElementById('StopY').style.display='none';
                document.getElementById('StopQ').style.display='block';
            }
        }
        else dispWarnings('hide');
    }
    else dispWarnings('hide');
    return true;
}

function dispWarnings(showHide) {
    if (showHide == 'show') {
        document.getElementById('nFormNext').style.display='none';
        document.getElementById('node563kids').style.display='block';
        setNodeVisib(439, true);
    }
    else {
        document.getElementById('nFormNext').style.display='block';
        document.getElementById('node563kids').style.display='none';
        setNodeVisib(439, false);
    }
    return true;
}
</script>