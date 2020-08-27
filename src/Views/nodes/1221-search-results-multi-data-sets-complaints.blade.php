<!-- resources/views/vendor/openpolice/nodes/1221-search-results-multi-data-sets-complaints.blade.php -->

<div class="pB15 pL5 pR5 brdBot relDiv">
    <div class="row">
        <div class="col-md-1 col-sm-12">
            Complaint ID#
        </div>
        <div class="col-md-3 col-sm-12">
            Complainant Name
        </div>
        <div class="col-md-3 col-8">
            Incident City, State
        </div>
        <div class="col-md-3 col-sm-12">
            Status
        </div>
        <div class="col-md-2 col-12">
            Date Submitted to OpenPolice.org
        </div>
    </div>
</div>

@if (sizeof($complaints) > 0)
    <?php $hasIncomplete = false; ?>
    @foreach ($complaints as $i => $com)
        @if ($i < $limit)

            <div id="complaintResultsAnim{{ $i }}" style="display: none;"
                class="pT15 pB15 pL5 pR5 @if ($i%2 == 0) row2 @endif ">
                <div class="row 
                    @if (!isset($com->complaint->com_public_id)
                        || intVal($com->complaint->com_public_id) == 0)
                        slGrey 
                    @endif ">
                    <div class="col-md-1 col-sm-12">
                    @if (isset($com->complaint->com_public_id)
                        && intVal($com->complaint->com_public_id) > 0)
                        <a href="/complaint/read-{{ $com->complaint->com_public_id }}"
                            ><nobr>{!! '#' . $com->complaint->com_public_id !!}</nobr></a>
                    @else
                        <nobr><a href="/complaint/readi-{{ $com->comID }}"
                            class="slGrey">{!! '#' . $com->comID !!}</a>
                        <sup class="slGrey">*</sup></nobr>
                        <?php $hasIncomplete = true; ?>
                    @endif
                    </div>
                    <div class="col-md-3 col-sm-12">
                    @if ((isset($com->complainant->prsn_name_first)
                            && trim($com->complainant->prsn_name_first) != '')
                        || (isset($com->complainant->prsn_name_last)
                            && trim($com->complainant->prsn_name_last) != ''))
                        @if ($isStaff 
                            || (isset($com->complaint->com_publish_user_name)
                                && intVal($com->complaint->com_publish_user_name) == 1))
                            {{ trim(trim($com->complainant->prsn_name_first) . ' '
                                . trim($com->complainant->prsn_name_last)) }}
                        @else
                            <i>Name Not Published</i>
                        @endif
                    @elseif (isset($com->complainant->prsn_nickname)
                        && trim($com->complainant->prsn_nickname) != '')
                        @if ($isStaff 
                            || (isset($com->complaint->com_publish_user_name)
                                && intVal($com->complaint->com_publish_user_name) == 1))
                            {{ trim(trim($com->complainant->prsn_name_first) . ' '
                                . trim($com->complainant->prsn_name_last)) }}
                            {{ trim($com->complainant->prsn_nickname) }}
                        @else
                            <i>Name Not Published</i>
                        @endif
                    @else
                        <i>Anonymous</i>
                    @endif
                    </div>
                    <div class="col-md-3 col-8">
                    @if (isset($com->complaint->inc_address_city)
                        && trim($com->complaint->inc_address_city) != '')
                        {{ $com->complaint->inc_address_city }},
                    @endif
                    @if (isset($com->complaint->inc_address_state)
                        && trim($com->complaint->inc_address_state) != '')
                        {{ $com->complaint->inc_address_state }}
                    @endif
                    </div>
                    <div class="col-md-3 col-sm-12">
                    @if (isset($com->complaint->com_status)
                        && intVal($com->complaint->com_status) > 0)
                        {{ $GLOBALS["SL"]->charLimitDotDotDot(
                            str_replace(
                                'Oversight', 
                                'Investigative Agency', 
                                $GLOBALS["SL"]->def->getVal(
                                    'Complaint Status', 
                                    $com->complaint->com_status
                                )
                            ),
                            25
                        ) }}
                    @endif
                    </div>
                    <div class="col-md-2 col-12">
                    @if (isset($com->complaint->com_record_submitted)
                        && trim($com->complaint->com_record_submitted) != '')
                        {{ date(
                            "n/j/y", 
                            strtotime($com->complaint->com_record_submitted)
                        ) }}
                    @endif
                    </div>
                </div>
            </div>
            
        @endif
    @endforeach
    @if ($hasIncomplete)
        <div class="pT15 pB15 slGrey">
            <sup>*</sup> Incomplete submissions with internal tracking ID#.
        </div>
    @endif
@else
    <div id="complaintResultsNone" class="pT15 pB15">No Complaints Found</div>
@endif
<div id="complaintResultsSpin">{!! $GLOBALS["SL"]->spinner() !!}</div>

<script type="text/javascript">
addResultAnimBase("complaintResults");
</script>
