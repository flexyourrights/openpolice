<!-- resources/views/vendor/openpolice/nodes/2718-dept-page-how-to-file.blade.php -->
@if ((isset($d["iaRow"]->OverWebsite) && trim($d["iaRow"]->OverWebsite) != '')
	|| (isset($d["iaRow"]->OverFacebook) && trim($d["iaRow"]->OverFacebook) != '')
	|| (isset($d["iaRow"]->OverTwitter) && trim($d["iaRow"]->OverTwitter) != '')
	|| (isset($d["iaRow"]->OverYouTube) && trim($d["iaRow"]->OverYouTube) != ''))
	<div class="slCard mT20">
	    <h3 class="mT0">Web Presence</h3>
	    @if (isset($d["iaRow"]->OverWebsite) && trim($d["iaRow"]->OverWebsite) != '')
	        <p><a href="{{ $d['iaRow']->OverWebsite }}" target="_blank" 
	            ><i class="fa fa-home mR5" aria-hidden="true"></i> {{ 
	            $GLOBALS["SL"]->urlCleanIfShort(
	            	$d["iaRow"]->OverWebsite, 
	            	'Department Website'
	            ) }}</a></p>
	    @endif
	    @if (isset($d["iaRow"]->OverFacebook) && trim($d["iaRow"]->OverFacebook) != '')
	        <p><a href="{{ $d['iaRow']->OverFacebook }}" target="_blank"
	            ><i class="fa fa-facebook-square mR5" aria-hidden="true"></i> Facebook</a></p>
	    @endif
	    @if (isset($d["iaRow"]->OverTwitter) && trim($d["iaRow"]->OverTwitter) != '')
	        <p><a href="{{ $d['iaRow']->OverTwitter }}" target="_blank"
	            ><i class="fa fa-twitter-square mR5" aria-hidden="true"></i> Twitter</a></p>
	    @endif
	    @if (isset($d["iaRow"]->OverYouTube) && trim($d["iaRow"]->OverYouTube) != '')
	        <p><a href="{{ $d['iaRow']->OverYouTube }}" target="_blank"
	            ><i class="fa fa-youtube-play mR5" aria-hidden="true"></i> YouTube</a></p>
	    @endif
	</div>
@endif

{!! view(
	'vendor.openpolice.dept-page-filing-instructs', 
	[ "d" => $d ]
)->render() !!}