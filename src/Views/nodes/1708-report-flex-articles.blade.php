<!-- Stored in resources/views/openpolice/nodes/1708-report-flex-articles.blade.php -->

@if (isset($allUrls) && sizeof($allUrls) > 0)
    <h3 class="slBlueDark mT0">Flex Your Rights articles related to this complaint</h3>
    <div class="row">
        <div class="col-lg-6">
        @foreach ($allUrls["txt"] as $i => $url)
            @if (ceil(sizeof($allUrls["txt"])/2) == $i) </div><div class="col-lg-6"> @endif
            <div class="mT20 pB10">
                <a href="{{ $url[1] }}" target="_blank" class="disBlo"
                    ><i class="fa fa-external-link mL15 pR5" aria-hidden="true"></i>
                    {{ $url[0] }}</a>
            </div>
        @endforeach
        </div>
    </div>
@endif