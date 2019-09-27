<!-- resources/views/vendor/openpolice/nodes/2069-prepare-complaint-org.blade.php -->
@if ($dat["PersonContact"][0]->PrsnNickname == 'An Attorney')
    <h3 class="slBlueDark">Share your story with an attorney</h3>
    <div class="mT10 mBn15"><p>
    OpenPolice.org will help you prepare your story for your attorney.
    </p></div>
@else
    <h3 class="slBlueDark">Share your <nobr>story with</nobr><br />
    {!! $dat["PersonContact"][0]->PrsnNickname !!}</h3>
    <div class="mT10 mBn15"><p>
    @if (isset($dat["Partners"][0]->PartHelpReqs)) {!! $dat["Partners"][0]->PartHelpReqs !!} @endif
    <?php /* <a href="/org/{{ $dat['Partners'][0]->PartSlug }}">Organization Profile</a> */ ?>
    We partnered with OpenPolice.org to help determine what help you need.
    </p></div>
@endif