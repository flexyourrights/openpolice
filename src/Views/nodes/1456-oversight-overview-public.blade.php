<!-- resources/views/vendor/openpolice/nodes/1456-oversight-overview-public.blade.php -->
<div id="blockWrap{{ $nID }}" class="w100">
<div class="container" id="treeWrap{{ $nID }}">
<div class="fC"></div>
<div class="nodeAnchor"><a id="n{{ $nID }}" name="n{{ $nID }}"></a></div>
<div id="node{{ $nID }}" class="slReport" 
    @if ($nID == 1456) style="max-width: 940px;" @endif >

<p>&nbsp;</p>

<div class="slCard reportBlock">
<h3 class="float-right m0 slGrey">Found {{ $oversights->count() }}</h3>
<h2 class="mT0">Police Departments Investigative Agencies</h2>
<p>
    The OpenPolice.org Accessibility Score is calculated by the policies 
    and online tools run by each department and the 
    investigative agency where we submit reports.
</p>
<table class="table table-striped">
<tr><th>Department Name 
    <div class="slGrey fPerc66">Type</div></th>
    <th>State, County<div class="fPerc80 slGrey">Street Address</div></th>
    <th># of Employees</th><th>Score</th></tr>
@if ($oversights->isNotEmpty())
    @foreach ($oversights as $i => $over)
        <tr><td><a href="/dept/{{ $over->dept_slug }}"
            >{{ str_replace('Department', 'Dept.', $over->dept_name) }}</a>
            <div class="slGrey fPerc80">{{ 
                $GLOBALS["SL"]->def->getVal('Investigative Agency Types', $over->OverType) 
            }}</div></td>
        <td>{{ $over->over_address_state }}, {{ $over->over_address_county }}
            <div class="slGrey fPerc80">{{ $GLOBALS["SL"]->printRowAddy($over, 'dept_') }}</span></td>
        <td> @if ($over->over_tot_officers > 0) {{ number_format($over->over_tot_officers) }} 
            @else <a href="/volunteer" class="slGrey">?</a> @endif </td>
        <td>{{ $over->over_score_openness }}</td></tr>
    @endforeach
@else
    <tr><td><i>None found.</i></td></tr>
@endif
</table>
</div>

</div> <!-- end #node{{ $nID }} -->
</div>
</div>