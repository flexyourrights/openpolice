<!-- Stored in resources/views/openpolice/complaint-listing-filters-states.blade.php -->
<div class="mTn20"></div>
@foreach ($GLOBALS["SL"]->states->stateList as $abbr => $state)
    <label class="disBlo pB5 pT5">
        <div class="fL" style="width: 21px;">
            <input type="checkbox" id="states{{ $abbr }}" name="states[]" value="{{ $abbr }}"
                @if (in_array($abbr, $srchFilts["states"])) CHECKED @endif 
                class="searchDeetFld fltStates" autocomplete="off">
        </div>
        <div class="fL" style="width: 30px;">{{ $abbr }}</div>
        <div class="fL">{{ $state }}</div>
        <div class="fC"></div>
    </label>
@endforeach