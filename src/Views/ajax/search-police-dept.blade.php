<!-- resources/views/vendor/openpolice/ajax/search-police-dept.blade.php -->

@if (!isset($depts) || !$depts || sizeof($depts) == 0)
    <div class="mB20">
        <div class="alert alert-warning" role="alert">
            <p><b>No police departments found by searching 
            <span class="slBlueDark">"{{ $search }}"</span> 
            @if (trim($stateName) != '') 
                @if (trim($stateName) == 'US') 
                    in <span class="slBlueDark">Federal Law Enforcement</span>.
                @else
                    in <span class="slBlueDark">{{ $stateName }}</span>.
                @endif
            @else 
                <span class="mLn5">.</span>
            @endif
            Please type something else in the search bar above.</b></p>
        </div>
    </div>
@else
    @foreach($depts as $i => $dept)
        @if ($GLOBALS["SL"]->REQ->has('loadNewFixDept'))
            <div class="mT20">
                <a href="javascript:;" onClick="return loadNewFixDept({{ 
                    $dept->dept_id }}, {{ json_encode($dept->dept_name) }});"
                    class="btn btn-secondary btn-block taL"
                    ><i class="fa fa-plus-circle mR3" aria-hidden="true"></i>
                    {{ $dept->dept_name }}</a>
            </div>
            <div class="m5" style="margin-left: 12px;">
                <a href="/dept/{{ $dept->dept_slug }}" target="_blank" 
                    ><i class="fa fa-external-link" aria-hidden="true"></i> 
                    /{{ $dept->dept_slug }}</a>
            </div>
        @else
            <div class="deptWrap">
            @if ($dept->dept_type == 366) 
                <h3 @if ($i == 0) class="mT0" @endif >{{ $dept->dept_name }}</h3>
                <div class="mTn10"><b><i>(Federal)</i></b></div>
            @else
                <h3>{{ str_replace('Department', 'Dept.', $dept->dept_name) }}</h3>
            @endif
                <div class="row mT10 mB10 @if ($i == 0) mT0 @endif ">
                    <div class="col-sm-9 pB20">
                        {!! $GLOBALS["SL"]->printRowAddy($dept, 'dept_') !!}
                        @if (trim($dept->dept_address_county) != '') 
                            <br />{{ $dept->dept_address_county }} County
                        @endif
                    </div>
                    <div class="col-sm-3">
                        <a id="dept{{ $dept->dept_id }}" href="javascript:;" 
                            class="deptLoad btn btn-lg btn-primary btn-block taC" 
                            >Select</a>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
@endif

@if (!$GLOBALS["SL"]->REQ->has('loadNewFixDept'))
    <p>&nbsp;</p>
    <h2>Can't find the right department?</h2>
    <ul>
        <li>Type the name of the county...</li>
        <li>Make sure you select the right state</li>
        <li>Double-check spelling of the department name</li>
    </ul>
    <p>
        If you can't find the right department, 
        <a id="hidivBtnAddNewDept" class="hidivBtn" 
            href="javascript:;">click here to add it to our database</a>.
    </p><p>
        If you don't know the department name, 
        <a class="deptLoad" id="dept18124" href="javascript:;"
            >click here to use a temporary place holder</a>.
    </p>
        
    <div id="hidivAddNewDept" class="slCard disNon mT20 mB20">
        <div id="addNewDeptError" class="disNon alert alert-danger" role="alert">
            <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> 
            Please type the name of the law enforcement department, and select the state.
        </div>
        <div class="row">
            <div class="col-md-7 pB20">
                <label for="newDeptNameID">
                    Department Name <span class="red">*required</span>
                </label>
                <div class="nFld">
                    <input id="newDeptNameID" name="newDeptName" type="text" 
                        value="" class="form-control form-control-lg" >
                </div>
            </div>
            <div class="col-md-5 pB20">
                <label for="newDeptAddressStateID">
                    State <span class="red">*required</span>
                </label>
                <div class="nFld">
                    <select id="newDeptAddressStateID" 
                        name="newDeptAddressState" 
                        class="form-control form-control-lg" 
                        autocomplete="off" >{!! $newDeptStateDrop !!}
                    </select>
                </div>
            </div>
        </div>
        <div class="row mB20">
            <div class="col-md-7"></div>
            <div class="col-md-5">
                <input id="newDeptSubmit" type="button" 
                    class="btn btn-primary btn-lg w100" 
                    value="Add New Department">
            </div>
        </div>
    </div>
@endif

<div class="p10"></div>
