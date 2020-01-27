<!-- resources/views/vendor/openpolice/ajax/search-officer.blade.php -->

<b>Similar Officers:</b><br /><i>(Click to Auto-Fill Their Info)</i>
<div style="max-height: 150px; overflow: auto; border: 1px #CCC dotted;">

@forelse($offs as $row)
    <a href="javascript:;" onClick="offvLoad('{{ htmlspecialchars($row->off_v_name_first) }}', '{{ 
        htmlspecialchars($row->off_v_name_middle) }}', '{{ htmlspecialchars($row->off_v_name_last) }}', '{{ 
        htmlspecialchars($row->off_v_badge_number) }}', '{{ htmlspecialchars($row->off_v_rank) }}');" 
        >{{ $row->off_v_name_first }} {{ $row->off_v_name_last }}, 
        {{ $row->off_v_rank }} #{{ $row->off_v_badge_number }}</a><br />
@empty
    no officers found<br />
@endforelse

</div>
