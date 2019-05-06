<!-- resources/views/vendor/openpolice/nodes/1898-public-attorney-page.blade.php -->

<div id="blockWrap{{ $nID }}" class="w100" style="overflow: visible;">
<div class="container" id="treeWrap{{ $nID }}">
<div class="fC"></div><div class="nodeAnchor"><a id="n{{ $nID }}" name="n{{ $nID }}"></a></div>

<div class="row">
    <div class="col-lg-3 col-md-4"><div id="partLft">
    @if ($type == $GLOBALS["SL"]->def->getID('Partner Types', 'Attorney'))
        <p>
        @if (isset($dat["Partners"][0]->PartTitle)) {!! $dat["Partners"][0]->PartTitle !!}<br /> @endif
        @if (isset($dat["Partners"][0]->PartCompanyName) && trim($dat["Partners"][0]->PartCompanyName) != '') 
            @if (isset($dat["Partners"][0]->PartCompanyWebsite))
                <a href="{!! $dat['Partners'][0]->PartCompanyWebsite !!}" target="_blank">{!! 
                    $dat["Partners"][0]->PartCompanyName !!}</a><br />
            @else {!! $dat["Partners"][0]->PartCompanyName !!}<br />
            @endif
        @endif </p>
    @endif
    @if (isset($dat["Partners"][0]->PartGeoDesc) && trim($dat["Partners"][0]->PartGeoDesc) != '')
        <p><b class="slBlueDark">Operating In</b><br />{!! $dat["Partners"][0]->PartGeoDesc !!}</p>
    @elseif (isset($dat["PartnerStates"]) && sizeof($dat["PartnerStates"]) > 0)
        <p><b class="slBlueDark">
        @if ($type == $GLOBALS["SL"]->def->getID('Partner Types', 'Attorney')) Licensed In
        @else Operating In @endif </b><br />
        @foreach ($dat["PartnerStates"] as $state)
            @if (isset($state->PrtStaState) && trim($state->PrtStaState) != '')
                {!! $GLOBALS["SL"]->getState($state->PrtStaState) !!}<br />
            @endif
        @endforeach
        </p>
    @endif
    @if (isset($dat["Partners"][0]->PartHelpReqs))
        <div class="row"><div class="col-lg-8">
        <p>{!! str_replace("\n", "</p><p>", $dat["Partners"][0]->PartHelpReqs) !!}</p>
        </div></div>
    @endif
    @if ($type == $GLOBALS["SL"]->def->getID('Partner Types', 'Organization'))
        <p><b class="slBlueDark">Organization Capabilities</b></p>
        <ul style="margin-left: -15px;">
        @if (isset($dat["PartnerCapac"]) && sizeof($dat["PartnerCapac"]) > 0)
            @foreach ($dat["PartnerCapac"] as $i => $cap)
                <li>{!! $GLOBALS["SL"]->def->getVal('Organization Capabilities', $cap->PrtCapCapacity) !!}</li>
            @endforeach
        @endif </ul>
    @endif
        
    </div></div>
    <div class="col-lg-7 col-md-8">
    
        <div style="height: 20px;"> </div>
        <h3>
            @if ($type == $GLOBALS["SL"]->def->getID('Partner Types', 'Attorney')) Meet the attorney
            @else About the organization @endif
        </h3>
        <p>
        @if (isset($dat["Partners"][0]->PartBio))
            {!! str_replace('\r\n', "</p><p>", str_replace("\n", "</p><p>", $dat["Partners"][0]->PartBio)) !!}
        @endif
        </p>
        @if (isset($dat["Partners"][0]->PartBioUrl))
            <p>More About: <a href="{!! $dat['Partners'][0]->PartBioUrl !!}" target="_blank"
                >{!! $dat['Partners'][0]->PartBioUrl !!}</a><br /></p>
        @endif
        @if (isset($dat["Partners"][0]->PartCompanyWebsite))
            <p>Home Page: <a href="{!! $dat['Partners'][0]->PartCompanyWebsite !!}" target="_blank"
                >{!! $dat['Partners'][0]->PartCompanyWebsite !!}</a><br /></p>
        @endif
        
        <?php /*
        @if (isset($dat["Partners"][0]->PartBioUrl))
            <p><a href="{!! $dat['Partners'][0]->PartBioUrl !!}" target="_blank"
                >Read more about 
            @if (isset($dat["PersonContact"][0]->PrsnNickname))
                {!! $dat["PersonContact"][0]->PrsnNickname !!}
            @else them @endif </a><br />
        @endif
        <p>
        @if ($type == $GLOBALS["SL"]->def->getID('Partner Types', 'Attorney'))
            @if (isset($dat["Partners"][0]->PartCompanyWebsite)) 
                <a href="{!! $dat['Partners'][0]->PartCompanyWebsite !!}" target="_blank">{!! 
                    $dat["Partners"][0]->PartCompanyWebsite !!}</a><br />
            @endif
            @if (isset($dat["Partners"][0]->PartBioUrl))
                <a href="{!! $dat['Partners'][0]->PartBioUrl !!}" target="_blank">Full Bio</a><br />
            @endif
        @elseif ($type == $GLOBALS["SL"]->def->getID('Partner Types', 'Organization'))
            @if (isset($dat["Partners"][0]->PartCompanyWebsite)) 
                <a href="{!! $dat['Partners'][0]->PartCompanyWebsite !!}" target="_blank">{!! 
                    $dat["Partners"][0]->PartCompanyWebsite !!}</a><br />
            @endif
        @endif
        </p>
        */ ?>
    </div>
    <div class="col-md-2"></div>
</div>

<p>&nbsp;</p>

<div class="slCard">
    <div class="row">
        <div class="col-md-3">
            <h4>Contact</h4>
            <p><a href="{{ $GLOBALS['SL']->rowAddyMapsURL($dat['PersonContact'][0], 'Prsn')
                }}" target="_blank">
            @if ($type == $GLOBALS["SL"]->def->getID('Partner Types', 'Attorney'))
                @if (isset($dat["Partners"][0]->PartCompanyName))
                    {!! $dat["Partners"][0]->PartCompanyName !!}<br />
                @endif
            @elseif ($type == $GLOBALS["SL"]->def->getID('Partner Types', 'Organization'))
                @if (isset($dat["PersonContact"][0]->PrsnNickname))
                    {!! $dat["PersonContact"][0]->PrsnNickname !!}<br />
                @endif
            @endif
            {!! $GLOBALS['SL']->printRowAddy($dat['PersonContact'][0], 'Prsn', true) !!}</a><br />
            @if (isset($dat["PersonContact"][0]->PrsnPhoneWork))
                {!! $dat["PersonContact"][0]->PrsnPhoneWork !!}<br />
            @endif
            </p>
        </div><div class="col-md-9">
        @if ($type == $GLOBALS["SL"]->def->getID('Partner Types', 'Attorney'))
            {!! $GLOBALS["SL"]->embedMapSimpRowAddy($nID, $dat['PersonContact'][0], 'Prsn',
                ((isset($dat["Partners"][0]->PartCompanyName)) ? $dat["Partners"][0]->PartCompanyName : '')) !!}
        @elseif ($type == $GLOBALS["SL"]->def->getID('Partner Types', 'Organization'))
            {!! $GLOBALS["SL"]->embedMapSimpRowAddy($nID, $dat['PersonContact'][0], 'Prsn',
                ((isset($dat["PersonContact"][0]->PrsnNickname)) ? $dat["PersonContact"][0]->PrsnNickname : '')) !!}
        @endif
        </div>
    </div>
</div>

<p>&nbsp;</p>
<p><a href="/partners">Back to list of all OpenPolice.org Partners</a></p>

</div>
</div>

<style>
#partImg { margin: -10px 0px -40px 15px; }
#partTtl { margin-top: -10px; }
#partLft { margin: 60px 0px 0px 18px; }
@media screen and (max-width: 992px) {
#partLft { margin: 60px 0px 0px 0px; }
#partImg { margin: 0px 0px -25px 0px; width: 145px; height: 145px; -moz-border-radius: 73px; border-radius: 73px; }
#partImg img { width: 145px; min-height: 145px; }
#partSharBtn { margin-top: -10px; }
}
@media screen and (max-width: 768px) {
#partImg { margin: -10px 0px -40px -5px; width: 125px; height: 125px; -moz-border-radius: 63px; border-radius: 63px; }
#partImg img { width: 125px; min-height: 125px; }
#partLft { margin: 50px 0px 0px 0px; }
#partTtl { padding: 0px 0px 0px 155px; margin: -90px -10px 30px 0px; }
}
@media screen and (max-width: 480px) {
#partImg { margin: -60px 0px -50px -40px; width: 100px; height: 100px; -moz-border-radius: 50px; border-radius: 50px; }
#partImg img { width: 100px; min-height: 100px; }
#partImg { margin: -20px 0px -20px -5px; }
#partSharBtn { margin: 0px 0px 20px 0px; }
#partTtl { margin: -70px 0px 0px -40px; }
}
</style>