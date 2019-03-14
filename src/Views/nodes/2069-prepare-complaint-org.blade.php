<!-- resources/views/vendor/openpolice/nodes/2069-prepare-complaint-org.blade.php -->
<h3 class="slBlueDark">Share your story with 
<br />{!! $dat["PersonContact"][0]->PrsnNickname !!}</h3>
<p>
@if (isset($dat["Partners"][0]->PartHelpReqs)) {!! $dat["Partners"][0]->PartHelpReqs !!} @endif
<?php /* <a href="/org/{{ $dat['Partners'][0]->PartSlug }}">Organization Profile</a> */ ?>
If they cannot handle your request, OpenPolice.org will ask you what you want to do next.
</p>
