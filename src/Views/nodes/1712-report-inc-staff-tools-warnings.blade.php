<!-- resources/views/vendor/openpolice/nodes/1712-report-inc-staff-tools-warnings.blade.php -->

<table class="repDeetsBlock repDeetVert">
    <tbody>
    <tr><td>
        <div class="relDiv" style="color: #333;">
            <div class="absDiv">
                <div class="vertPrgDone" style="background:
                @if (isset($complaintRec->ComAnyoneCharged) 
                    && in_array(trim($complaintRec->ComAnyoneCharged), ['Y', '?']))
                    @if (isset($complaintRec->ComAllChargesResolved)
                        && trim($complaintRec->ComAllChargesResolved) != 'Y')
                        #EC2327;
                    @else
                        #29B76F;
                    @endif
                @else
                    #29B76F;
                @endif
                ">
                <img src="/survloop/uploads/spacer.gif" alt="" border="0">
                </div>
            </div>
            @if (isset($complaintRec->ComAnyoneCharged) 
                && in_array(trim($complaintRec->ComAnyoneCharged), ['Y', '?']))
                @if (isset($complaintRec->ComAllChargesResolved)
                    && trim($complaintRec->ComAllChargesResolved) != 'Y')
                    Pending Charges
                @else
                    Charges Resolved
                @endif
            @else
                No Pending Charges
            @endif
        </div>
    </td></tr>

    <tr><td>
        <div class="relDiv" style="color: #333;">
            <div class="absDiv">
                <div class="vertPrgDone" style="background:
                @if ((isset($complaintRec->ComAttorneyHas) 
                        && trim($complaintRec->ComAttorneyHas) == 'Y')
                    || (isset($complaintRec->ComStatus) 
                        && intVal($complaintRec->ComStatus) 
                        == $GLOBALS["SL"]->def->getID(
                        'Complaint Status', 'Attorney\'d')))
                    #29B76F;
                @elseif (isset($complaintRec->ComAnyoneCharged) 
                    && in_array(trim($complaintRec->ComAnyoneCharged), ['Y', '?'])
                    && isset($complaintRec->ComAllChargesResolved)
                    && trim($complaintRec->ComAllChargesResolved) != 'Y')
                    #EC2327;
                @elseif (isset($complaintRec->ComAttorneyWant) 
                    && trim($complaintRec->ComAttorneyWant) == 'Y')
                    #F0AD4E;
                @else
                    #29B76F;
                @endif
                ">
                <img src="/survloop/uploads/spacer.gif" alt="" border="0">
                </div>
            </div>

            @if ((isset($complaintRec->ComAttorneyHas) 
                    && trim($complaintRec->ComAttorneyHas) == 'Y')
                || (isset($complaintRec->ComStatus) 
                    && intVal($complaintRec->ComStatus) 
                    == $GLOBALS["SL"]->def->getID(
                    'Complaint Status', 'Attorney\'d')))
                Has Attorney
            @elseif (isset($complaintRec->ComAnyoneCharged) 
                && in_array(trim($complaintRec->ComAnyoneCharged), ['Y', '?'])
                && isset($complaintRec->ComAllChargesResolved)
                && trim($complaintRec->ComAllChargesResolved) != 'Y')
                Needs Attorney
            @elseif (isset($complaintRec->ComAttorneyWant) 
                && trim($complaintRec->ComAttorneyWant) == 'Y')
                Wants Attorney
            @else
                No Attorney Needs
            @endif

        </div>
    </td></tr>

    <tr><td>
        <div class="relDiv" style="color: #333;">
            <div class="absDiv">
                <div class="vertPrgDone" style="background:
                @if ($complaintRec->ComPrivacy == $GLOBALS["SL"]->def->getID(
                    'Privacy Types', 'Submit Publicly'))
                    #29B76F;
                @elseif ($complaintRec->ComPrivacy == $GLOBALS['SL']->def->getID(
                    'Privacy Types', 'Names Visible to Police but not Public'))
                    #63C6FF;
                @elseif (in_array($complaintRec->ComPrivacy, [
                    $GLOBALS['SL']->def->getID('Privacy Types', 'Completely Anonymous'),
                    $GLOBALS['SL']->def->getID('Privacy Types', 'Anonymized')
                    ]))
                    #EC2327;
                @else
                    #888;
                @endif
                ">
                <img src="/survloop/uploads/spacer.gif" alt="" border="0">
                </div>
            </div>
            @if ($complaintRec->ComPrivacy == $GLOBALS["SL"]->def->getID(
                'Privacy Types', 'Submit Publicly'))
                Submit Publicly
            @elseif ($complaintRec->ComPrivacy == $GLOBALS['SL']->def->getID(
                'Privacy Types', 'Names Visible to Police but not Public'))
                No Names Public
            @elseif (in_array($complaintRec->ComPrivacy, [
                $GLOBALS['SL']->def->getID('Privacy Types', 'Completely Anonymous'),
                $GLOBALS['SL']->def->getID('Privacy Types', 'Anonymized')
                ]))
                Anonymous
            @else
                <span style="color: #888;">No Privacy Setting</span>
            @endif

        </div>
    </td></tr>

</tbody></table>

<?php /*

<div class="pT10 bld">
    @if (isset($complaintRec->ComAnyoneCharged) 
        && in_array(trim($complaintRec->ComAnyoneCharged), ['Y', '?']))
        @if (isset($complaintRec->ComAllChargesResolved)
            && trim($complaintRec->ComAllChargesResolved) != 'Y')
            <div class="disIn mR10 red">Pending Charges,</div>
        @else
            <div class="disIn mR10 grn">Charges Resolved,</div>
        @endif
    @else
        <div class="disIn mR10 grn">No Pending Charges,</div>
    @endif

    @if ((isset($complaintRec->ComAttorneyHas) 
            && trim($complaintRec->ComAttorneyHas) == 'Y')
        || (isset($complaintRec->ComStatus) 
            && intVal($complaintRec->ComStatus) 
            == $GLOBALS["SL"]->def->getID(
            'Complaint Status', 'Attorney\'d')))
        <div class="disIn mR10 grn">Has Attorney,</div>
    @elseif (isset($complaintRec->ComAnyoneCharged) 
        && in_array(trim($complaintRec->ComAnyoneCharged), ['Y', '?'])
        && isset($complaintRec->ComAllChargesResolved)
        && trim($complaintRec->ComAllChargesResolved) != 'Y')
        <div class="disIn mR10 red">Needs Attorney,</div>
    @elseif (isset($complaintRec->ComAttorneyWant) 
        && trim($complaintRec->ComAttorneyWant) == 'Y')
        <div class="disIn mR10 clrWarn">Wants Attorney,</div>
    @else
        <div class="disIn mR10 grn">No Attorney Needs,</div>
    @endif

    @if ($complaintRec->ComPrivacy == $GLOBALS["SL"]->def->getID(
        'Privacy Types', 'Submit Publicly'))
        <div class="disIn mR10 grn">Submit Publicly</div>
    @elseif ($complaintRec->ComPrivacy == $GLOBALS['SL']->def->getID(
        'Privacy Types', 'Names Visible to Police but not Public'))
        <div class="disIn mR10 clrInfo">No Names Public</div>
    @elseif (in_array($complaintRec->ComPrivacy, [
        $GLOBALS['SL']->def->getID('Privacy Types', 'Completely Anonymous'),
        $GLOBALS['SL']->def->getID('Privacy Types', 'Anonymized')
        ]))
        <div class="disIn mR10 red">Anonymous</div>
    @else
        <div class="disIn mR10 slGrey">No Privacy Setting</div>
    @endif


</div>

*/ ?>