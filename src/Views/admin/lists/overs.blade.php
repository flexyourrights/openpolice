<!-- resources/views/vendor/openpolice/admin/lists/overs.blade.php -->

@extends('vendor.survloop.admin.admin')

@section('content')

<h1>
    <i class="fa fa-list-ul"></i> Oversight Agencies 
    <nobr><span class="f14">({{ number_format(sizeof($oversights)) }})</span></nobr>
</h1>

<a href="javascript:void(0)" class="btn btn-default mR10" disabled ><i class="fa fa-plus-circle"></i> Add Oversight Agency</a>

<div class="p5"></div>

<table class="table table-striped" >
</td></tr>
<tr>
    <th></th>
    <th><div class="slBlueDark"><b>Agency Name</div>Police Department</th>
    <th>City, State</th>
    <th>Website<br />Phone</th>
</tr>

@forelse($oversights as $over)
    <a name="o{{ $over->OverID }}"></name>
    <tr>
    <td class="vaT"><a href="/volunteer/verify/{{ $over->DeptSlug }}#over"><i class="fa fa-pencil"></i></a></td>
    <td class="vaT">
        <a href="/volunteer/verify/{{ $over->DeptSlug }}#over" class="w100">
        <div class="slBlueDark f18">{{ $over->OverAgncName }}</div>
        <div class="gry4">{{ $over->DeptName }}</div>
        </a>
    </td>
    <td class="vaT">
        {{ $over->OverAddressCity }}, {{ $over->OverAddressState }}
    </td>
    <td class="vaT">
        <a href="{{ $over->OverWebsite }}" target="_blank" class="noUnd"><?= $GLOBALS["DB"]->urlPreview($over->OverWebsite) ?></a><br />
        {{ $over->OverPhoneWork }}
    </td>
    </tr>
@empty
    <tr><td colspan=4 >No oversight agencies found.</td></tr>
@endforelse

</table>


<div class="adminFootBuff"></div>

@endsection