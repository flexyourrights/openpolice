<!-- resources/views/vendor/openpolice/nodes/1961-public-attorney-header.blade.php -->
<p>&nbsp;</p>
<div class="row">
    <div class="col-lg-3 col-md-4">
        @if (isset($dat["Partners"][0]->PartPhotoUrl))
            <div id="partImg" class="hugTmbRoundDiv">
                <img src="{!! $dat['Partners'][0]->PartPhotoUrl !!}" border=0 >
            </div>
        @endif
    </div><div class="col-lg-9 col-md-8">
        <div id="partTtl">
        @if (isset($dat["PersonContact"][0]->PrsnNickname))
            <h2 class="slBlueDark mB10">{!! $dat["PersonContact"][0]->PrsnNickname !!}</h2>
        @endif
        <a href="/prepare-complaint-for-{{ $slg }}/{{ $dat['Partners'][0]->PartSlug }}?test=1" class="btn btn-lg btn-secondary"
            id="partSharBtn" onmouseover="this.style.color='#FFF';" onmouseout="this.style.color='#2B3493';"
            >Share Your Story</a>
        </div>
    </div>
</div>