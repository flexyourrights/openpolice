<!-- resources/views/vendor/openpolice/nodes/1712-report-inc-staff-tools-report-upload.blade.php -->
<form enctype="multipart/form-data" action="?view=reportUp&refresh=1{{ 
    (($GLOBALS['SL']->REQ->has('frame')) ? '&frame=1' : '') }}" method="post">
<input type="hidden" id="csrfTok" name="_token" value="{{ csrf_token() }}">
<input type="hidden" name="cID" value="{{ $complaintRec->ComPublicID }}">
<a name="reportUpload"></a>

@foreach ($reportUploadTypes as $i => $type)
    <div class="row mB10">
        <div class="col-6">
            <label class="finger">
                <div class="disIn mR5">
                    <input type="radio" name="reportUpType" id="reportUpType{{ $i }}" 
                        value="{{ $type[0] }}" class="slTab ntrStp" autocomplete="off" >
                </div> {{ $type[1] }}
            </label>
        </div>
        <div class="col-6">
            @if (file_exists($reportUploadFolder . $complaintRec->ComID 
                . '-' . $type[0] . '.pdf'))

            @endif
        </div>
    </div>
@endforeach

<input type="file" name="reportToUpload" id="reportToUploadID" 
    class="form-control form-control-lg">

<div class="m20">
    <input type="submit" class="btn btn-lg btn-primary" id="stfBtn9"
    onMouseOver="this.style.color='#2b3493';" onMouseOut="this.style.color='#FFF';"
    style="color: #FFF;" value="Upload Report">
</div>

</form>