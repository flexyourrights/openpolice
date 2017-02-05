<!-- resources/views/vendor/openpolice/ajax/search-police-dept.blade.php -->

@if (sizeof($depts) == 0)
    <div class="alert alert-warning f20" role="alert">
        No police departments found when searching:
        <div class="p20"><i class="blk mR20">"<b>{{ $search }}</b>"</i> in <i class="blk mL20">{{ $stateName }}</i></div>
        Please type something else in the search bar above.<br />
    </div>
@else
    <div class="jumbotron">
    @foreach($depts as $dept)
        @if ($dept->DeptType == 366) 
            <h2>{{ $dept->DeptName }}</h2>
            <div class="mTn10"><b><i>(Federal)</i></b></div>
        @else
            <h2>{{ str_replace('Department', 'Dept', ucwords(strtolower($dept->DeptName))) }}</h2>
        @endif
        <div class="row mT10 mB10">
            <div class="col-md-3 pT5 pB5">
                <a href="javascript:void(0)" class="deptLoad btn btn-primary btn-block f26 taC" 
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

<div class="nPrompt gry9 p20">
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
        <a href="javascript:void(0)" class="deptLoad" id="dept18124">click here to select a department place holder</a>.
    </p><p>
        If you still can't find the right department, 
        <a id="addNewDept" href="javascript:void(0);">click here to add it to our database</a>.
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
                <input id="newDeptNameID" name="newDeptName" type="text" value="" class="form-control" >
            </fieldset>
        </div>
        <div class="col-md-3">
            <fieldset class="form-group">
                <label for="newDeptAddressStateID">State <span class="red f12">* required</span></label>
                <select id="newDeptAddressStateID" name="newDeptAddressState" class="form-control" autocomplete="off" >
                {!! $newDeptStateDrop !!}
                </select>
            </fieldset>
        </div>
        <div class="col-md-3 pT20">
            <input id="newDeptSubmit" type="button" class="btn btn-lg btn-primary f20 mT5" value="Add New Department">
        </div>
    </div>
</div>



