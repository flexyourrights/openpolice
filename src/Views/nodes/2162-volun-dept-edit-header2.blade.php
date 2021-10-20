<!-- resources/views/vendor/openpolice/nodes/2162-volun-dept-edit-header2.blade.php -->
<div class="row">
    <div class="col-7">
        <h2 class="m0">Department Main Contact Info</h2>
    </div><div class="col-5">
        <a href="https://www.google.com/search?as_q={{
            urlencode($deptRow->dept_name . ' ' . $deptRow->dept_address_state
                . ' ' . $deptRow->dept_address_zip) }}"
            class="btn btn-secondary" target="_blank"
            >Begin Department Search <i class="fa fa-google mL10"></i></a>
    </div>
</div>
<style>
#node2154 { margin-top: 110px; }
</style>