<!-- resources/views/vendor/openpolice/dept-page-filing-instructs.blade.php -->

<div class="nodeAnchor"><a name="how"></a></div>
<div class="slCard mT20">
    <h3 class="mT0">
        How to File Complaints against the {!! $d["deptRow"]->dept_name !!}
    </h3>
    @if (isset($d["iaRow"]->over_official_form_not_req) 
        && intVal($d["iaRow"]->over_official_form_not_req) == 1
        && isset($d["iaRow"]->over_way_sub_email) 
        && intVal($d["iaRow"]->over_way_sub_email) == 1
        && isset($d[$d["whichOver"]]->over_email) 
        && trim($d[$d["whichOver"]]->over_email) != '')
        <div class="alert alert-success mT10" role="alert"><h4 class="m0">
            <i class="fa fa-smile-o mR5" aria-hidden="true"></i> 
            OpenPolice-Compatible Department
        </h4></div>
        <p class="mB20">
            This police department's policy permits them 
            to investigate complaints sent via email. 
            They also accept complaints filed on non-department forms. 
            <b class="bld">That means OpenPolice.org will automatically 
                file your report after you 
            <a href="/join-beta-test/{{ $d['deptRow']->dept_slug }}"
            <?php /* href="/share-complaint-or-compliment/{{ $d['deptRow']->dept_slug }}" */ ?>
                >share your story</a>.</b>
        </p>
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
            This department does not investigate 
            OpenPolice.org reports sent by email.
            @if (!isset($ownerTools) || !$ownerTools)
                We recommend you 
                <a href="/join-beta-test/{{ $d['deptRow']->dept_slug }}"
                    <?php /* href="/share-complaint-or-compliment/{{ $d['deptRow']->dept_slug }}" */ ?>
                    >share your story on OpenPolice.org</a> first.
                Then use the information below to submit a formal complaint.
            @endif
        </p>
    @endif
</div>

<div class="slCard mT20">
@if ($d["whichOver"] == 'civRow')
    <h3 class="mT0">{{ $d[$d["whichOver"]]->over_agnc_name }}</h3>
    <p>
    This is the agency that collects complaints 
    against the {!! $d["deptRow"]->dept_name !!}.
    It is where we recommend that OpenPolice.org 
    users file their formal complaints.
    </p>
    @if (isset($d["civAddy"]) && trim($d["civAddy"]) != '')
        <p><a href="{{ $GLOBALS['SL']->mapsURL($d['civAddy']) }}" target="_blank"
            ><i class="fa fa-map-marker mR5" aria-hidden="true"></i> 
            {!! $d["civAddy"] !!}</a></p>
    @endif
@elseif (isset($d["iaAddy"]) && trim($d["iaAddy"]) != '')
    <h3 class="mT0">Internal Affairs</h3>
    <p><a href="{{ $GLOBALS['SL']->mapsURL($d['iaAddy']) }}" target="_blank"
        ><i class="fa fa-map-marker mR5" aria-hidden="true"></i> 
        {!! $d["iaAddy"] !!}</a></p>
@endif
    
    @if (isset($d[$d["whichOver"]]->over_phone_work) 
    && trim($d[$d["whichOver"]]->over_phone_work) != '')
        <p>
            <i class="fa fa-phone mR5" aria-hidden="true"></i> 
            {{ $d[$d["whichOver"]]->over_phone_work }}
        </p>
    @endif
    
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

</div>

    
@if ($d["whichOver"] == 'civRow' && isset($d["iaAddy"]) && trim($d["iaAddy"]) != '')
     <div class="slCard mT20">
        <h3>Internal Affairs Office</h3>
        <p><a href="{{ $GLOBALS['SL']->mapsURL($d['iaAddy']) }}" target="_blank"
            ><i class="fa fa-map-marker mR5" aria-hidden="true"></i> {!! $d["iaAddy"] !!}</a></p>
        @if (isset($d["iaRow"]->over_phone_work) && trim($d["iaRow"]->over_phone_work) != '')
            <p><i class="fa fa-phone mR5" aria-hidden="true"></i> {{ $d["iaRow"]->over_phone_work }}</p>
        @endif
    </div>
@endif