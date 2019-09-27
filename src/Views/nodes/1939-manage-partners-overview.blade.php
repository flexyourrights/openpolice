<!-- resources/views/vendor/openpolice/nodes/1939-manage-partners-overview.blade.php -->
<h2>OpenPolice.org Partners</h2>
<div class="p10"><div class="row">
    <div class="col-md-4">Partner Type</div>
    <div class="col-md-2 taR">Total Active</div>
</div></div>
@foreach ($prtnTypes as $i => $p)
    <div class="p10 @if ($i%2 == 0) row2 @endif "><div class="row">
        <div class="col-md-4">
            <h3><a href="/dash/manage-{{ strtolower($p['plur']) }}">{{ $p["plur"] }}</a></h3>
        </div>
        <div class="col-md-2 taR">
            <h3>{{ number_format($p["tot"]) }}</h3>
        </div>
    </div></div>
@endforeach