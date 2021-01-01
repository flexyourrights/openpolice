<!-- resources/views/vendor/openpolice/nodes/2713-dept-page-calls-action.blade.php -->
<a class="btn btn-primary btn-lg"
    href="/filing-your-police-complaint/{{ $d['deptRow']->dept_slug }}"
<?php /*
@if (in_array(substr($d['deptRow']->dept_slug, 0, 3), ['NY-', 'MA-', 'MD-', 'MN-', 'DC-']))
    href="/filing-your-police-complaint/{{ $d['deptRow']->dept_slug }}"
@else
    href="/join-beta-test/{{ $d['deptRow']->dept_slug }}"
@endif
*/ ?>
    >File a Complaint or Compliment</a>
<div class="pT15 mT10"></div>
<style>
#node2707kids {
    padding-top: 30px;
}
#blockWrap2710 {
    margin-bottom: -20px;
}
</style>