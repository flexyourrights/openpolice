<!-- resources/views/vendor/openpolice/nodes/1712-report-inc-staff-tools-email.blade.php -->

<div class="disBlo pB30">
    Select Email Template<br />
    <select name="email" id="emailID" autocomplete="off" 
        class="form-control form-control-lg">
        <option value="" >No email right now</option>
    @forelse ($emailList as $i => $email)
        <?php $emaID2 = substr($email->email_name, 0, 2); ?>
        <?php $emaID3 = substr($email->email_name, 2, 1); ?>
        @if ($email->email_type != 'Blurb')
            @if (in_array($emaID2, [ '01', '10', '16' ]) && $emaID3 == '.')
                <option disabled ></option>
            @endif
            @if ($emaID2 != '01' || $email->email_id == 36)
                <option value="{{ $email->email_id }}" 
                    @if ($emailID == $email->email_id) SELECTED @endif
                    >{{ $email->email_name }} 
                    ({{ str_replace('Oversight', 'IA', $email->email_type) }})
                </option>
            @endif
        @endif
    @empty
    @endforelse
    </select>
    <div id="emailChooseDept" 
        class="@if ($emailID == 12) disBlo @else disNon @endif ">
        @if (sizeof($comDepts) > 0)
            <div class="pT15 pB10">Select Investigative Agency</div>
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
