<!-- resources/views/vendor/openpolice/nodes/1261-volun-dept-edit-wiki-stats.blade.php -->
<div class="p5"></div>
<a href="https://en.wikipedia.org/wiki/{!! 
    urlencode(str_replace(' ', '_', str_replace('Dept.', 'Department', $deptRow->dept_name)))
    !!}" class="btn btn-sm btn-secondary btn-block mB10" target="_blank"
    >Department <nobr>on <i class="fa fa-wikipedia-w"></i>ikipedia</nobr></a>
<div class="row">
    <div class="col-6">
        <a href="https://en.wikipedia.org/wiki/{!!
            urlencode(str_replace(' ', '_', $deptRow->dept_address_city . ', ' 
            . $GLOBALS['SL']->states->getState($deptRow->dept_address_state))) !!}" 
            class="btn btn-sm btn-secondary btn-block" target="_blank"
            >City</a>
    </div><div class="col-6">
        <a href="https://en.wikipedia.org/wiki/{!! 
            urlencode(str_replace(' ', '_', $deptRow->dept_address_county . ' County, ' 
            . $GLOBALS['SL']->states->getState($deptRow->dept_address_state))) !!}" 
            class="btn btn-sm btn-secondary btn-block" target="_blank"
            >County</a>
    </div>
</div>