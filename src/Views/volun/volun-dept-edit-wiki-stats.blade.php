<!-- resources/views/vendor/openpolice/volun/volun-dept-edit-wiki-stats.blade.php -->
<div class="p5"></div>
<a href="https://en.wikipedia.org/wiki/{!! urlencode(str_replace(' ', '_', str_replace('Dept', 'Department', 
    $deptRow->DeptName))) !!}" class="btn btn-sm btn-secondary w100 mB10" target="_blank"
    >Department on <i class="fa fa-wikipedia-w"></i>ikipedia</a>
<div class="row">
    <div class="col-6">
        <a href="https://en.wikipedia.org/wiki/{!! urlencode(str_replace(' ', '_', $deptRow->DeptAddressCity . ', ' 
            . $GLOBALS['SL']->states->getState($deptRow->DeptAddressState))) !!}" 
            class="btn btn-sm btn-secondary w100" target="_blank"
            >City <i class="fa fa-wikipedia-w mL5"></i></a>
    </div><div class="col-6">
        <a href="https://en.wikipedia.org/wiki/{!! urlencode(str_replace(' ', '_', $deptRow->DeptAddressCounty . ' County, ' 
            . $GLOBALS['SL']->states->getState($deptRow->DeptAddressState))) !!}" 
            class="btn btn-sm btn-secondary w100" target="_blank"
            >County <i class="fa fa-wikipedia-w mL5"></i></a>
    </div>
</div>