<!-- Stored in resources/views/openpolice/complaint-report-flex-articles.blade.php -->

@if ($allUrls && sizeof($allUrls) > 0)
    <div class="p20"></div>
    <h4 class="mB20">Flex Your Rights Information Related To This Complaint</h4>
    <div class="row"><div class="col-md-6">
    @foreach ($allUrls["txt"] as $i => $url)
        @if (ceil(sizeof($allUrls["txt"])/2) == $i) </div><div class="col-md-6"> @endif
        <a href="{{ $url[1] }}" target="_blank" class="fPerc125 disBlo mB20"
            ><i class="fa fa-info-circle mL20 mR10" aria-hidden="true"></i> {{ $url[0] }}</a>
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
            <iframe type="text/html" width="100%" frameborder="0" allowfullscreen 
                @if (!in_array(sizeof($allUrls["vid"]), [1, 2, 4])) height="200" @else height="315" @endif 
                src="https://www.youtube.com/embed/{{ $GLOBALS['SL']->getYoutubeID($url[1]) }}?rel=0&color=white" 
                ></iframe></div>
        @endforeach
        </div>
    @endif
@endif