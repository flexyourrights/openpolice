<!-- resources/views/vendor/openpolice/nodes/1710-report-inc-share.blade.php -->
<div class="row">
    <div class="col-lg-6 pB20">
        <h4 class="slBlueDark mT0 mB15">Read, Print, or Download</h4>
    @if (in_array($GLOBALS["SL"]->pageView, ['pdf', 'full-pdf']))
        <p>
            <i class="fa fa-link mR5" aria-hidden="true"></i>
            {{ $GLOBALS['SL']->sysOpts['app-url'] }}/complaint/read-{{ $pubID }}
        </p>
        <p>
            Print Complaint or Save as PDF
            <br />{{ $GLOBALS['SL']->sysOpts['app-url']
                }}/complaint/read-{{ $pubID }}/{{ $viewPrfx }}pdf
        </p>
        <p>
            <i class="fa fa-cloud-download mR5" aria-hidden="true"></i>
            Download Raw Complaint Data As XML File
            <br />{{ $GLOBALS['SL']->sysOpts['app-url']
                }}/complaint/read-{{ $pubID }}/{{ $viewPrfx }}xml
        </p>
    @else
        <div id="readPrintLnks">
            <div class="pB10">
                <a class="noUnd" href="/complaint/read-{{ $pubID }}"
                    ><i class="fa fa-link mR5" aria-hidden="true"></i>
                    {{ $GLOBALS['SL']->sysOpts['app-url']
                        }}/complaint/read-{{ $pubID }}
                </a>
            </div>
            <div class="pB10">
                <a class="noUnd" target="_blank"
                    href="/complaint/read-{{ $pubID }}/{{ $viewPrfx }}pdf"
                    ><i class="fa fa-print mR5" aria-hidden="true"></i>
                    Print Complaint or Save as PDF
                </a>
            </div>
            <div>
                <a class="noUnd" target="_blank"
                    href="/complaint/read-{{ $pubID }}/{{ $viewPrfx }}xml"
                    ><i class="fa fa-cloud-download mR5" aria-hidden="true"></i>
                    Download Raw Complaint Data As XML File
                </a>
            </div>
        </div>
    @endif
    </div>
    <div class="col-lg-6 pB20">
    @if (isset($published) && $published && isset($GLOBALS["SL"]->pageView)
        && in_array($GLOBALS["SL"]->pageView, ['public', 'full'])
        && !in_array($GLOBALS["SL"]->pageView, ['pdf', 'full-pdf']))
        <h4 class="slBlueDark mT0">Share</h4>
        <div class="disBlo">
            <div class="disIn mR10">
                {!! $GLOBALS["SL"]->twitShareBtn(
                    $GLOBALS["SL"]->sysOpts["app-url"] . '/complaint/read-' . $pubID,
                    'Check out this police complaint!'
                ) !!}
            </div>
            <div class="disIn mR10">
                {!! $GLOBALS["SL"]->faceShareBtn(
                    $GLOBALS["SL"]->sysOpts["app-url"] . '/complaint/read-' . $pubID
                ) !!}
            </div>
        </div>
        @if (isset($emojiTags) && trim($emojiTags) != '')
            <div class="mT5">{!! $emojiTags !!}</div>
        @endif
    @elseif (in_array($GLOBALS["SL"]->pageView, ['pdf', 'full-pdf']))
        <h4 class="slBlueDark mT20">What is OpenPolice.org?</h4>
        <p class="pR20">
            OpenPolice.org is a nonprofit website to help people prepare, file,
            and track police conduct reports. We designed it to serve the needs
            of oversight investigators, attorneys, police chiefs, and others
            working to advance constitutional and community policing.
        </p>
    @endif
    </div>
</div>
<style>
@media screen and (max-width: 480px) {
    #readPrintLnks { margin-bottom: 20px; }
}
</style>