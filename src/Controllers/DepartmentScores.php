<?php
/**
  * DepartmentScores is a side-class with functions to calculate,
  * manage, and print out department accessibility scores.
  *
  * OpenPolice.org
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <rockhoppers@runbox.com>
  * @since v0.0.7
  */
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
    public $vals        = [];
    public $scoreDepts  = null;
    public $deptNames   = [];
    public $deptOvers   = [];
    public $deptScore   = [];
    
    public $chartFlds   = [];
    public $gradeColor  = [];
    public $stats       = [];

    public $searchFilts = [];
    
    protected $loaded   = false;
    
    public function __construct()
    {
        $GLOBALS["SL"]->x["defOverIA"] = $GLOBALS["SL"]->def->getID(
            'Investigative Agency Types', 
            'Internal Affairs'
        );
        $GLOBALS["SL"]->x["defOverCiv"] = $GLOBALS["SL"]->def->getID(
            'Investigative Agency Types', 
            'Civilian Oversight'
        );

        $this->vals = [];
        $this->vals["WebForm"] = new DeptFldScore(
            20, 
            'over_complaint_web_form', 
            '', 
            '',
            'Has online-submittable complaint form (not just a PDF)'
        );
        $this->vals["WebInfo"] = new DeptFldScore(
            14, 
            'over_web_complaint_info', 
            '', 
            '',
            'Has complaint information on unique web page (not just on PDF)'
        );
        $this->vals["WebInfoHome"] = new DeptFldScore(
            10, 
            'over_homepage_complaint_link', 
            '', 
            'Y',
            'Has complaint information linked from home page'
        );
        $this->vals["PdfForm"] = new DeptFldScore(
            10, 
            'over_complaint_pdf', 
            '', 
            '',
            'Has complaint form PDF on website'
        );
        $this->vals["ByEmail"] = new DeptFldScore(
            10, 
            'over_way_sub_email', 
            '', 
            '1',
            'Investigates complaints sent via email'
        );
        $this->vals["OfficForm"] = new DeptFldScore(
            10, 
            'over_official_form_not_req', 
            '', 
            '1',
            'Official department form not required for investigation'
        );
        $this->vals["Anonymous"] = new DeptFldScore(
            10, 
            'over_official_anon', 
            '', 
            '1',
            'Anonymous complaints investigated'
        );
        $this->vals["HasWebsite"] = new DeptFldScore(
            3, 
            'over_website', 
            '', 
            '',
            'Has unique department website'
        );
        $this->vals["HasFace"] = new DeptFldScore(
            3, 
            'over_facebook', 
            '', 
            '',
            'Has a Facebook page (with public comments on)'
        );
        $this->vals["HasTwit"] = new DeptFldScore(
            3, 
            'over_twitter', 
            '', 
            '',
            'Has a Twitter account'
        );
        $this->vals["HasYou"] = new DeptFldScore(
            3, 
            'over_youtube', 
            '', 
            '',
            'Has a YouTube channel'
        );
        $this->vals["ByPhone"] = new DeptFldScore(
            2, 
            'over_way_sub_verbal_phone', 
            '', 
            '1',
            'Investigates complaints sent via phone'
        );
        $this->vals["ByPostal"] = new DeptFldScore(
            2, 
            'over_way_sub_paper_mail', 
            '', 
            '1',
            'Investigates complaints sent via postal mail'
        );
        $this->vals["InPerson"] = new DeptFldScore(
            0, 
            'over_way_sub_paper_in_person', 
            '', 
            '1',
            'Requires complaints to be filed in person'
        );
        $this->vals["Notary"] = new DeptFldScore(
            -10, 
            'over_way_sub_notary', 
            '', 
            '1',
            'Requires notary or in-person signature (for one or more types of complaint)'
        );

        $this->chartFlds = [ // column title, field name, trimmed fail value
            [
                'Complaint Web Form',
                'WebForm',
                '<i class="fa fa-cloud-upload" aria-hidden="true"></i>',
                'Have an Online Form to Submit Complaints'
            ], [
                'Complaint Info Unique Page',
                'WebInfo',
                '<i class="fa fa-info-circle" aria-hidden="true"></i>',
                'Have Complaint Info on Unique Web Page'
            ], [
                'Complaints Via Email',
                'ByEmail',
                '<i class="fa fa-at" aria-hidden="true"></i>',
                'Investigate Complaints Sent Via Email'
            ], [
                'Official Form Optional',
                'OfficForm',
                '<i class="fa fa-file-text" aria-hidden="true"></i>',
                'Investigate Complaints Not on Official Form'
            ], [
                'Anonymous Complaints',
                'Anonymous',
                '<i class="fa fa-user-secret" aria-hidden="true"></i>',
                'Investigate Anonymous Complaints'
            ], [
                'On Facebook',
                'HasFace',
                '<i class="fa fa-facebook-official" aria-hidden="true"></i>',
                'Have a Facebook Page'
            ], [
                'On Twitter',
                'HasTwit',
                '<i class="fa fa-twitter" aria-hidden="true"></i>',
                'Have a Twitter Feed'
            ], [
                'Never Requires Notary', 
                'Notary', 
                '<i class="fa fa-certificate" aria-hidden="true"></i>',
                'Never Require Notary to Submit Complaint'
            ]
        ];
        $this->gradeColors = [
            '#2B3493',
            $GLOBALS["SL"]->printColorFadeHex(0.3, '#FFFFFF', '#2B3493'),
            $GLOBALS["SL"]->printColorFadeHex(0.6, '#FFFFFF', '#2B3493'),
            $GLOBALS["SL"]->printColorFadeHex(0.3, '#FFFFFF', '#EC2327'),
            '#EC2327'
        ];
        $this->stats = [
            "count" => 0,
            "score" => 0
        ];
        foreach ($this->vals as $type => $specs) {
            $this->stats[$type] = 0;
        }
        return true;
    }
    
    public function loadAllDepts($searchFilts = [])
    {
        if (!$this->loaded) {
            $flts = "";
            if (isset($searchFilts["deptID"]) && trim($searchFilts["deptID"]) != '') {
                $flts .= "->where('dept_id', '" . trim($searchFilts["deptID"]) . "')";
            } elseif (isset($searchFilts["state"]) 
                && trim($searchFilts["state"]) != '') {
                $flts .= "->where('dept_address_state', '" . trim($searchFilts["state"]) . "')";
            }
            $eval = "\$this->scoreDepts = App\\Models\\OPDepartments"
                . "::where('dept_verified', '>', '2015-08-01 00:00:00')" 
                . $flts . "->orderBy('dept_score_openness', 'desc')->get();";
            eval($eval);
            if ($this->scoreDepts->isNotEmpty()) {
                foreach ($this->scoreDepts as $i => $dept) {
                    if (sizeof($searchFilts) == 0 
                        || !isset($searchFilts["state"]) 
                        || $searchFilts["state"] == $dept->dept_address_state) {
                        $deptName = str_replace('Police Dept', '', trim($dept->dept_name));
                        $deptName = str_replace('Police Department', '', $deptName);
                        $deptName = trim(str_replace('Department', 'Dept', $deptName));
                        $this->deptNames[$dept->dept_id] = $deptName . ', ' . $dept->dept_address_state;
                        $this->deptOvers[$dept->dept_id] = OPOversight::where('over_dept_id', $dept->dept_id)
                            ->whereNotNull('over_agnc_name')
                            ->where('over_agnc_name', 'NOT LIKE', '')
                            ->orderBy('over_type', 'asc')
                            ->first();
                        if (isset($this->deptOvers[$dept->dept_id]->over_type) 
                            && $this->deptOvers[$dept->dept_id]->over_type == $GLOBALS["SL"]->x["defOverCiv"]) {
                            $this->deptScore[$dept->dept_id] = OPOversight::where('over_dept_id', $dept->dept_id)
                                ->whereNotNull('over_agnc_name')
                                ->where('over_agnc_name', 'NOT LIKE', '')
                                ->where('over_type', $GLOBALS["SL"]->x["defOverIA"])
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
                $query->where('zed_over_online_research', 1)
                    ->orWhere('zed_over_made_dept_call', 1)
                    ->orWhere('zed_over_made_ia_call', 1);
            })
            ->select('zed_over_over_dept_id', 'zed_over_online_research', 
                'zed_over_made_dept_call', 'zed_over_made_ia_call', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get();
        if ($chk->isNotEmpty()) {
            foreach ($chk as $dept) {
                if (isset($dept->zed_over_over_dept_id)) {
                    $deptID = $dept->zed_over_over_dept_id;
                    if (!isset($verifCnt[$deptID])) {
                        $verifCnt[$deptID] = 0;
                    }
                    if (isset($dept->zed_over_online_research) 
                        && intVal($dept->zed_over_online_research) == 1) {
                        $verifCnt[$deptID]++;
                    }
                    if (isset($dept->zed_over_made_dept_call) 
                        && intVal($dept->zed_over_made_dept_call) == 1) {
                        $verifCnt[$deptID]++;
                    }
                    if (isset($dept->zed_over_made_ia_call) 
                        && intVal($dept->zed_over_made_ia_call) == 1) {
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
            DB::table('op_departments')->update([ 'dept_verified' => NULL ]);
            foreach ($verifList as $i => $deptID) {
                if (isset($verifDates[$deptID])) {
                    $dept = OPDepartments::find($deptID);
                    if ($dept && isset($dept->dept_id)) {
                        $dept->dept_verified = date("Y-m-d H:i:s", strtotime($verifDates[$deptID]));
                        $dept->save();
                    }
                }
            }
        }
        return true;
    }
    
    public function recalcAllDepts()
    {
        if ($GLOBALS["SL"]->REQ->has('recalc')) {
            $this->recheckVerified();
        }
        // Recalculate all verified departments' scores
        $this->loadAllDepts();
        if ($this->scoreDepts->isNotEmpty()) {
            foreach ($this->scoreDepts as $i => $dept) {
                if ($this->deptOvers[$dept->dept_id]) {
                    $this->scoreDepts[$i]->dept_score_openness = 0;
                    foreach ($this->vals as $type => $specs) {
                        $score = $this->checkRecFld($specs, $dept->dept_id);
                        if ($score != 0) {
                            $this->scoreDepts[$i]->dept_score_openness += $score;
                            $this->stats[$type]++;
                        }
                    }
                    if (isset($this->deptOvers[$dept->dept_id]->over_email)
                        && trim($this->deptOvers[$dept->dept_id]->over_email) != ''
                        && isset($this->deptOvers[$dept->dept_id]->over_way_sub_email)
                        && intVal($this->deptOvers[$dept->dept_id]->over_way_sub_email) == 1
                        && isset($this->deptOvers[$dept->dept_id]->over_official_form_not_req)
                        && intVal($this->deptOvers[$dept->dept_id]->over_official_form_not_req) == 1) {
                        $this->scoreDepts[$i]->dept_op_compliant = 1;
                    } else {
                        $this->scoreDepts[$i]->dept_op_compliant = 0;
                    }
                    $this->scoreDepts[$i]->save();
                    $this->stats["score"] += $this->scoreDepts[$i]->dept_score_openness;
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
            $overrow = $this->deptOvers[$deptID];
            if (isset($this->deptScore[$deptID])) {
                $overrow = $this->deptScore[$deptID];
            }
            if (!$overrow) {
                return 0;
            }
        }
        if (trim($specs->ifIs) != '') {
            if (isset($overrow->{ $specs->fld }) 
                && trim($overrow->{ $specs->fld }) == $specs->ifIs) {
                return $specs->score;
            }
        } else { // score defined by absence of value
            if (trim($overrow->{ $specs->fld }) != trim($specs->ifNot)) {
                return $specs->score;
            }
        }
        return 0;
    }
    
    public function printTotsBars($searchFilts = [])
    {
        $this->loadAllDepts($searchFilts);
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
        
        return view(
            'vendor.openpolice.nodes.1816-depts-score-criteria-bars', 
            [
                "datOut" => $datOut,
                "colorG" => $this->gradeColors[0],
                "colorB" => $this->gradeColors[4]
            ]
        )->render();
    }
    
    public function loadDeptStuff($deptID = -3)
    {
        if (!isset($GLOBALS["SL"]->x["depts"])) {
            $GLOBALS["SL"]->x["depts"] = [];
        }
        if ($deptID > 0 && !isset($GLOBALS["SL"]->x["depts"][$deptID])) {
            $d = [ "id" => $deptID ];
            $d["deptRow"] = OPDepartments::find($deptID);
            $d["iaRow"] = OPOversight::where('over_dept_id', $deptID)
                ->where('over_type', $GLOBALS["SL"]->x["defOverIA"])
                ->first();
            $d["civRow"] = OPOversight::where('over_dept_id', $deptID)
                ->where('over_type', $GLOBALS["SL"]->x["defOverCiv"])
                ->first();
            if (!isset($d["iaRow"]) || !$d["iaRow"]) {
                $d["iaRow"] = new OPOversight;
                $d["iaRow"]->over_dept_id = $deptID;
                if ($d["deptRow"] && isset($d["deptRow"]->dept_name)) {
                    $d["iaRow"]->over_type          = $GLOBALS["SL"]->x["defOverIA"];
                    $d["iaRow"]->over_agnc_name     = $d["deptRow"]->dept_name;
                    $d["iaRow"]->over_address       = $d["deptRow"]->dept_address;
                    $d["iaRow"]->over_address2      = $d["deptRow"]->dept_address2;
                    $d["iaRow"]->over_address_city  = $d["deptRow"]->dept_address_city;
                    $d["iaRow"]->over_address_state = $d["deptRow"]->dept_address_state;
                    $d["iaRow"]->over_address_zip   = $d["deptRow"]->dept_address_zip;
                    $d["iaRow"]->over_phone_work    = $d["deptRow"]->dept_phone_work;
                }
                $d["iaRow"]->save();
            }
            if (isset($d["deptRow"]->dept_name) && trim($d["deptRow"]->dept_name) != '') {
                if (!isset($d["iaRow"]->over_agnc_name) || trim($d["iaRow"]->over_agnc_name) == '') {
                    $d["iaRow"]->over_agnc_name = $d["deptRow"]->dept_name;
                    $d["iaRow"]->save();
                }
                if ($d["deptRow"] && isset($d["deptRow"]->dept_address)) {
                    $d["deptAddy"] = $d["deptRow"]->dept_address . ', ';
                    if (isset($d["deptRow"]->dept_address2) 
                        && trim($d["deptRow"]->dept_address2) != '') {
                        $d["deptAddy"] .= $d["deptRow"]->dept_address2 . ', ';
                    }
                    $d["deptAddy"] .= $d["deptRow"]->dept_address_city . ', ' 
                        . $d["deptRow"]->dept_address_state . ' ' . $d["deptRow"]->dept_address_zip;
                    $d["iaAddy"] = '';
                    if (isset($d["iaRow"]->over_address) 
                        && trim($d["iaRow"]->over_address) != '') {
                        $d["iaAddy"] = $d["iaRow"]->over_address . ', ';
                        if (isset($d["iaRow"]->over_address2) 
                            && trim($d["iaRow"]->over_address2) != '') {
                            $d["iaAddy"] .= $d["iaRow"]->over_address2 . ', ';
                        }
                        $d["iaAddy"] .= $d["iaRow"]->over_address_city 
                            . ', ' . $d["iaRow"]->over_address_state 
                            . ' ' . $d["iaRow"]->over_address_zip;
                    }
                    $d["civAddy"]  = '';
                    if (isset($d["civRow"]->over_address) 
                        && trim($d["civRow"]->over_address) != '') {
                        $d["civAddy"] = $d["civRow"]->over_address . ', ';
                        if (isset($d["civRow"]->over_address2) 
                            && trim($d["civRow"]->over_address2) != '') {
                            $d["civAddy"] .= $d["civRow"]->over_address2 . ', ';
                        }
                        $d["civAddy"] .= $d["civRow"]->over_address_city 
                            . ', ' . $d["civRow"]->over_address_state 
                            . ' ' . $d["civRow"]->over_address_zip;
                    }
                }
            }
            
            $d["whichOver"] = $which = 'iaRow';
            if (isset($d["civRow"]) 
                && isset($d["civRow"]->over_agnc_name)
                && trim($d["civRow"]->over_agnc_name) != '') {
                $d["whichOver"] = $which = 'civRow';
            }
            $d["overUser"] = $d["score"] = [];
            if (isset($d[$which]) && isset($d[$which]->over_email)) {
                $email = $d[$which]->over_email;
                $d["overUser"] = User::where('email', $email)
                    ->first();
            }
            if (isset($d) && isset($d["iaRow"])) {
                // isset($GLOBALS["SL"]->x["depts"])
                foreach ($this->vals as $type => $specs) {
                    $d["score"][] = [
                        $specs->score,
                        $specs->label,
                        ($this->checkRecFld($specs, -3, $d["iaRow"]) != 0)
                    ];
                }
            }
            $GLOBALS["SL"]->x["depts"][$deptID] = $d;
        }
        return true;
    }
    
    public function printMapScoreDesc($deptID = 0)
    {
        if (!isset($GLOBALS["SL"]->x["depts"]) 
            || !isset($GLOBALS["SL"]->x["depts"][$deptID])) {
            return '';
        }
        return view(
            'vendor.openpolice.dept-kml-desc', 
            [ "dept" => $GLOBALS["SL"]->x["depts"][$deptID] ]
        )->render();
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