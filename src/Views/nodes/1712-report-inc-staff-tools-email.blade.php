<!-- resources/views/vendor/openpolice/nodes/1712-report-inc-staff-tools-email.blade.php -->

<div class="disBlo pB30">
    Select which email template you want to send:<br />
    <select name="email" id="emailID" autocomplete="off" 
        class="form-control form-control-lg">
        <option value="" >No email right now</option>
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
                @if (!in_array(
                        substr($email->email_name, 0, 2), 
                        [ '01', '02', '04', '06', '21' ]
                    ))
                    <option value="{{ $email->email_id }}" 
                        @if ($emailID == $email->email_id) SELECTED @endif
                        >{{ $email->email_name }} ({{ $email->email_type }})</option>
                @endif
            @endif
        @empty
        @endforelse
    </select>
    <div id="emailChooseDept" 
        class="@if ($emailID == 12) disBlo @else disNon @endif ">
        @if (sizeof($comDepts) > 0)
            <div class="pT15 pB10">Select the investigative agency:</div>
            <select name="d" id="emailDeptID" autocomplete="off" 
                class="form-control form-control-lg">
            @forelse ($comDepts as $cnt => $dept)
                <option value="{{ $dept['id'] }}"
                    @if ($dept['id'] == $deptID) SELECTED @endif
                    >{{ $dept['name'] }}</option>
            @empty
            @endforelse
            </select>
        @else
            <input name="d" id="emailDeptID" type="hidden" 
                value="{{ $comDepts[0]['id'] }}" >
        @endif
    </div>

    <div id="emailFormWrap" class="w100 pB15">
    @if (intVal($emailID) > 0 && sizeof($currEmail) > 0)
        {!! view(
            'vendor.openpolice.nodes.1712-report-inc-staff-tools-email-form', 
            [
                "emailID"           => $emailID,
                "currEmail"         => $currEmail, 
                "complaintRec"      => $complaintRec, 
                "emailID"           => $emailID,
                "emailsTo"          => $emailsTo,
                "deptID"            => $deptID,
                "reportUploadTypes" => $reportUploadTypes
            ]
        )->render() !!}
    @endif
    </div>
</div>
