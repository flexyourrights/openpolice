<!-- resources/views/vendor/openpolice/nodes/1436-vehicle-match-line.blade.php -->
<tr>
@if (isset($civ))
    <td>{{ $civNames[$civ->CivID] }}</td>
    <td>
        <label class="w100">
            <input type="radio" data-nid="1436" class="nCbox1436 slTab ntrStp" autocomplete="off" 
                id="n1436v{{ $vehicle->VehicleID }}c{{ $civ->CivID }}d" {!! $GLOBALS["SL"]->tabInd() !!}
                name="n1436v{{ $vehicle->VehicleID }}c{{ $civ->CivID }}" value="Driver"
                @forelse ($linksCiv as $lnk)
                    @if ($lnk->LnkCivVehicCivID == $civ->CivID && $lnk->LnkCivVehicVehicID == $vehicle->VehicleID
                        && $lnk->LnkCivVehicRole == 'Driver') CHECKED @endif
                @empty @endforelse >
            Driver
        </label>
    </td>
    <td>
        <label class="w100">
            <input type="radio" data-nid="1436" class="nCbox1436 slTab ntrStp" autocomplete="off" 
                id="n1436v{{ $vehicle->VehicleID }}c{{ $civ->CivID }}p" {!! $GLOBALS["SL"]->tabInd() !!}
                name="n1436v{{ $vehicle->VehicleID }}c{{ $civ->CivID }}" value="Passenger"
                @forelse ($linksCiv as $lnk)
                    @if ($lnk->LnkCivVehicCivID == $civ->CivID && $lnk->LnkCivVehicVehicID == $vehicle->VehicleID
                        && $lnk->LnkCivVehicRole == 'Passenger') CHECKED @endif
                @empty @endforelse >
            Passenger
        </label>
    </td>
@elseif (isset($off))
    <td>{{ $offNames[$off->OffID] }}</td>
    <td>
        <label class="w100">
            <input type="radio" data-nid="1436" class="nCbox1436 slTab ntrStp" autocomplete="off" 
                id="n1436v{{ $vehicle->VehicleID }}o{{ $off->OffID }}d" {!! $GLOBALS["SL"]->tabInd() !!}
                name="n1436v{{ $vehicle->VehicleID }}o{{ $off->OffID }}" value="Driver"
                @forelse ($linksOff as $lnk)
                    @if ($lnk->LnkCivVehicCivID == $off->OffID && $lnk->LnkCivVehicVehicID == $vehicle->VehicleID
                        && $lnk->LnkCivVehicRole == 'Driver') CHECKED @endif
                @empty @endforelse >
            Driver
        </label>
    </td>
    <td>
        <label class="w100">
            <input type="radio" data-nid="1436" class="nCbox1436 slTab ntrStp" autocomplete="off" 
                id="n1436v{{ $vehicle->VehicleID }}o{{ $off->OffID }}p" {!! $GLOBALS["SL"]->tabInd() !!}
                name="n1436v{{ $vehicle->VehicleID }}o{{ $off->OffID }}" value="Passenger"
                @forelse ($linksOff as $lnk)
                    @if ($lnk->LnkCivVehicCivID == $off->OffID && $lnk->LnkCivVehicVehicID == $vehicle->VehicleID
                        && $lnk->LnkCivVehicRole == 'Passenger') CHECKED @endif
                @empty @endforelse >
            Passenger
        </label>
    </td>
@endif
</tr>
