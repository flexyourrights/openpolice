<!-- resources/views/vendor/openpolice/nodes/2715-dept-page-recent-reports.blade.php -->
@if ($GLOBALS["SL"]->REQ->has('test'))
<div style="padding-bottom: 13px;
    padding-top: 1px; margin-top: -33px;">
    <hr>
</div>
<h2 class="mT0">Recent Complaints <!--- & Compliments ---> </h2>
<div id="n{{ $nID }}ajaxLoad" class="w100">
<?php
// {!! $GLOBALS["SL"]->sysOpts["spinner-code"] !!}
?>
</div>
<div class="mBn5"><p>
    <a href="/filing-your-police-complaint/{{ $d['deptRow']->dept_slug }}"
        >File a complaint against this department.</a>
</p></div>
@endif