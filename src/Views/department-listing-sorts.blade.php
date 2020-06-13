<!-- Stored in resources/views/openpolice/department-listing-sorts.blade.php -->

<button id="fltDeptSortTypeMatch" type="button" data-sort-type="match"
    class="fltDeptSortTypeBtn dropdown-item @if ($sortLab == 'match') active @endif "
    >by Search Relevance & Department Size</button>

<button id="fltDeptSortTypeName" type="button" data-sort-type="name"
    class="fltDeptSortTypeBtn dropdown-item @if ($sortLab == 'name') active @endif "
    >by Department Name</button>

<button id="fltDeptSortTypeCity" type="button" data-sort-type="city"
    class="fltDeptSortTypeBtn dropdown-item @if ($sortLab == 'city') active @endif "
    >by City of Incident</button>

<button id="fltDeptSortTypeScore" type="button" data-sort-type="score"
    class="fltDeptSortTypeBtn dropdown-item @if ($sortLab == 'score') active @endif "
    >by Accessibility Score</button>

<div class="dropdown-divider"></div>

<button id="fltDeptSortDirAsc" type="button" data-sort-dir="asc"
    class="fltDeptSortDirBtn dropdown-item @if ($sortDir == 'asc') active @endif "
    >Ascending</button>

<button id="fltDeptSortDirDesc" type="button" data-sort-dir="desc"
    class="fltDeptSortDirBtn dropdown-item @if ($sortDir == 'desc') active @endif "
    >Descending</button>
