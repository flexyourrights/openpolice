<!-- resources/views/vendor/openpolice/nodes/2720-dept-page-officer-listing.blade.php -->

<h3 class="mT0">Officers of {!! $d["deptRow"]->dept_name !!}</h3>
<p>This is a listing of all the officers with published complaints.</p>


@forelse ($officers as $off)
    <div class="row">
        <div class="col-3">
            <!-- ..?.. -->
        </div>
        <div class="col-3">
            <!-- ..?.. -->
        </div>
        <div class="col-3">
            <!-- ..?.. -->
        </div>
        <div class="col-3">
            <!-- ..?.. -->
        </div>
    </div>
@empty
    <i class="slGrey">None found.</i>
@endforelse



<?php /* Temporary Debugging Output: */ ?>
<div class="slGrey">
    <hr><hr>
    <i>Verified Officer Objects:</i>
    <div class="w100 ovrFlo">
        <pre>{!! print_r($officers) !!}</pre>
    </div>
</div>
