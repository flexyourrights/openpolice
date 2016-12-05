<!-- Stored in resources/views/openpolice/inc-nav-public.blade.php -->

<ul class="nav navbar-nav">

@if (!isset($user) || !method_exists($user, 'hasRole') || (!$user->hasRole('administrator|staff|databaser|brancher|volunteer')))
    @if (isset($usrComplaintSlug) && trim($usrComplaintSlug) != '')
        <li class="active"><a href="/report{{ $usrComplaintSlug }}" class="f22" style="background: none;" title="Link to review and/or provide updates about your complaint">Update Your Complaint</a></li>
    @else
        <li class="active"><a href="/" class="f22" style="background: none;" title="Link to File A Complaint About The Police">Share Your Story</a></li>
    @endif
@endif

</ul>
<ul class="nav navbar-nav navbar-right">

@if (isset($user) && isset($user->id) && $user->id > 0)

    @if ($user->hasRole('administrator|staff|databaser|brancher|volunteer'))
        <li>{!! $user->printCasualUsername(true, 'Hi, ', '/profile/') !!}</li>
        <li><a href="/dashboard">Dashboard</a></li>
    @elseif (isset($currInComplaint) && $currInComplaint)
        <li><a href="javascript:void(0)" title="Edit Your Story">Edit Story</a></li>
    @else
    @endif
    <li><a href="/logout">Logout</a></li>
    <!-- <li><a href="https://OpenPoliceComplaints.org/articles/" target="_blank"><i class="fa fa-question-circle"></i></a></li> -->
    
@else

    <li><a href="/login" title="Please login to continue your unfinished complaint, or to post an update on your case.">Login</a></li>
    <li><a href="/register" title="Please sign up to help make Open Police Complaints better!">Volunteer</a></li>
    <!--- <li><a href="https://OpenPoliceComplaints.org/articles/" target="_blank"><i class="fa fa-question-circle"></i></a></li> --->
    
@endif

</ul>