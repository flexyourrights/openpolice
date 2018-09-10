<!-- resources/views/vendor/openpolice/ajax/search-police-dept.blade.php -->

@if (!isset($depts) || !$depts || sizeof($depts) == 0)
    <div class="alert alert-warning fPerc125" role="alert">
        <p><b>No police departments found by searching <span class="slBlueDark">"{{ $search }}"</span> in 
        <span class="slBlueDark">{{ $stateName }}</span>. Please type something else in the search bar above.</b></p>
    </div>
@else
    <div class="jumbotron" style="padding: 10px 20px;">
    @foreach($depts as $i => $dept)
        @if ($dept->DeptType == 366) 
            <h2 @if ($i == 0) class="mT0" @endif >{{ $dept->DeptName }}</h2>
            <div class="mTn10"><b><i>(Federal)</i></b></div>
        @else
            <h2>{{ str_replace('Department', 'Dept', ucwords(strtolower($dept->DeptName))) }}</h2>
        @endif
        <div class="row mT10 mB10 @if ($i == 0) mT0 @endif ">
            <div class="col-md-3 pT5 pB5">
                <a href="javascript:;" class="deptLoad btn btn-xl btn-primary btn-block taC" 
                    id="dept{{ $dept->DeptID }}">Select</a>
            </div>
            <div class="col-md-9 f16">
                {{ $dept->DeptAddress }}
                @if (trim($dept->DeptAddress2) != '')
                    <br />{{ $dept->DeptAddress2 }}
                @endif
                <br />{{ $dept->DeptAddressCity }}, {{ $dept->DeptAddressState }} {{ $dept->DeptAddressZip }}
                @if (trim($dept->DeptAddressCounty) != '')
                    <br />{{ $dept->DeptAddressCounty }} County
                @endif
            </div>
        </div>
        <hr>
    @endforeach
    </div>
@endif

<div class="gry6 fPerc125">
    <p>
        Tips for finding the right department:
    </p>
    <ul>
        <li>Try typing the name of the county instead of the city</li>
        <li>Make sure you select the right state</li>
        <li>Double-check spelling of the department name</li>
    </ul>
    <p>
        If you are not sure what police department was involved, 
        <a href="javascript:;" class="deptLoad" id="dept18124"
            >click here to select a department place holder</a>.
    </p><p>
        If you still can't find the right department, 
        <a id="addNewDept" href="javascript:;;">click here to add it to our database</a>.
    </p>
</div>
    
<div id="addNewDeptForm" class="disNon mT20 pT20">
    <div id="addNewDeptError" class="disNon alert alert-danger f20" role="alert">
        <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> 
        Please type in the name of the new department you need to add, and select the appropriate state.
    </div>
    <div class="row mB20 f18">
        <div class="col-md-6">
            <fieldset class="form-group">
                <label for="newDeptNameID">Department Name <span class="red f12">* required</span></label>
                <input id="newDeptNameID" name="newDeptName" type="text" value="" class="form-control form-control-lg" >
            </fieldset>
        </div>
        <div class="col-md-3">
            <fieldset class="form-group">
                <label for="newDeptAddressStateID">State <span class="red f12">* required</span></label>
                <select id="newDeptAddressStateID" name="newDeptAddressState" class="form-control form-control-lg" 
                    autocomplete="off" >
                    {!! $newDeptStateDrop !!}
                </select>
            </fieldset>
        </div>
        <div class="col-md-3 pT20">
            <input id="newDeptSubmit" type="button" class="btn btn-lg btn-primary f20 mT5" value="Add New Department">
        </div>
    </div>
</div>

<div class="p10"></div>

<style>
#dept18124 { font-size: 14pt; }
@media screen and (max-width: 768px) {
    #dept18124 { font-size: 14pt; }
}
@media screen and (max-width: 480px) {
    #dept18124 { font-size: 14pt; }
}
</style>