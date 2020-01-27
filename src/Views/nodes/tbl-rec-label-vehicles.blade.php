@if (isset($rec) && $rec)
    <!-- resources/views/vendor/openpolice/nodes/tbl-rec-label-vehicles.blade.php -->
    {{ $GLOBALS["SL"]->def->getValById($rec->vehic_transportation) }}
    @if (trim($rec->vehic_unmarked) == 'Y') Unmarked @elseif (trim($rec->vehic_unmarked) == '?') Maybe Unmarked @endif 
    @if (trim($rec->vehic_vehicle_make) != '')    , {{ $rec->vehic_vehicle_make }} @endif 
    @if (trim($rec->vehic_vehicle_model) != '')   , {{ $rec->vehic_vehicle_model }} @endif 
    @if (trim($rec->vehic_vehicle_desc) != '')    , {{ $rec->vehic_vehicle_desc }} @endif 
    @if (trim($rec->vehic_vehicle_licence) != '') , License Plate {{ $rec->vehic_vehicle_licence }} @endif 
    @if (trim($rec->vehic_vehicle_number) != '')  , #{{ $rec->vehic_vehicle_number }} @endif
@endif