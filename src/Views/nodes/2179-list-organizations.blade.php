<!-- resources/views/vendor/openpolice/nodes/2179-list-organizations.blade.php -->
<div class="container">
@forelse ($orgs as $i => $p)
    @if (isset($p->PartStatus) && intVal($p->PartStatus) == 1)
        <div class="pT20 pB20 mB20"><hr></div>
        <div class="row">
            <div class="col-sm-3">
                @if (isset($p->PartPhotoUrl) && trim($p->PartPhotoUrl) != '')
                    <a href="/org/{{ $p->PartSlug }}"><img src="{!! 
                        $p->PartPhotoUrl !!}" border=0 class="w100" >
                    </a>
                @else
                    <center><a href="/org/{{ $p->PartSlug }}"
                        ><img src="/openpolice/uploads/avatar-group-shell.png"
                        border=0 class="w80 round30" >
                    </a></center>
                @endif
            </div><div class="col-sm-4">
                <a href="/org/{{ $p->PartSlug }}"
                    ><h4>{!! $p->PrsnNickname !!}</h4></a>
                @if (isset($p->PartBio)) 
                    <p>{!! $GLOBALS["SL"]->wordLimitDotDotDot(
                        $GLOBALS["SL"]->plainLineBreaks($p->PartBio)) !!}
                    </p>
                @endif
                <p>
                @if (isset($p->PrsnAddressCity)) {{ $p->PrsnAddressCity }}, @endif
                @if (isset($p->PrsnAddressState)) {{ $p->PrsnAddressState }} @endif
                @if (isset($p->PrsnAddressCity) || isset($p->PrsnAddressState))
                    <br />
                @endif
                @if (isset($p->PartCompanyWebsite))
                    <a href="{{ $p->PartCompanyWebsite }}" target="_blank"
                        >{{ $p->PartCompanyWebsite }}</a>
                @endif
                </p>
            </div><div class="col-sm-5">
            @if (isset($capab[$p->PartID]) && sizeof($capab[$p->PartID]) > 0)
                <ul>
                @foreach ($capab[$p->PartID] as $c)
                    <li>{{ $c }}</li>
                @endforeach
                </ul>
            @endif
            </div>
        </div>
    @endif
@empty 
@endforelse
</div>