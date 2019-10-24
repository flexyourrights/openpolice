<!-- resources/views/vendor/openpolice/nodes/1712-report-inc-staff-tools-instruct.blade.php -->

Next, this complaint could use help with...

<?php /*
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
*/ ?>

