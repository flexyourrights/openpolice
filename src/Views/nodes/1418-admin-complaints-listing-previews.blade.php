<!-- resources/views/vendor/openpolice/nodes/1418-admin-complaints-listing-previews.blade.php -->
<?php
$cnt = 0;
$ids = []; // another guarantee to avoid duplicates
?>
@if (sizeof($complaintsPreviews) > 0)
    @foreach ($complaintsPreviews as $i => $prev)
        @if (($limit <= 0 || $cnt < $limit)
            && !in_array($complaintsPreviewsIDs[$i], $ids)
            && isset($complaintsPreviewsUser[$i])
            && $GLOBALS["SL"]->getUserProfilePicExists($complaintsPreviewsUser[$i]))
            <div id="complaintResultsAnim{{ $cnt }}" style="display: none;">
                <div class="slCard nodeWrap">
                    <div class="mTn20 mBn10">
                        {!! $prev !!}
                    </div>
                </div>
            </div>
            <?php
            $cnt++;
            $ids[] = $complaintsPreviewsIDs[$i];
            ?>
        @endif
    @endforeach
    @foreach ($complaintsPreviews as $i => $prev)
        @if (($limit <= 0 || $cnt < $limit)
            && !in_array($complaintsPreviewsIDs[$i], $ids)
            && (!isset($complaintsPreviewsUser[$i])
                || !$GLOBALS["SL"]->getUserProfilePicExists($complaintsPreviewsUser[$i]))
            && ($isStaffSort || $complaintsPreviewsPriv[$i]))
            <div id="complaintResultsAnim{{ $cnt }}" style="display: none;">
                <div class="slCard nodeWrap">
                    <div class="mTn20 mBn10">
                        {!! $prev !!}
                    </div>
                </div>
            </div>
            <?php
            $cnt++;
            $ids[] = $complaintsPreviewsIDs[$i];
            ?>
        @endif
    @endforeach
    @if (!$isStaffSort)
        @foreach ($complaintsPreviews as $i => $prev)
            @if (($limit <= 0 || $cnt < $limit)
                && !in_array($complaintsPreviewsIDs[$i], $ids)
                && (!isset($complaintsPreviewsUser[$i])
                    || !$GLOBALS["SL"]->getUserProfilePicExists($complaintsPreviewsUser[$i]))
                && !$complaintsPreviewsPriv[$i])
                <div id="complaintResultsAnim{{ $cnt }}" style="display: none;">
                    <div class="slCard nodeWrap">
                        <div class="mTn20 mBn10">
                            {!! $prev !!}
                        </div>
                    </div>
                </div>
                <?php 
                $cnt++;
                $ids[] = $complaintsPreviewsIDs[$i];
                ?>
            @endif
        @endforeach
    @endif
@else
    <div id="complaintResultsNone" class="pT15 pB15">No complaints found</div>
@endif
<div id="complaintResultsSpin">{!! $GLOBALS["SL"]->spinner() !!}</div>

<script type="text/javascript">
addResultAnimBase("complaintResults");

function fillResultCnt() {
    if (document.getElementById("searchFoundCnt")) {
        document.getElementById("searchFoundCnt").innerHTML={!! 
            json_encode('<nobr>' . $complaintFiltDescPrev . '</nobr>')
        !!};
    }
    return true;
}
setTimeout("fillResultCnt()", 10);

</script>   