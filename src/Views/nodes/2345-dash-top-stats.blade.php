<!-- resources/views/vendor/openpolice/nodes/2345-dash-top-stats.blade.php -->
<div class="relDiv w100">

<div class="absDiv" style="top: 250px; right: 15px; z-index: 100;">
    <p><b>Current Weekly Metrics:</b><br />
    {{ number_format(sizeof($statsWeek["contactsU"])) }} User talked to<br />
    {{ number_format(sizeof($statsWeek["activeU"])) }} WAUs
    </p>
</div>

<table id="opcAllTimeStats" cellpadding=0 cellspacing=0 border=0 ><tr>
    <td class="pL20 pR20">
        <h2 class="m0"><nobr>All-Time Stats</nobr></h2>
        <p><a href="/dash/all-complete-complaints"
            ><nobr>Manage Complaints</nobr></a></p>
    </td>
    <td class="taC pL20 pR20"><h2 class="mTn10">{{ 
        number_format($stats["betas"])
        }}</h2><nobr>Beta Invites</nobr></td>
    <td class="taC pL20 pR20"><h2 class="mTn10">{{ 
        number_format($stats["incomplete"]) 
        }}</h2>Incomplete</td>
    <td class="taC pL20 pR20"><h2 class="mTn10">{{ 
        number_format($stats["complete"])   
        }}</h2>Completed</td>
    <td class="taC pL20 pR20"><h2 class="mTn10">{{ 
        number_format($stats["processed"])  
        }}</h2>Processed</td>
    <td class="taC pL20 pR20"><h2 class="mTn10">{{ 
        number_format($stats["submitted"])  
        }}</h2><nobr>Investigation Filed</nobr></td>
</tr></table>

</div>