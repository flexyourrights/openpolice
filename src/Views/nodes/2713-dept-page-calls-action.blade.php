<!-- resources/views/vendor/openpolice/nodes/2713-dept-page-calls-action.blade.php -->
<a href="/join-beta-test/{{ $d['deptRow']->dept_slug }}"
<?php /* href="/share-complaint-or-compliment/{{ $d['deptRow']->dept_slug }}" */ ?>
    class="btn btn-primary btn-lg w100"
    >Share Your Complaint or Compliment with the {!! 
        str_replace('Police Dept', '<nobr>Police Dept</nobr>', 
            str_replace('Department', 'Dept', $d["deptRow"]->dept_name))
   	!!}
</a>