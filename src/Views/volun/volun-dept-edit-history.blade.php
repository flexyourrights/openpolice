<!-- resources/views/vendor/openpolice/volun/volun-dept-edit-history.blade.php -->
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
        @if ($edit && isset($edit->zed_over_over_id))
            <tr><td>
                @if (isset($edit->created_at)) {{ date("n/j/y", strtotime($edit->created_at)) }} @endif
            </td><td>
                @if (intVal($editsDept[$i]->zed_dept_user_id) > 0 
                    && isset($userNames[$editsDept[$i]->zed_dept_user_id])) 
                    {!! $userNames[$editsDept[$i]->zed_dept_user_id] !!} @endif
            </td><td>
                <div class="mLn10">
                @if ($edit->zed_over_online_research == 0 && $edit->zed_over_made_dept_call == 0 
                    && $edit->zed_over_made_ia_call == 0) <span class="slGrey mL10">Minor Changes</span> 
                @else 
                    @if ($edit->zed_over_online_research == 1) <i class="fa fa-laptop mL10"></i> @endif
                    @if ($edit->zed_over_made_dept_call == 1) <i class="fa fa-phone mL10"></i>Dept @endif
                    @if ($edit->zed_over_made_ia_call == 1) <i class="fa fa-phone mL10"></i>IA @endif
                @endif
                </div>
            </td><td>
                @if (isset($edit->zed_over_notes) && trim($edit->zed_over_notes) != '')
                    {{ $edit->zed_over_notes }}
                @endif
            </td></tr>
        @endif
    @empty
        <tr><td colspan=4 >no edits found</td></tr>
    @endforelse
    </table>
@else
    <div class="slGrey"><i>No volunteer edits of this department yet.</i></div>
@endif

@if ($recentEdits != '')
    <div class="pT20">
    <h3 class="slBlueDark">Detailed Edit History...</h3>
    <table class="table table-striped" border=0 cellpadding=10 >
    <tr>
        <th>Edit Details</th>
        <th>Department Info</th>
        <th>Internal Affairs</th>
        <th>Civilian Oversight</th>
    </tr>
    {!! $recentEdits !!}
    </table>
    </div>
@endif