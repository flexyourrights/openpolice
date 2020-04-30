<!-- resources/views/vendor/openpolice/nodes/1712-report-inc-staff-tools-warnings.blade.php -->

<div class="row mTn5" style="color: #333;">
    <div class="col-md-4">
        <div class="relDiv ovrSho" style="height: 30px;">
            <div class="absDiv" style="top: 8px; left: 15px;">
                <div class="vertPrgDone" style="background:
                @if (isset($complaintRec->com_anyone_charged) 
                    && in_array(trim($complaintRec->com_anyone_charged), ['Y', '?']))
                    @if (isset($complaintRec->com_all_charges_resolved)
                        && trim($complaintRec->com_all_charges_resolved) != 'Y')
                        #EC2327;
                    @else
                        #29B76F;
                    @endif
                @else
                    #29B76F;
                @endif ">
                <img src="/survloop/uploads/spacer.gif" alt="" border="0">
                </div>
            </div>
            <div class="absDiv" style="top: 7px; left: 40px;">
                @if (isset($complaintRec->com_anyone_charged) 
                    && in_array(trim($complaintRec->com_anyone_charged), ['Y', '?']))
                    @if (isset($complaintRec->com_all_charges_resolved)
                        && trim($complaintRec->com_all_charges_resolved) != 'Y')
                        Pending Charges
                    @else
                        Charges Resolved
                    @endif
                @else
                    No Pending Charges
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="relDiv ovrSho" style="height: 30px;">
            <div class="absDiv" style="top: 8px; left: 15px;">
                <div class="vertPrgDone" style="background:
                @if ((isset($complaintRec->com_attorney_has) 
                        && trim($complaintRec->com_attorney_has) == 'Y')
                    || (isset($complaintRec->com_status) 
                        && intVal($complaintRec->com_status) 
                        == $GLOBALS["SL"]->def->getID('Complaint Status', 'Attorney\'d')))
                    #29B76F;
                @elseif (isset($complaintRec->com_anyone_charged) 
                    && in_array(trim($complaintRec->com_anyone_charged), ['Y', '?'])
                    && isset($complaintRec->com_all_charges_resolved)
                    && trim($complaintRec->com_all_charges_resolved) != 'Y')
                    #EC2327;
                @elseif (isset($complaintRec->com_attorney_want) 
                    && trim($complaintRec->com_attorney_want) == 'Y')
                    #F0AD4E;
                @else
                    #29B76F;
                @endif ">
                <img src="/survloop/uploads/spacer.gif" alt="" border="0">
                </div>
            </div>
            <div class="absDiv" style="top: 7px; left: 40px;">
                @if ((isset($complaintRec->com_attorney_has) 
                        && trim($complaintRec->com_attorney_has) == 'Y')
                    || (isset($complaintRec->com_status) 
                        && intVal($complaintRec->com_status) == $GLOBALS["SL"]->def->getID(
                            'Complaint Status', 'Attorney\'d')))
                    Has Attorney
                @elseif (isset($complaintRec->com_anyone_charged) 
                    && in_array(trim($complaintRec->com_anyone_charged), ['Y', '?'])
                    && isset($complaintRec->com_all_charges_resolved)
                    && trim($complaintRec->com_all_charges_resolved) != 'Y')
                    Needs Attorney
                @elseif (isset($complaintRec->com_attorney_want) 
                    && trim($complaintRec->com_attorney_want) == 'Y')
                    Wants Attorney
                @else
                    No Attorney Needs
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="relDiv ovrSho" style="height: 30px;">
            <div class="absDiv" style="top: 8px; left: 15px;">
                <div class="vertPrgDone" style="background:
                @if ($complaintRec->com_privacy == $GLOBALS['SL']->def->getID(
                    'Privacy Types', 'Submit Publicly'))
                    #29B76F;
                @elseif ($complaintRec->com_privacy == $GLOBALS['SL']->def->getID(
                    'Privacy Types', 'Names Visible to Police but not Public'))
                    #63C6FF;
                @elseif (in_array($complaintRec->com_privacy, [
                    $GLOBALS['SL']->def->getID('Privacy Types', 'Completely Anonymous'),
                    $GLOBALS['SL']->def->getID('Privacy Types', 'Anonymized')
                    ]))
                    #EC2327;
                @else
                    #888;
                @endif ">
                <img src="/survloop/uploads/spacer.gif" alt="" border="0">
                </div>
            </div>
            <div class="absDiv" style="top: 7px; left: 40px;">
                @if ($complaintRec->com_privacy == $GLOBALS["SL"]->def->getID(
                    'Privacy Types', 'Submit Publicly'))
                    Submit Publicly
                @elseif ($complaintRec->com_privacy == $GLOBALS['SL']->def->getID(
                    'Privacy Types', 'Names Visible to Police but not Public'))
                    No Names Public
                @elseif (in_array($complaintRec->com_privacy, [
                    $GLOBALS['SL']->def->getID('Privacy Types', 'Completely Anonymous'),
                    $GLOBALS['SL']->def->getID('Privacy Types', 'Anonymized')
                    ]))
                    Anonymous
                @else
                    <span style="color: #888;">No Privacy Setting</span>
                @endif
            </div>
        </div>
    </div>
</div>

<?php /*

<div class="pT10 bld">
    @if (isset($complaintRec->com_anyone_charged) 
        && in_array(trim($complaintRec->com_anyone_charged), ['Y', '?']))
        @if (isset($complaintRec->com_all_charges_resolved)
            && trim($complaintRec->com_all_charges_resolved) != 'Y')
            <div class="disIn mR10 red">Pending Charges,</div>
        @else
            <div class="disIn mR10 grn">Charges Resolved,</div>
        @endif
    @else
        <div class="disIn mR10 grn">No Pending Charges,</div>
    @endif

    @if ((isset($complaintRec->com_attorney_has) 
            && trim($complaintRec->com_attorney_has) == 'Y')
        || (isset($complaintRec->com_status) 
            && intVal($complaintRec->com_status) 
            == $GLOBALS["SL"]->def->getID(
            'Complaint Status', 'Attorney\'d')))
        <div class="disIn mR10 grn">Has Attorney,</div>
    @elseif (isset($complaintRec->com_anyone_charged) 
        && in_array(trim($complaintRec->com_anyone_charged), ['Y', '?'])
        && isset($complaintRec->com_all_charges_resolved)
        && trim($complaintRec->com_all_charges_resolved) != 'Y')
        <div class="disIn mR10 red">Needs Attorney,</div>
    @elseif (isset($complaintRec->com_attorney_want) 
        && trim($complaintRec->com_attorney_want) == 'Y')
        <div class="disIn mR10 clrWarn">Wants Attorney,</div>
    @else
        <div class="disIn mR10 grn">No Attorney Needs,</div>
    @endif

    @if ($complaintRec->com_privacy == $GLOBALS["SL"]->def->getID(
        'Privacy Types', 'Submit Publicly'))
        <div class="disIn mR10 grn">Submit Publicly</div>
    @elseif ($complaintRec->com_privacy == $GLOBALS['SL']->def->getID(
        'Privacy Types', 'Names Visible to Police but not Public'))
        <div class="disIn mR10 clrInfo">No Names Public</div>
    @elseif (in_array($complaintRec->com_privacy, [
        $GLOBALS['SL']->def->getID('Privacy Types', 'Completely Anonymous'),
        $GLOBALS['SL']->def->getID('Privacy Types', 'Anonymized')
        ]))
        <div class="disIn mR10 red">Anonymous</div>
    @else
        <div class="disIn mR10 slGrey">No Privacy Setting</div>
    @endif


</div>

*/ ?>