<!-- resources/views/vendor/openpolice/nodes/1712-report-inc-staff-tools-warnings.blade.php -->

<div style="color: #333; margin-top: -25px;">
    <div>
        <div class="relDiv">
            <div class="absDiv" style="top: 2px; left: 0px;">
                <div class="vertPrgDone" style="background:
                @if (isset($complaintRec->com_anyone_charged) 
                    && in_array(trim($complaintRec->com_anyone_charged), ['Y', '?']))
                    @if (isset($complaintRec->com_all_charges_resolved)
                        && trim($complaintRec->com_all_charges_resolved) != 'Y')
                        #FF6059;
                    @else
                        #29CD42;
                    @endif
                @else
                    #29CD42;
                @endif ">
            <?php /* <img src="/survloop/uploads/spacer.gif" alt="" border="0"> */ ?>
                </div>
            </div>
        </div>
        <div class="pL20">
            @if (isset($complaintRec->com_anyone_charged) 
                && in_array(trim($complaintRec->com_anyone_charged), ['Y', '?']))
                @if (isset($complaintRec->com_all_charges_resolved)
                    && trim($complaintRec->com_all_charges_resolved) == 'Y')
                    Charges Resolved
                @else
                    Pending Charges
                @endif
            @else
                No Pending Charges
            @endif
        </div>
    </div>
    
    <div>
        <div class="relDiv">
            <div class="absDiv" style="top: 2px; left: 0px;">
                <div class="vertPrgDone" style="background:
                @if ((isset($complaintRec->com_attorney_has) 
                        && trim($complaintRec->com_attorney_has) == 'Y')
                    || (isset($complaintRec->com_status) 
                        && intVal($complaintRec->com_status) 
                        == $GLOBALS["SL"]->def->getID('Complaint Status', 'Has Attorney')))
                    @if (!isset($complaint->com_attorney_oked) 
                        || trim($complaint->com_attorney_oked) != 'Y')
                        #FFBD2E;
                    @else
                        #29CD42;
                    @endif
                @elseif (isset($complaintRec->com_anyone_charged) 
                    && in_array(trim($complaintRec->com_anyone_charged), ['Y', '?'])
                    && isset($complaintRec->com_all_charges_resolved)
                    && trim($complaintRec->com_all_charges_resolved) != 'Y')
                    #FF6059;
                @elseif (isset($complaintRec->com_attorney_want) 
                    && trim($complaintRec->com_attorney_want) == 'Y')
                    #FFBD2E;
                @else
                    #29CD42;
                @endif ">
            <?php /* <img src="/survloop/uploads/spacer.gif" alt="" border="0"> */ ?>
                </div>
            </div>
        </div>
        <div class="pL20">
            @if ((isset($complaintRec->com_attorney_has) 
                    && trim($complaintRec->com_attorney_has) == 'Y')
                || (isset($complaintRec->com_status) 
                    && intVal($complaintRec->com_status) == $GLOBALS["SL"]->def->getID(
                        'Complaint Status', 'Has Attorney')))
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

    <div>
        <div class="relDiv">
            <div class="absDiv" style="top: 2px; left: 0px;">
                <div class="vertPrgDone" style="background:
                @if ($complaintRec->com_publish_user_name == 1
                    && $complaintRec->com_publish_officer_name == 1)
                    #29CD42;
                @elseif ($complaintRec->com_anon != 1)
                    @if (isset($complaintRec->com_publish_user_name)
                        && isset($complaintRec->com_publish_officer_name))
                        #63C6FF;
                    @else 
                        #888;
                    @endif
                @else
                    #FF6059;
                @endif ">
            <?php /* <img src="/survloop/uploads/spacer.gif" alt="" border="0"> */ ?>
                </div>
            </div>
        </div>
        <div class="pL20">
            @if ($complaintRec->com_publish_user_name == 1
                && $complaintRec->com_publish_officer_name == 1)
                Submit Publicly
            @elseif ($complaintRec->com_anon != 1)
                @if ($complaintRec->com_publish_user_name == 1
                    && $complaintRec->com_publish_officer_name == 1)
                    Submit Publicly
                @elseif ($complaintRec->com_publish_user_name == 1)
                    Publish Complainant's Name
                @elseif ($complaintRec->com_publish_officer_name == 1)
                    Publish Officers' Names
                @elseif (isset($complaintRec->com_publish_user_name)
                    && isset($complaintRec->com_publish_officer_name))
                    Publish No Names
                @else 
                    No Publishing Settings
                @endif
            @else
                Anonymous
            @endif
        </div>
    </div>
</div>
