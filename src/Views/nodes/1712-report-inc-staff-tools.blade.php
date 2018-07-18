<!-- resources/views/vendor/openpolice/nodes/1712-report-inc-staff-tools.blade.php -->
@if ($firstReview)
    
    <div class="slCard mT10 mB20">
        <h1 class="m0">Complaint #{{ $complaintRec->ComPublicID }}:&nbsp;&nbsp;&nbsp;
        What Type Of Submission Is This?</h1>
        <div class="row">
            <div class="col-md-6">
                <a class="btn btn-lg btn-primary disBlo mT10 mB10" href="?firstReview=296" style="color: #FFF;">
                Police Complaint</a>
                <a class="btn btn-lg btn-default disBlo mT10" href="?firstReview=297">
                Not About Police</a>
            </div><div class="col-md-6">
                <div class="row mB0 pB0">
                    <div class="col-md-4">
                        <a class="btn btn-lg btn-default disBlo mT10 mB10" href="?firstReview=298">
                        Abuse</a>
                    </div><div class="col-md-4">
                        <a class="btn btn-lg btn-default disBlo mT10 mB10" href="?firstReview=299">
                        Spam</a>
                    </div><div class="col-md-4">
                        <a class="btn btn-lg btn-default disBlo mT10 mB10" href="?firstReview=300">
                        Test Submission</a>
                    </div>
                </div>
                <a class="btn btn-lg btn-default disBlo" href="?firstReview=301">
                Not Sure</a>
            </div>
        </div>
    </div>
    
@elseif (in_array($view, ['', 'history', 'update', 'emails', 'emailsType']))

    <div id="analystHistory" class="row disBlo mT10 mB10">
        <div class="col-md-8">
        
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
                        Email Subject
                        <input type="text" class="form-control input-lg w100" name="emailSubj{{ $j }}" 
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
                    
                    <div class="m20 taC"><input type="submit" class="btn btn-xl btn-primary w66" 
                        value="Send Email" style="color: #FFF;"></div>
                    
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
            
        </div>
        <div class="col-md-4">
        
        @if (intVal($emailID) > 0 && sizeof($currEmail) > 0)
            <div class="slCard mB20">
                <h2 class="mT0">History</h2>
                {!! view('vendor.openpolice.nodes.1712-report-inc-history', [ "history" => $history ])->render() !!}
            </div>
        @endif
            
            <div class="slCard mB20">
                <a id="hidivBtnStatus" href="javascript:;" 
                    class="hidivBtn btn btn-lg @if ($firstRevDone) btn-primary @else btn-default @endif disBlo taL" 
                    @if ($firstRevDone) style="color: #FFF;" @endif >
                    <i class="fa fa-sign-in mR5" aria-hidden="true"></i>
                    @if ($firstRevDone) Next, Update Complaint Status: @else Update Complaint Status @endif
                </a>
                <div id="hidivStatus" class=" @if ($firstRevDone) disBlo @else disNon @endif ">
                    <form name="comUpdate" action="/complaint/read-{{ $complaintRec->ComPublicID }}?save=1" 
                        method="post" >
                    <input type="hidden" id="csrfTok" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="cID" value="{{ $complaintRec->ComPublicID }}">
                    <input type="hidden" name="revType" value="Update">
                    <div class="nFld"><select name="revStatus" class="form-control input-lg" autocomplete="off">
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
                    
                    <div class="w100 taC mT20">
                        <a class="btn btn-lg btn-primary" style="color: #FFF;" href="javascript:;" 
                            onClick="document.comUpdate.submit();" >Save Status Update</a>
                    </div>
                    </form>
                </div>
            </div>
            
            <div class="slCard">
                <a id="hidivBtnEmails" class="btn btn-lg btn-default hidivBtn disBlo taL" href="javascript:;">
                    <i class="fa fa-envelope mR5" aria-hidden="true"></i> Send Emails
                </a>
                <div id="hidivEmails" class=" @if ($emailID > 0) disBlo @else disNon @endif ">
                    <div class="nFld">
                        Select which email template you want to send:<br />
                        <select name="email" id="emailID" class="form-control input-lg" autocomplete="off" >
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
                    <div class="mT20 taC"><a href="javascript:;" class="btn btn-lg btn-primary" style="color: #FFF;"
                        onClick="window.location='/complaint/read-{{ $complaintRec->ComPublicID }}?email='+document.getElementById('emailID').value+'#emailer';"
                        >Load Email Template</a>
                    </div>
                </div>
            </div>
    
        </div>
    </div>
        
@endif