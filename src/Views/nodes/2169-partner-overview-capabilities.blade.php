<!-- resources/views/vendor/openpolice/nodes/2169-partner-overview-capabilities.blade.php -->
<div class="p10 slGrey">
    <div class="row">
        <div class="col-md-8">Partner Capabilities</div>
        <div class="col-md-4">Total Active</div>
    </div>
</div>
@foreach ($capac as $i => $c)
    <div class="p10 @if ($i%2 == 0) row2 @endif ">
        <div class="row">
            <div class="col-md-8">{{ $c["def"] }}</div>
            <div class="col-md-4">{{ number_format($c["tot"]) }}</div>
        </div>
    </div>
@endforeach