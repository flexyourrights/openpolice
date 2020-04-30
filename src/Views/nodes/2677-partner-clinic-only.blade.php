<!-- resources/views/vendor/openpolice/nodes/2677-partner-clinic-only.blade.php -->
<h3>
@if ($type == $GLOBALS["SL"]->def->getID('Partner Types', 'Attorney'))
    @if (isset($dat["partners"][0]->part_title))
        {!! $dat["partners"][0]->part_title !!}
    @endif
@else
    @if (isset($dat["person_contact"][0]->prsn_nickname))
        {!! $dat["person_contact"][0]->prsn_nickname !!}
    @endif
@endif
</h3>
@if (isset($dat["partners"][0]->part_help_reqs))
    <p>{!! str_replace("\n", "</p><p>", $dat["partners"][0]->part_help_reqs) !!}</p>
@endif

@if (isset($dat["partners"][0]->part_id) && $dat["partners"][0]->part_id == 36)
    <center><div class="ovrNo w50 pT20 pB10">
    <a href="/openpolice/uploads/user-flow-clinic.jpg" target="_blank"
        ><img src="/openpolice/uploads/user-flow-clinic.jpg" class="w100" border="0"></a>
    </div></center>
    <style> #node2673 { display: none; } </style>
@endif
