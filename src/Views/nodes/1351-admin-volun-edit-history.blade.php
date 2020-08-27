<!-- resources/views/vendor/openpolice/nodes/1351-admin-volun-edit-history.blade.php -->

<h4>Volunteer Activity History</h4>
<div class="mBn20">{!! $volunDataGraph !!}</div>

<p><br /></p><hr><p><br /></p>

<div class="nodeAnchor"><a name="recentEdits"></a></div>
<h4>100 Most Recent Department Edits</h4>
<table class="table table-striped" border=0 cellpadding=10 >
    <tr>
        <th>Edit Details</th>
        <th>Department Info</th>
        <th>Internal Affairs</th>
        <th>Civilian Oversight</th>
    </tr>
    {!! $recentEdits !!}
</table>
