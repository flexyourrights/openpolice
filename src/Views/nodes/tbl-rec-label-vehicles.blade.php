@if (isset($rec) && sizeof($rec) > 0)
    <!-- resources/views/vendor/openpolice/nodes/tbl-rec-label-vehicles.blade.php -->
    {{ $GLOBALS["SL"]->getDefValById($rec->VehicTransportation) }}
    @if (trim($rec->VehicVehicleMake) != '')
        , {{ $rec->VehicVehicleMake }}
    @endif 
    @if (trim($rec->VehicVehicleModel) != '')
        , {{ $rec->VehicVehicleModel }}
    @endif 
    @if (trim($rec->VehicVehicleDesc) != '')
        , {{ $rec->VehicVehicleDesc }}
    @endif 
    @if (trim($rec->VehicVehicleLicence) != '')
        , License Plate {{ $rec->VehicVehicleLicence }}
    @endif 
    @if (trim($rec->VehicVehicleNumber) != '')
        , #{{ $rec->VehicVehicleNumber }}
    @endif
@endif