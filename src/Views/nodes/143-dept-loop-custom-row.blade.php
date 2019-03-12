<!-- resources/views/vendor/openpolice/nodes/143-dept-loop-custom-row.blade.php -->

<div class="wrapLoopItem"><a name="item{{ $setIndex }}"></a>
    <div id="wrapItem{{ $itemID }}On" class="slCard nodeWrap">
        <h3 class="m0">{{ $loopItem->DeptName }}</h3>
        <p>
        @if (intVal($loopItem->DeptType) > 0 && strpos($loopItem->DeptName, 'Not sure') === false) 
            {{ $GLOBALS['SL']->def->getValById($loopItem->DeptType) }},
        @endif
        {!! $GLOBALS["SL"]->printRowAddy($loopItem, 'Dept') !!}
        <?php /*
        @if (trim($loopItem->DeptAddressCounty) != '') , {{ $loopItem->DeptAddressCounty }} County @endif 
        @if (trim($loopItem->DeptPhoneWork) != '') , {{ $loopItem->DeptPhoneWork }}<br /> @endif
        @if (trim($loopItem->DeptWebsite) != '') , <a href="{{ $loopItem->DeptWebsite }}" target="_blank"
            >{{ $loopItem->DeptWebsite }}</a> @endif
        */ ?>
        </p>
        <a href="javascript:;" id="editLoopItem{{ $itemID }}" class="btn btn-secondary loopItemBtn editLoopItem"
            ><i class="fa fa-pencil fa-flip-horizontal"></i> Edit</a>
        <a href="javascript:;" id="delLoopItem{{ $itemID }}" class="btn btn-secondary loopItemBtn nobld 
            delLoopItem nFormLnkDel"><i class="fa fa-times"></i> Delete</a>
        <input type="checkbox" class="disNon" name="delItem[]" id="delItem{{ $itemID }}" value="{{ $itemID }}" >
    </div>
    <div id="wrapItem{{ $itemID }}Off" class="wrapItemOff brdGrey round20 mB20">
        <i class="mR20 fL">Deleted: {{ $GLOBALS['SL']->closestLoop["obj"]->DataLoopSingular }} #{{ (1+$setIndex) }}: 
            {!! $loopItem->DeptName !!}</i> 
        <a href="javascript:;" id="unDelLoopItem{{ $itemID }}" class="unDelLoopItem nFormLnkEdit mL20 fR"
            ><i class="fa fa-undo"></i> Undo</a>
        <div class="fC"></div>
    </div>
</div>