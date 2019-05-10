<!-- resources/views/vendor/openpolice/nodes/1710-report-inc-share.blade.php -->
<div class="row">
    <div class="col-lg-6 pB20">
        <h3 class="slBlueDark mT0 mB15">Read, Print, Download...</h3>
        <div id="readPrintLnks">
            <a class="noUnd" href="/complaint/read-{{ $pubID }}" 
                ><i class="fa fa-link mR5" aria-hidden="true"></i> {{ $GLOBALS['SL']->sysOpts['app-url'] 
                }}/complaint/read-{{ $pubID }}</a><br />
            <a class="noUnd" target="_blank" href="/complaint/read-{{ $pubID }}/{{ $viewPrfx }}pdf"
                ><i class="fa fa-print mR5" aria-hidden="true"></i> Print Complaint or Save as PDF</a><br />
            <a class="noUnd" target="_blank" href="/complaint/read-{{ $pubID }}/{{ $viewPrfx }}xml"
                ><i class="fa fa-cloud-download mR5" aria-hidden="true"></i> 
                Download Raw Complaint Data As XML File</a>
        </div>
    </div>
    <div class="col-lg-6 pB20">
    @if (isset($published) && $published && isset($GLOBALS["SL"]->x["pageView"]) 
        && in_array($GLOBALS["SL"]->x["pageView"], ['public', 'full']))
        <h3 class="slBlueDark mT0 mB20">Share...</h3>
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
    @endif
    </div>
</div>
<style>
#readPrintLnks { font-size: 125%; }
@media screen and (max-width: 480px) { #readPrintLnks { font-size: 100%; margin-bottom: 20px; } }
</style>