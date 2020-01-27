<!-- resources/views/vendor/openpolice/nodes/145-dept-search.blade.php -->

<input type="hidden" name="n{{ $nID }}fld" id="n{{ $nID }}FldID" value="">
<div class="mTn20"><div class="nFld">
    <div class="row mB10">
        <div class="col-8 pB20">
            <div class="deptNameInWrap">
                <a href="javascript:;" id="ajaxSubmit"><i class="fa fa-search"></i></a>
                <input type="text" name="deptNameIn" id="deptNameInID" 
                    value="{{ $incAddressCity }}" class="form-control form-control-lg w100">
            </div>
        </div>
        <div class="col-4 pB20">
            <select name="deptState" id="deptStateID" class="form-control form-control-lg">
                <option value="">Select State</option>
                {!! $stateDropstateDrop !!}
            </select>
        </div>
    </div>
</div></div>
<div id="ajaxSearch"></div>
