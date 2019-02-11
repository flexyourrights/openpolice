<!-- resources/views/vendor/openpolice/volun/dept-rows.blade.php -->
@if (sizeof($deptRows) > 0)
    <p>Click a department below to verify it's information and Accessibility Score:</p>
    <div class="p10"><div class="row">
        <div class="col-md-6"><b>Department Name</b></div>
        <div class="col-md-3 taR"><b>City, State</b></div>
        <div class="col-md-1"><b>Score</b></div>
        <div class="col-md-2"><b>Last Researched</b></div>
    </div></div>
    @forelse ($deptRows as $i => $dept)
        @if ($i < 500)
        <div class="p10 @if ($i%2 == 0) row2 @endif "><div class="row">
            <div class="col-md-6">
                <a href="/dashboard/start-{{ $dept->DeptID }}/volunteers-research-departments">
                    @if (isset($dept->DeptName)) {{ str_replace('Department', 'Dept', $dept->DeptName) }}
                    @else <span class="slGrey">(empty)</span> @endif </a>
            </div>
            <div class="col-md-3 taR">
                {{ $dept->DeptAddressCity }}, {{ $dept->DeptAddressState }}
            </div>
            <div class="col-md-1">
                @if (intVal($dept->DeptScoreOpenness) > 0) <b>{{ $dept->DeptScoreOpenness }}</b>
                @else <span class="slGery">0</span>
                @endif
            </div>
            <div class="col-md-2">
                {!! view('vendor.openpolice.volun.volunteer-recent-edits', [ "deptID" => $dept->DeptID ])->render() !!}
                @if (trim($dept->DeptVerified) != '' 
                    && !in_array($dept->DeptVerified, ['0000-00-00 00:00:00', '2001-01-01 00:00:00']))
                    {{ date("n/j/y", strtotime($dept->DeptVerified)) }}
                @else
                    <span class="slGery">-</span>
                @endif
            </div>
        </div></div>
        @endif
    @empty @endforelse
    @if (sizeof($deptRows) > 500)
        <div class="p10"><i>First 500 results printed above.</i></div>
    @endif
@endif