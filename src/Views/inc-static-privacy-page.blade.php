<!-- resources/views/vendor/openpolice/inc-static-privacy-page.blade.php -->

<form name="ownerPublish" id="ownerPublishID" 
    method="post" action="?ownerPublish=1">
<input type="hidden" name="_token" value="{{ csrf_token() }}">
<input type="hidden" name="ownerPublish" value="1">
<input type="hidden" name="refresh" value="1">

<div id="nodeStaticPrivacy">

    <div id="nLabel2018" class="nPrompt">
        <!--- <h2 class="slBlueDark">Publishing Privacy Options</h2> --->
        <p>After filing your complaint for investigation, your full story can 
        be published on OpenPolice.org. Please select your publishing settings.</p>
    </div>

    <div id="node2787" class="nodeWrap">
        <div class="nodeHalfGap"></div>
        <div id="nLabel2787" class="nPrompt">
            Do you want to publish your name with your complaint on OpenPolice.org? 
            <span id="req2787" class="rqd"><nobr>*required</nobr></span>
            <div id="hidivnLabel2787notes" class="subNote">
                Search engines will index your complaint, and 
                it will become part of the public record.
            </div>
        </div>
        <div class="nFld" style="margin-top: 12px;">
            <label for="n2787fld0" id="n2787fld0lab" class="finger">
                <div class="disIn mR5">
                    <input name="n2787fld" id="n2787fld0" data-nid="2787" value="1" 
                        class="nCbox2787 slTab slNodeChange ntrStp n2787fldCls" 
                        type="radio" autocomplete="off" tabindex="1">
                </div>
                Yes 
            </label>
            <label for="n2787fld1" id="n2787fld1lab" class="finger">
                <div class="disIn mR5">
                    <input name="n2787fld" id="n2787fld1" data-nid="2787" value="0" 
                        class="nCbox2787 slTab slNodeChange ntrStp n2787fldCls n2787fldCls" 
                        type="radio" autocomplete="off" tabindex="2">
                </div>
                No 
            </label>
        </div>
        <div id="node2787kids" class="nPrompt" style="display: none;">
            <div class="alert alert-primary w100 mT30">
                <p>By selecting 'No,' OpenPolice.org will only publish your 
                multiple-choice answers. That will NOT include your written 
                story nor your detailed surveyed answers.</p>
            </div>
        </div>
        <div class="nodeHalfGap"></div>
    </div>

@if ($offCnt > 0)
    <div id="node2789" class="nodeWrap">
        <div class="nodeHalfGap"></div>
        <div id="nLabel2789" class="nPrompt">
            Do you want to publish the 
            @if ($offCnt > 1) officers' @else officer's @endif 
            identity with your complaint on OpenPolice.org? 
            <span id="req2789" class="rqd"><nobr>*required</nobr></span>
        </div>
        <div class="nFld" style="margin-top: 12px;">
            <label for="n2789fld0" id="n2789fld0lab" class="finger">
                <div class="disIn mR5">
                    <input name="n2789fld" id="n2789fld0" data-nid="2789" value="1" 
                    class="nCbox2789 slTab slNodeChange ntrStp n2789fldCls"
                    type="radio" autocomplete="off" tabindex="3">
                </div>
                Yes 
            </label>
            <label for="n2789fld1" id="n2789fld1lab" class="finger">
                <div class="disIn mR5">
                    <input name="n2789fld" id="n2789fld1" data-nid="2789" value="0" 
                        class="nCbox2789 slTab slNodeChange ntrStp n2789fldCls n2789fldCls" 
                        type="radio" autocomplete="off" tabindex="4">
                </div>
                No 
            </label>
        </div>
        <div id="node2789kids" class="nPrompt" style="display: none;">
            <div class="alert alert-primary w100 mT30">
                <p>
                    By selecting 'No,' OpenPolice.org will only publish 
                    your multiple-choice answers. That will NOT include 
                    your written story nor your detailed surveyed answers.
                </p>
            </div>
        </div>
    </div>
@endif
    <div id="node269" class="nodeWrap">
        <div class="nodeHalfGap"></div>
        <div class="nFld">
            <div class="mT30">
                <span id="req269" class="rqd"><nobr>*required</nobr></span>
            </div>
            <label for="n269fld0" id="n269fld0lab" class="finger">
                <div class="disIn mR5">
                    <input id="n269fld0" name="n269fld[]" 
                        type="checkbox" value="Y" data-nid="269" 
                        class="nCbox269  slTab slNodeChange ntrStp" 
                        autocomplete="off" tabindex="2">
                </div>
                Yes, I want to submit my complaint to the 
                appropriate police <nobr>investigative agencies.</nobr>
            </label>
        </div>
        <div class="nodeHalfGap"></div>
    </div>

    <div class="nodeHalfGap"></div>
    <center>
        <a id="ownerPublishBtn" class="btn btn-lg btn-primary"
            href="javascript:;">Save Publishing Options</a>
    </center>
    <div class="nodeHalfGap"></div>
    <div class="alert alert-primary w100 mT30">
        We will publish no one’s private information. That includes addresses, phone numbers, emails, etc.
    </div>
    
</div>

</form>

<script type="text/javascript"> $(document).ready(function(){

    var triedToSubmit = false;

    function chkPubOwn() {
        if (document.getElementById("n2787fld0") && document.getElementById("n2787fld0").checked) {
            document.getElementById("n2787fld0lab").className="fingerAct";
            document.getElementById("n2787fld1lab").className="finger";
            $("#node2787kids").slideUp(50);
        } else if (document.getElementById("n2787fld1") && document.getElementById("n2787fld1").checked) {
            document.getElementById("n2787fld0lab").className="finger";
            document.getElementById("n2787fld1lab").className="fingerAct";
            $("#node2787kids").slideDown(50);
        } else {
            document.getElementById("n2787fld0lab").className="finger";
            document.getElementById("n2787fld1lab").className="finger";
            $("#node2787kids").slideUp(50);
        }
    }
    $(document).on("click", ".nCbox2787", function() {
        setTimeout(function() { chkPubOwn(); }, 100);
    });

    function chkPubOff() {
        if (document.getElementById("n2789fld0") && document.getElementById("n2789fld0").checked) {
            document.getElementById("n2789fld0lab").className="fingerAct";
            document.getElementById("n2789fld1lab").className="finger";
            $("#node2789kids").slideUp(50);
        } else if (document.getElementById("n2789fld1") && document.getElementById("n2789fld1").checked) {
            document.getElementById("n2789fld0lab").className="finger";
            document.getElementById("n2789fld1lab").className="fingerAct";
            $("#node2789kids").slideDown(50);
        } else {
            document.getElementById("n2789fld0lab").className="finger";
            document.getElementById("n2789fld1lab").className="finger";
            $("#node2789kids").slideUp(50);
        }
    }
    $(document).on("click", ".nCbox2789", function() {
        setTimeout(function() { chkPubOff(); }, 100);
    });

    function chkPublishForm() {
        var own = false;
        if ((document.getElementById("n2787fld0") && document.getElementById("n2787fld0").checked) || (document.getElementById("n2787fld1") && document.getElementById("n2787fld1").checked)) {
            own = true;
            if (triedToSubmit) document.getElementById("node2787").className="nodeWrap";
//console.log('own checked');
        } else {
            if (triedToSubmit) document.getElementById("node2787").className="nodeWrapError";
//console.log('own not checked');
        }
@if ($offCnt > 0)
        if ((document.getElementById("n2789fld0") && document.getElementById("n2789fld0").checked) || (document.getElementById("n2789fld1") && document.getElementById("n2789fld1").checked)) {
            if (triedToSubmit) document.getElementById("node2789").className="nodeWrap";
//console.log('off checked');
        } else {
            own = false;
            if (triedToSubmit) document.getElementById("node2789").className="nodeWrapError";
//console.log('off not checked');
        }
@endif 
        if (document.getElementById("n269fld0") && document.getElementById("n269fld0").checked) {
            if (triedToSubmit) document.getElementById("node269").className="nodeWrap";
//console.log('cfrm checked');
        } else {
            own = false;
            if (triedToSubmit) document.getElementById("node269").className="nodeWrapError";
//console.log('cfrm not checked');
        }
//console.log('chkPublishForm own: '+own);
        return own;
    }

    $("#ownerPublishBtn").click(function() {
        postToolboxOwnerPublish();
    });

    $("#ownerPublishID").submit(function( event ) {
        postToolboxOwnerPublish();
        event.preventDefault();
    });

    $(document).on("click", ".slNodeChange", function() { chkPublishForm(); });

    function postToolboxOwnerPublish() {
//console.log("postToolboxOwnerPublish");
        triedToSubmit = true;
        if (chkPublishForm()) {
            /* document.ownerPublish.submit(); */
            if (document.getElementById('ownerPrivacyWrap')) {
                var formData = new FormData($('#ownerPublishID').get(0));
                var loadSrc = "/toolbox-complainant-privacy-form/readi-{{ $complaint->com_id }}?ajax=1";
                document.getElementById('ownerPrivacyWrap').innerHTML = getSpinner();
                console.log(loadSrc);
                window.scrollTo(0, 0);
                $.ajax({
                    url: loadSrc,
                    type: "POST", 
                    data: formData, 
                    contentType: false,
                    processData: false,
                    //headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    success: function(data) {
                        $("#ownerPrivacyWrap").empty();
                        $("#ownerPrivacyWrap").append(data);
                    },
                    error: function(xhr, status, error) {
                        $("#ownerPrivacyWrap").append("<div>(error - "+xhr.responseText+")</div>");
                    }
                });
            }
        }

        return false;
    }

}); </script>
<style>
h4.disIn { padding-top: 5px; }
.privOptPadL { padding-top: 10px; }
</style>
