<!-- resources/views/vendor/openpolice/nodes/1261-volun-dept-edit-wiki-stats.blade.php -->
<div class="p5"></div>
<a href="https://en.wikipedia.org/wiki/{!! urlencode(str_replace(' ', '_', str_replace('Dept', 'Department', 
    $deptRow->DeptName))) !!}" class="btn btn-sm btn-secondary btn-block mB10" target="_blank"
    >Department <nobr>on <i class="fa fa-wikipedia-w"></i>ikipedia</nobr></a>
<div class="row">
    <div class="col-6">
        <a href="https://en.wikipedia.org/wiki/{!! urlencode(str_replace(' ', '_', $deptRow->DeptAddressCity . ', ' 
            . $GLOBALS['SL']->states->getState($deptRow->DeptAddressState))) !!}" 
            class="btn btn-sm btn-secondary btn-block" target="_blank"
            >City</a>
    </div><div class="col-6">
        <a href="https://en.wikipedia.org/wiki/{!! urlencode(str_replace(' ', '_', $deptRow->DeptAddressCounty . ' County, ' 
            . $GLOBALS['SL']->states->getState($deptRow->DeptAddressState))) !!}" 
            class="btn btn-sm btn-secondary btn-block" target="_blank"
            >County</a>
    </div>
</div>