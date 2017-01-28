<!-- resources/views/vendor/openpolice/admin/complaints/email-form.blade.php -->

@extends('vendor.survloop.admin.admin')

@section('content')

<h1>
    @if ($currEmailID > 0) 
        Auto-Email: {{ $currEmail->ComEmailName }} <span class="f12">({{ $currEmail->ComEmailType }})</span>
    @else
        Create New Auto Email
    @endif
</h1>

<div class="p5"></div>

<form name="emailEditForm" action="/dashboard/complaints/email/{{ $currEmailID }}" method="post">
<input type="hidden" name="_token" value="{{ csrf_token() }}">
<input type="hidden" name="emailID" value="{{ $currEmailID }}" >

<div class="row pB20">
    <div class="col-md-3 f22">
        Auto-Email Type
    </div>
    <div class="col-md-9">
        <select name="emailType" class="form-control f22" 
            onChange="if (this.value == 'Blurb') { document.getElementById('subj').style.display='none'; } else { document.getElementById('subj').style.display='block'; }" >
            <option value="To Complainant" @if ($currEmail->ComEmailType == 'To Complainant' || trim($currEmail->ComEmailType) == '') SELECTED @endif >Sent To Complainant</option>
            <option value="To Oversight" @if ($currEmail->ComEmailType == 'To Oversight') SELECTED @endif >Sent To Oversight Agency</option>
            <option value="Blurb" @if ($currEmail->ComEmailType == 'Blurb') SELECTED @endif >Blurb used within other emails</option>
        </select>
    </div>
</div>

<div class="row pB20">
    <div class="col-md-3 f22">
        Internal Name
    </div>
    <div class="col-md-9">
        <input type="text" name="emailName" value="{{ $currEmail->ComEmailName }}" class="form-control f22" >
    </div>     
</div>

<div id="subj" class="row pB20 @if ($currEmail->ComEmailType == 'Blurb') disNon @else disBlo @endif ">
    <div class="col-md-3 f22">
        Email Subject Line
    </div>
    <div class="col-md-9">
        <input type="text" name="emailSubject" value="{{ $currEmail->ComEmailSubject }}" class="form-control f22" >
    </div>
</div>

<div class="row pB20">
    <div class="col-md-3 f22">
        Email Body
        <div class="f12 pT10 pL10 gry9">
            To create spots within the email body where evaluators can provide hand-written paragraphs, just mark them with this: 
            <div class="f20 pT5 pL10 gry3">[{ Evaluator Message }]</div>
            <!--- while actually sending an email, provide the evaluators the full email with textareas swapped out for each customizable spot, so it is super clear --->
        </div>
    </div>
    <div class="col-md-9">
        <textarea name="emailBody" class="form-control f16" style="height: 300px;">{{ $currEmail->ComEmailBody }}</textarea>
    </div>
</div>

<center><input type="submit" class="btn btn-lg btn-primary f24" value="Save Auto-Email"></center>

</form>

<!--- {{ $currEmail->ComEmailOpts }} --->

<div class="adminFootBuff"></div>

@endsection