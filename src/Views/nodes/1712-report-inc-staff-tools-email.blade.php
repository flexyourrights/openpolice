<!-- resources/views/vendor/openpolice/nodes/1712-report-inc-staff-tools-email.blade.php -->

Select which email template you want to send:<br />
<select name="email" id="emailID" autocomplete="off" 
    class="form-control form-control-lg">
    <option value="" > No email right now</option>
    <?php $set = '00'; ?>
    @forelse ($emailList as $i => $email)
        @if ($email->email_type != 'Blurb')
            <?php
            $currSet = substr($email->email_name, 0, 1) . '0';
            if ($set != $currSet) {
                $set = $currSet;
                echo '<option disabled ></option>';
            }
            ?>
            <option value="{{ $email->email_id }}" 
                @if ($emailID == $email->email_id) SELECTED @endif
                >{{ $email->email_name }} 
                - {{ $email->email_type }}</option>
        @endif
    @empty
    @endforelse
</select>

<div id="emailFormWrap" class="w100">
@if (intVal($emailID) > 0 && sizeof($currEmail) > 0)
    {!! view(
        'vendor.openpolice.nodes.1712-report-inc-staff-tools-email-form', 
        [
            "currEmail"         => $currEmail, 
            "complaintRec"      => $complaintRec, 
            "emailID"           => $emailID,
            "emailsTo"          => $emailsTo,
            "reportUploadTypes" => $reportUploadTypes
        ]
    )->render() !!}
@endif
</div>
