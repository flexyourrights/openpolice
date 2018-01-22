<!-- Stored in resources/views/openpolice/complaint-report-flex-articles.blade.php -->

@if ($allUrls && sizeof($allUrls) > 0)
    <div class="p10"></div><hr><div class="p5"></div>
    <h3 class="mB20"><a href="https://www.flexyourrights.org" target="_blank"
        ><img src="/openpolice/uploads/flex-arm-sm.png" class="mR5" height="30" > 
        Flex Your Rights</a> articles and videos related to this complaint</h3>
    <div class="row"><div class="col-md-6">
    @foreach ($allUrls["txt"] as $i => $url)
        @if (ceil(sizeof($allUrls["txt"])/2) == $i) </div><div class="col-md-6"> @endif
        <a href="{{ $url[1] }}" target="_blank" class="fPerc125 disBlo mB10 pL5"
            ><i class="fa fa-external-link mL10 pR5" aria-hidden="true"></i> {{ $url[0] }}</a>
    @endforeach
    </div></div>
    @if (sizeof($allUrls["vid"]) > 0)
        @if (sizeof($allUrls["txt"]) > 0) <div class="p10"></div> @endif
        <div class="row">
        @foreach ($allUrls["vid"] as $i => $url)
            @if (in_array(sizeof($allUrls["vid"]), [1, 2, 4]))
                @if ($i > 0 && $i%2 == 0) </div><div class="row"> @endif
                <div class="col-md-6">
            @else
                @if ($i > 0 && $i%3 == 0) </div><div class="row"> @endif
                <div class="col-md-4">
            @endif
            @if (isset($showVidUrls) && $showVidUrls)
                <a href="{{ $url[1] }}" target="_blank">{{ $url[0] }}</a><br />
            @endif
            <iframe frameborder="0" allowfullscreen style="width: 100%;
                @if (!in_array(sizeof($allUrls['vid']), [1, 2, 4])) height: 200px; @else height: 315px; @endif "
                src="https://www.youtube.com/embed/{{ $GLOBALS['SL']->getYoutubeID($url[1]) }}?rel=0&color=white" 
                ></iframe>
            </div>
        @endforeach
        </div>
    @endif
@endif