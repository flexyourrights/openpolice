<!-- resources/views/vendor/openpolice/nodes/1712-report-inc-staff-tools-choose-email.blade.php -->
Select which email template you want to send:<br />
<select name="email" id="emailID" class="form-control form-control-lg" autocomplete="off" >
    <option value="" > No email right now</option>
    @forelse ($emailList as $i => $email)
        @if ($email->EmailType != 'Blurb')
            <option value="{{ $email->EmailID }}" @if ($emailID == $email->EmailID) SELECTED @endif
                >{{ $email->EmailName }} - {{ $email->EmailType }}</option>
        @endif
    @empty @endforelse
</select>
<div class="mT20 taC">
    <a href="javascript:;" class="btn btn-lg btn-primary" id="stfBtn8" style="color: #fff;"
        onMouseOver="this.style.color='#2b3493';" onMouseOut="this.style.color='#FFF';"
        onClick="window.location='/complaint/read-{{ $complaintRec->ComPublicID 
            }}?email='+document.getElementById('emailID').value+'#emailer';"
        >Load Email Template</a>
</div>
