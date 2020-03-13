<!-- resources/views/vendor/openpolice/nodes/2711-dept-page-basic-info.blade.php -->
<?php /* <pre>{!! print_r($d) !!}</pre> */ ?>
<?php /* <h2>{!! $d["deptRow"]->dept_name !!}</h2> */ ?>
<div class="w100 pB20 taC">
    <?php /*
    <img src="/survloop-libraries/state-flags/state-flag-MD-maryland.jpg" border=0 
        class="bigTmbRound slBoxShdGryB mB15" >
    */ ?>
    <h5>
    <nobr><a href="/department-accessibility">U.S. Police Departments</a> <span class="mL5 mR5">&gt;</span></nobr> 
    <nobr><a href="/department-accessibility?state={{ $d['deptRow']->dept_address_state }}"
        >Maryland Police Departments</a></nobr>
    </h5>
</div>

<h3>
    How to File Complaints against the {!! $d["deptRow"]->dept_name !!}
</h3>
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