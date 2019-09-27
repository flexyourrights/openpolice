<!-- resources/views/vendor/openpolice/nodes/2234-beta-listing.blade.php -->
<h2>Beta Tester Signups</h2>
<p>
    Below are the users who have signed up so far. 
    <a href="#stats" class="hsho">Stats Below</a>
</p>
<div class="p15">
    <div class="row">
        <div class="col-md-4">Name, Email<br />Year - Narrative</div>
        <div class="col-md-4">Signup</div>
        <div class="col-md-4">Invited</div>
    </div>
</div>
@forelse ($betas as $i => $beta)
    <div class="nodeAnchor"><a name="beta{{ $beta->BetaID }}"></a></div>
    <div class="p15 @if ($i%2 == 0) row2 @endif ">
        <div class="row">
            <div class="col-md-4">
                <b>{{ $beta->BetaName }} {{ $beta->BetaLastName }}</b><br />
                <a href="mailto:{{ $beta->BetaEmail }}">{{ $beta->BetaEmail }}</a>
            </div><div class="col-md-4">
                {{ $GLOBALS["SL"]->printTimeZoneShift($beta->created_at) }}<br />
                @if (isset($beta->BetaHowHear))
                    <span class="slGrey">{{ $beta->BetaHowHear }}</span>
                @endif
            </div><div class="col-md-4">
                @if (isset($beta->BetaInvited))
                    {{ date('n/j/y', strtotime($beta->BetaInvited)) }}
                @else
                    <a href="{!! $betaLinks[$beta->BetaID] !!}"
                        class="btn btn-secondary btn-sm">Send Invite</a>
                @endif
            </div>
        </div>
        @if (isset($beta->BetaYear)) {{ $beta->BetaYear }} - @endif
        {{ $beta->BetaNarrative }}
    </div>
@empty
    <i>None found</i>
@endforelse
<div class="p20">&nbsp;</div>

<div class="nodeAnchor"><a name="stats"></a></div>
<hr>
<h2>Click-Throughs</h2>
<p>There were also {{ number_format($emptyNoRef) }} 
    beta signup page loads without any referral, 
    out of {{ number_format($totLoads) }} total loads.</p>
<div id="betaClicks" class="w100"></div>

<hr>
<h2>Referral Signups</h2>
<div id="betaSignups" class="w100"></div>
