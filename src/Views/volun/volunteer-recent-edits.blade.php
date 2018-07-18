<!-- resources/views/vendor/openpolice/volun/volunteer-recent-edits.blade.php -->
@if (isset($GLOBALS["SL"]->x["recentDeptEdits"][$deptID]))
    <i class="fa fa-pencil"></i>
    @forelse ($GLOBALS["SL"]->x["recentDeptEdits"][$deptID] as $uID => $date)
        <span class="mR10">
        @if (isset($GLOBALS["SL"]->x["usernames"][$uID]))
            <a href="/profile-{{ $uID }}">{{ $GLOBALS["SL"]->x["usernames"][$uID] }}</a> 
        @endif
        {{ $GLOBALS["SL"]->printTimeAgo($date) }}</span>
    @empty
    @endforelse
@endif