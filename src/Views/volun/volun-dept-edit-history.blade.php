<!-- resources/views/vendor/openpolice/volun/volun-dept-edit-history.blade.php -->
<div class="nodeAnchor"><a id="deptEdits" name="deptEdits"></a></div>
<h2 class="m0">Past Volunteer Edits</h2>

@if ($editsIA)
    <table class="table table-striped" >
    <tr>
        <th>Date</th>
        <th>Volunteer</th>
        <th><i class="fa fa-laptop"></i> Online Research, 
            <i class="fa fa-phone"></i> Called Dept, <i class="fa fa-phone"></i> Called IA</th>
        <th>Volunteer Notes</th>
    </tr>
    @forelse($editsIA as $i => $edit)
        @if ($edit && isset($edit->ZedOverOverID))
            <tr><td>
                @if (isset($edit->created_at)) {{ date("n/j/y", strtotime($edit->created_at)) }} @endif
            </td><td>
                @if (intVal($editsDept[$i]->ZedDeptUserID) > 0 && isset($userNames[$editsDept[$i]->ZedDeptUserID])) 
                    {!! $userNames[$editsDept[$i]->ZedDeptUserID] !!} @endif
            </td><td>
                <div class="mLn10">
                @if ($edit->ZedOverOnlineResearch == 0 && $edit->ZedOverMadeDeptCall == 0 
                    && $edit->ZedOverMadeIACall == 0) <span class="slGrey mL10">Minor Changes</span> 
                @else 
                    @if ($edit->ZedOverOnlineResearch == 1) <i class="fa fa-laptop mL10"></i> @endif
                    @if ($edit->ZedOverMadeDeptCall == 1) <i class="fa fa-phone mL10"></i>Dept @endif
                    @if ($edit->ZedOverMadeIACall == 1) <i class="fa fa-phone mL10"></i>IA @endif
                @endif
                </div>
            </td><td>
                @if (isset($edit->ZedOverNotes) && trim($edit->ZedOverNotes) != '')
                    {{ $edit->ZedOverNotes }}
                @endif
            </td></tr>
        @endif
    @empty
        <tr><td colspan=4 >no edits found</td></tr>
    @endforelse
    </table>
@else
    <div class="slGrey"><i>No volunteer edits of this departments yet.</i></div>
@endif

@if ($recentEdits != '')
    <div class="pT20">
    <h3 class="slBlueDark">Detailed Edit History...</h3>
    <table class="table table-striped" border=0 cellpadding=10 >
    <tr><th>Edit Details</th><th>Department Info</th><th>Internal Affairs</th><th>Civilian Oversight</th></tr>
    {!! $recentEdits !!}
    </table>
    </div>
@endif