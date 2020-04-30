<!-- resources/views/vendor/openpolice/nodes/2715-dept-page-recent-reports.blade.php -->
<div style="padding-bottom: 13px; padding-top: 1px; margin-top: -33px;"><hr></div>
<h2 class="mT0">Recent Complaints & Compliments</h2>
<div id="n{{ $nID }}ajaxLoad" class="w100">
	{!! $GLOBALS["SL"]->sysOpts["spinner-code"] !!}
</div>
<div class="mBn5"><p>
	<a href="/join-beta-test/{{ $d['deptRow']->dept_slug }}">
<?php /* href="/share-complaint-or-compliment/{{ $d['deptRow']->dept_slug }}" */ ?>
		OpenPolice.org is currently beta testing with individual users.
	</a>
</p></div>