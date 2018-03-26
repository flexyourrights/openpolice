<!-- resources/views/vendor/openpolice/admin/complaints/complaint-review.blade.php -->

@extends('vendor.survloop.master')

@section('content')

@if ($firstReview)
    
    <div class="panel panel-info mT10">
        <div class="panel-heading">
            <div class="panel-title"><h1 class="m0">Complaint #{{ $cID }}:&nbsp;&nbsp;&nbsp;
            What Type Of Submission Is This?</h1></div>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-2">
                    <a class="btn btn-lg btn-primary disBlo" href="?firstReview=296">
                    Police Complaint</a>
                </div>
                <div class="col-md-2">
                    <a class="btn btn-lg btn-default disBlo" href="?firstReview=297">
                    Not About Police</a>
                </div>
                <div class="col-md-2">
                    <a class="btn btn-lg btn-default disBlo" href="?firstReview=298">
                    Abuse</a>
                </div>
                <div class="col-md-2">
                    <a class="btn btn-lg btn-default disBlo" href="?firstReview=299">
                    Spam</a>
                </div>
                <div class="col-md-2">
                    <a class="btn btn-lg btn-default disBlo" href="?firstReview=300">
                    Test Submission</a>
                </div>
                <div class="col-md-2">
                    <a class="btn btn-lg btn-default disBlo" href="?firstReview=301">
                    Not Sure</a>
                </div>
            </div>
        </div>
    </div>
    <style> .btnTall { height: 115px; } </style>
    
@elseif (in_array($viewType, ['review', 'history', 'update', 'emails', 'emailsType']))

    <div id="analystHistory" class="row disBlo">
        <div class="col-md-7">
        
            @if (intVal($emailID) > 0 && sizeof($currEmail) > 0)
            
                <form action="/dashboard/complaint/{{ $complaintRec->ComPublicID }}/emails/type" method="post" 
                    onSubmit="return chkEmaForm();" >
                <input type="hidden" id="csrfTok" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="cID" value="{{ $complaintRec->ComPublicID }}">
                <input type="hidden" name="emailID" value="{{ $emailID }}">
                <a name="emailer"></a>
                <div id="analystEmailComposer" class="panel panel-info mT10
                    @if (intVal($emailID) > 0) disBlo @else disNon @endif ">
                    <div class="panel-heading">
                        <div class="panel-title"><h1 class="m0">
                            <i class="fa fa-envelope" aria-hidden="true"></i> 
                            Sending @if (sizeof($currEmail) > 1) Emails @else Email @endif
                        </h1></div>
                    </div>
                    <div class="panel-body">
                        @if ($emailID == 12)
                            @forelse ($GLOBALS["SL"]->x["depts"] as $deptID => $stuff)
                                @if (!isset($stuff["overUser"]) || !isset($stuff["overUser"]->email))
                                    <div class="alert alert-danger mT10 fPerc133" role="alert">
                                        <strong>{{ $stuff["deptRow"]->DeptName }}</strong> 
                                        is NOT OPC-Compliant!<br />Do not send them an email!</div>
                                @endif
                            @empty
                            @endforelse
                        @endif
                    
                        <div class="nFld m0">
                        
                        @forelse ($currEmail as $j => $email)
                            @if ($j > 0) <div class="pT20"><hr></div> @endif
                            <h3 class="m0">Send To</h3>
                            <select class="form-control input-lg w100 changeEmailTo" autocomplete=off 
                                name="emailTo{{ $j }}" id="emailTo{{ $j }}ID">
                            @forelse ($emailsTo[$email["rec"]->EmailType] as $i => $ema)
                                <option value="{{ $ema[0] }}" @if ($ema[2]) SELECTED @endif 
                                    >{{ $ema[1] }} ({{ $ema[0] }}) </option>
                            @empty
                            @endforelse
                                <option value="--CUSTOM--">Type in custom email address:</option>
                            </select>
                            <div id="emailTo{{ $j }}CustID" class="row mT5 disNon">
                                <div class="col-md-6">
                                    Recipient Name
                                    <input type="text" name="emailTo{{ $j }}CustName" id="emailTo{{ $j }}CustNameID" 
                                        class="form-control input-lg" autocomplete=off >
                                </div>
                                <div class="col-md-6">
                                    Recipient Email
                                    <input type="text" name="emailTo{{ $j }}CustEmail" id="emailTo{{ $j }}CustEmailID" 
                                        class="form-control input-lg" autocomplete=off >
                                </div>
                            </div>
                            <div class="p10"></div>
                            <h3 class="m0">Email Subject</h3>
                            <input type="text" class="form-control input-lg w100" name="emailSubj{{ $j }}" 
                                id="emailSubj{{ $j }}ID" value="{{ $email['subject'] }}" autocomplete=off >
                            <div class="p10"></div>
                            <h3 class="m0">Email Body</h3>
                            <textarea name="emailBodyCust{{ $j }}" id="emailBodyCust{{ $j }}ID" class="w100" 
                                style="height: 500px;" autocomplete=off >{!! $email["body"] !!}</textarea>
                        @empty
                        @endforelse
                        </div>
                        
                        @if ($emailID == 12)
                            @forelse ($GLOBALS["SL"]->x["depts"] as $deptID => $stuff)
                                @if (!isset($stuff["overUser"]) || !isset($stuff["overUser"]->email))
                                    <div class="alert alert-danger mT10 fPerc133" role="alert">
                                        <strong>{{ $stuff["deptRow"]->DeptName }}</strong> 
                                        is NOT OPC-Compliant!<br />Do not send them an email!</div>
                                @endif
                            @empty
                            @endforelse
                        @endif
                        
                        <div class="m20 taC"><input type="submit" class="btn btn-xl btn-primary w66"
                            value="Send Email"></div>
                        
                    </div>
                </div>
                </form>
                <script type="text/javascript">
                function chkEmaForm() {
                    for (var j=0; j < {{ sizeof($currEmail) }}; j++) {
                        if (!document.getElementById('emailTo'+j+'ID') 
                            || document.getElementById('emailTo'+j+'ID').value.trim() == '') {
                            alert("Please provide an email address to send this message.");
                            return false;
                        }
                    }
                    return true;
                }
                </script>
            
            @else
            
                <h2>Complaint History: ID #{{ $cID }}</h2>
                {!! view('vendor.openpolice.admin.complaints.complaint-review-history', [
                    "history" => $history ])->render() !!}
                    
            @endif
            
        </div>
        <div class="col-md-5 pB10">
        
            @if (intVal($emailID) > 0 && sizeof($currEmail) > 0)
                <h2>Complaint History: ID #{{ $cID }}</h2>
                {!! view('vendor.openpolice.admin.complaints.complaint-review-history', [
                    "history" => $history ])->render() !!}
                <div class="p10"></div>
            @endif
            <div class="p10"></div>
            
            <a id="newStatusUpdate" class="btn btn-xl disBlo @if ($firstRevDone) btn-primary @else btn-default @endif " href="javascript:;">
                <div class="row">
                    <div class="col-md-1"><i class="fa fa-tachometer" aria-hidden="true"></i></div>
                    <div class="col-md-11 taL">
                        @if ($firstRevDone) Next, Update Complaint Status: @else Update Complaint Status @endif
                    </div>
                </div>
            </a>
            
            <div id="newStatusUpdateBlock" class="p10 mB20 mTn5 round5 brd
                @if ($firstRevDone) disBlo @else disNon @endif " style="border-top: 0px none;">
                <form name="comUpdate" action="/dashboard/complaint/{{ $complaintRec->ComPublicID }}/review/save" 
                    method="post" >
                <input type="hidden" id="csrfTok" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="cID" value="{{ $cID }}">
                <input type="hidden" name="revType" value="Update">
                {!! view('vendor.openpolice.admin.complaints.complaint-review-status', [
                    "firstReview" => false, 
                    "lastReview"  => $lastReview, 
                    "fullList"    => true
                    ])->render() !!}
    
                <div class="pL10 pR10 mTn20">
                    <h4 class="m0 gry9">Complaint Type: <a href="javascript:;" id="legitTypeBtn">{{ 
                        $GLOBALS['SL']->getDefValue('OPC Staff/Internal Complaint Type', $complaintRec->ComType)
                        }} <i class="fa fa-pencil"></i></a></h4>
                    <div id="legitTypeDrop" class="disNon mB20">
                        <select name="revComplaintType" class="form-control input-lg" autocomplete=off >
                            <option value="295" @if ($complaintRec->ComType == 295) SELECTED @endif >Unreviewed</option>
                            <option value="296" @if ($complaintRec->ComType == 296) SELECTED @endif >Complaint About Police</option>
                            <option value="297" @if ($complaintRec->ComType == 297) SELECTED @endif >Not About Police</option>
                            <option value="298" @if ($complaintRec->ComType == 298) SELECTED @endif >Abuse</option>
                            <option value="299" @if ($complaintRec->ComType == 299) SELECTED @endif >Spam</option>
                            <option value="300" @if ($complaintRec->ComType == 300) SELECTED @endif >Test Submission</option>
                            <option value="301" @if ($complaintRec->ComType == 301) SELECTED @endif >Not Sure</option>
                        </select>
                    </div>
                    
                    <h3 class="mT20 mB0">Notes for other evaluators:</h3>
                    <textarea name="revNote" class="form-control input-lg" style="height: 70px;" ></textarea>
                </div>
                
                <div class="p20 taC">
                    <a class="btn btn-xl btn-primary w66" href="javascript:;" onClick="document.comUpdate.submit();" 
                        >Save Status Update</a>
                </div>
            
                </form>
            </div>
            
            <a id="newEmails" class="btn btn-xl btn-default mT20 disBlo" href="javascript:;">
                <div class="row">
                    <div class="col-md-1"><i class="fa fa-envelope" aria-hidden="true"></i></div>
                    <div class="col-md-11 taL">Send Emails</div>
                </div>
            </a>
    
            <div id="analystEmailer" class="p10 mB20 mTn5 round5 brd @if ($emailID > 0) disBlo @else disNon @endif " 
                style="border-top: 0px none;">
                <h4 class="mT10">Select which email template you want to send right now:</h4>
                <div class="nFld mT0">
                    <select name="email" id="emailID" class="form-control input-lg" autocomplete=off >
                        <option value="" > No email right now</option>
                        @forelse ($emailList as $i => $email)
                            @if ($email->EmailType != 'Blurb')
                                <option value="{{ $email->EmailID }}"
                                    @if ($emailID == $email->EmailID) SELECTED @endif
                                    >{{ $email->EmailName }} - {{ $email->EmailType }}</option>
                            @endif
                        @empty
                        @endforelse
                    </select>
                </div>
                <div class="mT20 taC"><a href="javascript:;" class="btn btn-lg btn-primary w66"
                    onClick="window.location='/dashboard/complaint/{{ $complaintRec->ComPublicID }}/review?email='+document.getElementById('emailID').value+'#emailer';"
                    >Load Email Template</a>
                </div>
            </div>
    
        </div>
    </div>
        
@endif
    
<hr>

{!! $fullReport !!}

@endsection
