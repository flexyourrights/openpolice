<!-- resources/views/vendor/openpolice/nodes/145-dept-search.blade.php -->

<input type="hidden" name="n{{ $nID }}fld" id="n{{ $nID }}FldID" value="">
<div class="mTn20"><div class="nFld">
    <div class="row mB10">
        <div class="col-sm-6 pB20">
            <input type="text" name="deptNameIn" id="deptNameInID" value="{{ $IncAddressCity }}" 
                class="form-control form-control-lg">
        </div>
        <div class="col-sm-4 pB20">
            <select name="deptState" id="deptStateID" class="form-control form-control-lg">
                <option value="">Select State</option>
                {!! $stateDropstateDrop !!}
            </select>
        </div>
        <div class="col-sm-2 pB20">
            <a href="javascript:;" id="ajaxSubmit" class="btn btn-lg btn-secondary btn-block"
                ><i class="fa fa-search"></i></a>
        </div>
    </div>
</div></div>
<div id="ajaxSearch"></div>
