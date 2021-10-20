<!-- Stored in resources/views/openpolice/complaint-listing-filters-allegs.blade.php -->
@foreach ($GLOBALS["CUST"]->worstAllegs as $i => $allegType)
    <label class="disBlo pB5 pT5">
        <input type="checkbox" value="{{ $allegType->defID }}"
            id="filtAllegs{{ $i }}" name="filtAllegs[]"
            @if (in_array($allegType->defID, $srchFilts["allegs"])) CHECKED @endif
            class="mR5 searchDeetFld" autocomplete="off">
        {{ $allegType->name }}
    </label>
@endforeach