<!-- resources/views/vendor/openpolice/nodes/1898-public-attorney-page.blade.php -->

<div id="blockWrap{{ $nID }}" class="w100" style="overflow: visible;">
<div class="container" id="treeWrap{{ $nID }}">
<div class="fC"></div><div class="nodeAnchor"><a id="n{{ $nID }}" name="n{{ $nID }}"></a></div>

<div class="row">
    <div class="col-md-3">
        <div style="height: 55px;"></div>
        <p>
            @if (isset($dat["Partners"][0]->PartTitle)) {!! $dat["Partners"][0]->PartTitle !!}<br /> @endif
            @if (isset($dat["Partners"][0]->PartCompanyName)) 
                @if (isset($dat["Partners"][0]->PartCompanyWebsite)) <a href="{!! $dat['Partners'][0]->PartCompanyWebsite 
                    !!}" target="_blank">{!! $dat["Partners"][0]->PartCompanyName !!}</a><br />
                @else {!! $dat["Partners"][0]->PartCompanyName !!}<br />
                @endif
            @elseif (isset($dat["Partners"][0]->PartCompanyWebsite)) <a href="{!! $dat['Partners'][0]->PartCompanyWebsite 
                !!}" target="_blank">{!! $dat["Partners"][0]->PartCompanyWebsite !!}</a><br />
            @endif
            @if (isset($dat["Partners"][0]->PartBioUrl))
                <a href="{!! $dat['Partners'][0]->PartBioUrl !!}" target="_blank">Full Bio</a><br />
            @endif
        </p>
        <p>
        <b>Licensed In</b>
        @forelse ($dat["PartnerStates"] as $state)
            @if (isset($state->PrtStaState) && trim($state->PrtStaState) != '')
                <br />{!! $GLOBALS["SL"]->getState($state->PrtStaState) !!}
            @endif
        @empty @endforelse
        </p>
    </div>
    <div class="col-md-7">
        <div style="height: 15px;"></div>
        <h3>Meet the attorney</h3>
        <p>
        @if (isset($dat["Partners"][0]->PartBio))
            {!! str_replace("\n", "</p><p>", $dat["Partners"][0]->PartBio) !!}
        @endif
        </p>
    </div>
    <div class="col-md-2"></div>
</div>

<p>&nbsp;</p><p>&nbsp;</p>

<div class="slCard">
    <div class="row">
        <div class="col-md-3">
            <h4>Contact</h4>
            <p><a href="{{ $GLOBALS['SL']->rowAddyMapsURL($dat['PersonContact'][0], 'Prsn')
                }}" target="_blank">
            @if (isset($dat["Partners"][0]->PartCompanyName)) {!! $dat["Partners"][0]->PartCompanyName !!}<br /> @endif
            {!! $GLOBALS['SL']->printRowAddy($dat['PersonContact'][0], 'Prsn', true) !!}</a><br />
            @if (isset($dat["PersonContact"][0]->PrsnPhoneWork))
                {!! $dat["PersonContact"][0]->PrsnPhoneWork !!}<br />
            @endif
            </p>
        </div><div class="col-md-9">
            {!! $GLOBALS["SL"]->embedMapSimpRowAddy($nID, $dat['PersonContact'][0], 'Prsn',
                ((isset($dat["Partners"][0]->PartCompanyName)) ? $dat["Partners"][0]->PartCompanyName : '')) !!}
        </div>
    </div>
</div>

</div>
</div>