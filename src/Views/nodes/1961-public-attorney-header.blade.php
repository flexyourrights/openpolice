<!-- resources/views/vendor/openpolice/nodes/1961-public-attorney-header.blade.php -->
<p>&nbsp;</p>
<div class="row">
    <div class="col-md-3">
        @if (isset($dat["Partners"][0]->PartPhotoUrl))
            <div class="hugTmbRoundDiv" style="margin: -10px 0px -40px 0px;">
                <img src="{!! $dat['Partners'][0]->PartPhotoUrl !!}" border=0 >
            </div>
        @endif
    </div><div class="col-md-7">
        @if (isset($dat["PersonContact"][0]->PrsnNickname))
            <h2 class="slBlueDark mB10">{!! $dat["PersonContact"][0]->PrsnNickname !!}</h2>
        @endif
        <a href="/prepare-complaint-for-attorney/{{ $dat['Partners'][0]->PartSlug
            }}" class="btn btn-lg btn-secondary">Share Your Story</a>
    </div>
    <div class="col-md-2"></div>
</div>
<style> #node1960kids a.btn-secondary:hover { color: #FFF; } </style>