<!-- resources/views/vendor/openpolice/nodes/2715-dept-page-recent-reports.blade.php -->
<h3 class="mT0">Recent Complaints & Compliments</h3>
<div id="n{{ $nID }}ajaxLoad" class="w100">
	{!! $GLOBALS["SL"]->sysOpts["spinner-code"] !!}
</div>
<div class="mBn5"><p>
	<a href="/join-beta-test/{{ $d['deptRow']->DeptSlug }}">
<?php /* href="/share-complaint-or-compliment/{{ $d['deptRow']->DeptSlug }}" */ ?>
		OpenPolice.org is currently beta testing with individual complainants.
	</a>
</p></div>