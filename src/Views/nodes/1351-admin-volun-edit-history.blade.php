<!-- resources/views/vendor/openpolice/nodes/1351-admin-volun-edit-history.blade.php -->

{!! $volunDataGraph !!}

<div class="p20"></div>
<a name="recentEdits"></a> <h2>100 Most Recent Department Edits</h2>
<table class="table table-striped" border=0 cellpadding=10 >
    <tr><th>Edit Details</th><th>Department Info</th><th>Internal Affairs</th><th>Civilian Oversight</th></tr>
    {!! $recentEdits !!}
</table>