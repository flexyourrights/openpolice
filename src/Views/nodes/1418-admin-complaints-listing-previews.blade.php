<!-- resources/views/vendor/openpolice/nodes/1418-admin-complaints-listing-previews.blade.php -->

@if (sizeof($complaintsPreviews) == 0)
    No complaints found in this filter
@else
    @foreach ($complaintsPreviews as $prev)
    <div class="slCard nodeWrap">
        <div class="mTn20 mBn10">
            {!! $prev !!}
        </div>
    </div>
    @endforeach
@endif

<script type="text/javascript">
function fillResultCnt() {
    document.getElementById("searchFoundCnt").innerHTML={!! json_encode($complaintFiltDescPrev) !!};
    return true;
}
setTimeout("fillResultCnt()", 10);
</script>   