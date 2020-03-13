<!-- resources/views/vendor/openpolice/dept-page-filing-instructs.blade.php -->

<div class="nodeAnchor"><a name="how"></a></div>
@if (isset($d["iaRow"]->over_official_form_not_req) 
    && intVal($d["iaRow"]->over_official_form_not_req) == 1
    && isset($d["iaRow"]->over_way_sub_email) 
    && intVal($d["iaRow"]->over_way_sub_email) == 1
    && isset($d[$d["whichOver"]]->over_email) 
    && trim($d[$d["whichOver"]]->over_email) != '')
    <div class="alert alert-success mT10" role="alert">
        <div class="d-md-none">
            <h5 class="mB0">
            <i class="fa fa-handshake-o mR5" aria-hidden="true"></i>
            OpenPolice-Compatible Department
            </h5>
        </div>
        <div class="d-none d-md-block">
            <h3 class="m0">
            <i class="fa fa-handshake-o mR5" aria-hidden="true"></i>
            OpenPolice-Compatible Department
            </h3>
        </div>
    </div>
    <p>
        OpenPolice.org is an independent project developed by 
        Flex Your Rights, a 501(c)(3) educational nonprofit.
    </p>
    <p class="mB20">
        This police department's policy permits them 
        to investigate complaints sent via email. 
        They also accept complaints filed on non-department forms. 
        <b class="bld">That means OpenPolice.org can automatically 
            file your report after you put it together.</b>
    </p>

    @if (!isset($ownerTools) || !$ownerTools)
        {!! view(
            'vendor.openpolice.dept-page-filing-instructs-file-btn',
            [ "d" => $d ]
        )->render() !!}
    @endif

    <p>
        The information below includes other ways 
        to submit a formal complaint to the 
        {{ $d[$d["whichOver"]]->over_agnc_name }}.
    </p>
@else
    @if (isset($d["iaRow"]->over_way_sub_paper_in_person) 
        && intVal($d["iaRow"]->over_way_sub_paper_in_person) == 1)
        <div class="alert alert-danger mT10" role="alert">
            <i class="fa fa-frown-o mR5" aria-hidden="true"></i> 
            This department only investigates complaints submitted in-person.
        </div>
    @endif
    <p>
        OpenPolice.org is an independent project developed by 
        Flex Your Rights, a 501(c)(3) educational nonprofit.
    </p>
    <!--- <p>
    This department does not investigate OpenPolice.org reports sent by email.
    </p> --->
    @if (!isset($ownerTools) || !$ownerTools)
        <p>
            <b class="bld">We recommend you create a <i>transparent</i>
            misconduct report on OpenPolice.org first.</b> 
        </p>
        {!! view(
            'vendor.openpolice.dept-page-filing-instructs-file-btn',
            [ "d" => $d ]
        )->render() !!}
        <p>
            Then use the information below to submit a formal complaint to the {{ $d[$d["whichOver"]]->over_agnc_name }}.
        </p>
    @endif

@endif


<div style="padding-bottom: 13px; padding-top: 1px; margin-top: -3px;"><hr></div>
@if ($d["whichOver"] == 'civRow')
    <h3>{{ $d[$d["whichOver"]]->over_agnc_name }}</h3>
    <p>
    This is the agency that collects complaints 
    against the {!! $d["deptRow"]->dept_name !!}.
    It's where we recommend that OpenPolice.org 
    users file their formal complaints.
    </p>
    @if (isset($d["civAddy"]) && trim($d["civAddy"]) != '')
        <p>
            <a href="{{ $GLOBALS['SL']->mapsURL($d['civAddy']) }}" target="_blank"
            ><i class="fa fa-map-marker mR5" aria-hidden="true"></i> 
            {!! $d["civAddy"] !!}</a>
    @endif
@else
    <h3>Internal Affairs</h3>
    @if (isset($d["iaAddy"]) && trim($d["iaAddy"]) != '')
        <p><a href="{{ $GLOBALS['SL']->mapsURL($d['iaAddy']) }}" target="_blank"
            ><i class="fa fa-map-marker mR5" aria-hidden="true"></i> 
            {!! $d["iaAddy"] !!}</a>
    @else <p class="mTn5">
    @endif
@endif
    
    @if (isset($d[$d["whichOver"]]->over_phone_work) 
        && trim($d[$d["whichOver"]]->over_phone_work) != '')
        <br /><i class="fa fa-phone mR5" aria-hidden="true"></i> 
        {{ $d[$d["whichOver"]]->over_phone_work }}
        
    @endif
    </p>
    
    @if (isset($d["iaRow"]->over_way_sub_email) 
        && intVal($d["iaRow"]->over_way_sub_email) == 1
        && isset($d[$d["whichOver"]]->over_email) 
        && trim($d[$d["whichOver"]]->over_email) != '')
        @if (!isset($d["iaRow"]->over_official_form_not_req) 
            || intVal($d["iaRow"]->over_official_form_not_req) == 0)
            <p>
            This agency investigates complaints emailed with 
            their official department form. When you send 
            that PDF form, also attach your OpenPolice.org PDF, 
            which we will send to you.
            <br />
        @else
            <p>OpenPolice.org can email your complaint to this agency.<br />
        @endif
        @if (!isset($d[$d['whichOver']]->over_keep_email_private) 
            || intVal($d[$d['whichOver']]->over_keep_email_private) == 0)
            <a href="mailto:{{ $d[$d['whichOver']]->over_email }}">{{ 
                $d[$d["whichOver"]]->over_email }}</a></p>
        @endif
    @elseif (isset($d[$d["whichOver"]]->over_email) 
        && trim($d[$d["whichOver"]]->over_email) != ''
        && (!isset($d[$d['whichOver']]->over_keep_email_private) 
            || intVal($d[$d['whichOver']]->over_keep_email_private) == 0))
        <p><a href="mailto:{{ $d[$d['whichOver']]->over_email }}">{{ 
                $d[$d["whichOver"]]->over_email }}</a></p>
    @endif

    @if (isset($d["iaRow"]->over_complaint_web_form) 
        && trim($d["iaRow"]->over_complaint_web_form) != '')
        <p>You can submit your complaint through this agency's online 
            complaint form. Include the public link to your OpenPolice.org 
            complaint. And if possible, attach your OpenPolice.org PDF, 
            which we will send you.<br />
        {!! $GLOBALS["SL"]->swapURLwrap($d["iaRow"]->over_complaint_web_form, false) !!}</a></p>
    @endif
    
    @if (isset($d["iaRow"]->over_complaint_pdf) 
        && trim($d["iaRow"]->over_complaint_pdf) != '')
        <p>This investigative agency has a PDF form you can print 
        @if (isset($d["iaRow"]->over_way_sub_paper_mail) 
            && intVal($d["iaRow"]->over_way_sub_paper_mail) == 1)
            out and mail.
        @else out. @endif
        <br />
        {!! $GLOBALS["SL"]->swapURLwrap($d["iaRow"]->over_complaint_pdf, false) !!}</p>
    @endif
    
@if (!isset($d["iaRow"]->over_way_sub_paper_in_person) 
    || intVal($d["iaRow"]->over_way_sub_paper_in_person) != 1)
    <p>If you submit your complaint on paper, we recommend that you 
    staple a copy of your full OpenPolice.org complaint 
    together with the department form.</p>
@endif
    
    @if (isset($d["iaRow"]->over_web_complaint_info) 
        && trim($d["iaRow"]->over_web_complaint_info) != '')
        <p>Complaint process information:<br />
        {!! $GLOBALS["SL"]->swapURLwrap($d["iaRow"]->over_web_complaint_info, false) !!}</p>
    @endif

@if (isset($d["iaRow"]->over_way_sub_paper_in_person) 
    && intVal($d["iaRow"]->over_way_sub_paper_in_person) == 1)

    <p>
    This department only investigates complaints submitted in-person. Regardless, 
    we recommend you print and send your paper complaint to the department by 
    <a href="https://faq.usps.com/s/article/What-is-Certified-Mail" target="_blank"
        ><b>USPS Certified Mail</b></a>. When you get confirmation of receipt, 
        please login to your account to let us know.
    </p>

@else

    <ul style="padding-left: 30px;">
        @if (!isset($d["iaRow"]->over_official_anon) 
            || intVal($d["iaRow"]->over_official_anon) == 0)
            <li>Anonymous complaints will not be investigated.</li>
        @endif
        @if (!isset($d["iaRow"]->over_official_form_not_req) 
            || intVal($d["iaRow"]->over_official_form_not_req) == 0)
            <li>Only complaints submitted on department forms will be investigated.</li>
        @endif
        @if (isset($d["iaRow"]->over_way_sub_notary) 
            && intVal($d["iaRow"]->over_way_sub_notary) == 1)
            <li>Submitted complaints may require a notary to be investigated.</li>
        @endif
        @if (isset($d["iaRow"]->over_way_sub_verbal_phone) 
            && intVal($d["iaRow"]->over_way_sub_verbal_phone) == 1)
            <li>Complaints submitted by phone will be investigated.</li>
        @endif
        @if (isset($d["iaRow"]->over_way_sub_paper_mail) 
            && intVal($d["iaRow"]->over_way_sub_paper_mail) == 1)
            <li>Complaints submitted by postal mail will be investigated. 
            Send using 
            <a href="https://faq.usps.com/s/article/What-is-Certified-Mail" 
            target="_blank"><b>USPS Certified Mail</b></a>.</li>
        @endif
    @if (isset($d["iaRow"]->over_submit_deadline) 
        && intVal($d["iaRow"]->over_submit_deadline) > 0)
        <li>Complaints must be submitted within {{ 
            number_format($d["iaRow"]->over_submit_deadline) 
            }} days of your incident to be investigated.</li>
    @endif
    </ul>

@endif


    
@if ($d["whichOver"] == 'civRow' && isset($d["iaAddy"]) && trim($d["iaAddy"]) != '')
    <div style="padding-bottom: 13px; padding-top: 1px; margin-top: -3px;"><hr></div>
    <h3>Internal Affairs Office</h3>
    <p><a href="{{ $GLOBALS['SL']->mapsURL($d['iaAddy']) }}" target="_blank"
        ><i class="fa fa-map-marker mR5" aria-hidden="true"></i> {!! $d["iaAddy"] !!}</a></p>
    @if (isset($d["iaRow"]->over_phone_work) && trim($d["iaRow"]->over_phone_work) != '')
        <p><i class="fa fa-phone mR5" aria-hidden="true"></i> {{ $d["iaRow"]->over_phone_work }}</p>
    @endif
@endif