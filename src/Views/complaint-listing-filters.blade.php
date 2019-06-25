<!-- Stored in resources/views/openpolice/complaint-listing-filters.blade.php -->

<?php /*
<div class="search-bar">
    <input type="text" id="searchBarComplaint" name="s807" class="form-control form-control-lg slTab searchBar" tabindex="1" value="">
    <div class="search-btn-wrap"><a id="searchTxt807t1" href="javascript:;" class="btn btn-info searchBarBtn" target="_parent"><i class="fa fa-search" aria-hidden="true"></i></a></div>
</div>
*/ ?>

<div id="hidivCompFilts{{ $nID }}" class="disBlo pB15">
	<div class="row">
		<div class="col-sm-6">

		    @if ($view != 'incomplete')
				<p><b>By Complaint Status</b>
		        <select name="fltStatus" id="fltStatusID" class="form-control applyFilts" autocomplete="off">
		            <option value="0" @if (!isset($fltStatus) || intVal($fltStatus) <= 0) SELECTED @endif 
		                >Any Status</option>
		            {!! $GLOBALS["SL"]->def->getSetDrop('Complaint Status', $fltStatus,
		                ((isset($statusSkips)) ? $statusSkips : [])) !!}
		        </select></p>
		    @endif
			<p><b>By State</b>
			<select class="form-control" name="filtState">{!! $stateDrop !!}</select>
			</p>
			<p><b>By Allegation</b><br />
			@foreach ($allegTypes as $i => $alleg)
				<label class="disBlo pB5 pT5"><input type="checkbox" class="mR5" name="filtAllegs[]" value="{{ $alleg[0] }}"> {{ $alleg[1] }}</label>
			@endforeach
			</p>

		</div>
		<div class="col-sm-6">

			<p><b>By Victim Description</b><br />
				<label class="disBlo pB5 pT5"><input type="checkbox" class="mR5" name="filtVictGend[]" value="M"> Male</label>
				<label class="disBlo pB5 pT5"><input type="checkbox" class="mR5" name="filtVictGend[]" value="F"> Female</label>
				<label class="disBlo pB5 pT5"><input type="checkbox" class="mR5" name="filtVictGend[]" value="T"> Transgender/Other</label>
			@foreach ($races as $i => $race)
				<label class="disBlo pB5 pT5"><input type="checkbox" class="mR5" name="filtVictRace[]" value="T"> {{ $race->DefValue }}</label>
			@endforeach
			</p>
			<p><b>By Officer Description</b><br />
				<label class="disBlo pB5 pT5"><input type="checkbox" class="mR5" name="filtVictGend[]" value="M"> Male</label>
				<label class="disBlo pB5 pT5"><input type="checkbox" class="mR5" name="filtVictGend[]" value="F"> Female</label>
				<label class="disBlo pB5 pT5"><input type="checkbox" class="mR5" name="filtVictGend[]" value="T"> Transgender/Other</label>
			@foreach ($races as $i => $race)
				<label class="disBlo pB5 pT5"><input type="checkbox" class="mR5" name="filtVictRace[]" value="T"> {{ $race->DefValue }}</label>
			@endforeach
			</p>

		</div>
	</div>

	<a href="javascript:;" id="compFiltBtn{{ $nID }}" class="btn btn-primary btn-block btn-lg mT20">Filter Results</a>
</div>
