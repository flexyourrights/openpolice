<!-- Stored in resources/views/openpolice/complaint-listing-filters-allegs.blade.php -->
<div class="mTn10"></div>
@foreach ($allegTypes as $i => $alleg)
    <label class="disBlo pB5 pT5">
        <input type="checkbox" value="{{ $alleg[0] }}" 
            id="filtAllegs{{ $i }}" name="filtAllegs[]"
            @if (in_array($alleg[0], $srchFilts["allegs"])) CHECKED @endif 
            class="mR5 searchDeetFld" autocomplete="off">
        {{ $alleg[1] }}
    </label>
@endforeach