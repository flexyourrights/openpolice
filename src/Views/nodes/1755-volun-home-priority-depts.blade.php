<!-- resources/views/vendor/openpolice/nodes/1755-volun-home-priority-depts.blade.php -->
@if (sizeof($deptPriorityRows) > 0) 
    <h2 class="m0 slRedDark pull-right"><i class="fa fa-warning" aria-hidden"true"=""></i></h2>
@endif
<h2 class="mT0">Priority Departments</h2>
<div class="pB5 gry6">Click a department below to verify it's information:</div>
<div class="list-group taL">
    @forelse ($deptPriorityRows as $dept)
        <a class="list-group-item" href="/dashboard/start-{{ $dept->DeptID }}/volunteers-research-departments">
            <div class="pull-right deptRgtCol">
                @if (intVal($dept->DeptScoreOpenness) > 0)
                    <h3 class="m0">{{ $dept->DeptScoreOpenness }}</h3>
                    {!! view('vendor.openpolice.volun.volunteer-recent-edits', [
                        "deptID" => $dept->DeptID ])->render() !!}
                    @if (trim($dept->DeptVerified) != '' && trim($dept->DeptVerified) != '0000-00-00 00:00:00')
                        <span class="gryA"><i>{{ date("n/j/y", strtotime($dept->DeptVerified)) }}</i></span>
                    @endif
                @else
                
                @endif
            </div>
            <h3 class="m0">{{ str_replace('Department', 'Dept', $dept->DeptName) }}</h3>
            <div class="gry9"><i>{{ $dept->DeptAddressCity }}, {{ $dept->DeptAddressState }}</i></div>
        </a>
    @empty
        <a class="list-group-item" href="javascript:;">No priority departments found, so pick anything you like.</a>
    @endforelse
</div>
