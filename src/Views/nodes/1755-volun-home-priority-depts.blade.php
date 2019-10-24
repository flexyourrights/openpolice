<!-- resources/views/vendor/openpolice/nodes/1755-volun-home-priority-depts.blade.php -->
<h2 class="mT0">
    <i class="fa fa-warning txtDanger" aria-hidden="true"></i> 
    High-Priority Departments
</h2>
@if (sizeof($deptPriorityRows) > 0)
    {!! view(
        'vendor.openpolice.volun.dept-rows', 
        [ "deptRows" => $deptPriorityRows ]
    )->render() !!}
@else
    <a class="list-group-item" href="javascript:;"
        >No priority departments found, so pick anything you like.</a>
@endif
