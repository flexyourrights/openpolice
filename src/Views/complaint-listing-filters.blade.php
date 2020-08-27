<!-- Stored in resources/views/openpolice/complaint-listing-filters.blade.php -->

<input type="hidden" name="showPreviews" id="showPreviewsID" value="0">
<div id="hidivCompFilts{{ $nID }}" 
    class="disBlo relDiv @if ($view == 'list') pB15 @endif ">

@if ($GLOBALS["SL"]->x["isPublicList"])

    <a id="compFiltBtn{{ $nID }}" href="javascript:;"
        class="pull-right btn btn-secondary btn-sm 
            updateSearchFilts updateComplaintFilts"
        ><nobr>Apply Filters</nobr></a>
    <h4>Filter</h4>
    <div class="w100 pT30 mBn15">
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

    <div class="absDiv" style="top: -10px; right: 100px; z-index: 100;">
        <div class="p5 bgWht" 
            style="position: fixed; 
                box-shadow: 0px 0px 3px 1px rgba(255, 255, 255, 0.9);">
            <a id="compFiltBtn{{ $nID }}" href="javascript:;"
                class="btn btn-secondary btn-sm 
                    updateSearchFilts updateComplaintFilts"
                ><nobr>Apply Filters</nobr></a>
        </div>
    </div>
    <h4>Filter</h4>
    <div class="row">
        <div class="col-sm-6">
            {!! $statusFilts !!}
            <div><hr></div>
            {!! $stateFilts !!}
            <div><hr></div>
        </div>
        <div class="col-sm-6">
            {!! $allegFilts !!}
            <div><hr></div>
            {!! $victFilts !!}
            <div><hr></div>
            {!! $offFilts !!}
		</div>
	</div>

@endif

</div>
