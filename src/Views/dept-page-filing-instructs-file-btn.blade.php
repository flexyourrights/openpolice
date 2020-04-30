<!-- resources/views/vendor/openpolice/dept-page-filing-instructs-file-btn.blade.php -->

<?php /* 
href="/share-complaint-or-compliment/{{ $d['deptRow']->dept_slug }}" 
*/ ?>

<div class="d-none d-md-block">
    <p><a class="btn btn-xl btn-primary btn-block mT20 mB20" 
        href="/join-beta-test/{{ $d['deptRow']->dept_slug }}"
        >File a Complaint or Compliment</a>
    </p>
</div>
<div class="d-md-none">
    <p><a class="btn btn-lg btn-primary btn-block mT20 mB20" 
        href="/join-beta-test/{{ $d['deptRow']->dept_slug }}"
        >File a Complaint or Compliment</a>
    </p>
</div>
