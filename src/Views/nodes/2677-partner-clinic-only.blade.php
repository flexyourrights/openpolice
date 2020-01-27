<!-- resources/views/vendor/openpolice/nodes/2677-partner-clinic-only.blade.php -->
<h3>
@if ($type == $GLOBALS["SL"]->def->getID('Partner Types', 'Attorney'))
    @if (isset($dat["Partners"][0]->part_title))
        {!! $dat["Partners"][0]->part_title !!}
    @endif
@else
    @if (isset($dat["PersonContact"][0]->prsn_nickname))
        {!! $dat["PersonContact"][0]->prsn_nickname !!}
    @endif
@endif
</h3>
@if (isset($dat["Partners"][0]->part_help_reqs))
    <p>{!! str_replace("\n", "</p><p>", $dat["Partners"][0]->part_help_reqs) !!}</p>
@endif

@if (isset($dat["Partners"][0]->part_id) && $dat["Partners"][0]->part_id == 36)
    <center><div class="ovrNo w50 pT20 pB10">
    <a href="/openpolice/uploads/user-flow-clinic.jpg" target="_blank"
        ><img src="/openpolice/uploads/user-flow-clinic.jpg" class="w100" border="0"></a>
    </div></center>
    <style> #node2673 { display: none; } </style>
@endif
