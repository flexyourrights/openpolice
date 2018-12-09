<!-- resources/views/vendor/openpolice/nodes/2069-prepare-complaint-org.blade.php -->
<p>&nbsp;</p>
<h3 class="slBlueDark">Share your story with<br />{!! $dat["PersonContact"][0]->PrsnNickname !!}</h3>
<p>
    @if (isset($dat["Partners"][0]->PartHelpReqs)) {{ $dat["Partners"][0]->PartHelpReqs }} @endif
    <a href="/org/{{ $dat['Partners'][0]->PartSlug }}">Organization Profile</a>
</p>