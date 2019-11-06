<!-- resources/views/vendor/openpolice/nodes/1961-public-partner-header.blade.php -->
<p>&nbsp;</p>
<div class="row">
    <div class="col-lg-3 col-md-4">
        <div id="partImg" class="hugTmbRoundDiv">
            <img border=0 src="{!! ((isset($dat['Partners'][0]->PartPhotoUrl)
                && trim($dat['Partners'][0]->PartPhotoUrl) != '') 
                ? $dat['Partners'][0]->PartPhotoUrl
                : '/openpolice/uploads/avatar-group-shell.png') !!}" >
        </div>
    </div><div class="col-lg-9 col-md-8">
        <div id="partTtl">
        @if (isset($dat["PersonContact"][0]->PrsnNickname))
            <h2 class="slBlueDark mB10">{!! 
                $dat["PersonContact"][0]->PrsnNickname !!}</h2>
        @endif
        <a href="/prepare-complaint-for-{{ $slg }}/{{ 
            $dat['Partners'][0]->PartSlug 
                . (($GLOBALS['SL']->REQ->has('test')) ? '?test=1' : '') }}" 
            class="btn btn-lg btn-secondary" id="partSharBtn" 
            onmouseover="this.style.color='#FFF';" 
            onmouseout="this.style.color='#2B3493';"
            >Share Your Story</a>
        </div>
    </div>
</div>