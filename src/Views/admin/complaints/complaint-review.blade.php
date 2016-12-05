<!-- resources/views/vendor/openpolice/admin/complaints/complaint-review.blade.php -->

@extends('vendor.survloop.admin.admin')

@section('content')
    
@if ($viewType == 'review' && $settings["Complaint Evaluations"] == 'Y')

    <div class="row">
        <div class="col-md-4 pL20">
    
            <div class="row2 p5 round10 pB20 mLn10">
            
                <center><b class="f26 slBlueDark">Your Evaluation of #{{ $cID }}</b>
                @if (!$firstReview)
                    <div class="pB10 red"><i class="f18">you reviewed this complaint on {{ date("M n, Y", strtotime($yourReview->ComRevDate)) }}</i></div>
                @endif
                </center>
                
                <div class="pL10 pR10">
                
                    <form action="/dashboard/complaint/{{ $cID }}/review/save" method="post" >
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="cID" value="{{ $cID }}">
                    <input type="hidden" name="revType" value="Full">
                    
                    <div class="checkbox mTn5 mL5 brdTop">
                        <div class="fL"><label>
                            <input type="checkbox" name="complaintLegit" value="196" 
                                @if ($firstReview || $GLOBALS["DB"]->getDefValue('OPC Staff/Internal Complaint Type', $yourReview->ComRevComplaintType) == 'Legitimate')
                                    CHECKED 
                                @endif
                                onClick="if (this.checked) { document.getElementById('notLegit').style.display='none'; } else { document.getElementById('notLegit').style.display='block';  }" 
                                > Legitimate Complaint
                        </label></div>
                        <div class="gry9 fR"><label>
                            <input type="checkbox" name="revEnglishSkill" value="1" 
                                @if ($firstReview || intVal($yourReview->ComRevEnglishSkill) == 1)
                                    CHECKED 
                                @endif
                                > In English 
                        </label></div>
                        <div class="fC"></div>
                    </div>
            
                    <div id="notLegit" class="pL10 
                        @if ($firstReview)
                            disNon 
                        @elseif ($GLOBALS['DB']->getDefValue('OPC Staff/Internal Complaint Type', $yourReview->ComRevComplaintType) == 'Legitimate')
                            disNon
                        @else
                            disBlo
                        @endif
                        ">
                        <div class="radio">
                            <nobr><label>
                                <input type="radio" name="revComplaintType" value="197" 
                                    @if (!$firstReview && $GLOBALS['DB']->getDefValue('OPC Staff/Internal Complaint Type', $yourReview->ComRevComplaintType) == 'Not About Police')
                                        CHECKED
                                    @endif
                                    > Not about police
                            </label></nobr>
                            <nobr><label class="mL20">
                                <input type="radio" name="revComplaintType" value="198" 
                                    @if (!$firstReview && $GLOBALS['DB']->getDefValue('OPC Staff/Internal Complaint Type', $yourReview->ComRevComplaintType) == 'Abuse')
                                        CHECKED
                                    @endif
                                    > Abuse
                            </label></nobr>
                            <nobr><label class="mL20">
                                <input type="radio" name="revComplaintType" value="199" 
                                    @if (!$firstReview && $GLOBALS['DB']->getDefValue('OPC Staff/Internal Complaint Type', $yourReview->ComRevComplaintType) == 'Spam')
                                        CHECKED
                                    @endif
                                    > Spam
                            </label></nobr>
                            <nobr><label class="mL20">
                                <input type="radio" name="revComplaintType" value="200" 
                                    @if (!$firstReview && $GLOBALS['DB']->getDefValue('OPC Staff/Internal Complaint Type', $yourReview->ComRevComplaintType) == 'Test')
                                        CHECKED
                                    @endif
                                    > Test submission
                            </label></nobr>
                            <nobr><label class="mL20">
                                <input type="radio" name="revComplaintType" value="200" 
                                    @if (!$firstReview && $GLOBALS['DB']->getDefValue('OPC Staff/Internal Complaint Type', $yourReview->ComRevComplaintType) == 'Not Sure')
                                        CHECKED
                                    @endif
                                    > Not Sure
                            </label></nobr>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                        
                            <b class="f20">Investigability</b> (0 to 6)
                            <div class="pL10 f16">
                                <div class="checkbox"><label>
                                    <input type="checkbox" name="revNotAnon" value="1" 
                                        @if (!$firstReview && intVal($yourReview->ComRevNotAnon) == 1)
                                            CHECKED
                                        @endif
                                        > Not anonymous
                                </label></div>
                                <div class="checkbox"><label>
                                    <input type="checkbox" name="revOneIncident" value="1" 
                                        @if (!$firstReview && intVal($yourReview->ComRevOneIncident) == 1)
                                            CHECKED
                                        @endif
                                        > 1 Specific incident
                                </label></div>
                                <div class="checkbox"><label>
                                    <input type="checkbox" name="revCivilianContact" value="1" 
                                        @if (!$firstReview && intVal($yourReview->ComRevCivilianContact) == 1)
                                            CHECKED
                                        @endif
                                        > &ge; 1 Civilian contact
                                </label></div>
                                <div class="checkbox"><label>
                                    <input type="checkbox" name="revOneOfficer" value="1" 
                                        @if (!$firstReview && intVal($yourReview->ComRevOneOfficer) == 1)
                                            CHECKED
                                        @endif
                                        > &ge; 1 Police officer
                                </label></div>
                                <div class="checkbox"><label>
                                    <input type="checkbox" name="revOneAllegation" value="1" 
                                        @if (!$firstReview && intVal($yourReview->ComRevOneAllegation) == 1)
                                            CHECKED
                                        @endif
                                        > &ge; 1 Allegation
                                </label></div>
                                <div class="checkbox"><label>
                                    <input type="checkbox" name="revEvidenceUpload" value="1" 
                                        @if (!$firstReview && intVal($yourReview->ComRevEvidenceUpload) == 1)
                                            CHECKED
                                        @endif
                                        > Evidence obtainable 
                                </label></div>
                            </div>
                            
                            <!---
                            <div class="pL10">
                                <select name="revEnglishSkill" class="form-control" style="height: 31px; margin-bottom: 2px;">
                                    <option value=""  >English Abilities?</option>
                                    <option value="-1"  >None / Not in English</option>
                                    <option value="0"  >Maybe new to English</option>
                                    <option value="1" SELECTED >Fluent in English</option>
                                </select>
                            </div>
                            --->
                        
                        </div>
                        <div class="col-md-6">
                        
                            <b class="f20">Internal Rating</b> (-4 to 4)
                            <div class="pL10">
                            
                                <select name="revReadability" id="revReadabilityID" class="form-control f18 mB10" autocomplete=off 
                                    onChange="updateRatingDrop('ComRevReadability');" >
                                    <option value="" @if ($firstReview) SELECTED @endif >Readability?</option>
                                    <option value="1" class="bgGrn" 
                                        @if (!$firstReview && isset($yourReview->ComRevReadability) && intVal($yourReview->ComRevReadability) == 1)
                                            SELECTED
                                        @endif
                                        >Very well written (1)</option>
                                    <option value="0" class="bgYel" 
                                        @if (!$firstReview && isset($yourReview->ComRevReadability) && intVal($yourReview->ComRevReadability) == 0)
                                            SELECTED
                                        @endif
                                        >Fairly easy To read (0)</option>
                                    <option value="-1" class="bgRed" 
                                        @if (!$firstReview && isset($yourReview->ComRevReadability) && intVal($yourReview->ComRevReadability) == -1)
                                            SELECTED
                                        @endif
                                        >Difficult to read (-1)</option>
                                </select>
                                
                                <select name="revConsistency" id="revConsistencyID" class="form-control f18 mB10" autocomplete=off onChange="updateRatingDrop('ComRevConsistency');" >
                                    <option value="" @if ($firstReview) SELECTED @endif >Consistency?</option>
                                    <option value="1" class="bgGrn" 
                                        @if (!$firstReview && isset($yourReview->ComRevConsistency) && intVal($yourReview->ComRevConsistency) == 1)
                                            SELECTED
                                        @endif
                                        >Consistently documented (1)</option>
                                    <option value="0" class="bgYel" 
                                        @if (!$firstReview && isset($yourReview->ComRevConsistency) && intVal($yourReview->ComRevConsistency) == 0)
                                            SELECTED
                                        @endif
                                        >Seems reasonable (0)</option>
                                    <option value="-1" class="bgRed" 
                                        @if (!$firstReview && isset($yourReview->ComRevConsistency) && intVal($yourReview->ComRevConsistency) == -1)
                                            SELECTED
                                        @endif
                                        >Includes obvious contradictions (-1)</option>
                                </select>
                                
                                <select name="revRealistic" id="revRealisticID" class="form-control f18 mB10" autocomplete=off 
                                    onChange="updateRatingDrop('ComRevReadability');" >
                                    <option value="" @if ($firstReview) SELECTED @endif >Reality-Based?</option>
                                    <option value="1" class="bgGrn" 
                                        @if (!$firstReview && isset($yourReview->ComRevRealistic) && intVal($yourReview->ComRevRealistic) == 1)
                                            SELECTED
                                        @endif
                                        >Very plausible (1)</option>
                                    <option value="0" class="bgYel" 
                                        @if (!$firstReview && isset($yourReview->ComRevRealistic) && intVal($yourReview->ComRevRealistic) == 0)
                                            SELECTED
                                        @endif
                                        >Might be confused or under the influence (0)</option>
                                    <option value="-1" class="bgRed" 
                                        @if (!$firstReview && isset($yourReview->ComRevRealistic) && intVal($yourReview->ComRevRealistic) == -1)
                                            SELECTED
                                        @endif
                                        >Likely to be impossible (-1)</option>
                                </select>
                                
                                <select name="revOutrage" id="revOutrageID" class="form-control f18" autocomplete=off onChange="updateRatingDrop('ComRevOutrage');" >
                                    <option value="" @if ($firstReview) SELECTED @endif >Your Outrage?</option>
                                    <option value="1" class="bgGrn" 
                                        @if (!$firstReview && isset($yourReview->ComRevOutrage) && intVal($yourReview->ComRevOutrage) == 1)
                                            SELECTED
                                        @endif
                                        >Horrified. Call CNN! (do not actually call) (1)</option>
                                    <option value="0" class="bgYel" 
                                        @if (!$firstReview && isset($yourReview->ComRevOutrage) && intVal($yourReview->ComRevOutrage) == 0)
                                            SELECTED
                                        @endif
                                        >WOW, that seriously sucks, &lt;3 (0)</option>
                                    <option value="-1" class="bgRed" 
                                        @if (!$firstReview && isset($yourReview->ComRevOutrage) && intVal($yourReview->ComRevOutrage) == -1)
                                            SELECTED
                                        @endif
                                        >Sorry to hear, life's not fair (-1)</option>
                                </select>
                                
                            </div>
                            
                        </div>
                    </div>
                    
                    <script type="text/javascript">
                    function updateRatingDrop(which) 
                    {
                        if (document.getElementById(which+'ID'))
                        {
                            var newClass = 'form-control f18 mB10';
                            if (document.getElementById(which+'ID').value == 1) newClass += ' bgGrn';
                            else if (document.getElementById(which+'ID').value == 0) newClass += ' bgYel';
                            else if (document.getElementById(which+'ID').value == -1) newClass += ' bgRed';
                            document.getElementById(which+'ID').className = newClass;
                        }
                        return true;
                    }
                    updateRatingDrop('revReadability');
                    updateRatingDrop('revConsistency');
                    updateRatingDrop('revRealistic');
                    updateRatingDrop('revOutrage');
                    </script>
                    
                    <div class="mTn5 brdTop">
                        <div class="checkbox f16">
                            <nobr><label class="mL10">
                                <input type="checkbox" name="revMakeFeatured" value="1" 
                                    @if (!$firstReview && intVal($yourReview->ComRevMakeFeatured) == 1)
                                        CHECKED
                                    @endif
                                    > Feature complaint
                            </label></nobr>
                            <nobr><label class="mL10">
                                <input type="checkbox" name="revExplicitLang" value="1" 
                                    @if (!$firstReview && intVal($yourReview->ComRevExplicitLang) == 1)
                                        CHECKED
                                    @endif
                                    > Explicit language
                            </label></nobr>
                            <nobr><label class="mL10">
                                <input type="checkbox" name="revGraphicContent" value="1" 
                                    @if (!$firstReview && intVal($yourReview->ComRevGraphicContent) == 1)
                                        CHECKED
                                    @endif
                                    > Graphic content
                            </label></nobr>
                        </div>
                    </div>
                    
                    <div class="mTn5">
                        <b class="f16">Notes for other evaluators</b>
                        @if (!$firstReview)
                            <div class="pL10 f18"><i class="red">You previously noted:</i> {{ $yourReview->ComRevNote }}</div>
                        @endif
                        <div class="pL10 pB5">
                            <textarea name="revNote" class="form-control f18" style="height: 70px;" ></textarea>
                        </div>
                    </div>
                            
                    
                    <b class="f16">Change Complaint Status</b>
                    <div class="pL10 pB10">
                        {!! view('openpolice/admin/complaints/complaint-review-status', [
                            "firstReview"     => $firstReview, 
                            "latestReview"     => $yourReview,
                            "settings"        => $settings
                            ])->render() !!}
                    </div>
                    
                
                    <b class="f16">Next Action After Saving</b>
                    <div class="pL10 pB10">
                        <select name="revNextAction" class="form-control f22" >
                            <option value="update" SELECTED >View Past Complaint Reviews</option>
                            <option value="emails"  >Send to Oversight Agency/Internal Affairs</option>
                            <option value="listing"  >Back to list of complaints</option>
                        </select>
                    </div>
            
                    
                </div>
                
                <center><input type="submit" class="btn btn-lg btn-primary f24 mT5" value="Save Review"></center>
            
            
            </div>
            
            </form>
            
            
        </div>
        <div class="col-md-8">
        
            {!! $fullReport !!}
        
        </div>
    </div>
            

@else                         
    
    
    @if ($viewType == 'emails' || $viewType == 'emailsType')
    
        {!! $sendingEmails !!}
        
    @endif
    
    
    
    
    @if ($settings["Complaint Evaluations"] == 'N' && $firstReview)
        
        <form action="/dashboard/complaint/{{ $cID }}/review/save" method="post" >
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="cID" value="{{ $cID }}">
        <input type="hidden" name="revType" value="Full">
        
        <div class="row2 round10 pT5 pR20 pB20 mLn10">
            
            <table border=0 class="table">
                <tr>
                    <td rowspan=2 class="w20 f26 slBlueDark bld" style="border: 0px none;" >
                        <nobr>Spam Check:</nobr><br /><nobr>Complaint #{{ $cID }}</nobr>
                    </td>
                    <td colspan=2 style="border: 0px none;" >
                        
                        <div class="radio f18">
                            <nobr><label class="mR20">
                                <input type="radio" name="revComplaintType" value="196" 
                                    @if ($firstReview || $GLOBALS["DB"]->getDefValue('OPC Staff/Internal Complaint Type', $yourReview->ComRevComplaintType) == 'Legitimate')
                                        CHECKED 
                                    @endif
                                    > Police Complaint
                            </label></nobr>
                            <nobr><label class="mL20 mR20">
                                <input type="radio" name="revComplaintType" value="197" 
                                    @if (!$firstReview && $GLOBALS['DB']->getDefValue('OPC Staff/Internal Complaint Type', $yourReview->ComRevComplaintType) == 'Not About Police')
                                        CHECKED
                                    @endif
                                    > Not About Police
                            </label></nobr>
                            <nobr><label class="mL20 mR20">
                                <input type="radio" name="revComplaintType" value="198" 
                                    @if (!$firstReview && $GLOBALS['DB']->getDefValue('OPC Staff/Internal Complaint Type', $yourReview->ComRevComplaintType) == 'Abuse')
                                        CHECKED
                                    @endif
                                    > Abuse
                            </label></nobr>
                            <nobr><label class="mL20 mR20">
                                <input type="radio" name="revComplaintType" value="199" 
                                    @if (!$firstReview && $GLOBALS['DB']->getDefValue('OPC Staff/Internal Complaint Type', $yourReview->ComRevComplaintType) == 'Spam')
                                        CHECKED
                                    @endif
                                    > Spam
                            </label></nobr>
                            <nobr><label class="mL20 mR20">
                                <input type="radio" name="revComplaintType" value="200" 
                                    @if (!$firstReview && $GLOBALS['DB']->getDefValue('OPC Staff/Internal Complaint Type', $yourReview->ComRevComplaintType) == 'Test')
                                        CHECKED
                                    @endif
                                    > Test Submission
                            </label></nobr>
                            <nobr><label class="mL20">
                                <input type="radio" name="revComplaintType" value="200" 
                                    @if (!$firstReview && $GLOBALS['DB']->getDefValue('OPC Staff/Internal Complaint Type', $yourReview->ComRevComplaintType) == 'Not Sure')
                                        CHECKED
                                    @endif
                                    > Not Sure
                            </label></nobr>
                        </div>
                    
                    </td>
                </tr>
                <tr>
                    <td class="w40 pR20" style="border: 0px none;">
                    
                        <b class="f18">Notes for other staff:</b><br />
                        <textarea name="revNote" class="form-control f18" style="height: 40px;" ></textarea>

                    </td>
                    <td class="w40 pL20" style="border: 0px none;">
                
                        <b class="f18">Change Complaint Status:</b><br />
                        {!! view('openpolice/admin/complaints/complaint-review-status', [
                            "firstReview"     => $firstReview, 
                            "latestReview"     => $yourReview,
                            "settings"        => $settings
                            ])->render() !!}
                        
                    </td>
                </tr>
            </table>
                        
            <div class="fR"><input type="submit" class="btn btn-lg btn-primary f24 mT5" value="Save Review"></div>
            <div class="fC"></div>
            
            </div>
            
        </div>
            
        </form>
    
            
        
    @elseif ($viewType == 'history' || $viewType == 'update' || $viewType == 'emails' || $viewType == 'emailsType')
        @if ($viewType == 'update')
            <script type="text/javascript">
                $( document ).ready(function() {
                    window.location = '#new';
                });
            </script>
        @endif
    
        <div id="analystHistory" class="p20 round5 disBlo">
            
            {!! $allReviews !!}
            
        </div>
        <div class="brdTop brdBot p10 m20"></div>
            
    @endif
    
    
    {!! $fullReport !!}
    
    
@endif

    
@endsection


