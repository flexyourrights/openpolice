<!-- resources/views/vendor/openpolice/nodes/145-dept-search.blade.php -->

<input type="hidden" name="n145fld" id="n145FldID" value="">
<label for="deptNameInID">
	<div class="f28 pT20 pB20">
		Find & Select A Police Department <small class="slRedDark f12">*required</small>
	</div>
	<div class="nPrompt">
		Please search for the department on the scene. 
		Try typing the name of the <b>city or county</b>, then click the correct department.
	</div>
</label>
<div class="nFld">
	<div class="row mB10">
		<div class="col-md-9">
			<input type="text" name="deptNameIn" id="deptNameInID" value="{{ $IncAddressCity }}" 
				class="form-control form-control-lg">
		</div>
		<div class="col-md-2">
			<select name="deptState" id="deptStateID" class="form-control form-control-lg">
				<option value="">Select State</option>
				{!! $stateDropstateDrop !!}
			</select>
		</div>
		<div class="col-md-1 taR">
			<a href="javascript:void(0)" id="ajaxSubmit" class="btn btn-primary"><i class="fa fa-search"></i></a>
		</div>
	</div>
</div>
<div id="ajaxSearch"></div>
<div class="fC p10"></div>
