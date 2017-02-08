<!-- resources/views/vendor/openpolice/nodes/143-dept-loop-custom-row.blade.php -->

<div class="wrapLoopItem"><a name="item{{ $setIndex }}"></a>
    <div id="wrapItem{{ $itemID }}On">
        <div class="row brdA round20 mB20">
            <div class="fL p20">
                <h4 class="m0 gry9">
                    {{ $GLOBALS["DB"]->closestLoop["obj"]->DataLoopSingular }} #{{ (1+$setIndex) }}:</h4>
                <h2 class="m0">{{ $loopItem->DeptName }}</h2>
                <p>
                @if (trim($loopItem->DeptAddress) != '') {{ $loopItem->DeptAddress }}, @endif
                @if (trim($loopItem->DeptAddress2) != '') {{ $loopItem->DeptAddress2 }}, @endif
                @if (trim($loopItem->DeptAddressCity) != '') {{ $loopItem->DeptAddressCity }}, @endif
                {{ $loopItem->DeptAddressState }} {{ $loopItem->DeptAddressZip }}
                @if (trim($loopItem->DeptAddressCounty) != '') , {{ $loopItem->DeptAddressCounty }} County @endif 
                @if (trim($loopItem->DeptPhoneWork) != '') , {{ $loopItem->DeptPhoneWork }}<br /> @endif
                @if (trim($loopItem->DeptWebsite) != '') , <a href="{{ $loopItem->DeptWebsite }}" target="_blank"
                    >{{ $loopItem->DeptWebsite }}</a> @endif 
                </p>
            </div>
            <a href="javascript:;" id="editLoopItem{{ $itemID }}" class="editLoopItem btn btn-default m10 mT20 fR"
                ><i class="fa fa-pencil fa-flip-horizontal"></i> Edit</a>
            <a href="javascript:;" id="delLoopItem{{ $itemID }}" 
                class="delLoopItem nFormLnkDel nobld btn btn-default m10 mT20 fR"
                ><i class="fa fa-times"></i> Delete</a>
            <input type="checkbox" class="disNon" 
                name="delItem[]" id="delItem{{ $itemID }}" value="{{ $itemID }}" >
            <div class="fC p5"></div>
        </div>
    </div>
    <div id="wrapItem{{ $itemID }}Off" class="wrapItemOff">
        <i class="mR20">Deleted: {{ $GLOBALS["DB"]->closestLoop["obj"]->DataLoopSingular }} #{{ (1+$setIndex) }}: 
            {!! $loopItem->DeptName !!}</i> 
        <a href="javascript:;" id="unDelLoopItem{{ $itemID }}" class="unDelLoopItem nFormLnkEdit f14 nobld mL20"
            ><i class="fa fa-undo"></i> Undo</a>
    </div>
</div>