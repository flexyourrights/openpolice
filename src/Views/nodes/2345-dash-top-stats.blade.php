<!-- resources/views/vendor/openpolice/nodes/2345-dash-top-stats.blade.php -->

<table id="opcAllTimeStats" cellpadding=0 cellspacing=0 border=0 ><tr>
    <td class="pL20 pR20">
        <h2 class="mT0"><nobr>All-Time Stats</nobr></h2>
        <p><a href="/dash/all-complete-complaints"
            ><nobr>Manage Complaints</nobr></a></p>
    </td>
    <td class="taC pL20 pR20"><h2>{{ 
        number_format($stats["betas"])
        }}</h2><p><nobr><a href="/dash/beta-test-signups">Beta Invites</a></nobr></p></td>
    <td class="taC pL20 pR20"><h2>{{ 
        number_format($stats["incomplete"]) 
        }}</h2><p>Incomplete</p></td>
    <td class="taC pL20 pR20"><h2>{{ 
        number_format($stats["complete"])   
        }}</h2><p>Completed</p></td>
    <td class="taC pL20 pR20"><h2>{{ 
        number_format($stats["attorney"])  
        }}</h2><p>Pending Attorney</p></td>
    <td class="taC pL20 pR20"><h2>{{ 
        number_format($stats["published"])  
        }}</h2><p>Published</p></td>
    <td class="taC pL20 pR20"><h2>{{ 
        number_format($stats["submitted"])  
        }}</h2><p><nobr>Investigations Filed</nobr></p></td>
</tr></table>
<div class="slBlueDark pL20">
    <p>
        Current Weekly Metrics: Talked to 
        <b>{{ number_format(sizeof($statsWeek["contactsU"])) }}</b>
        out of
        <b>{{ number_format(sizeof($statsWeek["activeU"])) }}</b>
        active users
    </p>
</div>
