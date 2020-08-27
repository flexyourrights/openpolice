<!-- resources/views/vendor/openpolice/nodes/1221-search-results-multi-data-sets.blade.php -->

@if (trim($searcher->searchTxt) == '')

    @if (!$dashView) <div class="slCard mT15"> @endif
    <h4 class="mT0">Search OpenPolice.org</h4>
    <h4 class="mT30 slBlueDark">
        Type something into the search bar above
    </h4>
    @if (!$dashView) </div> @endif

    <script type="text/javascript">
        setTimeout("topSearchFocus()", 100); 
    </script>

@else

    @if (!$dashView) <div class="slCard mT15 mB25"> @endif
    <h4 class="mT0">Search Results for "{{ $searcher->searchTxt }}"</h4>
    <div class="mB0">
        <a href="#departments">Departments</a> | 
        <a href="#complaints">Complaints</a>
    </div>
    <div class="nodeAnchor"><a id="departments" name="departments"></a></div>
    @if (!$dashView)
        </div><div class="slCard mB25">
    @else
        <div class="p10"></div>
    @endif
    <h4 class="mT0 slBlueDark">Police Departments</h4>
    <div id="tbl111SetSearchResults" class="w100"></div>
    <a class="btn btn-secondary mT15 mB15"
        href="/departments?s={{ urlencode(trim($searcher->searchTxt)) }}" 
        ><i class="fa fa-arrow-right mR3" aria-hidden="true"></i>
        Search & Browse Police Departments</a>
    <div class="nodeAnchor"><a id="complaints" name="complaints"></a></div>
    @if (!$dashView)
        </div><div class="slCard">
    @else
        <div class="p30"></div>
    @endif
    <h4 class="mT0 slBlueDark">Complaints</h4>
    <div id="tbl112SetSearchResults" class="w100"></div>
    <a class="btn btn-secondary mT15 mB15"
    @if ($isStaff)
        href="/dash/all-complete-complaints?s={{ urlencode(trim($searcher->searchTxt)) }}" 
    @else
        href="/complaints?s={{ urlencode(trim($searcher->searchTxt)) }}" 
    @endif
        ><i class="fa fa-arrow-right mR3" aria-hidden="true"></i>
        Search & Browse Complaints</a>
    @if (!$dashView) </div> @endif

    <script type="text/javascript"> $(document).ready(function(){

        function loadSetResults(type) {
            var divID = "tbl"+type+"SetSearchResults";
            if (document.getElementById(divID)) {
                var url = "/ajax/data-set-search-results?type="+type+"&s={!! (($GLOBALS['SL']->REQ->has('s')) ? urlencode($GLOBALS['SL']->REQ->get('s')) : '') !!}{!! (($GLOBALS['SL']->REQ->has('refresh')) ? '&refresh=1' : '') !!}";
                $("#"+divID+"").load(url);
                console.log(url);
            }
        }
        setTimeout(function() { loadSetResults(111); }, 1);
        setTimeout(function() { loadSetResults(112); }, 1000);

    }); </script>

@endif
