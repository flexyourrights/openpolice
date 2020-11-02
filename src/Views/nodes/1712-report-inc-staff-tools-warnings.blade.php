<!-- resources/views/vendor/openpolice/nodes/1712-report-inc-staff-tools-warnings.blade.php -->

<div class="disBlo ovrSho pT15" 
    style="color: #333; min-height: 45px;">
    <div id="complaintWarn1" class="fL disBlo mR20">
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
        <div class="pL20" style="min-height: 30px;">
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
    
    <div id="complaintWarn2" class="fL disBlo mR20">
        <div class="relDiv">
            <div class="absDiv" style="top: 2px; left: 0px;">
                <div class="vertPrgDone" style="background:
                @if ((isset($complaintRec->com_attorney_has) 
                        && trim($complaintRec->com_attorney_has) == 'Y')
                    || (isset($complaintRec->com_status) 
                        && intVal($complaintRec->com_status) 
                        == $GLOBALS["SL"]->def->getID('Complaint Status', 'Has Attorney')))
                    @if (isset($complaint->com_attorney_oked) 
                        && trim($complaint->com_attorney_oked) == 'Y')
                        #29CD42;
                    @else
                        #FFBD2E;
                    @endif
                @elseif (isset($complaintRec->com_anyone_charged) 
                    && in_array(trim($complaintRec->com_anyone_charged), ['Y', '?'])
                    && isset($complaintRec->com_all_charges_resolved)
                    && trim($complaintRec->com_all_charges_resolved) != 'Y')
                    #FF6059;
                @elseif (isset($complaintRec->com_file_lawsuit) 
                    && trim($complaintRec->com_file_lawsuit) == 'Y')
                    #FFBD2E;
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
        <div class="pL20" style="min-height: 30px;">
            @if ((isset($complaintRec->com_attorney_has) 
                    && trim($complaintRec->com_attorney_has) == 'Y')
                || (isset($complaintRec->com_status) 
                    && intVal($complaintRec->com_status) 
                    == $GLOBALS["SL"]->def->getID('Complaint Status', 'Has Attorney')))
                @if (isset($complaint->com_attorney_oked) 
                    && trim($complaint->com_attorney_oked) == 'Y')
                    Attorney OK'd
                @else
                    Attorney OK?
                @endif
            @elseif (isset($complaintRec->com_anyone_charged) 
                && in_array(trim($complaintRec->com_anyone_charged), ['Y', '?'])
                && isset($complaintRec->com_all_charges_resolved)
                && trim($complaintRec->com_all_charges_resolved) != 'Y')
                Needs Attorney
            @elseif (isset($complaintRec->com_file_lawsuit) 
                && trim($complaintRec->com_file_lawsuit) == 'Y')
                Filing Lawsuit
            @elseif (isset($complaintRec->com_attorney_want) 
                && trim($complaintRec->com_attorney_want) == 'Y')
                Wants Attorney
            @else
                No Attorney Needs
            @endif
        </div>
    </div>

    <div id="complaintWarn3" class="fL disBlo mR20">
        <div class="relDiv">
            <div class="absDiv" style="top: 2px; left: 0px;">
                <div class="vertPrgDone" style="background:
                @if (isset($complaintRec->com_publish_user_name)
                    && $complaintRec->com_publish_user_name == 1
                    && isset($complaintRec->com_publish_officer_name)
                    && $complaintRec->com_publish_officer_name == 1)
                    #29CD42;
                @elseif (intVal($complaintRec->com_anon) == 1)
                    #FF6059;
                @else
                    @if (isset($complaintRec->com_publish_user_name)
                        && isset($complaintRec->com_publish_officer_name))
                        #416CBD;
                    @else 
                        #888;
                    @endif
                @endif ">
            <?php /* <img src="/survloop/uploads/spacer.gif" alt="" border="0"> */ ?>
                </div>
            </div>
        </div>
        <div class="pL20" style="min-height: 30px;">
            @if (isset($complaintRec->com_publish_user_name)
                && $complaintRec->com_publish_user_name == 1
                && isset($complaintRec->com_publish_officer_name)
                && $complaintRec->com_publish_officer_name == 1)
                Submit Publicly
            @elseif (intVal($complaintRec->com_anon) == 1)
                Anonymous
            @else
                @if ($complaintRec->com_publish_user_name == 1
                    && $complaintRec->com_publish_officer_name == 1)
                    Submit Publicly
                @elseif ($complaintRec->com_publish_user_name == 1)
                    Publish Complainants' Name
                @elseif ($complaintRec->com_publish_officer_name == 1)
                    Publish Officers' Names
                @elseif (isset($complaintRec->com_publish_user_name)
                    && isset($complaintRec->com_publish_officer_name))
                    Publish No Names
                @else 
                    <span class="slGrey">No Publishing Settings</span>
                @endif
            @endif
        </div>
    </div>
    <div class="fC p5"></div>
</div>
