<!-- resources/views/vendor/openpolice/nodes/2718-dept-page-how-to-file.blade.php -->
@if ((isset($d["iaRow"]->over_website) && trim($d["iaRow"]->over_website) != '')
	|| (isset($d["iaRow"]->over_facebook) && trim($d["iaRow"]->over_facebook) != '')
	|| (isset($d["iaRow"]->over_twitter) && trim($d["iaRow"]->over_twitter) != '')
	|| (isset($d["iaRow"]->over_youtube) && trim($d["iaRow"]->over_youtube) != ''))
	<div class="slCard mT20">
	    <h3 class="mT0">Web Presence</h3>
	    @if (isset($d["iaRow"]->over_website) && trim($d["iaRow"]->over_website) != '')
	        <p><a href="{{ $d['iaRow']->over_website }}" target="_blank" 
	            ><i class="fa fa-home mR5" aria-hidden="true"></i> {{ 
	            $GLOBALS["SL"]->urlCleanIfShort(
	            	$d["iaRow"]->over_website, 
	            	'Department Website'
	            ) }}</a></p>
	    @endif
	    @if (isset($d["iaRow"]->over_facebook) && trim($d["iaRow"]->over_facebook) != '')
	        <p><a href="{{ $d['iaRow']->over_facebook }}" target="_blank"
	            ><i class="fa fa-facebook-square mR5" aria-hidden="true"></i> Facebook</a></p>
	    @endif
	    @if (isset($d["iaRow"]->over_twitter) && trim($d["iaRow"]->over_twitter) != '')
	        <p><a href="{{ $d['iaRow']->over_twitter }}" target="_blank"
	            ><i class="fa fa-twitter-square mR5" aria-hidden="true"></i> Twitter</a></p>
	    @endif
	    @if (isset($d["iaRow"]->over_youtube) && trim($d["iaRow"]->over_youtube) != '')
	        <p><a href="{{ $d['iaRow']->over_youtube }}" target="_blank"
	            ><i class="fa fa-youtube-play mR5" aria-hidden="true"></i> YouTube</a></p>
	    @endif
	</div>
@endif

{!! view(
	'vendor.openpolice.dept-page-filing-instructs', 
	[ "d" => $d ]
)->render() !!}