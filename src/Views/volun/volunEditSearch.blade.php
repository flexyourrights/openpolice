<!-- resources/views/vendor/openpolice/volun/volunEditSearch.blade.php -->

<form name="volunDeptSearch" method="get" onSubmit="subVolunDeptSearch(); return false;" class="form-inline">
<input type="hidden" name="_token" value="{{ csrf_token() }}">
Search Local Departments: 
<label class="sr-only" for="stateID">Select Your State</label>
<select id="stateID" name="state" class="form-control mL5">
    {!! $stateDrop !!}
</select> 
<input id="deptNameID" name="deptName" value="{{ $deptName }}" type="text" class="form-control" >
<a href="javascript:void(0)" onClick="return subVolunDeptSearch();" class="btn btn-lg btn-primary f16"><i class="fa fa-search"></i></a>
</form>

<script type="text/javascript">
function subVolunDeptSearch() {
    var url = '';
    if (document.getElementById('stateID').value != '') url += '/s/'+document.getElementById('stateID').value;
    if (document.getElementById('deptNameID').value != '') url += '/d/'+encodeURIComponent(document.getElementById('deptNameID').value);
    if (url == '') url = '/all';
    window.location = '/dashboard/volunteer'+url;
    return true;
}
</script>
