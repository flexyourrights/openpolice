<!-- resources/views/vendor/openpolice/nodes/1939-manage-partners-overview.blade.php -->
<h2 class="mBn10">Manage Partners</h2>
@foreach ($prtnTypes as $i => $p)
    <div class="pT20 mT10">
        <a href="/dash/manage-{{ strtolower($p['plur']) }}"
            class="btn btn-secondary btn-lg btn-block taL"
            >{{ number_format($p["tot"]) }} {{ $p["plur"] }}</a>
    </div>
@endforeach