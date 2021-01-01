<!-- resources/views/vendor/openpolice/dept-page-filing-instructs-file-btn.blade.php -->
<?php /* /share-complaint-or-compliment/{slug} */ ?>
<div class="d-none d-md-block">
    <p><a class="btn btn-xl btn-primary btn-block mT20 mB20" 
        href="/filing-your-police-complaint/{{ $d['deptRow']->dept_slug }}"
<?php /*
@if (in_array(substr($d['deptRow']->dept_slug, 0, 3), ['NY-', 'MA-', 'MD-', 'MN-', 'DC-']))
        href="/filing-your-police-complaint/{{ $d['deptRow']->dept_slug }}"
@else
        href="/join-beta-test/{{ $d['deptRow']->dept_slug }}"
@endif
*/ ?>
        >File a Complaint or Compliment</a>
    </p>
</div>
<div class="d-md-none">
    <p><a class="btn btn-lg btn-primary btn-block mT20 mB20" 
        href="/filing-your-police-complaint/{{ $d['deptRow']->dept_slug }}"
        >File a Complaint or Compliment</a></p>
<?php /*
    <p><a class="btn btn-lg btn-primary btn-block mT20 mB20" 
@if (in_array(substr($d['deptRow']->dept_slug, 0, 3), ['NY-', 'MA-', 'MD-', 'MN-', 'DC-']))
        href="/filing-your-police-complaint/{{ $d['deptRow']->dept_slug }}"
@else
        href="/join-beta-test/{{ $d['deptRow']->dept_slug }}"
@endif
        >File a Complaint or Compliment</a>
    </p>
*/ ?>

</div>
