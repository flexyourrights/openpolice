<!-- Stored in resources/views/openpolice/complaint-listing-filters-settings.blade.php -->

<label class="disBlo pB5 pT5">
    <input type="checkbox" value="3"
        name="filtComSetts[]" id="filtComSetts3"
        @if (in_array('3', $srchFilts["comsetts"])) CHECKED @endif
        class="mR5 searchDeetFld" autocomplete="off"
        onClick="if (this.checked) document.getElementById('filtComSetts4').checked=false;">
    Full Transparency
</label>
<label class="disBlo pB5 pT5">
    <input type="checkbox" value="4"
        name="filtComSetts[]" id="filtComSetts4"
        @if (in_array('4', $srchFilts["comsetts"])) CHECKED @endif
        class="mR5 searchDeetFld" autocomplete="off"
        onClick="if (this.checked) document.getElementById('filtComSetts3').checked=false;">
    Fully Anonymous
</label>

<label class="disBlo pB5 pT5">
    <input type="checkbox" value="5"
        name="filtComSetts[]" id="filtComSetts5"
        @if (in_array('5', $srchFilts["comsetts"])) CHECKED @endif
        class="mR5 searchDeetFld" autocomplete="off">
    Uploaded Evidence
</label>
