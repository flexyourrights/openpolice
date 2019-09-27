<!-- resources/views/vendor/openpolice/nodes/inc-oversight-info-row.blade.php -->
@if ($o && isset($o->OverAgncName) && trim($o->OverAgncName) != '')
    <b>{{ $o->OverAgncName }}</b><br />
    @if (isset($o->OverWebsite) && trim($o->OverWebsite) != '')
        <a href="{{ $o->OverWebsite }}" target="_blank">{{ $o->OverWebsite }}</a><br />
    @endif
    @if (isset($o->OverFacebook) && trim($o->OverFacebook) != '')
        <a href="{{ $o->OverFacebook }}" target="_blank">{{ $o->OverFacebook }}</a><br />
    @endif
    @if (isset($o->OverTwitter) && trim($o->OverTwitter) != '')
        <a href="{{ $o->OverTwitter }}" target="_blank">{{ $o->OverTwitter }}</a><br />
    @endif
    @if (isset($o->OverYouTube) && trim($o->OverYouTube) != '')
        <a href="{{ $o->OverYouTube }}" target="_blank">{{ $o->OverYouTube }}</a><br />
    @endif
    @if (isset($o->OverPhoneWork) && trim($o->OverPhoneWork) != '')
        {{ $o->OverPhoneWork }}<br />
    @endif
    @if (isset($o->OverAddress) && trim($o->OverAddress) != '')
        {{ $o->OverAddress }}<br />
        @if (isset($o->OverAddress2) && trim($o->OverAddress2) != '') {{ $o->OverAddress2 }}<br /> @endif
        {{ $o->OverAddressCity }}, {{ $o->OverAddressState }} {{ $o->OverAddressZip }}<br />
    @endif
    <br />                                                
    @if (isset($o->OverWaySubOnline) && intVal($o->OverWaySubOnline) == 1 && isset($o->OverComplaintWebForm) 
        && trim($o->OverComplaintWebForm) != '')
        You can submit your complaint through your investigative agency's online complaint form.
        Somewhere in their official form, 
        include a link to your OpenPolice.org complaint.<br />
        <a href="{{ $o->OverComplaintWebForm }}" target="_blank">{{ $o->OverComplaintWebForm }}</a><br /><br />
    @endif
    @if (isset($o->OverWaySubEmail) && intVal($o->OverWaySubEmail) == 1 && isset($o->OverEmail) 
        && trim($o->OverEmail) != '')
        You can submit your complaint by emailing your investigative agency. We recommend you include a link to your 
        OpenPolice.org complaint in your email.<br /><a href="mailto:{{ $o->OverEmail }}">{{ $o->OverEmail }}</a><br /><br />
    @endif
    @if (isset($o->OverComplaintPDF) && trim($o->OverComplaintPDF) != '')
        You can print out and use your investigative agency\'s official complaint form online. We recommend you also print
        your full OpenPolice.org complaint and submit it along with their official form.<br />
        <a href="{{ $o->OverComplaintPDF }}" target="_blank">{{ $o->OverComplaintPDF }}</a><br /><br />
    @endif
    <i>More about this complaint process:</i><br />
    @if (isset($o->OverWebComplaintInfo) && trim($o->OverWebComplaintInfo) != '')
        <a href="{{ $o->OverWebComplaintInfo }}" target="_blank">{{ $o->OverWebComplaintInfo }}</a><br />
    @endif
    @if (!isset($o->OverOfficialFormNotReq) || intVal($o->OverOfficialFormNotReq) == 0)
        Only complaints submitted on official forms will be investigated.<br /> 
    @endif
    @if (isset($o->OverWaySubNotary) && intVal($o->OverWaySubNotary) == 1)
        Submitted complaints may require a notary to be investigated.<br /> 
    @endif
    @if (!isset($o->OverOfficialAnon) || intVal($o->OverOfficialAnon) == 0)
        Anonymous complaints will not be investigated.<br /> 
    @endif
    @if (isset($o->OverWaySubVerbalPhone) && intVal($o->OverWaySubVerbalPhone) == 1)
        Complaints submitted by phone will be investigated.<br /> 
    @endif
    @if (isset($o->OverWaySubPaperMail) && intVal($o->OverWaySubPaperMail) == 1)
        Complaints submitted by postal mail will be investigated.<br /> 
    @endif
    @if (isset($o->OverWaySubPaperInPerson) && intVal($o->OverWaySubPaperInPerson) == 1)
        Complaints submitted in person will be investigated.<br /> 
    @endif
    @if (isset($o->OverSubmitDeadline) && intVal($o->OverSubmitDeadline) > 0)
        Complaints must be submitted within {{ number_format($o->OverSubmitDeadline) }} days of your incident 
        to be investigated.<br /> 
    @endif
@endif