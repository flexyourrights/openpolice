<!-- resources/views/vendor/openpolice/nodes/1712-report-inc-staff-tools-report-upload.blade.php -->

<form id="complaintAdminUpload" enctype="multipart/form-data" method="post" 
    action="/dash/complaint/read-{{ $complaintRec->com_id 
        }}?view=reportUp&refresh=1{{ $GLOBALS['SL']->getReqParams() }}" >
<input type="hidden" name="_token" value="{{ csrf_token() }}">
<input type="hidden" name="cid" value="{{ $complaintRec->com_id }}">

<div class="nodeAnchor"><a name="reportUpload"></a></div>
<h4>Upload Reports</h4>
<p>
    Upload cleaner PDFs for the public and 
    sensitive versions of this complaint.
</p>
@foreach ($reportUploadTypes as $i => $type)
    <div class="row mB10">
        <div class="col-8">
            <label class="finger">
                <div class="disIn mR5">
                    <input type="radio" class="slTab ntrStp"
                        name="reportUpType" id="reportUpType{{ $i }}" 
                        value="{{ $type[0] }}" autocomplete="off" >
                </div> {{ $type[1] }}
            </label>
        </div>
        <div class="col-4">
        @if (file_exists($reportUploadFolder 
            . $complaintRec->com_id . '-' . $type[0] . '.pdf'))
            
        @endif
        </div>
    </div>
@endforeach

<input type="file" name="reportToUpload" id="reportToUploadID" 
    class="form-control form-control-lg">

<div class="mT20 mB20">
    <input type="submit" class="btn btn-lg btn-primary" id="stfBtn9"
        onMouseOver="this.style.color='#2b3493';" 
        onMouseOut="this.style.color='#FFF';" style="color: #FFF;" 
        value="Upload Report" >
</div>

</form>

<script type="text/javascript"> $(document).ready(function(){

    function postToolboxAdminUpload() {
        if (document.getElementById('complaintToolbox')) {
/*
            document.getElementById('complaintToolbox').innerHTML = getSpinner();
            var actionUrl = "/complaint-toolbox?cid={{ $complaintRec->com_id }}&ajax=1&refresh=1&save=1";
            var formData = new FormData(document.getElementById("comUpdate"));
            window.scrollTo(0, 0);
            $.ajax({
                url: actionUrl,
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
*/
        }
        return false;
    }

    $("#complaintAdminUpload").submit(function( event ) {
        event.preventDefault();
        return postToolboxAdminUpload();
    });

}); </script>