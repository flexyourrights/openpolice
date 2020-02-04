<!-- resources/views/vendor/openpolice/nodes/2711-dept-page-basic-info.blade.php -->
<h2>{!! $d["deptRow"]->dept_name !!}</h2>
<p>
@if (isset($d["deptRow"]->dept_address) 
	&& trim($d["deptRow"]->dept_address) != '')
    <a href="{{ $GLOBALS['SL']->mapsURL($d['deptAddy']) }}" 
    	target="_blank" class="mR20"
        ><i class="fa fa-map-marker mR5" aria-hidden="true"></i> 
    	{{ $d["deptAddy"] }}
   	</a>
@endif
@if (isset($d["deptRow"]->dept_phone_work) 
	&& trim($d["deptRow"]->dept_phone_work) != '')
    <br /><i class="fa fa-phone mR5" aria-hidden="true"></i> 
    {{ $d["deptRow"]->dept_phone_work }}
@endif
</p>
<?php /*
<div class="mT3">{!! 
    $GLOBALS["SL"]->embedMapSimpRowAddy(
    	$nID, 
    	$d["deptRow"], 
    	'Dept', 
    	$d["deptRow"]->dept_name, 
    	250
    )
!!}</div>
*/ ?>