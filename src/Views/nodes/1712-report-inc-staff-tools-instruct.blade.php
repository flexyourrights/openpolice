<!-- resources/views/vendor/openpolice/nodes/1712-report-inc-staff-tools-instruct.blade.php -->

Next, this complaint could use help with...

<?php /*
@if ((isset($complaintRec->com_attorney_has) 
        && trim($complaintRec->com_attorney_has) == 'Y')
    || (isset($complaintRec->com_status) 
        && intVal($complaintRec->com_status) == $GLOBALS["SL"]->def->getID(
        'Complaint Status', 'Attorney\'d')))
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
@endif
*/ ?>

