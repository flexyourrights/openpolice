<!-- generated from resources/views/vendor/openpolice/nodes/1190-report-basic-stats.blade.php -->

<?php /*
<a class="float-right btn btn-secondary btn-sm mT5 mB15"
    @if (isset($fltStateClim) && trim($fltStateClim) != '')
        href="?excel=1&fltStateClim={{ $fltStateClim }}"
    @else
        href="?excel=1"
    @endif
    ><i class="fa fa-file-excel-o mR5" aria-hidden="true"></i> Excel</a>
*/ ?>
<h2 class="slBlueDark">Complaint Basic Statistics</h2>

<p>
    <b>{{ sizeof($coreRecs) }} Completed Complaints</b><br />
    <span class="slGrey">
    Numbers in grey are the raw record counts.
    You should be able to copy and paste these tables directly into a spreadsheet.
	</span>
</p>
<p><br /></p>

<h3>Table 1: Frequency of Allegations within Complaints</h3>
<p>
    If relevant to an incident, allegation questions are revealed and required.
</p>
{!! str_replace(
    'Wrongful Entry',
    'Wrongful Entry<sup>*</sup>',
    $statAllegs->printTblPercHasDat('chrg', $GLOBALS["CUST"]->getAllegIDs('aleg'))
) !!}
<?php /* {!! $statAllegs->pieTblMutliPercHas($fltRow, $fltCol) !!} */ ?>
<p>
    <b>* Wrongful Entry</b> allegations can only be made by 'Gold-level' complaints
    with a stop or detention: {{ number_format($goldStopCnt) }} out of all
    completed complaints, or {{ $GLOBALS["SL"]->sigFigs((100*$goldStopPerc), 3) }}%.
    Then out of those complaints, some were offered this allegation because
    an officer also entered a private home or workplace without permission.
</p>

<p><br /></p>
<p><br /></p>


<h3>Table 2: Frequency of Complainants 'Going Gold'</h3>
{!! $statEvents->pieTblMutliPercHasHgt('gold', '', 240) !!}

<p><br /></p>
<p><br /></p>

<h3>Table 3: Frequency of Incident Events within Complaints</h3>
<p>
    Responding to these questions is optional, and not all complaints
    have allegations tied to these types of events within one incident.
</p>
{!! $statEvents->printTblPercHasDat('gold', $GLOBALS["CUST"]->getAllEventAbbrs('eve')) !!}


<p><br /></p>
<p><br /></p>

<h3>Table 4: Frequency of Fully Published Complaints</h3>
<p>

</p>
{!! $publishTbl->print() !!}
<?php /*
<pre>{!! print_r($publishCnts) !!}</pre>
{!! $statPublish->printTblPercHasDat('status', ['transpar']) !!}
{!! $statPublish->pieTblMutliPercHasHgt('status', 'transpar', 240) !!}
*/ ?>

<p><br /></p>
<p><br /></p>

<h3>Table 5: Frequency of Complaints with New Departments</h3>
<p>

</p>
{!! $newDeptTbl->print() !!}
<?php /*
<pre>{!! print_r($deptCnts) !!}</pre>
{!! $statDeptsNew->printTblPercHasDat('week2', ['newDept']) !!}
{!! $statDeptsNew->pieTblMutliPercHasHgt('week2', 'newDept', 240) !!}
*/ ?>

<p><br /></p>
<p><br /></p>

<h3>Table 6: Frequency of Complaints by State</h3>
<div class="row">
	<div class="col-md-5">
        <p><b>Sorted Alphabetically</b></p>
        {!! $statesTbl->print() !!}
    </div>
	<div class="col-md-2"></div>
	<div class="col-md-5">
        <p><b>Sorted by Number of Complaints</b></p>
        {!! $statesTbl2->print() !!}
    </div>
</div>

<p><br /></p>
<p><br /></p>

<p><a href="?refresh=1"><i class="fa fa-refresh mR3" aria-hidden="true"></i> Refresh Report</a></p>


