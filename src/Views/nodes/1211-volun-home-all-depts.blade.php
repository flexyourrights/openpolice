<!-- resources/views/vendor/openpolice/nodes/1211-volun-home-all-depts.blade.php -->
@if ($GLOBALS["SL"]->REQ->has('s') && trim($GLOBALS["SL"]->REQ->get('s')) != '')
    <h2 class="m0">Department Search: 
    <a href="javascript:;" class="admSrchFldFocus slBlueDark">{{ $GLOBALS["SL"]->REQ->get('s') }}</a></h2>
    <a href="/dash/volunteer">Clear Search & Show All Departments</a>
@else
    <h2 class="mT0">All Departments</h2>
    <div class="row mB10">
        <div class="col-md-4">
            <i class="fa fa-filter" aria-hidden="true"></i> Filter by<br />
            <select name="deptState" id="deptStateID" class="form-control input-lg" onChange="runDeptSearch();">
                <option value="" @if (!$GLOBALS["SL"]->REQ->has('state')) SELECTED @endif >select state</option>
                {!! $GLOBALS["SL"]->states->stateDrop(($GLOBALS["SL"]->REQ->has('state')) 
                    ? $GLOBALS["SL"]->REQ->get('state') : '') !!}
            </select>
        </div><div class="col-md-4">
            <i class="fa fa-sort-amount-asc" aria-hidden="true"></i> Sort by<br />
            <select name="deptSort" id="deptSortID" class="form-control input-lg" onChange="runDeptSearch();">
                <option value="recent" @if (!isset($viewType) || $viewType == 'recent') SELECTED @endif 
                    >Recently Verified</option>
                <option value="best" @if (isset($viewType) && $viewType == 'best') SELECTED @endif 
                    >Best Departments</option>
                <option value="name" @if (isset($viewType) && $viewType == 'name') SELECTED @endif 
                    >Department Name</option>
                <option value="city" @if (isset($viewType) && $viewType == 'city') SELECTED @endif 
                    >State, City</option>
            </select>
        </div><div class="col-md-4">
        </div>
    </div>
@endif

<div class="w100 slGrey" style="padding: 0px 15px 5px 15px;">
    <div class="pull-right deptRgtCol"><nobr>Accessibility Score</nobr><br /><i>Last Verified</i></div>
    Police Department Name,<br /><i>City, State</i>
</div>
<div id="deptListGroup" class="list-group taL">
    @forelse ($deptRows as $i => $dept)
        @if ($i < 500)
            <a class="list-group-item" href="/dashboard/start-{{ $dept->DeptID }}/volunteers-research-departments">
                <div class="pull-right deptRgtCol">
                    @if (intVal($dept->DeptScoreOpenness) > 0)
                        <h3 class="m0">{{ $dept->DeptScoreOpenness }}</h3>
                        {!! view('vendor.openpolice.volun.volunteer-recent-edits', [
                            "deptID" => $dept->DeptID ])->render() !!}
                        @if (trim($dept->DeptVerified) != '' && trim($dept->DeptVerified) != '0000-00-00 00:00:00')
                            <span class="gryA"><i>{{ date("n/j/y", strtotime($dept->DeptVerified)) }}</i></span>
                        @endif
                    @else
                        <div class="gryA"><i class="fa fa-star"></i></div>
                    @endif
                </div>
                <h3 class="m0">{{ str_replace('Department', 'Dept', $dept->DeptName) }}</h3>
                <div class="gry9"><i>
                @if (!isset($dept->DeptAddressState) || trim($dept->DeptAddressState) == '' 
                    || $dept->DeptAddressState == 'US') Federal
                @else {{ $dept->DeptAddressCity }}, {{ $dept->DeptAddressState }} @endif
                </i></div>
            </a>
        @endif
    @empty
        <a class="list-group-item" href="javascript:;">No departments found.</a>
    @endforelse
</div>

<div class="row2 p15">
<a href="javascript:;" id="hidivBtnNewDept" class="hidivBtn"><h3 class="m0">
    Need to add a new police department to the database?</h3></a>
<div id="hidivNewDept" class="disNon pT20 pB20">
    <div class="alert alert-danger" role="alert">
        <b>Before adding a department, please search for ALL each words within the department name one at a time. 
        Also make sure you are searching in the right state.</b>
    </div>
    </form>
    <form name="volunAddNewDept" action="/dash/volunteer?newDept=1" method="post" onSubmit="return checkNewDept();">
    <input type="hidden" id="csrfTok" name="_token" value="{{ csrf_token() }}">
    <div class="row mB20 f18">
        <div class="col-md-8">
            <fieldset class="form-group">
                <label for="deptNameID">Department Name</label>
                <input id="deptNameID" name="deptName" type="text" value="" class="form-control input-lg" >
            </fieldset>
        </div>
        <div class="col-md-4">
            <fieldset class="form-group">
                <label for="DeptAddressStateID">State</label>
                <select id="DeptAddressStateID" name="DeptAddressState" class="form-control input-lg" 
                    autocomplete="off" >{!! $GLOBALS['SL']->states->stateDrop('', true) !!}
                </select>
            </fieldset>
        </div>
    </div>
    <center><input type="submit" class="btn btn-lg btn-primary" value="Add New Department"></center>
</div>
</div>
<script type="text/javascript">
function checkNewDept() {
    if (document.getElementById('deptNameID').value.trim() == '' || document.getElementById('DeptAddressStateID').value.trim() == '') {
        alert('Please type in a police department name and its state.');
        return false;
    }
    return true;
}
/* s='+document.getElementById('admSrchFld').value+'&
function tweakSearchForm() {
    if (document.getElementById('dashSearchFrmID')) {
        document.getElementById('dashSearchFrmID').action+='?state='+document.getElementById('deptStateID').value+'&sort='+document.getElementById('deptSortID').value;
    }
}
setTimeout("tweakSearchForm()", 10);
*/
function runDeptSearch() {
    window.location='?state='+document.getElementById('deptStateID').value+'&sort='+document.getElementById('deptSortID').value;
}
</script>
