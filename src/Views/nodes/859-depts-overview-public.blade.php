<!-- resources/views/vendor/openpolice/nodes/859-depts-overview-public.blade.php -->
<div id="blockWrap{{ $nID }}" class="w100">
<div class="container" id="treeWrap{{ $nID }}">
<div class="fC"></div><div class="nodeAnchor"><a id="n{{ $nID }}" name="n{{ $nID }}"></a></div>
<div id="node{{ $nID }}" class="slReport" @if ($nID == 1454) style="max-width: 940px;" @endif >

<p>&nbsp;</p>

<div class="slCard reportBlock">
<h3 class="pull-right m0 slGrey">Found 0</h3>
<h2 class="mT0">Police Departments' Complaints</h2>
<table class="table table-striped">
<tr><td><i>None found.</i></td></tr>
</table>
</div>

<div class="slCard reportBlock">
<h3 class="pull-right m0 slGrey">Found {{ $deptScores->count() }}</h3>
<h2 class="mT0">Police Departments' Accessibility Scores</h2>
<p>The OPC Accessibility Score is calculated by the policies and online tools run by each department and the 
oversight agency where we submit reports.</p>
<table class="table table-striped">
<tr><th>Department Name <div class="slGrey fPerc66">Type</div></th>
    <th>State, County<div class="fPerc80 slGrey">Street Address</div></th>
    <th># of Employees</th><th>Score</th></tr>
@if ($deptScores->isNotEmpty())
    @foreach ($deptScores as $i => $dept)
        <tr><td><a href="/dept/{{ $dept->DeptSlug }}">{{ str_replace('Department', 'Dept.', $dept->DeptName) }}</a>
            <div class="slGrey fPerc80">{{ $GLOBALS["SL"]->def->getVal('Department Types', $dept->DeptType) }}</div>
            </td>
        <td>{{ $dept->DeptAddressState }}, {{ $dept->DeptAddressCounty }}
            <div class="slGrey fPerc80">{{ $GLOBALS["SL"]->printRowAddy($dept, 'Dept') }}</span>
            </td>
        <td> @if ($dept->DeptTotOfficers > 0) {{ number_format($dept->DeptTotOfficers) }} 
            @else <a href="/volunteer" class="slGrey">?</a> @endif </td>
        <td>{{ $dept->DeptScoreOpenness }}</td></tr>
    @endforeach
@else
    <tr><td><i>None found.</i></td></tr>
@endif
</table>
</div>

</div> <!-- end #node{{ $nID }} -->
</div>
</div>