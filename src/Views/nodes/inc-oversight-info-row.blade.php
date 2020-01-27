<!-- resources/views/vendor/openpolice/nodes/inc-oversight-info-row.blade.php -->
@if ($o && isset($o->over_agnc_name) 
    && trim($o->over_agnc_name) != '')
    <b>{{ $o->over_agnc_name }}</b><br />
    @if (isset($o->over_website) 
        && trim($o->over_website) != '')
        <a href="{{ $o->over_website }}" target="_blank"
            >{{ $o->over_website }}</a><br />
    @endif
    @if (isset($o->over_facebook) 
        && trim($o->over_facebook) != '')
        <a href="{{ $o->over_facebook }}" target="_blank"
            >{{ $o->over_facebook }}</a><br />
    @endif
    @if (isset($o->over_twitter) 
        && trim($o->over_twitter) != '')
        <a href="{{ $o->over_twitter }}" target="_blank"
            >{{ $o->over_twitter }}</a><br />
    @endif
    @if (isset($o->over_youtube) 
        && trim($o->over_youtube) != '')
        <a href="{{ $o->over_youtube }}" target="_blank"
            >{{ $o->over_youtube }}</a><br />
    @endif
    @if (isset($o->over_phone_work) 
        && trim($o->over_phone_work) != '')
        {{ $o->over_phone_work }}<br />
    @endif
    @if (isset($o->over_address) && trim($o->over_address) != '')
        {{ $o->over_address }}<br />
        @if (isset($o->over_address2) && trim($o->over_address2) != '')
            {{ $o->over_address2 }}<br />
        @endif
        {{ $o->over_address_city }}, {{ $o->over_address_state }} 
        {{ $o->over_address_zip }}<br />
    @endif
    <br />                                                
    @if (isset($o->over_way_sub_online) 
        && intVal($o->over_way_sub_online) == 1 
        && isset($o->over_complaint_web_form) 
        && trim($o->over_complaint_web_form) != '')
        You can submit your complaint through your investigative agency's 
        online complaint form. Somewhere in their official form, 
        include a link to your OpenPolice.org complaint.<br />
        <a href="{{ $o->over_complaint_web_form }}" target="_blank"
            >{{ $o->over_complaint_web_form }}</a><br /><br />
    @endif
    @if (isset($o->over_way_sub_email) 
        && intVal($o->over_way_sub_email) == 1 
        && isset($o->over_email) 
        && trim($o->over_email) != '')
        You can submit your complaint by emailing your investigative agency. 
        We recommend you include a link to your OpenPolice.org complaint 
        in your email.<br /><a href="mailto:{{ $o->over_email }}"
        >{{ $o->over_email }}</a><br /><br />
    @endif
    @if (isset($o->over_complaint_pdf) 
        && trim($o->over_complaint_pdf) != '')
        You can print out and use your investigative agency\'s official complaint 
        form online. We recommend you also print your full OpenPolice.org 
        complaint and submit it along with their official form.<br />
        <a href="{{ $o->over_complaint_pdf }}" target="_blank"
            >{{ $o->over_complaint_pdf }}</a><br /><br />
    @endif
    <i>More about this complaint process:</i><br />
    @if (isset($o->over_web_complaint_info) 
        && trim($o->over_web_complaint_info) != '')
        <a href="{{ $o->over_web_complaint_info }}" target="_blank"
            >{{ $o->over_web_complaint_info }}</a><br />
    @endif
    @if (!isset($o->over_official_form_not_req) 
        || intVal($o->over_official_form_not_req) == 0)
        Only complaints submitted on official forms will be investigated.<br /> 
    @endif
    @if (isset($o->over_way_sub_notary) && intVal($o->over_way_sub_notary) == 1)
        Submitted complaints may require a notary to be investigated.<br /> 
    @endif
    @if (!isset($o->over_official_anon) 
        || intVal($o->over_official_anon) == 0)
        Anonymous complaints will not be investigated.<br /> 
    @endif
    @if (isset($o->over_way_sub_verbal_phone) 
        && intVal($o->over_way_sub_verbal_phone) == 1)
        Complaints submitted by phone will be investigated.<br /> 
    @endif
    @if (isset($o->over_way_sub_paper_mail) 
        && intVal($o->over_way_sub_paper_mail) == 1)
        Complaints submitted by postal mail will be investigated.<br /> 
    @endif
    @if (isset($o->over_way_sub_paper_in_person) 
        && intVal($o->over_way_sub_paper_in_person) == 1)
        Complaints submitted in person will be investigated.<br /> 
    @endif
    @if (isset($o->over_submit_deadline) 
        && intVal($o->over_submit_deadline) > 0)
        Complaints must be submitted within 
        {{ number_format($o->over_submit_deadline) }} 
        days of your incident to be investigated.<br /> 
    @endif
@endif