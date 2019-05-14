<?php
namespace OpenPolice\Controllers;

use DB;
use Auth;
use App\Models\User;
use App\Models\OPDepartments;
use App\Models\OPOversight;
use App\Models\OPzComplaintReviews;
use App\Models\OPZeditDepartments;
use App\Models\OPZeditOversight;
use App\Models\OPOversightModels;

class DepartmentScores
{
    public $vals = [];
    public $scoreDepts = null;
    public $deptNames  = [];
    public $deptOvers  = [];
    public $deptScore  = [];
    
    public $chartFlds  = [];
    public $gradeColor = [];
    public $stats      = [];
    
    protected $loaded  = false;
    
    public function __construct()
    {
        $this->vals = [
            "WebForm"     => new DeptFldScore(20,  'OverComplaintWebForm',      '', '',
                'Has online-submittable complaint form (not just a PDF)'),
            
            "WebInfo"     => new DeptFldScore(14,  'OverWebComplaintInfo',      '', '',
                'Has complaint information on unique web page (not just on PDF)'),
            
            "WebInfoHome" => new DeptFldScore(10,  'OverHomepageComplaintLink', '', 'Y',
                'Has complaint information linked from home page'),
            
            "PdfForm"     => new DeptFldScore(10,  'OverComplaintPDF',          '', '',
                'Has complaint form PDF on website'),
            
            "ByEmail"     => new DeptFldScore(10,  'OverWaySubEmail',           '', '1',
                'Investigates complaints sent via email'),
            
            "OfficForm"   => new DeptFldScore(10,  'OverOfficialFormNotReq',    '', '1',
                'Official department form not required for investigation'),
            
            "Anonymous"   => new DeptFldScore(10,  'OverOfficialAnon',          '', '1',
                'Anonymous complaints investigated'),
            
            "HasWebsite"  => new DeptFldScore(3,   'OverWebsite',               '', '',
                'Has unique department website'),
            
            "HasFace"     => new DeptFldScore(3,   'OverFacebook',              '', '',
                'Has a Facebook page (with public comments on)'),
            
            "HasTwit"     => new DeptFldScore(3,   'OverTwitter',               '', '',
                'Has a Twitter account'),
            
            "HasYou"      => new DeptFldScore(3,   'OverYouTube',               '', '',
                'Has a YouTube channel'),
            
            "ByPhone"     => new DeptFldScore(2,   'OverWaySubVerbalPhone',     '', '1',
                'Investigates complaints sent via phone'),
            
            "ByPostal"    => new DeptFldScore(2,   'OverWaySubPaperMail',       '', '1',
                'Investigates complaints sent via postal mail'),
            
            "InPerson"    => new DeptFldScore(0,   'OverWaySubPaperInPerson',   '', '1',
                'Requires complaints to be filed in person'),
            
            "Notary"      => new DeptFldScore(-10, 'OverWaySubNotary',          '', '1',
                'Requires notary (for one or more types of complaint)')
            ];
        $this->chartFlds = [ // column title, field name, trimmed fail value
            ['Complaint Web Form',   'WebForm',    '<i class="fa fa-cloud-upload" aria-hidden="true"></i>',
                'Have an Online Form to Submit Complaints'],
            ['Complaint Info Unique Page',  'WebInfo',    '<i class="fa fa-info-circle" aria-hidden="true"></i>',
                'Have Complaint Info on Unique Web Page'],
            ['Complaints Via Email', 'ByEmail',    '<i class="fa fa-at" aria-hidden="true"></i>',
                'Investigate Complaints Sent Via Email'],
            ['Official Form Optional',     'OfficForm',  '<i class="fa fa-file-text" aria-hidden="true"></i>',
                'Investigate Complaints Not on Official Form'],
            ['Anonymous Complaints', 'Anonymous',  '<i class="fa fa-user-secret" aria-hidden="true"></i>',
                'Investigate Anonymous Complaints'],
            ['On Facebook',          'HasFace', '<i class="fa fa-facebook-official" aria-hidden="true"></i>',
                'Have a Facebook Page'],
            ['On Twitter',     'HasTwit',    '<i class="fa fa-twitter" aria-hidden="true"></i>',
                'Have a Twitter Feed'],
            ['Never Requires Notary',      'Notary',     '<i class="fa fa-certificate" aria-hidden="true"></i>',
                'Never Require Notary to Submit Complaint']
            ];
        $this->gradeColors = [
            '#2B3493',
            $GLOBALS["SL"]->printColorFadeHex(0.3, '#FFFFFF', '#2B3493'),
            $GLOBALS["SL"]->printColorFadeHex(0.6, '#FFFFFF', '#2B3493'),
            $GLOBALS["SL"]->printColorFadeHex(0.3, '#FFFFFF', '#EC2327'),
            '#EC2327'
            ];
        $this->stats = [ "count" => 0, "score" => 0 ];
        foreach ($this->vals as $type => $specs) {
            $this->stats[$type] = 0;
        }
        return true;
    }
    
    public function loadAllDepts($searchOpts = [])
    {
        if (!$this->loaded) {
            $flts = "";
            if (isset($searchOpts["deptID"]) && trim($searchOpts["deptID"]) != '') {
                $flts .= "->where('DeptID', '" . trim($searchOpts["deptID"]) . "')";
            } elseif (isset($searchOpts["state"]) && trim($searchOpts["state"]) != '') {
                $flts .= "->where('DeptAddressState', '" . trim($searchOpts["state"]) . "')";
            }
            $eval = "\$this->scoreDepts = OpenPolice\\Models\\OPDepartments::where('DeptVerified', '>', '2015-08-01 00:00:00')" . $flts 
                . "->orderBy('DeptScoreOpenness', 'desc')->get();";
            eval($eval);
            if ($this->scoreDepts->isNotEmpty()) {
                foreach ($this->scoreDepts as $i => $dept) {
                    if (sizeof($searchOpts) == 0 || !isset($searchOpts["state"]) 
                        || $searchOpts["state"] == $dept->DeptAddressState) {
                        $this->deptNames[$dept->DeptID] = trim(str_replace('Department', 'Dept', 
                            str_replace('Police Department', '', str_replace('Police Dept', '', $dept->DeptName))))
                            . ', ' . $dept->DeptAddressState;
                        $this->deptOvers[$dept->DeptID] = OPOversight::where('OverDeptID', $dept->DeptID)
                            ->whereNotNull('OverAgncName')
                            ->where('OverAgncName', 'NOT LIKE', '')
                            ->orderBy('OverType', 'asc')
                            ->first();
                        if (isset($this->deptOvers[$dept->DeptID]->OverType) && $this->deptOvers[$dept->DeptID]->OverType 
                            == $GLOBALS["SL"]->def->getID('Investigative Agency Types', 'Civilian Oversight')) {
                            $this->deptScore[$dept->DeptID] = OPOversight::where('OverDeptID', $dept->DeptID)
                                ->whereNotNull('OverAgncName')
                                ->where('OverAgncName', 'NOT LIKE', '')
                                ->where('OverType', 
                                    $GLOBALS["SL"]->def->getID('Investigative Agency Types', 'Internal Affairs'))
                                ->first();
                        }
                    }
                }
            }
            $this->loaded = true;
        }
        return true;
    }
    
    protected function recheckVerified()
    {
        $verifList = $verifDates = $verifCnt = [];
        $chk = OPZeditOversight::where(function ($query) {
                $query->where('ZedOverOnlineResearch', 1)
                    ->orWhere('ZedOverMadeDeptCall', 1)
                    ->orWhere('ZedOverMadeIACall', 1);
            })
            ->select('ZedOverOverDeptID', 'ZedOverOnlineResearch', 'ZedOverMadeDeptCall', 'ZedOverMadeIACall', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get();
        if ($chk->isNotEmpty()) {
            foreach ($chk as $dept) {
                if (isset($dept->ZedOverOverDeptID)) {
                    $deptID = $dept->ZedOverOverDeptID;
                    if (!isset($verifCnt[$deptID])) {
                        $verifCnt[$deptID] = 0;
                    }
                    if (isset($dept->ZedOverOnlineResearch) && intVal($dept->ZedOverOnlineResearch) == 1) {
                        $verifCnt[$deptID]++;
                    }
                    if (isset($dept->ZedOverMadeDeptCall) && intVal($dept->ZedOverMadeDeptCall) == 1) {
                        $verifCnt[$deptID]++;
                    }
                    if (isset($dept->ZedOverMadeIACall) && intVal($dept->ZedOverMadeIACall) == 1) {
                        $verifCnt[$deptID]++;
                    }
                    if (!isset($verifDates[$deptID])) {
                        $verifDates[$deptID] = $dept->created_at;
                    }
                    if ($verifCnt[$deptID] > 0 && !in_array($deptID, $verifList)) {
                        $verifList[] = $deptID;
                    }
                }
            }
            DB::table('OP_Departments')
                ->update([ 'DeptVerified' => NULL ]);
            foreach ($verifList as $i => $deptID) {
                if (isset($verifDates[$deptID])) {
                    $dept = OPDepartments::find($deptID);
                    if ($dept && isset($dept->DeptID)) {
                        $dept->DeptVerified = date("Y-m-d H:i:s", strtotime($verifDates[$deptID]));
                        $dept->save();
                    }
                }
            }
        }
        return true;
    }
    
    public function recalcAllDepts()
    {
        if ($GLOBALS["SL"]->REQ->has('refresh')) {
            $this->recheckVerified();
        }
        // Recalculate all verified departments' scores
        $this->loadAllDepts();
        if ($this->scoreDepts->isNotEmpty()) {
            foreach ($this->scoreDepts as $i => $dept) {
                if ($this->deptOvers[$dept->DeptID]) {
                    $this->scoreDepts[$i]->DeptScoreOpenness = 0;
                    foreach ($this->vals as $type => $specs) {
                        $score = $this->checkRecFld($specs, $dept->DeptID);
                        if ($score != 0) {
                            $this->scoreDepts[$i]->DeptScoreOpenness += $score;
                            $this->stats[$type]++;
                        }
                    }
                    $this->scoreDepts[$i]->save();
                    $this->stats["score"] += $this->scoreDepts[$i]->DeptScoreOpenness;
                    $this->stats["count"]++;
                }
            }
            if ($this->stats["count"] > 0) {
                $this->stats["scoreAvg"] = $this->stats["score"]/$this->stats["count"];
            }
        }
        return true;
    }
    
    public function checkRecFld($specs, $deptID, $overrow = null)
    {
        if ($overrow === null) {
            if ($deptID <= 0) {
                return 0;
            }
            $overrow = ((isset($this->deptScore[$deptID])) ? $this->deptScore[$deptID] : $this->deptOvers[$deptID]);
            if (!$overrow) {
                return 0;
            }
        }
        if (trim($specs->ifIs) != '') {
            if (isset($overrow->{ $specs->fld }) && trim($overrow->{ $specs->fld }) == $specs->ifIs) {
                return $specs->score;
            }
        } else { // score defined by absence of value
            if (trim($overrow->{ $specs->fld }) != trim($specs->ifNot)) {
                return $specs->score;
            }
        }
        return 0;
    }
    
    public function printTotsBars()
    {
        $this->loadAllDepts();
        $this->recalcAllDepts();
        $datOut = $datTmp = [];
        foreach ($this->chartFlds as $fld) {
            $perc = 100;
            if ($this->stats['count'] > 0) {
                $perc = round(100*$this->stats[$fld[1]]/$this->stats['count']);
            }
            if ($fld[1] == 'Notary') {
                $datTmp[] = [ strip_tags($fld[3]), 100-$perc, $fld[2]];
            } else {
                $datTmp[] = [ strip_tags($fld[3]), $perc, $fld[2] ];
            }
        }
        $done = [];
        for ($i = 0; $i < sizeof($datTmp); $i++) {
            $ind = $min = -1000;
            foreach ($datTmp as $j => $dat) {
                if (($ind < 0 || $dat[1] < $min) && !in_array($j, $done)) {
                    $min = $dat[1];
                    $ind = $j;
                }
            }
            $datOut[] = $datTmp[$ind];
            $done[] = $ind;
        }
        
        return view('vendor.openpolice.nodes.inc-depts-score-criteria-bars', [
            "datOut" => $datOut,
            "colorG" => $this->gradeColors[0],
            "colorB" => $this->gradeColors[4]
            ])->render();
    }
    
    public function loadDeptStuff($deptID = -3)
    {
        if (!isset($GLOBALS["SL"]->x["depts"])) $GLOBALS["SL"]->x["depts"] = [];
        if ($deptID > 0 && !isset($GLOBALS["SL"]->x["depts"][$deptID])) {
            $d = [ "id" => $deptID ];
            $d["deptRow"] = OPDepartments::find($deptID);
            $d["iaRow"] = OPOversight::where('OverDeptID', $deptID)
                ->where('OverType', $GLOBALS["SL"]->def->getID('Investigative Agency Types', 'Internal Affairs'))
                ->first();
            $d["civRow"] = OPOversight::where('OverDeptID', $deptID)
                ->where('OverType', $GLOBALS["SL"]->def->getID('Investigative Agency Types', 'Civilian Oversight'))
                ->first();
            if (!isset($d["iaRow"]) || !$d["iaRow"]) {
                $d["iaRow"] = new OPOversight;
                $d["iaRow"]->OverDeptID = $deptID;
                if ($d["deptRow"] && isset($d["deptRow"]->DeptName)) {
                    $d["iaRow"]->OverType = $GLOBALS["SL"]->def->getID('Investigative Agency Types', 'Internal Affairs');
                    $d["iaRow"]->OverAgncName = $d["deptRow"]->DeptName;
                    $d["iaRow"]->OverAddress = $d["deptRow"]->DeptAddress;
                    $d["iaRow"]->OverAddress2 = $d["deptRow"]->DeptAddress2;
                    $d["iaRow"]->OverAddressCity = $d["deptRow"]->DeptAddressCity;
                    $d["iaRow"]->OverAddressState = $d["deptRow"]->DeptAddressState;
                    $d["iaRow"]->OverAddressZip = $d["deptRow"]->DeptAddressZip;
                    $d["iaRow"]->OverPhoneWork = $d["deptRow"]->DeptPhoneWork;
                }
                $d["iaRow"]->save();
            }
            if (isset($d["deptRow"]->DeptName)&& trim($d["deptRow"]->DeptName) != '') {
                if (!isset($d["iaRow"]->OverAgncName) || trim($d["iaRow"]->OverAgncName) == '') {
                    $d["iaRow"]->OverAgncName= $d["deptRow"]->DeptName;
                    $d["iaRow"]->save();
                }
                if ($d["deptRow"] && isset($d["deptRow"]->DeptAddress)) {
                    $d["deptAddy"] = $d["deptRow"]->DeptAddress . ', ';
                    if (isset($d["deptRow"]->DeptAddress2) && trim($d["deptRow"]->DeptAddress2) != '') {
                        $d["deptAddy"] .= $d["deptRow"]->DeptAddress2 . ', ';
                    }
                    $d["deptAddy"] .= $d["deptRow"]->DeptAddressCity . ', ' . $d["deptRow"]->DeptAddressState . ' ' 
                        . $d["deptRow"]->DeptAddressZip;
                    $d["iaAddy"] = '';
                    if (isset($d["iaRow"]->OverAddress) && trim($d["iaRow"]->OverAddress) != '') {
                        $d["iaAddy"] = $d["iaRow"]->OverAddress . ', ';
                        if (isset($d["iaRow"]->OverAddress2) && trim($d["iaRow"]->OverAddress2) != '') {
                            $d["iaAddy"] .= $d["iaRow"]->OverAddress2 . ', ';
                        }
                        $d["iaAddy"] .= $d["iaRow"]->OverAddressCity . ', ' . $d["iaRow"]->OverAddressState . ' ' 
                            . $d["iaRow"]->OverAddressZip;
                    }
                    $d["civAddy"]  = '';
                    if (isset($d["civRow"]->OverAddress) && trim($d["civRow"]->OverAddress) != '') {
                        $d["civAddy"] = $d["civRow"]->OverAddress . ', ';
                        if (isset($d["civRow"]->OverAddress2) && trim($d["civRow"]->OverAddress2) != '') {
                            $d["civAddy"] .= $d["civRow"]->OverAddress2 . ', ';
                        }
                        $d["civAddy"] .= $d["civRow"]->OverAddressCity . ', ' . $d["civRow"]->OverAddressState . ' ' 
                            . $d["civRow"]->OverAddressZip;
                    }
                }
            }
            
            $d["whichOver"] = $which = ((isset($d["civRow"]) && isset($d["civRow"]->OverAgncName)
                && trim($d["civRow"]->OverAgncName) != '') ? "civRow" :"iaRow");
            $d["overUser"] = $d["score"] = [];
            if (isset($d[$which]) && isset($d[$which]->OverEmail)) {
                $email = $d[$which]->OverEmail;
                $d["overUser"] = User::where('email', $email)->first();
            }
            if (isset($d) && isset($d["iaRow"])) { // isset($GLOBALS["SL"]->x["depts"])
                foreach ($this->vals as $type => $specs) {
                    $d["score"][] = [
                        $specs->score,
                        $specs->label,
                        ($this->checkRecFld($specs, -3, $d["iaRow"])
                            != 0)
                        ];
                }
            }
            $GLOBALS["SL"]->x["depts"][$deptID] = $d;
        }
        return true;
    }
    
    public function printMapScoreDesc($deptID = 0)
    {
        if (!isset($GLOBALS["SL"]->x["depts"]) || !isset($GLOBALS["SL"]->x["depts"][$deptID])) {
            return '';
        }
        return view('vendor.openpolice.dept-kml-desc', [ "dept" => $GLOBALS["SL"]->x["depts"][$deptID] ])->render();
    }
    
    
}

class DeptFldScore
{
    public $fld   = '';
    public $label = '';
    
    public $score = 0;
    public $ifNot = '';
    public $ifIs  = '';
    
    public function __construct($score = 0, $fld = '', $ifNot = '', $ifIs = '', $label = '')
    {
        $this->fld   = $fld;
        $this->label = $label;
        $this->score = $score;
        $this->ifNot = $ifNot;
        $this->ifIs  = $ifIs;
        return true;
    }
}