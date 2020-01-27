<!-- resources/views/vendor/openpolice/nodes/1712-report-inc-staff-tools-report-upload.blade.php -->

<form enctype="multipart/form-data" action="/dash/complaint/read-{{ 
    $complaintRec->com_id }}?view=reportUp&refresh=1{{ 
    $GLOBALS['SL']->getReqParams() }}" method="post" >
<input type="hidden" id="csrfTok" name="_token" value="{{ csrf_token() }}">
<input type="hidden" name="cID" value="{{ $complaintRec->com_public_id }}">

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
        @if (file_exists($reportUploadFolder . $complaintRec->com_id . '-' . $type[0] . '.pdf'))
            
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