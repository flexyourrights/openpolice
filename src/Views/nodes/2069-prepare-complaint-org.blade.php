<!-- resources/views/vendor/openpolice/nodes/2069-prepare-complaint-org.blade.php -->
@if ($dat["PersonContact"][0]->prsn_nickname == 'An Attorney')
    <h3 class="slBlueDark">Share your story with an attorney</h3>
    <div class="mT10 mBn15"><p>
    OpenPolice.org will help you prepare your story for your attorney.
    </p></div>
@else
    <h3 class="slBlueDark">Share your <nobr>story with</nobr><br />
    {!! $dat["PersonContact"][0]->prsn_nickname !!}</h3>
    <div class="mT10 mBn15"><p>
    @if (isset($dat["Partners"][0]->part_help_reqs)) {!! $dat["Partners"][0]->part_help_reqs !!} @endif
    <?php /* <a href="/org/{{ $dat['Partners'][0]->part_slug }}">Organization Profile</a> */ ?>
    We partnered with OpenPolice.org to help determine what help you need.
    </p></div>
@endif