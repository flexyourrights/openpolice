<!-- resources/views/vendor/openpolice/nodes/1712-report-inc-staff-tools-warnings.blade.php -->

<div class="row">
    <div class="col-sm-4 taC pT10">
    @if (isset($complaintRec->ComAnyoneCharged) 
        && in_array(trim($complaintRec->ComAnyoneCharged), ['Y', '?']))
        @if (isset($complaintRec->ComAllChargesResolved)
            && trim($complaintRec->ComAllChargesResolved) != 'Y')
            <div class="w100 p10 taC brdRed">Pending Charges</div>
        @else
            <div class="w100 p10 taC brdGrn">Charges Resolved</div>
        @endif
    @else
        <div class="w100 p10 taC brdGrn">No Pending Charges</div>
    @endif
    </div>
    <div class="col-sm-4 taC pT10">
    @if ((isset($complaintRec->ComAttorneyHas) 
            && trim($complaintRec->ComAttorneyHas) == 'Y')
        || (isset($complaintRec->ComStatus) 
            && intVal($complaintRec->ComStatus) 
            == $GLOBALS["SL"]->def->getID(
            'Complaint Status', 'Attorney\'d')))
        <div class="w100 p10 taC brdGrn">Has Attorney</div>
    @elseif (isset($complaintRec->ComAnyoneCharged) 
        && in_array(trim($complaintRec->ComAnyoneCharged), ['Y', '?'])
        && isset($complaintRec->ComAllChargesResolved)
        && trim($complaintRec->ComAllChargesResolved) != 'Y')
        <div class="w100 p10 taC brdRed">Needs Attorney</div>
    @elseif (isset($complaintRec->ComAttorneyWant) 
        && trim($complaintRec->ComAttorneyWant) == 'Y')
        <div class="w100 p10 taC brdWarn">Wants Attorney</div>
    @else
        <div class="w100 p10 taC brdGrn">No Attorney Needs</div>
    @endif
    </div>
    <div class="col-sm-4 taC pT10">
    @if ($complaintRec->ComPrivacy == $GLOBALS["SL"]->def->getID(
        'Privacy Types', 'Submit Publicly'))
        <div class="w100 p10 taC brdGrn">Submit Publicly</div>
    @elseif ($complaintRec->ComPrivacy == $GLOBALS['SL']->def->getID(
        'Privacy Types', 'Names Visible to Police but not Public'))
        <div class="w100 p10 taC brdInfo">No Names Public</div>
    @elseif (in_array($complaintRec->ComPrivacy, [
        $GLOBALS['SL']->def->getID('Privacy Types', 'Completely Anonymous'),
        $GLOBALS['SL']->def->getID('Privacy Types', 'Anonymized')
        ]))
        <div class="w100 p10 taC brdRed">Anonymous</div>
    @else
        <div class="w100 p10 taC brdGrey slGrey">-</div>
    @endif
    </div>
</div>
