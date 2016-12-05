<!-- resources/views/vendor/openpolice/volun/admPrintDeptEdit.blade.php -->

@if (isset($deptEdit) && isset($deptEdit->EditDeptName))

    <tr>
    <td class="pT10 pB20">
    <b>{{ date("n/j/y g:ia", strtotime($deptEdit->EditDeptVerified)) }}</b><br />
    <span class="gry9"><i>by</i></span> {!! $user !!}<br />
    <br />
    <span class="gry9"><i>took</i></span> {{ number_format(($deptEdit->EditDeptPageTime/60), 1) }} minutes<br />
    <br />
    
    @if (sizeof($iaEdit) > 0)
        <div class="pB10">
        @if ($iaEdit->EditOverOnlineResearch == 1) <i class="fa fa-laptop"></i>, @endif
        @if ($iaEdit->EditOverMadeDeptCall == 1) <i class="fa fa-phone"></i>Dept, @endif
        @if ($iaEdit->EditOverMadeIACall == 1) <span class="slBlueDark"><i class="fa fa-phone "></i>IA</span>, @endif
        </div>
    @endif
    
    </td><td class="pT10 pB20">
    
    @if (isset($deptRow->DeptName)) 
        <a @if (isset($deptRow->DeptSlug)) href="/volunteer/verify/{{ $deptRow->DeptSlug }}" @endif 
            ><b>{{ $deptEdit->EditDeptName }}</b></a><br />
    @endif
    @if (sizeof($iaEdit) > 0)
    
        @if (trim($iaEdit->EditOverWebsite) != '')
            <a href="{{ $iaEdit->EditOverWebsite }}" target="_blank">Website</a>, 
        @endif
        @if (trim($iaEdit->EditOverFacebook) != '')
            <a href="{{ $iaEdit->EditOverFacebook }}" target="_blank">Facebook</a>, 
        @endif
        @if (trim($iaEdit->EditOverTwitter) != '')
            <a href="{{ $iaEdit->EditOverTwitter }}" target="_blank">Twitter</a>, 
        @endif
        @if (trim($iaEdit->EditOverWebsite) != '' || trim($iaEdit->EditOverFacebook) != '' || trim($iaEdit->EditOverTwitter) != '')
            <br />
        @endif
        @if (trim($deptEdit->EditDeptEmail) != '')
            <a href="mailto:{{ $deptEdit->EditDeptEmail }}">{{ $deptEdit->EditDeptEmail }}</a><br />
        @endif
        @if (trim($deptEdit->EditDeptPhoneWork) != '')
            {{ $deptEdit->EditDeptPhoneWork }}<br />
        @endif
        <span class="gry9">{{ $deptEdit->EditDeptAddress }}
        @if (trim($deptEdit->EditDeptAddress2) != '')
            <br />{{ $deptEdit->EditDeptAddress2 }}
        @endif
        {{ $deptEdit->EditDeptAddressCity }}, {{  $deptEdit->EditDeptAddressState }} {{  $deptEdit->EditDeptAddressZip }}<br />
        {{ $deptEdit->EditDeptAddressCounty }}<br />
        
        Type: {{ $deptType }}<br />
        Status: @if (intVal($deptEdit->EditDeptStatus) == 0) Inactive @else Active @endif <br />
        </span>
        
        @if (intVal($deptEdit->EditDeptJurisdictionPopulation) > 0)
            <nobr><span class="gry9">Population:</span> {{ number_format($deptEdit->EditDeptJurisdictionPopulation) }}</nobr><br />
        @endif
        @if (intVal($deptEdit->EditDeptTotOfficers) > 0)
            <nobr><span class="gry9">Officers:</span> {{ number_format($deptEdit->EditDeptTotOfficers) }}</nobr><br />
        @endif
    
        </td><td>
        
        <nobr><b><span class="gry9">Accessibility:</span> {{ $deptEdit->EditDeptScoreOpenness }}</b></nobr><br />
    
        @if (trim($iaEdit->EditOverWebComplaintInfo) != '')
            <a href="{{ $iaEdit->EditOverWebComplaintInfo }}" target="_blank">Complaint Info</a><br />
        @endif
        @if (trim($iaEdit->EditOverComplaintWebForm) != '')
            <a href="{{ $iaEdit->EditOverComplaintWebForm }}" target="_blank">Online Form</a><br />
        @endif
        @if (trim($deptEdit->EditDeptWebsite) != '')
            <span class="gry9">Homepage Link:</span> {{ $iaEdit->EditOverHomepageComplaintLink }}<br />
        @endif
        @if ($iaEdit->EditOverWaySubPaperMail)
            Paper Form Mail<br />
        @endif
        @if ($iaEdit->EditOverWaySubPaperFax)
            Paper Form Fax<br />
        @endif
        @if ($iaEdit->EditOverWaySubPaperInPerson)
            Paper In Person<br />
        @endif
        @if ($iaEdit->EditOverWaySubVerbalPhone)
            Verbal Phone<br />
        @endif
        @if ($iaEdit->EditOverWaySubVerbalInPerson)
            Verbal In Person<br />
        @endif
        @if ($iaEdit->EditOverWaySubEmail)
            Email<br />
        @endif
        @if ($iaEdit->EditOverWaySubOnline)
            Online Form<br />
        @endif
        
        @if (intVal($iaEdit->EditOverSubmitDeadline) > 0) 
            <nobr><span class="gry9">Days To Submit:</span> {{ $iaEdit->EditOverSubmitDeadline }}</nobr><br />
        @endif
        @if (trim($iaEdit->EditOverEmail) != '')
            {{ $iaEdit->EditOverEmail }}<br />
        @endif
        @if (trim($iaEdit->EditOverPhoneWork) != '')
            {{ $iaEdit->EditOverPhoneWork }}<br />
        @endif
        
        <span class="gry9">{{ $iaEdit->EditOverAddress }}
        @if (trim($iaEdit->EditOverAddress2) != '')
            <br />{{ $iaEdit->EditOverAddress2 }}
        @endif
        <br />{{ $iaEdit->EditOverAddressCity }}, {{ $iaEdit->EditOverAddressState }} {{ $iaEdit->EditOverAddressZip }}<br />
    
    @endif
    
    </td><td class="pT10 pB20">
    
    @if (sizeof($civEdit) > 0 && (isset($civEdit->EditOverAgncName) || isset($civEdit->EditOverWebsite)))
        @if (trim($civEdit->EditOverAgncName) != '')
            {{ $civEdit->EditOverAgncName }}<br />
        @endif
        @if (trim($civEdit->EditOverEmail) != '')
            {{ $civEdit->EditOverEmail }}<br />
        @endif
        @if (trim($civEdit->EditOverPhoneWork) != '')
            {{ $civEdit->EditOverPhoneWork }}<br />
        @endif
        @if (trim($civEdit->EditOverWebsite) != '')
            <a href="{{ $civEdit->EditOverWebsite }}" target="_blank">Website</a><br />
        @endif
        @if (trim($civEdit->EditOverFacebook) != '')
            <a href="{{ $civEdit->EditOverFacebook }}" target="_blank">Facebook</a><br />
        @endif
        <span class="gry9">{{ $civEdit->EditOverAddress }}<br />
        @if (trim($civEdit->EditOverAddress2) != '')
            {{ $civEdit->EditOverAddress2 }}<br />
        @endif
        {{ $civEdit->EditOverAddressCity }}, {{ $civEdit->EditOverAddressState }} {{ $civEdit->EditOverAddressZip }}</span><br /><br />
        @if (trim($civEdit->EditOverWebComplaintInfo) != '')
            <a href="{{ $civEdit->EditOverWebComplaintInfo }}" target="_blank">Complaint Info</a><br />
        @endif
        @if (trim($civEdit->EditOverComplaintWebForm) != '')
            <a href="{{ $civEdit->EditOverComplaintWebForm }}" target="_blank">Online Form</a><br />
        @endif
        
        @if ($civEdit->EditOverWaySubPaperMail)
            Paper Form Mail<br />
        @endif
        @if ($civEdit->EditOverWaySubPaperFax)
            Paper Form Fax<br />
        @endif
        @if ($civEdit->EditOverWaySubPaperInPerson)
            Paper In Person<br />
        @endif
        @if ($civEdit->EditOverWaySubVerbalPhone)
            Verbal Phone<br />
        @endif
        @if ($civEdit->EditOverWaySubVerbalInPerson)
            Verbal In Person<br />
        @endif
        @if ($civEdit->EditOverWaySubEmail)
            Email<br />
        @endif
        @if ($civEdit->EditOverWaySubOnline)
            Online Form<br />
        @endif
    
        @if (intVal($civEdit->EditOverSubmitDeadline) > 0)
            <nobr><span class="gry9">Days To Submit:</span> {{ $civEdit->EditOverSubmitDeadline }}</nobr><br />
        @endif
    @endif
    
    </td></tr>
    
    @if (isset($iaEdit->EditOverNotes) && trim($iaEdit->EditOverNotes) != '')
        <tr>
        <td>&nbsp;</td>
        <td colspan=3 class="pB20 slRedDark"><i class="fa fa-level-up fa-flip-horizontal" aria-hidden="true"></i> <i>Note:</i> {{ $iaEdit->EditOverNotes }}</td>
        </tr>
    @endif
    
@endif
