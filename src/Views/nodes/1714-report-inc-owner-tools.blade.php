<!-- resources/views/vendor/openpolice/nodes/1714-report-inc-owner-tools.blade.php -->
<?php $hideUpdate = false; ?>

<h4>
Current Status:
@if (!isset($complaint->com_status) 
    || intVal($complaint->com_status) <= 0 
    || $GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->com_status) 
        == 'Incomplete')
    Incomplete
@else
    @if (isset($overUpdateRow->lnk_com_over_received) 
        && trim($overUpdateRow->lnk_com_over_received) != '')
        Received by Oversight
    @elseif ($GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->com_status)
        == 'Pending Attorney')
        Searching for Legal Help
    @elseif ($GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->com_status)
        == 'OK to Submit to Oversight')
        OK to File with Investigative Agency
    @else 
        {{ str_replace('Oversight', 'Investigative Agency', 
            $GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->com_status)) }}
    @endif
@endif
</h4>

@if ($GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->com_status) 
    == 'Incomplete')

    <p><b>Please use the button below to continue your complaint.</b></p>
    <p>Once it is complete, we will review it.</p>

@elseif (in_array($GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->com_status), 
    ['Hold', 'New', 'Reviewed']))

    <p><b>Congratulations, {{ $user->name }}!</b></p>
    <p>Within the next week, we will review your complaint. 
    If there are no problems, we will try to file it with the {{ $overList }}. 
    Then, we'll let you know what comes next. So hang tight!</p>

@elseif ($GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->com_status) 
    == 'Pending Attorney')

    @if ($complaint->com_anyone_charged == 'Y' 
        && in_array($complaint->com_all_charges_resolved, ['N', '?']))

        <p><b>Sorry to hear about this situation, {{ $user->name }}.</b></p>
        <p>We've reviewed your complaint, and we urge you to contact a local criminal defense 
        lawyer before you do anything else. Because you shared information that might harm 
        somebody's legal situation, we've unpublished your complaint. Do not post anything 
        online about this incident, and do not talk to the police without having a lawyer 
        with you. We are also actively looking for a lawyer to help you. Within a week, 
        we will email you with whatever we find.</p>
        <p>Please understand that we do not provide direct legal services. 
        But the work you put into your complaint could help your lawyer. 
        So please save and print your complaint.</p>

    @else

        <?php $hideUpdate = true; ?>
        <p><b>Hi {{ $user->name }},</b></p>
        <p>Although your police experience was awful, we were unable to find a qualified 
        attorney who will take your case. Unfortunately, there are few skilled civil rights 
        attorneys willing to take on new police misconduct cases. And the few they do accept, 
        generally involve misconduct resulting in severe injury or death.</p>
        <h4>Next Step: Publish & File Your Complaint</h4>
        <p>Based on the facts of your case, <b>we recommend that you file your complaint
        with the {{ $overList }}. We also recommend that you publish it on OpenPolice.org.</b>
        We can help you do both things.</p>
        <p>First, to publish your story on OpenPolice.org, please select your privacy option. 
        {!! $privacyForm !!}

    @endif

@elseif ($GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->com_status) 
    == 'Attorney\'d')

    <p><b>Hi, {{ $user->name }},</b></p>
    <p>We are glad you found a lawyer to help with your situation. 
    After all legal situations are resolved — or if advised by your lawyer — 
    you can safely choose to publish your story for the public to see.</p>
    <p>Please understand that we do not provide direct legal services. 
    But the work you put into your complaint could help your lawyer. 
    So please save and print your complaint.</p>

@elseif ($GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->com_status) 
    == 'OK to Submit to Oversight')

    @if (isset($oversights) 
        && sizeof($oversights) > 0 
        && isset($oversights[0]->over_email) 
        && trim($oversights[0]->over_email) != '' 
        && isset($oversights[0]->over_way_sub_email) 
        && intVal($oversights[0]->over_way_sub_email) == 1)

        <p><b>Congratulations, {{ $user->name }}!</b></p>
        <p>Within the next week, we will review your complaint. 
        If there are no problems, we will try to file it with the {{ $overList }}. 
        Then, we'll let you know what comes next. So hang tight!</p>

    @else

        <p><b>Hi, {{ $user->name }},</b></p>
        <p>We're almost done — but we need you to do one more important thing as soon as 
        possible. OpenPolice.org is working to get all police departments 
        to accept complaints sent by email. Unfortunately, the {{ $overList }} does not 
        investigate OpenPolice.org complaints sent by email.</p>
        <p>
        @if (isset($oversights) && sizeof($oversights) > 0 
            && isset($oversights[0]->over_way_sub_paper_in_person)
            && intVal($oversights[0]->over_way_sub_paper_in_person) == 1)
            You
        @else
            The good news is you can easily copy information from your 
            OpenPolice.org complaint to their required forms. And you
        @endif
        can find the instructions for formally submitting your complaint to the department 
        below. After you submit your complaint with the {{ $overList }}, please log back 
        in to update the community. Also, please let us know whenever they receive or 
        investigate your complaint.
        </p>

        @if (!isset($complaint->com_privacy) || intVal($complaint->com_privacy) == 0)
            <p><hr></p>
            <h4>Publishing Privacy Options</h4>
            {!! $privacyForm !!}
            <div class="p10"></div>
        @endif
        
        @if (isset($GLOBALS["SL"]->x["depts"]) && sizeof($GLOBALS["SL"]->x["depts"]) > 0)
            @foreach ($GLOBALS["SL"]->x["depts"] as $d)
                <p><hr></p>
                {!! str_replace('slCard mT20', '', str_replace('<h3 class="mT0">', '<h4>', 
                    str_replace('<h3>', '<h4>', str_replace('</h3>', '</h4>', 
                    view(
                        'vendor.openpolice.dept-page-filing-instructs', 
                        [
                            "d"          => $d,
                            "ownerTools" => true
                        ]
                    )->render())))) !!}
                <p><a href="/dept/{!! $d["deptRow"]->dept_slug !!}" target="_blank"
                    ><i class="fa fa-university" aria-hidden="true"></i>
                    {!! $d["deptRow"]->dept_name !!}</a></p>
            @endforeach
        @endif

    @endif

@elseif ($GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->com_status)
    == 'Submitted to Oversight')

    <p><b>Hi, {{ $user->name }},</b></p>
    <p>We just attempted to email your complaint to the {{ $overList }}. 
    But this shouldn't be the end of the road for you!</p>
    <p>Please contact the {{ $overList }} this week to confirm that your complaint 
    has been received. And as the investigation progresses, please log back in 
    to update the community!</p>
    <p>If you can't confirm that your complaint was accepted by the {{ $overList }}, 
    you'll need to submit it another way to make sure it gets investigated. You can 
    find the instructions for submitting your complaint to the department here:</p>
    <p>
    @forelse ($depts as $i => $d)
        <a href="/dept/{{ $d->dept_slug }}" target="_blank">{{ $d->dept_name }}</a><br />
    @empty
    @endforelse
    </p>
    <p>Thank you so much for using OpenPolice.org!</p>

@elseif ($GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->com_status) 
    == 'Received by Oversight')

    <p><b>Hi, {{ $user->name }},</b></p>
    The {{ $overList }} received your complaint! But this shouldn't be the end of the 
    road for you. As the investigation progresses, please update the community here.    

@elseif ($GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->com_status) 
    == 'Declined To Investigate (Closed)')

    <p><b>Hi, {{ $user->name }},</b></p>

@elseif ($GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->com_status) 
    == 'Investigated (Closed)')

    <p><b>Hi, {{ $user->name }},</b></p>

@elseif ($GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->com_status) 
    == 'Closed')

    <p><b>Hi, {{ $user->name }},</b></p>

@endif
<div class="p10"></div>

@if (in_array($GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->com_status), 
        [ 'Submitted to Oversight', 'Received by Oversight' ]) 
    && (!isset($complaint->com_privacy) || intVal($complaint->com_privacy) == 0))
    <p><hr></p>
    <h4>Publishing Privacy Options</h4>
    {!! $privacyForm !!}
    <div class="p10"></div>
@endif

@if (!isset($complaint->com_status) || intVal($complaint->com_status) <= 0 
    || $GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->com_status) == 'Incomplete')

    <div class="row">
        <div class="col-sm-6">
            <a href="/switch/1/{{ $complaint->com_id }}" id="ownBtnCont" 
                class="btn btn-lg btn-primary btn-block mB10 taL" style="color: #FFF;"
                onMouseOver="this.style.color='#2b3493';" onMouseOut="this.style.color='#FFF';"
                ><i class="fa fa-pencil mR5"></i> Continue</a>
        </div>
        <div class="col-sm-6">
            <a href="javascript:;" class="btn btn-lg btn-danger w100 taL" id="ownBtnDel" 
                onMouseOver="this.style.color='#EC2327';" onMouseOut="this.style.color='#FFF';" 
                onClick="if (confirm('{!! $warning !!}')) { window.location='/delSess/1/{{ 
                    $complaint->com_id }}'; }" style="color: #FFF;"
                ><i class="fa fa-trash-o mR5"></i> Delete</a>
        </div>
    </div>

@else

    @if (!in_array($GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->com_status), 
            ['Hold', 'New', 'Reviewed']) 
        && !$hideUpdate)

        <p><hr></p>
        <h4>Update Your Complaint Status</h4>
        <form method="post" name="accessCode" action="?ownerUpdate=1&refresh=1{{
            (($GLOBALS['SL']->REQ->has('frame')) ? '&frame=1' : '') }}">
        <input type="hidden" id="csrfTok" name="_token" value="{{ csrf_token() }}">

        {!! view('vendor.openpolice.nodes.1712-report-inc-tools-progress-dates', [
            "complaint"      => $complaint,
            "comDepts"       => $comDepts,
            "oversightDates" => $oversightDates
        ])->render() !!}

        <div class="nFld mT10 mB20">
            Notes about the status of this complaint:<br />
            <textarea name="overNote" class="w100 mT5"></textarea>
            <small class="slGrey mTn5">
            This is for administrators of OpenPolice.org. We will not make it public.</small>
        </div>
        <center><input type="submit" value="Save Status Changes" class="btn btn-lg btn-primary"
            onMouseOver="this.style.color='#2b3493';" onMouseOut="this.style.color='#FFF';"
            style="color: #FFF;"></center>
        </form>
        <div class="p20"></div>

    @endif

    <p>
        <a href="/complaint/read-{{ $complaint->com_public_id }}/full-pdf" 
            target="_blank" id="ownBtnPrnt"
            ><i class="fa fa-print mR5" aria-hidden="true"></i> 
            Print Complaint / Save as PDF</a>
    </p>
    <p><a href="/complaint/read-{{ $complaint->com_public_id }}/full-xml" 
        id="ownBtnDwnl" target="_blank"
        ><i class="fa fa-cloud-download mR5" aria-hidden="true"></i> 
        Download Raw Data File</a>
    </p>
        
    <p>Email Complaint To:</p>
    <div class="row">
        <div class="col-md-12 col-lg-7 col-xl-8">
            <input value="{{ $user->email }}" type="text" class="form-control w100 mB5">
        </div><div class="col-md-12 col-lg-5 col-xl-4">
            <a class="btn btn-secondary btn-block mB5" id="ownBtnSend" href="javascript:;"
                ><nobr><i class="fa fa-envelope" aria-hidden="true"></i> Send</nobr></a>
        </div>
    </div>
    
    <p class="pT20">Link To Share:</p>
    <input value="{{ $GLOBALS['SL']->sysOpts['app-url'] 
        }}/complaint/read-{{ $complaint->com_public_id 
        }}" type="text" class="form-control w100 mB5">
    <div class="disIn mR10">
        {!! view('vendor.survloop.elements.inc-social-simple-tweet', [
            "link"  => $GLOBALS['SL']->sysOpts['app-url'] 
                . '/complaint/read-' . $complaint->com_public_id,
            "title" => 'Check out this police complaint!'
        ])->render() !!}
    </div>
    <div class="disIn">
        {!! view('vendor.survloop.elements.inc-social-simple-facebook', [
            "link"  => $GLOBALS['SL']->sysOpts['app-url'] 
                . '/complaint/read-' . $complaint->com_public_id
        ])->render() !!}
    </div>

@endif

<div class="p10"></div>

<style>
#ajaxWrap { background: #F5FBFF; }
</style>