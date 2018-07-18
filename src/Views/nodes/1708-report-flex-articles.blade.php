<!-- Stored in resources/views/openpolice/nodes/1708-report-flex-articles.blade.php -->

@if (isset($allUrls) && sizeof($allUrls) > 0)
    <h3 class="slBlueDark mT0">Flex Your Rights articles related to this complaint</h3>
    <div class="row mT20">
        <div class="col-md-6 pB10">
        @foreach ($allUrls["txt"] as $i => $url)
            @if (ceil(sizeof($allUrls["txt"])/2) == $i) </div><div class="col-md-6"> @endif
            <a href="{{ $url[1] }}" target="_blank" class="disBlo mB10 pL5"
                ><i class="fa fa-external-link mL10 pR5" aria-hidden="true"></i> 
                <span class="fPerc125">{{ $url[0] }}</span></a>
        @endforeach
        </div>
    </div>
@endif