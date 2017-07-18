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
                    <a class="btn btn-xl btn-primary disBlo btnTall" href="?firstReview=296">
                    Police Complaint</a>
                </div>
                <div class="col-md-2">
                    <a class="btn btn-xl btn-default disBlo btnTall" href="?firstReview=297">
                    Not About Police</a>
                </div>
                <div class="col-md-2">
                    <a class="btn btn-xl btn-default disBlo btnTall" href="?firstReview=298">
                    <div style="padding-top: 18px;">Abuse</div></a>
                </div>
                <div class="col-md-2">
                    <a class="btn btn-xl btn-default disBlo btnTall" href="?firstReview=299">
                    <div style="padding-top: 18px;">Spam</div></a>
                </div>
                <div class="col-md-2">
                    <a class="btn btn-xl btn-default disBlo btnTall" href="?firstReview=300">
                    Test Submission</a>
                </div>
                <div class="col-md-2">
                    <a class="btn btn-xl btn-default disBlo btnTall" href="?firstReview=301">
                    <div style="padding-top: 18px;">Not Sure</div></a>
                </div>
            </div>
        </div>
    </div>
    <style> .btnTall { height: 115px; } </style>
    
@elseif (in_array($viewType, ['review', 'history', 'update', 'emails', 'emailsType']))

    <div id="analystHistory" class="row disBlo">
        <div class="col-md-7">
        
            <h2>Complaint History: ID #{{ $cID }}</h2>
            @if ($history && sizeof($history) > 0)
                @foreach ($history as $i => $h)
                    <div class="p5 brdBot">
                        <h4 class="m0 slBlueDark">
                            @if ($h["type"] == 'Status')
                                <i class="fa fa-tachometer mR5" aria-hidden="true"></i> 
                            @elseif ($h["type"] == 'Email')
                                <i class="fa fa-envelope mR5" aria-hidden="true"></i>
                            @endif
                            {!! $h["desc"] !!}
                        </h4>
                        <span class="slGrey">{!! $h["who"] !!}, {{ date("n/j/y h:ia", $h["date"]) }}</span>
                    </div>
                @endforeach
            @else
                <div class="p5 brdBot"><i>This complaint has not been reviewed yet.</i></div>
            @endif
            
            @if (intVal($emailID) > 0 && sizeof($currEmail) > 0)
            
                <form action="/dashboard/complaint/{{ $cID }}/emails/type" method="post" >
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="cID" value="{{ $cID }}">
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
                        <div class="nFld m0">
                        
                        @forelse ($currEmail as $j => $email)
                            @if ($j > 0) <div class="pT20"><hr></div> @endif
                            <h3 class="m0">Send To</h3>
                            <select class="form-control input-lg w100" name="emailTo{{ $j }}" id="emailTo{{ $j }}ID">
                            @forelse ($emailsTo[$email["rec"]->EmailType] as $i => $ema)
                                <option value="{{ $ema[0] }}" @if ($ema[2]) SELECTED @endif 
                                    >{{ $ema[0] }} ({{ $ema[1] }}) </option>
                            @empty                                   
                            @endforelse
                            </select>
                            <div class="p10"></div>
                            <h3 class="m0">Email Subject</h3>
                            <input type="text" class="form-control input-lg w100" name="emailSubj{{ $j }}" 
                                id="emailSubj{{ $j }}ID" value="{{ $email['subject'] }}" >
                            <div class="p10"></div>
                            <h3 class="m0">Email Body</h3>
                            <textarea name="emailBodyCust{{ $j }}" id="emailBodyCust{{ $j }}ID" class="w100" 
                                style="height: 500px;" >{!! $email["body"] !!}</textarea>
                        @empty
                        @endforelse
                        </div>
                        <div class="m20 taC"><input type="submit" class="btn btn-xl btn-primary w66"
                            value="Send Email"></div>
                        
                    </div>
                </div>
                </form>
            
            @endif
            
        </div>
        <div class="col-md-5 pT20 pB10">
        
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
                <form name="comUpdate" action="/dashboard/complaint/{{ $cID }}/review/save" method="post" >
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
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
                    onClick="window.location='/dashboard/complaint/{{ $cID }}/review?email='+document.getElementById('emailID').value+'#emailer';"
                    >Load Email Template</a>
                </div>
            </div>
    
        </div>
    </div>
        
@endif
    
<hr>

{!! $fullReport !!}

@endsection
