<!-- resources/views/vendor/openpolice/nodes/143-dept-loop-custom-row.blade.php -->

<div class="wrapLoopItem"><a name="item{{ $setIndex }}"></a>
    <div id="wrapItem{{ $loopItem->getKey() }}On" class="disIn">
        <h2 class="m0">{{ $loopItem->DeptName }}</h2>
        <a href="javascript:void(0)" id="delloopItem{{ $loopItem->getKey() }}" class="delloopItem slRedDark nFormLnkDel f14 nobld mR20"
            ><i class="fa fa-times"></i> Delete</a>
        <span class="f14 gry9 mL20">
            {{ $loopItem->DeptAddress }}, 
            @if (trim($loopItem->DeptAddress2) != '') {{ $loopItem->DeptAddress2 }}, @endif
            {{ $loopItem->DeptAddressCity }}, {{ $loopItem->DeptAddressState }} {{ $loopItem->DeptAddressZip }}
            @if (trim($loopItem->DeptAddressCounty) != '') , {{ $loopItem->DeptAddressCounty }} County @endif 
            @if (trim($loopItem->DeptPhoneWork) != '') , {{ $loopItem->DeptPhoneWork }}<br /> @endif
            @if (trim($loopItem->DeptWebsite) != '') , <a href="{{ $loopItem->DeptWebsite }}" target="_blank">{{ $loopItem->DeptWebsite }}</a> @endif 
        </span>
        <input type="checkbox" name="delItem[]" id="delItem{{ $loopItem->getKey() }}" value="{{ $loopItem->getKey() }}" class="disNon">
    </div>
    <div id="wrapItem{{ $loopItem->getKey() }}Off" class="wrapItemOff">
        <i class="mR20">Deleted: {{ $loopItem->DeptName }}</i> 
        <a href="javascript:void(0)" id="unDelloopItem{{ $loopItem->getKey() }}" class="unDelloopItem nFormLnkEdit f14 nobld mL20"
            ><i class="fa fa-undo"></i> Undo</a>
    </div>
</div>