<!-- Stored in resources/views/openpolice/complaint-listing-filters-off.blade.php -->
<label class="disBlo pB5 pT5 mTn20">
    <input type="checkbox" name="filtOffGend[]" id="filtOffGendM" value="M" autocomplete="off"
        @if (in_array('M', $srchFilts["offgend"])) CHECKED @endif class="mR5 searchDeetFld">
    Male
</label>
<label class="disBlo pB5 pT5">
    <input type="checkbox" name="filtOffGend[]" id="filtOffGendF" value="F" autocomplete="off"
        @if (in_array('F', $srchFilts["offgend"])) CHECKED @endif class="mR5 searchDeetFld">
    Female
</label>
@foreach ($races as $i => $race)
    <label class="disBlo pB5 pT5">
        <input type="checkbox" name="filtOffRace[]" id="filtOffRace{{ $i }}" value="{{ $race->DefID }}"
            @if (in_array($race->DefID, $srchFilts["offrace"])) CHECKED @endif class="mR5 searchDeetFld"
            autocomplete="off">
        {{ str_replace('Other', 'Other Race', $race->DefValue) }}
    </label>
@endforeach