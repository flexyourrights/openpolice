<?php
namespace OpenPolice\Controllers;

use DB;
use Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

use App\Models\User;

use App\Models\OPEventSequence;
use App\Models\OPStops;
use App\Models\OPSearches;
use App\Models\OPArrests;
use App\Models\OPForce;
use App\Models\OPOrders;
use App\Models\OPInjuries;
use App\Models\OPEvidence;
use App\Models\OPDepartments;
use App\Models\OPLinksComplaintDept;
use App\Models\OPLinksOfficerEvents;
use App\Models\OPLinksOfficerOrders;
use App\Models\OPLinksCivilianEvents;
use App\Models\OPLinksCivilianOrders;

use OpenPolice\Controllers\OpenPoliceReport;
use OpenPolice\Controllers\VolunteerController;

use SurvLoop\Controllers\SurvFormTree;

class OpenPolice extends SurvFormTree
{
    
    public $classExtension         = 'OpenPolice';
    public $treeID                 = 1;
    
    // Initialize some more stuff which is unique to OPC...
    protected $allCivs             = [];
    public $eventTypes             = ['Stops', 'Searches', 'Force', 'Arrests'];
    protected $eventTypeLabel     = [
        'Stops'    => 'Stop/Questioning',
        'Searches' => 'Search/Seizure',
        'Force'    => 'Use of Force',
        'Arrests'  => 'Arrest'
    ];
    protected $eventTypeLookup     = []; // $eveSeqID => 'Event Type'
    protected $eventCivLookup     = []; // array('Event Type' => array($civID, $civID, $civID))
    
    // Allegations in descending order of severity
    public $worstAllegations = [
        'Sexual Assault', 'Unreasonable Force', 'Wrongful Arrest', 
        'Wrongful Property Seizure', 'Intimidating Display Of Weapon', 
        'Wrongful Search', 'Wrongful Entry', 'Wrongful Detention', 
        'Bias-Based Policing', 'Retaliation', 'Retaliation: Unnecessary Charges', 
        'Conduct Unbecoming an Officer', 'Discourtesy', 'Policy or Procedure', 
        'Miranda Rights', 'Officer Refused To Provide ID'
    ];
    
    protected function isSilver()
    { 
        if (!isset($this->sessData->dataSets["Complaints"])) return false;
        return ($this->sessData->dataSets["Complaints"][0]->ComAwardMedallion == 'Silver'); 
    }
    
    protected function isGold()
    {     
        if (!isset($this->sessData->dataSets["Complaints"])) return false;
        return ($this->sessData->dataSets["Complaints"][0]->ComAwardMedallion == 'Gold');
    }
    
    protected function isPublic()
    {
        if (!isset($this->sessData->dataSets["Complaints"])) return false;
        return ($this->sessData->dataSets["Complaints"][0]->ComPrivacy 
            == $GLOBALS["DB"]->getDefID('Privacy Types', 'Submit Publicly'));
    }
    
    protected function moreThan1Victim()
    { 
        if (!isset($this->sessData->loopItemIDs['Victims'])) return false;
        return (sizeof($this->sessData->loopItemIDs['Victims']) > 1); 
    }
    
    protected function moreThan1Officer() 
    { 
        if (!isset($this->sessData->loopItemIDs['Officers'])) return false;
        return (sizeof($this->sessData->loopItemIDs["Officers"]) > 1); 
    }
    
    protected function movePrevOverride($nID) { return -3; }
    
    protected function moveNextOverride($nID) { return -3; }
    
    // Initializing a bunch of things which are not [yet] automatically determined by the software
    protected function initExtra(Request $request)
    {
        // Establishing Main Navigation Organization, with Node ID# and Section Titles
        $this->majorSections = [];
        $this->majorSections[] = array(1,             'Your Story');
        $this->majorSections[] = array(4,             'Who\'s Involved');
        $this->majorSections[] = array(5,             'Allegations');
        $this->majorSections[] = array(6,             'Go Gold');
        $this->majorSections[] = array(419,         'Finish');
        
        $this->minorSections = array( [], [], [], [], [] );
        $this->minorSections[0][] = array(157,         'Start Your Story');
        $this->minorSections[0][] = array(437,         'Privacy Options');
        $this->minorSections[0][] = array(158,         'When');
        $this->minorSections[0][] = array(159,         'Where');
        
        $this->minorSections[1][] = array(139,         'About You');
        $this->minorSections[1][] = array(140,         'Victims');
        $this->minorSections[1][] = array(141,         'Witnesses');
        $this->minorSections[1][] = array(144,         'Police Departments');
        $this->minorSections[1][] = array(142,         'Officers');
        
        $this->minorSections[2][] = array(198,         'Stops');
        $this->minorSections[2][] = array(199,         'Searches');
        $this->minorSections[2][] = array(200,         'Force');
        $this->minorSections[2][] = array(201,         'Arrests');
        $this->minorSections[2][] = array(202,         'Citations');
        $this->minorSections[2][] = array(154,         'Other');
        
        $this->minorSections[3][] = array(484,         'What Happened');
        $this->minorSections[3][] = array(149,         'Stops');
        $this->minorSections[3][] = array(150,         'Searches');
        $this->minorSections[3][] = array(151,         'Force');
        $this->minorSections[3][] = array(152,         'Arrests');
        $this->minorSections[3][] = array(153,         'Citations');
        $this->minorSections[3][] = array(410,         'Injuries');
        
        $this->minorSections[4][] = array(420,         'Review Narrative');
        $this->minorSections[4][] = array(431,         'Sharing Options');
        $this->minorSections[4][] = array(156,         'Submit Complaint');
        
        //echo '<pre>'; print_r(Auth::user()); echo '</pre><div style="padding: 100px;">' . Auth::user()->name . '</div>';
        return true;
    }
        
    // Initializing a bunch of things which are not [yet] automatically determined by the software
    protected function loadExtra()
    {
        if (!isset($this->sessData->dataSets["Complaints"][0]->ComUniqueStr) 
            || trim($this->sessData->dataSets["Complaints"][0]->ComUniqueStr) == '') {
            $this->sessData->dataSets["Complaints"][0]->update([
                'ComUniqueStr' => $this->getRandStr('Complaints', 'ComUniqueStr', 20)
            ]);
        }
        if ((!isset($this->sessData->dataSets["Complaints"][0]->ComIPaddy) 
            || trim($this->sessData->dataSets["Complaints"][0]->ComIPaddy) == '') && isset($_SERVER["REMOTE_ADDR"])) {
            $this->sessData->dataSets["Complaints"][0]->update([
                'ComIPaddy' => bcrypt($_SERVER["REMOTE_ADDR"])
            ]);
        }
        if (!isset($this->sessData->dataSets["Complaints"][0]->ComIsMobile) 
            || trim($this->sessData->dataSets["Complaints"][0]->ComIsMobile) == '') {
            $isMobile = 0;
            if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec'
                . '|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?'
                . '|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap'
                . '|windows (ce|phone)|xda|xiino/i', $_SERVER['HTTP_USER_AGENT'])
                || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av'
                . '|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb'
                . '|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw'
                . '|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8'
                . '|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit'
                . '|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)'
                . '|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji'
                . '|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga'
                . '|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)'
                . '|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf'
                . '|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil'
                . '|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380'
                . '|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc'
                . '|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01'
                . '|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)'
                . '|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61'
                . '|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', 
                substr($_SERVER['HTTP_USER_AGENT'],0,4))) {
                $isMobile = 1;
            }
            $this->sessData->dataSets["Complaints"][0]->update([
                'ComIsMobile' => $isMobile
            ]);
        }
        if ($this->v["user"] && intVal($this->v["user"]->id) > 0 && isset($this->sessData->dataSets["Civilians"]) 
            && isset($this->sessData->dataSets["Civilians"][0])
            && (!isset($this->sessData->dataSets["Civilians"][0]->CivUserID) 
                || intVal($this->sessData->dataSets["Civilians"][0]->CivUserID) <= 0)) {
            $this->sessData->dataSets["Civilians"][0]->update([
                'CivUserID' => $this->v["user"]->id
            ]);
        }
        $this->v["isPublic"] = $this->isPublic();
        return true;
    }
    
    protected function isOwnerUser()
    {
        if (!$this->v["user"] || intVal($this->v["user"]->id) <= 0) return false;
        return ($this->sessData->dataSets["Civilians"][0]->CivUserID == $this->v["user"]->id);
    }
    
    protected function overrideMinorSection($nID = -3, $majorSectInd = -3)
    {
        if (in_array($nID, [286, 285, 288])) return 148;
        return -1;
    }
    
    // CUSTOM={OnlyIfNoAllegationsOtherThan:WrongStop,Miranda,PoliceRefuseID]
    protected function checkNodeConditionsCustom($nID, $condition = '')
    {
        if ($condition == '#NoSexualAllegation') {
            $noSexAlleg = true;
            if (isset($this->sessData->dataSets["Allegations"]) 
                && sizeof($this->sessData->dataSets["Allegations"]) > 0) {
                foreach ($this->sessData->dataSets["Allegations"] as $alleg) {
                    if (in_array($alleg->AlleType, [
                        $GLOBALS["DB"]->getDefID('Allegation Type', 'Sexual Assault'), 
                        $GLOBALS["DB"]->getDefID('Allegation Type', 'Sexual Harassment')
                        ])) {
                        $noSexAlleg = false;
                    }
                }
            }
            return $noSexAlleg;
        } elseif ($condition == '#HasArrestOrForce') {
            if ($this->sessData->dataHas('Arrests') || $this->sessData->dataHas('Force')) return true;
            else return false;
        } elseif (in_array($condition, [
            '#PreviousEnteredStops', 
            '#PreviousEnteredSearches', 
            '#PreviousEnteredForce', 
            '#PreviousEnteredArrests'
            ])) {
            return (sizeof($this->getPreviousEveSeqsOfType($GLOBALS["DB"]->closestLoop["itemID"])) > 0);
        } elseif ($condition == '#HasForceHuman') {
            if (!$this->sessData->dataHas('Force') || sizeof($this->sessData->dataSets["Force"]) == 0) return false;
            $foundHuman = false;
            foreach ($this->sessData->dataSets["Force"] as $force) {
                if (trim($force->ForAgainstAnimal) != 'Y') $foundHuman = true;
            }
            return $foundHuman;
        } elseif ($condition == '#Property') {
            $search = $this->sessData->getChildRow('EventSequence', $GLOBALS["DB"]->closestLoop["itemID"], 'Searches');
            if ((isset($search->SrchSeized) && trim($search->SrchSeized) == 'Y')
                || (isset($search->SrchDamage) && trim($search->SrchDamage) == 'Y')) {
                return true;
            } else {
                return false;
            }
        }
        return true; 
    }
    
    protected function loadSessionDataSavesExceptions($nID)
    { 
        return false; 
    }
    
    
/*****************************************************************************
// START Processes Which Override Basic Node Printing and $_POST Processing
*****************************************************************************/
    
    protected function rawOrderPercentTweak($nID, $rawPerc, $found = -3)
    { 
        if ($this->isGold()) return $rawPerc;
        return round(100*($found/(sizeof($this->nodesRawOrder)-200)));
    }
    
    protected function loadProgBarTweak()
    {
        if ($this->currNode() == 137 && !in_array(4, $this->sessMajorsTouched)) {
            $this->currMajorSection = 1;
            $this->currMinorSection = 0;
        }
        return true;
    }

    protected function customNodePrint($nID = -3, $tmpSubTier = [])
    {
        $ret = '';
        if ($nID == 454) {
            return view('vendor.openpolice.registerComplaint', [
                "complaintID" => $this->coreID, 
                "anonyLogin" => ( in_array($this->sessData->dataSets["Complaints"][0]->ComUnresolvedCharges, ['Y', '?'])
                    || intVal($this->sessData->dataSets["Complaints"][0]->ComPrivacy) 
                        == intVal($GLOBALS["DB"]->getDefID('Privacy Types', 'Completely Anonymous')) ), 
                "anonyPass" => uniqid(),
                "currAdmPage" => '', 
                "user" => Auth::user()
            ])->render();
        } elseif ($nID == 576) {
            $ret .= view('vendor.openpolice.nodes.576-vehicle-details', [
                "nodes"            =>    [576, 577, 578, 91, 92, 93, 94, 95], 
                "alreadyDescribed" =>    $this->getNodeCurrSessData($nID),
                "whichVehic"       =>    $this->getNodeCurrSessData(577),
                "vehicDeets"       =>    $this->printNodePublic(578),
                "vehicles"         =>    $this->getVehicles()
            ])->render();
        } elseif ($nID == 571) {
            $ret .= view('vendor.openpolice.nodes.576-vehicle-details', [
                "nodes"            =>    [571, 572, 583, 131, 132, 133, 134, 135], 
                "alreadyDescribed" =>    $this->getNodeCurrSessData($nID),
                "whichVehic"       =>    $this->getNodeCurrSessData(572),
                "vehicDeets"       =>    $this->printNodePublic(583),
                "vehicles"         =>    $this->getVehicles()
            ])->render();
        } elseif ($nID == 584) {
            $ret .= view('vendor.openpolice.nodes.576-vehicle-details', [
                "nodes"                =>    [584, 585, 589, 189, 190, 191, 192, -3], 
                "alreadyDescribed"    =>    $this->getNodeCurrSessData($nID),
                "whichVehic"        =>    $this->getNodeCurrSessData(585),
                "vehicDeets"        =>    $this->printNodePublic(589),
                "vehicles"            =>    $this->getVehicles()
            ])->render();
        } elseif ($nID == 145) {
            $this->nextBtnOverride = 'To continue, please find and select a department above';
            $searchSuggest = [];
            $deptCitys = OPDepartments::select('DeptAddressCity')
                ->distinct()
                ->where('DeptAddressState', $this->sessData->dataSets["Incidents"][0]->IncAddressState)
                ->get();
            if ($deptCitys && sizeof($deptCitys) > 0) {
                foreach ($deptCitys as $dept) {
                    if (!in_array($dept->DeptAddressCity, $searchSuggest)) {
                        $searchSuggest[] = json_encode($dept->DeptAddressCity);
                    }
                }
            }
            $deptCounties = OPDepartments::select('DeptAddressCounty')
                ->distinct()
                ->where('DeptAddressState', $this->sessData->dataSets["Incidents"][0]->IncAddressState)
                ->get();
            if ($deptCounties && sizeof($deptCounties) > 0) {
                foreach ($deptCounties as $dept) {
                    if (!in_array($dept->DeptAddressCounty, $searchSuggest)) {
                        $searchSuggest[] = json_encode($dept->DeptAddressCounty);
                    }
                }
            }
            $deptFed = OPDepartments::select('DeptName')->where('DeptType', 366)->get();
            if ($deptFed && sizeof($deptFed) > 0) {
                foreach ($deptFed as $dept) {
                    if (!in_array($dept->DeptName, $searchSuggest)) {
                        $searchSuggest[] = json_encode($dept->DeptName);
                    }
                }
            }
            $this->pageAJAX .= view('vendor.openpolice.nodes.145-ajax-dept-search', [
                "searchSuggest" => $searchSuggest
            ])->render();
            $ret .= view('vendor.openpolice.nodes.145-dept-search', [ 
                "IncAddressCity" => $this->sessData->dataSets["Incidents"][0]->IncAddressCity, 
                "stateDropstateDrop" => $GLOBALS["DB"]->states->stateDrop($this->sessData->dataSets["Incidents"][0]->IncAddressState, true) 
            ])->render();
        } elseif ($nID == 285) {
            $this->checkHasEventSeq($nID);
            $victims = $this->sessData->loopItemIDs['Victims'];
            $victimNames = $victimArrest = [];
            foreach ($victims as $i => $civ) $victimNames[$civ] = $this->getCivilianNameFromID($civ);
            $victim1youLower = ((isset($victims[0]) && isset($victimNames[$victims[0]])) ? $this->youLower($victimNames[$victims[0]]) : 'you');
            //$victim1youLower = $this->youLower($victimNames[$victims[0]]);
            
            $sceneType = 'vehicle';
            if (in_array($this->sessData->dataSets["Scenes"][0]->ScnType, [
                    $GLOBALS["DB"]->getDefID('Scene Type', 'Home or Private Residence'), 
                    $GLOBALS["DB"]->getDefID('Scene Type', 'Workplace')
                ])) {
                $sceneType = 'home';
            }
            
            $forceAnimal = [];
            if (isset($this->eventCivLookup["Force Animal"][0]) 
                && intVal($this->eventCivLookup["Force Animal"][0]) > 0) {
                $forceAnimal = $this->sessData->getRowById('Force', $this->eventCivLookup["Force Animal"][0]);
            }
            $GLOBALS["DB"]->loadDefinitions('Force Type');
            $otherInd = -3;
            foreach ($GLOBALS["DB"]->defValues["Force Type"] as $i => $fType) {
                if ($fType->DefValue == 'Other') $otherInd = $i;
            }
            
            $this->pageAJAX .= '$("#n285fldForceAnimalID").click(function() {
                if (document.getElementById("n285fldForceAnimalID").checked) { $("#node285").slideDown("fast"); }
                else { $("#node285").slideUp("fast"); }
            });' . "\n";
            $this->pageJSvalid .= "if (document.getElementById('n285fldForceAnimalID').checked) {
                if (document.getElementById('ForceAnimalDescID').value.trim() == '') { setFormLabelRed(285); totFormErrors++; }
                else { setFormLabelBlack(285); }
            } \n";
            foreach ($victims as $i => $civ) {
                $this->pageAJAX .= '$("#n285fldForce'.$i.'").click(function(){
                    if (document.getElementById("n285fldForce'.$i.'").checked) $("#forceCiv'.$i.'").slideDown("fast");
                    else $("#forceCiv'.$i.'").slideUp("fast");
                });' . "\n";
            }
            $forceCivs = $this->getCivForceTypes();
            if (sizeof($forceCivs) > 0) {
                foreach ($forceCivs as $civID => $forces) {
                    $this->pageFldList[] = 'n-'.$civID.'fld';
                    foreach ($GLOBALS["DB"]->defValues["Force Type"] as $i => $fType) $this->pageFldList[] = 'n'.$civID.'fld'.$i;
                    $this->pageJSvalid .= "if (document.getElementById('n".$civID."VisibleID').value == 1) reqFormFldRadio(".$civID.", " . sizeof($GLOBALS["DB"]->defValues["Force Type"]) . ");
                                   if (document.getElementById('n-".$civID."VisibleID').value == 1) reqFormFld(-".$civID.");";
                    $this->pageAJAX .= 'conditionNodes['.$civID.'] = true; nodeKidList['.$civID.'] = [-'.$civID.']; nodeKidList[-'.$civID.'] = new [];
                    $(".n'.$civID.'fldCls").click(function(){ var foundKidResponse = false;
                        if (document.getElementById("n'.$civID.'fld'.$otherInd.'").checked) foundKidResponse = true;
                        if (foundKidResponse) { document.getElementById("node'.$civID.'kids").style.display="inline"; kidsVisible('.$civID.', true, true); } else { document.getElementById("node'.$civID.'kids").style.display="none"; kidsVisible('.$civID.', false, true); }
                    });' . "\n";
                }
            }
                
            $ret .= view('vendor.openpolice.nodes.285-gold-what-happened', [ 
                "victims"         => $victims,
                "victimNames"     => $victimNames, 
                "victim1youLower" => $victim1youLower, 
                "sceneType"       => $sceneType, 
                "eventTypes"      => $this->eventTypes,
                "eventTypeLabel"  => $this->eventTypeLabel, 
                "forceCivs"       => $forceCivs, 
                "forceType"       => $GLOBALS["DB"]->defValues["Force Type"], 
                "otherInd"        => $otherInd, 
                "forceAnimal"     => $forceAnimal, 
                "eventCivLookup"  => $this->eventCivLookup, 
                "userRole"        => trim($this->sessData->dataSets["Civilians"][0]->CivRole)
            ])->render();
            $this->pageHasReqs++;
        } elseif ($nID == 288) {
            $this->pageAJAX .= '$("#sortable").sortable({ axis: "y", update: function (event, ui) {
                var data = $(this).sortable("serialize");
                //alert("/ajax/?saveIncEveOrder=1&"+data);
                document.getElementById("hidFrameID").src="/ajax/?saveIncEveOrder=1&"+data+"";
                //$("#hidFrameID").load("/ajax/?saveIncEveOrder=1&"+data);
            } }); $("#sortable").disableSelection();';
                // $.ajax({ data: data, type: "POST", url: "/ajax/?saveIncEveOrder=1" });
            $ret .= '<div id="nLabel288" class="nPrompt">
                <h2>Please drag the events below into the order they happened.</h2> 
            </div>
            <br /><ul id="sortable">' . "\n";
            $eveSeqs = $this->getEventSequence();
            if (sizeof($eveSeqs) > 0) {
                foreach ($eveSeqs as $eveSeq) {
                    $ret .= '<li id="item-'.$eveSeq["EveID"].'" class="sortOff" onMouseOver="'
                        . 'this.className=\'sortOn\';" onMouseOut="this.className=\'sortOff\';">'
                        . '<span><i class="fa fa-ellipsis-v slBlueLight mL20 mR20"></i></span> ' 
                        . $this->printEventSequenceLine($eveSeq) . '</li>' . "\n";
                }
            }
            $ret .= '</ul>' . "\n";
            
        } elseif ($nID == 415) {
            $civInjs = $this->getCivSetPossibilities('Injuries', 'InjuryCare');
            $ret .= '<div id="node415" class="nodeWrap">
            <div class="nPrompt"><div class="nPromptHeader">Medical Care</div>
            Did ' . ((sizeof($civInjs["opts"]) == 1) 
                ? $this->youLower($this->getCivilianNameFromID($civInjs["opts"][0])) 
                : 'anyone with injuries') . ' receive medical care?
            </div>';
            if (sizeof($civInjs["opts"]) == 1) {
                $ret .= '<div class="nFld" style="font-size: 130%;"><nobr>'
                    . '<input type="checkbox" autocomplete="off" value="' . $civInjs["opts"][0] 
                    . '" name="InjuryCare[]" id="InjuryCare' . $civInjs["opts"][0] . '" ' 
                    . ((sizeof($civInjs["active"]) > 0) ? 'CHECKED' : '') . ' > 
                    <label for="InjuryCare'.$civInjs["opts"][0].'">Yes</label></nobr></div>';
            } else {
                $ret .= $this->formCivSetPossibilities('Injuries', 'InjuryCare', $civInjs);
            }
            $ret .= '<div class="slRedDark pL10 pT20 mT20 pB20">
                <i><b>IMPORTANT!</b> If anyone has been injured, they need to get medical attention now! 
                The official medical evidence will also help improve your complaint.</i>
            </div>
            </div>';
        } elseif ($nID == 270) {
            $url = '/report/' . $this->coreID;
            if (trim($this->sessData->dataSets["Complaints"][0]->ComSlug) != '') {
                $url = '/report' . $this->sessData->dataSets["Complaints"][0]->ComSlug;
            }
            $ret .= '<br /><br /><center><h1>All Done! Taking you to <a href="' . $url . '">your finished '
                . 'complaint</a>...</h1></center><script type="text/javascript"> setTimeout("window.location=\'' 
                . $url . '\'", 1500); </script>';
            $this->createNewSess();
        }
        return $ret;
    }
    
    // This is more for special behavior which is repeating for multiple nodes, compared to one-time specialization 
    // of customNodePrint(). Also in contrast to customNodePrint(), printSpecial() runs within the standard 
    // node print process (not replacing it) and does not require printing of the <form> or prompt text.
    protected function printSpecial($nID = -3, $promptNotesSpecial = '', $currNodeSessionData = '')
    { 
        $ret = '';
        if ($promptNotesSpecial == '[[MergeVictimsEventSequence]]') {
            $eventType = $this->getEveSeqTypeFromNode($nID);
            $typeLabel = (($eventType == 'Stops') ? 'stop' 
                : (($eventType == 'Searches') ? 'search' 
                    : (($eventType == 'Force') ? 'force' : 'arrest')));
            $ret .= '<div class="nPrompt">Was this ' . $typeLabel 
                . ' the same as another victim\'s ' . $typeLabel . '?</div>
            <div class="nFld pB20 mB20">' . "\n";
                $prevEvents = $this->getPreviousEveSeqsOfType($GLOBALS["DB"]->closestLoop["itemID"]);
                if (sizeof($prevEvents) > 0) {
                    foreach ($prevEvents as $i => $prevEveID) {
                        $eveSeq = $this->getEventSequence($prevEveID);
                        $ret .= '<div class="nFld pT20" style="font-size: 125%;"><label for="eventMerge' . $i . '">
                            <input type="radio" name="eventMerge" id="eventMerge' . $i . '" value="' . $prevEveID . '">
                            <span class="mL5">' . $this->printEventSequenceLine($eveSeq[0]) . '</span>
                        </label></div>' . "\n";
                    }
                }
                $ret .= '<div class="nFld pT20 pB20" style="font-size: 125%;"><label for="eventMergeNo">
                    <input type="radio" name="eventMerge" id="eventMergeNo" value="-3"> <span class="mL5">No</span>
                </label></div>
            </div>';
        } elseif ($promptNotesSpecial == '[[OfficerOrders]]') {
            $eventType = $this->getEveSeqTypeFromNode($nID);
            $eventOffs = $this->getLinkedToEvent('Officer', $GLOBALS["DB"]->closestLoop["itemID"]);
            $eventCivs = $this->getLinkedToEvent('Civilian', $GLOBALS["DB"]->closestLoop["itemID"]);
            $eveSeqOrders = $this->getEventOrders($GLOBALS["DB"]->closestLoop["itemID"], $eventType);
            for ($i=0; $i<20; $i++) {
                $currOrder = [];
                if (isset($eveSeqOrders[$i])) {
                    $currOrder = $eveSeqOrders[$i];
                    $currOrder->Officers  = $this->getLinkedToEvent('Officer', -3, -3, $currOrder->OrdID);
                    $currOrder->Civilians = $this->getLinkedToEvent('Civilian', -3, -3, $currOrder->OrdID);
                }
                $ret .= '<input type="hidden" name="ordID'.$i.'" value="' 
                    . ((isset($currOrder->OrdID)) ? $currOrder->OrdID : -3) . '">
                    <input type="hidden" id="orderVis'.$i.'ID" name="orderVis'.$i.'" value="' 
                    . (($i == 0 || isset($currOrder->OrdID)) ? 'Y' : 'N') . '">
                    <a name="ord'.$i.'"></a><div id="order'.$i.'" class="dis' 
                    . (($i == 0 || isset($currOrder->OrdID)) ? 'Blo' : 'Non') . ' w100 orderBlock">' . "\n";
                
                if ($i > 0) {
                    $ret .= '<a href="#ord'.($i-1).'" id="delOrder'.$i.'" class="nFormLnkDel fR mTn10">'
                        . '<i class="fa fa-minus-square-o"></i></a><div class="fC mBn10"></div>';
                }
                
                $offs = $this->sessData->getLoopRows('Officers');
                if (sizeof($offs) > 1) { 
                    $ret .= '<div class="nodeWrap pB20"><div class="nPrompt">Order From: '
                        . '<div class="nFld disIn mL20 mR20">' . "\n";
                    foreach ($offs as $offInd => $off) {
                        $ret .= '<div class="nFld disIn mR20"><nobr><input type="checkbox" '
                             . 'name="order'.$i.'Off[]" id="order'.$i.'Off'.$offInd.'" value="' . $off->OffID . '" ';
                        if (isset($currOrder->OrdID)) {
                            if (in_array($off->OffID, $currOrder->Officers)) $ret .= 'CHECKED'; 
                        }
                        elseif (in_array($off->OffID, $eventOffs)) $ret .= 'CHECKED';
                        $ret .= ' > <label for="order'.$i.'Off'.$offInd.'">' 
                            . $this->getLoopItemLabel('Officers', $off, $offInd) 
                            . '</label></nobr></div> ' . "\n";
                    }
                    $ret .= '</div></div></div>' . "\n";
                } elseif (sizeof($offs) == 1) {
                    $ret .= '<input type="hidden" name="order'.$i.'Off" '
                        . 'id="order'.$i.'OffID" value="' . $offs[0]->OffID . '">' . "\n";
                }
                
                $civs = $this->getCivilianList('Civilians');
                if (sizeof($civs) > 1) { 
                    $ret .= '<div class="nodeWrap pB20"><div class="nPrompt">Order To: '
                        . '<div class="nFld disIn mL20 mR20">' . "\n";
                    foreach ($civs as $civInd => $civ) {
                        $ret .= '<div class="nFld disIn mR20"><nobr><input type="checkbox" '
                            . 'name="order'.$i.'Civ[]" id="order'.$i.'Civ'.$civInd.'" value="' . $civ . '" ';
                        if (isset($currOrder->OrdID)) {
                            if (in_array($civ, $currOrder->Civilians)) $ret .= 'CHECKED'; 
                        } elseif (in_array($civ, $eventCivs)) {
                            $ret .= 'CHECKED';
                        }
                        $ret .= ' > <label for="order'.$i.'Civ'.$civInd.'">' 
                            . $this->getLoopItemLabel('Civilians', $this->sessData->getRowById('Civilians', $civ), $civInd) 
                            . '</label></nobr></div> ' . "\n";
                    }
                    $ret .= '</div></div></div>' . "\n";
                } elseif (sizeof($civs) == 1) {
                    $ret .= '<input type="hidden" name="order'.$i.'Civ" '
                        . 'id="order'.$i.'CivID" value="' . $civs[0] . '">' . "\n";
                }
                
                $ret .= '<div class="nodeWrap"><div class="nPrompt">Order Given:</div><div class="nFld">'
                    . '<input type="text" name="order'.$i.'" id="order'.$i.'ID" class="form-control" value="' 
                    . ((isset($currOrder->OrdID)) ? $currOrder->OrdDescription : '') . '"></div></div>';
                
                if ($nID == 342) { // only if Order for Use of Force
                    $ret .= '<div class="nodeWrap">
                        <div class="nPrompt disIn mR20">Did recipient have trouble hearing order?</div>
                        <div class="nFld disIn mR20"><nobr><label for="hearOrder'.$i.'Y">
                            <input id="hearOrder'.$i.'Y" name="hearOrder'.$i.'" value="Y" type="radio" autocomplete="off" class="ordHearCls" ' 
                            . ((isset($currOrder->OrdID) && $currOrder->OrdTroubleUnderYN == 'Y') ? 'CHECKED' : '') 
                            . ' > Yes
                        </label></nobr></div>
                        <div class="nFld disIn mR20"><nobr><label for="hearOrder'.$i.'N">
                            <input id="hearOrder'.$i.'N" name="hearOrder'.$i.'" value="N" type="radio" autocomplete="off" class="ordHearCls" ' 
                            . ((isset($currOrder->OrdID) && $currOrder->OrdTroubleUnderYN == 'N') ? 'CHECKED' : '') 
                            . ' > No
                        </label></nobr></div>
                        <div class="nFld disIn"><nobr><label for="hearOrder'.$i.'Q">
                            <input id="hearOrder'.$i.'Q" name="hearOrder'.$i.'" value="?" type="radio" autocomplete="off" class="ordHearCls" ' 
                            . ((isset($currOrder->OrdID) && $currOrder->OrdTroubleUnderYN == '?') ? 'CHECKED' : '') 
                            . ' > Don\'t Know
                        </label></nobr></div>
                    </div>
                    <div id="orderHearing'.$i.'" class="nodeWrap pL20 dis' 
                        . ((isset($currOrder->OrdID) && $currOrder->OrdTroubleUnderYN == 'Y') ? 'Blo' : 'Non') . '">
                        <div class="nPrompt">What did the victim say or do before use of force?</div>
                        <div class="nFld"><input type="text" name="hearOrder'.$i.'Why" class="form-control" value="' 
                        . ((isset($currOrder->OrdTroubleUnderstading)) ? $currOrder->OrdTroubleUnderstading : '') . '"></div>
                    </div>';
                }
                $ret .= '</div>';
            }
            $ret .= '<div class="orderBlock"><center><a href="javascript:;" id="nFormAddOrder" class="btn btn-default"><nobr><i class="fa fa-plus-circle"></i> Add Another Order/Question</nobr></a></center></div>
            <div class="fC"></div>';
            $this->pageAJAX .= "\t\t".'$("#nFormAddOrder").click(function() { var maxOrder = 1;
                for (var i=0; i<20; i++) { if (document.getElementById("orderVis"+i+"ID").value=="Y") maxOrder = 1+i; }
                document.getElementById("nFormAddOrder").href="#ord"+maxOrder+"";
                if (document.getElementById("orderVis"+maxOrder+"ID")) { $("#order"+maxOrder+"").slideDown("slow"); document.getElementById("orderVis"+maxOrder+"ID").value="Y"; }
            });
            $(document).on("click", "a.nFormLnkDel", function() {
                delOrd = $(this).attr("id").replace("delOrder", "");
                $("#order"+delOrd+"").slideUp("slow"); document.getElementById("orderVis"+delOrd+"ID").value="N";
            });' . "\n";
            if ($nID == 342) { // only if Order for Use of Force
            $this->pageAJAX .= "\t\t".'$(document).on("click", ".ordHearCls", function() {
                ordHearInd = $(this).attr("id").replace("hearOrder", "").replace("Y", "").replace("N", "").replace("Q", "");
                ordHearVal = $(this).attr("value");
                if (ordHearVal == "Y") { $("#orderHearing"+ordHearInd+"").slideDown("slow"); }
                else { $("#orderHearing"+ordHearInd+"").slideUp("slow"); }
            });' . "\n";
            }
        } elseif ($promptNotesSpecial == '[[VictimsWithForce]]') { // node 411
            $civInjs = $this->getCivSetPossibilities('Force', 'Injuries', 'Type');
            $GLOBALS["DB"]->loadDefinitions("Injury Types");
            if (sizeof($civInjs["opts"]) > 0) {
                $colWidth = 12/sizeof($civInjs["opts"]);
                $ret .= '<div class="row">
                    <div class="col-md-'.$colWidth.'">';
                    foreach ($civInjs["opts"] as $i => $civID) {
                        if ($i > 0) $ret .= '</div><div class="col-md-'.$colWidth.'">';
                        $ret .= '<div class="nFld pB20"><nobr><label for="injCivs'.$civID.'">
                            <input name="injCivs[]" id="injCivs'.$civID.'" autocomplete="off" type="checkbox" 
                            value="'.$civID.'" ' . ((isset($civInjs["active"][$civID])) ? 'CHECKED' : '') 
                            . ' class="civInjury" > 
                            <span style="font-size: 130%;">' . $this->getCivilianNameFromID($civID) . '</span>
                            </label></nobr></div>
                        <div id="civ'.$civID.'InjTypes" class="dis' 
                        . ((isset($civInjs["active"][$civID])) ? 'Blo' : 'Non') . ' pL20 mL20">';
                        foreach ($GLOBALS["DB"]->defValues["Injury Types"] as $j => $injType) {
                            if ($injType->DefValue != 'Handcuff Injury') {
                                $ret .= '<div class="nFld pB5"><nobr><input name="inj'.$civID.'Type[]" id="inj'
                                .$civID.'Type'.$j.'" autocomplete="off" type="checkbox" value="'.$injType->DefID.'" ' 
                                . ((isset($civInjs["active"][$civID]) 
                                    && in_array($injType->DefID, $civInjs["active"][$civID])) ? 'CHECKED' : '') . ' > 
                                <label for="inj'.$civID.'Type'.$j.'">' . $injType->DefValue . '</label></nobr></div>';
                            }
                        }
                        $ret .= '<div class="p20 m20"></div>
                        </div>';
                    }
                    $ret .= '</div>
                </div>';
                $this->pageAJAX .= "\t\t".'$(document).on("click", ".civInjury", function() {
                    injInd = $(this).attr("id").replace("injCivs", "");
                    if (document.getElementById("injCivs"+injInd+"").checked) { $("#civ"+injInd+"InjTypes").slideDown("slow"); }
                    else { $("#civ"+injInd+"InjTypes").slideUp("slow"); }
                });' . "\n";
            }
        } elseif ($promptNotesSpecial == '[[BodyClicker]]') {
            $parts = ((trim($currNodeSessionData) != '') ? $this->mexplode(';;', substr($currNodeSessionData, 1, strlen($currNodeSessionData)-2)) : []);
            
            $ret .= '<div class="bodyClick">
            <div style="top: 8px; left: 25px;"><nobr><label for="body0">Head</label><input name="n'.$nID.'fld[]" id="body0" value="59" ' . ((in_array(59, $parts)) ? 'CHECKED' : '') . ' type="checkbox" autocomplete="off"></nobr></div>
            <div style="top: 38px; left: 31px;"><nobr><label for="body1">Neck</label><input name="n'.$nID.'fld[]" id="body1" value="60" ' . ((in_array(60, $parts)) ? 'CHECKED' : '') . ' type="checkbox" autocomplete="off"></nobr></div>
            <div style="top: 70px; left: 71px;"><center><label for="body2">Torso</label><br /><input name="n'.$nID.'fld[]" id="body2" value="61" ' . ((in_array(61, $parts)) ? 'CHECKED' : '') . ' type="checkbox" autocomplete="off"></center></div>
            <div style="top: 145px; left: -40px;"><nobr><label for="body3">Hand</label><input name="n'.$nID.'fld[]" id="body3" value="62" ' . ((in_array(62, $parts)) ? 'CHECKED' : '') . ' type="checkbox" autocomplete="off"></nobr></div>
            <div style="top: 96px; left: -14px;"><nobr><label for="body4">Elbow</label><input name="n'.$nID.'fld[]" id="body4" value="63" ' . ((in_array(63, $parts)) ? 'CHECKED' : '') . ' type="checkbox" autocomplete="off"></nobr></div>
            <div style="top: 114px; left: 137px;"><nobr><input name="n'.$nID.'fld[]" id="body5" value="64" ' . ((in_array(64, $parts)) ? 'CHECKED' : '') . ' type="checkbox" autocomplete="off"><label for="body5">Arm</label></nobr></div>
            <div style="top: 274px; left: 19px;"><nobr><label for="body6">Foot</label><input name="n'.$nID.'fld[]" id="body6" value="65" ' . ((in_array(65, $parts)) ? 'CHECKED' : '') . ' type="checkbox" autocomplete="off"></nobr></div>
            <div style="top: 208px; left: 15px;"><nobr><label for="body7">Knee</label><input name="n'.$nID.'fld[]" id="body7" value="66" ' . ((in_array(66, $parts)) ? 'CHECKED' : '') . ' type="checkbox" autocomplete="off"></nobr></div>
            <div style="top: 189px; left: 105px;"><nobr><input name="n'.$nID.'fld[]" id="body8" value="67" ' . ((in_array(67, $parts)) ? 'CHECKED' : '') . ' type="checkbox" autocomplete="off"><label for="body8">Leg</label></nobr></div>
            <div style="top: 135px; left: 67px;"><center><label for="body9">Crotch</label><br /><input name="n'.$nID.'fld[]" id="body9" value="68" ' . ((in_array(68, $parts)) ? 'CHECKED' : '') . ' type="checkbox" autocomplete="off"></center></div>
            <div style="top: 0px; left: 180px;"><nobr><input name="n'.$nID.'fld[]" id="bodyUnknown" value="69" ' . ((in_array(69, $parts)) ? 'CHECKED' : '') . ' type="checkbox" autocomplete="off"><label for="bodyUnknown">Unknown</label></nobr></div>
            </div>';
        } elseif ($promptNotesSpecial == '[[VictimsWithoutArrests]]') {
            $ret .= $this->formCivSetPossibilities('!Arrests', 'Charges');
        }
        return $ret;
    }
    
    protected function customNodePrintButton($nID = -3, $promptNotes = '')
    { 
        if ($nID == 270) return '<!-- no buttons, all done! -->';
        return '';
    }
    
    protected function postNodePublicCustom($nID = -3, $tmpSubTier = [])
    { 
        if (sizeof($tmpSubTier) == 0) $tmpSubTier = $this->loadNodeSubTier($nID);
        list($tbl, $fld) = $this->allNodes[$nID]->getTblFld();
        $this->sessData->dataSets["Complaints"][0]->update(["updated_at" => date("Y-m-d H:i:s")]);
        if ($nID == 28) { // Complainant's Role
            $this->sessData->dataSets["Civilians"][0]->CivRole = trim($this->REQ->input('n28fld'));
            $this->sessData->dataSets["Civilians"][0]->save();
            return true;
        } elseif ($nID == 439) { // Unresolved criminal charges decision
            if ($this->REQ->has('n439fld')) {
                $defID = $GLOBALS["DB"]->getDefID('Unresolved Charges Actions', 'Full complaint to print or save');
                if ($this->REQ->input('n439fld') == $defID) {
                    $defID = $GLOBALS["DB"]->getDefID('Privacy Types', 'Anonymized');
                    if ($this->sessData->dataSets["Complaints"][0]->ComPrivacy == $defID) {
                        $this->sessData->dataSets["Complaints"][0]->update([
                            "ComPrivacy" => $GLOBALS["DB"]->getDefID('Privacy Types', 'Submit Publicly')
                        ]);
                    }
                } else {
                    $defID = $GLOBALS["DB"]->getDefID('Unresolved Charges Actions', 'Anonymous complaint data only');
                    if ($this->REQ->input('n439fld') == $defID) {
                        $this->sessData->dataSets["Complaints"][0]->update([
                            "ComPrivacy" => $GLOBALS["DB"]->getDefID('Privacy Types', 'Anonymized')
                        ]);
                    }
                }
            }
            return false;
        } elseif ($nID == 15) { // Incident Start-End Date & Times
            $this->sessData->currSessData($nID, $tbl, $fld, 'update', date("Y-m-d", strtotime($this->REQ->n15fld)).' '.$this->postFormTimeStr(16));
            return true;
        } elseif ($nID == 16) {
            return true;
        } elseif ($nID == 17) {
            $this->sessData->currSessData($nID, $tbl, $fld, 'update', date("Y-m-d", strtotime($this->REQ->n15fld)).' '.$this->postFormTimeStr($nID));
            return true;
        } elseif ($nID == 47) { // Complainant Recorded Incident?
            $this->sessData->dataSets["Civilians"][0]->CivCameraRecord = $this->REQ->input('n47fld');
            $this->sessData->dataSets["Civilians"][0]->save();
            return true;
        } elseif ($nID == 577) { // Victim Vehicle is a previously entered?
            $previously = $this->getNodeCurrSessData(577);
            if ($previously > 0 && ($previously != $this->REQ->n577fld 
                || ($this->REQ->previouslyAlreadyDescribed == 'Y' && $this->REQ->n576fld == 'N'))) {
                $this->sessData->currSessData(577, 'LinksCivilianVehicles', 'LnkCivVehicVehicID', 'update', 0);
                $this->sessData->refreshDataSets();
                $this->runLoopConditions();
            }
            if ($this->REQ->n576fld == 'N') return true;
        } elseif ($nID == 578) { // Victim Vehicle is a previously entered?
            if ($this->REQ->n576fld == 'Y') return true;
        } elseif ($nID == 572) { // Witness Vehicle is a previously entered?
            $previously = $this->getNodeCurrSessData(572);
            if ($previously > 0 && ($previously != $this->REQ->n572fld 
                || ($this->REQ->previouslyAlreadyDescribed == 'Y' && $this->REQ->n571fld == 'N'))) {
                $this->sessData->currSessData(572, 'LinksCivilianVehicles', 'LnkCivVehicVehicID', 'update', 0);
                $this->sessData->refreshDataSets();
                $this->runLoopConditions();
            }
            if ($this->REQ->n571fld == 'N') return true;
        } elseif ($nID == 583) { // Witness Vehicle is a previously entered?
            if ($this->REQ->n571fld == 'Y') return true;
        } elseif ($nID == 585) { // Officer Vehicle is a previously entered?
            $previously = $this->getNodeCurrSessData(585);
            if ($previously > 0 && ($previously != $this->REQ->n585fld 
                || ($this->REQ->previouslyAlreadyDescribed == 'Y' && $this->REQ->n584fld == 'N'))) {
                $this->sessData->currSessData(585, 'LinksCivilianVehicles', 'LnkCivVehicVehicID', 'update', 0);
                $this->sessData->refreshDataSets();
                $this->runLoopConditions();
            }
            if ($this->REQ->n584fld == 'N') return true;
        } elseif ($nID == 589) { // Officer Vehicle is a previously entered?
            if ($this->REQ->n584fld == 'Y') return true;
        } elseif ($nID == 145) { // Searched & Found Police Department
            $newDeptID = -3;
            if (intVal($this->REQ->n145fld) > 0) {
                $newDeptID = intVal($this->REQ->n145fld);
                $this->sessData->logDataSave($nID, 'NEW', -3, 'ComplaintDeptLinks', $this->REQ->n145fld);
            } elseif ($this->REQ->has('newDeptName') && trim($this->REQ->newDeptName) != '') {
                $tmpVolunCtrl = new VolunteerController;
                $newDept = $tmpVolunCtrl->newDeptAdd($this->REQ->newDeptName, $this->REQ->newDeptAddressState);
                $newDeptID = $newDept->DeptID;
                $logTxt = 'ComplaintDeptLinks - !New Department Added!';
                $this->sessData->logDataSave($nID, 'NEW', -3, $logTxt, $newDeptID);
            }
            if ($newDeptID > 0) {
                $deptChk = OPLinksComplaintDept::where('LnkComDeptComplaintID', $this->coreID)
                    ->where('LnkComDeptDeptID', $newDeptID)
                    ->get();
                if (!$deptChk || sizeof($deptChk) <= 0) {
                    $newDeptLnk = new OPLinksComplaintDept;
                    $newDeptLnk->LnkComDeptComplaintID = $this->coreID;
                    $newDeptLnk->LnkComDeptDeptID = $newDeptID;
                    $newDeptLnk->save();
                    $this->sessData->refreshDataSets();
                    $this->runLoopConditions();
                }
            }
            return true;
        } elseif ($nID == 671) { // Officers Used Profanity?
            foreach ($this->sessData->dataSets["Officers"] as $i => $off) {
                if (isset($this->REQ->n671fld) && in_array($off->getKey(), $this->REQ->n671fld)) {
                    $this->sessData->dataSets["Officers"][$i]->OffUsedProfanity = 'Y';
                }
                else $this->sessData->dataSets["Officers"][$i]->OffUsedProfanity = '';
                $this->sessData->dataSets["Officers"][$i]->save();
            }
        } elseif ($nID == 674) { // Officer Used Profanity?
            if (isset($this->REQ->n674fld)) {
                $this->sessData->dataSets["Officers"][0]->OffUsedProfanity = trim($this->REQ->n674fld);
            }
            else $this->sessData->dataSets["Officers"][0]->OffUsedProfanity = '';
            $this->sessData->dataSets["Officers"][0]->save();
        } elseif ($nID == 670) { // Victims Used Profanity?
            foreach ($this->sessData->dataSets["Civilians"] as $i => $civ) {
                if (isset($this->REQ->n670fld) && in_array($civ->getKey(), $this->REQ->n670fld)) {
                    $this->sessData->dataSets["Civilians"][$i]->CivUsedProfanity = 'Y';
                }
                else $this->sessData->dataSets["Civilians"][$i]->CivUsedProfanity = '';
                $this->sessData->dataSets["Civilians"][$i]->save();
            }
        } elseif ($nID == 676) { // Victim Used Profanity?
            $civInd = $this->getFirstVictimCivInd();
            if ($civInd >= 0) {
                if (isset($this->REQ->n676fld)) {
                    $this->sessData->dataSets["Civilians"][$civInd]->CivUsedProfanity = trim($this->REQ->n676fld);
                }
                else $this->sessData->dataSets["Civilians"][$civInd]->CivUsedProfanity = '';
                $this->sessData->dataSets["Civilians"][$civInd]->save();
            }
        } elseif ($nID == 146) { // Going Gold?
            // probably some transitions required?
            if ($this->REQ->has('n146fld') && $this->REQ->n146fld == 'Gold' 
                && sizeof($this->sessData->loopItemIDs["Victims"]) == 1) {
                $this->checkHasEventSeq($nID);
                if (sizeof($this->eventCivLookup["Stops"]) == 0 
                    && isset($this->sessData->dataSets["AllegSilver"][0]->AlleSilStopYN)
                    && in_array($this->sessData->dataSets["AllegSilver"][0]->AlleSilStopYN, ['Y', '?'])) {
                    $this->addNewEveSeq('Stops', $this->sessData->loopItemIDs["Victims"][0]);
                }
                if (sizeof($this->eventCivLookup["Searches"]) == 0 
                    && ( (isset($this->sessData->dataSets["AllegSilver"][0]->AlleSilSearchYN)
                    && in_array($this->sessData->dataSets["AllegSilver"][0]->AlleSilSearchYN, ['Y', '?']))
                    || (isset($this->sessData->dataSets["AllegSilver"][0]->AlleSilPropertyYN)
                    && in_array($this->sessData->dataSets["AllegSilver"][0]->AlleSilPropertyYN, ['Y', '?'])) )) {
                    $this->addNewEveSeq('Searches', $this->sessData->loopItemIDs["Victims"][0]);
                }
                if (sizeof($this->eventCivLookup["Force"]) == 0 
                    && isset($this->sessData->dataSets["AllegSilver"][0]->AlleSilForceYN)
                    && in_array($this->sessData->dataSets["AllegSilver"][0]->AlleSilForceYN, ['Y', '?'])) {
                    $this->addNewEveSeq('Force', $this->sessData->loopItemIDs["Victims"][0]);
                }
                if (sizeof($this->eventCivLookup["Arrests"]) == 0 
                    && isset($this->sessData->dataSets["AllegSilver"][0]->AlleSilArrestYN)
                    && in_array($this->sessData->dataSets["AllegSilver"][0]->AlleSilArrestYN, ['Y', '?'])) {
                    $this->addNewEveSeq('Arrests', $this->sessData->loopItemIDs["Victims"][0]);
                }
            }
            return false;
        } elseif ($nID == 285) { // establishing Events' instances
            $this->initializeGoldEvents($nID);
            return true;
        } elseif ($nID == 288) { // order Events
            $list = '';
            if (sizeof($this->sessData->dataSets["EventSequence"]) > 0) {
                foreach ($this->sessData->dataSets["EventSequence"] as $eveSeq) {
                    $list .= ','.$eveSeq->EveID;
                }
            }
            $this->sessData->logDataSave($nID, 'EVENTS', -3, 'Ordering Event Instances', $list);
            return true;
        } elseif (in_array($nID, array(296, 295, 294, 293))) { // adding Officers to Events
            $eventType = $this->getEveSeqTypeFromNode($nID);
            $this->deleteEventPeopleLinks($nID, $GLOBALS["DB"]->closestLoop["itemID"], 'Officer');
            $this->connectPeopleEventLinks("n".$nID."fld", '', -3, $GLOBALS["DB"]->closestLoop["itemID"]);
            if ($this->REQ->has('n'.$nID.'fld')) {
                $this->sessData->logDataSave($nID, 'GOLD '.$eventType, -3, 'Adding Officers', 
                    implode('-|-', $this->REQ->input('n'.$nID.'fld')));
            }
            return true;
        } elseif ($this->allNodes[$nID]->nodeRow->NodePromptNotes == '[[MergeVictimsEventSequence]]') {
            return $this->processEventMerge($nID);
        } elseif ($this->allNodes[$nID]->nodeRow->NodePromptNotes == '[[OfficerOrders]]') {
            return $this->processOrders($nID);
        } elseif ($nID == 316) {
            return $this->processHandcuffInjury($nID);
        } elseif ($nID == 411) { // Gold Victims with Force, checkin for Injuries, from [[VictimsWithForce]]
            $logs = '';
            $civInjs = $this->getCivSetPossibilities('Force', 'Injuries', 'Type');
            if (sizeof($civInjs["active"]) > 0) { 
                foreach ($civInjs["active"] as $civID => $injTypes) {
                    if (sizeof($injTypes) > 0) { 
                        foreach ($injTypes as $injType) {
                            if (!$this->REQ->has('injCivs') || !in_array($civID, $this->REQ->injCivs) 
                                || !$this->REQ->has('inj'.$civID.'Type')) {
                                OPInjuries::where('InjSubjectID', $civID)
                                    ->where('InjType', $injType)
                                    ->where('InjComplaintID', $this->coreID)
                                    ->delete();
                            }
                        }
                    }
                }
            }
            if ($this->REQ->has('injCivs') && sizeof($this->REQ->injCivs) > 0) {
                foreach ($this->REQ->injCivs as $civID) {
                    $logs .= '-|-civ:'.$civID.'::';
                    if ($this->REQ->has('inj'.$civID.'Type') && sizeof($this->REQ->input('inj'.$civID.'Type')) > 0) {
                        foreach ($this->REQ->input('inj'.$civID.'Type') as $injType) {
                            $logs .= $injType.';;';
                            $found = false;
                            if (isset($civInjs["active"][$civID])) {
                                foreach ($civInjs["active"][$civID] as $injChk) {
                                    if ($injChk == $injType) $found = true;
                                }
                            }
                            if (!$found) {
                                $injRow = $this->sessData->newDataRecordSimple('Injuries', 'InjSubjectID', $civID);
                                $injRow->InjType = $injType;
                                $injRow->save();
                            }
                        }
                    }
                }
            }
            $this->sessData->logDataSave($nID, 'Injuries', -3, 'New Records', str_replace(';;-|-', '-|-', $logs));
        } elseif ($nID == 415) { // Gold Victims with Injuries, checkin for Care, from [[VictimsWithInjuries]]
            $this->postCivSetPossibilities('Injuries', 'InjuryCare', 'InjCareSubjectID');
            $this->sessData->logDataSave($nID, 'InjuryCare', -3, 'New Records', 
                (($this->REQ->has('InjuryCare')) ? implode(';;', $this->REQ->InjuryCare) : ''));
        } elseif ($nID == 391) { // Gold Victims without Arrests, but with Citations, from [[VictimsWithoutArrests]]
            $this->postCivSetPossibilities('Arrests', 'Charges');
            $this->sessData->logDataSave($nID, 'Charges', -3, 'New Records', 
                (($this->REQ->has('Charges')) ? implode(';;', $this->REQ->Charges) : ''));
        } elseif ($nID == 274) { // CONFIRM COMPLAINT SUBMISSION
            $slug = $this->sessData->dataSets["Incidents"][0]->IncAddressCity . '-' 
                . $this->sessData->dataSets["Incidents"][0]->IncAddressState;
            if ($this->REQ->has('n274fld') && trim($this->REQ->n274fld) != '') $slug = $this->REQ->n274fld;
            $slug = '/' . $this->sessData->dataSets["Complaints"][0]->ComID . '/' . Str::slug($slug);
            $this->sessData->dataSets["Complaints"][0]->update(["ComSlug" => $slug]);
        } elseif ($nID == 269) { // CONFIRM COMPLAINT SUBMISSION
            if ($this->REQ->has('n269fld')) {
                if ($this->REQ->n269fld == 'Y') {
                    $this->sessData->currSessData($nID, 'Complaints', 'ComStatus', 'update', 196); // 'New'
                    $this->sessData->currSessData($nID, 'Complaints', 'ComRecordSubmitted', 'update', 
                        date("Y-m-d H:i:s"));
                }
            }
        }
        return false; // false to continue standard post processing
    }
    
    // returns an array of overrides for ($currNodeSessionData, ???... 
    protected function printNodeSessDataOverride($nID = -3, $tmpSubTier = [], $currNodeSessionData = '')
    {
        if ($nID == 28) { // Complainant's Role
            return [trim($this->sessData->dataSets["Civilians"][0]->CivRole)];
        } elseif ($nID == 47) { // Complainant Recorded Incident?
            return [trim($this->sessData->dataSets["Civilians"][0]->CivCameraRecord)];
        } elseif ($nID == 19) { // Would you like to provide the GPS location?
            if (intVal($this->sessData->dataSets["Incidents"][0]->IncAddressLat) != 0 
                || intVal($this->sessData->dataSets["Incidents"][0]->IncAddressLng) != 0) {
                return ['Yes'];
            } else {
                return [];
            }
        } elseif ($nID == 39) {
            if ($currNodeSessionData == '') {
                $user = Auth::user();
                return [$user->email];
            }
        } elseif ($nID == 671) { // Officers Used Profanity?
            $currVals = [];
            foreach ($this->sessData->dataSets["Officers"] as $i => $off) {
                if ($off->OffUsedProfanity == 'Y') $currVals[] = $off->getKey();
            }
            return $currVals;
        } elseif ($nID == 674) { // Officer Used Profanity?
            return trim($this->sessData->dataSets["Officers"][0]->OffUsedProfanity);
        } elseif ($nID == 670) { // Victims Used Profanity?
            $currVals = [];
            foreach ($this->sessData->dataSets["Civilians"] as $i => $civ) {
                if ($civ->CivUsedProfanity == 'Y') $currVals[] = $civ->getKey();
            }
            return $currVals;
        } elseif ($nID == 676) { // Victim Used Profanity?
            $civInd = $this->getFirstVictimCivInd();
            if ($civInd >= 0) {
                return trim($this->sessData->dataSets["Civilians"][$civInd]->CivUsedProfanity);
            }
        } elseif ($nID == 269) {
            return array((($this->sessData->dataSets["Complaints"][0]->ComStatus == 'New') ? 'Y' : ''));
        }
        return [];
    }
    
    protected function customLabels($nID = -3, $nodePromptText = '')
    {
        $event = [];
        if ($GLOBALS["DB"]->closestLoop["loop"] == 'Events') {
            $event = $this->getEventSequence($GLOBALS["DB"]->closestLoop["itemID"]);
        }
        if (strpos($nodePromptText, '[SetCivLabel]') !== false) {
            $civName = $this->isEventAnimalForce($event[0]["EveID"], $event[0]["Event"]);
            if (trim($civName) == '' && isset($event[0]["Civilians"])) {
                $civName = $this->getCivilianNameFromID($event[0]["Civilians"][0]);
            }
            $nodePromptText = str_replace('[SetCivLabel]', 
                '<span class="slBlueDark"><b>' . $civName . '</b></span>', $nodePromptText);
        }
        if (strpos($nodePromptText, '[ForceType]') !== false) {
            $forceDesc = $GLOBALS["DB"]->getDefValue('Force Type', $event[0]["Event"]->ForType);
            if ($forceDesc == 'Other') $forceDesc = $event[0]["Event"]->ForTypeOther;
            $nodePromptText = str_replace('[ForceType]', '<span class="slBlueDark"><b>'
                . $forceDesc . '</b></span>', $nodePromptText);
        }
        if (strpos($nodePromptText, '[InjuryType]') !== false) {
            $inj = $this->sessData->getRowById('Injuries', $GLOBALS["DB"]->closestLoop["itemID"]);
            if ($inj && sizeof($inj) > 0) {
                $nodePromptText = str_replace('[InjuryType]', '<span class="slBlueDark"><b>'
                    . $GLOBALS["DB"]->getDefValue('Injury Types', $inj->InjType) . '</b></span>', $nodePromptText);
                $nodePromptText = $this->cleanLabel(str_replace('[LoopItemLabel]', '<span class="slBlueDark"><b>'
                    . $this->getCivilianNameFromID($inj->InjSubjectID) . '</b></span>', $nodePromptText));
            }
        }
        if (strpos($nodePromptText, '[[ListCitationCharges]]') !== false) {
            $stop = $this->sessData->getRowById('Stops', $GLOBALS["DB"]->closestLoop["itemID"]);
            $chargesType = 'Citation Charges Pedestrian';
            $defID = $GLOBALS["DB"]->getDefID('Scene Type', 'Vehicle Stop');
            if ($this->sessData->dataSets["Scenes"][0]->ScnType == $defID) $chargesType = 'Citation Charges';
            $list = '';
            if (isset($this->sessData->dataSets["Charges"]) && sizeof($this->sessData->dataSets["Charges"]) > 0) {
                foreach ($this->sessData->dataSets["Charges"] as $chrg) {
                    if ($chrg->ChrgStopID == $stop->StopID) {
                        $list .= ', ' . $GLOBALS["DB"]->getDefValue($chargesType, $chrg->ChrgCharges);
                    }
                }
            }
            if (trim($stop->StopChargesOther) != '') $list .= ', ' . $stop->StopChargesOther;
            if (substr($list, 0, 2) == ', ') $list = trim(substr($list, 1));
            $nodePromptText = str_replace('[[ListCitationCharges]]', $list, $nodePromptText);
        }
        if (strpos($nodePromptText, '[[List of Allegations]]') !== false) {
            $nodePromptText = str_replace('[[List of Allegations]]', 
                $this->basicAllegationList(true), $nodePromptText);
        }
        if (strpos($nodePromptText, '[[List of Events and Allegations]]') !== false) {
            $nodePromptText = str_replace('[[List of Events and Allegations]]', 
                $this->basicAllegationList(true), $nodePromptText);
        }
        return $nodePromptText;
    }
    
    protected function jumpToNodeCustom($nID)
    { 
        $newID = -3;
        // maybe needed?
        return $newID; 
    }
    
    protected function nodePrintJumpToCustom($nID = -3)
    {
        //if ($nID == 137 && intVal(session()->get('privacyJumpBackTo')) > 0) return session()->get('privacyJumpBackTo');
        return -3; 
    }
    
    protected function getLoopItemLabelCustom($loop, $itemRow = [], $itemInd = -3)
    {
        //if ($itemIndex < 0) return '';
        if (in_array($loop, ['Victims', 'Witnesses'])) {
            return $this->getCivName($loop, $itemRow, $itemInd);
        } elseif ($loop == 'Civilians') {
            return $this->getCivilianNameFromID($itemRow->getKey());
        } elseif ($loop == 'Officers') {
            return $this->getOfficerName($itemRow, $itemInd);
        } elseif ($loop == 'Departments') {
            return $this->getDeptName($itemRow, $itemInd);
        } elseif ($loop == 'Events') {
            $EveSeq = $this->getEventSequence($itemRow->EveID);
            return $this->printEventSequenceLine($EveSeq[0], 'Civilians');
        } elseif ($loop == 'Injuries') {
            return $this->getCivilianNameFromID($itemRow->InjSubjectID) . ': ' 
                .  $GLOBALS["DB"]->getDefValue('Injury Types', $itemRow->InjType);
        } elseif ($loop == 'Medical Care') {
            return $this->getCivilianNameFromID($itemRow->InjCareSubjectID);
        } elseif ($loop == 'Citations') { // why isn't this working?!
            if (isset($itemRow->StopEventSequenceID) && intVal($itemRow->StopEventSequenceID) > 0) {
                $eveID = $itemRow->StopEventSequenceID;
                $EveSeq = $this->getEventSequence($eveID);
                if (sizeof($EveSeq[0]["Civilians"]) == 1) {
                    return $this->getCivilianNameFromID($EveSeq[0]["Civilians"][0]);
                }
                $civList = '';
                foreach ($EveSeq[0]["Civilians"] as $civID) $civList .= ', ' . $this->getCivilianNameFromID($civID);
                return substr($civList, 1);
            }
        }
        return '';
    }
    
    protected function printSetLoopNavRowCustom($nID, $loopItem, $setIndex) 
    {
        if ($nID == 143 && $loopItem && sizeof($loopItem) > 0) { // $tbl == 'Departments'
            return view('vendor.openpolice.nodes.143-dept-loop-custom-row', [
                "loopItem" => $this->sessData->getChildRow('LinksComplaintDept', $loopItem->getKey(), 'Departments'), 
                "setIndex" => $setIndex, 
                "itemID"   => $loopItem->getKey()
            ])->render();
        }
        return '';
    }
    
/*****************************************************************************
// END Processes Which Override Default Behaviors of SetNav LOOPS
*****************************************************************************/
    
    












    
    
/*****************************************************************************
// START Processes Which Handle Allegations
*****************************************************************************/
    
    
    public function simpleAllegationList()
    {
        return $this->commaAllegationList((isset($this->sessData->dataSets["Allegations"])) ? $this->sessData->dataSets["Allegations"] : []);
    }
    
    public function commaAllegationList($allegsTmp)
    {
        $ret = '';
        if (sizeof($allegsTmp) > 0) {
            $skipAllegs = [];
            if ($this->isGold()) {
                foreach ($allegsTmp as $i => $alleg1) {
                    if (!isset($alleg->AlleEventSequenceID) || intVal($alleg->AlleEventSequenceID) <= 0) {
                        foreach ($allegsTmp as $j => $alleg2) {
                            if ($i != $j && $alleg1->AlleType == $alleg2->AlleType 
                                && intVal($alleg2->AlleEventSequenceID) > 0) {
                                if (!in_array($alleg1->AlleID, $skipAllegs)) $skipAllegs[] = $alleg1->AlleID;
                            }
                        }
                    }
                }
            }
            foreach ($this->worstAllegations as $allegType) {
                foreach ($allegsTmp as $alleg) {
                    if (!in_array($alleg->AlleID, $skipAllegs)) {
                        if ($alleg->AlleType == $GLOBALS["DB"]->getDefID('Allegation Type', $allegType)) {
                            if ($this->checkAllegIntimidWeapon($alleg)) {
                                $ret .= ', ' . $allegType;
                            }
                        }
                    }
                }
            }
            $ret = trim(substr($ret, 1));
        }
        return $ret;
    }
    
    protected function checkAllegIntimidWeapon($alleg)
    {
        $defA = $GLOBALS["DB"]->getDefID('Allegation Type', 'Intimidating Display Of Weapon');
        $defB = $GLOBALS["DB"]->getDefID('Intimidating Displays Of Weapon', 'N/A');
        $defC = $GLOBALS["DB"]->getDefID('Intimidating Displays Of Weapon', 'Don\'t Know');
        return ($alleg->AlleType != $defA || !in_array($alleg->AlleIntimidatingWeapon, [$defB, $defC]));
    }
    
    protected function basicAllegationList($showWhy = false, $isAnon = false)
    {
        $ret = '';
        if (isset($this->sessData->dataSets["Allegations"]) && sizeof($this->sessData->dataSets["Allegations"]) > 0) {
            $printedOfficers = false;
            $allegOffs = [];
            // if there's only one Officer on the Complaint, then it is associated with all Allegations
            if (!$isAnon && isset($this->sessData->dataSets["Officers"]) 
                && sizeof($this->sessData->dataSets["Officers"]) == 1) {
                /*
                $ret .= '<div class="pL5 pB10 f16">Officer '
                    . $this->getOfficerNameFromID($this->sessData->dataSets["Officers"][0]->OffID) . '</div>';
                */
                $printedOfficers = true;
            } else { // Load Officer names for each Allegation
                foreach ($this->sessData->dataSets["Allegations"] as $alleg) {
                    if ($this->checkAllegIntimidWeapon($alleg)) {
                        $allegOffs[$alleg->AlleID] = '';
                        $offs = $this->getLinkedToEvent('Officer', $alleg->AlleID);
                        if (sizeof($offs) > 0) {
                            foreach ($offs as $off) {
                                $allegOffs[$alleg->AlleID] .= ', '.$this->getOfficerNameFromID($off);
                            }
                        }
                        if (trim($allegOffs[$alleg->AlleID]) != '') {
                            $allegOffs[$alleg->AlleID] = substr($allegOffs[$alleg->AlleID], 1); 
                            // 'Officer'.((sizeof($offs) > 1) ? 's' : '').
                        }
                    }
                }
                // now let's check if all allegations are against the same officers, so we only print them once
                $allOfficersSame = true; $prevAllegOff = '*START*';
                foreach ($allegOffs as $allegOff) {
                    if ($prevAllegOff == '*START*') {
                    
                    } elseif ($prevAllegOff != $allegOff) {
                        $allOfficersSame = false;
                    }
                    $prevAllegOff = $allegOff;
                }
                if (!$isAnon && $allOfficersSame) { // all the same, so print once at the top
                    $ret .= '<div class="pL5 pB10 f18">' 
                        . $allegOffs[$this->sessData->dataSets["Allegations"][0]->AlleID] . '</div>';
                    $printedOfficers = true;
                }
            }
            foreach ($this->worstAllegations as $allegType) { // printing Allegations in order of severity...
                foreach ($this->sessData->dataSets["Allegations"] as $alleg) {
                    if ($alleg->AlleType == $GLOBALS["DB"]->getDefID('Allegation Type', $allegType)) {
                        if ($this->checkAllegIntimidWeapon($alleg)) {
                            $ret .= '<div class="f18">' . $allegType;
                            if (!$isAnon && !$printedOfficers && isset($allegOffs[$alleg->AlleID])) {
                                $ret .= ' <span class="f16 mL20 gry6">' . $allegOffs[$alleg->AlleID] . '</span>';
                            }
                            $ret .= '</div>' 
                            . (($showWhy) ? '<div class="gry9 f14 mTn10 pL20">'.$alleg->AlleDescription.'</div>' : '')
                            . '<div class="p5"></div>';
                        }
                    }
                }
            }
        } else {
            $ret = '<i>No allegations found.</i>';
        }
        return $ret;
    }
    
/*****************************************************************************
// END Processes Which Handle Allegations
*****************************************************************************/



    
/*****************************************************************************
// START Processes Which Handle The Event Sequence
*****************************************************************************/

    // get Incident Event Type from Node location in the Gold process
    protected function getEveSeqTypeFromNode($nID)
    {
        $eveSeqLoop = array('Stops' => 149, 'Searches' => 150, 'Force' => 151, 'Arrests' => 152);
        foreach ($eveSeqLoop as $eventType => $nodeRoot) {
            if ($this->allNodes[$nID]->checkBranch($this->allNodes[$nodeRoot]->nodeTierPath)) {
                return $eventType;
            }
        }
        return '';
    }
    
    protected function getEveSeqOrd($eveSeqID)
    {
        if (isset($this->sessData->dataSets["EventSequence"]) 
            && sizeof($this->sessData->dataSets["EventSequence"]) > 0) { 
            foreach ($this->sessData->dataSets["EventSequence"] as $i => $eveSeq) {
                if ($eveSeq->EveID == $eveSeqID) return $eveSeq->EveOrder;
            }
        }
        return 0;
    }
    
    protected function getLastEveSeqOrd()
    {
        $newOrd = 0;
        if (isset($this->sessData->dataSets["EventSequence"]) 
            && sizeof($this->sessData->dataSets["EventSequence"]) > 0) {
            $ind = sizeof($this->sessData->dataSets["EventSequence"])-1;
            $newOrd = $this->sessData->dataSets["EventSequence"][$ind]->EveOrder;
        }
        return $newOrd;
    }
        
    protected function getPreviousEveSeqsOfType($eveSeqID)
    {
        $eventType = $this->getEveSeqRowType($eveSeqID);
        $eventRow = $this->sessData->getChildRow('EventSequence', $eveSeqID, $eventType);
        if ($this->isEventAnimalForce($eveSeqID, $eventRow)) return [];
        $prevEvents = [];
        $eveSeqInd = $this->sessData->getLoopIndFromID('Events', $eveSeqID);
        for ($i=($eveSeqInd-1); $i>=0; $i--) {
            if ($eventType == 'Force') {
                $forceEvent = $this->sessData->getChildRow('EventSequence', 
                    $this->sessData->dataSets["EventSequence"][$i]->EveID, 'Force');
                if ($this->getEveSeqRowType($this->sessData->dataSets["EventSequence"][$i]->EveID) == 'Force' 
                    && $eventRow->ForType == $forceEvent->ForType 
                    && $eventRow->ForTypeOther == $forceEvent->ForTypeOther) {
                    $prevEvents[] = $this->sessData->dataSets["EventSequence"][$i]->EveID;
                }
            } elseif ($eventType == $this->getEveSeqRowType($this->sessData->dataSets["EventSequence"][$i]->EveID)) {
                $prevEvents[] = $this->sessData->dataSets["EventSequence"][$i]->EveID;
            }
        }
        return $prevEvents;
    }
    
    protected function checkHasEventSeq($nID)
    {
        //if (sizeof($this->eventCivLookup) > 0) return $this->eventCivLookup;
        $this->eventCivLookup = [
            'Stops'        => [], 
            'Searches'     => [], 
            'Force'        => [], 
            'Force Animal' => [], 
            'Arrests'      => [], 
            'Citations'    => [], 
            'Warnings'     => [], 
            'No Punish'    => []
        ];
        foreach ($this->sessData->loopItemIDs["Victims"] as $i => $civ) {
            $arrestEveSeqID = $this->getCivEveSeqIdByType($civ, 'Arrests');
            $stopEveSeqID     = $this->getCivEveSeqIdByType($civ, 'Stops');
            
            if ($stopEveSeqID > 0) {
                $this->eventCivLookup["Stops"][] = $civ;
                $stop = $this->sessData->getChildRow('EventSequence', $stopEveSeqID, 'Stops');
                if ($stop->StopGivenCitation == 'Y') $this->eventCivLookup["Citations"][] = $civ;
                if ($stop->StopGivenWarning == 'Y') $this->eventCivLookup["Warnings"][] = $civ;
                if ($stop->StopGivenCitation == 'N' && $stop->StopGivenWarning == 'N' && $arrestEveSeqID <= 0) {
                    $this->eventCivLookup["No Punish"][] = $civ;
                }
            }
            if ($this->getCivEveSeqIdByType($civ, 'Searches') > 0) {
                $this->eventCivLookup["Searches"][] = $civ;
            }
            if ($this->getCivEveSeqIdByType($civ, 'Force') > 0) {
                $this->eventCivLookup["Force"][] = $civ;
            }
            if ($arrestEveSeqID > 0) {
                $this->eventCivLookup["Arrests"][] = $civ;
            }
        }
        if (isset($this->sessData->dataSets["Force"]) && sizeof($this->sessData->dataSets["Force"]) > 0) {
            foreach ($this->sessData->dataSets["Force"] as $forceRow) {
                if ($forceRow->ForAgainstAnimal == 'Y') {
                    $this->eventCivLookup["Force Animal"][] = $forceRow->ForID;
                }
            }
        }
        return true;
    }
    
    protected function initializeGoldEvents($nID)
    {   // Node $nID == 285
        $arrests = array('Arrests' => [], 'Citations' => [], 'Warnings' => [], 'None' => []);
        $this->checkHasEventSeq($nID);
        $GLOBALS["DB"]->loadDefinitions('Force Type');
        $victims = $this->sessData->loopItemIDs["Victims"];
        $victims[] = 0;
        foreach ($victims as $i => $civ) {
            if ($this->REQ->has('n285civ'.$i.'') && trim($this->REQ->input('n285civ'.$i.'')) != '') {
                $arrests[$this->REQ->input('n285civ'.$i.'')][] = $civ;
            }
            foreach ($this->eventTypes as $eventType) {
                if ($eventType == 'Force') {
                    
                    if ($this->REQ->has('n285fldForce') && in_array($civ, $this->REQ->get('n285fldForce'))) {
                        foreach ($GLOBALS["DB"]->defValues["Force Type"] as $j => $fType) {
                            $civTypeOther = '';
                            $civTypeExists = $civTypeChecked = false;
                            if ($this->REQ->has('n'.$civ.'fld') 
                                && in_array($fType->DefID, $this->REQ->get('n'.$civ.'fld'))) {
                                $civTypeChecked = true;
                                if ($fType->DefVal == 'Other' && $this->REQ->has('n-'.$civ.'fld')) {
                                    $civTypeOther = trim($this->REQ->get('n-'.$civ.'fld'));
                                }
                            }
                            if (isset($this->sessData->dataSets["Force"]) 
                                && sizeof($this->sessData->dataSets["Force"]) > 0) {
                                foreach ($this->sessData->dataSets["Force"] as $k => $force) {
                                    $currCivs = $this->getLinkedToEvent('Civilian', $force->ForEventSequenceID);
                                    if (sizeof($currCivs) > 0) {
                                        foreach ($currCivs as $l => $c) {
                                            if ($c == $civ && $force->ForType == $fType->DefID) {
                                                $civTypeExists = true;
                                                if (!$civTypeChecked) {
                                                    $this->deleteEventByID($nID, $force->ForEventSequenceID);
                                                } elseif ($fType->DefVal == 'Other') {
                                                    $force->ForTypeOther = $civTypeOther;
                                                    $force->save();
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            if ($civTypeChecked && !$civTypeExists) {
                                $newForce = $this->addNewEveSeq('Force', $civ);
                                $newForce->ForType = $fType->DefID;
                                $newForce->ForTypeOther = $civTypeOther;
                                $newForce->ForAgainstAnimal = 'N';
                                if ($civ == 0) {
                                    $newForce->ForAgainstAnimal = 'Y';
                                    $newForce->ForAnimalDesc = trim($this->REQ->ForceAnimalDesc);
                                }
                                $newForce->save();
                            }
                        }
                        
                    } else { // civilian not checked for force
                        if (isset($this->sessData->dataSets["Force"]) 
                            && sizeof($this->sessData->dataSets["Force"]) > 0) {
                            foreach ($this->sessData->dataSets["Force"] as $k => $force) {
                                if ($civ > 0) {
                                    if (in_array($civ, 
                                        $this->getLinkedToEvent('Civilian', $force->ForEventSequenceID))) {
                                        $this->deleteEventByID($nID, $force->ForEventSequenceID);
                                    }
                                } elseif ($force->ForAgainstAnimal == 'Y') {
                                    $this->deleteEventByID($nID, $force->ForEventSequenceID);
                                }
                            }
                        }
                    }
                    
                } elseif ($civ > 0) { // and not Force
                    
                    // *forcing Stops row creation if Citation or Written Warning
                    if (($this->REQ->has('n285fld'.$eventType) 
                        && in_array($civ, $this->REQ->input('n285fld'.$eventType)))
                        || ($eventType == 'Stops' && (in_array($civ, $arrests["Citations"]) 
                        || in_array($civ, $arrests["Warnings"])))
                        || ($eventType == 'Arrests' && in_array($civ, $arrests["Arrests"]))) { // then we've got one
                        $newEvent = [];
                        if (!in_array($civ, $this->eventCivLookup[$eventType])) {
                            $newEvent = $this->addNewEveSeq($eventType, $civ);
                        }
                        if ($eventType == 'Stops') {
                            if (!$newEvent || sizeof($newEvent) == 0) { 
                                // not a new Stop, so let's load the old record
                                $eveSeqID = $this->getCivEveSeqIdByType($civ, 'Stops');
                                $newEvent = $this->sessData->getChildRow('EventSequence', $eveSeqID, 'Stops'); 
                            }
                            $newEvent->StopGivenCitation = ((in_array($civ, $arrests["Citations"])) ? 'Y' : 'N');
                            $newEvent->StopGivenWarning = ((in_array($civ, $arrests["Warnings"])) ? 'Y' : 'N');
                            $newEvent->save();
                        }
                    } elseif (in_array($civ, $this->eventCivLookup[$eventType])) {
                        $eveSeqID = $this->getCivEveSeqIdByType($civ, $eventType);
                        $event = $this->sessData->getChildRow('EventSequence', $eveSeqID, $eventType); 
                        $this->sessData->deleteDataItem($nID, 'EventSequence', $eveSeqID);
                        $this->sessData->deleteDataItem($nID, $eventType, $event->getKey());
                    }
                    
                }
            }
        }
        $log = '-|-Stops:'     . (($this->REQ->has('n285fldStops')) ? implode(',', $this->REQ->n285fldStops) : '')
            . '-|-Searches:'     . (($this->REQ->has('n285fldSearches')) ? implode(',', $this->REQ->n285fldSearches) : '')
            . '-|-Force:'     . (($this->REQ->has('n285fldForce')) ? implode(',', $this->REQ->n285fldForce) : '')
            . '-|-Animal:'     . (($this->REQ->has('n285fldForceAnimal')) ? 'Y::'.$this->REQ->ForceAnimalDesc : 'N')
            . '-|-Arrests:'     . ((sizeof($arrests["Arrests"]) > 0) ? implode(',', $arrests["Arrests"]) : '')
            . '-|-Citations:' . ((sizeof($arrests["Citations"]) > 0) ? implode(',', $arrests["Citations"]) : '')
            . '-|-Warnings:'     . ((sizeof($arrests["Warnings"]) > 0) ? implode(',', $arrests["Warnings"]) : '')
            . '-|-Nones:'     . ((sizeof($arrests["None"]) > 0) ? implode(',', $arrests["None"]) : '');
        $this->sessData->logDataSave($nID, 'EVENTS', -3, 'Adding Event Instances', $log);
        $this->sessData->refreshDataSets();
        $this->runLoopConditions();
        return true;
    }
    
    protected function addNewEveSeq($eventType, $civID = -3)
    {
        $newEveSeq = new OPEventSequence;
        $newEveSeq->EveComplaintID = $this->coreID;
        $newEveSeq->EveType = $eventType;
        $newEveSeq->EveOrder = (1+$this->getLastEveSeqOrd());
        $newEveSeq->save();
        eval("\$newEvent = new App\\Models\\" . $GLOBALS["DB"]->tblModels[$eventType] . ";");
        $newEvent->{ $GLOBALS["DB"]->tblAbbr[$eventType].'EventSequenceID' } = $newEveSeq->getKey();
        $newEvent->save();
        if ($civID > 0) {
            $civLnk = new OPLinksCivilianEvents;
            $civLnk->LnkCivEveEveID = $newEveSeq->getKey();
            $civLnk->LnkCivEveCivID = $civID;
            $civLnk->save();
        }
        $this->sessData->dataSets["EventSequence"][] = $newEveSeq;
        $this->sessData->dataSets[$eventType][] = $newEvent;
        return $newEvent;
    }
    
    protected function deleteEventByID($nID, $eveSeqID)
    {
        if ($eveSeqID > 0) {
            OPEventSequence::find($eveSeqID)->delete();
            OPStops::where('StopEventSequenceID', $eveSeqID)->delete();
            OPSearches::where('SrchEventSequenceID', $eveSeqID)->delete();
            OPArrests::where('ArstEventSequenceID', $eveSeqID)->delete();
            OPForce::where('ForEventSequenceID', $eveSeqID)->delete();
            OPLinksCivilianEvents::where('LnkCivEveEveID', $eveSeqID)->delete();
            OPLinksOfficerEvents::where('LnkOffEveEveID', $eveSeqID)->delete();
        }
        return true;
    }
    
    public function ajaxSaveIncEveOrder()
    {
        foreach ($this->REQ->input('item') as $i => $value) {
            $eveSeq = OPEventSequence::find($value);
            $eveSeq->EveOrder = $i;
            $eveSeq->save();
            //echo 'ajaxSaveIncEveOrder(), find(' . $value . ')->update(' . $i . ')<br />';
            //$this->sessData->logDataSave(288, $value, -3, 'ajaxSaveIncEveOrder', $i);
        }
        return true;
    }
    
    protected function processEventMerge($nID)
    {
        /* needs update!
        $eventType = $this->getEveSeqTypeFromNode($nID);
        if ($this->REQ->has('eventMerge') && intVal($this->REQ->eventMerge) > 0) {
            $civs = $this->getLinkedToEvent('Civilian', $GLOBALS["DB"]->closestLoop["itemID"]);
            if (sizeof($civs) > 0) { 
                foreach ($civs as $civID) {
                    $this->savePeopleEventLink($this->saveDataNewRow('PeopleEventLinks', '+'), -3, $civID, -3, intVal($this->REQ->eventMerge));
                }
            }
            $this->deleteEventPeopleLinks($nID, $GLOBALS["DB"]->closestLoop["itemID"]);
            $this->sessData->deleteDataItem($nID, $eventType, $this->subsetChildRow(true));
            $this->sessData->deleteDataItem($nID, 'EventSequence', $GLOBALS["DB"]->closestLoop["itemID"]);
            $GLOBALS["DB"]->closestLoop["itemID"] = -3;
            $this->sessInfo->save();
            $this->REQ->jumpTo = 148;
            //echo '<script type="text/javascript"> setTimeout("window.location=\'/\'", 5); </script>';
            //exit;
        }
        */
        return true;
    }
    
    protected function getEventOrders($eveSeqID, $eventType)
    {
        $eveSeqOrders = [];
        if (isset($this->sessData->dataSets["Orders"][$eventType]) 
            && sizeof($this->sessData->dataSets["Orders"][$eventType]) > 0) {
            foreach ($this->sessData->dataSets["Orders"][$eventType] as $ord) {
                if ($ord->OrdEventSequenceID == $eveSeqID) $eveSeqOrders[] = $ord;
            }
        }
        return $eveSeqOrders;
    }

    protected function processOrders($nID)
    {
        /*
        $eventType = $this->getEveSeqTypeFromNode($nID);
        //if ($this->debugOn) { echo 'processOrders('.$nID.'), event: ' . $eventType . '<br />'; }
        for ($i=0; $i<20; $i++)
        {
            if ($this->REQ->has('ordID'.$i) && $this->REQ->has('orderVis'.$i))
            {
                $ordID = intVal($this->REQ->input('ordID'.$i));
                //if ($this->debugOn) { echo 'processOrders('.$nID.'), event: ' . $eventType . ', ordID: ' . $ordID . '<br />'; }
                if ($this->REQ->input('orderVis'.$i) == 'Y' && trim($this->REQ->input('order'.$i)) != '')
                {
                    if ($ordID <= 0) $ordID = $this->sessData->newDataRecordSimple('Orders', 'OrdEventSequenceID', $GLOBALS["DB"]->closestLoop["itemID"]);
                    $this->deleteOrderPeopleLinks($nID, $ordID);
                    $this->connectPeopleEventLinks('order'.$i.'Off', 'order'.$i.'Civ', -3, -3, $ordID);
                    OPOrders::find($ordID)->update(array('OrdDescription' => $this->REQ->input('order'.$i)));
                    if ($nID == 342 && $this->REQ->has('hearOrder'.$i)) 
                    {
                        OPOrders::find($ordID)->update(array('OrdTroubleUnderYN' => $this->REQ->input('hearOrder'.$i), 
                            'OrdTroubleUnderstading' => $this->REQ->input('hearOrder'.$i.'Why')));
                    }
                }
                elseif ($ordID > 0)
                {     // then deleting previously submitted order
                    $this->deleteOrderPeopleLinks($nID, $ordID);
                    $this->sessData->deleteDataItem($nID, 'Orders', $ordID);
                }
            }
        }
        $this->loadAllSessData();
        */
        return true;
    }
    
    protected function deleteOrderPeopleLinks($nID, $ordID)
    {
        OPLinksCivilianOrders::where('LnkCivOrdOrdID', $ordID)->delete();
        OPLinksOfficerOrders::where('LnkOffOrdOrdID', $ordID)->delete();
        return true;
    }
    
    protected function connectPeopleOrderLinks($nID, $ordID, $Ptype = '')
    {
        
        
        /* ummm */
        
        
        return true;
    }
    
    protected function deleteEventPeopleLinks($nID, $ordID, $Ptype = '')
    {
        
        
        /* ummm */
        
        
        return true;
    }
    
    protected function connectPeopleEventLinks($nID, $ordID, $Ptype = '')
    {
        
        
        /* ummm */
        
        
        return true;
    }
    
    protected function processHandcuffInjury($nID)
    {
        $handcuffDefID = $GLOBALS["DB"]->getDefID('Injury Types', 'Handcuff Injury');
        $stopRow = $this->getEventSequence($GLOBALS["DB"]->closestLoop["itemID"]);
        if ($this->REQ->has('n316fld') && trim($this->REQ->n316fld) == 'Y') {
            if (intVal($stopRow[0]["Event"]->StopSubjectHandcuffInjury) <= 0) {
                $newInj = new OPInjuries;
                $newInj->InjType = $handcuffDefID;
                $newInj->InjSubjectID = ((isset($stopRow[0]["Civilians"][0])) ? $stopRow[0]["Civilians"][0] : -3);
                $newInj->save();
                $this->sessData->dataSets["Injuries"]["Handcuff"][] = $newInj;
                OPStops::find($stopRow[0]["Event"]->StopID)
                    ->update(array('StopSubjectHandcuffInjury' => $newInj->InjID));
            }
        } elseif (intVal($stopRow[0]["Event"]->StopSubjectHandcuffInjury) > 0) {
            OPStops::find($stopRow[0]["Event"]->StopID)->update(array('StopSubjectHandcuffInjury' => NULL));
            $this->sessData->deleteDataItem($nID, 'Injuries', $stopRow[0]["Event"]->StopSubjectHandcuffInjury);
        }
        return false;
    }
    
/*****************************************************************************
// END Processes Which Handle The Event Sequence
*****************************************************************************/
    
    
    
    
/*****************************************************************************
// START Processes Which Handle People/Officer Linkages
*****************************************************************************/

    protected function getLinkedToEvent($Ptype = 'Officer', $eveSeqID = -3)
    {
        $retArr = [];
        if ($eveSeqID > 0) {
            if ($Ptype == 'Civilian') {
                if (isset($this->sessData->dataSets["LinksCivilianEvents"]) 
                    && sizeof($this->sessData->dataSets["LinksCivilianEvents"]) > 0) {
                    foreach ($this->sessData->dataSets["LinksCivilianEvents"] as $PELnk) {
                        if ($PELnk->LnkCivEveEveID == $eveSeqID) $retArr[] = $PELnk->LnkCivEveCivID;
                    }
                }
            } elseif ($Ptype == 'Officer') {
                if (isset($this->sessData->dataSets["LinksOfficerEvents"]) 
                    && sizeof($this->sessData->dataSets["LinksOfficerEvents"]) > 0) {
                    foreach ($this->sessData->dataSets["LinksOfficerEvents"] as $PELnk) {
                        if ($PELnk->LnkOffEveEveID == $eveSeqID) $retArr[] = $PELnk->LnkOffEveOffID;
                    }
                }
            }
        }
        //if ($this->debugOn) { echo 'getLinkedToEvent('.$Ptype.', '.$allegID.', '.$eveSeqID.', '.$ordID.'): '; print_r($retArr); echo '<br />'; }
        return $retArr;
    }
    
    // get list of all Civilians or Officers connected to all instances of one type of Incident Event
    protected function getLinkedToEventType($Ptype = 'Officer', $eventType = 'Stops')
    {
        $retArr = [];
        if (isset($this->sessData->dataSets["EventSequence"]) 
            && sizeof($this->sessData->dataSets["EventSequence"]) > 0) {
            foreach ($this->sessData->dataSets["EventSequence"] as $eveSeq) {
                if ($eveSeq->EveType == $eventType) {
                    $tmpArr = $this->getLinkedToEvent($Ptype, $eveSeq->EveID);
                    if (sizeof($tmpArr) > 0) { foreach ($tmpArr as $civID) { $retArr[] = $civID; } }
                }
            }
        }
        return $retArr;
    }
    
    protected function getCivEveSeqIdByType($civID, $eventType)
    {
        $foundCivEvent = -3;
        if ($eventType != '' && isset($this->sessData->dataSets["EventSequence"]) 
            && sizeof($this->sessData->dataSets["EventSequence"]) > 0) {
            foreach ($this->sessData->dataSets["EventSequence"] as $eveSeq) {
                if ($eveSeq->EveType == $eventType) {
                    if (in_array($civID, $this->getLinkedToEvent('Civilian', $eveSeq->EveID))) {
                        return $eveSeq->EveID;
                    }
                }
            }
        }
        return $foundCivEvent;
    }
    
    protected function getEveSeqRowType($eveSeqID = -3)
    {
        $eveSeq = $this->sessData->getRowById('EventSequence', $eveSeqID);
        if ($eveSeq && sizeof($eveSeq) > 0) return $eveSeq->EveType;
        return '';
    }
    
    protected function isEventAnimalForce($eveSeqID = -3, $force = [])
    {
        if (sizeof($force) == 0) {
            $eveSeq = $this->sessData->getRowById('EventSequence', $eveSeqID);
            if ($eveSeq && sizeof($eveSeq) > 0 && $eveSeq->EveType == 'Force') {
                $force = $this->sessData->getChildRow('EventSequence', $eveSeq->EveID, $eveSeq->EveType);
            }
        }
        if (sizeof($force) > 0 && isset($force->ForAgainstAnimal) && $force->ForAgainstAnimal == 'Y') {
            return $force->ForAnimalDesc;
        }
        return '';
    }
    
    protected function getEventSequence($eveSeqID = -3)
    {
        $eveSeqs = [];
        $allEvents = $this->sessData->getLoopRows('Events');
        if (sizeof($allEvents) > 0) {
            foreach ($allEvents as $eveSeq) {
                if ($eveSeqID <= 0 || $eveSeqID == $eveSeq->EveID) {
                    $eveSeqs[] = [ 
                        "EveID"     => $eveSeq->EveID, 
                        "EveOrder"  => $eveSeq->EveOrder, 
                        "EveType"   => $eveSeq->EveType, 
                        "Civilians" => $this->getLinkedToEvent('Civilian', $eveSeq->EveID), 
                        "Officers"  => $this->getLinkedToEvent('Officer', $eveSeq->EveID), 
                        "Event"     => $this->sessData->getChildRow('EventSequence', $eveSeq->EveID, $eveSeq->EveType)
                    ];
                }
            }
        }
        return $eveSeqs;
    }
    
    protected function printEventSequenceLine($eveSeq, $info = '')
    {
        if (!isset($eveSeq["EveType"]) || sizeof($eveSeq["Event"]) <= 0) return '';
        $ret = '<span class="slBlueDark">';
        if ($eveSeq["EveType"] == 'Force' 
            && isset($eveSeq["Event"]->ForType) && trim($eveSeq["Event"]->ForType) != '') {
            if ($eveSeq["Event"]->ForType == $GLOBALS["DB"]->getDefID('Force Type', 'Other')) {
                $ret .= $eveSeq["Event"]->ForTypeOther . ' Force ';
            } else {
                $ret .= $GLOBALS["DB"]->getDefValue('Force Type', $eveSeq["Event"]->ForType) . ' Force ';
            }
        } elseif (isset($this->eventTypeLabel[$eveSeq["EveType"]])) {
            $ret .= $this->eventTypeLabel[$eveSeq["EveType"]] . ' ';
        }
        if ($eveSeq["EveType"] == 'Force' && $eveSeq["Event"]->ForAgainstAnimal == 'Y') {
            $ret .= '<span class="gry9">on</span> ' . $eveSeq["Event"]->ForAnimalDesc;
        } else {
            $civNames = $offNames = '';
            if ($this->moreThan1Victim() && in_array($info, array('', 'Civilians'))) { 
                foreach ($eveSeq["Civilians"] as $civ) {
                    $civNames .= ', '.$this->getCivilianNameFromID($civ);
                }
                if (trim($civNames) != '') {
                    $ret .= '<span class="gry9">' . (($eveSeq["EveType"] == 'Force') ? 'on ' : 'of ')
                        . '</span>' . substr($civNames, 1);
                }
            }
            if ($this->moreThan1Officer() && in_array($info, array('', 'Officers'))) { 
                foreach ($eveSeq["Officers"] as $off) {
                    $offNames .= ', '.$this->getOfficerNameFromID($off);
                }
                if (trim($offNames) != '') $ret .= ' <span class="gry9">by</span> ' . substr($offNames, 1);
            }
        }
        $ret .= '</span>';
        return $ret;
    }
    
/*****************************************************************************
// END Processes Which Handle People/Officer Linkages
*****************************************************************************/




/*****************************************************************************
// START Processes Which Manage Lists of People
*****************************************************************************/

    protected function getGenderLabel($physDesc)
    {
        if ($physDesc->PhysGender == 'F') {
            return 'Female';
        } elseif ($physDesc->PhysGender == 'M') {
            return 'Male';
        } elseif ($physDesc->PhysGender == 'O') {
            if (isset($physDesc->PhysGenderOther) && trim($physDesc->PhysGenderOther) != '') {
                return $physDesc->PhysGenderOther;
            }
        }
        return '';
    }

    protected function shortRaceName($raceDefID)
    {
        $race = $GLOBALS["DB"]->getDefValue('Races', $raceDefID);
        if (in_array($race, ['Other', 'Decline or Unknown'])) return '';
        return str_replace('Asian American',         'Asian', 
            str_replace('Black or African American', 'Black', 
            str_replace('Hispanic or Latino',        'Hispanic', 
            $race)));
    }
    
    protected function getPersonLabel($type = 'Civilians', $id = -3, $row = [])
    {
        $name = '';
        $civ2 = [];
        $civ2 = $this->sessData->getChildRow($type, $id, 'PersonContact');
        if (sizeof($civ2) > 0 && (trim($civ2->PrsnNameFirst) != '' || trim($civ2->PrsnNameLast) != '')) {
            $name = $civ2->PrsnNameFirst . ' ' . $civ2->PrsnNameLast . ' ' . $name;
        } else {
            if ($type == 'Officers' && isset($row->OffBadgeNumber) && intVal($row->OffBadgeNumber) > 0) {
                $name = 'Badge #' . $row->OffBadgeNumber . ' ' . $name;
            } else {
                $civ2 = $this->sessData->getChildRow($type, $id, 'PhysicalDesc');
                if (sizeof($civ2) > 0) {
                    if (trim($civ2->PhysGender) != '') $name = $this->getGenderLabel($civ2) . ' ' . $name;
                    if (intVal($civ2->PhysRace) > 0) $name = $this->shortRaceName($civ2->PhysRace) . ' ' . $name;
                }
            }
        }
        return trim($name);
    }

    protected function youLower($civName = '')
    {
        return str_replace('You', 'you', $civName);
    }
    
    // converts Civilians row into identifying name used in most of the complaint process
    protected function getCivName($loop, $civ = [], $itemInd = -3)
    {
        $name = '';
        if ($civ->CivIsCreator == 'Y' && (($loop == 'Victims' && $civ->CivRole == 'Victim') 
            || ($loop == 'Witnesses' && $civ->CivRole == 'Witness')) ) {
            if (!$this->isReport) {
                $name = 'You ' . $name;
            } else {
                $name = $civ->CivNameFirst . ' ' . $civ->CivNameLast;
                if (trim($name) == '') $name = 'Complainant';
            }
        }
        else $name = $this->getPersonLabel('Civilians', $civ->CivID, $civ);
        return trim($name);
    }
    
    public function getCivilianNameFromID($civID)
    {
        if ($this->sessData->dataSets["Civilians"][0]->CivID == $civID) {
            $role = '';
            if ($this->sessData->dataSets["Civilians"][0]->CivRole == 'Victim') {
                $role = 'Victims';
            } elseif ($this->sessData->dataSets["Civilians"][0]->CivRole == 'Witness') {
                $role = 'Witnesses';
            }
            return $this->getCivName($role, $this->sessData->dataSets["Civilians"][0], 1);
        }
        $civInd = $this->sessData->getLoopIndFromID('Victims', $civID);
        if ($civInd >= 0) {
            return $this->getCivName('Victims', $this->sessData->getRowById('Civilians', $civID), (1+$civInd));
        }
        $civInd = $this->sessData->getLoopIndFromID('Witnesses', $civID);
        if ($civInd >= 0) {
            return $this->getCivName('Witnesses', $this->sessData->getRowById('Civilians', $civID), (1+$civInd));
        }
        return '';
    }
    
    // converts Officer row into identifying name used in most of the complaint process
    protected function getOfficerName($officer = [], $itemIndex = -3)
    {
        $name = $this->getPersonLabel('Officers', $officer->OffID, $officer);
        if (trim($name) == '') $name = 'Officer #' . (1+$itemIndex);
        return trim($name);
    }
    
    protected function getOfficerNameFromID($offID)
    {
        $offInd = $this->sessData->getLoopIndFromID('Officers', $offID);
        if ($offInd >= 0) return $this->getOfficerName($this->sessData->getRowById('Officers', $offID), (1+$offInd));
        return '';
    }
    
    protected function getFirstVictimCivInd()
    {
        $civInd = -3;
        if (sizeof($this->sessData->dataSets["Civilians"]) > 0) {
            foreach ($this->sessData->dataSets["Civilians"] as $i => $civ) {
                if (isset($civ->CivRole) && trim($civ->CivRole) == 'Victim' && $civInd < 0) $civInd = $i;
            }
        }
        return $civInd;
    }
    
    protected function getDeptName($dept = [], $itemIndex = -3)
    {
        $name = ''; //(($itemIndex > 0) ? '<span class="fPerc66 gry9">(#'.$itemIndex.')</span>' : '');
        if (isset($dept->DeptName) && trim($dept->DeptName) != '') $name = $dept->DeptName.' '.$name;
        return trim($name);
    }
    
    protected function getDeptNameByID($deptID)
    {
        $dept = $this->sessData->getRowById('Departments', $deptID);
        if (sizeof($dept) > 0) return $this->getDeptName($dept);
        return '';
    }
    
    protected function civRow2Set($civ)
    {
        if (!$civ || sizeof($civ) == 0 || !isset($civ->CivIsCreator)) return '';
        return (($civ->CivIsCreator == 'Y') ? '' : (($civ->CivRole == 'Victim') ? 'Victims' : 'Witnesses') );
    }
    
    protected function getCivilianList($loop = 'Victims')
    {
        if ($loop == 'Victims' || $loop == 'Witness') return $this->sessData->loopItemIDs[$loop];
        $civs = [];
        if (isset($this->sessData->dataSets["Civilians"]) && sizeof($this->sessData->dataSets["Civilians"]) > 0) {
            foreach ($this->sessData->dataSets["Civilians"] as $civ) $civs[] = $civ->CivID;
        }
        return $civs;
    }
    
    // returns list of force types associated with each civilian. Animal is stored as CivID #0
    protected function getCivForceTypes()
    {
        $forceCivs = [];
        if (isset($this->sessData->dataSets["Force"]) && sizeof($this->sessData->dataSets["Force"]) > 0) {
            foreach ($this->sessData->dataSets["Force"] as $force) {
                if ($force->ForAgainstAnimal == 'Y') {
                    if (!isset($forceCivs[0])) $forceCivs[0] = array("recs" => [], "types" => [], "other" => '');
                    $forceCivs[0]["recs"][] = $force;
                    $forceCivs[0]["types"][] = $force->ForType;
                    if ($force->ForType == $GLOBALS["DB"]->getDefID('Force Type', 'Other')) {
                        $forceCivs[0]["other"] = $force->ForTypeOther;
                    }
                } else {
                    $civs = $this->getLinkedToEvent('Civilian', $force->ForEventSequenceID);
                    if (sizeof($civs) > 0) {
                        foreach ($civs as $civID) {
                            if (!isset($forceCivs[$civID])) $forceCivs[$civID] = array("recs" => [], "types" => [], "other" => '');
                            $forceCivs[$civID]["recs"][] = $force;
                            $forceCivs[$civID]["types"][] = $force->ForType;
                            if ($force->ForType == $GLOBALS["DB"]->getDefID('Force Type', 'Other')) {
                                $forceCivs[$civID]["other"] = $force->ForTypeOther;
                            }
                        }
                    }
                }
            }
        }
        return $forceCivs;
    }

    // This function provides an "opts" set of Civilian IDs related to Table 1 which are to be used as candidates for Table 2.
    // It also provides an "active" set of Civilian IDs which already have records in Table 2, with one field value optionally associated.
    // Usages: ('Force', 'Injuries', 'InjType'), ('Injuries', 'InjuryCare'), ('!Arrests', 'Charges')
    protected function getCivSetPossibilities($tbl1, $tbl2, $activeFld = 'ID')
    {
        $possible = array("opts" => [], "active" => []);
        $notInTbl1 = false;
        if (substr($tbl1, 0, 1) == '!') {
            $notInTbl1 = true;
            $tbl1 = str_replace('!', '', $tbl1);
        }
        if (sizeof($this->sessData->dataSets[$tbl1]) > 0) {
            foreach ($this->sessData->dataSets[$tbl1] as $tblRow1) {
                $eveSeqID  = $tblRow1->{$GLOBALS["DB"]->tblAbbr[$tbl1]."EventSequenceID"};
                $eveSubjID = '';
                if (isset($tblRow1->{$GLOBALS["DB"]->tblAbbr[$tbl1]."SubjectID"})) {
                    $eveSubjID = $tblRow1->{$GLOBALS["DB"]->tblAbbr[$tbl1]."SubjectID"};
                }
                if (intVal($eveSeqID) > 0) {
                    $tmpArr = $this->getLinkedToEvent('Civilian', $eveSeqID);
                    if (sizeof($tmpArr) > 0) {
                        foreach ($tmpArr as $civID) {
                            if (!in_array($civID, $possible["opts"])) $possible["opts"][] = $civID;
                        }
                    }
                } elseif (intVal($eveSubjID) > 0 && !in_array($eveSubjID, $possible["opts"])) {
                    $possible["opts"][] = $eveSubjID;
                }
            }
        }
        if ($notInTbl1) {
            $tmpArr = $possible["opts"]; $possible["opts"] = []; 
            foreach ($this->sessData->loopItemIDs["Victims"] as $civ) {
                if (!in_array($civ, $tmpArr)) $possible["opts"][] = $civ;
            }
        }
        if (isset($this->sessData->dataSets[$tbl2]) && sizeof($this->sessData->dataSets[$tbl2]) > 0) {
            foreach ($this->sessData->dataSets[$tbl2] as $tblRow2) {
                $eveSubjID = $tblRow2->{$GLOBALS["DB"]->tblAbbr[$tbl2]."SubjectID"};
                if (!isset($possible["active"][$eveSubjID])) $possible["active"][$eveSubjID] = [];
                $possible["active"][$eveSubjID][] = $tblRow2->{$GLOBALS["DB"]->tblAbbr[$tbl2] . $activeFld};
            }
        }
        return $possible;
    }
    
    // Takes in the same Tables of getCivSetPossibilities and provides the checkbox fields controlling active options.
    // Usage: ('Injuries', 'InjuryCare'), ('!Arrests', 'Charges')
    protected function formCivSetPossibilities($tbl1, $tbl2, $possible = [])
    {
        if (sizeof($possible) == 0) $possible = $this->getCivSetPossibilities($tbl1, $tbl2);
        $ret = '<div class="nFld pB20">';
        if (sizeof($possible["opts"]) > 0) { 
            foreach ($possible["opts"] as $i => $civID) {
                $ret .= '<div class="nFld" style="font-size: 130%;"><nobr><input type="checkbox" autocomplete="off" 
                value="'.$civID.'" name="'.$tbl2.'[]" id="'.$tbl2.$civID.'" ' 
                . ((isset($possible["active"][$civID])) ? 'CHECKED' : '') . ' > 
                <label for="'.$tbl2.$civID.'">' . $this->getCivilianNameFromID($civID) . '</label></nobr></div>';
            }
        }
        return $ret . '</div>';
    }
    
    // Takes in the same Tables of getCivSetPossibilities and the checkbox field controlling active options.
    // It deletes records which have since been deselected, and creates new ones. 
    // Same usage as formCivSetPossibilities().
    protected function postCivSetPossibilities($tbl1, $tbl2, $activeFld = 'ID')
    {
        $possible = $this->getCivSetPossibilities($tbl1, $tbl2, $activeFld);
        if (sizeof($possible["active"]) > 0) { 
            foreach ($possible["active"] as $civID => $activeFld) {
                if (!$this->REQ->has($tbl2) || !in_array($civID, $this->REQ->input($tbl2))) {
                    $complaintIDLnk = ($tbl2 != 'InjuryCare') 
                        ? "->where('".$GLOBALS["DB"]->tblAbbr[$tbl2]."ComplaintID', ".$this->coreID.")" : "";
                    eval("App\\Models\\" . $GLOBALS["DB"]->tblModels[$tbl2] . "::where('"
                        . $GLOBALS["DB"]->tblAbbr[$tbl2] . "SubjectID', " . $civID . ")" 
                        . $complaintIDLnk . "->delete();");
                }
            }
        }
        if ($this->REQ->has($tbl2) && sizeof($this->REQ->input($tbl2)) > 0) { 
            foreach ($this->REQ->input($tbl2) as $civID) {
                if (!isset($possible["active"][$civID])) {
                    $injRow = $this->sessData->newDataRecordSimple($tbl2, $activeFld, $civID);
                }
            }
        }
        return true;
    }
    
    protected function getVehicles()
    {
        $vehicles = [];
        if (isset($this->sessData->dataSets["Vehicles"]) && sizeof($this->sessData->dataSets["Vehicles"]) > 0) {
            foreach ($this->sessData->dataSets["Vehicles"] as $v) {
                $type = $GLOBALS["DB"]->getDefValue('Transportation Civilian', $v->VehicTransportation);
                if (trim($type) == '') {
                    $type = $GLOBALS["DB"]->getDefValue('Transportation Officer', $v->VehicTransportation);
                }
                $desc = [];
                if (trim($v->VehicVehicleMake) != '')    $desc[] = $v->VehicVehicleMake;
                if (trim($v->VehicVehicleModel) != '')   $desc[] = $v->VehicVehicleModel;
                if (trim($v->VehicVehicleDesc) != '')    $desc[] = $v->VehicVehicleDesc;
                if (trim($v->VehicVehicleLicence) != '') $desc[] = 'License Plate ' . $v->VehicVehicleLicence;
                if (trim($v->VehicVehicleNumber) != '')  $desc[] = '#' . $v->VehicVehicleNumber;
                $vehicles[] = [
                    $v->VehicID, 
                    $type . ((sizeof($desc) > 0) ? ': <span class="gry3">' . implode(', ', $desc) . '</span>' : '')
                ];
            }
        }
        return $vehicles;
    }
    
/*****************************************************************************
// END Processes Which Manage Lists of People
*****************************************************************************/





/*****************************************************************************
// START Processes Which Manage Uploads
*****************************************************************************/
    
    // $upArr = array('type' => '', 'title' => '', 'desc' => '', 'privacy' => '', 'upFile' => '', 'storeFile' => '', 'video' => '', 'vidDur' => 0);
    protected function getUploadLinks($nID)
    {
        $upLinks = [];
        $upLinks = array('CivilianID' => -3, 'DeptID' => -3, 'SceneID' => -3, 'EventSequenceID' => -3, 'InjuryID' => -3, 'NoteID' => -3);
        if ($nID == 280) {
            $upLinks["SceneID"] = $this->sessData->dataSets["Scenes"][0]->ScnID;
        } elseif ($nID == 317) {
            if (isset($this->sessData->dataSets["Injuries"]["Handcuff"]) 
                && sizeof($this->sessData->dataSets["Injuries"]["Handcuff"]) > 0) {
                $stopRow = $this->getEventSequence($GLOBALS["DB"]->closestLoop["itemID"]);
                foreach ($this->sessData->dataSets["Injuries"]["Handcuff"] as $i => $inj) {
                    if ($inj->InjSubjectID == $stopRow[0]["Civilians"][0]) {
                        $upLinks["InjuryID"] = $inj->InjID; // was "EvidInjuryID", on 1/28, for some reason?
                    }
                }
            }
        } elseif ($nID == 324) {
            $upLinks["EventSequenceID"] = $GLOBALS["DB"]->closestLoop["itemID"]; // search warrant
        } elseif ($nID == 413) {
            $upLinks["InjuryID"] = $GLOBALS["DB"]->closestLoop["itemID"]; // standard injury
        }
        return $upLinks;
    }
    
    protected function getUploadSet($nID)
    {
        if ($nID == 280) {
            return 'Scene';
        } elseif (in_array($nID, array(317, 413))) {
            return 'Injuries';
        } elseif ($nID == 324) {
            return 'EventSequence';
        }
        return '+';
    }

    protected function storeUploadRecord($nID, $upArr, $upLinks)
    {
        $newEvid = new OPEvidence;
        $newEvid->EvidComplaintID   = $this->coreID;
        $newEvid->EvidType          = $upArr["type"];
        $newEvid->EvidPrivacy       = $upArr["privacy"];
        $newEvid->EvidDateTime      = date("Y-m-d H:i:s");
        $newEvid->EvidTitle         = $upArr["title"];
        $newEvid->EvidEvidenceDesc  = $upArr["desc"];
        $newEvid->EvidUploadFile    = $upArr["upFile"];
        $newEvid->EvidStoredFile    = $upArr["storeFile"];
        $newEvid->EvidVideoLink     = $upArr["video"];
        $newEvid->EvidVideoDuration = $upArr["vidDur"];
        if ($upLinks["CivilianID"] > 0)      $newEvid->EvidCivilianID = $upLinks["CivilianID"];
        if ($upLinks["DeptID"] > 0)          $newEvid->EvidDeptID = $upLinks["DeptID"];
        if ($upLinks["SceneID"] > 0)         $newEvid->EvidSceneID = $upLinks["SceneID"];
        if ($upLinks["EventSequenceID"] > 0) $newEvid->EvidEventSequenceID = $upLinks["EventSequenceID"];
        if ($upLinks["InjuryID"] > 0)        $newEvid->EvidInjuryID = $upLinks["InjuryID"];
        if ($upLinks["NoteID"] > 0)          $newEvid->EvidNoteID = $upLinks["NoteID"];
        $newEvid->save();
        return '';
    }
    
    protected function updateUploadRecord($nID, $upArr)
    {
        $evid = OPEvidence::find($upArr["id"]);
        if ($evid && sizeof($evid) > 0) {
            $evid->EvidType         = $upArr["type"];
            $evid->EvidPrivacy      = $upArr["privacy"];
            $evid->EvidTitle        = $upArr["title"];
            $evid->EvidEvidenceDesc = $upArr["desc"];
            $evid->save();
        }
        return '';
    }
    
    protected function prevUploadList($nID, $upLinks = [])
    {
        $retArr = [];
        $qman = "SELECT 
                `EvidID`            AS `id`, 
                `EvidType`          AS `type`, 
                `EvidTitle`         AS `title`, 
                `EvidEvidenceDesc`  AS `desc`, 
                `EvidPrivacy`       AS `privacy`, 
                `EvidUploadFile`    AS `upFile`, 
                `EvidStoredFile`    AS `storeFile`, 
                `EvidVideoLink`     AS `video`, 
                `EvidVideoDuration` AS `vidDur` 
            FROM `OP_Evidence` 
            WHERE `EvidComplaintID` LIKE '" . $this->coreID . "'";
        if (sizeof($upLinks) > 0) {
            foreach ($upLinks as $lnkType => $lnkVal) {
                if ($lnkVal > 0) $qman .= " AND `Evid" . $lnkType . "` LIKE '" . $lnkVal . "'";
            }
        }
        $retArr = DB::select( DB::raw( $qman . " ORDER BY `EvidID`" ) );
        return $retArr;
    }

/*****************************************************************************
// END Processes Which Manage Uploads
*****************************************************************************/



    
    public function runAjaxChecks(Request $request) {
        
        if ($request->has('email') && $request->has('password')) {
            
            print_r($request);
            $chk = User::where('email', $request->email)->get();
            if ($chk && sizeof($chk) > 0) echo 'found';
            echo 'not found';
            exit;
            
        } elseif ($request->has('policeDept')) {
            
            if (trim($request->get('policeDept')) == '') {
                return '<i>Please type part of the department\'s name to find it.</i>';
            }
            $depts = [];
            // Prioritize by Incident City first, also by Department size (# of officers)
            $reqState = (($request->has('policeState')) ? trim($request->get('policeState')) : '');
            if (!in_array($reqState, ['', 'US'])) {
                $depts = OPDepartments::where('DeptName', 'LIKE', '%'.$request->policeDept.'%')
                    ->where('DeptAddressState', $reqState)
                    ->orderBy('DeptName', 'asc')
                    ->get();
            }
            $deptsFed = OPDepartments::where('DeptName', 'LIKE', '%'.$request->policeDept.'%')
                ->where('DeptType', 366)
                ->orderBy('DeptName', 'asc')
                ->get();
            if ($deptsFed && sizeof($deptsFed) > 0) {
                foreach ($deptsFed as $d) $depts[] = $d;
            }
            echo view('vendor.openpolice.ajax.search-police-dept', [
                "depts"            => $depts, 
                "search"           => $request->get('policeDept'), 
                "stateName"        => $GLOBALS["DB"]->states->getState($request->get('policeState')), 
                "newDeptStateDrop" => $GLOBALS["DB"]->states->stateDrop($request->get('policeState'), true)
            ]);
            return '';
            
        } elseif ($request->has('saveIncEveOrder')) {
            
            $this->ajaxSaveIncEveOrder();
            
        }
        exit;
    }
    
    public function allegationsList(Request $request)
    {
        $this->v["content"] = view('vendor.openpolice.allegations' );
        return view('vendor.survloop.master', $this->v);
    }
    
    
    
}
