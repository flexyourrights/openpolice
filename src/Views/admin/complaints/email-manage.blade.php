<!-- resources/views/vendor/openpolice/admin/complaints/email-manage.blade.php -->

@extends('vendor.survloop.admin.admin')

@section('content')

<div class="row">
    <div class="col-md-4">
        <h1>System Settings</h1>
    </div>
    @if (isset($settings) && sizeof($settings) > 0)
        <form action="/dashboard/complaints/emails" method="post" >
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="savingSettings" value="1">
            @foreach ($settings as $i => $s)
                <div class="col-md-3 f18 pT10 mT5">
                    <div class="f22">{{ $s->setting }}</div>
                    <label class="mL20">
                        <input type="radio" name="setting{{ $i }}" value="Y"
                            @if ($s->val == 'Y') CHECKED @endif
                            > Yes
                    </label>
                    <label class="mL20">
                        <input type="radio" name="setting{{ $i }}" value="N"
                            @if ($s->val == 'N') CHECKED @endif
                            > No
                    </label>
                </div>
            @endforeach
            <div class="col-md-2 pT20 taR">
                <input type="submit" class="btn btn-lg btn-primary" value="Save Settings">
            </div>
        </form>
    @endif
</div>

<br /><br />

<div class="row">
    <div class="col-md-6">
        <h1>
            Manage Auto-Emails <span class="f16">(eg. in response to complaint submissions)</span>
        </h1>
    </div>
    <div class="col-md-6 pT20 taR">
        <a href="/dashboard/complaints/email/-3" class="btn btn-default">Create New Auto-Email</a>
        <a href="javascript:void(0)" class="btn btn-default mL20" id="showAll">Show/Hide All Email Bodies</a>
    </div>
</div>

<div class="p5"></div>

<div class="row f18 gry9 pB20">
    <div class="col-md-4">Email Type<br /><i>Internal Name</i></div>
    <div class="col-md-7">Email Subject Line</div>
    <div class="col-md-1 taC">Emails Sent</div>
</div>
@forelse ($emailList as $i => $email)
    <div class="row pT10 pB10 f18 @if ($i%2 == 0) row2 @endif ">
        <div class="col-md-4">
            <a href="/dashboard/complaints/email/{{ $email->ComEmailID }}"><i class="fa fa-pencil fa-flip-horizontal mR10" aria-hidden="true"></i></a>
            {{ $email->ComEmailType }} 
            <br />
            @if ($email->ComEmailType == 'Blurb')
                [{ <a class="emailLnk" id="showEmail{{ $email->ComEmailID }}" href="javascript:void(0)"
                    ><i>{{ $email->ComEmailName }}</i></a> }]
            @else
                <i>{{ $email->ComEmailName }}</i>
            @endif
        </div>
        <div class="col-md-7">
            <a class="emailLnk f28" id="showEmail{{ $email->ComEmailID }}" href="javascript:void(0)"
                >{{ $email->ComEmailSubject }}</a>
        </div>
        <div class="col-md-1 taC">
            @if ($email->ComEmailType != 'Blurb')
                {{ number_format($email->ComEmailTotSent, 0) }} 
                <a href="#"><i class="fa fa-paper-plane" aria-hidden="true"></i></a>
            @endif
        </div>
    </div>
    <div id="emailBody{{ $email->ComEmailID }}" class="emailBody row pB20 f18 @if ($i%2 == 0) row2 @endif 
        @if ($isAll) disBlo @else disNon @endif ">
        <div class="col-md-4"></div>
        <div class="col-md-8 pB20">
            {!! $email->ComEmailBody !!}
        </div>
    </div>
@empty
    <i>No emails found!?!</i>
@endforelse

<script type="text/javascript">
$(function() {
    $(document).on("click", "a.emailLnk", function() {
        $("#emailBody"+$(this).attr("id").replace("showEmail", "")).slideToggle("fast");
    });
    $(document).on("click", "#showAll", function() {
        $(".emailBody").slideToggle("fast");
    });
});
</script>

<div class="adminFootBuff"></div>

@endsection