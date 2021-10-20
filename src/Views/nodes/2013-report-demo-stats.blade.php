<!-- generated from resources/views/vendor/openpolice/nodes/2013-report-demo-stats.blade.php -->

<?php /*
<a class="float-right btn btn-secondary btn-sm mT5 mB15"
    @if (isset($fltStateClim) && trim($fltStateClim) != '')
        href="?excel=1&fltStateClim={{ $fltStateClim }}"
    @else
        href="?excel=1"
    @endif
    ><i class="fa fa-file-excel-o mR5" aria-hidden="true"></i> Excel</a>
*/ ?>
<h2 class="slBlueDark">Complaint Victims' Demographic Report</h2>

<p>
    <b>{{ sizeof($civsData) }} Alleged Victims</b> from
    {{ sizeof($coreRecs) }} Completed Complaints<br />
    <span class="slGrey">
    Numbers in grey are the raw record counts.
    You should be able to copy and paste these tables directly into a spreadsheet.
    </span>
</p>
<p><br /></p>

<h3>Table 1: Victims by Race</h3>
{!! $statRaces->pieTblMutliPercHasHgt('race', '', 420) !!}

<p><br /></p>
<p><br /></p>

<h3>Table 2: Victims by Race Groups</h3>
{!! $statRaces2->pieTblMutliPercHasHgt('race2', '', 240) !!}

<p><br /></p>
<p><br /></p>

<h3>Table 3: Victims by Age Group</h3>
{!! $statAges->pieTblMutliPercHasHgt('age', '', 420) !!}

<p><br /></p>
<p><br /></p>

<h3>Table 4: Victims by Gender</h3>
{!! $statGends->pieTblMutliPercHasHgt('gend', '', 240) !!}

<p><br /></p>
<p><br /></p>


<h3>Table 5: Victim Age Groups broken down by Race</h3>
{!! $statRaceAges->pieTblMutliPercHasHgt('race', 'age', 240) !!}

<p><br /></p>
<p><br /></p>


<h3>Table 6: Victim Age Groups broken down by Race Groups</h3>
{!! $statRace2Ages->pieTblMutliPercHasHgt('race2', 'age', 240) !!}

<p><br /></p>
<p><br /></p>

<h3>Table 7: Percentage of All Victims in Unreasonable Use of Force Complaint</h3>
{!! $statForceAll->pieTblMutliPercHasHgt('force', '', 240) !!}

<p><br /></p>
<p><br /></p>

<h3>Table 8: Percentage of Victim Age Groups in Unreasonable Use of Force Complaint</h3>
{!! $statForceAges->pieTblMutliPercHasHgt('force', 'age', 240) !!}

<p><br /></p>
<p><br /></p>

<h3>Table 9: Percentage of Victim Races in Unreasonable Use of Force Complaint</h3>
{!! $statForceRaces->pieTblMutliPercHasHgt('force', 'race', 240) !!}

<p><br /></p>
<p><br /></p>

<h3>Table 10: Percentage of Victim Race Groups in Unreasonable Use of Force Complaint</h3>
{!! $statForceRaces2->pieTblMutliPercHasHgt('force', 'race2', 240) !!}

<p><br /></p>
<p><br /></p>

<h3>Table 11: Percentage of Victim Genders in Unreasonable Use of Force Complaint</h3>
{!! $statForceGends->pieTblMutliPercHasHgt('force', 'gend', 240) !!}

<p><br /></p>
<p><br /></p>

<h3>Table 12: Percentage of All Victims in Complaint with Unresolved Charges</h3>
{!! $statChargeAll->pieTblMutliPercHasHgt('chrg', '', 240) !!}

<p><br /></p>
<p><br /></p>

<h3>Table 13: Percentage of Victim Races in Complaint with Unresolved Charges</h3>
{!! $statRaces->pieTblMutliPercHasHgt('chrg', 'race', 240) !!}

<p><br /></p>
<p><br /></p>

<h3>Table 14: Percentage of Victim Race Groups in Complaint with Unresolved Charges</h3>
{!! $statRaces2->pieTblMutliPercHasHgt('chrg', 'race2', 240) !!}

<p><br /></p>
<p><br /></p>


<?php /*
<h3>Table 2: Victims by Races</h3>
<p>Each victim may be more than one race.</p>
<table class="table w100">
{!! $statRaces->tblHeaderRowPercs('chrg') !!}
{!! $statRaces->printTblPercHasDat(
    'chrg',
    [
        'race318',
        'race319',
        'race320',
        'race469',
        'race321',
        'race322',
        'race323',
        'race324',
        'race325'
    ]
) !!}
{!! $statRaces->tblTotalsRowPercs('chrg') !!}
</table>

<p><br /></p>
<p><br /></p>

<h3>Table 3: Victims by Genders</h3>
<table class="table w100">
{!! $statGends->tblHeaderRowPercs('chrg') !!}
{!! $statGends->printTblPercHasDat(
    'chrg',
    [
        'gendF',
        'gendM',
        'gendO'
    ]
) !!}
{!! $statGends->tblTotalsRowPercs('chrg') !!}
</table>

*/ ?>

<p><br /></p>
<p><br /></p>


<hr>
<h3>Other Gender Descriptions</h3>
<table class="table table-striped w100">
    <tr><th>Raw Complaint ID#</th><th>Civilian ID#</th><th>Other Gender Description</th></tr>
@foreach ($civsData as $i => $civ)
    @if ($civ->gend == 'O' || trim($civ->gendOth) != '')
        <tr>
            <td><a href="/complaint/readi-{{ $civ->comID }}" target="_blank">Raw #{{ $civ->comID }}</a></td>
            <td>{{ $civ->civID }}</td>
            <td>{{ $civ->gendOth }}</td>
        </tr>
    @endif
@endforeach
</table>


<p><a href="?refresh=1"><i class="fa fa-refresh mR3" aria-hidden="true"></i> Refresh Report</a></p>

<?php /*
<h1>statAges:</h1><pre>{!! print_r($statAges) !!}</pre>
*/ ?>
