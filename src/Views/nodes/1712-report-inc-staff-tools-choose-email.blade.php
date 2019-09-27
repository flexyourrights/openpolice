<!-- resources/views/vendor/openpolice/nodes/1712-report-inc-staff-tools-choose-email.blade.php -->
Select which email template you want to send:<br />
<select name="email" id="emailID" class="form-control form-control-lg mB20" autocomplete="off" 
    onChange="window.location='/complaint/read-{{ $complaintRec->ComPublicID 
        }}?email='+this.value+'&refresh=1#emailer';">
    <option value="" > No email right now</option>
    @forelse ($emailList as $i => $email)
        @if ($email->EmailType != 'Blurb')
            <option value="{{ $email->EmailID }}" 
                @if ($emailID == $email->EmailID) SELECTED @endif
                >{{ $email->EmailName }} - {{ $email->EmailType }}</option>
        @endif
    @empty @endforelse
</select>