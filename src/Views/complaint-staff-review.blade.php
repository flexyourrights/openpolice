<!-- Stored in resources/views/openpolice/complaint-staff-review.blade.php -->

    @if ($settings["Complaint Evaluations"] == 'N')
        
        <div class="row">
            <div class="col-md-4">
                <h2 class="m0">Complaint #{{ $cID }} History</h2>
            </div>
            <div class="col-md-8 taR">
                Buttons
            </div>
        </div>
            
        @if ($reviews && sizeof($reviews) > 0)
            @foreach ($reviews as $i => $r)
                <div class="pT10 pB20 brdTop f18">
                    <i class="gry9">{{ date("M n, Y, g:i a", strtotime($r->ComRevDate)) }}, by {!! $allStaffName[$r->ComRevUser] !!}</i><br />
                    <div class="pL5 f18">
                        <div class="f22"><b>{{ $r->ComRevStatus }}</b></div>
                        {{ $GLOBALS['SL']->getDefValue('OPC Staff/Internal Complaint Type', $r->ComRevComplaintType) }}
                        @if ($r->ComRevType == 'Full') 
                            <nobr><i class="fa fa-search-plus mL20 slBlueDark f16" aria-hidden="true"></i> {{ ($r->ComRevNotAnon + $r->ComRevOneIncident + $r->ComRevCivilianContact + $r->ComRevOneOfficer + $r->ComRevOneAllegation + $r->ComRevEvidenceUpload) }}</nobr>
                            @if (($r->ComRevReadability + $r->ComRevConsistency + $r->ComRevRealistic + $r->ComRevOutrage) >= 0)
                                <nobr><i class="fa fa-thumbs-o-up mL20 slBlueDark f16" aria-hidden="true"></i> 
                            @else
                                <nobr><i class="fa fa-thumbs-o-down mL20 slBlueDark f16" aria-hidden="true"></i> 
                            @endif
                            {{ ($r->ComRevReadability + $r->ComRevConsistency + $r->ComRevRealistic + $r->ComRevOutrage) }}</nobr>
                        @endif
                        @if ($r->ComRevMakeFeatured)
                            <i class="fa fa-certificate mL20 slBlueDark f16" aria-hidden="true"></i> Featured
                        @endif
                    </div>
                    <div class="pL5 pT5 f16">
                        {{ $r->ComRevNote }}
                    </div>
                </div>
            @endforeach
        @else
            <div class="p20"><i>This complaint has not been reviewed yet.</i></div>
        @endif
                    
        
        @if ($viewType != 'emails' && $viewType != 'emailsType')
        
            <div class="relDiv"><a name="new" class="absDiv" style="top: -80px;"></a></div>
            <div class="p5 pL10 pR20 mT20 round5 brd">
                <form action="/dashboard/complaint/{{ $cID }}/review/save" method="post" >
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="cID" value="{{ $cID }}">
                <input type="hidden" name="revType" value="Update">
                
                <div class="row">
                    <div class="col-md-6 f26 bld">
                        Complaint Status Update
                    </div>
                    <div class="col-md-6 taR">
                        <b class="f16 gry9">Change Complaint Type: <a href="#" id="legitTypeBtn">{{ str_replace('Legitimate', 'Complaint About Police', 
                            $GLOBALS['SL']->getDefValue('OPC Staff/Internal Complaint Type', $complaintRec->ComType)) }}</a></b>
                        <?php /* <label class="mL20 pL20">
                            <input type="checkbox" name="ComRevMakeFeatured" value="1" class="mR20" 
                                @if (isset($latestReview->ComRevMakeFeatured) && intVal($latestReview->ComRevMakeFeatured) == 1) CHECKED @endif
                                > <b class="f16">Featured</b>
                        </label> */ ?>
                        <div id="legitTypeDrop" class="disNon">
                            <select name="revComplaintType" class="form-control f18 mB10 mL5" autocomplete=off >
                                <option value="195" @if ($complaintRec->ComType == 195) SELECTED @endif >Unreviewed</option>
                                <option value="196" @if ($complaintRec->ComType == 196) SELECTED @endif >Complaint About Police</option>
                                <option value="197" @if ($complaintRec->ComType == 197) SELECTED @endif >Not About Police</option>
                                <option value="198" @if ($complaintRec->ComType == 198) SELECTED @endif >Abuse</option>
                                <option value="199" @if ($complaintRec->ComType == 199) SELECTED @endif >Spam</option>
                                <option value="200" @if ($complaintRec->ComType == 200) SELECTED @endif >Test Submission</option>
                                <option value="201" @if ($complaintRec->ComType == 201) SELECTED @endif >Not Sure</option>
                            </select>
                        </div>
                        <script type="text/javascript">
                        $(function() {
                            $("#legitTypeBtn").click(function(){ $("#legitTypeDrop").slideToggle('fast'); });
                        });
                        </script>
                    </div>
                </div>
                {!! view('openpolice/admin/complaints/complaint-review-status', [
                    "firstReview"     => false, 
                    "latestReview"     => $latestReview, 
                    "fullList"         => true ,
                    "settings"        => $settings
                    ])->render() !!}
                
                
                <div class="mT5">
                    <div class="row">
                        <div class="col-md-8">
                            <b class="f16">Notes for other evaluators</b>
                        </div>
                        <div class="col-md-4 taR">
                        </div>
                    </div>
                    <div class="pB5">
                        <textarea name="revNote" class="form-control f18 mL5" style="height: 70px;" ></textarea>
                    </div>
                </div>
                
                <div class="p5 taC">
                    <input type="submit" class="btn btn-lg btn-primary f24" value="Save Update">
                </div>
            
                </form>
            </div>
            
        @endif
    
    
    
    
        
    @else
    
        @if ($reviews && sizeof($reviews) < 2)
            <a name="new"></a>
        @endif
        
        <div class="row">
            <div class="col-md-4">
                
                <h2 class="m0">Complaint #{{ $cID }} History</h2>
                <div class="pL5">
                
                    <div class="pB5 slBlueDark">
                        <i class="fa fa-search-plus" aria-hidden="true"></i> <span class="f16 mL5">Investigability</span> <i class="f12">(0 to 6)</i>, 
                        <i class="fa fa-thumbs-o-up mL20" aria-hidden="true"></i> <span class="f16 mL5">Quality Rating</span> <i class="f12">(-4 to 4)</i><br />
                    </div>
                    
                    @if ($reviews && sizeof($reviews) > 0)
                        @foreach ($reviews as $i => $r)
                            @if (sizeof($reviews) > 1 && $i == sizeof($reviews)-1)
                                <div class="relDiv"><a name="new" class="absDiv" style="top: -50px;"></a></div>
                            @endif
                            <div class="pT10 pB20 brdTop f18">
                                <i class="gry9">{{ date("M n, Y, g:i a", strtotime($r->ComRevDate)) }}, by {!! $allStaffName[$r->ComRevUser] !!}</i><br />
                                <div class="pL5 f18">
                                    <div class="f22"><b>{{ $r->ComRevStatus }}</b></div>
                                    {{ $GLOBALS['SL']->getDefValue('OPC Staff/Internal Complaint Type', $r->ComRevComplaintType) }}
                                    @if ($r->ComRevType == 'Full') 
                                        <nobr><i class="fa fa-search-plus mL20 slBlueDark f16" aria-hidden="true"></i> {{ ($r->ComRevNotAnon + $r->ComRevOneIncident + $r->ComRevCivilianContact + $r->ComRevOneOfficer + $r->ComRevOneAllegation + $r->ComRevEvidenceUpload) }}</nobr>
                                        @if (($r->ComRevReadability + $r->ComRevConsistency + $r->ComRevRealistic + $r->ComRevOutrage) >= 0)
                                            <nobr><i class="fa fa-thumbs-o-up mL20 slBlueDark f16" aria-hidden="true"></i> 
                                        @else
                                            <nobr><i class="fa fa-thumbs-o-down mL20 slBlueDark f16" aria-hidden="true"></i> 
                                        @endif
                                        {{ ($r->ComRevReadability + $r->ComRevConsistency + $r->ComRevRealistic + $r->ComRevOutrage) }}</nobr>
                                    @endif
                                    @if ($r->ComRevMakeFeatured)
                                        <i class="fa fa-certificate mL20 slBlueDark f16" aria-hidden="true"></i> Featured
                                    @endif
                                </div>
                                <div class="pL5 pT5 f16">
                                    {{ $r->ComRevNote }}
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="p20"><i>This complaint has not been reviewed yet.</i></div>
                    @endif
                    
                    
                    @if ($viewType != 'emails' && $viewType != 'emailsType')
                    
                        <div class="p5 pL10 pR20 mT20 round5 brd">
                            <form action="/dashboard/complaint/{{ $cID }}/review/save" method="post" >
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="cID" value="{{ $cID }}">
                            <input type="hidden" name="revType" value="Update">
                            
                            
                            <b class="f26 mT5">Complaint Status Update</b>
                            <div class="pB10">
                            {!! view('openpolice/admin/complaints/complaint-review-status', [
                                    "firstReview"     => false, 
                                    "latestReview"     => $latestReview, 
                                    "fullList"         => true ,
                                    "settings"        => $settings
                                    ])->render() !!}
                            </div>
                            
                            <b class="f16">Change Complaint Type: <a href="#" id="legitTypeBtn">{{ $GLOBALS['SL']->getDefValue('OPC Staff/Internal Complaint Type', $complaintRec->ComType) }}</a></b>
                            <div id="legitTypeDrop" class="disNon">
                                <select name="revComplaintType" class="form-control f18 mB10 mL5" autocomplete=off >
                                    <option value="195" @if ($complaintRec->ComType == 195) SELECTED @endif >Unreviewed</option>
                                    <option value="196" @if ($complaintRec->ComType == 196) SELECTED @endif >Legitimate</option>
                                    <option value="197" @if ($complaintRec->ComType == 197) SELECTED @endif >Not About Police</option>
                                    <option value="198" @if ($complaintRec->ComType == 198) SELECTED @endif >Abuse</option>
                                    <option value="199" @if ($complaintRec->ComType == 199) SELECTED @endif >Spam</option>
                                    <option value="200" @if ($complaintRec->ComType == 200) SELECTED @endif >Test Submission</option>
                                    <option value="201" @if ($complaintRec->ComType == 201) SELECTED @endif >Not Sure</option>
                                </select>
                            </div>
                            <script type="text/javascript">
                            $(function() {
                                $("#legitTypeBtn").click(function(){ $("#legitTypeDrop").slideToggle('fast'); });
                            });
                            </script>
                            
                            <div class="mT5">
                                <div class="row">
                                    <div class="col-md-8">
                                        <b class="f16">Notes for other evaluators</b>
                                    </div>
                                    <div class="col-md-4 taR">
                                        <label>
                                            <input type="checkbox" name="ComRevMakeFeatured" value="1" class="mR20" 
                                                @if (isset($latestReview->ComRevMakeFeatured) && intVal($latestReview->ComRevMakeFeatured) == 1) CHECKED @endif
                                                > <b class="f16">Featured</b>
                                        </label>
                                    </div>
                                </div>
                                <div class="pB5">
                                    <textarea name="revNote" class="form-control f18 mL5" style="height: 70px;" ></textarea>
                                </div>
                            </div>
                            
                            <div class="p5 taC">
                                <input type="submit" class="btn btn-lg btn-primary f24" value="Save Update">
                            </div>
                        
                            </form>
                        </div>
                        
                    @endif
                    
                    
                </div>
                
                
            </div>
            <div class="col-md-8">
            
            
                @if ($reviews && sizeof($reviews) > 0)
            
                    <table class="table @if ($viewType == 'emails' || $viewType == 'emailsType') f16 @else f22 @endif ">
                    
                        <tr>
                            <th class="taR" style="border-right: 1px #AAA solid;">Date</th>
                            @foreach ($reviews as $i => $r)
                                @if ($r->ComRevType == 'Full') 
                                    <td class="taC @if ($i%2 == 0) row2 @endif "
                                        >{!! date("M n, g:i a", strtotime($r->ComRevDate)) !!}</td>
                                @endif
                            @endforeach
                        </tr>
                        <tr  style="border-bottom: 4px #000 solid;">
                            <th class="taR" style="border-right: 1px #AAA solid;">Evaluator</th>
                            @foreach ($reviews as $i => $r)
                                @if ($r->ComRevType == 'Full') 
                                    <td class="taC @if ($i%2 == 0) row2 @endif ">{!! $allStaffName[$r->ComRevUser] !!}</td>
                                @endif
                            @endforeach
                        </tr>
                        <tr class="brdBot9">
                            <th class="taR" style="border-right: 1px #AAA solid;">Legitimate</th>
                            @foreach ($reviews as $i => $r)
                                @if ($r->ComRevType == 'Full') 
                                    <td class="taC @if ($i%2 == 0) row2 @endif ">{{ $GLOBALS['SL']->getDefValue('OPC Staff/Internal Complaint Type', $r->ComRevComplaintType) }}</td>
                                @endif
                            @endforeach
                        </tr>
                        <tr>
                            <th class="taR" style="border-right: 1px #AAA solid;">Not Anonymous</th>
                            @foreach ($reviews as $i => $r)
                                @if ($r->ComRevType == 'Full') 
                                    <td class="taC @if ($i%2 == 0) row2 @endif ">{{ (($r->ComRevNotAnon == 1) ? 'Y' : 'N') }}</td>
                                @endif
                            @endforeach
                        </tr>
                        <tr class="brdBot9">
                            <th class="taR" style="border-right: 1px #AAA solid;">One Incident</th>
                            @foreach ($reviews as $i => $r)
                                @if ($r->ComRevType == 'Full') 
                                    <td class="taC @if ($i%2 == 0) row2 @endif ">{{ (($r->ComRevOneIncident == 1) ? 'Y' : 'N') }}</td>
                                @endif
                            @endforeach
                        </tr>
                        <tr>
                            <th class="taR" style="border-right: 1px #AAA solid;">Civilian Contact</th>
                            @foreach ($reviews as $i => $r)
                                @if ($r->ComRevType == 'Full') 
                                    <td class="taC @if ($i%2 == 0) row2 @endif ">{{ (($r->ComRevCivilianContact == 1) ? 'Y' : 'N') }}</td>
                                @endif
                            @endforeach
                        </tr>
                        <tr class="brdBot9">
                            <th class="taR" style="border-right: 1px #AAA solid;">Police Officer</th>
                            @foreach ($reviews as $i => $r)
                                @if ($r->ComRevType == 'Full') 
                                    <td class="taC @if ($i%2 == 0) row2 @endif ">{{ (($r->ComRevOneOfficer == 1) ? 'Y' : 'N') }}</td>
                                @endif
                            @endforeach
                        </tr>
                        <tr>
                            <th class="taR" style="border-right: 1px #AAA solid;">Allegation</th>
                            @foreach ($reviews as $i => $r)
                                @if ($r->ComRevType == 'Full') 
                                    <td class="taC @if ($i%2 == 0) row2 @endif ">{{ (($r->ComRevOneAllegation == 1) ? 'Y' : 'N') }}</td>
                                @endif
                            @endforeach
                        </tr>
                        <tr style="border-bottom: 3px #63c6ff solid;">
                            <th class="taR" style="border-right: 1px #AAA solid;">Evidence Upload</th>
                            @foreach ($reviews as $i => $r)
                                @if ($r->ComRevType == 'Full') 
                                    <td class="taC @if ($i%2 == 0) row2 @endif ">{{ (($r->ComRevEvidenceUpload == 1) ? 'Y' : 'N') }}</td>
                                @endif
                            @endforeach
                        </tr>
                        <tr>
                            <th class="taR" style="border-right: 1px #AAA solid;">Readability</th>
                            @foreach ($reviews as $i => $r)
                                @if ($r->ComRevType == 'Full') 
                                    <td class="taC @if ($i%2 == 0) row2 @endif " alt="
                                        @if (intVal($r->ComRevReadability) == 1)
                                            Very well written
                                        @elseif (intVal($r->ComRevReadability) == -1)
                                            Difficult to read
                                        @else
                                            Fairly easy To read
                                        @endif
                                        ">{{ $r->ComRevReadability }}
                                    </td>
                                @endif
                            @endforeach
                        </tr>
                        <tr class="brdBot9">
                            <th class="taR" style="border-right: 1px #AAA solid;">Consistency</th>
                            @foreach ($reviews as $i => $r)
                                @if ($r->ComRevType == 'Full') 
                                    <td class="taC @if ($i%2 == 0) row2 @endif " alt="
                                        @if (intVal($r->ComRevConsistency) == 1)
                                            Consistently documented
                                        @elseif (intVal($r->ComRevConsistency) == -1)
                                            Includes obvious contradictions
                                        @else
                                            Seems reasonable
                                        @endif
                                        ">{{ $r->ComRevConsistency }}
                                    </td>
                                @endif
                            @endforeach
                        </tr>
                        <tr>
                            <th class="taR" style="border-right: 1px #AAA solid;">Reality-Based</th>
                            @foreach ($reviews as $i => $r)
                                @if ($r->ComRevType == 'Full') 
                                    <td class="taC @if ($i%2 == 0) row2 @endif " alt="
                                        @if (intVal($r->ComRevRealistic) == 1)
                                            Very plausible
                                        @elseif (intVal($r->ComRevRealistic) == -1)
                                            Likely to be impossible
                                        @else
                                            Might be confused or under the influence
                                        @endif
                                        ">{{ $r->ComRevRealistic }}
                                    </td>
                                @endif
                            @endforeach
                        </tr>
                        <tr style="border-bottom: 3px #63c6ff solid;">
                            <th class="taR" style="border-right: 1px #AAA solid;">Your Outrage</th>
                            @foreach ($reviews as $i => $r)
                                @if ($r->ComRevType == 'Full') 
                                    <td class="taC @if ($i%2 == 0) row2 @endif " alt="
                                        @if (intVal($r->ComRevOutrage) == 1)
                                            Horrified. Call CNN!
                                        @elseif (intVal($r->ComRevOutrage) == -1)
                                            Sorry to hear, life's not fair
                                        @else
                                            WOW, that seriously sucks, &lt;3
                                        @endif
                                        ">{{ $r->ComRevOutrage }}
                                    </td>
                                @endif
                            @endforeach
                        </tr>
                        <tr>
                            <th class="taR" style="border-right: 1px #AAA solid;">Feature Complaint</th>
                            @foreach ($reviews as $i => $r)
                                @if ($r->ComRevType == 'Full') 
                                    <td class="taC @if ($i%2 == 0) row2 @endif ">{{ (($r->ComRevMakeFeatured == 1) ? 'Y' : 'N') }}</td>
                                @endif
                            @endforeach
                        </tr>
                        <tr class="brdBot9">
                            <th class="taR" style="border-right: 1px #AAA solid;">Explicit Language</th>
                            @foreach ($reviews as $i => $r)
                                @if ($r->ComRevType == 'Full') 
                                    <td class="taC @if ($i%2 == 0) row2 @endif ">{{ (($r->ComRevExplicitLang == 1) ? 'Y' : 'N') }}</td>
                                @endif
                            @endforeach
                        </tr>
                        <tr>
                            <th class="taR" style="border-right: 1px #AAA solid;">Graphic Content</th>
                            @foreach ($reviews as $i => $r)
                                @if ($r->ComRevType == 'Full') 
                                    <td class="taC @if ($i%2 == 0) row2 @endif ">{{ (($r->ComRevGraphicContent == 1) ? 'Y' : 'N') }}</td>
                                @endif
                            @endforeach
                        </tr>
                        <tr>
                            <th class="taR" style="border-right: 1px #AAA solid;">In English</th>
                            @foreach ($reviews as $i => $r)
                                @if ($r->ComRevType == 'Full') 
                                    <td class="taC @if ($i%2 == 0) row2 @endif ">{{ (($r->ComRevEnglishSkill == 1) ? 'Y' : 'N') }}</td>
                                @endif
                            @endforeach
                        </tr>
                
                    </table>
            
                @endif
            
            </div>
        </div>

    @endif
