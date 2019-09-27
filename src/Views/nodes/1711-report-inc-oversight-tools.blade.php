<!-- resources/views/vendor/openpolice/nodes/1711-report-inc-oversight-tools.blade.php -->
<div class="alert alert-danger fade in alert-dismissible show" 
    style="padding: 10px 15px; margin: 20px 0px 20px 0px; color: #a94442;">
    <b>NOTICE TO 
    @if (isset($overRow->OverAgncName)) {{ strtoupper($overRow->OverAgncName) }} 
    @else OVERSIGHT @endif STAFF:</b>
    This view may contain sensitive personal information. 
    Please share with appropriate oversight staff only.
</div>

<div class="row">
    <div class="col-8">

        <div class="slCard">
            <h2 class="mT0" style="color: #2B3493;">
                OpenPolice.org Complaint #{{ $complaint->ComPublicID }}
            </h2>
            <?php /*
            <p><span class="slGrey">
            Current Status:
            @if (isset($overUpdateRow->LnkComOverReceived) && trim($overUpdateRow->LnkComOverReceived) != '')
                Received by Oversight
            @else {{ $GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->ComStatus) }} @endif
            </span></p>
            */ ?>
            
<?php /*
@if ($GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->ComStatus) == 'OK to Submit to Oversight')
    @if (sizeof($oversights) > 0 && isset($oversights[0]->OverEmail) && trim($oversights[0]->OverEmail) != ''
        && isset($oversights[0]->OverWaySubEmail) && intVal($oversights[0]->OverWaySubEmail) == 1)
        <p><b>Congratulations, {{ $user->name }}!</b></p>
        <p>Within the next week, we will review your complaint. 
        If there are no problems, we will try to file it with the {{ $overList }}. 
        Then, we'll let you know what comes next. So hang tight!</p>
    @else
        <p><b>Hi, {{ $user->name }},</b></p>
        <p>We're almost done — but we need you to do one more important thing as soon as possible. 
        OpenPolice.org is working to get all police departments to accept complaints sent by email. 
        Unfortunately, the {{ $overList }} does not investigate OpenPolice.org complaints sent by email.</p>
        <p>The good news is you can easily copy information from your OpenPolice.org complaint to their required forms. 
        And you can find the instructions for formally submitting your complaint to the department here:</p><p>
        @forelse ($depts as $i => $d)
            <a href="/dept/{{ $d->DeptSlug }}" target="_blank">{{ $d->DeptName }}</a><br />
        @empty
        @endforelse
        </p><p>After you submit your complaint with the {{ $overList }}, please log back in to update the community. 
        Also, please let us know whenever they receive or investigate your complaint. 
        Meanwhile, you can share your published complaint on Facebook, Twitter, or anywhere you like!</p>
    @endif
@elseif ($GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->ComStatus) == 'Submitted to Oversight')
    <p><b>Hi, {{ $user->name }},</b></p>
    <p>We just attempted to email your complaint to the {{ $overList }}. 
    But this shouldn't be the end of the road for you!</p>
    <p>Please contact the {{ $overList }} this week to confirm that your complaint has been received. 
    And as the investigation progresses, please log back in to update the community!</p>
    <p>If you can't confirm that your complaint was accepted by the {{ $overList }}, you'll need to submit it 
    another way to make sure it gets investigated. You can find the instructions for submitting your complaint 
    to the department here:</p><p>
    @forelse ($depts as $i => $d)
        <a href="/dept/{{ $d->DeptSlug }}" target="_blank">{{ $d->DeptName }}</a><br />
    @empty
    @endforelse
    </p><p>Thank you so much for using OpenPolice.org!</p>
@elseif ($GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->ComStatus) == 'Received by Oversight')
    <p><b>Hi, {{ $user->name }},</b></p>
    The {{ $overList }} received your complaint! But this shouldn't be the end of the road for you. 
    As the investigation progresses, please update the community here.
@elseif ($GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->ComStatus) == 'Declined To Investigate (Closed)')
    <p><b>Hi, {{ $user->name }},</b></p>
    
@elseif ($GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->ComStatus) == 'Investigated (Closed)')
    <p><b>Hi, {{ $user->name }},</b></p>
    
@elseif ($GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->ComStatus) == 'Closed')
    <p><b>Hi, {{ $user->name }},</b></p>
    
@endif
*/ ?>

            <h3 class="mT5 mB0">Please confirm the status of this complaint:</h3>
            <form method="post" name="accessCode" action="?overUpdate=1&refresh=1{{
                (($GLOBALS['SL']->REQ->has('frame')) ? '&frame=1' : '') }}">
            <input type="hidden" id="csrfTok" name="_token" value="{{ csrf_token() }}">
            <div class="nFld mT0">
                @if ($GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->ComStatus) 
                    == 'Submitted to Oversight')
                    <label class="finger">
                        <input type="radio" id="overStatus1" name="overStatus" autocomplete="off"
                            value="Submitted to Oversight"
                            @if ($GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->ComStatus) 
                                == 'Submitted to Oversight') CHECKED @endif > 
                        <span class="mL5" style="font-weight: normal;">Submitted to 
                        @if (isset($overRow->OverAgncName)) {{ $overRow->OverAgncName }} 
                        @else this investigative agency @endif</span>
                        @if ($GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->ComStatus) 
                            == 'Submitted to Oversight') <span class="mL5 slGrey">(Current Status)</span> @endif
                    </label>
                @endif
                <label class="finger">
                    <input type="radio" id="overStatus2" name="overStatus" autocomplete="off" 
                        value="Received by Oversight" 
                        @if ($GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->ComStatus) 
                            == 'Received by Oversight') CHECKED @endif > 
                    <span class="mL5" style="font-weight: normal;">Received by 
                    @if (isset($overRow->OverAgncName)) {{ $overRow->OverAgncName }} 
                    @else this investigative agency @endif</span>
                    @if ($GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->ComStatus) 
                        == 'Received by Oversight') <span class="mL5 slGrey">(Current Status)</span> @endif
                </label>
                @if ($GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->ComStatus) 
                    != 'Submitted to Oversight')
                    <label class="finger">
                        <input type="radio" id="overStatus3" name="overStatus" autocomplete="off" 
                            value="Investigated (Closed)"
                            @if ($GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->ComStatus) 
                                == 'Investigated (Closed)') CHECKED @endif >
                            <span class="mL5" style="font-weight: normal;">Investigated by 
                            @if (isset($overRow->OverAgncName)) {{ $overRow->OverAgncName }} 
                            @else this investigative agency @endif </span>
                            @if ($GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->ComStatus) 
                                == 'Investigated (Closed)') <span class="mL5 slGrey">(Current Status)</span> @endif
                    </label>
                    <label class="finger">
                        <input type="radio" id="overStatus4" name="overStatus" autocomplete="off" 
                            value="Declined To Investigate (Closed)" 
                            @if ($GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->ComStatus) 
                                == 'Declined To Investigate (Closed)') CHECKED @endif > 
                            <span class="mL5" style="font-weight: normal;">
                            @if (isset($overRow->OverAgncName)) {{ $overRow->OverAgncName }} 
                            @else Oversight agency @endif declined to investigate</span>
                            @if ($GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->ComStatus) 
                                == 'Declined To Investigate (Closed)') <span class="mL5 slGrey">(Current Status)</span>
                            @endif
                    </label>
                @endif
                @if ($GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->ComStatus) == 'Submitted to Oversight')
                    <label class="finger">
                        <input type="radio" id="overStatus5" name="overStatus" autocomplete="off" 
                            value="OK to Submit to Oversight" 
                            @if ($GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->ComStatus) 
                                == 'OK to Submit to Oversight') CHECKED @endif > 
                        <span class="mL5" style="font-weight: normal;">
                        @if (isset($overRow->OverAgncName)) {{ $overRow->OverAgncName }} 
                        @else This investigative agency @endif cannot investigate this complaint as is</span>
                    </label>
                @endif
            </div>
            <div class="nFld mT10">
                <div id="notesStatus" class="disBlo">
                    Notes about the status of this complaint:<br />
                    <small class="slGrey">
                    These optional notes are for OpenPolice.org administrators. We will not make them public.
                    </small>
                </div>
                <div id="notesCannot" class="disNon">
                    Please tell us why this complaint cannot be investigated as is:<br />
                    <small class="slGrey">
                    We can then inform the complainant to submit their complaints another way.
                    </small>
                </div>
                <textarea name="overNote" class="w100 mT5"></textarea>
            </div>
            <input type="submit" value="Save New Status" class="btn btn-lg btn-xl btn-primary mT20">
            </form>
        </div>

        <div class="p10"> </div>
    </div>
    <div class="col-4">
            
        <div class="slCard">
            <a href="/complaint/read-{{ $complaint->ComPublicID }}/full-pdf" target="_blank"
                class="btn btn-lg btn-secondary disBlo taL"><nobr><i class="fa fa-print mR5" aria-hidden="true"></i> 
                Print Complaint</nobr> / <nobr>Save as PDF</nobr></a>
            <div class="mT20"><a href="/complaint/read-{{ $complaint->ComPublicID }}/full-xml" target="_blank"
                class="btn btn-lg btn-secondary disBlo taL"
                ><i class="fa fa-cloud-download mR5" aria-hidden="true"></i> Download Raw Data File</a></div>
            <?php /*
            <h4 class="mT20 pT20 mB5"><i class="fa fa-link mR3" aria-hidden="true"></i> Public Link To Share:</h4>
            <input value="{{ $GLOBALS['SL']->sysOpts['app-url'] }}/complaint/read-{{ $complaint->ComPublicID 
                }}" type="text" class="form-control w100 mB10">
            */ ?>
        </div>
        
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
}); </script>