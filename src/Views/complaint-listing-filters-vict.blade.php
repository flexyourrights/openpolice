<!-- Stored in resources/views/openpolice/complaint-listing-filters-vict.blade.php -->
<label class="disBlo pB5 pT5 mTn20">
    <input type="checkbox" name="filtVictGend[]" id="filtVictGendM" value="M" autocomplete="off"
        @if (in_array('M', $srchFilts["victgend"])) CHECKED @endif class="mR5 searchDeetFld">
    Male
</label>
<label class="disBlo pB5 pT5">
    <input type="checkbox" name="filtVictGend[]" id="filtVictGendF" value="F" autocomplete="off"
        @if (in_array('F', $srchFilts["victgend"])) CHECKED @endif class="mR5 searchDeetFld">
        Female
</label>
<label class="disBlo pB5 pT5">
    <input type="checkbox" name="filtVictGend[]" id="filtVictGendT" value="T" autocomplete="off"
        @if (in_array('T', $srchFilts["victgend"])) CHECKED @endif class="mR5 searchDeetFld"> 
        Transgender/Other
</label>
@foreach ($races as $i => $race)
    <label class="disBlo pB5 pT5">
        <input name="filtVictRace[]" id="filtVictRace{{ $i }}" value="{{ $race->DefID }}"
            @if (in_array($race->DefID, $srchFilts["victrace"])) CHECKED @endif 
            class="mR5 searchDeetFld" type="checkbox" autocomplete="off">
            {{ str_replace('Other', 'Other Race', $race->DefValue) }}
    </label>
@endforeach