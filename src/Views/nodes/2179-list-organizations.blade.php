<!-- resources/views/vendor/openpolice/nodes/2179-list-organizations.blade.php -->
<div class="container">
@forelse ($orgs as $i => $p)
    @if (isset($p->part_status) && intVal($p->part_status) == 1)
        <div class="pT20 pB20 mB20"><hr></div>
        <div class="row">
            <div class="col-sm-3">
                @if (isset($p->part_photo_url) && trim($p->part_photo_url) != '')
                    <a href="/org/{{ $p->part_slug }}"><img src="{!! 
                        $p->part_photo_url !!}" border=0 class="w100" >
                    </a>
                @else
                    <center><a href="/org/{{ $p->part_slug }}"
                        ><img src="/openpolice/uploads/avatar-group-shell.png"
                        border=0 class="w80 round30" >
                    </a></center>
                @endif
            </div><div class="col-sm-4">
                <a href="/org/{{ $p->part_slug }}"
                    ><h4>{!! $p->prsn_nickname !!}</h4></a>
                @if (isset($p->part_bio)) 
                    <p>{!! $GLOBALS["SL"]->wordLimitDotDotDot(
                        $GLOBALS["SL"]->plainLineBreaks($p->part_bio)) !!}
                    </p>
                @endif
                <p>
                @if (isset($p->prsn_address_city)) {{ $p->prsn_address_city }}, @endif
                @if (isset($p->prsn_address_state)) {{ $p->prsn_address_state }} @endif
                @if (isset($p->prsn_address_city) || isset($p->prsn_address_state))
                    <br />
                @endif
                @if (isset($p->part_company_website))
                    <a href="{{ $p->part_company_website }}" target="_blank"
                        >{{ $p->part_company_website }}</a>
                @endif
                </p>
            </div><div class="col-sm-5">
            @if (isset($capab[$p->part_id]) && sizeof($capab[$p->part_id]) > 0)
                <ul>
                @foreach ($capab[$p->part_id] as $c)
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