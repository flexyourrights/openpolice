<!-- resources/views/vendor/openpolice/nodes/2345-dash-top-stats.blade.php -->

<div id="opcAllTimeStats">
    <div class="row">
        <div class="col-lg-2 col-md-4 taC">
            <h2>{{ number_format($stats["betas"]) }}</h2>
            <p><nobr>
                <a href="/dash/beta-test-signups">Beta Invites</a>
            </nobr></p>
        </div>
        <div class="col-lg-2 col-md-4 taC">
            <h2>{{ number_format($stats["incomplete"]) }}</h2>
            <p>Incomplete</p>
        </div>
        <div class="col-lg-2 col-md-4 taC">
            <h2>{{ number_format($stats["complete"]) }}</h2>
            <p>Completed</p>
        </div>
        <div class="col-lg-2 col-md-4 taC">
            <h2>{{ number_format($stats["attorney"]) }}</h2>
            <p>Pending Attorney</p>
        </div>
        <div class="col-lg-2 col-md-4 taC">
            <h2>{{ number_format($stats["published"]) }}</h2>
            <p>Published</p>
        </div>
        <div class="col-lg-2 col-md-4 taC">
            <h2>{{ number_format($stats["submitted"]) }}</h2>
            <p><nobr>Investigations Filed</nobr></p>
        </div>
    </div>
</div>

<h4 class="mT30">
    Current Weekly Metrics: Talked to 
    <span class="slBlueDark">{{ number_format(sizeof($statsWeek["contactsU"])) }}</span>
    out of
    <span class="slBlueDark">{{ number_format(sizeof($statsWeek["activeU"])) }}</span>
    active users
</h4>
