<!-- Stored in resources/views/openpolice/1708-report-flex-articles-email.blade.php -->

@if (isset($allUrls) && sizeof($allUrls) > 0)
    @if (isset($allUrls["txt"]) && sizeof($allUrls["txt"]) > 0)
        <h4>Flex Your Rights Articles Related To This Complaint</h4>
        <ul>
        @foreach ($allUrls["txt"] as $i => $url)
            <li><a href="{{ $url[1] }}" target="_blank">{{ $url[0] }}</a></li>
        @endforeach
        </ul>
    @endif
    @if (isset($allUrls["vid"]) && sizeof($allUrls["vid"]) > 0)
        <h4>Flex Your Rights Videos Related To This Complaint</h4>
        <ul>
        @foreach ($allUrls["vid"] as $i => $url)
            <li><a href="{{ $url[1] }}" target="_blank">{{ $url[0] }}</a>
                <iframe type="text/html" width="100%" height="315" frameborder="0" allowfullscreen 
                    src="https://www.youtube.com/embed/{{ $GLOBALS['SL']->getYoutubeID($url[1]) }}?rel=0&color=white" 
                    style="margin-bottom: 20px;" ></iframe>
                </li>
        @endforeach
        </ul>
    @endif
@endif