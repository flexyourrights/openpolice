<!-- resources/views/vendor/openpolice/nodes/2234-beta-listing.blade.php -->
@forelse ($betas as $i => $beta)
    <div class="p15 @if ($i%2 == 0) row2 @endif "><div class="row">
        <div class="col-md-3"><a href="mailto:{{ $beta->BetaEmail }}">{{ $beta->BetaEmail }}</a></div>
        <div class="col-md-3">{{ $beta->BetaName }}</div>
        <div class="col-md-6">{{ $beta->BetaNarrative }}</div>
    </div></div>
@empty
    <i>None found</i>
@endforelse