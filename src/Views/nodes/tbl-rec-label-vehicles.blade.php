@if (isset($rec) && $rec)
    <!-- resources/views/vendor/openpolice/nodes/tbl-rec-label-vehicles.blade.php -->
    {{ $GLOBALS["SL"]->def->getValById($rec->VehicTransportation) }}
    @if (trim($rec->VehicUnmarked) == 'Y') Unmarked @elseif (trim($rec->VehicUnmarked) == '?') Maybe Unmarked @endif 
    @if (trim($rec->VehicVehicleMake) != '')    , {{ $rec->VehicVehicleMake }} @endif 
    @if (trim($rec->VehicVehicleModel) != '')   , {{ $rec->VehicVehicleModel }} @endif 
    @if (trim($rec->VehicVehicleDesc) != '')    , {{ $rec->VehicVehicleDesc }} @endif 
    @if (trim($rec->VehicVehicleLicence) != '') , License Plate {{ $rec->VehicVehicleLicence }} @endif 
    @if (trim($rec->VehicVehicleNumber) != '')  , #{{ $rec->VehicVehicleNumber }} @endif
@endif