<!-- resources/views/vendor/openpolice/admin/lists/depts.blade.php -->

@extends('vendor.survloop.admin.admin')

@section('content')

<h1>
    <i class="fa fa-list-ul"></i> Police Departments with Complaints 
    <nobr><span class="f14">({{ number_format(sizeof($departments)) }})</span></nobr>
</h1>

<a href="javascript:void(0)" class="btn btn-default mR10" disabled ><i class="fa fa-plus-circle"></i> Add New Department</a>
<a href="/volunteer/all" class="btn btn-default mR10">All Departments</a>

<div class="p5"></div>

<table class="table table-striped" >
<tr><th></th><th>Name</th><th>City, State</th><th class="gry9"><b># of Complaints</b></th><th class="gry9"><b># of Allegations</b></th></tr>

@forelse($departments as $dept)
    <tr>
    <td class="vaT pL5"><a href="/volunDept/{{ $dept->DeptSlug }}" class="nFormLnkEdit"><i class="fa fa-pencil"></i></a></td>
    <td><a href="/volunDept/{{ $dept->DeptSlug }}" class="f18 slBlueDark noUnd"><b>{{ $dept->DeptName }}</b></a></td>
    <td>{{ $dept->DeptAddressCity }}, {{ $dept->DeptAddressState }}</td>
    <td><?= number_format(intVal($deptComplaints[$dept->DeptID])) ?></td>
    <td><?= number_format(intVal($deptAllegs[$dept->DeptID])) ?></td>
    </tr>
@empty
    <tr><td colspan=4 >No departments found.</td></tr>
@endforelse

</table>


<div class="slBlueDark" style="padding-left: 100px;"><h2><i>Coming Soon</i></h2></div>


<div class="adminFootBuff"></div>

@endsection