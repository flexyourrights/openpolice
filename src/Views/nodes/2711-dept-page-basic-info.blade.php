<!-- resources/views/vendor/openpolice/nodes/2711-dept-page-basic-info.blade.php -->
<?php /* <pre>{!! print_r($d) !!}</pre> */ ?>
<?php /* <h2>{!! $d["deptRow"]->dept_name !!}</h2> */ ?>
<div class="w100 pB20 taC">
    <center>
    <img src="{{ $GLOBALS['SL']->states->getStateFlagImg(
            $d['deptRow']->dept_address_state
        ) }}" border=0 class="bigTmbRound slBoxShdGryB mB15" >
    </center>
    <h5>
    <nobr><a href="/departments"
        >U.S. Police Departments</a> 
        <span class="mL5 mR5">&gt;</span></nobr> 
    <nobr><a href="/departments?states={{ 
        $d['deptRow']->dept_address_state }}">{{ 
            $GLOBALS["SL"]->getState($d['deptRow']->dept_address_state) 
        }} Police Departments</a></nobr>
    </h5>
</div>

<h2>
    How to File Complaints against the {!! $d["deptRow"]->dept_name !!}
</h2>
<div class="pB5"><p>
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
</p></div>
<?php /*
<div class="mT3">{!! 
    $GLOBALS["SL"]->embedMapSimpRowAddy(
    	$nID, 
    	$d["deptRow"], 
    	'dept_', 
    	$d["deptRow"]->dept_name, 
    	250
    )
!!}</div>
*/ ?>