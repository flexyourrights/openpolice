<!-- resources/views/vendor/openpolice/inc-nav-admin.blade.php -->

@if (isset($admTopMenu))

    {!! $admTopMenu !!}
    <ul class="nav navbar-nav navbar-right">
    <li><a href="/dashboard">Dashboard</a></li>
    <li><a href="/auth/logout">Logout</a></li>
    </ul>
    
@else

    @if (isset($user) && $user->hasRole('volunteer'))
        <ul class="nav navbar-nav navbar-right">
        <li><a href="/dashboard">Dashboard</a></li>
        @if (isset($user)) <li><a href="/volunteer/user/{{ $user->id }}">Profile</a></li> @endif
        <li><a href="/auth/logout">Logout</a></li>
        </ul>
        <form name="volunDeptSearch" method="get" onSubmit="subVolunDeptSearch(); return false;" class="navbar-form navbar-right">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input id="deptNameID" name="deptName" type="text" 
            @if (isset($deptName) && trim($deptName) != '') 
                value="{{ $deptName }}" class="form-control"
            @else 
                value="Department Name..." class="form-control gry9"
            @endif >
        <label class="sr-only" for="stateID">Select Your State</label>
        <select id="stateID" name="state" class="form-control mL5 mR5">
            {!! $GLOBALS["DB"]->states->stateDrop($currState) !!}
        </select> 
        <a href="javascript:void(0)" onClick="return subVolunDeptSearch();" class="btn btn-med btn-primary f16"><i class="fa fa-search"></i></a>
        </form>
        
        <script type="text/javascript">
        $( document ).ready(function() {
            $("#deptNameID").focus(function() {
                if (document.getElementById("deptNameID").value == 'Department Name...') {
                    document.getElementById("deptNameID").value = '';
                    document.getElementById("deptNameID").className = 'form-control';
                }
            });
            $("#deptNameID").blur(function() {
                if (document.getElementById("deptNameID").value == '') {
                    document.getElementById("deptNameID").value = 'Department Name...';
                    document.getElementById("deptNameID").className = 'form-control gry9';
                }
            });
            
            $( "#deptNameID" ).autocomplete({ 
                select: function(e) { subVolunDeptSearch(); }, 
                source: [
                    @if (isset($searchSuggest)) {!! implode(', ', $searchSuggest) !!} @endif
                ]
            });
        });
        function subVolunDeptSearch() {
            var url = '';
            if (document.getElementById('stateID').value != '') url += '/s/'+document.getElementById('stateID').value;
            if (document.getElementById('deptNameID').value != '') url += '/d/'+encodeURIComponent(document.getElementById('deptNameID').value);
            if (url == '') url = '/all';
            window.location = '/volunteer'+url;
            return true;
        }
        </script>

    @elseif (isset($user) && ($user->hasRole('administrator') || $user->hasRole('staff') || $user->hasRole('brancher') || $user->hasRole('databaser')))
    
        <ul class="nav navbar-nav navbar-right">
        <li><a href="/dashboard">Dashboard</a></li>
        <li><a href="/volunteer/user/{{ $user->id }}">Profile</a></li>
        <li><a href="/auth/logout">Logout</a></li>
        </ul>
        <form class="navbar-form navbar-right">
        <input type="text" class="form-control" placeholder="Search..." style="height: 33px;">
        </form>
        
    @endif
    
@endif