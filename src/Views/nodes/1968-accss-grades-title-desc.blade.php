<!-- resources/views/vendor/openpolice/nodes/1968-accss-grades-title-desc.blade.php -->
<div id="blockWrap{{ $nID }}" class="w100" style="margin-bottom: -15px; margin-top: 10px;">
<p><br></p>
<h2 class="slBlueDark"><nobr>Police Department</nobr> 
@if (!isset($state) || trim($state) == '') <nobr>Accessibility Grades</nobr>
@else <nobr>Accessibility Grades:</nobr> {{ $GLOBALS["SL"]->getState($state) }} @endif
</h2>
</div>