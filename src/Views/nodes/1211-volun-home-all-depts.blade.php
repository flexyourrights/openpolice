<!-- resources/views/vendor/openpolice/nodes/1211-volun-home-all-depts.blade.php -->

<h2 class="mT0">All Departments</h2>
<div class="row mB10">
    <div class="col-6">
        <i class="fa fa-search" aria-hidden="true"></i> Search Phrase<br />
        <input type="text" name="deptSearch" id="deptSearchID" 
            class="form-control slTab" {!! $GLOBALS["SL"]->tabInd() !!}
            @if ($GLOBALS["SL"]->REQ->has('s') 
                && trim($GLOBALS["SL"]->REQ->get('s')) != '')
                value="{!! trim($GLOBALS["SL"]->REQ->get('s')) !!}" 
            @else value="" @endif >
    </div><div class="col-6">
        <i class="fa fa-filter" aria-hidden="true"></i> Filter by<br />
        <select name="deptState" id="deptStateID" class="form-control slTab" 
            onChange="return runVolunDeptSearch();" {!! $GLOBALS["SL"]->tabInd() !!} >
        <option value="" @if (trim($state) == '') SELECTED @endif >All States</option>
        <option value="US" @if (trim($state) == 'US') SELECTED @endif >Federal</option>
        {!! $GLOBALS["SL"]->states->stateDrop($state) !!}
        </select>
    </div>
</div>
<div class="row mB10">
    <div class="col-6">
        <i class="fa fa-sort-amount-asc" aria-hidden="true"></i> Sort by<br />
        <select name="deptSort" id="deptSortID" 
            class="form-control slTab" {!! $GLOBALS["SL"]->tabInd() !!}
            onChange="return runVolunDeptSearch();">
            <option value="recent" 
                @if (!isset($viewType) || $viewType == 'recent') SELECTED @endif 
                >Recently Verified</option>
            <option value="best" 
                @if (isset($viewType) && $viewType == 'best') SELECTED @endif 
                >Best Departments</option>
            <option value="name" 
                @if (isset($viewType) && $viewType == 'name') SELECTED @endif 
                >Department Name</option>
            <option value="city" 
                @if (isset($viewType) && $viewType == 'city') SELECTED @endif 
                >State, City</option>
        </select>
    </div><div class="col-6">
        &nbsp;<br />
        <a class="btn btn-primary btn-block slTab" {!! $GLOBALS["SL"]->tabInd() !!}
            onClick="return runVolunDeptSearch();" href="javascript:;">Search</a>
    </div>
</div>

@if (sizeof($deptRows) > 0)
    {!! view(
        'vendor.openpolice.volun.dept-rows', 
        [ "deptRows" => $deptRows ]
    )->render() !!}
@else
    <a class="list-group-item" href="javascript:;">No departments found.</a>
@endif

<div class="row2 p15 mT20">
<a href="javascript:;" id="hidivBtnNewDept" class="hidivBtn"><h3 class="m0">
    Need to add a new police department to the database?</h3></a>
<div id="hidivNewDept" class="disNon pT20 pB20">
    <div class="alert alert-danger" role="alert">
        <b>
            Before adding a department, please search for
            EACH word within the department name one at a time. 
            Also make sure you are searching in the right state.
        </b>
    </div>
    </form>
    <form name="volunAddNewDept" action="/dash/volunteer?newDept=1" 
        method="post" onSubmit="return checkNewDept();">
    <input type="hidden" id="csrfTok" name="_token" value="{{ csrf_token() }}">
    <div class="row mB20 fPerc125">
        <div class="col-8">
            <fieldset class="form-group">
                <label for="deptNameID">Department Name</label>
                <input id="deptNameID" name="deptName" type="text" value="" class="form-control" >
            </fieldset>
        </div>
        <div class="col-4">
            <fieldset class="form-group">
                <label for="DeptAddressStateID">State</label>
                <select id="DeptAddressStateID" name="DeptAddressState" class="form-control" 
                    autocomplete="off" >{!! $GLOBALS['SL']->states->stateDrop('', true) !!}
                </select>
            </fieldset>
        </div>
    </div>
    <center><input type="submit" class="btn btn-lg btn-primary" value="Add New Department"></center>
</div>
</div>
<script id="noExtractDepts" type="text/javascript">
function checkNewDept() {
    if (document.getElementById('deptNameID').value.trim() == '' || document.getElementById('DeptAddressStateID').value.trim() == '') {
        alert('Please type in a police department name and its state.');
        return false;
    }
    return true;
}
/* s='+document.getElementById('admSrchFld').value+'&
function tweakSearchForm() {
    if (document.getElementById('dashSearchFormID')) {
        document.getElementById('dashSearchFormID').action+='?state='+document.getElementById('deptStateID').value+'&sort='+document.getElementById('deptSortID').value;
    }
}
setTimeout("tweakSearchForm()", 10);
*/
function runVolunDeptSearch() {
    window.location='?s='+encodeURI(document.getElementById('deptSearchID').value)+'&state='+document.getElementById('deptStateID').value+'&sort='+document.getElementById('deptSortID').value+'#n1757';
    return false;
}
$(document).ready(function(){
	$(document).on("keyup", "#deptSearchID", function(e) {
        if (e.keyCode == 13) {
            runVolunDeptSearch();
            return false; 
        }
    });
});
@if ($GLOBALS["SL"]->REQ->has('s') && strpos($_SERVER["REQUEST_URI"], '#n1757') === false)
    setTimeout("window.location+='#n1757'", 1);
@endif
</script>
