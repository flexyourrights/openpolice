<!-- resources/views/vendor/openpolice/volun/volunteer-recent-edits.blade.php -->
<span class="pull-right">
@if (isset($GLOBALS["SL"]->x["deptEditing"])
    && isset($GLOBALS["SL"]->x["deptEditing"][$deptID])
    && trim($GLOBALS["SL"]->x["deptEditing"][$deptID]) != '')
    <span class="slGreenDark"><nobr>Editing Now:</nobr> 
    {!! $GLOBALS["SL"]->x["deptEditing"][$deptID] !!}
    <i class="fa fa-commenting mL3" aria-hidden="true"></i></span></nobr>
@elseif (isset($GLOBALS["SL"]->x["recentDeptEdits"][$deptID]))
    @if (isset($GLOBALS["SL"]->x["usernames"][$GLOBALS["SL"]->x["recentDeptEdits"][$deptID]]))
        {!! $GLOBALS["SL"]->x["usernames"][$GLOBALS["SL"]->x["recentDeptEdits"][$deptID]] !!}
    @endif
@endif
</span>