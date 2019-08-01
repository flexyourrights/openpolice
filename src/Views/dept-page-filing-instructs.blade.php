<!-- resources/views/vendor/openpolice/dept-page-filing-instructs.blade.php -->

<a name="how"></a>
<div class="slCard mT20">
    <h3 class="mT0">How to File Complaints with the {!! $d["deptRow"]->DeptName !!}</h3>
    @if (isset($d["iaRow"]->OverOfficialFormNotReq) 
        && intVal($d["iaRow"]->OverOfficialFormNotReq) == 1
        && isset($d["iaRow"]->OverWaySubEmail) && intVal($d["iaRow"]->OverWaySubEmail) == 1
        && isset($d[$d["whichOver"]]->OverEmail) && trim($d[$d["whichOver"]]->OverEmail) != '')
        <div class="alert alert-success mT10" role="alert"><h4 class="m0">
            <i class="fa fa-trophy mR5" aria-hidden="true"></i> OPC-Compatible Department
        </h4></div>
        <p class="mB20">
            This police department's policy permits them to investigate complaints sent via email. 
            They also accept complaints filed on non-department forms. 
            <b class="bld">That means OPC will automatically file your report after you 
            <a href="/join-beta-test/{{ $d['deptRow']->DeptSlug }}"
                <?php /* href="/share-complaint-or-compliment/{{ $d['deptRow']->DeptSlug }}" */ ?>
                >share your story</a>.</b>
        </p><p>
            The information below includes other ways to submit a formal complaint to the 
            {{ $d[$d["whichOver"]]->OverAgncName }}.
        </p>
    @else
        <p>
            This department does not investigate OPC reports sent by email. We recommend you 
            <a href="/join-beta-test/{{ $d['deptRow']->DeptSlug }}"
                <?php /* href="/share-complaint-or-compliment/{{ $d['deptRow']->DeptSlug }}" */ ?>
                >share your story on OpenPolice.org</a>.
            Then use the information below to submit a formal complaint 
            to the {{ $d[$d["whichOver"]]->OverAgncName }}.
        </p>
    @endif
</div>

<div class="slCard mT20">
@if ($d["whichOver"] == 'civRow')
    <h3 class="mT0">{{ $d[$d["whichOver"]]->OverAgncName }}</h3>
    <p>
    This is the agency that collects complaints for the {!! $d["deptRow"]->DeptName !!}.
    This is where we recommend that OPC users file their complaints.
    </p>
    @if (isset($d["civAddy"]) && trim($d["civAddy"]) != '')
        <p><a href="{{ $GLOBALS['SL']->mapsURL($d['civAddy']) }}" target="_blank"
            ><i class="fa fa-map-marker mR5" aria-hidden="true"></i> {!! $d["civAddy"] !!}</a></p>
    @endif
@elseif (isset($d["iaAddy"]) && trim($d["iaAddy"]) != '')
    <h3 class="mT0">Internal Affairs</h3>
    <p><a href="{{ $GLOBALS['SL']->mapsURL($d['iaAddy']) }}" target="_blank"
        ><i class="fa fa-map-marker mR5" aria-hidden="true"></i> {!! $d["iaAddy"] !!}</a></p>
@endif
    
    @if (isset($d[$d["whichOver"]]->OverPhoneWork) && trim($d[$d["whichOver"]]->OverPhoneWork) != '')
        <p><i class="fa fa-phone mR5" aria-hidden="true"></i> {{ $d[$d["whichOver"]]->OverPhoneWork }}</p>
    @endif
    
    @if (isset($d["iaRow"]->OverComplaintWebForm) && trim($d["iaRow"]->OverComplaintWebForm) != '')
        <p>You can submit your complaint through this investigative agency's online complaint form. 
        (TIP: Drop in a link to your OPC complaint too!)<br />
        {!! $GLOBALS["SL"]->swapURLwrap($d["iaRow"]->OverComplaintWebForm, false) !!}</a></p>
    @endif
    @if (isset($d["iaRow"]->OverWaySubEmail) && intVal($d["iaRow"]->OverWaySubEmail) == 1
        && isset($d[$d["whichOver"]]->OverEmail) && trim($d[$d["whichOver"]]->OverEmail) != '')
        <p>You can submit your complaint by emailing this investigative agency. 
        (We recommend you include a link to your OPC complaint in your email.)<br />
        @if (!isset($d[$d['whichOver']]->OverKeepEmailPrivate) || intVal($d[$d['whichOver']]->OverKeepEmailPrivate) == 0)
            <a href="mailto:{{ $d[$d['whichOver']]->OverEmail }}">{{ 
                $d[$d["whichOver"]]->OverEmail }}</a></p>
        @endif
    @elseif (isset($d[$d["whichOver"]]->OverEmail) && trim($d[$d["whichOver"]]->OverEmail) != ''
        && (!isset($d[$d['whichOver']]->OverKeepEmailPrivate) || intVal($d[$d['whichOver']]->OverKeepEmailPrivate) == 0))
        <p><a href="mailto:{{ $d[$d['whichOver']]->OverEmail }}">{{ 
                $d[$d["whichOver"]]->OverEmail }}</a></p>
    @endif
    @if (isset($d["iaRow"]->OverComplaintPDF) && trim($d["iaRow"]->OverComplaintPDF) != '')
        <p>This investigative agency has a PDF form you can print 
        
        @if (isset($d["iaRow"]->OverWaySubPaperMail) && intVal($d["iaRow"]->OverWaySubPaperMail) == 1)
            out and mail.
        @else out. @endif
        <br />
        {!! $GLOBALS["SL"]->swapURLwrap($d["iaRow"]->OverComplaintPDF, false) !!}</p>
    @endif
    
    <p>If you submit your complaint on paper, we recommend that you staple a copy of your full OPC complaint 
    together with the department form.</p>
    
    @if (isset($d["iaRow"]->OverWebComplaintInfo) && trim($d["iaRow"]->OverWebComplaintInfo) != '')
        <p>Complaint process information:<br />
        {!! $GLOBALS["SL"]->swapURLwrap($d["iaRow"]->OverWebComplaintInfo, false) !!}</p>
    @endif

    <ul style="padding-left: 30px;">
    @if (!isset($d["iaRow"]->OverOfficialFormNotReq) || intVal($d["iaRow"]->OverOfficialFormNotReq) == 0)
        <li>Only complaints submitted on department forms will be investigated.</li>
    @endif
    @if (isset($d["iaRow"]->OverWaySubNotary) && intVal($d["iaRow"]->OverWaySubNotary) == 1)
        <li>Submitted complaints may require a notary to be investigated.</li>
    @endif
    @if (!isset($d["iaRow"]->OverOfficialAnon) || intVal($d["iaRow"]->OverOfficialAnon) == 0)
        <li>Anonymous complaints will not be investigated.</li>
    @endif
    @if (isset($d["iaRow"]->OverWaySubVerbalPhone) && intVal($d["iaRow"]->OverWaySubVerbalPhone) == 1)
        <li>Complaints submitted by phone will be investigated.</li>
    @endif
    @if (isset($d["iaRow"]->OverWaySubPaperMail) && intVal($d["iaRow"]->OverWaySubPaperMail) == 1)
        <li>Complaints submitted by postal mail will be investigated.</li>
    @endif
    @if (isset($d["iaRow"]->OverWaySubPaperInPerson) && intVal($d["iaRow"]->OverWaySubPaperInPerson) == 1)
        <li>Complaints submitted in person will be investigated.</li>
    @endif
    @if (isset($d["iaRow"]->OverSubmitDeadline) && intVal($d["iaRow"]->OverSubmitDeadline) > 0)
        <li>Complaints must be submitted within {{ number_format($d["iaRow"]->OverSubmitDeadline) 
        }} days of your incident to be investigated.</li>
    @endif
    </ul>
</div>

    
@if ($d["whichOver"] == 'civRow' && isset($d["iaAddy"]) && trim($d["iaAddy"]) != '')
     <div class="slCard mT20">
        <h3>Internal Affairs Office</h3>
        <p><a href="{{ $GLOBALS['SL']->mapsURL($d['iaAddy']) }}" target="_blank"
            ><i class="fa fa-map-marker mR5" aria-hidden="true"></i> {!! $d["iaAddy"] !!}</a></p>
        @if (isset($d["iaRow"]->OverPhoneWork) && trim($d["iaRow"]->OverPhoneWork) != '')
            <p><i class="fa fa-phone mR5" aria-hidden="true"></i> {{ $d["iaRow"]->OverPhoneWork }}</p>
        @endif
    </div>
@endif