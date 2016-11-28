<!-- resources/views/vendor/openpolice/nodes/285-gold-what-happened.blade.php -->

<h1>What Happened?</h1>

@foreach ($eventTypes as $eventType)
	@if ($eventType != 'Arrests')
		<div id="nLabel285{{ $eventType }}" class="nPrompt mT20">
		@if (sizeof($victims) > 1) 
			<nobr><span class="fPerc125 bld slBlueDark">{!! $eventTypeLabel[$eventType] !!}:</span></nobr>
		@endif
		</div>
		<div class="nPrompt">
			<b>{{ $eventTypeLabel[$eventType] }}:</b> 
			@if ($eventType == 'Stops')
				Did an officer 
				@if ($sceneType == 'home') 
					detain, frisk, or question
				@else
					pull over a vehicle, detain, or frisk
				@endif
				@if (sizeof($victims) > 1) someone? @else {!! $victim1youLower !!}? @endif
			@elseif ($eventType == 'Searches')
				Did an officer search a vehicle, home, person, or property? Or was any property seized?
			@elseif ($eventType == 'Force')
				Did an officer use any weapons or physical force? @endif
			@endif
		</div>
		<div class="pL20">
		
			@foreach ($victims as $i => $civ)
				<div class="nFld disIn mR20">
					<nobr><label for="n285fld{{ $eventType }}{{ $i }}">
						<input type="checkbox" id="n285fld{{ $eventType }}{{ $i }}" name="n285fld{{ $eventType }}[]" value="{{ $civ }}" 
							@if (in_array($civ, $eventCivLookup[$eventType])) CHECKED @endif autocomplete="off" > 
						@if (sizeof($victims) > 1)
							{{ $victimNames[$civ] }}
						@else
							Yes
						@endif
					</label></nobr>
				</div> 
			@endforeach
			
			@if ($eventType == 'Force')
			
				@foreach ($victims as $i => $civ)
					<div id="forceCiv{{ $i }}" class="nFld mL20 mT20 mB20 
						@if (in_array($civ, $eventCivLookup[$eventType])) disBlo @else disNon @endif ">
						<div class="nPrompt">
							Which types of force were used against <span class="slBlueDark"> 
							@if (sizeof($victims) > 1) {{ $victimNames[$civ] }}</span>? @else you</span>? @endif
							<div class="redDrk f12 pL10 disIn">*required</div>
						</div>
						<div class="nFld pL20">
							@foreach ($forceType as $i => $fType)
								<div class="nFld disIn mR20">
									<nobr><label for="n{{ $civ }}fld{{ $i }}">
										<input type="checkbox" id="n{{ $civ }}fld{{ $i }}" name="n{{ $civ }}fld[]" class="n{{ $civ }}fldCls" 
											@if (isset($forceCivs[$civ]) && in_array($fType->DefID, $forceCivs[$civ]["types"])) CHECKED @endif 
											autocomplete="off" value="{{ $fType->DefID }}" > {{ $fType->DefValue }}
											@if ($fType->DefValue == 'Body Weapons') (e.g. punch or kick) @endif
									</label></nobr>
								</div>
							@endforeach
							<div id="node{{ $civ }}kids" class="nKids nFld 
								@if (isset($forceCivs[$civ]) && isset($forceCivs[$civ]['other']) && trim($forceCivs[$civ]['other']) != '') 
									disIn @else disNon @endif " >
								<input name="n-{{ $civ }}Visible" id="n-{{ $civ }}VisibleID" value="0" type="hidden">
								<label for="n-{{ $civ }}FldID"><span class="gry9">Describe Other:</span></label>
								<input type="text" name="n-{{ $civ }}fld" id="n-{{ $civ }}FldID" 
									@if (isset($forceCivs[$civ]) && isset($forceCivs[$civ]["other"])) 
										value="{{ $forceCivs[$civ]['other'] }}" @else value="" @endif > 
								<span class="slRedDark f12">*required</span>
							</div>
						</div>
					</div>
				@endforeach
				
				<div class="nFld disIn mR20">
					<nobr><label for="n285fldForceAnimalID">
						<input type="checkbox" id="n285fldForceAnimalID" name="n285fld{{ $eventType }}[]" value="0" 
							@if (sizeof($forceAnimal) > 0) CHECKED @endif autocomplete="off" 
							> Against A Pet or Other Animal
					</label></nobr>
				</div>
				<div id="node285" class="nodeWrap pT10 pL20 mBn10 @if (sizeof($forceAnimal) > 0) disBlo @else disNon @endif "><nobr>
					<div id="nLabel285" class="nPrompt disIn">
						<label for="ForceAnimalDescID">Describe animal(s): <span class="red f12 pL10 pR10">*required</span></label>
					</div>
					<div class="nFld disIn">
						<input id="ForceAnimalDescID" name="ForceAnimalDesc" type="text" class="form-control disIn w40" 
							@if (sizeof($forceAnimal) > 0) value="{{ trim($forceAnimal->ForAnimalDesc) }}" @else value="" @endif >
					</div>
					<div class="pT10">
						<div class="nPrompt">
							Which types of force were used against an animal?
							<div class="redDrk f12 pL10 disIn">*required</div>
						</div>
						<div class="nFld pL20">
							@foreach ($forceType as $i => $fType)
								<div class="nFld disIn mR20">
									<nobr><label for="n0fld{{ $i }}">
										<input type="checkbox" id="n0fld{{ $i }}" name="n0fld[]" class="n0fldCls" 
											@if (isset($forceCivs[0]) && in_array($fType->DefID, $forceCivs[0]["types"])) CHECKED @endif 
											autocomplete="off" value="{{ $fType->DefID }}" > {{ $fType->DefValue }}
											@if ($fType->DefValue == 'Body Weapons') (e.g. punch or kick) @endif
									</label></nobr>
								</div> 
							@endforeach
							<div id="node0kids" class="nKids nFld 
								@if (isset($forceCivs[0]) && isset($forceCivs[0]['other']) && trim($forceCivs[0]['other']) != '') 
									disIn @else disNon @endif ">
								<input name="n-0Visible" id="n-0VisibleID" value="0" type="hidden">
								<label for="n-0FldID"><span class="gry9">Describe Other:</span></label>
								<input type="text" name="n-0fld" id="n-0FldID" 
									@if (isset($forceCivs[0]) && isset($forceCivs[0]["other"])) 
										value="{{ $forceCivs[0]['other'] }}" @else value="" @endif > 
								<span class="slRedDark f12">*required</span>
							</div>
						</div>
					</div>
				</nobr></div>
				
			@endif
		</div>
	@endif
@endforeach

<div>
	<div id="nLabel285civQ" class="nPrompt pT20 mT20">
		Did an officer take 
		@if (sizeof($victims) > 1) anyone @else {!! $victim1youLower !!} @endif
		into custody or jail?
	</div>
	@foreach ($victims as $i => $civ)
		<div class="pL20 pB20">
			@if (sizeof($victims) > 1) <div class="disIn mR20 f22 bld"><nobr>{!! $victimNames[$civ] !!}:</nobr></div> @endif
			<div class="nFld disIn mR20"><nobr><label for="n285civ{{ $i }}arrest">
				<input id="n285civ{{ $i }}arrest" name="n285civ{{ $i }}" value="Arrests" type="radio" autocomplete="off" 
					@if (in_array($civ, $eventCivLookup["Arrests"])) CHECKED @endif 
					> Yes, Arrested
			</label></nobr></div>
			<div class="nFld disIn mR20"><nobr><label for="n285civ{{ $i }}citation">
				<input id="n285civ{{ $i }}citation" name="n285civ{{ $i }}" value="Citations" type="radio" autocomplete="off" 
					@if (in_array($civ, $eventCivLookup["Citations"])) CHECKED @endif 
					> Citation
			</label></nobr></div>
			<div class="nFld disIn mR20"><nobr><label for="n285civ{{ $i }}warning">
				<input id="n285civ{{ $i }}warning" name="n285civ{{ $i }}" value="Warnings" type="radio" autocomplete="off" 
					@if (in_array($civ, $eventCivLookup["Warnings"])) CHECKED @endif
					> Written Warning
			</label></nobr></div>
			<div class="nFld disIn mR20"><nobr><label for="n285civ{{ $i }}none">
				<input id="n285civ{{ $i }}none" name="n285civ{{ $i }}" value="None" type="radio" autocomplete="off" 
					> None
			</label></nobr></div>
		</div>
	@endforeach

</div>