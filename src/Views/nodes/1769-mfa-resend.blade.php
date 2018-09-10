<!-- resources/views/vendor/openpolice/nodes/1769-mfa-resend.blade.php -->
<p>
Only the most recently emailed key code will work, only for the week after it is sent.
For security, your key code expires after 7 days. 
</p><p>
If your key code has expired or does not work, please click the following button 
to have a fresh key code quickly sent to <b>{{ $user->email }}</b>: <br /><br />
<a href="?resend=access" class="btn btn-sm btn-secondary mL10 mT20 float-right">Send Fresh Key Code</a>
</p>