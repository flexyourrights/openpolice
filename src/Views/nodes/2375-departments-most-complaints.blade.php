<!-- resources/views/vendor/openpolice/nodes/2375-departments-most-complaints.blade.php -->

<p>&nbsp;</p>
<h2>Departments with Most Complaints Submitted</h2>

<div class="mT30 pB15 pL5 pR5 brdBot">
    <div class="row">
    @if ($isStaff)
        <div class="col-4"><b>Department Name</b></div>
        <div class="col-8"><b>Submitted to OpenPolice.org</b></div>
        <div class="col-5"></div>
        <div class="col-7"><b>Attorney Track</b></div>
        <div class="col-6"></div>
        <div class="col-6"><b>OK to File with Investigative Agency</b></div>
        <div class="col-7"></div>
        <div class="col-5"><b>Filed with Investigative Agency</b></div>
        <div class="col-8"></div>
        <div class="col-4"><b>Received by Investigative Agency</b></div>
        <div class="col-9"></div>
        <div class="col-3"><b>No Response from Investigative Agency</b></div>
        <div class="col-10"></div>
        <div class="col-2"><nobr><b>Investigative Agency Declined to Investigate</b></nobr></div>
        <div class="col-11"></div>
        <div class="col-1"><b>Investigated</b></div>
    @else
        <div class="col-5"><b>Department Name</b></div>
        <div class="col-7"><b>OK to File with Investigative Agency</b></div>
        <div class="col-6"></div>
        <div class="col-6"><b>Filed with Investigative Agency</b></div>
        <div class="col-7"></div>
        <div class="col-5"><b>Received by Investigative Agency</b></div>
        <div class="col-8"></div>
        <div class="col-4"><b>No Response from Investigative Agency</b></div>
        <div class="col-9"></div>
        <div class="col-3"><b><nobr>Investigative Agency Declined to Investigate</nobr></b></div>
        <div class="col-10"></div>
        <div class="col-2"><b>Investigated</b></div>
    @endif
    </div>
</div>

@if ($deptTots && isset($deptTots->dept_stat_ok_to_file))
    <div class="pT15 pB15 pL5 pR5 row2 brdBot">
        <div class="row">
            <div class=" @if ($isStaff) col-4 @else col-5 @endif ">
                <b>Totals</b>
            </div>
        @if ($isStaff)
            <div class="col-1 @if ($deptTots->dept_stat_submitted_op == 0) slGrey @else bld @endif ">
                {{ number_format($deptTots->dept_stat_submitted_op) }}
            </div>
            <div class="col-1 @if ($deptTots->dept_stat_attorneys == 0) slGrey @else bld @endif ">
                {{ number_format($deptTots->dept_stat_attorneys) }}
            </div>
        @endif
            <div class="col-1 @if ($deptTots->dept_stat_ok_to_file == 0) slGrey @else bld @endif ">
                {{ number_format($deptTots->dept_stat_ok_to_file) }}
            </div>
            <div class="col-1 @if ($deptTots->dept_stat_investigate_submitted == 0) slGrey @else bld @endif ">
                {{ number_format($deptTots->dept_stat_investigate_submitted) }}
            </div>
            <div class="col-1 @if ($deptTots->dept_stat_investigate_received == 0) slGrey @else bld @endif ">
                {{ number_format($deptTots->dept_stat_investigate_received) }}
            </div>
            <div class="col-1 @if ($deptTots->dept_stat_investigate_no_response == 0) slGrey @else bld @endif ">
                {{ number_format($deptTots->dept_stat_investigate_no_response) }}
            </div>
            <div class="col-1 @if ($deptTots->dept_stat_investgated == 0) slGrey @else bld @endif ">
                {{ number_format($deptTots->dept_stat_investgated) }}
            </div>
            <div class="col-1 @if ($deptTots->dept_stat_investigate_declined == 0) slGrey @else bld @endif ">
                {{ number_format($deptTots->dept_stat_investigate_declined) }}
            </div>
        </div>
    </div>
@endif

@if ($deptStats && $deptStats->isNotEmpty())
    @foreach ($deptStats as $i => $dept)
        <?php $pubTot = $dept->dept_stat_ok_to_file+$dept->dept_stat_investigate_submitted
            +$dept->dept_stat_investigate_received+$dept->dept_stat_investigate_no_response
            +$dept->dept_stat_investgated+$dept->dept_stat_investigate_declined; ?>
        @if ($isStaff || $pubTot > 0)
            <div class="pT15 pB15 pL5 pR5 @if ($i%2 == 1) row2 @endif ">
                <div class="row">
                    <div class=" @if ($isStaff) col-4 @else col-5 @endif ">
                        <a href="/dept/{{ $dept->dept_slug }}"
                            >{{ $dept->dept_name }}, {{ $dept->dept_address_state }}</a>
                    </div>
                @if ($isStaff)
                    <div class="col-1 @if ($dept->dept_stat_submitted_op == 0) slGrey @endif ">
                        {{ number_format($dept->dept_stat_submitted_op) }}
                    </div>
                    <div class="col-1 @if ($dept->dept_stat_attorneys == 0) slGrey @endif ">
                        {{ number_format($dept->dept_stat_attorneys) }}
                    </div>
                @endif
                    <div class="col-1 @if ($dept->dept_stat_ok_to_file == 0) slGrey @endif ">
                        {{ number_format($dept->dept_stat_ok_to_file) }}
                    </div>
                    <div class="col-1 @if ($dept->dept_stat_investigate_submitted == 0) slGrey @endif ">
                        {{ number_format($dept->dept_stat_investigate_submitted) }}
                    </div>
                    <div class="col-1 @if ($dept->dept_stat_investigate_received == 0) slGrey @endif ">
                        {{ number_format($dept->dept_stat_investigate_received) }}
                    </div>
                    <div class="col-1 @if ($dept->dept_stat_investigate_no_response == 0) slGrey @endif ">
                        {{ number_format($dept->dept_stat_investigate_no_response) }}
                    </div>
                    <div class="col-1 @if ($dept->dept_stat_investgated == 0) slGrey @endif ">
                        {{ number_format($dept->dept_stat_investgated) }}
                    </div>
                    <div class="col-1 @if ($dept->dept_stat_investigate_declined == 0) slGrey @endif ">
                        {{ number_format($dept->dept_stat_investigate_declined) }}
                    </div>
                </div>
            </div>
        @endif
    @endforeach
@else
    <p>&nbsp;</p><p><i>No departments found.</i></p><p>&nbsp;</p>
@endif
