<!-- resources/views/vendor/openpolice/nodes/1712-report-inc-staff-tools.blade.php -->
<?php $defSet = 'Complaint Status'; ?>

<div class="pT20 pB20">
    <div class="slCard">
        <div class="slAccordBig">
            <div class="accordHeadWrap" style="background: none;">
                <div class="fL mT3"><h4>
                    {!! $toolkitTitle !!}
                </h4></div>
                <div class="fC"></div>
            </div>

        @if (isset($complaintRec->com_status)
            && $GLOBALS["SL"]->def->getVal($defSet, $complaintRec->com_status) 
                == 'Incomplete')
            
            <div class="pB15">
                <div class="row" style="color: #333;">
                    <div class="col-lg-4">
                        <div class="relDiv ovrSho" style="height: 30px;">
                            <div class="absDiv" style="top: 8px; left: 15px;">
                                <div class="vertPrgDone" 
                                    style="background: #FF6059;"></div>
                            </div>
                            <div class="absDiv" style="top: 7px; left: 40px;">
                                Incomplete
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        @elseif (isset($complaintRec->com_type)
            && $GLOBALS["SL"]->def->getVal('Complaint Type', $complaintRec->com_type) 
                == 'Police Complaint')

            {!! view(
                'vendor.openpolice.nodes.1712-report-inc-staff-tools-warnings', 
                [
                    "com"         => $complaintRec,
                    "hasOfficers" => $hasOfficers
                ]
            )->render() !!}

        @endif


        @if ($firstReview 
            && $GLOBALS["SL"]->def->getVal($defSet, $complaintRec->com_status) 
                != 'Incomplete')
            
            <div class="pT15 pB15 slBlueDark">
                <i class="fa fa-check-square-o mL5 mR5" aria-hidden="true"></i>
                    <b>Is this a <i>Police</i> Complaint?</b>
                    {!! str_replace('Next', 'First', $alertIco) !!}
            </div>
            <div id="firstReviewWrap">
                <div class="mTn30 mB30">{!! $GLOBALS["SL"]->spinner() !!}</div>
            </div>
            <div class="pT0 pL15 pR15">
            {!! $GLOBALS["SL"]->printAccordTxt(
                'About these submission types',
                view(
                    'vendor.openpolice.nodes.1712-report-inc-staff-tools-first-about-types'
                )->render(),
                false,
                'caret'
            ) !!}
            </div>
            
        @elseif (in_array($view, ['', 'history', 'update', 'emails', 'emailsType']))

            <div id="analystHistory">

                <div class="brdTopGrey">
                {!! $GLOBALS["SL"]->printAccordian(
                    '<i class="fa fa-sliders mL5 mR5" aria-hidden="true"></i> ' . $updateTitle,
                    '<div id="statusUpdateWrap" class="pL15 pR15"><div class="mTn30 mB30">' 
                        . $GLOBALS["SL"]->spinner() . '</div></div>',
                    false,
                    false,
                    'text',
                    $ico
                ) !!}
                </div>

            @if (isset($complaintRec->com_status) && intVal($complaintRec->com_status) > 0)

                <div class="brdTopGrey">
                {!! $GLOBALS["SL"]->printAccordian(
                    '<i class="fa fa-envelope-o mL5 mR5" aria-hidden="true"></i>'
                        . ' <b>Send Email</b>' . (($emailID > 0) ? $alertIco : ''),
                    '<div id="staffEmailWrap" class="pL15 pR15"><div class="mTn30 mB30">' 
                        . $GLOBALS["SL"]->spinner() . '</div></div>',
                    false,
                    false,
                    'text',
                    $ico
                ) !!}
                <?php /* (($emailID > 0) ? true : false), */ ?>
                </div>

            @endif

            @if (isset($complaintRec->com_type)
                && in_array($GLOBALS["SL"]->def->getVal('Complaint Type', $complaintRec->com_type), 
                    ['Unverified', 'Police Complaint', 'Not Sure']))

                <div class="brdTopGrey">
                {!! $GLOBALS["SL"]->printAccordian(
                    '<i class="fa fa-pencil-square-o mL5 mR5" aria-hidden="true"></i>'
                        . ' <b>Make Complaint Corrections</b>',
                    '<div id="staffEditsWrap" class="pL15 pR15"><div class="mTn30 mB30">' 
                        . $GLOBALS["SL"]->spinner() . '</div></div>',
                    false,
                    false,
                    'text',
                    $ico
                ) !!}
                </div>

            @endif

                <div class="brdTopGrey">
                {!! $GLOBALS["SL"]->printAccordian(
                    '<i class="fa fa-comments-o mL5 mR5" aria-hidden="true"></i>'
                        . ' <b>Complaint History</b>',
                    '<div class="pL15 pR15">' . view(
                        'vendor.openpolice.nodes.1712-report-inc-history', 
                        [ "history" => $history ]
                    )->render() . '</div>',
                    (($view == 'history' 
                        || ($GLOBALS["SL"]->REQ->has('view') 
                            && $GLOBALS["SL"]->REQ->view == 'history'))
                        ? true : false),
                    false,
                    'text',
                    $ico
                ) !!}
                </div>

            </div>
                
        @endif

        </div>
    </div>
</div>


<style>
#blockWrap2336 { display: none; }
@if ($GLOBALS["SL"]->REQ->has('frame') || $GLOBALS["SL"]->REQ->has('wdg'))
    #node2632kids { margin-top: -20px; }
@endif
</style>

<script type="text/javascript"> $(document).ready(function(){

@if ($firstReview 
    && $GLOBALS["SL"]->def->getVal($defSet, $complaintRec->com_status) 
        != 'Incomplete')

    <?php $src = "/dash/complaint-toolbox-first-review-form/readi-"
        . $complaintRec->com_id . "?ajax=1"; ?>
    setTimeout(function() { 
        console.log("{!! $src !!}"); 
        $("#firstReviewWrap").load("{!! $src !!}");
    }, 100);

@elseif (in_array($view, ['', 'history', 'update', 'emails', 'emailsType']))

    <?php $src = "/dash/complaint-toolbox-status-forms/readi-" 
        . $complaintRec->com_id . "?ajax=1"; ?>
    setTimeout(function() { 
        console.log("{!! $src !!}"); 
        $("#statusUpdateWrap").load("{!! $src !!}");
    }, 100);

    @if (isset($complaintRec->com_status) && intVal($complaintRec->com_status) > 0)

        <?php $src = "/dash/complaint-toolbox-email-form/readi-" 
            . $complaintRec->com_id . "?ajax=1"; ?>
        setTimeout(function() {
            console.log("{!! $src !!}"); 
            $("#staffEmailWrap").load("{!! $src !!}");
        }, 400);

    @endif

    @if (isset($complaintRec->com_type)
        && in_array($GLOBALS["SL"]->def->getVal('Complaint Type', $complaintRec->com_type), 
            ['Unverified', 'Police Complaint', 'Not Sure']))

        <?php $src = "/dash/complaint-toolbox-edit-details/readi-" 
            . $complaintRec->com_id . "?ajax=1"; ?>
        setTimeout(function() {
            console.log("{!! $src !!}"); 
            $("#staffEditsWrap").load("{!! $src !!}");
        }, 700);

    @endif

@endif

}); </script>
