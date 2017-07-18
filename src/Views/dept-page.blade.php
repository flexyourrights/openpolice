<!-- resources/views/vendor/openpolice/dept-page.blade.php -->

<h2 class="slBlueDark">{!! $deptStuff["deptRow"]->DeptName !!}</h2>

<div class="row">
    <div class="col-md-4">
    
        <div class="pT5"></div>
        @if (isset($deptStuff["deptRow"]->DeptAddress) && trim($deptStuff["deptRow"]->DeptAddress) != '')
            <a href="{{ $GLOBALS['SL']->mapsURL($deptStuff['deptAddy']) }}" target="_blank"
                ><i class="fa fa-map-marker mR5" aria-hidden="true"></i> {{ $deptStuff["deptAddy"] }}</a>
        @endif
        @if (isset($deptStuff["deptRow"]->DeptPhoneWork) && trim($deptStuff["deptRow"]->DeptPhoneWork) != '')
            <br /><i class="fa fa-phone mR5" aria-hidden="true"></i> {{ $deptStuff["deptRow"]->DeptPhoneWork }}
        @endif
        
        <style>
        tr.scoreRowOn td { color: #63C6FF; }
        tr.scoreRowOff td { color: #CCC; }
        </style>
        
        <div class="p10"></div>
        
        @if (isset($deptStuff["deptRow"]->DeptScoreOpenness) && intVal($deptStuff["deptRow"]->DeptScoreOpenness) > 0)
            <a class="toggleScoreInfo btn btn-info btn-lg w100" href="javascript:;"
                >OPC Accessibility Score: <b class="mL5" style="font-weight: bold;">{{ $deptStuff["deptRow"]->DeptScoreOpenness }}</b></a>
            <div id="toggleScoreInfoDeets" class="disNon">
                <table class="table table-striped mB0">
                <tbody>
                @if (isset($deptStuff["score"]) && sizeof($deptStuff["score"]) > 0)
                    @foreach ($deptStuff["score"] as $i => $s)
                        @if ($s[2])
                            <tr class="scoreRowOn">
                            <td class="tR"><i class="fa fa-check-circle mL5" aria-hidden="true"></i></td>
                            <td class="tC" style="width: 40px;">{{ $s[0] }}</td><td>{{ $s[1] }}</td></tr>
                        @else
                            <tr class="scoreRowOff"><td>&nbsp;</td>
                            <td class="tC" style="width: 40px;">{{ $s[0] }}</td><td>{{ $s[1] }}</td></tr>
                        @endif
                    @endforeach
                @endif
                </tbody>
                </table>
            </div>
            @if (true || (isset($deptStuff[$deptStuff["whichOver"]]->OverOfficialFormNotReq) 
                && intVal($deptStuff[$deptStuff["whichOver"]]->OverOfficialFormNotReq) == 1
                && isset($deptStuff[$deptStuff["whichOver"]]->OverWaySubEmail) 
                && intVal($deptStuff[$deptStuff["whichOver"]]->OverWaySubEmail) == 1
                && isset($deptStuff[$deptStuff["whichOver"]]->OverEmail) 
                && trim($deptStuff[$deptStuff["whichOver"]]->OverEmail) != ''))
                <a class="toggleOPCcompat btn btn-info btn-lg w100 mT5" href="javascript:;"
                    ><i class="fa fa-trophy mR5" aria-hidden="true"></i> OPC-Compatible Department 
                    <i class="fa fa-trophy mL5" aria-hidden="true"></i></a>
                <div id="toggleOPCcompatDeets" class="disNon jumbotron mTn5">
                    This police department's policy permits them to investigate complaints sent via email. They also accept 
                    complaints filed on non-department forms. That means OPC can file your complaint automatically!
                </div>
            @endif
        @endif
        
        <a class="btn btn-primary btn-lg w100 mT20 mB20" 
            href="/sharing-your-story/{{ $deptStuff['deptRow']->DeptSlug }}"
            >Share Your Story About The {!! str_replace('Department', 'Dept', $deptStuff["deptRow"]->DeptName) !!}</a>
        
        <h2 class="mT0 mB0">How To File A Complaint</h2>
        <div class="mB10">
            about police misconduct at the 
            {!! str_replace('Department', 'Dept', $deptStuff["deptRow"]->DeptName) !!}...
        </div>
        
        @if ($deptStuff["whichOver"] == 'civRow')
            <h3 class="m0 slBlueDark">{{ $deptStuff[$deptStuff["whichOver"]]->OverAgncName }}</h3>
            <p class="gry9">
            This is the agency that collects complaints for the {!! $deptStuff["deptRow"]->DeptName !!}.
            This is where we recommend that OPC users file their complaints.
            </p>
            @if (isset($deptStuff["civAddy"]) && trim($deptStuff["civAddy"]) != '')
                <a href="{{ $GLOBALS['SL']->mapsURL($deptStuff['civAddy']) }}" target="_blank"
                    ><i class="fa fa-map-marker mR5" aria-hidden="true"></i> {!! $deptStuff["civAddy"] !!}</a><br />
            @endif
        @else
            <h2 class="m0">Internal Affairs</h2>
            @if (isset($deptStuff["iaAddy"]) && trim($deptStuff["iaAddy"]) != '')
                <a href="{{ $GLOBALS['SL']->mapsURL($deptStuff['iaAddy']) }}" target="_blank"
                    ><i class="fa fa-map-marker mR5" aria-hidden="true"></i> {!! $deptStuff["iaAddy"] !!}</a><br />
            @endif
        @endif
            
                @if (isset($deptStuff[$deptStuff["whichOver"]]->OverPhoneWork) 
                    && trim($deptStuff[$deptStuff["whichOver"]]->OverPhoneWork) != '')
                    <i class="fa fa-phone mR5" aria-hidden="true"></i> 
                    {{ $deptStuff[$deptStuff["whichOver"]]->OverPhoneWork }}<br />
                @endif
                <br />
                
                @if (isset($deptStuff[$deptStuff["whichOver"]]->OverWaySubOnline) 
                    && intVal($deptStuff[$deptStuff["whichOver"]]->OverWaySubOnline) == 1
                    && isset($deptStuff[$deptStuff["whichOver"]]->OverComplaintWebForm) 
                    && trim($deptStuff[$deptStuff["whichOver"]]->OverComplaintWebForm) != '')
                    You can submit your complaint through this oversight agency's online complaint form. 
                    (Drop in a link to your OPC complaint too.)<br /><a href="{{ 
                    $deptStuff[$deptStuff['whichOver']]->OverComplaintWebForm }}" target="_blank">{{ 
                    $deptStuff[$deptStuff["whichOver"]]->OverComplaintWebForm }}
                    </a><br /><br />
                @endif
                @if (isset($deptStuff[$deptStuff["whichOver"]]->OverWaySubEmail) 
                    && intVal($deptStuff[$deptStuff["whichOver"]]->OverWaySubEmail) == 1
                    && isset($deptStuff[$deptStuff["whichOver"]]->OverEmail) 
                    && trim($deptStuff[$deptStuff["whichOver"]]->OverEmail) != '')
                    You can submit your complaint by emailing this oversight agency. 
                    (We recommend you include a link to your OPC complaint in your email.)<br />
                    <a href="mailto:{{ $deptStuff[$deptStuff['whichOver']]->OverEmail }}">{{ 
                    $deptStuff[$deptStuff["whichOver"]]->OverEmail }}</a><br /><br />
                @endif
                @if (isset($deptStuff[$deptStuff["whichOver"]]->OverComplaintPDF) 
                    && trim($deptStuff[$deptStuff["whichOver"]]->OverComplaintPDF) != '')
                    This oversight agency has a PDF form you can print out.<br /><a href="{{ 
                    $deptStuff[$deptStuff['whichOver']]->OverComplaintPDF }}" target="_blank">{{ 
                    $deptStuff[$deptStuff["whichOver"]]->OverComplaintPDF }}</a><br /><br />
                @endif
                
                If you submit your complaint on paper, we recommend that you staple a copy of your full OPC complaint together 
                with the department form.<br /><br />
                
                @if (isset($deptStuff[$deptStuff["whichOver"]]->OverWebComplaintInfo) 
                    && trim($deptStuff[$deptStuff["whichOver"]]->OverWebComplaintInfo) != '')
                    <div class="pT10">
                        Complaint process information:<br />
                        <a href="{{ $deptStuff[$deptStuff["whichOver"]]->OverWebComplaintInfo }}" target="_blank">{{ 
                        $deptStuff[$deptStuff["whichOver"]]->OverWebComplaintInfo }}</a>
                    </div>
                @endif
            
                <ul class="pT10" style="padding-left: 30px;">
                @if (!isset($deptStuff[$deptStuff["whichOver"]]->OverOfficialFormNotReq) 
                    || intVal($deptStuff[$deptStuff["whichOver"]]->OverOfficialFormNotReq) == 0)
                    <li>Only complaints submitted on department forms will be investigated.</li>
                @endif
                @if (isset($deptStuff[$deptStuff["whichOver"]]->OverWaySubNotary) 
                    && intVal($deptStuff[$deptStuff["whichOver"]]->OverWaySubNotary) == 1)
                    <li>Submitted complaints may require a notary to be investigated.</li>
                @endif
                @if (!isset($deptStuff[$deptStuff["whichOver"]]->OverOfficialAnon) 
                    || intVal($deptStuff[$deptStuff["whichOver"]]->OverOfficialAnon) == 0)
                    <li>Anonymous complaints will not be investigated.</li>
                @endif
                @if (isset($deptStuff[$deptStuff["whichOver"]]->OverWaySubVerbalPhone) 
                    && intVal($deptStuff[$deptStuff["whichOver"]]->OverWaySubVerbalPhone) == 1)
                    <li>Complaints submitted by phone will be investigated.</li>
                @endif
                @if (isset($deptStuff[$deptStuff["whichOver"]]->OverWaySubPaperMail) 
                    && intVal($deptStuff[$deptStuff["whichOver"]]->OverWaySubPaperMail) == 1)
                    <li>Complaints submitted by snail mail will be investigated.</li>
                @endif
                @if (isset($deptStuff[$deptStuff["whichOver"]]->OverWaySubPaperInPerson) 
                    && intVal($deptStuff[$deptStuff["whichOver"]]->OverWaySubPaperInPerson) == 1)
                    <li>Complaints submitted in person will be investigated.</li>
                @endif
                @if (isset($deptStuff[$deptStuff["whichOver"]]->OverSubmitDeadline) 
                    && intVal($deptStuff[$deptStuff["whichOver"]]->OverSubmitDeadline) > 0)
                    <li>Complaints must be submitted within {{ 
                    number_format($deptStuff[$deptStuff["whichOver"]]->OverSubmitDeadline) 
                    }} days of your incident to be investigated.</li>
                @endif
                </ul>
                
            @if ($deptStuff["whichOver"] == 'civRow')
                <div class="mT20"></div>
                <h3 class="m0">Internal Affairs Office</h3>
                @if (isset($deptStuff["iaAddy"]) && trim($deptStuff["iaAddy"]) != '')
                    <a href="{{ $GLOBALS['SL']->mapsURL($deptStuff['iaAddy']) }}" target="_blank"
                        ><i class="fa fa-map-marker mR5" aria-hidden="true"></i> {!! $deptStuff["iaAddy"] !!}</a><br />
                @endif
                @if (isset($deptStuff["iaRow"]->OverPhoneWork) 
                    && trim($deptStuff["iaRow"]->OverPhoneWork) != '')
                    <i class="fa fa-phone mR5" aria-hidden="true"></i> {{ $deptStuff["iaRow"]->OverPhoneWork }}<br />
                @endif
            @endif
        
            <div class="mT20"></div>
            <h3 class="m0">Department Web Presence</h3>
            @if (isset($deptStuff[$deptStuff["whichOver"]]->OverWebsite) 
                && trim($deptStuff[$deptStuff["whichOver"]]->OverWebsite) != '')
                <a href="{{ $deptStuff[$deptStuff["whichOver"]]->OverWebsite }}" target="_blank" 
                class="fPerc125 disBlo mB10">{{ 
                $GLOBALS["SL"]->urlClean($deptStuff[$deptStuff["whichOver"]]->OverWebsite) }}</a>
            @endif
            @if (isset($deptStuff[$deptStuff["whichOver"]]->OverFacebook) 
                && trim($deptStuff[$deptStuff["whichOver"]]->OverFacebook) != '')
                <a href="{{ $deptStuff[$deptStuff["whichOver"]]->OverFacebook }}" target="_blank"
                ><i class="fa fa-facebook-square mR5" aria-hidden="true"></i> {{ 
                $GLOBALS["SL"]->urlClean($deptStuff[$deptStuff["whichOver"]]->OverFacebook) }}</a><br />
            @endif
            @if (isset($deptStuff[$deptStuff["whichOver"]]->OverTwitter) 
                && trim($deptStuff[$deptStuff["whichOver"]]->OverTwitter) != '')
                <a href="{{ $deptStuff[$deptStuff["whichOver"]]->OverTwitter }}" target="_blank"
                ><i class="fa fa-twitter-square mR5" aria-hidden="true"></i> {{ 
                $GLOBALS["SL"]->urlClean($deptStuff[$deptStuff["whichOver"]]->OverTwitter) }}</a><br />
            @endif
            @if (isset($deptStuff[$deptStuff["whichOver"]]->OverYouTube) 
                && trim($deptStuff[$deptStuff["whichOver"]]->OverYouTube) != '')
                <a href="{{ $deptStuff[$deptStuff["whichOver"]]->OverYouTube }}" target="_blank"
                ><i class="fa fa-youtube-play mR5" aria-hidden="true"></i> {{ 
                $GLOBALS["SL"]->urlClean($deptStuff[$deptStuff["whichOver"]]->OverYouTube) }}</a><br />
            @endif
        
            <div class="pT10 mT20">
                The above instructions should also work to file a compliment (or commendation) 
                for excellent police conduct.
            </div>
            <div class="mT20 slGrey">
                <i>See something inaccurate? Please <a href="/contact">contact us</a> or 
                <a href="/dashboard/volunteer">volunteer</a> to update these records.</i>
            </div>
        
    </div>
    <div class="col-md-1"></div>
    <div class="col-md-7">

        <h3 class="mT0">Recent Complaints & Compliments</h3>
        <i>No complaints have been submitted for this deparment.</i>
        
    </div>
</div>
