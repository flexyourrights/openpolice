<!-- resources/views/vendor/openpolice/nodes/2850-report-inc-owner-update-form.blade.php -->

<form method="post" name="comUpdate" id="comUpdateID">
<input type="hidden" id="csrfTok" name="_token" value="{{ csrf_token() }}">
<input type="hidden" name="cid" value="{{ $complaint->com_id }}">
<input type="hidden" name="ajax" value="1">
<input type="hidden" name="refresh" value="1">
<input type="hidden" name="ownerUpdate" value="1">
<p><hr></p>
<h4>Update Your Complaint Status</h4>
{!! view(
    'vendor.openpolice.nodes.1712-report-inc-tools-progress-dates', 
    [
        "complaint"            => $complaint,
        "comDepts"             => $comDepts,
        "oversightDateLookups" => $oversightDateLookups
    ]
)->render() !!}
<div class="nFld mT10 mB20">
    Notes about the status of this complaint:<br />
    <textarea name="overNote" class="w100 mT5"></textarea>
    <small class="slGrey mTn5">
        This is for administrators of OpenPolice.org. 
        We will not make it public.
    </small>
</div>
<center><input value="Save Status Changes" id="ownBtn7"
    type="button" class="btn btn-lg btn-primary"></center>
<div class="p20"></div>
</form>

<script type="text/javascript"> $(document).ready(function(){

function postToolboxUpdateStatus() {
    if (document.getElementById('complaintToolbox')) {
        var formData = new FormData($('#comUpdateID').get(0));
        document.getElementById('complaintToolbox').innerHTML = getSpinner();
        window.scrollTo(0, 0);
        $.ajax({
            url: "/complaint-toolbox",
            type: "POST", 
            data: formData, 
            contentType: false,
            processData: false,
            //headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
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

$("#ownBtn7").click(function() {
    postToolboxUpdateStatus();
});

$("#comUpdateID").submit(function( event ) {
    postToolboxUpdateStatus();
    event.preventDefault();
});

}); </script>
