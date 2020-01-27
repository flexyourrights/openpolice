<!-- resources/views/vendor/openpolice/nodes/1781-mfa-instructions2.blade.php -->
<p>&nbsp;</p>
<p>
This is a one-time code that will expire after 7 days. If your 
code has expired or doesn’t work, please click the button below 
to get a fresh code sent to <b>{{ $user->email }}</b>.
</p>
<center><a href="?resend=access" class="btn btn-secondary mT10 mB10"
    ><i class="fa fa-envelope-o mR5" aria-hidden="true"></i> 
    Email Fresh Key Code</a></center>
