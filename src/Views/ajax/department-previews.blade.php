<!-- Stored in resources/views/openpolice/ajax/department-previews.blade.php -->

<div class="slCard mB15">
@if (isset($deptSearch) && trim($deptSearch) != '')
    @if (isset($stateName) && trim($stateName) != '')
        <h4 class="slBlueDark mB10">"{{ $deptSearch }}", {{ $stateName }}</h4>
    @else 
        <h4 class="slBlueDark mB10">"{{ $deptSearch }}"</h4>
    @endif
@elseif (isset($stateName) && trim($stateName) != '')
    <h4 class="slBlueDark mB10">{{ $stateName }}</h4>
@endif
@if (!isset($depts) || !$depts || sizeof($depts) == 0)
    @if ((isset($deptSearch) && trim($deptSearch) != '')
        || (isset($stateName) && trim($stateName) != ''))
        <h3>No search results found</h3>
    @endif
        Type in part of the name of a police department to search for it.
        Or select a state to browse law enforcement agencies in the OpenPolice.org 
        database.
@else

    {!! view(
        'vendor.openpolice.ajax.department-previews-table', 
        [
            "depts" => $depts,
            "limit" => ((isset($limit)) ? $limit : 1000)
        ]
    )->render() !!}

    <script type="text/javascript"> $(document).ready(function(){

    setTimeout(function() {
        if (document.getElementById("searchFoundCnt")) {
            document.getElementById("searchFoundCnt").innerHTML = '<nobr>{{ number_format(sizeof($depts)) }} Found</nobr>';
        }
        var target_offset = $("#results").offset();
        var target_top = target_offset.top;
        $('html, body').animate({scrollTop:target_top}, 1000, 'easeInSine');
    }, 100);

    }); </script>

@endif
</div>

<style>
#colHeadDeptScore { text-align: left; }
#colHeadDeptScoreLn2 { display: inline; }
@media screen and (max-width: 768px) {
    #colHeadDeptScore { position: absolute; top: -45px; right: 15px; text-align: right; }
    #colHeadDeptScoreLn2 { display: block; }
}
</style>
