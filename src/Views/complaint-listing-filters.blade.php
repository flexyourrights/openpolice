<!-- Stored in resources/views/openpolice/complaint-listing-filters.blade.php -->

<input type="hidden" name="showPreviews" id="showPreviewsID" value="0">
<div id="hidivCompFilts{{ $nID }}" 
    class="disBlo relDiv @if ($view == 'list') pB15 @endif ">

@if ($GLOBALS["SL"]->x["isPublicList"])

    <div class="pB15">
        <a id="compFiltBtn{{ $nID }}" href="javascript:;"
            class="btn btn-primary btn-sm updateSearchFilts updateComplaintFilts"
            ><nobr>Apply Filters</nobr></a>
    </div>
    <a id="compFiltClearBtn{{ $nID }}" href="javascript:;"
        class="updateSearchFilts clearComplaintFilts slRedDark"
        ><nobr>Reset Filters</nobr></a>
    <div class="w100 pT15 mBn15">
        {!! $allegFilts !!}
        <div class="mTn10 mB5"><hr></div>
        {!! $victFilts !!}
        <div class="mTn10 mB5"><hr></div>
        {!! $offFilts !!}
        <div class="mTn10 mB5"><hr></div>
        {!! $stateFilts !!}
        <div class="mTn10 mB5"><hr></div>
        {!! $statusFilts !!}
    </div>

@else

    <div class="w100 pB10 pT20 mTn20 bgWht" 
        style="position: fixed; z-index: 100;
            box-shadow: 0px 0px 3px 1px rgba(255, 255, 255, 0.9);">
        <div class="pB15">
            <a id="compFiltBtn{{ $nID }}" href="javascript:;"
                class="btn btn-primary btn-sm updateSearchFilts updateComplaintFilts"
                ><nobr>Apply Filters</nobr></a>
        </div>
        <a id="compFiltClearBtn{{ $nID }}" href="javascript:;"
            class="updateSearchFilts clearComplaintFilts slRedDark"
            ><nobr>Reset Filters</nobr></a>
    </div>
    <div class="w100 p30 mB30"></div>
    <div class="row">
        <div class="col-sm-6">
            {!! $statusFilts !!}
        </div>
        <div class="col-sm-6">
            {!! $stateFilts !!}
            <div class="mTn15 mB5"><hr></div>
            {!! $allegFilts !!}
            <div class="mTn15 mB5"><hr></div>
            {!! $victFilts !!}
            <div class="mTn15 mB5"><hr></div>
            {!! $offFilts !!}
		</div>
	</div>

@endif

</div>
