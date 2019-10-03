<!-- resources/views/vendor/openpolice/nodes/2677-partner-clinic-only.blade.php -->
<h3>
@if ($type == $GLOBALS["SL"]->def->getID('Partner Types', 'Attorney'))
    @if (isset($dat["Partners"][0]->PartTitle))
        {!! $dat["Partners"][0]->PartTitle !!}
    @endif
@else
    @if (isset($dat["PersonContact"][0]->PrsnNickname))
        {!! $dat["PersonContact"][0]->PrsnNickname !!}
    @endif
@endif
</h3>
@if (isset($dat["Partners"][0]->PartHelpReqs))
    <p>{!! str_replace("\n", "</p><p>", $dat["Partners"][0]->PartHelpReqs) !!}</p>
@endif

@if (isset($dat["Partners"][0]->PartID) && $dat["Partners"][0]->PartID == 36)
    <center><div class="ovrNo w50 pT20 pB10">
    <a href="/openpolice/uploads/user-flow-clinic.jpg" target="_blank"
        ><img src="/openpolice/uploads/user-flow-clinic.jpg" class="w100" border="0"></a>
    </div></center>
    <style> #node2673 { display: none; } </style>
@endif
