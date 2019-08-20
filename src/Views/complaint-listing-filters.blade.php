<!-- Stored in resources/views/openpolice/complaint-listing-filters.blade.php -->

<?php /*
<div class="search-bar">
    <input type="text" id="searchBarComplaint" name="s807" class="form-control form-control-lg slTab searchBar" tabindex="1" value="">
    <div class="search-btn-wrap"><a id="searchTxt807t1" href="javascript:;" class="btn btn-info searchBarBtn" target="_parent"><i class="fa fa-search" aria-hidden="true"></i></a></div>
</div>
*/ ?>

<div id="hidivCompFilts{{ $nID }}" class="disBlo @if ($view == 'list') pB15 @endif ">
    <div class="row">
        <div class="col-6">
            <h4>Filter</h4>
        </div><div class="col-6 taR">
            <a id="compFiltBtn{{ $nID }}" class="btn btn-secondary btn-sm updateSearchFilts"
                href="javascript:;">Apply Filters</a>
        </div>
    </div>
    <div class="p0"><br /></div>

@if ($view == 'list')
	<div class="row">
		<div class="col-sm-6">
@endif

@if (!$GLOBALS["SL"]->x["isPublicList"])
            {!! $statusFilts !!}
            <div class="mTn10 mBn10"><hr></div>
            {!! $stateFilts !!}
@endif

@if ($view == 'list')
		</div>
		<div class="col-sm-6">
            <div class="mTn20"></div>
@elseif (!$GLOBALS["SL"]->x["isPublicList"])
            <div class="mTn10 mBn10"><hr></div>
@endif

            {!! $allegFilts !!}
            <div class="mTn10 mBn10"><hr></div>
            {!! $victFilts !!}
            <div class="mTn10 mBn10"><hr></div>
            {!! $offFilts !!}

@if ($GLOBALS["SL"]->x["isPublicList"])
            <div class="mTn10 mBn10"><hr></div>
            {!! $stateFilts !!}
            <div class="mTn10 mBn10"><hr></div>
            {!! $statusFilts !!}
@endif

@if ($view == 'list')
		</div>
	</div>
@endif

</div>
