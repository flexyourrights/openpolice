<!-- resources/views/vendor/openpolice/volun/volun-dept-edit-ia-contact.blade.php -->
<div class="nodeAnchor"><a id="dept{{ $whch }}" name="dept{{ $whch }}"></a></div><hr>
@if ($whch == 'Civ' && !$hasCiv)
    <a id="hidnodeBtn1319" href="javascript:;" class="btn btn-xl btn-secondary hidnodeBtnSelf"
        ><i class="fa fa-plus-circle"></i> Add Civilian Oversight Office</a>
@endif
<div id="overHead{{ $whch }}" class="row @if ($whch == 'IA' || $hasCiv) disBlo @else disNon @endif ">
    <div class="col-7">
        <h2 class="m0">{{ $type }} Office</h2>
    </div><div class="col-5">
        @if (!$hasC)
            <a id="hidnodeBtn{{ $n }}" href="javascript:;" class="btn btn-secondary hidnodeBtnSelf"
                ><i class="fa fa-plus-circle"></i> Add Primary Contact</a>
        @endif
        <h3 id="h3Btn{{ $n }}" class="mT5 @if ($hasC) disBlo @else disNon @endif ">Primary Contact:</h3>
    </div>
</div>
<style>
@if (!$hasC) #node{{ $n }} { display: none; } @endif
@if ($whch == 'Civ' && !$hasCiv) #node1319 { display: none; } @endif
</style>
<script type="text/javascript">
$(document).ready(function(){
    $(document).on("click", "#hidnodeBtn{{ $n }}", function() {
	    setNodeVisib({{ $n }}, "", true);
	    setNodeVisib(1312, "", true);
	    setNodeVisib(1341, "", true);
        setTimeout(function() { $("#h3Btn{{ $n }}").slideDown("fast"); }, 350);
	});
@if ($whch == 'Civ' && !$hasCiv)
    setTimeout(function() {
        setNodeVisib({{ $n }}, "", false);
        setNodeVisib(1312, "", false);
        setNodeVisib(1341, "", false);
    }, 10);
	$(document).on("click", "#hidnodeBtn1319", function() { $("#overHead{{ $whch }}").slideDown("fast"); });
@endif
}); </script>