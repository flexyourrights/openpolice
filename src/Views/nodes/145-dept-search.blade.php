<!-- resources/views/vendor/openpolice/nodes/145-dept-search.blade.php -->

<input type="hidden" name="n{{ $nID }}fld" id="n{{ $nID }}FldID" value="">
<div class="nFld mTn20">
    <div class="row mB10">
        <div class="col-7 pT5">
            <input type="text" name="deptNameIn" id="deptNameInID" value="{{ $IncAddressCity }}" 
                class="form-control form-control-lg">
        </div>
        <div class="col-3 pT5">
            <select name="deptState" id="deptStateID" class="form-control form-control-lg">
                <option value="">Select State</option>
                {!! $stateDropstateDrop !!}
            </select>
        </div>
        <div class="col-2 pT5">
            <a href="javascript:;" id="ajaxSubmit" class="btn btn-lg btn-primary w100"
                ><i class="fa fa-search"></i></a>
        </div>
    </div>
</div>
<div id="ajaxSearch"></div>
