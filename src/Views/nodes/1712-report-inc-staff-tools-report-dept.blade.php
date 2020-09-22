<!-- resources/views/vendor/openpolice/nodes/1712-report-inc-staff-tools-report-department.blade.php -->

<form name="fixDeptsForm" id="fixDeptsFormID">
<input type="hidden" name="_token" value="{{ csrf_token() }}">
<input type="hidden" name="cid" value="{{ $complaintRec->com_id }}">
<input type="hidden" name="fixDepts" value="1">
<input type="hidden" name="refresh" value="1">
<input type="hidden" name="ajax" value="1">
{!! $GLOBALS['SL']->getReqHiddenInputs() !!}

<div class="nodeAnchor"><a name="reportUpload"></a></div>
<h4>Associated Department(s)</h4>
<p>
    Add or remove departments responsible for this complaint.
</p>
<div class="row mB10">
    <div class="col-md-6">
@forelse ($GLOBALS["SL"]->x["depts"] as $deptID => $d)
        <div class="mB10">
            <label class="finger">
                <div class="disIn mR5">
                    <input type="checkbox" class="slTab ntrStp"
                        name="keepDepts[]" id="keepDept{{ $deptID }}" 
                        value="{{ $deptID }}" autocomplete="off" CHECKED >
                </div> {!! $d["deptRow"]->dept_name !!} 
                @if (isset($d["deptRow"]->dept_address_state)
                    && trim($d["deptRow"]->dept_address_state) != '')
                    , {!! $d["deptRow"]->dept_address_state !!}
                @endif
            </label>
        </div>
@empty
@endforelse
        <div id="fixDeptsAddNew" class="disNon mB10">
            <label class="finger">
                <div class="disIn mR5">
                    <input type="checkbox" class="slTab ntrStp"
                        name="keepDeptNew" id="keepDeptNewID" 
                        value="0" autocomplete="off" CHECKED >
                </div> 
                <div id="fixDeptsAddNewName" class="disIn"></div>
            </label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="row">
            <div class="col-8">
                <div class="deptNameInWrap">
                    <a href="javascript:;" id="ajaxSubmitFixDepts"
                        ><i class="fa fa-search"></i></a>
                    <input type="text" autocomplete="off"
                        name="fixDeptsSearchIn" id="fixDeptsSearchInID" 
                        class="form-control form-control-lg w100 ui-autocomplete-input">
                </div>
            </div>
            <div class="col-4">
                <select name="deptStateSearch" id="deptStateSearchID" 
                    class="form-control form-control-lg">
                    <option value="">Select State</option>
                    {!! $GLOBALS["SL"]->states->stateDrop($incidentState, true) !!}
                </select>
            </div>
        </div>
        <div id="ajaxSearchFixDepts">

        </div>
    </div>
</div>

<div class="mT15">
    <input type="submit" id="stfBtnDept"
        class="btn btn-lg btn-primary" style="color: #FFF;"
        onMouseOver="this.style.color='#2b3493';" 
        onMouseOut="this.style.color='#FFF';"
        value="Apply Department Changes">
</div>

</form>

<script type="text/javascript">

function loadNewFixDept(deptID, deptName) {
    if (document.getElementById('keepDeptNewID') && document.getElementById('fixDeptsAddNewName')) {
        document.getElementById('keepDeptNewID').value=deptID;
        document.getElementById('keepDeptNewID').checked=true;
        document.getElementById('fixDeptsAddNewName').innerHTML=deptName;
        document.getElementById('fixDeptsAddNew').style.display='block';
        document.getElementById('ajaxSearchFixDepts').innerHTML="";
        return true;
    }
    return false;
}

var holdSearch = false;
$(document).ready(function(){ 

    function postToolboxFixDept() {
        if (document.getElementById('complaintToolbox')) {
            var formData = new FormData($('#fixDeptsFormID').get(0));
            document.getElementById('complaintToolbox').innerHTML = getSpinner();
            window.scrollTo(0, 0);
            $.ajax({
                url: "/complaint-toolbox",
                type: "POST", 
                data: formData, 
                contentType: false,
                processData: false,
                success: function(data) {
                    $("#complaintToolbox").empty();
                    $("#complaintToolbox").append(data);
                },
                error: function(xhr, status, error) {
                    $("#complaintToolbox").append("<div>(error - "+xhr.responseText+")</div>");
                }
            });
        }
        return false;
    }

    $("#stfBtnDept").click(function() {
        postToolboxFixDept();
    });

    $("#fixDeptsFormID").submit(function( event ) {
        postToolboxFixDept();
        event.preventDefault();
    });


    function submitAjaxSearchFixDepts() {
        document.getElementById('ajaxSearchFixDepts').innerHTML=getSpinner();
        document.getElementById("ajaxSearchFixDepts").style.display="block";
        setTimeout(function() {
            var loadUrl = "/ajax/?policeDept="+encodeURIComponent(document.getElementById("fixDeptsSearchInID").value)+"&policeState="+encodeURIComponent(document.getElementById("deptStateSearchID").value)+"&loadNewFixDept=1";
            $("#ajaxSearchFixDepts").load(loadUrl);
            return true;
        }, 800);
    }
    $(document).on("click", "#ajaxSubmitFixDepts", function() {
        return submitAjaxSearchFixDepts();
    });
    $(document).on("keyup", "#fixDeptsSearchInID", function() {
        if (!holdSearch) {
            holdSearch = true;
            submitAjaxSearchFixDepts();
            setTimeout("holdSearch=false", 2000);
        }
        return true;
    });
    
});
</script>
<style>
#ajaxSubmitFixDepts {
    position: absolute;
    top: 12px;
    left: 30px;
}
#fixDeptsSearchInID {
    padding-left: 40px;
}
</style>