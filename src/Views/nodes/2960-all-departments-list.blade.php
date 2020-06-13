<!-- resources/views/vendor/openpolice/nodes/2960-all-departments-list.blade.php -->
<div id="blockWrap{{ $nID }}" class="w100">
<div class="container" id="treeWrap{{ $nID }}">
<div class="fC"></div>
<div class="nodeAnchor"><a id="n{{ $nID }}" name="n{{ $nID }}"></a></div>
<div id="node{{ $nID }}">

    <p>&nbsp;</p>
    <h2>
        {{ number_format($depts->count()+$feds->count()) }} 
        Police Departments by State and County
    </h2>
    <p>
        Click any state for a list of all of its police departments,
        whether or not they have been researched for Accessibility Scores.
    </p>

    <table border=0 class="table table-striped w100" >
    <?php $prevState = $prevCounty = ''; ?>
    @foreach ($depts as $dept)
        @if ($prevState != $dept->dept_address_state)
            <?php $prevState = $dept->dept_address_state; ?>
            <tr><td colspan=5 >
                <hr class="mT30">
                <h2 class="mT30">{{ $GLOBALS["SL"]->getState($prevState) }}</h2>
            </td></tr>
        @endif
        @if ($prevCounty != $dept->dept_address_county)
            <?php $prevCounty = $dept->dept_address_county; ?>
            <tr><td colspan=5 >
                <h3 class="slBlueDark">{{ $prevCounty }}, {{ $prevState }}</h3>
            </td></tr>
            <tr>
                <td> </td>
                <td>Department</td>
                <td colspan=2 >Score</td>
            </tr>
        @endif
        {!! view(
            'vendor.openpolice.nodes.2960-all-departments-list-row', 
            [ "dept" => $dept ]
        )->render() !!}
    @endforeach
        <tr><td colspan=5 >
            <hr class="mT30">
            <h2 class="mT30">Federal Law Enforcement</h2>
        </td></tr>
        <tr>
            <td> </td>
            <td>Department</td>
            <td colspan=2 >Score</td>
        </tr>
    @foreach ($feds as $dept)
        {!! view(
            'vendor.openpolice.nodes.2960-all-departments-list-row', 
            [ "dept" => $dept ]
        )->render() !!}
    @endforeach
    </table>

</div> <!-- end #node{{ $nID }} -->
</div>
</div>

