<!-- resources/views/vendor/openpolice/nodes/2960-all-departments-list-row.blade.php -->
<tr>
    <td> </td>
    <td>
        <a href="/dept/{{ $dept->dept_slug }}">{{ 
            str_replace('Department', 'Dept', $dept->dept_name) 
        }}</a>
    </td>
@if (isset($dept->dept_verified) && trim($dept->dept_verified) != '')
    <td>{{ $GLOBALS["SL"]->calcGrade($dept->dept_score_openness) }}</td>
    <td>{{ $dept->dept_score_openness }}</td>
@else
    <td colspan=2 >&nbsp;</td>
@endif
</tr>