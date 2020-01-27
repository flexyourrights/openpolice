<!-- resources/views/vendor/openpolice/nodes/1780-mfa-instructions.blade.php -->
<div>
    <div class="slCard mT20">
        <h3 class="m0" style="color: #2B3493;">
            <i class="fa fa-key mR5" aria-hidden="true"></i>
            To get full access to this complaint report, choose one of these options:
        </h3>
    </div>
    <div class="row">
        <div class="col-6">
            <div id="keyOpt1" class="slCard mT20">
                <h3 class="mT0">Option #1: Enter your emailed Key Code</h3>
                <div class="pT5 mB10 pL10">{!! $mfa !!}</div>
                <p>
                If your code has expired or doesn’t work, please click the button 
                below to get a new code sent to <b>{{ $user->email }}</b>.
                </p>
                <div class="pL10">
                    <a href="?resend=access" class="btn btn-secondary" style="width: 190px;"
                        ><i class="fa fa-envelope-o mR5" aria-hidden="true"></i> 
                        Email New Key Code</a>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div id="keyOpt2" class="slCard mT20">
                <h3 class="mT0">Option #2: Create an account</h3>
                <p>
                Creating an account makes it easier for you to access and manage complaint 
                reports. If the account for <b>{{ $user->email }}</b> has not been setup 
                yet, you can use the <a href="/password/reset">password reset tool</a> 
                to gain access to it by email.
                </p>
                <p>
                If your office needs to change the email associated with 
                its account, please <a href="/contact">contact us</a>.
                </p>
            </div>
        </div>
    </div>
</div>

<div class="alert alert-danger fade in alert-dismissible show" 
    style="padding: 10px 15px; margin: 20px 0px 10px 0px; color: #a94442;">
    The public view below does not contain any personal contact information. 
    But you can access that using the above instructions.
</div>

<style>
#keyOpt1, #keyOpt2 { min-height: 300px; }
@media screen and (max-width: 992px) {
    #keyOpt1, #keyOpt2 { min-height: 100px; }
}
#keySry { color: #a94442; margin-right: 10px; }
</style>


<?php /*
<h3 class="m0" style="color: #2B3493;">You have used a custom link for special access</h3> 
<p>
to view the full details of this record. To finished gaining full access, either enter the Key Code sent to you, or 
<a href="/login">login</a> using <b>{{ $user->email }}</b>. 
</p><p>
<i class="fa fa-key fa-flip-horizontal icoHuge float-right mL5 mB10" align="left" aria-hidden="true"></i>
If the account for <span class="slBlueDark">{{ $user->email }}</span> has not really been setup yet, you can use the 
<a href="/password/reset">reset password tool</a> to gain access to it by email.
This will also make it easier for you to access full records in the future.
</p>
<p>
<b class="slBlueDark">For security, your key code expires after 7 days.</b>
Only the most recently emailed key code will work, only for the week after it is sent.
</p><p>
<a href="?resend=access" class="btn btn-secondary mL10 mT10 mB10 disBlo float-right"
    ><i class="fa fa-envelope-o mR5" aria-hidden="true"></i> Email Fresh Key Code</a>
If your key code has expired or does not work, please click this button 
to have a fresh key code quickly sent to <span class="slBlueDark">{{ $user->email }}</span>.
</p><p>
Please <a href="/contact">contact us</a> if your office needs to change this privileged access 
to a different email address, or primary contact for OpenPolice.org.
</p>
*/ ?>