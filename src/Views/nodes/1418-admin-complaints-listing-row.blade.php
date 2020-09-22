<!-- resources/views/vendor/openpolice/nodes/1418-admin-complaints-listing-row.blade.php -->

<div id="comRow{{ $com->com_id }}" class="complaintRowWrp">

    <a class="complaintRowA" href="javascript:;"
        data-com-id="{{ $com->com_id }}" 
        data-com-pub-id="{{ intVal($com->com_public_id) }}">
        <div class="float-left complaintAlert">
            <div class="mLn5">&nbsp;
            @if (in_array($GLOBALS['SL']->def->getVal('Complaint Type', $com->com_type), 
                    ['Unverified', 'Not Sure'])
                || ($GLOBALS['SL']->def->getVal('Complaint Type', $com->com_type) 
                    == 'Police Complaint'
                    && in_array($GLOBALS['SL']->def->getVal('Complaint Status', $com->com_status), 
                        ['New', 'Hold', 'Reviewed'])))
                <div class="litRedDot"></div>
            @elseif ($GLOBALS['SL']->def->getVal('Complaint Type', $com->com_type) 
                    == 'Police Complaint'
                && in_array($GLOBALS['SL']->def->getVal('Complaint Status', $com->com_status), 
                    ['Needs More Work', 'Pending Attorney', 'OK to Submit to Oversight']))
                <div class="litRedDottie"></div>
            @endif
            </div>
        </div>
        <div class="float-left">
            <b>{!! $GLOBALS["SL"]->charLimitDotDotDot(
                ( ((isset($com->com_anon) && intVal($com->com_anon) == 1)
                    || trim($com->prsn_name_first . ' ' . $com->prsn_name_last) == '')
                        ? 'Anonymous, '
                        : $GLOBALS["SL"]->convertAllCallToUp1stChars(
                            $com->prsn_name_first . ' ' . $com->prsn_name_last
                        ) . ', ')
                    . trim($com->inc_address_city) . ', ' . $com->inc_address_state,
                42
            ) !!}</b><br />
            <span class="slGrey">
        @if ($com->com_public_id <= 0)
            #i{{ number_format($com->com_id) }}
            @if ($com->com_submission_progress > 0 
                && isset($lastNodes[$com->com_submission_progress]))
                /{{ $lastNodes[$com->com_submission_progress] }}
            @endif
        @else
            #{{ number_format($com->com_public_id) }}
            @if ($GLOBALS['SL']->def->getVal('Complaint Type', $com->com_type) 
                == 'Police Complaint')
                {{ str_replace(
                    'Oversight', 
                    'Investigators',
                    $GLOBALS['SL']->def->getVal('Complaint Status', $com->com_status)
                ) }}
            @endif
        @endif
        @if ($com->com_status 
                != $GLOBALS['SL']->def->getID('Complaint Status', 'Incomplete') 
            && $com->com_type 
                != $GLOBALS["SL"]->def->getID('Complaint Type',  'Police Complaint'))
            {{ $GLOBALS['SL']->def->getVal('Complaint Type', $com->com_type) }}
        @endif
            </span>
        </div>
        <div class="float-right">
        @if (isset($com->com_record_submitted))
            &nbsp;<br /><span class="slGrey">{{ 
                date("n/j/y", strtotime($com->com_record_submitted)) 
            }}</span>
        @endif
        </div>
        <div class="fC"></div>
    </a>

    <a class="complaintRowFull" 
        @if ($com->com_public_id > 0) 
            href="/dash/complaint/read-{{ $com->com_public_id }}"
        @else 
            href="/dash/complaint/readi-{{ $com->com_id }}"
        @endif ><i class="fa fa-arrows-alt" aria-hidden="true"></i></a>
    
    <div id="resultSpin{{ $com->com_id }}" class="resultSpin"></div>

</div>