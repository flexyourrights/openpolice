<!-- resources/views/vendor/openpolice/nodes/2711-dept-page-basic-info.blade.php -->
<h2 class="mT0">{!! $d["deptRow"]->DeptName !!}</h2>
<p>
@if (isset($d["deptRow"]->DeptAddress) 
	&& trim($d["deptRow"]->DeptAddress) != '')
    <a href="{{ $GLOBALS['SL']->mapsURL($d['deptAddy']) }}" 
    	target="_blank" class="mR20"
        ><i class="fa fa-map-marker mR5" aria-hidden="true"></i> 
    	{{ $d["deptAddy"] }}
   	</a>
@endif
@if (isset($d["deptRow"]->DeptPhoneWork) 
	&& trim($d["deptRow"]->DeptPhoneWork) != '')
    <br /><i class="fa fa-phone mR5" aria-hidden="true"></i> 
    {{ $d["deptRow"]->DeptPhoneWork }}
@endif
</p>
<div class="mT3">{!! 
    $GLOBALS["SL"]->embedMapSimpRowAddy(
    	$nID, 
    	$d["deptRow"], 
    	'Dept', 
    	$d["deptRow"]->DeptName, 
    	250
    )
!!}</div>