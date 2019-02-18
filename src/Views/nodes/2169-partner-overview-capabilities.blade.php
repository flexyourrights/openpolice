<!-- resources/views/vendor/openpolice/nodes/2169-partner-overview-capabilities.blade.php -->
<h2>Partner Capabilities</h2>
<div class="p10"><div class="row">
    <div class="col-md-8">Partner Capability</div>
    <div class="col-md-2 taR">Total Active</div>
</div></div>
@foreach ($capac as $i => $c)
    <div class="p10 @if ($i%2 == 0) row2 @endif "><div class="row">
        <div class="col-md-8">
            <h4>{{ $c["def"] }}</h4>
        </div><div class="col-md-2 taR">
            <h4>{{ number_format($c["tot"]) }}</h4>
        </div>
    </div></div>
@endforeach