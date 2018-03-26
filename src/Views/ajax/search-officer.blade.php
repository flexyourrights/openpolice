<!-- resources/views/vendor/openpolice/ajax/search-officer.blade.php -->

<b>Similar Officers:</b><br /><i>(Click to Auto-Fill Their Info)</i><div style="max-height: 150px; overflow: auto; border: 1px #CCC dotted;">

@forelse($offs as $row)
    <a href="javascript:;" onClick="offvLoad('{{ htmlspecialchars($row->OffVNameFirst) }}', '{{ htmlspecialchars($row->OffVNameMiddle) }}', '{{ htmlspecialchars($row->OffVNameLast) }}', '{{ htmlspecialchars($row->OffVBadgeNumber) }}', '{{ htmlspecialchars($row->OffVRank) }}');" 
        >{{ $row->OffVNameFirst }} {{ $row->OffVNameLast }}, {{ $row->OffVRank }} #{{ $row->OffVBadgeNumber }}</a><br />
@empty
    no officers found<br />
@endforelse

</div>
