<!-- resources/views/vendor/openpolice/nodes/2345-dash-top-stats.blade.php -->

<h4 class="mT5">Current Weekly Metrics</h4>
<div class="row mT20">
    <div class="col-lg-3 col-md-6 taC">
        <h1 class="mB0 slBlueDark">{{ number_format(sizeof($statsWeek["complete"])) }}</h1>
        <p><nobr><b>Completed Complaints</b></nobr></p>
    </div>
    <div class="col-lg-3 col-md-6 taC">
        <h1 class="mB0 slBlueDark">{{ number_format(sizeof($statsWeek["published"])) }}</h1>
        <p><b>Published on OpenPolice.org</b></p>
    </div>
    <div class="col-lg-3 col-md-6 taC">
        <h1 class="mB0 slBlueDark">{{ number_format(sizeof($statsWeek["submitted"])) }}</h1>
        <p><b>Published & Filed with IAs</b></p>
    </div>
</div>

<div class="w100 pT15 pB15"><div class="w100 brdTopGrey"></div></div>

<div class="mT15">

    <h4>All-Time Stats</h4>
    <div id="opcAllTimeStats" class="mT20">
        <div class="row">
            <div class="col-lg-3 col-md-6 taC">
                <h1 class="mB0 slBlueDark">{{ number_format($stats["complete"]) }}</h1>
                <p><b>Completed Complaints</b></p>
            </div>
            <div class="col-lg-3 col-md-6 taC">
                <h1 class="mB0 slBlueDark">{{ number_format($stats["incomplete"]) }}</h1>
                <p><b>Incomplete Complaints</b></p>
            </div>
            <div class="col-lg-3 col-md-6 taC">
                <h1 class="mB0 slBlueDark">{{ number_format($stats["attorneyWant"]) }}</h1>
                <p><b>Wants but Doesn't Need Attorney</b></p>
            </div>
            <div class="col-lg-3 col-md-12"></div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-6 taC">
                <h1 class="mB0 slBlueDark">{{ number_format($stats["published"]) }}</h1>
                <p><b>Published on OpenPolice.org</b></p>
            </div>
            <div class="col-lg-3 col-md-6 taC">
                <h1 class="mB0 slBlueDark">{{ number_format($stats["submitted"]) }}</h1>
                <p><b>Published & Filed with IAs</b></p>
            </div>
            <div class="col-lg-3 col-md-6 taC">
                <h1 class="mB0 slBlueDark">{{ number_format($stats["attorney"]) }}</h1>
                <p><b>Needs or Already Has Attorney</b></p>
            </div>
            <div class="col-lg-3 col-md-12"></div>
        </div>
    </div>

</div>

<div class="w100 pT15 pB15"><div class="w100 brdTopGrey"></div></div>
