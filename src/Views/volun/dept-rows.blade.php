<!-- resources/views/vendor/openpolice/volun/dept-rows.blade.php -->
@if (sizeof($deptRows) > 0)
    <p>Click a department below to verify it's information and 
    <a href="/department-accessibility">Accessibility Score</a>:</p>
    <div class="p10"><div class="row">
        <div class="col-5"><b>Department Name</b></div>
        <div class="col-3"><b>City, State</b></div>
        <div class="col-1"><b>Score</b></div>
        <div class="col-3"><b>Researched</b></div>
    </div></div>
    @forelse ($deptRows as $i => $dept)
        @if ($i < 500 && $dept->dept_name != 'Not sure about department')
        <div class="p10 @if ($i%2 == 0) row2 @endif "><div class="row">
            <div class="col-5">
                <a href="/dashboard/start-{{ $dept->dept_id }}/volunteers-research-departments">
                    @if (isset($dept->dept_name)) {{ str_replace('Police Dept', 'PD',
                        str_replace('Department', 'Dept', $dept->dept_name)) }}
                    @else <span class="slGrey">(empty)</span> @endif </a>
            </div>
            <div class="col-3">
                {{ $dept->dept_address_city }}, {{ $dept->dept_address_state }}
            </div>
            <div class="col-1">
                @if (intVal($dept->dept_score_openness) > 0) <b>{{ $dept->dept_score_openness }}</b>
                @else <span class="slGery">0</span>
                @endif
            </div>
            <div class="col-3">
            @if (trim($dept->dept_verified) != '' 
                && !in_array($dept->dept_verified, 
                    ['0000-00-00 00:00:00', '2001-01-01 00:00:00']))
                {{ date("n/j/y", strtotime($dept->dept_verified)) }}
                {!! view('vendor.openpolice.volun.volunteer-recent-edits', [
                    "deptID" => $dept->dept_id
                ])->render() !!}
            @else
                <span class="slGery">-</span>
            @endif
            </div>
        </div></div>
        @endif
    @empty
    @endforelse
    @if (sizeof($deptRows) > 500)
        <div class="p10"><i>First 500 results printed above.</i></div>
    @endif
@endif