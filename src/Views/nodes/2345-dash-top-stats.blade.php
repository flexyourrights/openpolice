<!-- resources/views/vendor/openpolice/nodes/2345-dash-top-stats.blade.php -->
<h2>OPC All-Time Stats</h2>
<p><a href="/dash/all-complete-complaints">Manage Complaints</a></p>
<div class="row bld mT10">
    <div class="col-2 taC"><h2>{{ number_format($betas)      }}</h2>Beta Invites</div>
    <div class="col-2 taC"><h2>{{ number_format($incomplete) }}</h2>Incomplete</div>
    <div class="col-2 taC"><h2>{{ number_format($complete)   }}</h2>Completed</div>
    <div class="col-2 taC"><h2>{{ number_format($processed)  }}</h2>Processed</div>
    <div class="col-2 taC"><h2>{{ number_format($submitted)  }}</h2>Investigation Filed</div>
    <div class="col-2"></div>
</div>
