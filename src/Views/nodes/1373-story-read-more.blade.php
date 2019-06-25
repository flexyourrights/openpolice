<!-- resources/views/vendor/openpolice/nodes/1373-story-read-more.blade.php -->
<div id="showStoryLess" class=" @if ($more) disNon @else disBlo @endif ">
    {!! $storyLess !!}...
    <div class="mT20">
        <a id="showBtnStoryMore" class="btn btn-primary" href="javascript:;">Read More</a>
    </div>
</div>
<div id="showStoryMore" class=" @if ($more) disBlo @else disNon @endif ">
    {!! $storyMore !!}
    <div class="mT20">
        <a id="showBtnStoryLess" class="btn btn-primary" href="javascript:;">Read Less</a>
    </div>
</div>