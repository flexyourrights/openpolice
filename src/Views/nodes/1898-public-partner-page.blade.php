<!-- resources/views/vendor/openpolice/nodes/1898-public-partner-page.blade.php -->

<div id="blockWrap{{ $nID }}" class="w100" style="overflow: visible;">
<div class="container" id="treeWrap{{ $nID }}">
<div class="fC"></div>
<div class="nodeAnchor"><a id="n{{ $nID }}" name="n{{ $nID }}"></a></div>

<div class="row">
    <div class="col-lg-3 col-md-4"><div id="partLft">
    @if ($type == $GLOBALS["SL"]->def->getID('Partner Types', 'Attorney'))
        <p>
        @if (isset($dat["Partners"][0]->part_title))
            {!! $dat["Partners"][0]->part_title !!}<br />
        @endif
        @if (isset($dat["Partners"][0]->part_company_name) 
            && trim($dat["Partners"][0]->part_company_name) != '') 
            @if (isset($dat["Partners"][0]->part_company_website))
                <a href="{!! $dat['Partners'][0]->part_company_website !!}" 
                    target="_blank">{!! $dat["Partners"][0]->part_company_name !!}</a><br />
            @else {!! $dat["Partners"][0]->part_company_name !!}<br />
            @endif
        @endif </p>
    @endif
    @if (isset($dat["Partners"][0]->part_geo_desc) 
        && trim($dat["Partners"][0]->part_geo_desc) != '')
        <p><b class="slBlueDark">Operating In</b><br />
        {!! $dat["Partners"][0]->part_geo_desc !!}</p>
    @elseif (isset($dat["PartnerStates"]) && sizeof($dat["PartnerStates"]) > 0)
        <p><b class="slBlueDark">
        @if ($type == $GLOBALS["SL"]->def->getID('Partner Types', 'Attorney')) Licensed In
        @else Operating In 
        @endif </b><br />
        @foreach ($dat["PartnerStates"] as $state)
            @if (isset($state->prt_sta_state) && trim($state->prt_sta_state) != '')
                {!! $GLOBALS["SL"]->getState($state->prt_sta_state) !!}<br />
            @endif
        @endforeach
        </p>
    @endif
    @if (isset($dat["Partners"][0]->part_help_reqs))
        <div class="row"><div class="col-lg-8">
        <p>{!! str_replace("\n", "</p><p>", $dat["Partners"][0]->part_help_reqs) !!}</p>
        </div></div>
    @endif
    @if ($type == $GLOBALS["SL"]->def->getID('Partner Types', 'Organization'))
        <div class="mT20 mB10 slBlueDark">
            <b>Organization Capabilities for Users of OpenPolice.org</b>
        </div>
        <ul style="margin-left: -15px;">
        @if (isset($dat["PartnerCapac"]) && sizeof($dat["PartnerCapac"]) > 0)
            @foreach ($dat["PartnerCapac"] as $i => $cap)
                <li>{!! $GLOBALS["SL"]->def->getVal(
                    'Organization Capabilities', 
                    $cap->PrtCapCapacity
                ) !!}</li>
            @endforeach
        @endif </ul>
    @endif
        
    </div></div>
    <div class="col-lg-7 col-md-8">
    
        <div style="height: 20px;"> </div>
        <h3>
        @if ($type == $GLOBALS["SL"]->def->getID('Partner Types', 'Attorney'))
            Meet the attorney
        @else
            About the organization
        @endif
        </h3>
        <p>
        @if (isset($dat["Partners"][0]->part_bio))
            {!! str_replace('\r\n', "</p><p>", str_replace("\n", "</p><p>", 
                $dat["Partners"][0]->part_bio)) !!}
        @endif
        </p>
        @if (isset($dat["Partners"][0]->part_bio_url))
            <p>More About: 
            <a href="{!! $dat['Partners'][0]->part_bio_url !!}" target="_blank"
                >{!! $dat['Partners'][0]->part_bio_url !!}</a><br /></p>
        @endif
        @if (isset($dat["Partners"][0]->part_company_website))
            <p>Home Page: 
            <a href="{!! $dat['Partners'][0]->part_company_website !!}" target="_blank"
                >{!! $dat['Partners'][0]->part_company_website !!}</a><br /></p>
        @endif
        
        <?php /*
        @if (isset($dat["Partners"][0]->part_bio_url))
            <p><a href="{!! $dat['Partners'][0]->part_bio_url !!}" target="_blank"
                >Read more about 
            @if (isset($dat["PersonContact"][0]->prsn_nickname))
                {!! $dat["PersonContact"][0]->prsn_nickname !!}
            @else them @endif </a><br />
        @endif
        <p>
        @if ($type == $GLOBALS["SL"]->def->getID('Partner Types', 'Attorney'))
            @if (isset($dat["Partners"][0]->part_company_website)) 
                <a href="{!! $dat['Partners'][0]->part_company_website !!}" target="_blank">{!! 
                    $dat["Partners"][0]->part_company_website !!}</a><br />
            @endif
            @if (isset($dat["Partners"][0]->part_bio_url))
                <a href="{!! $dat['Partners'][0]->part_bio_url !!}" target="_blank">Full Bio</a><br />
            @endif
        @elseif ($type == $GLOBALS["SL"]->def->getID('Partner Types', 'Organization'))
            @if (isset($dat["Partners"][0]->part_company_website)) 
                <a href="{!! $dat['Partners'][0]->part_company_website !!}" target="_blank">{!! 
                    $dat["Partners"][0]->part_company_website !!}</a><br />
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
            <p><a href="{{ $GLOBALS['SL']->rowAddyMapsURL($dat['PersonContact'][0], 'prsn_')
                }}" target="_blank">
            @if ($type == $GLOBALS["SL"]->def->getID('Partner Types', 'Attorney'))
                @if (isset($dat["Partners"][0]->part_company_name))
                    {!! $dat["Partners"][0]->part_company_name !!}<br />
                @endif
            @elseif ($type == $GLOBALS["SL"]->def->getID('Partner Types', 'Organization'))
                @if (isset($dat["PersonContact"][0]->prsn_nickname))
                    {!! $dat["PersonContact"][0]->prsn_nickname !!}<br />
                @endif
            @endif
            {!! $GLOBALS['SL']->printRowAddy($dat['PersonContact'][0], 'prsn_', true) !!}</a><br />
            @if (isset($dat["PersonContact"][0]->prsn_phone_work))
                {!! $dat["PersonContact"][0]->prsn_phone_work !!}<br />
            @endif
            </p>
        </div><div class="col-md-9">
        @if ($type == $GLOBALS["SL"]->def->getID('Partner Types', 'Attorney'))
            {!! $GLOBALS["SL"]->embedMapSimpRowAddy($nID, $dat['PersonContact'][0], 'prsn_',
                ((isset($dat["Partners"][0]->part_company_name)) 
                    ? $dat["Partners"][0]->part_company_name : '')) !!}
        @elseif ($type == $GLOBALS["SL"]->def->getID('Partner Types', 'Organization'))
            {!! $GLOBALS["SL"]->embedMapSimpRowAddy($nID, $dat['PersonContact'][0], 'prsn_',
                ((isset($dat["PersonContact"][0]->prsn_nickname)) 
                    ? $dat["PersonContact"][0]->prsn_nickname : '')) !!}
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