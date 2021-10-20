<!-- Stored in resources/views/openpolice/complaint-listing-filters-off.blade.php -->
<label class="disBlo pB5 pT5">
    <input type="checkbox" name="filtOffGend[]" id="filtOffGendM" value="M"
        @if (in_array('M', $srchFilts["offgend"])) CHECKED @endif
        class="mR5 searchDeetFld" autocomplete="off">
    Male
</label>
<label class="disBlo pB5 pT5">
    <input type="checkbox" name="filtOffGend[]" id="filtOffGendF" value="F"
        @if (in_array('F', $srchFilts["offgend"])) CHECKED @endif
        class="mR5 searchDeetFld" autocomplete="off">
    Female
</label>
@foreach ($races as $i => $race)
    <label class="disBlo pB5 pT5">
        <input type="checkbox" name="filtOffRace[]" id="filtOffRace{{ $i }}"
            @if (in_array($race->def_id, $srchFilts["offrace"])) CHECKED @endif
            value="{{ $race->def_id }}" class="mR5 searchDeetFld" autocomplete="off">
        {{ str_replace('Other', 'Other Race', $race->def_value) }}
    </label>
@endforeach