<!-- Stored in resources/views/openpolice/nodes/1753-report-flex-videos.blade.php -->

@if (isset($allUrls) && sizeof($allUrls) > 0 && sizeof($allUrls["vid"]) > 0)
    <h3 class="slBlueDark mT0 mB20">Flex Your Rights videos related to this complaint</h3>
    <div class="row @if (sizeof($allUrls['txt']) > 0) mT15 @endif ">
    @foreach ($allUrls["vid"] as $i => $url)
        @if (in_array(sizeof($allUrls["vid"]), [1, 2, 4]))
            @if ($i > 0 && $i%2 == 0) </div><div class="row mT10"> @endif
            <div class="col-md-6">
        @else
            @if ($i > 0 && $i%3 == 0) </div><div class="row mT10"> @endif
            <div class="col-md-4">
        @endif
        @if (isset($showVidUrls) && $showVidUrls)
            <a href="{{ $url[1] }}" target="_blank">{{ $url[0] }}</a><br />
        @endif
        <div class="w100 round10"><iframe frameborder="0" allowfullscreen style="width: 100%;
            @if (!in_array(sizeof($allUrls['vid']), [1, 2, 4])) height: 200px; @else height: 315px; @endif "
            src="https://www.youtube.com/embed/{{ $GLOBALS['SL']->getYoutubeID($url[1]) }}?rel=0&color=white" 
            ></iframe></div>
        </div>
    @endforeach
    </div>
@endif