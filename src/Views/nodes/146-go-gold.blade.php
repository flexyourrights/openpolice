<!-- resources/views/vendor/openpolice/nodes/146-go-gold.blade.php -->

<input type="hidden" name="n146Visible" id="n146VisibleID" value="1">
<div id="node146" class="nodeWrap pT20 mT10">
	<div class="row">
		<div id="nLabel146" class="col-md-6 nPrompt">
			<h1>Great Job! Want To Go Gold?</h1>
			You have completed a solid complaint. This includes basic info that most departments need to investigate your complaint. 
			<br /><br />
			But here are the benefits of taking a few more minutes to create a <span class="goldLevel">GOLD STAR complaint</span>...
			<ul class="mT20">
			<li class="mB20">Discover important things about your incident and paint a more complete picture</li>
			<li class="mB20">Create a first-class complaint, that is even stronger for investigators</li>
			<li class="mB20">Receive custom information on the many things you can learn from this police encounter</li>
			<li class="mB20">Helps us to better track problematic police behavior in your community and nationwide</li>
			</ul>
		</div>
		<div class="col-md-1"></div>
		<div class="col-md-3 nFld">
			<label for="n146fld0" class="w100">
				<nobr><input id="n146fld0" value="Gold" type="radio" name="n146fld" autocomplete="off" class="mR5" 
					@if ($firstTimeGold || in_array($ComAwardMedallion, ['', 'Gold'])) CHECKED @endif 
					> <b class="f32">Yes, I want to Go Gold!</b></nobr>
			</label>
			<label for="n146fld1" class="w100 pT20">
				<nobr><input id="n146fld1" value="Silver" type="radio" name="n146fld" autocomplete="off" class="mR5"
					@if (!$firstTimeGold && $ComAwardMedallion == 'Silver') CHECKED @endif 
					> No thanks.</nobr> <nobr>I'm ready to submit my complaint as is.</nobr>
			</label>
			<center><img src="https://app.openpolicecomplaints.org/images/medal-gold.png" hspace=5 width=50% class="mT20" ></center>
		</div>
	</div>
</div> <!-- end #node146 -->
