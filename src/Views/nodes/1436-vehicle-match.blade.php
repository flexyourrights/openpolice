<!-- resources/views/vendor/openpolice/nodes/1436-vehicle-match.blade.php -->
<div id="blockWrap1436" class="w100">
    <div class="nodeAnchor"><a id="n1436" name="n1436"></a></div>
    <div id="node1436" class="nodeWrap">
        <div class="nodeHalfGap"></div>
        <h2>Who Was In Which Vehicle?</h2>
        @forelse ($vehicles as $vehicle)
            <h3 class="slBlueDark">{!! view('vendor.openpolice.nodes.tbl-rec-label-vehicles', 
                [ "rec" => $vehicle ])->render() !!}</h3>
            <table class="table">
            @if (intVal($vehicle->VehicIsCivilian) == 1)
                @forelse ($civs as $civ)
                    {!! view('vendor.openpolice.nodes.1436-vehicle-match-line', [
                        "vehicle"  => $vehicle,
                        "linksCiv" => $linksCiv,
                        "civ"      => $civ,
                        "civNames" => $civNames
                    ])->render() !!}
                @empty
                @endforelse
                @forelse ($offs as $off)
                    {!! view('vendor.openpolice.nodes.1436-vehicle-match-line', [
                        "vehicle"  => $vehicle,
                        "linksOff" => $linksOff,
                        "off"      => $off,
                        "offNames" => $offNames
                    ])->render() !!}
                @empty
                @endforelse
            @else
                @forelse ($offs as $off)
                    {!! view('vendor.openpolice.nodes.1436-vehicle-match-line', [
                        "vehicle"  => $vehicle,
                        "linksOff" => $linksOff,
                        "off"      => $off,
                        "offNames" => $offNames
                    ])->render() !!}
                @empty
                @endforelse
                @forelse ($civs as $civ)
                    {!! view('vendor.openpolice.nodes.1436-vehicle-match-line', [
                        "vehicle"  => $vehicle,
                        "linksCiv" => $linksCiv,
                        "civ"      => $civ,
                        "civNames" => $civNames
                    ])->render() !!}
                @empty
                @endforelse
            @endif
            </table>
        @empty
        @endforelse
        <div class="nodeHalfGap"></div>
    </div> <!-- end #node1436 -->
</div>