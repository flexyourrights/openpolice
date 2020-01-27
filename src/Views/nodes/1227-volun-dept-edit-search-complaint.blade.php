<!-- resources/views/vendor/openpolice/nodes/1227-volun-dept-edit-search-complaint.blade.php -->
<div class="nodeAnchor"><a id="deptWeb" name="deptWeb"></a></div>
<div class="row">
    <div class="col-7">
        <h2 class="m0">Web Presence & Complaint Process</h2>
        <p class="slGrey">Leave fields blank if the department doesn't have the thing.</p>
    </div><div class="col-5">
        <div class="p5"></div>
        <a href="https://www.google.com/search?as_q={{ 
            urlencode($deptRow->dept_name . ' file complaint against') }}" 
            class="btn btn-secondary" target="_blank"
            >Complaints Search <i class="fa fa-google mL10"></i></a>
    </div>
</div>