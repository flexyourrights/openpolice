<!-- resources/views/vendor/openpolice/nodes/2234-beta-listing.blade.php -->
<p>Below are the users who have signed up so far. 
@if (isset($emptyTot) && intVal($emptyTot) > 0)
    There have also been {{ number_format($emptyTot) }} empty page visitor sessions since 3/6.
@endif
</p>
@forelse ($betas as $i => $beta)
    <div class="p15 @if ($i%2 == 0) row2 @endif "><div class="row">
        <div class="col-md-4">
            {{ $beta->BetaName }} {{ $beta->BetaLastName }}<br />
            <a href="mailto:{{ $beta->BetaEmail }}">{{ $beta->BetaEmail }}</a>
        </div><div class="col-md-6">
            {{ $beta->BetaNarrative }}
        </div><div class="col-md-2">
            {{ date("n/j g:ia", strtotime($beta->created_at)) }}<br />
            @if (isset($beta->BetaHowHear)) <span class="slGrey">{{ $beta->BetaHowHear }}</span> @endif
        </div>
    </div></div>
@empty
    <i>None found</i>
@endforelse