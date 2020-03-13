<!-- resources/views/vendor/openpolice/dept-page-filing-instructs-file-btn.blade.php -->

<?php /* href="/share-complaint-or-compliment/{{ $d['deptRow']->dept_slug }}" */ ?>
<center>
    <div class="d-sm-none taC">
        <a href="/join-beta-test/{{ $d['deptRow']->dept_slug }}"
            class="btn btn-primary btn-md"
            >File a Complaint or Compliment</a>
    </div>
    <div class="d-none d-sm-block taC">
        <a href="/join-beta-test/{{ $d['deptRow']->dept_slug }}"
            class="btn btn-primary btn-lg"
            >File a Complaint or Compliment</a>
    </div>
</center>
<div class="pB20"></div>