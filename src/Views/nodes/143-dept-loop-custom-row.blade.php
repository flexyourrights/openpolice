<!-- resources/views/vendor/openpolice/nodes/143-dept-loop-custom-row.blade.php -->

@if (isset($loopItem->dept_name))
<div class="wrapLoopItem">
    <div class="nodeAnchor"><a name="item{{ $setIndex }}"></a></div>
    <div id="wrapItem{{ $itemID }}On" class="slCard nodeWrap">
        <h3 class="m0">{{ $loopItem->dept_name }}</h3>
        <p>
        @if (intVal($loopItem->dept_type) > 0 
            && strpos($loopItem->dept_name, 'Not sure') === false) 
            {{ $GLOBALS['SL']->def->getValById($loopItem->dept_type) }},
        @endif
        {!! $GLOBALS["SL"]->printRowAddy($loopItem, 'Dept') !!}
        <?php /*
        @if (trim($loopItem->dept_address_county) != '') 
            , {{ $loopItem->dept_address_county }} County 
        @endif 
        @if (trim($loopItem->dept_phone_work) != '') 
            , {{ $loopItem->dept_phone_work }}<br /> 
        @endif
        @if (trim($loopItem->dept_website) != '') 
            , <a href="{{ $loopItem->dept_website }}" target="_blank"
            >{{ $loopItem->dept_website }}</a> 
        @endif
        */ ?>
        </p>
        <a href="javascript:;" id="editLoopItem{{ $itemID }}" 
            class="btn btn-secondary loopItemBtn editLoopItem"
            ><i class="fa fa-pencil fa-flip-horizontal"></i> Edit</a>
        <a href="javascript:;" id="delLoopItem{{ $itemID }}" 
            class="delLoopItem nFormLnkDel nobld btn btn-secondary loopItemBtn"
            data-item-id="{{ $itemID }}" data-item-label="{{ $loopItem->dept_name }}"
            ><i class="fa fa-trash-o"></i> Delete</a>
    </div>
</div>
@endif