<table class="table table-striped">
	<tr><th colspan=2 class="taC" >
		<img id="saveStar1" src="/openpolice/star1.png" border=0 height=15 class="mTn5" > 
		Volunteer All-Stars <img id="saveStar1" src="/openpolice/star1.png" border=0 height=15 class="mTn5" >
	</th></tr>
	@forelse ($leaderboard->UserInfoStars as $i => $u)
		@if ($i < 10)
			<tr>
				<td class="taC"><a href="/volunteer/user/{{ $u->UserInfoUserID }}">{{ $u->name }}</a></td>
				<td class="pR20">{{ $u->UserInfoStars }}</td>
			</tr>
		@endif
	@empty
		<tr><td colspan=2 >No volunteers found.</td></tr>
	@endforelse
</table>