<!-- resources/views/vendor/openpolice/nodes/1351-admin-volun-edit-history.blade.php -->
<div class="slCard nodeWrap pB0">
    <h2>Volunteer Activity History</h2>
    <div class="mBn20">{!! $volunDataGraph !!}</div>
</div>
<div class="nodeAnchor"><a name="recentEdits"></a></div>
<div class="slCard nodeWrap">
    <h2>100 Most Recent Department Edits</h2>
    <table class="table table-striped" border=0 cellpadding=10 >
        <tr>
            <th>Edit Details</th>
            <th>Department Info</th>
            <th>Internal Affairs</th>
            <th>Civilian Oversight</th>
        </tr>
        {!! $recentEdits !!}
    </table>
</div>
<style> #mainBody { background: #F5FBFF; } </style>