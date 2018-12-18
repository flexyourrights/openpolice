<!-- resources/views/vendor/openpolice/nodes/1712-report-inc-staff-tools.blade.php -->
@if (!isset($complaintRec->ComStatus) || intVal($complaintRec->ComStatus) <= 0 || 
    $GLOBALS["SL"]->def->getVal('Complaint Status', $complaintRec->ComStatus) == 'Incomplete')

@elseif ($firstReview)
    
    <div class="slCard mT10">
        <h2 class="m0">Complaint #{{ $complaintRec->ComPublicID }}:
        What Type Of Submission Is This?</h2>
        <div class="row">
            <div class="col-md-6">
                <a class="btn btn-lg btn-primary disBlo mT10 mB10" href="?firstReview=296" id="stfBtn1">
                Police Complaint</a>
                <a class="btn btn-lg btn-secondary disBlo mT10" href="?firstReview=297" id="stfBtn2">
                Not About Police</a>
            </div><div class="col-md-6">
                <div class="row mB0 pB0">
                    <div class="col-4">
                        <a class="btn btn-lg btn-secondary disBlo mT10 mB10" href="?firstReview=298" id="stfBtn3">
                        Abuse</a>
                    </div><div class="col-4">
                        <a class="btn btn-lg btn-secondary disBlo mT10 mB10" href="?firstReview=299" id="stfBtn4">
                        Spam</a>
                    </div><div class="col-4">
                        <a class="btn btn-lg btn-secondary disBlo mT10 mB10" href="?firstReview=300" id="stfBtn5">
                        Test</a>
                    </div>
                </div>
                <a class="btn btn-lg btn-secondary disBlo" href="?firstReview=301" id="stfBtn6">
                Not Sure</a>
            </div>
        </div>
    </div>
    <div class="mB20">&nbsp;</div>
    <style> 
    a#stfBtn1:link, a#stfBtn1:active, a#stfBtn1:visited { color: #FFF; }
    a#stfBtn1:hover { color: #2b3493; }
    a#stfBtn2:link, a#stfBtn2:active, a#stfBtn2:visited, a#stfBtn3:link, a#stfBtn3:active, a#stfBtn3:visited,
    a#stfBtn4:link, a#stfBtn4:active, a#stfBtn4:visited, a#stfBtn5:link, a#stfBtn5:active, a#stfBtn5:visited,
    a#stfBtn6:link, a#stfBtn6:active, a#stfBtn6:visited { color: #2b3493; }
    a#stfBtn2:hover, a#stfBtn3:hover, a#stfBtn4:hover, a#stfBtn5:hover, a#stfBtn6:hover { color: #FFF; }
    </style>
    
@elseif (in_array($view, ['', 'history', 'update', 'emails', 'emailsType']))

    <div id="analystHistory" class="row mT10 mB10">
        <div class="col-8">
        
        @if (intVal($emailID) > 0 && sizeof($currEmail) > 0)
            
            <div class="slCard mB20">
                <form action="/complaint/read-{{ $complaintRec->ComPublicID }}?view=emails" method="post" 
                    onSubmit="return chkEmaForm();" >
                <input type="hidden" id="csrfTok" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="cID" value="{{ $complaintRec->ComPublicID }}">
                <input type="hidden" name="emailID" value="{{ $emailID }}">
                <a name="emailer"></a>
                <div id="analystEmailComposer" class=" @if (intVal($emailID) > 0) disBlo @else disNon @endif ">
                    <h2 class="mT0"><i class="fa fa-envelope" aria-hidden="true"></i> 
                        Complaint #{{ $complaintRec->ComPublicID }}:&nbsp;&nbsp;&nbsp;
                        Sending @if (sizeof($currEmail) > 1) Emails @else Email @endif</h2>
                    @if ($emailID == 12)
                        @forelse ($GLOBALS["SL"]->x["depts"] as $deptID => $stuff)
                            @if (!isset($stuff["overUser"]) || !isset($stuff["overUser"]->email))
                                <div class="alert alert-danger mT10 fPerc133" role="alert">
                                    <strong>{{ $stuff["deptRow"]->DeptName }}</strong> 
                                    is <nobr>NOT OPC-Compliant!</nobr><br />Do not send them an email!</div>
                            @endif
                        @empty
                        @endforelse
                    @endif
                
                    <div class="nFld m0">
                    @forelse ($currEmail as $j => $email)
                        @if ($j > 0) <div class="pT20"><hr></div> @else <div class="p5"></div> @endif
                        Send To
                        <select class="form-control form-control-lg w100 changeEmailTo" autocomplete=off 
                            name="emailTo{{ $j }}" id="emailTo{{ $j }}ID">
                        @forelse ($emailsTo[$email["rec"]->EmailType] as $i => $ema)
                            <option value="{{ $ema[0] }}" @if ($ema[2]) SELECTED @endif 
                                >{{ $ema[1] }} ({{ $ema[0] }}) </option>
                        @empty
                        @endforelse
                            <option value="--CUSTOM--">Type in custom email address:</option>
                        </select>
                        <div id="emailTo{{ $j }}CustID" class="row mT5 disNon">
                            <div class="col-6">
                                Recipient Name
                                <input type="text" name="emailTo{{ $j }}CustName" id="emailTo{{ $j }}CustNameID" 
                                    class="form-control form-control-lg" autocomplete=off >
                            </div>
                            <div class="col-6">
                                Recipient Email
                                <input type="text" name="emailTo{{ $j }}CustEmail" id="emailTo{{ $j }}CustEmailID" 
                                    class="form-control form-control-lg" autocomplete=off >
                            </div>
                        </div>
                        <div class="p10"></div>
                        Email Subject
                        <input type="text" class="form-control form-control-lg w100" name="emailSubj{{ $j }}" 
                            id="emailSubj{{ $j }}ID" value="{{ $email['subject'] }}" autocomplete=off >
                        <div class="p10"></div>
                        Email Body
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
                    
                    <div class="m20 taC"><input type="submit" class="btn btn-xl btn-primary w66" id="stfBtn9"
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
            
            <div class="slCard mB10">
                <h2 class="mT0">Complaint #{{ $complaintRec->ComPublicID }}:&nbsp;&nbsp;&nbsp;History</h2>
                {!! view('vendor.openpolice.nodes.1712-report-inc-history', [ "history" => $history ])->render() !!}
            </div>
            
        @endif
            
        </div><div class="col-4">
        
        @if (intVal($emailID) > 0 && sizeof($currEmail) > 0)
            <div class="slCard mB20">
                <h2 class="mT0">History</h2>
                {!! view('vendor.openpolice.nodes.1712-report-inc-history', [ "history" => $history ])->render() !!}
            </div>
        @endif
            
            <div class="slCard mB20">
                @if ($firstRevDone) <h3 class="mBn10"><span class="slRedDark">Next, Update Complaint Status:</span></h3>
                @else <a id="hidivBtnStatus" href="javascript:;" 
                    class="hidivBtn btn btn-lg @if ($firstRevDone) btn-primary @else btn-secondary @endif disBlo taL" 
                    @if ($firstRevDone) style="color: #FFF;" @endif >
                    <i class="fa fa-sign-in mR5" aria-hidden="true"></i> Update Complaint Status</a>
                @endif
                <div id="hidivStatus" class=" @if ($firstRevDone) disBlo @else disNon @endif ">
                    <form name="comUpdate" action="/complaint/read-{{ $complaintRec->ComPublicID }}?save=1" 
                        method="post" >
                    <input type="hidden" id="csrfTok" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="cID" value="{{ $complaintRec->ComPublicID }}">
                    <input type="hidden" name="revType" value="Update">
                    <div class="nFld"><select name="revStatus" class="form-control form-control-lg" autocomplete="off">
                        {!! view('vendor.openpolice.nodes.1712-report-inc-status', [
                            "firstReview" => false, 
                            "lastReview"  => $lastReview, 
                            "fullList"    => true
                            ])->render() !!}
                    </select></div> <!-- end nFld -->
                    
                    <div class="pT5">
                        Complaint Type: <a href="javascript:;" id="legitTypeBtn">{{ 
                            $GLOBALS['SL']->def->getVal('OPC Staff/Internal Complaint Type', $complaintRec->ComType)
                            }} <i class="fa fa-pencil fPerc66"></i></a>
                    </div>
                    <div id="legitTypeDrop" class="disNon">
                        <select name="revComplaintType" class="form-control form-control-lg" autocomplete=off >
                            <option value="295" @if ($complaintRec->ComType == 295) SELECTED @endif >Unreviewed</option>
                            <option value="296" @if ($complaintRec->ComType == 296) SELECTED @endif >Complaint About Police</option>
                            <option value="297" @if ($complaintRec->ComType == 297) SELECTED @endif >Not About Police</option>
                            <option value="298" @if ($complaintRec->ComType == 298) SELECTED @endif >Abuse</option>
                            <option value="299" @if ($complaintRec->ComType == 299) SELECTED @endif >Spam</option>
                            <option value="300" @if ($complaintRec->ComType == 300) SELECTED @endif >Test Submission</option>
                            <option value="301" @if ($complaintRec->ComType == 301) SELECTED @endif >Not Sure</option>
                        </select>
                    </div>
                    
                    <h4 class="mT20 mB10">Notes for other evaluators:</h4>
                    <textarea name="revNote" class="form-control form-control-lg" style="height: 70px;" ></textarea>
                    
                    <div class="w100 taC mT20">
                        <a class="btn btn-lg btn-primary" id="stfBtn7" href="javascript:;" 
                            onClick="document.comUpdate.submit();" >Save Status Update</a>
                    </div>
                    </form>
                </div>
            </div>
            
            <div class="slCard">
                <a id="hidivBtnEmails" class="btn btn-lg btn-secondary hidivBtn disBlo taL" href="javascript:;">
                    <i class="fa fa-envelope mR5" aria-hidden="true"></i> Send Emails
                </a>
                <div id="hidivEmails" class=" @if ($emailID > 0) disBlo @else disNon @endif ">
                    <div class="nFld">
                        Select which email template you want to send:<br />
                        <select name="email" id="emailID" class="form-control form-control-lg" autocomplete="off" >
                            <option value="" > No email right now</option>
                            @forelse ($emailList as $i => $email)
                                @if ($email->EmailType != 'Blurb')
                                    <option value="{{ $email->EmailID }}"
                                        @if ($emailID == $email->EmailID) SELECTED @endif
                                        >{{ $email->EmailName }} - {{ $email->EmailType }}</option>
                                @endif
                            @empty @endforelse
                        </select>
                    </div>
                    <div class="mT20 taC"><a href="javascript:;" class="btn btn-lg btn-primary" id="stfBtn8"
                        onClick="window.location='/complaint/read-{{ $complaintRec->ComPublicID 
                            }}?email='+document.getElementById('emailID').value+'#emailer';"
                        >Load Email Template</a>
                    </div>
                </div>
            </div>
            <div class="pB20">&nbsp;</div>
    
        </div>
    </div>
    <style>
    a#hidivBtnStatus:link, a#hidivBtnStatus:active, a#hidivBtnStatus:visited,
    a#hidivBtnEmails:link, a#hidivBtnEmails:active, a#hidivBtnEmails:visited { color: #2b3493; }
    a#hidivBtnStatus:hover, a#hidivBtnEmails:hover { color: #FFF; }
    a#stfBtn7:link, a#stfBtn7:active, a#stfBtn7:visited,
    a#stfBtn8:link, a#stfBtn8:active, a#stfBtn8:visited { color: #FFF; }
    a#stfBtn7:hover, a#stfBtn8:hover, a#stfBtn9:hover { color: #2b3493; }
    </style>
        
@endif