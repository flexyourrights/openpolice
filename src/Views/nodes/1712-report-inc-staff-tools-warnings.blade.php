<!-- resources/views/vendor/openpolice/nodes/1712-report-inc-staff-tools-warnings.blade.php -->

<div class="disBlo ovrSho pT15" 
    style="color: #333; min-height: 45px;">
    <div id="complaintWarn1" class="fL disBlo mR20">
        <div class="relDiv">
            <div class="absDiv" style="top: 2px; left: 0px;">
                <div class="vertPrgDone" style="background:
                @if (isset($com->com_anyone_charged) 
                    && in_array(trim($com->com_anyone_charged), ['Y', '?']))
                    @if (isset($com->com_all_charges_resolved)
                        && trim($com->com_all_charges_resolved) != 'Y')
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
            @if (isset($com->com_anyone_charged) 
                && in_array(trim($com->com_anyone_charged), ['Y', '?']))
                @if (isset($com->com_all_charges_resolved)
                    && trim($com->com_all_charges_resolved) != 'Y')
                    Pending Charges
                @else
                    Charges Resolved
                @endif
            @else
                No Pending Charges
            @endif
        </div>
    </div>
    
<?php $defHasAtt = $GLOBALS["SL"]->def->getID('Complaint Status', 'Has Attorney'); ?>
    <div id="complaintWarn2" class="fL disBlo mR20">
        <div class="relDiv">
            <div class="absDiv" style="top: 2px; left: 0px;">
                <div class="vertPrgDone" style="background:
                @if ((isset($com->com_attorney_has) 
                        && trim($com->com_attorney_has) == 'Y')
                    || (isset($com->com_status) 
                        && intVal($com->com_status) == $defHasAtt))
                    @if (isset($com->com_attorney_oked) 
                        && trim($com->com_attorney_oked) == 'Y')
                        #29CD42;
                    @else
                        #FFBD2E;
                    @endif
                @elseif (isset($com->com_anyone_charged) 
                    && in_array(trim($com->com_anyone_charged), ['Y', '?'])
                    && isset($com->com_all_charges_resolved)
                    && trim($com->com_all_charges_resolved) != 'Y')
                    #FF6059;
                @elseif (isset($com->com_file_lawsuit) 
                    && trim($com->com_file_lawsuit) == 'Y')
                    #FFBD2E;
                @elseif (isset($com->com_attorney_want) 
                    && trim($com->com_attorney_want) == 'Y')
                    #FFBD2E;
                @else
                    #29CD42;
                @endif ">
            <?php /* <img src="/survloop/uploads/spacer.gif" alt="" border="0"> */ ?>
                </div>
            </div>
        </div>
        <div class="pL20" style="min-height: 30px;">
            @if ((isset($com->com_attorney_has) 
                    && trim($com->com_attorney_has) == 'Y')
                || (isset($com->com_status) 
                    && intVal($com->com_status) == $defHasAtt))
                @if (isset($com->com_attorney_oked) 
                    && trim($com->com_attorney_oked) == 'Y')
                    Attorney OK'd
                @else
                    Attorney OK?
                @endif
            @elseif (isset($com->com_anyone_charged) 
                && in_array(trim($com->com_anyone_charged), ['Y', '?'])
                && isset($com->com_all_charges_resolved)
                && trim($com->com_all_charges_resolved) != 'Y')
                Needs Attorney
            @elseif (isset($com->com_file_lawsuit) 
                && trim($com->com_file_lawsuit) == 'Y')
                Filing Lawsuit
            @elseif (isset($com->com_attorney_want) 
                && trim($com->com_attorney_want) == 'Y')
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
                @if (isset($com->com_publish_user_name)
                    && intVal($com->com_publish_user_name) == 1
                    && (!$hasOfficers 
                        || isset($com->com_publish_officer_name)
                            && intVal($com->com_publish_officer_name) == 1))
                    #29CD42;
                @elseif (isset($com->com_anon) && intVal($com->com_anon) == 1)
                    #FF6059;
                @else
                    @if (isset($com->com_publish_user_name)
                        && intVal($com->com_publish_user_name) == 1)
                        #416CBD;
                    @elseif (isset($com->com_publish_officer_name)
                        && intVal($com->com_publish_officer_name) == 1)
                        #416CBD;
                    @elseif (isset($com->com_publish_user_name)
                        && (!$hasOfficers || isset($com->com_publish_officer_name)))
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
            @if (isset($com->com_publish_user_name)
                && intVal($com->com_publish_user_name) == 1
                && (!$hasOfficers 
                    || isset($com->com_publish_officer_name)
                        && intVal($com->com_publish_officer_name) == 1))
                Submit Publicly
            @elseif (isset($com->com_anon) && intVal($com->com_anon) == 1)
                Anonymous
            @else
                @if (isset($com->com_publish_user_name)
                    && intVal($com->com_publish_user_name) == 1)
                    Publish Complainant's Name
                @elseif (isset($com->com_publish_officer_name)
                    && intVal($com->com_publish_officer_name) == 1)
                    Publish Officers' Names
                @elseif (isset($com->com_publish_user_name)
                    && (!$hasOfficers || isset($com->com_publish_officer_name)))
                    Publish No Names
                @else 
                    <span class="slGrey">No Publishing Settings</span>
                @endif
            @endif
        </div>
    </div>
    <div class="fC p5"></div>
</div>
