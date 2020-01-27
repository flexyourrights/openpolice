<!-- resources/views/vendor/openpolice/volun/admPrintDeptEdit.blade.php -->

@if (isset($deptEdit) && isset($deptEdit->zed_dept_dept_name))

    <tr>
    <td class="pT10 pB20">
    <b>{{ date("n/j/y g:ia", strtotime($deptEdit->zed_dept_dept_verified)) }}</b><br />
    <span class="slGrey"><i>by</i></span> {!! $user !!}<br />
    <br />
    <span class="slGrey"><i>took</i></span> {{ 
        number_format(($deptEdit->zed_dept_dept_duration/60), 1) }} minutes<br />
    <br />
    
    @if (isset($iaEdit) && $iaEdit)
        <div class="pB10">
        @if ($iaEdit->zed_over_online_research == 1) 
            <i class="fa fa-laptop"></i>, 
        @endif
        @if ($iaEdit->zed_over_made_dept_call == 1) 
            <i class="fa fa-phone"></i>Dept, 
        @endif
        @if ($iaEdit->zed_over_made_ia_call == 1) 
            <span class="slBlueDark"><i class="fa fa-phone "></i>IA</span>, 
        @endif
        </div>
    @endif
    
    </td><td class="pT10 pB20">
    
    @if (isset($deptRow->dept_name)) 
        <a @if (isset($deptRow->dept_slug)) href="/dashboard/volunteer/verify/{{ $deptRow->dept_slug }}" @endif 
            ><b>{{ $deptEdit->zed_dept_dept_name }}</b></a><br />
    @endif
    @if (isset($iaEdit) && $iaEdit)
    
        @if (trim($iaEdit->zed_over_over_website) != '')
            <a href="{{ $iaEdit->zed_over_over_website }}" target="_blank">Website</a>, 
        @endif
        @if (trim($iaEdit->ZedOver->over_facebook) != '')
            <a href="{{ $iaEdit->ZedOver->over_facebook }}" target="_blank">Facebook</a>, 
        @endif
        @if (trim($iaEdit->ZedOver->over_twitter) != '')
            <a href="{{ $iaEdit->ZedOver->over_twitter }}" target="_blank">Twitter</a>, 
        @endif
        @if (trim($iaEdit->zed_over_over_website) != '' 
            || trim($iaEdit->ZedOver->over_facebook) != '' 
            || trim($iaEdit->ZedOver->over_twitter) != '')
            <br />
        @endif
        @if (trim($deptEdit->zed_dept_dept_email) != '')
            <a href="mailto:{{ $deptEdit->zed_dept_dept_email }}"
                >{{ $deptEdit->zed_dept_dept_email }}</a><br />
        @endif
        @if (trim($deptEdit->zed_dept_dept_phone_work) != '')
            {{ $deptEdit->zed_dept_dept_phone_work }}<br />
        @endif
        <span class="slGrey">{{ $deptEdit->zed_dept_dept_address }}
        @if (trim($deptEdit->zed_dept_dept_address2) != '')
            <br />{{ $deptEdit->zed_dept_dept_address2 }}
        @endif
        {{ $deptEdit->zed_dept_dept_address_city }}, {{  $deptEdit->zed_dept_dept_address_state }} {{  $deptEdit->zed_dept_dept_address_zip }}<br />
        {{ $deptEdit->zed_dept_dept_address_county }}<br />
        
        Type: {{ $deptType }}<br />
        Status: @if (intVal($deptEdit->zed_dept_dept_status) == 0) Inactive @else Active @endif <br />
        </span>
        
        @if (intVal($deptEdit->zed_dept_dept_jurisdiction_population) > 0)
            <nobr><span class="slGrey">Population:</span> {{ number_format($deptEdit->zed_dept_dept_jurisdiction_population) }}</nobr><br />
        @endif
        @if (intVal($deptEdit->zed_dept_dept_tot_officers) > 0)
            <nobr><span class="slGrey">Officers:</span> 
            {{ number_format($deptEdit->zed_dept_dept_tot_officers) }}</nobr><br />
        @endif
    
        </td><td>
        
        <nobr><b><span class="slGrey">Accessibility:</span> 
        {{ $deptEdit->zed_dept_dept_score_openness }}</b></nobr><br />
    
        @if (trim($iaEdit->zed_over_over_web_complaint_info) != '')
            <a href="{{ $iaEdit->zed_over_over_web_complaint_info }}" 
                target="_blank">Complaint Info</a><br />
        @endif
        @if (trim($iaEdit->zed_over_over_complaint_web_form) != '')
            <a href="{{ $iaEdit->zed_over_over_complaint_web_form }}" 
                target="_blank">Online Form</a><br />
        @endif
        @if (trim($deptEdit->zed_dept_dept_website) != '')
            <span class="slGrey">Homepage Link:</span> 
            {{ $iaEdit->zed_over_over_homepage_complaint_link }}<br />
        @endif
        @if ($iaEdit->zed_over_over_way_sub_paper_mail)
            Paper Form Mail<br />
        @endif
        @if ($iaEdit->zed_over_over_way_sub_paper_fax)
            Paper Form Fax<br />
        @endif
        @if ($iaEdit->zed_over_over_way_sub_paper_in_person)
            Paper In Person<br />
        @endif
        @if ($iaEdit->zed_over_over_way_sub_verbal_phone)
            Verbal Phone<br />
        @endif
        @if ($iaEdit->zed_over_over_way_sub_verbal_in_person)
            Verbal In Person<br />
        @endif
        @if ($iaEdit->zed_over_over_way_sub_email)
            Email<br />
        @endif
        @if ($iaEdit->zed_over_over_way_sub_online)
            Online Form<br />
        @endif
        
        @if (intVal($iaEdit->zed_over_over_submit_deadline) > 0) 
            <nobr><span class="slGrey">Days To Submit:</span> 
            {{ $iaEdit->zed_over_over_submit_deadline }}</nobr><br />
        @endif
        @if (trim($iaEdit->zed_over_over_email) != '')
            {{ $iaEdit->zed_over_over_email }}<br />
        @endif
        @if (trim($iaEdit->zed_over_over_phone_work) != '')
            {{ $iaEdit->zed_over_over_phone_work }}<br />
        @endif
        
        <span class="slGrey">{{ $iaEdit->zed_over_over_address }}
        @if (trim($iaEdit->zed_over_over_address2) != '')
            <br />{{ $iaEdit->zed_over_over_address2 }}
        @endif
        <br />{{ $iaEdit->zed_over_over_addressCity }}, 
        {{ $iaEdit->zed_over_over_addressState }} 
        {{ $iaEdit->zed_over_over_addressZip }}<br />
    
    @endif
    
    </td><td class="pT10 pB20">
    
    @if ($civEdit && (isset($civEdit->zed_over_over_agnc_name) 
        || isset($civEdit->zed_over_over_website)))
        @if (trim($civEdit->zed_over_over_agnc_name) != '')
            {{ $civEdit->zed_over_over_agnc_name }}<br />
        @endif
        @if (trim($civEdit->zed_over_over_email) != '')
            {{ $civEdit->zed_over_over_email }}<br />
        @endif
        @if (trim($civEdit->zed_over_over_phone_work) != '')
            {{ $civEdit->zed_over_over_phone_work }}<br />
        @endif
        @if (trim($civEdit->zed_over_over_website) != '')
            <a href="{{ $civEdit->zed_over_over_website }}" 
                target="_blank">Website</a><br />
        @endif
        @if (trim($civEdit->ZedOver->over_facebook) != '')
            <a href="{{ $civEdit->ZedOver->over_facebook }}" 
                target="_blank">Facebook</a><br />
        @endif
        <span class="slGrey">{{ $civEdit->zed_over_over_address }}<br />
        @if (trim($civEdit->zed_over_over_address2) != '')
            {{ $civEdit->zed_over_over_address2 }}<br />
        @endif
        {{ $civEdit->zed_over_over_addressCity }}, 
        {{ $civEdit->zed_over_over_addressState }} 
        {{ $civEdit->zed_over_over_addressZip }}</span><br /><br />
        @if (trim($civEdit->zed_over_over_web_complaint_info) != '')
            <a href="{{ $civEdit->zed_over_over_web_complaint_info }}" 
                target="_blank">Complaint Info</a><br />
        @endif
        @if (trim($civEdit->zed_over_over_complaint_web_form) != '')
            <a href="{{ $civEdit->zed_over_over_complaint_web_form }}" 
                target="_blank">Online Form</a><br />
        @endif
        
        @if ($civEdit->zed_over_over_way_sub_paper_mail)
            Paper Form Mail<br />
        @endif
        @if ($civEdit->zed_over_over_way_sub_paper_fax)
            Paper Form Fax<br />
        @endif
        @if ($civEdit->zed_over_over_way_sub_paper_in_person)
            Paper In Person<br />
        @endif
        @if ($civEdit->zed_over_over_way_sub_verbal_phone)
            Verbal Phone<br />
        @endif
        @if ($civEdit->zed_over_over_way_sub_verbal_in_person)
            Verbal In Person<br />
        @endif
        @if ($civEdit->zed_over_over_way_sub_email)
            Email<br />
        @endif
        @if ($civEdit->zed_over_over_way_sub_online)
            Online Form<br />
        @endif
    
        @if (intVal($civEdit->zed_over_over_submit_deadline) > 0)
            <nobr><span class="slGrey">Days To Submit:</span> 
            {{ $civEdit->zed_over_over_submit_deadline }}</nobr><br />
        @endif
    @endif
    
    </td></tr>
    
    @if (isset($iaEdit->zed_over_notes) && trim($iaEdit->zed_over_notes) != '')
        <tr>
        <td>&nbsp;</td>
        <td colspan=3 class="pB20 txtDanger">
            <i class="fa fa-level-up fa-flip-horizontal" aria-hidden="true"></i> 
            <i>Note:</i> {{ $iaEdit->zed_over_notes }}
        </td>
        </tr>
    @endif
    
@endif
