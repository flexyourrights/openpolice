<!-- resources/views/vendor/openpolice/nodes/145-dept-search.blade.php -->

<input type="hidden" name="n145fld" id="n145FldID" value="">
<div class="nFld mTn20">
    <div class="row mB10">
        <div class="col-md-9">
            <input type="text" name="deptNameIn" id="deptNameInID" value="{{ $IncAddressCity }}" 
                class="form-control fingerTxt">
        </div>
        <div class="col-md-2">
            <select name="deptState" id="deptStateID" class="form-control fingerTxt">
                <option value="">Select State</option>
                {!! $stateDropstateDrop !!}
            </select>
        </div>
        <div class="col-md-1">
            <a href="javascript:void(0)" id="ajaxSubmit" class="btn btn-primary w100 p15"
                ><h2><i class="fa fa-search"></i></h2></a>
        </div>
    </div>
</div>
<div id="ajaxSearch"></div>
