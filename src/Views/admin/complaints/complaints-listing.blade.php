<!-- resources/views/vendor/openpolice/admin/complaints/complaints-listing.blade.php -->

@extends('vendor.survloop.master')

@section('content')

<h1>
    <i class="fa fa-star"></i> 
    @if ($currPage == '/dashboard/complaints')                 All New Complaints, or Requiring Action
    @elseif ($currPage == '/dashboard/complaints/me')          All Complaints Assigned To Me
    @elseif ($currPage == '/dashboard/complaints/flagged')     All Complaints Flagged for Review
    @elseif ($currPage == '/dashboard/complaints/waiting')     All Complaints Waiting for Investigation
    @elseif ($currPage == '/dashboard/complaints/all')         All Complete Complaints
    @elseif ($currPage == '/dashboard/complaints/incomplete')  All Incomplete Complaints
    @endif
</h1>

<div class="p5"></div>

<table class="table">
<tr><th class="taC"><i>ID#</i></th><th>Status</th>
<th>@if ($currPage == '/dashboard/complaints/incomplete') Last Page
@elseif ($currPage == '/dashboard/complaints/unpublished') Type / Last Page @else Type @endif </th>
<th>Submitted</th><th>Incident</th><th>City</th><th>Complainant</th><th>Level</th><th>Privacy</th></tr>
@forelse ($complaints as $com)
    <tr>
        <td rowspan=2 class="taC" >
            <a href="/dashboard/complaint/{{ $com->ComPublicID }}/review" class="btn w100 
            @if ($com->ComStatus == $GLOBALS['SL']->getDefID('Complaint Status', 'New')) 
                btn-primary @else btn-default @endif ">#{{ number_format($com->ComPublicID) }}</a>
        </td>
        <td>
            <nobr>
            @if ($GLOBALS['SL']->getDefValue('Complaint Status', $com->ComStatus) == 'New')
                <i class="fa fa-star slRedDark"></i>
            @endif
            {{ $GLOBALS['SL']->getDefValue('Complaint Status', $com->ComStatus) }}
            </nobr>
        </td>
        <td>
            <nobr>
            @if ($com->ComStatus == $GLOBALS['SL']->getDefID('Complaint Status', 'Incomplete')) 
                @if ($com->ComSubmissionProgress > 0 && isset($lastNodes[$com->ComSubmissionProgress]))
                    <i class="fa fa-star-half-o" aria-hidden="true"></i>
                    <a href="/u/complaint/{{ $lastNodes[$com->ComSubmissionProgress] }}?preview=1" target="_blank"
                        >/{{ $lastNodes[$com->ComSubmissionProgress] }}</a>
                @endif
            @else 
                @if ($GLOBALS['SL']->getDefValue('OPC Staff/Internal Complaint Type', $com->ComType) == 'Unreviewed')
                    <i class="fa fa-star slRedDark"></i>
                @endif
                {{ $GLOBALS['SL']->getDefValue('OPC Staff/Internal Complaint Type', $com->ComType) }}
            @endif
            </nobr>
        </td>
        <td>
            {{ $comInfo[$com->ComID]["comDate"] }}
        </td>
        <td>
            @if (trim($com->IncDate) != '') {{ date("n/j/Y", strtotime($com->IncDate)) }} @endif
        </td>
        <td>{{ $com->IncAddressCity }}, {{ $com->IncAddressState }}</td>
        <td>{{ $com->PrsnNameFirst }} {{ $com->PrsnNameLast }}</td>
        <td>{{ $com->ComAwardMedallion }}</td>
        <td>
            @if ($com->ComPrivacy == 304) Open
            @elseif ($com->ComPrivacy == 305) No Names
            @elseif ($com->ComPrivacy == 306) Anonymous
            @elseif ($com->ComPrivacy == 307) Anonymized
            @endif
        </td>
    </tr>
    <tr>
        <td colspan=8 style="border-top: 0px none; padding-top: 0px;">
            <div class="slGrey" style="margin-top: -5px;">
            @if (trim($comInfo[$com->ComID]["alleg"]) != '') 
                {!! $comInfo[$com->ComID]["alleg"] !!} - 
            @endif
            @if (isset($com->ComSummary) && trim($com->ComSummary) != '')
                @if (strlen(strip_tags($com->ComSummary)) > (150-strlen($comInfo[$com->ComID]["alleg"])))
                    {{ substr(strip_tags($com->ComSummary), 0, (150-strlen($comInfo[$com->ComID]["alleg"]))) }}...
                @else
                    {{ $com->ComSummary }}
                @endif
            @endif
            </div>
        </td>
    </tr>
@empty
    <tr><td colspan=6 ><i>No complaints found in this filter</i></td></tr>
@endforelse
</table>


<div class="adminFootBuff"></div>

@endsection