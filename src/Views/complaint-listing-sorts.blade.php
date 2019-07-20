<!-- Stored in resources/views/openpolice/complaint-listing-sorts.blade.php -->

<button type="button" data-sort-type="date"
    class="fltSortTypeBtn dropdown-item @if ($sortLab == 'date') active @endif "
    >by Date Submitted to OPC</button>

<button type="button" data-sort-type="first-name"
    class="fltSortTypeBtn dropdown-item @if ($sortLab == 'first-name') active @endif "
    >by Complainant First Name</button>

<button type="button" data-sort-type="last-name"
    class="fltSortTypeBtn dropdown-item @if ($sortLab == 'last-name') active @endif "
    >by Complainant Last Name</button>

<button type="button" data-sort-type="city"
    class="fltSortTypeBtn dropdown-item @if ($sortLab == 'city') active @endif "
    >by City of Incident</button>

<div class="dropdown-divider"></div>

<button type="button" data-sort-dir="asc"
    class="fltSortDirBtn dropdown-item @if ($sortDir == 'asc') active @endif "
    >Ascending</button>

<button type="button" data-sort-dir="desc"
    class="fltSortDirBtn dropdown-item @if ($sortDir == 'desc') active @endif "
    >Descending</button>
