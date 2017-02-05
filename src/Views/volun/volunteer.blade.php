<!-- resources/views/vendor/openpolice/volun/volunteer.blade.php -->

@extends('vendor.survloop.admin.admin')

@section('content')

<div class="row">
    <div class="col-md-6">

        <div class="row">
            @if ($viewType == 'all')
                <div class="col-md-8">
                    <h2 class="mB0">All Departments</h2>
                    <div class="pB5 gry6">Click a department below to verify it's information:</div>
            @elseif ($viewType != 'search')
                <div class="col-md-8">
                    <h2 class="mB0">Priority Departments</h2>
                    <div class="pB5 gry6">Click a department below to verify it's information:</div>
            @else
                <div class="col-md-6">
                    <h3 class="mBn20">Department Search:</h3>
                    <div class="pL20"><h2>
                        @if (isset($deptName) && trim($deptName) != '') {{ $deptName }} @endif
                    </h2></div>
            @endif
            </div>
            @if ($viewType == 'all')
                <div class="col-md-4 pT20 taR">
                    <a class="btn btn-med btn-default" href="/volunteer">Show Priority</a>
            @elseif ($viewType != 'search')
                <div class="col-md-4 pT20 taR">
                    <a class="btn btn-med btn-default" href="/volunteer/all">Show All Departments</a>
            @else
                <div class="col-md-6 pT20 taR">
                    <a class="btn btn-med btn-default" href="/volunteer/all">All Departments</a>
                    <a class="btn btn-med btn-default" href="/volunteer">Priority</a>
                    <div class="pT5 pB5 gry6">Click a department below<br />to verify it's information:</div>
            @endif
            </div>
        </div>
        
        @if ($viewType != 'priority') 
            <div>{!! $deptRows->render() !!}</div>
        @endif
        
        <div class="list-group taL">
            <a class="list-group-item active">
                <span class="pull-right">Accessibility Score, <i>Last Verified</i></span>
                Police Department Name, <i>City, State</i>
            </a>
            
            @forelse ($deptRows as $dept)
                <a class="list-group-item" href="/volunteer/verify/{{ $dept->DeptSlug }}">
                    <div class="pull-right taR">
                        @if (intVal($dept->DeptScoreOpenness) > 0)
                            <div class="f22">{{ $dept->DeptScoreOpenness }}</div>
                            @if (trim($dept->DeptVerified) != '' && trim($dept->DeptVerified) != '0000-00-00 00:00:00')
                                <span class="gryA"><i>{{ date("n/j", strtotime($dept->DeptVerified)) }}</i></span>
                            @endif
                        @else
                            <div class="gryA"><i class="fa fa-star"></i></div>
                        @endif
                    </div>
                    <div class="f22">{{ str_replace('Department', 'Dept', $dept->DeptName) }}</div>
                    <div class="gry9"><i>{{ $dept->DeptAddressCity }}, {{ $dept->DeptAddressState }}</i></div>
                </a>
            @empty
                <a class="list-group-item" href="javascript:void(0)">No departments found.</a>
            @endforelse
            
        </div>
        
        @if ($viewType != 'priority')
            {!! $deptRows->render() !!}
        @endif
        
        <a href="javascript:void(0)" id="newDeptBtn" class="f16">Need to add a new police department to the database?</a>
        <div id="newDeptForm" class="disNon">
            <div class="alert alert-danger" role="alert">
                <b class="f14">Before adding a department, please search for ALL each words within the department name one at a time. Also make sure you are searching in the right state.</b>
            </div>
            <form name="volunAddNewDept" action="/volunteer/newDept" method="post" onSubmit="return checkNewDept();">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="panel panel-warning">
                <div class="panel-heading taC">
                    <h2 class="m0">Add A Missing Police Department</h2>
                </div>
                <div class="panel-body">
                    <div class="row mB20 f18">
                        <div class="col-md-8">
                            <fieldset class="form-group">
                                <label for="deptNameID">Department Name</label>
                                <input id="deptNameID" name="deptName" type="text" value="" class="form-control" >
                            </fieldset>
                        </div>
                        <div class="col-md-4">
                            <fieldset class="form-group">
                                <label for="DeptAddressStateID">State</label>
                                <select id="DeptAddressStateID" name="DeptAddressState" class="form-control" autocomplete="off" >
                                {!! $GLOBALS["DB"]->states->stateDrop('', true) !!}
                                </select>
                            </fieldset>
                        </div>
                    </div>
                    <center><input type="submit" class="btn btn-lg btn-primary f22" value="Add New Department"></center>
                </div>
            </div>
            </form>
        </div>
        <script type="text/javascript">
        $(function() { 
            $( "#newDeptBtn" ).click(function() { $("#newDeptForm").slideToggle("fast"); }); 
        });
        function checkNewDept() {
            if (document.getElementById('deptNameID').value.trim() == '' || document.getElementById('DeptAddressStateID').value.trim() == '') {
                alert('Please type in a police department name and its state.');
                return false;
            }
            return true;
        }
        </script>
        
    </div>
    <div class="col-md-6 taC">
        <div class="jumbotron">
            <h2>Thank You For Your Help!</h2>
            <p class="taL">
                Please verify as much of department information as possible. 
                This requires both online research and calling each department - 
                including their internal affairs office. 
                You can choose a random department, or search for a city or county in your home state.
            </p>
            <p class="taL">
                Completing research on each department should take you about 30 minutes. Please take your time, because quality matters most!
            </p>
            <p class="taC">
                <i class="f16">Watch the full-length video tutorial here:</i>
                <iframe width="100%" height="240" src="https://www.youtube.com/embed/VoLEkx8iK0A" frameborder="0" allowfullscreen></iframe>
            </p>
        </div>
    
        @if (!isset($yourContact->PrsnAddressState) || !$yourContact->PrsnAddressState || trim($yourContact->PrsnAddressState) == '')
            <div id="volunState" class="fR disBlo">
                <form name="volunStateForm" method="post" action="/volunteer/saveDefaultState" target="hidFrame" 
                    onSubmit="document.getElementById('volunState').style.display='none'; 
                                document.getElementById('stateID').value=document.getElementById('newStateID').value;">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="panel panel-danger mT20">
                    <div class="panel-heading taC"><h3 class="panel-title">Info About You</h3></div>
                    <div class="panel-body taC">
                        <label>
                            What State Do You Live In?
                            <select id="newStateID" name="newState" class="form-control mB20">
                                {!! $GLOBALS["DB"]->states->stateDrop() !!}
                            </select>
                        </label>
                        <div class="mB20"><label>
                            What is your phone number? 
                            <input name="newPhone" type="text" class="form-control" 
                            @if (isset($yourContact) && isset($yourContact->PrsnPhoneMobile))
                                value="{{ trim($yourContact->PrsnPhoneMobile) }}"
                            @else
                                value=""
                            @endif
                            >
                        </label></div>
                        <input type="submit" class="btn btn-danger" value="Save Your Info">
                    </div>
                </div>
                </form>
            </div>
        @endif
        
    </div>
</div>

<style>
#deptListGroup { width: 50%; }
@media screen and (max-width: 992px) { #deptListGroup { width: 100%; } }
</style>

<div class="adminFootBuff"></div>

@endsection