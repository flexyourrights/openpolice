<!-- resources/views/vendor/openpolice/volun/admPrintDeptEdit.blade.php -->

@if (isset($deptEdit) && isset($deptEdit->ZedDeptDeptName))

    <tr>
    <td class="pT10 pB20">
    <b>{{ date("n/j/y g:ia", strtotime($deptEdit->ZedDeptDeptVerified)) }}</b><br />
    <span class="gry9"><i>by</i></span> {!! $user !!}<br />
    <br />
    <span class="gry9"><i>took</i></span> {{ number_format(($deptEdit->ZedDeptDeptDuration/60), 1) }} minutes<br />
    <br />
    
    @if (isset($iaEdit) && $iaEdit)
        <div class="pB10">
        @if ($iaEdit->ZedOverOnlineResearch == 1) <i class="fa fa-laptop"></i>, @endif
        @if ($iaEdit->ZedOverMadeDeptCall == 1) <i class="fa fa-phone"></i>Dept, @endif
        @if ($iaEdit->ZedOverMadeIACall == 1) <span class="slBlueDark"><i class="fa fa-phone "></i>IA</span>, @endif
        </div>
    @endif
    
    </td><td class="pT10 pB20">
    
    @if (isset($deptRow->DeptName)) 
        <a @if (isset($deptRow->DeptSlug)) href="/dashboard/volunteer/verify/{{ $deptRow->DeptSlug }}" @endif 
            ><b>{{ $deptEdit->ZedDeptDeptName }}</b></a><br />
    @endif
    @if (isset($iaEdit) && $iaEdit)
    
        @if (trim($iaEdit->ZedOverOverWebsite) != '')
            <a href="{{ $iaEdit->ZedOverOverWebsite }}" target="_blank">Website</a>, 
        @endif
        @if (trim($iaEdit->ZedOverOverFacebook) != '')
            <a href="{{ $iaEdit->ZedOverOverFacebook }}" target="_blank">Facebook</a>, 
        @endif
        @if (trim($iaEdit->ZedOverOverTwitter) != '')
            <a href="{{ $iaEdit->ZedOverOverTwitter }}" target="_blank">Twitter</a>, 
        @endif
        @if (trim($iaEdit->ZedOverOverWebsite) != '' || trim($iaEdit->ZedOverOverFacebook) != '' || trim($iaEdit->ZedOverOverTwitter) != '')
            <br />
        @endif
        @if (trim($deptEdit->ZedDeptDeptEmail) != '')
            <a href="mailto:{{ $deptEdit->ZedDeptDeptEmail }}">{{ $deptEdit->ZedDeptDeptEmail }}</a><br />
        @endif
        @if (trim($deptEdit->ZedDeptDeptPhoneWork) != '')
            {{ $deptEdit->ZedDeptDeptPhoneWork }}<br />
        @endif
        <span class="gry9">{{ $deptEdit->ZedDeptDeptAddress }}
        @if (trim($deptEdit->ZedDeptDeptAddress2) != '')
            <br />{{ $deptEdit->ZedDeptDeptAddress2 }}
        @endif
        {{ $deptEdit->ZedDeptDeptAddressCity }}, {{  $deptEdit->ZedDeptDeptAddressState }} {{  $deptEdit->ZedDeptDeptAddressZip }}<br />
        {{ $deptEdit->ZedDeptDeptAddressCounty }}<br />
        
        Type: {{ $deptType }}<br />
        Status: @if (intVal($deptEdit->ZedDeptDeptStatus) == 0) Inactive @else Active @endif <br />
        </span>
        
        @if (intVal($deptEdit->ZedDeptDeptJurisdictionPopulation) > 0)
            <nobr><span class="gry9">Population:</span> {{ number_format($deptEdit->ZedDeptDeptJurisdictionPopulation) }}</nobr><br />
        @endif
        @if (intVal($deptEdit->ZedDeptDeptTotOfficers) > 0)
            <nobr><span class="gry9">Officers:</span> {{ number_format($deptEdit->ZedDeptDeptTotOfficers) }}</nobr><br />
        @endif
    
        </td><td>
        
        <nobr><b><span class="gry9">Accessibility:</span> {{ $deptEdit->ZedDeptDeptScoreOpenness }}</b></nobr><br />
    
        @if (trim($iaEdit->ZedOverOverWebComplaintInfo) != '')
            <a href="{{ $iaEdit->ZedOverOverWebComplaintInfo }}" target="_blank">Complaint Info</a><br />
        @endif
        @if (trim($iaEdit->ZedOverOverComplaintWebForm) != '')
            <a href="{{ $iaEdit->ZedOverOverComplaintWebForm }}" target="_blank">Online Form</a><br />
        @endif
        @if (trim($deptEdit->ZedDeptDeptWebsite) != '')
            <span class="gry9">Homepage Link:</span> {{ $iaEdit->ZedOverOverHomepageComplaintLink }}<br />
        @endif
        @if ($iaEdit->ZedOverOverWaySubPaperMail)
            Paper Form Mail<br />
        @endif
        @if ($iaEdit->ZedOverOverWaySubPaperFax)
            Paper Form Fax<br />
        @endif
        @if ($iaEdit->ZedOverOverWaySubPaperInPerson)
            Paper In Person<br />
        @endif
        @if ($iaEdit->ZedOverOverWaySubVerbalPhone)
            Verbal Phone<br />
        @endif
        @if ($iaEdit->ZedOverOverWaySubVerbalInPerson)
            Verbal In Person<br />
        @endif
        @if ($iaEdit->ZedOverOverWaySubEmail)
            Email<br />
        @endif
        @if ($iaEdit->ZedOverOverWaySubOnline)
            Online Form<br />
        @endif
        
        @if (intVal($iaEdit->ZedOverOverSubmitDeadline) > 0) 
            <nobr><span class="gry9">Days To Submit:</span> {{ $iaEdit->ZedOverOverSubmitDeadline }}</nobr><br />
        @endif
        @if (trim($iaEdit->ZedOverOverEmail) != '')
            {{ $iaEdit->ZedOverOverEmail }}<br />
        @endif
        @if (trim($iaEdit->ZedOverOverPhoneWork) != '')
            {{ $iaEdit->ZedOverOverPhoneWork }}<br />
        @endif
        
        <span class="gry9">{{ $iaEdit->ZedOverOverAddress }}
        @if (trim($iaEdit->ZedOverOverAddress2) != '')
            <br />{{ $iaEdit->ZedOverOverAddress2 }}
        @endif
        <br />{{ $iaEdit->ZedOverOverAddressCity }}, {{ $iaEdit->ZedOverOverAddressState }} {{ $iaEdit->ZedOverOverAddressZip }}<br />
    
    @endif
    
    </td><td class="pT10 pB20">
    
    @if ($civEdit && (isset($civEdit->ZedOverOverAgncName) || isset($civEdit->ZedOverOverWebsite)))
        @if (trim($civEdit->ZedOverOverAgncName) != '')
            {{ $civEdit->ZedOverOverAgncName }}<br />
        @endif
        @if (trim($civEdit->ZedOverOverEmail) != '')
            {{ $civEdit->ZedOverOverEmail }}<br />
        @endif
        @if (trim($civEdit->ZedOverOverPhoneWork) != '')
            {{ $civEdit->ZedOverOverPhoneWork }}<br />
        @endif
        @if (trim($civEdit->ZedOverOverWebsite) != '')
            <a href="{{ $civEdit->ZedOverOverWebsite }}" target="_blank">Website</a><br />
        @endif
        @if (trim($civEdit->ZedOverOverFacebook) != '')
            <a href="{{ $civEdit->ZedOverOverFacebook }}" target="_blank">Facebook</a><br />
        @endif
        <span class="gry9">{{ $civEdit->ZedOverOverAddress }}<br />
        @if (trim($civEdit->ZedOverOverAddress2) != '')
            {{ $civEdit->ZedOverOverAddress2 }}<br />
        @endif
        {{ $civEdit->ZedOverOverAddressCity }}, {{ $civEdit->ZedOverOverAddressState }} {{ $civEdit->ZedOverOverAddressZip }}</span><br /><br />
        @if (trim($civEdit->ZedOverOverWebComplaintInfo) != '')
            <a href="{{ $civEdit->ZedOverOverWebComplaintInfo }}" target="_blank">Complaint Info</a><br />
        @endif
        @if (trim($civEdit->ZedOverOverComplaintWebForm) != '')
            <a href="{{ $civEdit->ZedOverOverComplaintWebForm }}" target="_blank">Online Form</a><br />
        @endif
        
        @if ($civEdit->ZedOverOverWaySubPaperMail)
            Paper Form Mail<br />
        @endif
        @if ($civEdit->ZedOverOverWaySubPaperFax)
            Paper Form Fax<br />
        @endif
        @if ($civEdit->ZedOverOverWaySubPaperInPerson)
            Paper In Person<br />
        @endif
        @if ($civEdit->ZedOverOverWaySubVerbalPhone)
            Verbal Phone<br />
        @endif
        @if ($civEdit->ZedOverOverWaySubVerbalInPerson)
            Verbal In Person<br />
        @endif
        @if ($civEdit->ZedOverOverWaySubEmail)
            Email<br />
        @endif
        @if ($civEdit->ZedOverOverWaySubOnline)
            Online Form<br />
        @endif
    
        @if (intVal($civEdit->ZedOverOverSubmitDeadline) > 0)
            <nobr><span class="gry9">Days To Submit:</span> {{ $civEdit->ZedOverOverSubmitDeadline }}</nobr><br />
        @endif
    @endif
    
    </td></tr>
    
    @if (isset($iaEdit->ZedOverNotes) && trim($iaEdit->ZedOverNotes) != '')
        <tr>
        <td>&nbsp;</td>
        <td colspan=3 class="pB20 slRedDark"><i class="fa fa-level-up fa-flip-horizontal" aria-hidden="true"></i> <i>Note:</i> {{ $iaEdit->ZedOverNotes }}</td>
        </tr>
    @endif
    
@endif
