<!-- resources/views/vendor/openpolice/nodes/1710-report-inc-share.blade.php -->
<div class="row">
    <div class="col-lg-6 pB20">
        <h3 class="slBlueDark mT0 mB15">Read, Print, or Download</h3>
    @if (in_array($GLOBALS["SL"]->x["pageView"], ['pdf', 'full-pdf']))
        <div id="readPrintLnks">
            <div class="pT10 pB10"><a class="noUnd" href="/complaint/read-{{ $pubID }}" 
                ><i class="fa fa-link mR5" aria-hidden="true"></i> {{ $GLOBALS['SL']->sysOpts['app-url'] 
                }}/complaint/read-{{ $pubID }}</a></div>
            <div class="pB10"><a class="noUnd" target="_blank" href="/complaint/read-{{ $pubID }}/{{ $viewPrfx }}pdf"
                ><i class="fa fa-print mR5" aria-hidden="true"></i> Print Complaint or Save as PDF
                <div class="pL20 mL5">{{ $GLOBALS['SL']->sysOpts['app-url'] }}/complaint/read-{{ $pubID }}/{{ $viewPrfx }}pdf</div>
                </a></div>
            <div><a class="noUnd" target="_blank" href="/complaint/read-{{ $pubID }}/{{ $viewPrfx }}xml"
                ><i class="fa fa-cloud-download mR5" aria-hidden="true"></i> 
                Download Raw Complaint Data As XML File
                <div class="pL20 mL5">{{ $GLOBALS['SL']->sysOpts['app-url'] }}/complaint/read-{{ $pubID }}/{{ $viewPrfx }}xml</div>
                </a></div>
        </div>
    @else
        <div id="readPrintLnks">
            <div class="pB10"><a class="noUnd" href="/complaint/read-{{ $pubID }}" 
                ><i class="fa fa-link mR5" aria-hidden="true"></i> {{ $GLOBALS['SL']->sysOpts['app-url'] 
                }}/complaint/read-{{ $pubID }}</a></div>
            <div class="pB10"><a class="noUnd" target="_blank" href="/complaint/read-{{ $pubID }}/{{ $viewPrfx }}pdf"
                ><i class="fa fa-print mR5" aria-hidden="true"></i> Print Complaint or Save as PDF</a></div>
            <div><a class="noUnd" target="_blank" href="/complaint/read-{{ $pubID }}/{{ $viewPrfx }}xml"
                ><i class="fa fa-cloud-download mR5" aria-hidden="true"></i> 
                Download Raw Complaint Data As XML File</a></div>
        </div>
    @endif
    </div>
    <div class="col-lg-6 pB20">
    @if (isset($published) && $published && isset($GLOBALS["SL"]->x["pageView"]) 
        && in_array($GLOBALS["SL"]->x["pageView"], ['public', 'full'])
        && !in_array($GLOBALS["SL"]->x["pageView"], ['pdf', 'full-pdf']))
        <h3 class="slBlueDark mT0">Share</h3>
        <div class="disBlo">
            <div class="disIn mR10">
                {!! $GLOBALS["SL"]->twitShareBtn($GLOBALS["SL"]->sysOpts["app-url"] . '/complaint/read-' . $pubID, 
                    'Check out this police complaint!') !!}
            </div>
            <div class="disIn mR10">
                {!! $GLOBALS["SL"]->faceShareBtn($GLOBALS["SL"]->sysOpts["app-url"] . '/complaint/read-' . $pubID) !!}
            </div>
        </div>
        @if (isset($emojiTags) && trim($emojiTags) != '') <div class="mT5">{!! $emojiTags !!}</div> @endif
    @elseif (in_array($GLOBALS["SL"]->x["pageView"], ['pdf', 'full-pdf']))
        <h3 class="slBlueDark mT20">What is Open Police Complaints?</h3>
        <p class="pR20">OPC is a web app that helps people to prepare, file, and track police conduct reports. It's designed to serve the needs of oversight investigators, attorneys, police chiefs, and others working to advance constitutional and community policing.</p>
    @endif
    </div>
</div>
<style>
@media screen and (max-width: 480px) {
    #readPrintLnks { margin-bottom: 20px; }
}
</style>