<!-- resources/views/vendor/openpolice/nodes/1711-report-inc-oversight-tools.blade.php -->

<div class="alert alert-danger fade in alert-dismissible show" 
    style="padding: 10px 15px; margin: 20px 0px 20px 0px; color: #a94442;">
    <b>NOTICE TO 
    @if (isset($overRow->over_agnc_name)) 
        {{ strtoupper($overRow->over_agnc_name) }} 
    @else 
        INVESTIGATIVE 
    @endif STAFF:</b><br />
    This view may contain sensitive personal information. 
    Please share with <nobr>appropriate investigative staff only.</nobr>
</div>

<div class="row mB15">
    <div class="col-lg-8">

        <div class="slCard">
            <h2 class="mT0" style="color: #2B3493;">
                OpenPolice.org Complaint 
                #{{ $complaint->com_public_id }}
            </h2>
            
            <form name="overStatusForm" id="overStatusFormID"
                action="?overUpdate=1" method="post">
            <input type="hidden" id="csrfTok" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="overUpdate" value="1">
            <input type="hidden" name="refresh" value="1">

            <h3>Please confirm the status of this complaint:</h3>

            <div class="nFld mT15 mB30">

                @if ($GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->com_status) 
                    == 'Submitted to Oversight')
                    <label class="finger">
                        <input type="radio" id="overStatus1" name="overStatus" 
                            value="Submitted to Oversight" autocomplete="off"
                            @if ($GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->com_status) 
                                == 'Submitted to Oversight') CHECKED @endif > 
                        <span class="mL5" style="font-weight: normal;">Submitted to 
                        @if (isset($overRow->over_agnc_name)) {{ $overRow->over_agnc_name }} 
                        @else this investigative agency @endif</span>
                        @if ($GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->com_status) 
                            == 'Submitted to Oversight') 
                            <span class="mL5 slGrey">(Current Status)</span> 
                        @endif
                    </label>
                @endif

                <label class="finger">
                    <input type="radio" id="overStatus2" name="overStatus" 
                        value="Received by Oversight" autocomplete="off" 
                        @if ($GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->com_status) 
                            == 'Received by Oversight') CHECKED @endif > 
                    <span class="mL5" style="font-weight: normal;">Received by 
                    @if (isset($overRow->over_agnc_name)) {{ $overRow->over_agnc_name }} 
                    @else this investigative agency @endif</span>
                    @if ($GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->com_status) 
                        == 'Received by Oversight') 
                        <span class="mL5 slGrey">(Current Status)</span> 
                    @endif
                </label>

                @if ($GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->com_status) 
                    != 'Submitted to Oversight')
                    <label class="finger">
                        <input type="radio" id="overStatus3" name="overStatus" 
                            value="Investigated (Closed)" autocomplete="off" 
                            @if ($GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->com_status) 
                                == 'Investigated (Closed)') CHECKED @endif >
                            <span class="mL5" style="font-weight: normal;">Investigated by 
                            @if (isset($overRow->over_agnc_name)) {{ $overRow->over_agnc_name }} 
                            @else this investigative agency @endif </span>
                            @if ($GLOBALS["SL"]->def->getVal('Complaint Status', 
                                $complaint->com_status) == 'Investigated (Closed)') 
                                <span class="mL5 slGrey">(Current Status)</span> 
                            @endif
                    </label>

                    <label class="finger">
                        <input type="radio" id="overStatus4" name="overStatus"  
                            value="Declined To Investigate (Closed)" autocomplete="off"
                            @if ($GLOBALS["SL"]->def->getVal('Complaint Status', 
                                $complaint->com_status) == 'Declined To Investigate (Closed)') 
                                CHECKED
                            @endif > 
                            <span class="mL5" style="font-weight: normal;">
                            @if (isset($overRow->over_agnc_name)) {{ $overRow->over_agnc_name }} 
                            @else Oversight agency 
                            @endif declined to investigate
                            </span>
                            @if ($GLOBALS["SL"]->def->getVal('Complaint Status', 
                                $complaint->com_status) == 'Declined To Investigate (Closed)') 
                                <span class="mL5 slGrey">(Current Status)</span>
                            @endif
                    </label>
                @endif

                @if ($GLOBALS["SL"]->def->getVal('Complaint Status', 
                    $complaint->com_status) == 'Submitted to Oversight')
                    <label class="finger">
                        <input type="radio" id="overStatus5" name="overStatus" 
                            value="OK to Submit to Oversight" autocomplete="off" 
                            @if ($GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->com_status) 
                                == 'OK to Submit to Oversight') CHECKED @endif > 
                        <span class="mL5" style="font-weight: normal;">
                        @if (isset($overRow->over_agnc_name)) {{ $overRow->over_agnc_name }} 
                        @else This investigative agency 
                        @endif cannot investigate this complaint as is
                        </span>
                    </label>
                @endif

            </div>

            <div class="nFld mT30">
                <div id="notesStatus" class="disBlo">
                    Notes about the status of this complaint:<br />
                    <small class="slGrey">
                    These optional notes are for OpenPolice.org 
                    administrators. We will not make them public.
                    </small>
                </div>
                <div id="notesCannot" class="disNon">
                    Please tell us why this complaint 
                    cannot be investigated as is:<br />
                    <small class="slGrey">
                    We can then inform the complainant to 
                    submit their complaints another way.
                    </small>
                </div>
                <textarea name="overNote" class="w100 mT5"></textarea>
            </div>
            <input id="overStatusSubmit" type="submit" value="Save New Status" 
                class="btn btn-lg btn-primary mT20">
        </div>
        <div class="p10"> </div>

        </form>

    </div>
    <div class="col-lg-4">
            
        <a href="/complaint/read-{{ $complaint->com_public_id }}/full-pdf"
            class="btn btn-lg btn-secondary btn-block disBlo taL" target="_blank"
            ><nobr><i class="fa fa-print mR5" aria-hidden="true"></i> 
            Print Complaint</nobr> / <nobr>Save as PDF</nobr></a>
        <div class="mT20">
            <a href="/complaint/read-{{ $complaint->com_public_id }}/full-xml" 
                target="_blank" class="btn btn-lg btn-secondary btn-block disBlo taL"
                ><i class="fa fa-cloud-download mR5" aria-hidden="true"></i> 
                Download Raw Data File</a>
        </div>
        <?php /*
        <h4 class="mT20 pT20 mB5"><i class="fa fa-link mR3" aria-hidden="true"></i> Public Link To Share:</h4>
        <input value="{{ $GLOBALS['SL']->sysOpts['app-url'] }}/complaint/read-{{ $complaint->com_public_id 
            }}" type="text" class="form-control w100 mB10">
        */ ?>
        
        <div class="p10"> </div>
    </div>
</div>

<script type="text/javascript"> $(document).ready(function(){

function chkNotes() {
    if (document.getElementById('overStatus5') && document.getElementById('overStatus5').checked) {
        $("#notesStatus").slideUp("fast");
        setTimeout(function() { $("#notesCannot").slideDown("fast"); }, 301);
    } else {
        $("#notesCannot").slideUp("fast");
        setTimeout(function() { $("#notesStatus").slideDown("fast"); }, 301);
    }
    return true;
}
$(document).on("click", "#overStatus1", function() { chkNotes(); });
$(document).on("click", "#overStatus2", function() { chkNotes(); });
$(document).on("click", "#overStatus3", function() { chkNotes(); });
$(document).on("click", "#overStatus4", function() { chkNotes(); });
$(document).on("click", "#overStatus5", function() { chkNotes(); });

setTimeout(function() {
    if (document.getElementById("treeWrap2766")) {
        document.getElementById("treeWrap2766").className="";
    }
}, 10);

}); </script>

<style>
#mfaOP, #tokAlrt, #comTokWarn { display: none; }
</style>