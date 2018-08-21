<?php
namespace OpenPolice\Controllers;

use DB;
use Auth;

use App\Models\OPDepartments;
use App\Models\OPOversight;

use App\Models\OPzComplaintReviews;
use App\Models\OPZeditDepartments;
use App\Models\OPZeditOversight;

class DepartmentScores
{
    public $vals = [];
    public $scoreDepts = null;
    public $deptNames  = [];
    public $deptOvers  = [];
    
    public $chartFlds  = [];
    public $gradeColor = [];
    public $stats      = [];
    
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
            ['<br />Complaint Web Form',   'WebForm',    '<i class="fa fa-cloud-upload" aria-hidden="true"></i>',
                'Have an Online Form to Submit Complaints'],
            ['Complaint Info Unique Page',  'WebInfo',    '<i class="fa fa-info-circle" aria-hidden="true"></i>',
                'Have Complaint Info on Unique Web Page'],
            ['<br />Complaints Via Email', 'ByEmail',    '<i class="fa fa-at" aria-hidden="true"></i>',
                'Investigate Complaints Sent Via Email'],
            ['Official Form Optional',     'OfficForm',  '<i class="fa fa-file-text" aria-hidden="true"></i>',
                'Investigate Complaints Not on Official Form'],
            ['<br />Anonymous Complaints', 'Anonymous',  '<i class="fa fa-user-secret" aria-hidden="true"></i>',
                'Investigate Anonymous Complaints'],
            ['<br />On Facebook',          'HasFace', '<i class="fa fa-facebook-official" aria-hidden="true"></i>',
                'Have a Facebook Page'],
            ['<br /><br />On Twitter',     'HasTwit',    '<i class="fa fa-twitter" aria-hidden="true"></i>',
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
        foreach ($this->vals as $type => $specs) $this->stats[$type] = 0;
        return true;
    }
    
    public function loadAllDepts()
    {
        $this->scoreDepts = OPDepartments::where('DeptScoreOpenness', '>', 0)
            ->where('DeptVerified', '>', '2015-08-01 00:00:00')
            ->orderBy('DeptScoreOpenness', 'desc')
            ->get();
        if ($this->scoreDepts->isNotEmpty()) {
            foreach ($this->scoreDepts as $i => $dept) {
                $this->deptNames[$dept->DeptID] = trim(str_replace('Department', 'Dept', 
                    str_replace('Police Department', '', str_replace('Police Dept', '', $dept->DeptName))))
                    . ', ' . $dept->DeptAddressState;
                $this->deptOvers[$dept->DeptID] = OPOversight::where('OverDeptID', $dept->DeptID)
                    ->whereNotNull('OverAgncName')
                    ->where('OverAgncName', 'NOT LIKE', '')
                    ->orderBy('OverType', 'asc')
                    ->first();
            }
        }
        return true;
    }
    
    public function recalcAllDepts()
    {
        $this->loadAllDepts();
        if ($this->scoreDepts->isNotEmpty()) {
            foreach ($this->scoreDepts as $i => $dept) {
                if ($this->deptOvers[$dept->DeptID]) {
                    $this->stats["count"]++;
                    $this->scoreDepts[$i]->DeptScoreOpenness = 0;
                    foreach ($this->vals as $type => $specs) {
                        $score = $this->checkRecFld($specs, $this->deptOvers[$dept->DeptID]);
                        if ($score != 0) {
                            $this->scoreDepts[$i]->DeptScoreOpenness += $score;
                            $this->stats[$type]++;
                        }
                    }
                    $this->scoreDepts[$i]->save();
                    $this->stats["score"] += $this->scoreDepts[$i]->DeptScoreOpenness;
                }
            }
        }
        return true;
    }
    
    public function checkRecFld($specs, $overrow = null)
    {
        if (!$overrow) return 0;
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
            if ($fld[1] == 'Notary') {
                $datTmp[] = [ strip_tags($fld[3]), 100-round(100*$this->stats[$fld[1]]/$this->stats['count']), $fld[2]];
            } else {
                $datTmp[] = [ strip_tags($fld[3]), round(100*$this->stats[$fld[1]]/$this->stats['count']), $fld[2] ];
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