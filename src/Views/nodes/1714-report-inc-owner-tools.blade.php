<!-- resources/views/vendor/openpolice/nodes/1714-report-inc-owner-tools.blade.php -->
<div class="p10"> </div>

<div class="row">
    <div class="col-xl-8 col-lg-6">

        <div class="slCard">
            <h2 class="m0">
                @if (!isset($complaint->ComPublicID) || intVal($complaint->ComPublicID) <= 0)
                    Incomplete Complaint #{{ $complaint->ComID }}
                @else Complaint #{{ $complaint->ComPublicID }} @endif </h2>
            @if (!isset($complaint->ComStatus) || intVal($complaint->ComStatus) <= 0 || 
                $GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->ComStatus) == 'Incomplete')
                <h4 class="mT5 txtDanger">Current Status: Incomplete</h4>
            @else
                <h4 class="mT5">Current Status: 
                @if (isset($overUpdateRow->LnkComOverReceived) && trim($overUpdateRow->LnkComOverReceived) != '')
                    Received by Oversight
                @else {{ $GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->ComStatus) }} @endif </h4>
            @endif
            
@if (in_array($GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->ComStatus), ['Hold', 'New', 'Reviewed']))
    <h2 class="mT0">Congratulations, {{ $user->name }}!</h2>
    <p>Within the next week, we will review your complaint. 
    If there are no problems, we will try to file it with the {{ $overList }}. 
    Then, we'll let you know what comes next. So hang tight!</p>
@elseif ($GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->ComStatus) == 'Pending Attorney')
    <p><b>Sorry to hear about this situation, {{ $user->name }}.</b></p>
    <p>We've reviewed your complaint, and we urge you to contact a local criminal defense lawyer before you do 
    anything else. Because you shared information that might harm somebody's legal situation, we've unpublished 
    your complaint. Do not post anything online about this incident, and do not talk to the police without 
    having a lawyer with you. We are also actively looking for a lawyer to help you. 
    Within a week, we will email you with whatever we find.</p>
    <p>Please understand that we do not provide direct legal services. 
    But the work you put into your complaint could help your lawyer. So please save and print your complaint.</p>
@elseif ($GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->ComStatus) == 'Attorney\'d')
    <p><b>Hi, {{ $user->name }},</b></p>
    <p>We are glad you found a lawyer to help with your situation. 
    After all legal situations are resolved — or if advised by your lawyer — 
    you can safely choose to publish your story for the public to see.</p>
    <p>Please understand that we do not provide direct legal services. 
    But the work you put into your complaint could help your lawyer. So please save and print your complaint.</p>
@elseif ($GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->ComStatus) == 'OK to Submit to Oversight')
    @if (sizeof($oversights) > 0 && isset($oversights[0]->OverEmail) && trim($oversights[0]->OverEmail) != ''
        && isset($oversights[0]->OverWaySubEmail) && intVal($oversights[0]->OverWaySubEmail) == 1)
        <p><b>Congratulations, {{ $user->name }}!</b></p>
        <p>Within the next week, we will review your complaint. 
        If there are no problems, we will try to file it with the {{ $overList }}. 
        Then, we'll let you know what comes next. So hang tight!</p>
    @else
        <p><b>Hi, {{ $user->name }},</b></p>
        <p>We're almost done — but we need you to do one more important thing as soon as possible. 
        Open Police Complaints (OPC) is working to get all police departments to accept complaints sent by email. 
        Unfortunately, the {{ $overList }} does not investigate OPC complaints sent by email.</p>
        <p>The good news is you can easily copy information from your OPC complaint to their required forms. 
        And you can find the instructions for formally submitting your complaint to the department here:</p><p>
        @forelse ($depts as $i => $d)
            <a href="/dept/{{ $d->DeptSlug }}" target="_blank">{{ $d->DeptName }}</a><br />
        @empty
        @endforelse
        </p><p>After you submit your complaint with the {{ $overList }}, please log back in to update the community. 
        Also, please let us know whenever they receive or investigate your complaint. 
        Meanwhile, you can share your published complaint on Facebook, Twitter, or anywhere you like!</p>
    @endif
@elseif ($GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->ComStatus) == 'Submitted to Oversight')
    <p><b>Hi, {{ $user->name }},</b></p>
    <p>We just attempted to email your complaint to the {{ $overList }}. 
    But this shouldn't be the end of the road for you!</p>
    <p>Please contact the {{ $overList }} this week to confirm that your complaint has been received. 
    And as the investigation progresses, please log back in to update the community!</p>
    <p>If you can't confirm that your complaint was accepted by the {{ $overList }}, you'll need to submit it 
    another way to make sure it gets investigated. You can find the instructions for submitting your complaint 
    to the department here:</p><p>
    @forelse ($depts as $i => $d)
        <a href="/dept/{{ $d->DeptSlug }}" target="_blank">{{ $d->DeptName }}</a><br />
    @empty
    @endforelse
    </p><p>Thank you so much for using Open Police Complaints!</p>
@elseif ($GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->ComStatus) == 'Received by Oversight')
    <p><b>Hi, {{ $user->name }},</b></p>
    The {{ $overList }} received your complaint! But this shouldn't be the end of the road for you. 
    As the investigation progresses, please update the community here.    
@elseif ($GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->ComStatus) == 'Declined To Investigate (Closed)')
    <p><b>Hi, {{ $user->name }},</b></p>
@elseif ($GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->ComStatus) == 'Investigated (Closed)')
    <p><b>Hi, {{ $user->name }},</b></p>
@elseif ($GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->ComStatus) == 'Closed')
    <p><b>Hi, {{ $user->name }},</b></p>
@endif

        </div>
        <div class="p10"></div>
    </div>
    <div class="col-xl-4 col-lg-6">
        <div class="slCard">
        
    @if (!isset($complaint->ComStatus) || intVal($complaint->ComStatus) <= 0 || 
        $GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->ComStatus) == 'Incomplete')
            <a href="/switch/1/{{ $complaint->ComID }}" class="btn btn-lg btn-primary btn-block mB10 taL" id="ownBtnCont"
                ><i class="fa fa-pencil mR5"></i> Continue</a>
            <a href="javascript:;" class="btn btn-lg btn-danger w100 taL" id="ownBtnDel"
                onClick="if (confirm('{!! $warning !!}')) { window.location='/delSess/1/{{ $complaint->ComID }}'; }"
                ><i class="fa fa-trash-o mR5"></i> Delete</a>
    @else
        @if (!in_array($GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->ComStatus), 
            ['Hold', 'New', 'Reviewed']))
            <div class="mB10"><a id="hidivBtnUpdateStatus" class="btn btn-lg btn-primary btn-block taL hidivBtn"
                style="color: #FFF;" href="javascript:;"><i class="fa fa-refresh mR5" aria-hidden="true"></i> 
                Update Complaint Status</a></div>
            <div id="hidivUpdateStatus" class="mTn10 mB20 disNon">
                <form method="post" name="accessCode" action="?ownerUpdate=1">
                <input type="hidden" id="csrfTok" name="_token" value="{{ csrf_token() }}">
                <div class="nFld mT0">
                    <label class="finger">
                        <input type="radio" id="overStatus1" name="overStatus" autocomplete="off" 
                            value="Submitted to Oversight" 
                            @if ($GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->ComStatus) 
                                == 'Submitted to Oversight') CHECKED @endif > 
                            <span class="mL5">Submitted To Oversight Agency</span>
                    </label>
                    <label class="finger">
                        <input type="radio" id="overStatus2" name="overStatus" autocomplete="off" 
                            value="Received by Oversight" 
                            @if ($GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->ComStatus) 
                                == 'Received by Oversight') CHECKED @endif > 
                        <span class="mL5">Received By Oversight Agency</span>
                    </label>
                    <label class="finger">
                        <input type="radio" id="overStatus3" name="overStatus" autocomplete="off" 
                            value="Investigated (Closed)" 
                            @if ($GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->ComStatus) 
                                == 'Investigated (Closed)') CHECKED @endif >
                            <span class="mL5">Investigated By Oversight Agency</span>
                    </label>
                    <label class="finger">
                        <input type="radio" id="overStatus4" name="overStatus" autocomplete="off" 
                            value="Declined To Investigate (Closed)" 
                            @if ($GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->ComStatus) 
                                == 'Declined To Investigate (Closed)') CHECKED @endif > 
                            <span class="mL5">Oversight Agency Declined To Investigate</span>
                    </label>
                    <label class="finger">
                        <input type="radio" id="overStatus1" name="overStatus" autocomplete="off" 
                            value="Attorney'd" 
                            @if ($GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->ComStatus) 
                                == 'Attorney\'d') CHECKED @endif > 
                            <span class="mL5">Have An Attorney (Complaint Process On Hold)</span>
                    </label>
                </div>
                <div class="nFld mT10 mB20">
                    Notes about the status of this complaint:<br />
                    <textarea name="overNote" class="w100 mT5"></textarea>
                    <small class="slGrey mTn5">
                    This is for administrators of Open Police Complaints. We will not make it public.</small>
                </div>
                <center><input type="submit" value="Save Status Changes" class="btn btn-lg btn-primary"></center>
                </form>
                <div class="pT10"><hr></div>
            </div>
            <div class="mB20"> </div>
        @endif
            <a href="/complaint/read-{{ $complaint->ComPublicID }}/full-pdf" target="_blank"
                class="btn btn-lg btn-secondary btn-block taL" id="ownBtnPrnt"
                ><i class="fa fa-print mR5" aria-hidden="true"></i> Print Complaint / Save as PDF</a>
            <div class="mT20"><a href="/complaint/read-{{ $complaint->ComPublicID }}/full-xml" target="_blank"
                class="btn btn-lg btn-secondary btn-block taL" id="ownBtnDwnl"
                ><i class="fa fa-cloud-download mR5" aria-hidden="true"></i> Download Raw Data File</a></div>
                
            <h3 class="mT20 mB5">Email Complaint To:</h3>
            <div class="row">
                <div class="col-md-12 col-lg-7 col-xl-8">
                    <input value="{{ $user->email }}" type="text" class="form-control w100 mB5">
                </div><div class="col-md-12 col-lg-5 col-xl-4">
                    <a class="btn btn-secondary btn-block mB5" id="ownBtnSend" href="javascript:;"
                        ><nobr><i class="fa fa-envelope" aria-hidden="true"></i> Send</nobr></a>
                </div>
            </div>
            
            <h3 class="mT10 mB5">Link To Share:</h3>
            <input value="{{ $GLOBALS['SL']->sysOpts['app-url'] }}/complaint/read-{{ $complaint->ComPublicID 
                }}" type="text" class="form-control w100 mB5">
            <div class="disIn mR10">
                {!! view('vendor.survloop.elements.inc-social-simple-tweet', [
                    "link"  => $GLOBALS['SL']->sysOpts['app-url'] . '/complaint/read-' . $complaint->ComPublicID,
                    "title" => 'Check out this police complaint!'
                    ])->render() !!}
            </div>
            <div class="disIn">
                {!! view('vendor.survloop.elements.inc-social-simple-facebook', [
                    "link"  => $GLOBALS['SL']->sysOpts['app-url'] . '/complaint/read-' . $complaint->ComPublicID
                    ])->render() !!}
            </div>
        
    @endif
        
        </div>
        <div class="p10"></div>
    </div>
</div>

<style>
a#ownBtnDel:link, a#ownBtnDel:visited, a#ownBtnDel:active { color: #FFF; }
a#ownBtnDel:hover { color: #EC2327; }
a#ownBtnPrnt:hover, a#ownBtnDwnl:hover, a#ownBtnSend:hover { color: #FFF; }
a#ownBtnCont:link, a#ownBtnCont:visited, a#ownBtnCont:active { color: #FFF; }
a#ownBtnCont:hover { color: #2b3493; }
</style>