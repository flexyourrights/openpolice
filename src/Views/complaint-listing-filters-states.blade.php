<!-- Stored in resources/views/openpolice/complaint-listing-filters-states.blade.php -->

@foreach ($GLOBALS["SL"]->states->stateList as $abbr => $state)
    <label class="disBlo pT5 pB5">
        <div class="fL" style="width: 21px;">
            <input id="states{{ $abbr }}" name="states[]" value="{{ $abbr }}"
                @if (in_array($abbr, $srchFilts["states"])) CHECKED @endif
                class="searchDeetFld fltStates mT5" <?php /* updateSearchFilts */ ?>
                type="checkbox" autocomplete="off">
        </div>
        <div class="fL" style="width: 30px;">{{ $abbr }}</div>
        <div class="fL">{{ $state }}</div>
        <div class="fC"></div>
    </label>
@endforeach
<label class="disBlo pT20 pB5">
    <div class="fL" style="width: 21px;">
        <input id="statesUS" name="states[]" value="US"
            @if (in_array($abbr, $srchFilts["states"])) CHECKED @endif
            class="searchDeetFld fltStates mT5"  <?php /* updateSearchFilts */ ?>
            type="checkbox" autocomplete="off">
    </div>
    <div class="fL" style="width: 30px;">US</div>
    <div class="fL">Federal Law Enforcement</div>
    <div class="fC"></div>
</label>
