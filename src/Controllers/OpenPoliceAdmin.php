<?php
namespace OpenPolice\Controllers;

use DB;
use Auth;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\SLDefinitions;

use App\Models\OPComplaints;
use App\Models\OPAllegations;
use App\Models\OPOfficers;
use App\Models\OPPersonContact;
use App\Models\OPDepartments;
use App\Models\OPOversight;
use App\Models\OPLinksComplaintDept;
use App\Models\OPCustomers;             

use App\Models\OPzVolunEditsDepts;
use App\Models\OPzVolunEditsOvers;
use App\Models\OPzVolunStatDays;
use App\Models\OPzVolunUserInfo;

use App\Models\SLEmails;
use App\Models\SLEmailed;
use App\Models\OPzComplaintEmailed;
use App\Models\OPzComplaintReviews;

use OpenPolice\Controllers\OpenPoliceReport;
use OpenPolice\Controllers\VolunteerLeaderboard;
use SurvLoop\Controllers\AdminSubsController;

class OpenPoliceAdmin extends AdminSubsController
{
    public $classExtension     = 'OpenPoliceAdmin';
    public $treeID             = 1;
    
    public function initPowerUser($uID = -3)
    {
        if (!$this->v["user"] || intVal($this->v["user"]->id) <= 0
            || !$this->v["user"]->hasRole('administrator|staff|databaser|brancher|volunteer')) {
            return $this->redir('/');
        }
        $userInfo = OPzVolunUserInfo::where('UserInfoUserID', $uID)
            ->first();
        if (!$userInfo || sizeof($userInfo) == 0) {
            $userInfo = new OPzVolunUserInfo;
            $userInfo->UserInfoUserID = $uID;
            $userInfo->save();
        }
        $userContact = [];
        if (!isset($userInfo->UserInfoPersonContactID) || intVal($userInfo->UserInfoPersonContactID) <= 0) {
            $thisUser = User::select('email')->find($uID);
            $userContact = new OPPersonContact;
            $userContact->PrsnEmail = $thisUser->email;
            $userContact->save();
            $userInfo->UserInfoPersonContactID = $userContact->PrsnID;
            $userInfo->save();
        } else {
            $userContact = OPPersonContact::find($userInfo->UserInfoPersonContactID);
        }
        return [$userInfo, $userContact];
    }
    
    public function loadAdmMenu()
    {
        if (isset($this->v["user"])) {
            $published = $flagged = 0;
            /* $chk = DSStories::where('StryStatus', $GLOBALS["SL"]->getDefID('Story Status', 'Published'))
                ->select('StryID')
                ->get();
            $published = sizeof($chk);
            $flagIDs = [];
            $flags = SLSessEmojis::where('SessEmoTreeID', 1)
                ->where('SessEmoDefID', 194)
                ->select('SessEmoRecID')
                ->get();
            if ($flags && sizeof($flags) > 0) {
                foreach ($flags as $f) {
                    if (!in_array($f->SessEmoRecID, $flagIDs)) $flagIDs[] = $f->SessEmoRecID;
                }
            }
            $chk = DSStories::whereIn('StryID', $flagIDs)
                ->where('StryStatus', $GLOBALS["SL"]->getDefID('Story Status', 'Published'))
                ->select('StryID')
                ->get();
            $flagged = sizeof($chk); */
            $treeMenu = $this->loadAdmMenuBasics();
            unset($treeMenu[3][4]);
            if ($this->v["user"]->hasRole('administrator|staff|databaser')) {
                return [
                    [
                        'javascript:;',
                        '<i class="fa fa-star mR5"></i> Complaints',
                        1,
                        [
                            $this->admMenuLnk('/dashboard/complaints/all',
                                '<span class="label label-primary mR5">' . $published . '</span> All Published'), 
                            $this->admMenuLnk('/dashboard/complaints/flagged', 
                                (($flagged > 0) ? '<span class="label label-danger mR5">' . $flagged . '</span> ' : '') 
                                    . 'Flagged For Review'), 
                            $this->admMenuLnk('/dashboard/complaints/unpublished', 'Un-Published Complaints'), 
                            $this->admMenuLnk('/dashboard/complaints/incomplete', 'Incomplete Complaints'),
                            $this->admMenuLnk('/dashboard/emails', 'Manage Email Templates'),
                            $this->admMenuLnkContact(false)
                        ]
                    ], [
                        'javascript:;',
                        '<i class="fa fa-users mR5"></i> Volunteers',
                        1,
                        [
                            $this->admMenuLnk('/dashboard/volun', 'Recent Department Edits'), 
                            $this->admMenuLnk('/dashboard/volun/stars', 'List of Volunteers'), 
                            $this->admMenuLnk('/dashboard/volunteer', 'Departments Dashboard'), 
                            $this->admMenuLnk('/dashboard/volunteer/nextDept', 'Verify A Department')
                        ]
                    ], [
                        'javascript:;',
                        '<i class="fa fa-list-ul mR5"></i> Directories',
                        1,
                        [
                            $this->admMenuLnk('/dashboard/officers', 'Police Officers'), 
                            $this->admMenuLnk('/dashboard/depts', 'Police Departments'), 
                            $this->admMenuLnk('/dashboard/overs', 'Oversight Agencies'), 
                            $this->admMenuLnk('/dashboard/volunteer/legal', 'Attorneys')
                        ]
                    ], 
                    $treeMenu
                ];
            } elseif ($this->v["user"]->hasRole('volunteer')) {
                return [
                    $this->admMenuLnk('/dashboard/volunteer', 'Police Departments'), 
                    $this->admMenuLnk('/dashboard/volunteer/nextDept', 'Verify A Dept.'), 
                    $this->admMenuLnk('/dashboard/volunteer/stars', 'You Have [[score]] Stars')
                ];
            }
        }
        return [
            [
                '/dashboard',
                'Dashboard',
                1,
                []
            ]
        ];
    }
    
    protected function tweakAdmMenu($currPage = '')
    {
        /*
        if (strpos($currPage, '/dashboard/complaint/' . $this->coreID . '') !== false 
            && $this->v["settings"]["Complaint Evaluations"] == 'Y') {
            $this->admMenuData = [
                "adminNav" => [],
                "currNavPos" => []
            ];
            $this->admMenuData["adminNav"] = [
                [
                    '/dashboard/complaints',
                    'Complaints <span class="pull-right"><i class="fa fa-star"></i></span>',
                    1,
                    []
                ], [
                    'javascript:;',
                    'Complaint #' . $this->coreID,
                    1,
                    [
                        [
                            '/dashboard/complaint/' . $this->coreID,
                            'View Complaint',
                            1,
                            []
                        ], [
                            '/dashboard/complaint/' . $this->coreID . '/review',
                            'Review This Complaint',
                            1,
                            []
                        ], [
                            '/dashboard/complaint/' . $this->coreID . '/emails',
                            'Send Complaint Emails',
                            1,
                            []
                        ], [
                            '/dashboard/complaint/' . $this->coreID . '/update',
                            'Update Complaint Status',
                            1,
                            []
                        ], [
                            '/dashboard/complaint/' . $this->coreID . '/history',
                            'View Past Reviews',
                            1,
                            []
                        ] 
                    ]
                ]
            ];
        } else
        */
        if (isset($this->v["deptSlug"])) {
            if ($this->v["user"]->hasRole('administrator|staff|databaser')) {
                $this->admMenuData["currNavPos"] = [1, 3, -1, -1];
            } else {
                $this->admMenuData["currNavPos"] = [0, -1, -1, -1];
            }
            $volunteeringSubMenu = [
                'javascript:;" id="navBtnContact0',
                '<b>Verifying Department</b>',
                1,
                [
                    [
                        'javascript:;" id="navBtnContact',
                        '&nbsp;&nbsp;Contact Info <div id="currContact" class="disIn pull-right mL20">'
                            . '<i class="fa fa-chevron-right"></i></div>',
                        1,
                        []
                    ], [
                        'javascript:;" id="navBtnWeb',
                        '&nbsp;&nbsp;Web & Complaints <div id="currWeb" class="disNon pull-right mL20">'
                             . '<i class="fa fa-chevron-right"></i></div>',
                        1,
                        []
                    ], [
                        'javascript:;" id="navBtnIA',
                        '&nbsp;&nbsp;Internal Affairs <div id="currIA" class="disNon pull-right mL20">'
                            . '<i class="fa fa-chevron-right"></i></div>',
                        1,
                        []
                    ], [
                        'javascript:;" id="navBtnOver',
                        '&nbsp;&nbsp;Civilian Oversight <div id="currOver" class="disNon pull-right mL20">'
                            . '<i class="fa fa-chevron-right"></i></div>',
                        1,
                        []
                    ], [
                        'javascript:;" id="navBtnSave',
                        '<span class="btn btn-lg btn-primary">Save All Changes <i class="fa fa-floppy-o"></i></span>', 
                        1,
                        []
                    ], [
                        'javascript:;" id="navBtnEdits',
                        '<span class="gry9">&nbsp;&nbsp;Past Edits:</span> ' 
                            . ((isset($this->v["editsSummary"][1])) ? $this->v["editsSummary"][1]: '') . '<div id="currEdits" '
                            . 'class="disNon pull-right mL20"><i class="fa fa-chevron-right"></i></div>',
                        1,
                        []
                    ], [
                        'javascript:;" id="navBtnCheck',
                        '<span class="gry9">&nbsp;&nbsp;Volunteer Checklist</span> <div id="currCheck" '
                            . 'class="disNon pull-right mL20"><i class="fa fa-chevron-right"></i></div>',
                        1,
                        []
                    ], [
                        'javascript:;" id="navBtnPhone',
                        '<span class="gry9">&nbsp;&nbsp;Sample Phone Script</span>',
                        1,
                        []
                    ], [
                        'javascript:;" id="navBtnFAQ',
                        '<span class="gry9">&nbsp;&nbsp;Frequently Asked <i class="fa fa-question"></i>s</span> <div '
                            . 'id="currFAQ" class="disNon pull-right mL20"><i class="fa fa-chevron-right"></i></div>',
                        1,
                        []
                    ]
                ]
            ];
            if ($this->v["user"]->hasRole('administrator|staff|databaser')) {
                $this->admMenuData["adminNav"][1][3][3] = $volunteeringSubMenu;
            } else { // is Volunteer
                $volunteeringSubMenu[1] .= ' <span class="pull-right"><i class="fa fa-check"></i></span>';
                $this->admMenuData["adminNav"][0] = $volunteeringSubMenu;
            }
        }
        if (!$this->v["user"]->hasRole('administrator|staff|databaser')) {
            if (isset($this->admMenuData["adminNav"][1]) && isset($this->admMenuData["adminNav"][1][1])) {
                if (!isset($this->v["yourUserInfo"]->UserInfoStars)) {
                    $this->admMenuData["adminNav"][1][1] = 0;
                } else {
                    $this->admMenuData["adminNav"][1][1] = str_replace('[[score]]', 
                        intVal($this->v["yourUserInfo"]->UserInfoStars), $this->admMenuData["adminNav"][1][1]);
                }
            }
        }
        
        $this->getAdmMenuLoc($currPage);
        return true;
    }
    
    protected function initExtra(Request $request)
    {
        $this->CustReport = new OpenPoliceReport($request);
        
        if (!isset($this->v["currPage"])) $this->v["currPage"] = '/dashboard';
        if (trim($this->v["currPage"]) == '') $this->v["currPage"] = '/dashboard';
        $this->v["allowEdits"] = ($this->v["user"]->hasRole('administrator|staff'));
        
        $this->v["management"] = ($this->v["user"]->hasRole('administrator|staff'));
        $this->v["volunOpts"] = 1;
        if ($GLOBALS["SL"]->REQ->session()->has('volunOpts')) {
            $this->v["volunOpts"] = $GLOBALS["SL"]->REQ->session()->get('volunOpts');
        }
        $this->v["ways"] = [
            'Online-Submittable Form', 
            'Submit via Email Allowed', 
            'Verbally on Phone Allowed', 
            'Paper Form via Snail Mail Allowed', 
            'Requires In-Person Visit', 
            'Official Form NOT Required for Investigation', 
            'Anonymous Complaints Investigated', 
            'Requires Notary (for any type of complaint)', 
        ];
        $this->v["waysFlds"] = [
            'OverWaySubOnline', 
            'OverWaySubEmail', 
            'OverWaySubVerbalPhone', 
            'OverWaySubPaperMail', 
            'OverWaySubPaperInPerson', 
            'OverOfficialFormNotReq', 
            'OverOfficialAnon', 
            'OverWaySubNotary', 
        ];
        $this->v["wayPoints"] = [30, 15, 3, 2, 0, 15, 15, -10];
        $this->v["deptPoints"] = [
            "Website"              => 5, 
            "FB"                   => 5, 
            "Twit"                 => 5, 
            "YouTube"              => 5, 
            "ComplaintInfo"        => 20, 
            "ComplaintInfoHomeLnk" => 15, 
            "FormPDF"              => 15
        ];
        return true;
    }
    
    public function dashboardDefault(Request $request)
    {
        $user = Auth::user();
        if (!$user->hasRole('administrator|staff|databaser|brancher')) {
            if ($user->hasRole('volunteer')) {
                return $this->redir('/dashboard/volunteer');
            }
            return $this->redir('/');
        }
        return $this->redir( '/dashboard/complaints' );
    }
    
    protected function loadSearchSuggestions()
    {    
        $this->v["searchSuggest"] = [];
        $deptCitys = OPDepartments::select('DeptAddressCity')->distinct()->get();
        if ($deptCitys && sizeof($deptCitys) > 0) {
            foreach ($deptCitys as $dept) {
                if (!in_array($dept->DeptAddressCity, $this->v["searchSuggest"]) && $dept->DeptAddressCounty) {
                    $this->v["searchSuggest"][] = json_encode($dept->DeptAddressCity);
                }
            }
        }
        $deptCounties = OPDepartments::select('DeptAddressCounty')->distinct()->get();
        if ($deptCounties && sizeof($deptCounties) > 0) {
            foreach ($deptCounties as $dept) {
                if (!in_array($dept->DeptAddressCounty, $this->v["searchSuggest"]) && $dept->DeptAddressCounty) {
                    $this->v["searchSuggest"][] = json_encode($dept->DeptAddressCounty);
                }
            }
        }
        return true;
    }
    
    public function listComplaints(Request $request)
    {
        $this->admControlInit($request, '/dashboard/complaints');
        return $this->printComplaintListing($request);
    }
    
    public function listAll(Request $request)
    {
        $this->admControlInit($request, '/dashboard/complaints/all');
        return $this->printComplaintListing($request);
    }
    
    public function listMine(Request $request)
    {
        $this->admControlInit($request, '/dashboard/complaints/me');
        return $this->printComplaintListing($request);
    }
    
    public function listWaiting(Request $request)
    {
        $this->admControlInit($request, '/dashboard/complaints/waiting');
        return $this->printComplaintListing($request);
    }
    
    public function listIncomplete(Request $request)
    {
        $this->admControlInit($request, '/dashboard/complaints/incomplete');
        return $this->printComplaintListing($request);
    }
    
    public function listUnpublished(Request $request)
    {
        $this->admControlInit($request, '/dashboard/complaints/unpublished');
        return $this->printComplaintListing($request);
    }
    
    public function listFlagged(Request $request)
    {
        $this->admControlInit($request, '/dashboard/complaints/flagged');
        return $this->printComplaintListing($request);
    }
    
    protected function printComplaintListing(Request $request)
    {
        $sort = "date";
        $qman = "SELECT c.*, p.`PrsnNameFirst`, p.`PrsnNameLast`, i.* 
            FROM `OP_Complaints` c 
            JOIN `OP_Incidents` i ON c.`ComIncidentID` LIKE i.`IncID` 
            LEFT OUTER JOIN `OP_Civilians` civ ON c.`ComID` LIKE civ.`CivComplaintID` 
            LEFT OUTER JOIN `OP_PersonContact` p ON p.`PrsnID` LIKE civ.`CivPersonID` 
            WHERE civ.`CivIsCreator` LIKE 'Y' ";
        switch ($this->v["currPage"]) {
            case '/dashboard/complaints':         
                $qman .= " AND (c.`ComStatus` LIKE '" . $GLOBALS["SL"]->getDefID('Complaint Status', 'New') . "' 
                    OR (c.`ComType` IN (
                    '" . $GLOBALS["SL"]->getDefID('OPC Staff/Internal Complaint Type', 'Unreviewed') . "', 
                    '" . $GLOBALS["SL"]->getDefID('OPC Staff/Internal Complaint Type', 'Not Sure') . "'
                    ) AND c.`ComStatus` NOT LIKE '" 
                    . $GLOBALS["SL"]->getDefID('Complaint Status', 'Incomplete') . "') )"; 
                break;
            case '/dashboard/complaints/me':     
                $qman .= " AND c.`ComAdminID` LIKE '" . $this->v["user"]->id . "' 
                    AND c.`ComStatus` NOT LIKE '" . $GLOBALS["SL"]->getDefID('Complaint Status', 'Incomplete') . "'";
                break;
            case '/dashboard/complaints/waiting':     
                $qman .= " AND (c.`ComStatus` IN (
                    '" . $GLOBALS["SL"]->getDefID('Complaint Status', 'Attorney\'d') . "', 
                    '" . $GLOBALS["SL"]->getDefID('Complaint Status', 'Submitted to Oversight') . "', 
                    '" . $GLOBALS["SL"]->getDefID('Complaint Status', 'Received by Oversight') . "', 
                    '" . $GLOBALS["SL"]->getDefID('Complaint Status', 'Pending Oversight Investigation') . "'
                    ) )"; 
                break;
            case '/dashboard/complaints/all':     
                $qman .= " AND c.`ComStatus` NOT LIKE '" 
                    . $GLOBALS["SL"]->getDefID('Complaint Status', 'Incomplete') . "'";
                break;
            case '/dashboard/complaints/incomplete':     
                $qman .= " AND c.`ComStatus` LIKE '" 
                    . $GLOBALS["SL"]->getDefID('Complaint Status', 'Incomplete') . "'";
                break;
        }
        $this->v["complaints"] = $this->v["comInfo"] = [];
        $compls = DB::select( DB::raw($qman) );
        if ($compls && sizeof($compls) > 0) {
            foreach ($compls as $com) {
                $comTime = strtotime($com->updated_at);
                if (trim($com->ComRecordSubmitted) != '' && $com->ComRecordSubmitted != '0000-00-00 00:00:00') {
                    $comTime = strtotime($com->ComRecordSubmitted);
                }
                $sortInd = $comTime;
                $this->v["comInfo"][$com->ComID] = ["alleg" => '', "comDate" => ''];
                $allegsTmp = [];
                $chkAlleg = OPAllegations::select('AlleType')
                    ->where('AlleComplaintID', $com->ComID)
                    ->get();
                if ($chkAlleg && sizeof($chkAlleg) > 0) {
                    if ($sort == 'Allegations') $sortInd = sizeof($chkAlleg)+($sortInd/10000000000);
                    foreach ($chkAlleg as $alleg) $allegsTmp[] = $alleg;
                    $this->v["comInfo"][$com->ComID]["alleg"] = $this->CustReport->commaAllegationList();
                }
                $this->v["comInfo"][$com->ComID]["comDate"] = date("n/j/Y", $comTime);
                
                //$this->v["comInfo"][$com->ComID]["investigability"] = ...
                //$this->v["comInfo"][$com->ComID]["rating"] = ...
                //$this->v["comInfo"][$com->ComID]["featured"] = ...
                
                $this->v["complaints"][$sortInd] = $com;
            }
            krsort($this->v["complaints"]);
        }
        return view('vendor.openpolice.admin.complaints.complaints-listing', $this->v);
    }
    
    function clearIncompletes()
    {
        /*
        if (!isset($_SESSION["clearIncompletes"])) {
            $qmen = $delComs = [];
            $qman = "SELECT * FROM `Complaints` WHERE `ComCreated` < '" . date("Y-m-d 00:00:00", mktime(0, 0, 0, date("n"), date("j")-2, date("Y"))) . "' AND (`ComSubmissionProgress` < '1' OR `ComSummary` IS NULL)";
            $chk = mysqli_query($GLOBALS["SL"], $qman); // echo $qman . '<br />';
            if ($chk && mysqli_num_rows($chk) > 0) { 
                while ($row = mysqli_fetch_array($chk)) { $delComs[] = $row["ComID"]; }
                $qmen[] = "DELETE FROM `Civilians` WHERE `CivComplaintID` IN ('" . implode("', '", $delComs) . "')";
                $qmen[] = "DELETE FROM `Scenes` WHERE `ScnComplaintID` IN ('" . implode("', '", $delComs) . "')";
                $qmen[] = "DELETE FROM `Incidents` WHERE `IncComplaintID` IN ('" . implode("', '", $delComs) . "')";
                $qmen[] = "DELETE FROM `Complaints` WHERE `ComID` IN ('" . implode("', '", $delComs) . "')";
            }
            $qmen[] = "DELETE FROM `AuthUser` WHERE `UserCreated` < '" . date("Y-m-d 00:00:00", mktime(0, 0, 0, date("n"), date("j")-2, date("Y"))) . "' AND `UserName` IS NULL AND `UserPassword` IS NULL AND `UserID` NOT IN (SELECT `CivUserID` FROM `Civilians`)";
            foreach ($qmen as $qman) {
                $chk = mysqli_query($GLOBALS["SL"], $qman); // echo $qman . '<br />';
            }
            $_SESSION["clearIncompletes"] = true;
        }
        */
    }
    
    
    
    public function complaintView(Request $request, $cid, $viewType = 'view') 
    {
        $this->v["cID"] = $this->coreID = $cid;
        $currPage = '/dashboard/complaint/' . $cid . (($viewType == 'view') ? '' : '/'.$viewType);
        $this->admControlInit($request, $currPage);
        $this->v["firstRevDone"] = false;
        if ($request->has('firstReview') && intVal($request->firstReview) > 0) {
            $newTypeVal = $GLOBALS["SL"]->getDefValue('OPC Staff/Internal Complaint Type', $request->firstReview);
            $newReview = new OPzComplaintReviews;
            $newReview->ComRevComplaint = $cid;
            $newReview->ComRevUser      = $this->v["user"]->id;
            $newReview->ComRevDate      = date("Y-m-d H:i:s");
            $newReview->ComRevType      = 'First';
            $newReview->ComRevStatus    = $newTypeVal;
            $newReview->save();
            $com = OPComplaints::find($cid);
            $com->comType = $request->firstReview;
            $com->save();
            $this->v["firstRevDone"] = true;
        }
        /* if ($this->v["settings"]["Complaint Evaluations"] == 'N') {
            $currPage = '/dashboard/complaints/all';
            $this->setCurrPage($currPage);
        } */
        $this->v["viewType"] = $viewType;
        /* if ($viewType == 'review' && $this->v["settings"]["Complaint Evaluations"] == 'Y') {
            $this->v["admMenuHideable"] = true;
        } */
        $this->CustReport->loadAllSessData('Complaints', $cid);
        $this->v["fullReport"]   = $this->CustReport->printAdminReport($cid, $viewType);
        $this->v["complaintRec"] = $this->CustReport->sessData->dataSets["Complaints"][0];
        $this->v["firstReview"]  = true;
        $this->v["lastReview"]   = true;
        $this->v["history"]      = [];
        $allUserNames = [];
        $reviews = OPzComplaintReviews::where('ComRevComplaint', '=', $cid)
            ->where('ComRevType', 'NOT LIKE', 'Draft')
            ->orderBy('ComRevDate', 'desc')
            ->get();
        if ($reviews && sizeof($reviews) > 0) {
            foreach ($reviews as $i => $r) {
                if ($i == 0) $this->v["lastReview"] = $r;
                $this->v["firstReview"] = false;
                if (!isset($allUserNames[$r->ComRevUser])) {
                    $allUserNames[$r->ComRevUser] = User::find($r->ComRevUser)
                        ->printUsername(true, '/dashboard/volun/user/');
                }
                $desc = '<span class="slBlueDark">' . $r->ComRevStatus . '</span>';
                if (isset($r->ComRevNote) && trim($r->ComRevNote) != '') {
                    $desc .= ' - ' . $r->ComRevNote;
                }
                $this->v["history"][] = [
                    "type" => 'Status', 
                    "date" => strtotime($r->ComRevDate), 
                    "desc" => $desc, 
                    "who"  => $allUserNames[$r->ComRevUser]
                ];
            }
        }
        $this->v["emailList"] = SLEmails::orderBy('EmailName', 'asc')
            ->orderBy('EmailType', 'asc')
            ->get();
        $emails = SLEmailed::where('EmailedTree', 1)
            ->where('EmailedRecID', $cid)
            ->orderBy('EmailedDate', 'asc')
            ->get();
        if ($emails && sizeof($emails) > 0) {
            foreach ($emails as $i => $e) {
                if (!isset($allUserNames[$e->EmailedFromUser])) {
                    $allUserNames[$e->EmailedFromUser] = User::find($e->EmailedFromUser)
                        ->printUsername(true, '/dashboard/volun/user/');
                }
                $desc = '<a href="javascript:void(0)" id="emaSubj' . $e->EmailedID . '" class="emaSubj">';
                if ($this->v["emailList"] && sizeof($this->v["emailList"]) > 0) {
                    foreach ($this->v["emailList"] as $ema) {
                        if ($e->EmailedEmailID == $ema->EmailID) $desc = $ema->EmailSubject;
                    }
                }
                $desc = '</a><div id="emaBody' . $e->EmailedID . '" class="disNon p10">' . $e->EmailedBody . '</div>';
                $this->v["history"][] = [
                    "type" => 'Email', 
                    "date" => strtotime($e->EmailDate), 
                    "desc" => $desc, 
                    "who"  => $allUserNames[$e->EmailedFromUser]
                ];
            }
        }
        $this->v["history"] = $GLOBALS["SL"]->sortArrByKey($this->v["history"], 'date', 'desc');
        $this->prepEmailComData();
        $isOverCompatible = $this->CustReport->isOverCompatible($this->v["comDepts"][0][
                            $this->v["comDepts"][0]["whichOver"]]);
        $this->v["emailsTo"] = [ "To Complainant" => [], "To Oversight" => [] ];
        $complainantUser = User::find($this->v["complaintRec"]->ComUserID);
        if ($complainantUser && isset($complainantUser->email)) {
            $name = $complainantUser->name;
            if (isset($this->CustReport->sessData->dataSets["PersonContact"])
                && sizeof($this->CustReport->sessData->dataSets["PersonContact"]) > 0
                && isset($this->CustReport->sessData->dataSets["PersonContact"][0]->PrsnNameFirst)) {
                $name = $this->CustReport->sessData->dataSets["PersonContact"][0]->PrsnNameFirst . ' '
                    . $this->CustReport->sessData->dataSets["PersonContact"][0]->PrsnNameLast;
            }
            $this->v["emailsTo"]["To Complainant"][] = [ $complainantUser->email, $name, true ];
        }
        if ($isOverCompatible) {
            $this->v["emailsTo"]["To Oversight"][] = [
                $this->v["comDepts"][0][$this->v["comDepts"][0]["whichOver"]]->OverEmail,
                $this->v["comDepts"][0][$this->v["comDepts"][0]["whichOver"]]->OverAgncName,
                true
            ];
        }
        $this->v["emailMap"] = [ // 'Review Status' => Email ID#
                'Submitted to Oversight'    => [7, 12], 
                'Hold: Go Gold'             => [6],
                'Pending Attorney: Needed'  => [17],
                'Pending Attorney: Hook-Up' => [18]
            ];
        $this->v["emailID"] = ($request->has('email') ? $request->email : -3);
        if ($this->v["emailID"] <= 0) {
            if (!$emails || sizeof($emails) == 0) {
                switch ($this->v["complaintRec"]->ComStatus) {
                    case $GLOBALS["SL"]->getDefID('Complaint Status', 'OK to Submit to Oversight'):
                        if ($isOverCompatible) {
                            $this->v["emailID"] = 12; // Send to oversight agency
                        } else {
                            $this->v["emailID"] = 9; // How to manually submit
                        }
                        break;
                    case $GLOBALS["SL"]->getDefID('Complaint Status', 'Submitted to Oversight'):
                        
                        break;
                }
            }
        }
        
        $this->v["currEmail"] = [];
        if (isset($this->CustReport->sessData->dataSets["LinksComplaintDept"]) 
            && sizeof($this->CustReport->sessData->dataSets["LinksComplaintDept"]) > 0) {
            foreach ($this->CustReport->sessData->dataSets["LinksComplaintDept"] as $deptLnk) {
                $this->CustReport->loadDeptStuff($deptLnk->LnkComDeptDeptID);
                $this->v["currEmail"][] = $this->processEmail($this->v["emailID"], $deptLnk->LnkComDeptDeptID);
            }
        }
        if (sizeof($this->v["currEmail"]) > 0) { 
            foreach ($this->v["currEmail"] as $j => $email) {
                $GLOBALS["SL"]->pageJAVA .= 'CKEDITOR.replace( "emailBodyCust' . $j . 'ID", {
                    customConfig: "/survloop/ckeditor-config.js" } );';
            }
        }
        $GLOBALS["SL"]->pageAJAX .= '$("#legitTypeBtn").click(function(){ $("#legitTypeDrop").slideToggle("fast"); });
        $("#newStatusUpdate").click(function(){ $("#newStatusUpdateBlock").slideToggle("fast"); });
        $("#newEmails").click(function(){ $("#analystEmailer").slideToggle("fast"); });
        ' . (($viewType == 'update') ? 'window.location = "#new"; ' : '');
        $this->v["needsWsyiwyg"] = true;
        return view('vendor.openpolice.admin.complaints.complaint-review', $this->v);
    }
    
    public function prepEmailComData()
    {
        $cnt = 0;
        $this->v["comDepts"] = [];
        //echo '<pre>'; print_r($this->CustReport->sessData->dataSets["LinksComplaintDept"]); echo '</pre>';
        if (sizeof($this->CustReport->sessData->dataSets["LinksComplaintDept"]) > 0) {
            foreach ($this->CustReport->sessData->dataSets["LinksComplaintDept"] as $i => $lnk) {
                $this->v["comDepts"][$cnt] = [ "id" => $lnk->LnkComDeptDeptID ];
                $this->v["comDepts"][$cnt]["deptRow"] = OPDepartments::find($lnk->LnkComDeptDeptID)->first();
                $this->v["comDepts"][$cnt]["iaRow"] = OPOversight::where('OverDeptID', $lnk->LnkComDeptDeptID)
                    ->where('OverType', $GLOBALS["SL"]->getDefID('Oversight Agency Types', 'Internal Affairs'))
                    ->first();
                $this->v["comDepts"][$cnt]["civRow"] = OPOversight::where('OverDeptID', $lnk->LnkComDeptDeptID)
                    ->where('OverType', $GLOBALS["SL"]->getDefID('Oversight Agency Types', 'Civilian Oversight'))
                    ->first();
                if (!isset($this->v["comDepts"][$cnt]["iaRow"]) || sizeof($this->v["comDepts"][$cnt]["iaRow"]) == 0) {
                    $this->v["comDepts"][$cnt]["iaRow"] = new OPOversight;
                    $this->v["comDepts"][$cnt]["iaRow"]->OverDeptID = $lnk->LnkComDeptDeptID;
                    $this->v["comDepts"][$cnt]["iaRow"]->OverType
                        = $GLOBALS["SL"]->getDefID('Oversight Agency Types', 'Internal Affairs');
                    $this->v["comDepts"][$cnt]["iaRow"]->OverAgncName
                        = $this->v["comDepts"][$cnt]["deptRow"]->DeptName;
                    $this->v["comDepts"][$cnt]["iaRow"]->OverAddress
                        = $this->v["comDepts"][$cnt]["deptRow"]->DeptAddress;
                    $this->v["comDepts"][$cnt]["iaRow"]->OverAddress2
                        = $this->v["comDepts"][$cnt]["deptRow"]->DeptAddress2;
                    $this->v["comDepts"][$cnt]["iaRow"]->OverAddressCity
                        = $this->v["comDepts"][$cnt]["deptRow"]->DeptAddressCity;
                    $this->v["comDepts"][$cnt]["iaRow"]->OverAddressState
                        = $this->v["comDepts"][$cnt]["deptRow"]->DeptAddressState;
                    $this->v["comDepts"][$cnt]["iaRow"]->OverAddressZip
                        = $this->v["comDepts"][$cnt]["deptRow"]->DeptAddressZip;
                    $this->v["comDepts"][$cnt]["iaRow"]->OverPhoneWork
                        = $this->v["comDepts"][$cnt]["deptRow"]->DeptPhoneWork;
                    $this->v["comDepts"][$cnt]["iaRow"]->save();
                }
                $this->v["comDepts"][$cnt]["whichOver"] = '';
                if (isset($this->v["comDepts"][0]["civRow"]) 
                    && isset($this->v["comDepts"][0]["civRow"]->OverAgncName)) {
                    $this->v["comDepts"][$cnt]["whichOver"] = "civRow";
                } elseif (isset($this->v["comDepts"][0]["iaRow"]) 
                    && isset($this->v["comDepts"][0]["iaRow"]->OverAgncName)) {
                    $this->v["comDepts"][$cnt]["whichOver"] = "iaRow";
                }
                $this->v["comDepts"][$cnt]["overInfo"] 
                    = $this->getOversightInfo($this->v["comDepts"][$cnt][$this->v["comDepts"][$cnt]["whichOver"]]);
                $cnt++;
            }
        }
        return true;
    }
    
    public function getOversightInfo($overRow)
    {
        $ret = '';
        if ($overRow && isset($overRow->OverAgncName) && trim($overRow->OverAgncName) != '') {
            $ret .= '<b>' . $overRow->OverAgncName . '</b><br />';
            if (isset($overRow->OverWebsite) && trim($overRow->OverWebsite) != '') {
                $ret .= '<a href="' . $overRow->OverWebsite . '" target="_blank">' . $overRow->OverWebsite 
                    . '</a><br />';
            }
            if (isset($overRow->OverFacebook) && trim($overRow->OverFacebook) != '') {
                $ret .= '<a href="' . $overRow->OverFacebook . '" target="_blank">' . $overRow->OverFacebook 
                    . '</a><br />';
            }
            if (isset($overRow->OverTwitter) && trim($overRow->OverTwitter) != '') {
                $ret .= '<a href="' . $overRow->OverTwitter . '" target="_blank">' . $overRow->OverTwitter 
                    . '</a><br />';
            }
            if (isset($overRow->OverYouTube) && trim($overRow->OverYouTube) != '') {
                $ret .= '<a href="' . $overRow->OverYouTube . '" target="_blank">' . $overRow->OverYouTube 
                    . '</a><br />';
            }
            if (isset($overRow->OverPhoneWork) && trim($overRow->OverPhoneWork) != '') {
                $ret .= $overRow->OverPhoneWork . '<br />';
            }
            if (isset($overRow->OverAddress) && trim($overRow->OverAddress) != '') {
                $ret .= $overRow->OverAddress . '<br />';
                if (isset($overRow->OverAddress2) && trim($overRow->OverAddress2) != '') {
                    $ret .= $overRow->OverAddress2 . '<br />';
                }
                $ret .= $overRow->OverAddressCity . ', ' . $overRow->OverAddressState . ' ' 
                    . $overRow->OverAddressZip . '<br />';
            }
            $ret .= '<br />';
            if (isset($overRow->OverWaySubOnline) && intVal($overRow->OverWaySubOnline) == 1
                && isset($overRow->OverComplaintWebForm) && trim($overRow->OverComplaintWebForm) != '') {
                $ret .= 'You can submit your complaint through your oversight agency\'s online complaint form. '
                    . 'Pro Tip: Somewhere in their official form, include a link to your OPC complaint.<br /><a href="'
                    . $overRow->OverComplaintWebForm . '" target="_blank">' . $overRow->OverComplaintWebForm 
                    . '</a><br /><br />';
            }
            if (isset($overRow->OverWaySubEmail) && intVal($overRow->OverWaySubEmail) == 1
                && isset($overRow->OverEmail) && trim($overRow->OverEmail) != '') {
                $ret .= 'You can submit your complaint by emailing your oversight agency. We recommend you '
                    . 'include a link to your OPC complaint in your email.<br />'
                    . '<a href="mailto:' . $overRow->OverEmail . '">' . $overRow->OverEmail . '</a><br /><br />';
            }
            if (isset($overRow->OverComplaintPDF) && trim($overRow->OverComplaintPDF) != '') {
                $ret .= 'You can print out and use your oversight agency\'s official complaint form online. '
                    . 'We recommend you also print your full OPC complaint and submit it along with their '
                    . 'official form.<br /><a href="' . $overRow->OverComplaintPDF . '" target="_blank">' 
                    . $overRow->OverComplaintPDF . '</a><br /><br />';
            }
            $ret .= '<i>More about this complaint process:</i><br />';
            if (isset($overRow->OverWebComplaintInfo) && trim($overRow->OverWebComplaintInfo) != '') {
                $ret .= '<a href="' . $overRow->OverWebComplaintInfo . '" target="_blank">' 
                    . $overRow->OverWebComplaintInfo . '</a><br />';
            }
            if (!isset($overRow->OverOfficialFormNotReq) || intVal($overRow->OverOfficialFormNotReq) == 0) {
                $ret .= 'Only complaints submitted on official forms will be investigated.<br />'; 
            }
            if (isset($overRow->OverWaySubNotary) && intVal($overRow->OverWaySubNotary) == 1) {
                $ret .= 'Submitted complaints may require a notary to be investigated.<br />'; 
            }
            if (!isset($overRow->OverOfficialAnon) || intVal($overRow->OverOfficialAnon) == 0) {
                $ret .= 'Anonymous complaints will not be investigated.<br />'; 
            }
            if (isset($overRow->OverWaySubVerbalPhone) && intVal($overRow->OverWaySubVerbalPhone) == 1) {
                $ret .= 'Complaints submitted by phone will be investigated.<br />'; 
            }
            if (isset($overRow->OverWaySubPaperMail) && intVal($overRow->OverWaySubPaperMail) == 1) {
                $ret .= 'Complaints submitted by snail mail will be investigated.<br />'; 
            }
            if (isset($overRow->OverWaySubPaperInPerson) && intVal($overRow->OverWaySubPaperInPerson) == 1) {
                $ret .= 'Complaints submitted in person will be investigated.<br />'; 
            }
            if (isset($overRow->OverSubmitDeadline) && intVal($overRow->OverSubmitDeadline) > 0) {
                $ret .= 'Complaints must be submitted within ' . number_format($overRow->OverSubmitDeadline) 
                    . ' days of your incident to be investigated.<br />'; 
            }
        }
        return $ret;
    }
    
    public function processEmail($emailID, $deptID = -3)
    {
        $email = [ "rec" => false, "body" => '', "subject" => '' ];
        if ($emailID > 0) {
            if (sizeof($this->v["emailList"]) > 0) {
                foreach ($this->v["emailList"] as $e) {
                    if ($e->EmailID == $emailID) $email["rec"] = $e;
                }
                if ($email["rec"] !== false && isset($email["rec"]->EmailBody) 
                    && trim($email["rec"]->EmailBody) != '') {
                    $email["body"] = $GLOBALS["SL"]->swapEmailBlurbs($email["rec"]->EmailBody);
                    $email["body"] = $this->CustReport->sendEmailBlurbsCustom($email["body"], $deptID);
                    $email["subject"] = $GLOBALS["SL"]->swapEmailBlurbs($email["rec"]->EmailSubject);
                    $email["subject"] = $this->CustReport->sendEmailBlurbsCustom($email["subject"], $deptID);
                }
            }
        }
        return $email;
    }
    
    public function complaintReview(Request $request, $cid) 
    {
        return $this->complaintView($request, $cid, 'review');
    }
    
    public function complaintEmails(Request $request, $cid) 
    {
        return $this->complaintView($request, $cid, 'emails');
    }
    
    public function complaintEmailsType(Request $request, $cid) 
    {
        return $this->complaintView($request, $cid, 'emailsType');
    }
    
    public function complaintUpdate(Request $request, $cid) 
    {
        return $this->complaintView($request, $cid, 'update');
    }
    
    public function complaintHistory(Request $request, $cid) 
    {
        return $this->complaintView($request, $cid, 'history');
    }
    
    public function complaintReviewPost(Request $request, $cid) 
    {
        $this->admControlInit($request, '/dashboard/complaint/' . $cid . '/review');
        $this->CustReport->loadSessionData('Complaints', $cid);
        if ($request->has('cID') && intVal($request->cID) > 0 && intVal($request->cID) == $cid) {
            $newReview = new OPzComplaintReviews;
            $newReview->ComRevComplaint    = $cid;
            $newReview->ComRevUser         = $this->v["user"]->id;
            $newReview->ComRevDate         = date("Y-m-d H:i:s");
            $newReview->ComRevType         = $request->revType;
            $newReview->ComRevNote         = $request->revNote;
            $newReview->ComRevStatus       = $request->revStatus;
            $newReview->ComRevMakeFeatured = intVal($request->revMakeFeatured);
            if ($request->revType == 'Full') {
                OPzComplaintReviews::where('ComRevComplaint', '=', $cid)
                    ->where('ComRevUser', Auth::user()->id)
                    ->where('ComRevType', 'Full')
                    ->update([ "ComRevType" => 'Draft' ]);
                $newReview->ComRevComplaintType     = 296;
                if ((!$request->has('complaintLegit') || intVal($request->complaintLegit) == 0)
                    && $request->has('revComplaintType') && intVal($request->revComplaintType) > 0) {
                    $newReview->ComRevComplaintType = intVal($request->revComplaintType);
                }
                $newReview->ComRevNotAnon           = intVal($request->revNotAnon);
                $newReview->ComRevOneIncident       = intVal($request->revOneIncident);
                $newReview->ComRevCivilianContact   = intVal($request->revCivilianContact);
                $newReview->ComRevOneOfficer        = intVal($request->revOneOfficer);
                $newReview->ComRevOneAllegation     = intVal($request->revOneAllegation);
                $newReview->ComRevEvidenceUpload    = intVal($request->revEvidenceUpload);
                $newReview->ComRevEnglishSkill      = intVal($request->revEnglishSkill);
                $newReview->ComRevReadability       = $request->revReadability;
                $newReview->ComRevConsistency       = $request->revConsistency;
                $newReview->ComRevRealistic         = $request->revRealistic;
                $newReview->ComRevOutrage           = $request->revOutrage;
                $newReview->ComRevExplicitLang      = intVal($request->revExplicitLang);
                $newReview->ComRevGraphicContent    = intVal($request->revGraphicContent);
                $newReview->ComRevNextAction        = $request->revNextAction;
            } else {
                $newReview->ComRevComplaintType     = intVal($request->revComplaintType);
            }
            $newReview->save();
            
            $com = OPComplaints::find($cid);
            $com->comType = $newReview->ComRevComplaintType;
            if (in_array($newReview->ComRevStatus, ['Hold: Go Gold', 'Hold: Not Sure'])) {
                $com->comStatus = $GLOBALS["SL"]->getDefID('Complaint Status', 'Hold');
            } elseif (in_array($newReview->ComRevStatus, ['Pending Attorney: Needed', 'Pending Attorney: Hook-Up'])) {
                $com->comStatus = $GLOBALS["SL"]->getDefID('Complaint Status', 'Pending Attorney');
            } else {
                $com->comStatus = $GLOBALS["SL"]->getDefID('Complaint Status', $newReview->ComRevStatus);
            }
            $com->save();
        }
        //$this->CustReport->loadSessionData('Complaints');
        return $this->redir('/dashboard/complaint/' . $cid . '/history');
    }
    
    public function comStatus($defID)
    {
        return $GLOBALS["SL"]->getDefID('Complaint Status', $defID);
    }
    
    
    
    
    
    
    
    
    
    public function volunList(Request $request)
    {
        $this->admControlInit($request, '/dashboard/volun/stars');
        $this->v["printVoluns"] = array([], [], []); // voluns, staff, admin
        $this->v["leaderboard"] = new VolunteerLeaderboard;
        foreach ($this->v["leaderboard"]->UserInfoStars as $i => $stars) {
            $tmpArr = [$stars];
            $tmpArr[1] = User::find($stars->UserInfoUserID);
            if (isset($tmpArr[1]) && sizeof($tmpArr[1]) > 0) {
                list($na, $tmpArr[2]) = $this->initPowerUser($stars->UserInfoUserID);
                $list = 0;
                if ($tmpArr[1]->hasRole('administrator')) {
                    $list = 2;
                } elseif ($tmpArr[1]->hasRole('staff')) {
                    $list = 1;
                }
                $this->v["printVoluns"][$list][] = $tmpArr;
            }
        }
        return view('vendor.openpolice.admin.volun.volun', $this->v);
    }
        
    public function volunDepts(Request $request)
    {
        $this->admControlInit($request, '/dashboard/volun');
        
        $statTots = $statRanges = [];
        $statRanges[] = array('Last 24 Hours', " WHERE `EditDeptVerified` > '" 
            . date("Y-m-d H:i:s", mktime(date("H")-24, date("i"), date("s"), date("n"), date("j"), date("Y"))) . "'");
        $statRanges[] = array('This Week', " WHERE `EditDeptVerified` > '" 
            . date("Y-m-d H:i:s", mktime(date("H"), 0, 0, date("n"), date("j")-7, date("Y"))) . "'");
        $statRanges[] = array('All-Time Totals', "");
        foreach ($statRanges as $i => $stat) {
            $statTots[$i] = array( $stat[0] );
            $statTots[$i][] = sizeof( DB::select( DB::raw(
                "SELECT DISTINCT `EditDeptUser` FROM `OP_zVolunEditsDepts` ".$stat[1]) ) );
            $statTots[$i][] = sizeof( DB::select( DB::raw(
                "SELECT `EditDeptID` FROM `OP_zVolunEditsDepts` ".$stat[1]) ) );
            $overQry = ((strpos($stat[1], "WHERE") === false) 
                ? " WHERE `EditOverType` LIKE '303'" : " AND `EditOverType` LIKE '303'");
            $res = DB::select( DB::raw("SELECT SUM(`EditOverOnlineResearch`) as `tot` FROM `OP_zVolunEditsOvers` "
                . str_replace('EditDeptVerified', 'EditOverVerified', $stat[1]) . $overQry) );
            $statTots[$i][] = $res[0]->tot;
            $res = DB::select( DB::raw("SELECT SUM(`EditOverMadeDeptCall`) as `tot` FROM `OP_zVolunEditsOvers` "
                . str_replace('EditDeptVerified', 'EditOverVerified', $stat[1]) . $overQry) );
            $statTots[$i][] = $res[0]->tot;
            $res = DB::select( DB::raw("SELECT SUM(`EditOverMadeIACall`) as `tot` FROM `OP_zVolunEditsOvers` "
                . str_replace('EditDeptVerified', 'EditOverVerified', $stat[1]) . $overQry) );
            $statTots[$i][] = $res[0]->tot;
            $statTots[$i][] = sizeof( DB::select( DB::raw(
                "SELECT DISTINCT `EditDeptDeptID` FROM `OP_zVolunEditsDepts` ".$stat[1]) ) );
        }
        
        $deptEdits = [];
        $recentEdits = OPzVolunEditsDepts::take(100)
            ->orderBy('EditDeptVerified', 'desc')
            ->get();
        if ($recentEdits && sizeof($recentEdits) > 0) {
            foreach ($recentEdits as $i => $edit) {
                $iaEdit  = OPzVolunEditsOvers::where('EditOverEditDeptID', $edit->EditDeptID)
                    ->where('EditOverType', 303)
                    ->first();
                $civEdit = OPzVolunEditsOvers::where('EditOverEditDeptID', $edit->EditDeptID)
                    ->where('EditOverType', 302)
                    ->first();
                $userObj = User::find($edit->EditOverUser);
                $deptEdits[] = [
                    ($userObj && sizeof($userObj) > 0) ? $userObj->printUsername(true, '/dashboard/volun/user/') : '',
                    $edit, 
                    $iaEdit, 
                    $civEdit
                ];
            }
        }
        //echo '<pre>'; print_r($deptEdits); echo '</pre>';
        $this->v["statTots"] = $statTots;
        $this->v["recentEdits"] = '';
        foreach ($deptEdits as $deptEdit) {
            $this->v["recentEdits"] .= view('vendor.openpolice.volun.admPrintDeptEdit', [
                "user"     => $deptEdit[0], 
                "deptRow"  => OPDepartments::find($deptEdit[1]->EditDeptDeptID), 
                "deptEdit" => $deptEdit[1], 
                "deptType" => $GLOBALS["SL"]->getDefValue('Types of Departments', $deptEdit[1]->EditDeptType),
                "iaEdit"   => $deptEdit[2], 
                "civEdit"  => $deptEdit[3]
            ])->render();
        }
        
        $this->recalcVolunStats();
        $past = 60;
        $startDate = date("Y-m-d", mktime(0, 0, 0, date("n"), date("j")-$past, date("Y")));
        $this->v["statDays"] = OPzVolunStatDays::where('VolunStatDate', '>=', $startDate)
            ->orderBy('VolunStatDate', 'asc')
            ->get();
        $this->v["axisLabels"] = [];
        foreach ($this->v["statDays"] as $i => $s) {
            if ($i%5 == 0) {
                $this->v["axisLabels"][] = date('n/j', strtotime($s->VolunStatDate));
            } else {
                $this->v["axisLabels"][] = '';
            }
        }
        $lines = [];
        $lines[0] = [
            "label"     => 'Unique Departments', 
            "brdColor"     => '#2b3493', 
            "dotColor"     => 'rgba(75,192,192,1)', 
            "data"         => [], 
        ];
        foreach ($this->v["statDays"] as $s) $lines[0]["data"][] = $s->VolunStatDeptsUnique;
        $lines[1] = [
            "label"     => 'Unique Users', 
            "brdColor"     => '#63c6ff', 
            "dotColor"     => 'rgba(75,192,192,1)', 
            "data"         => [], 
        ];
        foreach ($this->v["statDays"] as $s) $lines[1]["data"][] = $s->VolunStatUsersUnique;
        $lines[2] = [
            "label"     => 'Total Edits', 
            "brdColor"     => '#c3ffe1', 
            "dotColor"     => 'rgba(75,192,192,1)', 
            "data"         => [], 
        ];
        foreach ($this->v["statDays"] as $s) $lines[2]["data"][] = $s->VolunStatTotalEdits;
        $lines[3] = [
            "label"     => 'Total Calls', 
            "brdColor"     => '#29B76F', 
            "dotColor"     => 'rgba(75,192,192,1)', 
            "data"         => [], 
        ];
        foreach ($this->v["statDays"] as $s) $lines[3]["data"][] = $s->VolunStatCallsTot;
        $lines[4] = [
            "label"     => 'Signups', 
            "brdColor"     => '#ffd2c9', 
            "dotColor"     => 'rgba(75,192,192,1)', 
            "data"         => [], 
        ];
        foreach ($this->v["statDays"] as $s) $lines[4]["data"][] = $s->VolunStatSignups;
        $this->v["dataLines"] = '';
        foreach ($lines as $l) {
            $this->v["dataLines"] .= view('vendor.survloop.graph-data-line', $l)->render();
        }
        return view('vendor.openpolice.admin.volun.volunDepts', $this->v);
    }
    
    public function volunStatsInitDay()
    {
        return [
            'signups'         => 0, 
            'logins'          => 0, 
            'usersUnique'     => 0, 
            'deptsUnique'     => 0, 
            'onlineResearch'  => 0, 
            'callsDept'       => 0, 
            'callsIA'         => 0, 
            'callsTot'        => 0, 
            'totalEdits'      => 0,
            'onlineResearchV' => 0, 
            'callsDeptV'      => 0, 
            'callsIAV'        => 0, 
            'callsTotV'       => 0, 
            'totalEditsV'     => 0,
            'users'           => [], 
            'depts'           => []
        ];
    }
    
    public function recalcVolunStats()
    {
        $past = 100;
        $startDate = date("Y-m-d", mktime(0, 0, 0, date("n"), date("j")-$past, date("Y")));
        $days = [];
        for ($i = 0; $i < $past; $i++) {
            $day = date("Y-m-d", mktime(0, 0, 0, date("n"), date("j")-$i, date("Y")));
            $days[$day] = $this->volunStatsInitDay();
        }
        
        $volunteers = [];
        $users = DB::table('users')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('SL_UsersRoles')
                    ->where('SL_UsersRoles.RoleUserRID', 17) // 'volunteer'
                    ->whereRaw('SL_UsersRoles.RoleUserUID = users.id');
            })
            ->get();
        if ($users && sizeof($users) > 0) {
            foreach ($users as $i => $u) {
                $volunteers[] = $u->id;
                if (strtotime($u->created_at) > strtotime($startDate)) {
                    $dataInd = date("Y-m-d", strtotime($u->created_at));
                    if (isset($days[$dataInd])) $days[$dataInd]["signups"]++;
                }
            }
        }
        
        
        $edits  = OPzVolunEditsOvers::where('EditOverType', 303)
            ->where('EditOverVerified', '>', date("Y-m-d", strtotime($startDate)).' 00:00:00')
            ->get();
        if ($edits && sizeof($edits) > 0) {
            foreach ($edits as $i => $e) {
                $day = date("Y-m-d", strtotime($e->EditOverVerified));
                if (!isset($days[$day])) $days[$day] = $this->volunStatsInitDay();
                $days[$day]["totalEdits"]++;
                $days[$day]["onlineResearch"]      += intVal($e->EditOverOnlineResearch);
                $days[$day]["callsDept"]           += intVal($e->EditOverMadeDeptCall);
                $days[$day]["callsIA"]             += intVal($e->EditOverMadeIACall);
                $days[$day]["callsTot"]            += intVal($e->EditOverMadeDeptCall) + intVal($e->EditOverMadeIACall);
                if (in_array($e->EditOverUser, $volunteers)) {
                    $days[$day]["totalEditsV"]++;
                    $days[$day]["onlineResearchV"] += intVal($e->EditOverOnlineResearch);
                    $days[$day]["callsDeptV"]      += intVal($e->EditOverMadeDeptCall);
                    $days[$day]["callsIAV"]        += intVal($e->EditOverMadeIACall);
                    $days[$day]["callsTotV"]       += intVal($e->EditOverMadeDeptCall) + intVal($e->EditOverMadeIACall);
                }
                if (!in_array($e->EditOverUser, $days[$day]["users"])) {
                    $days[$day]["users"][] = $e->EditOverUser;
                }
                if (!in_array($e->EditOverDeptID, $days[$day]["depts"])) {
                    $days[$day]["depts"][] = $e->EditOverDeptID;
                }
            }
        }
        
        OPzVolunStatDays::where('VolunStatDate', '>=', $startDate)->delete();
        foreach ($days as $day => $stats) {
            $newDay = new OPzVolunStatDays;
            $newDay->VolunStatDate            = $day;
            $newDay->VolunStatSignups         = $stats["signups"];
            $newDay->VolunStatLogins          = $stats["logins"];
            $newDay->VolunStatUsersUnique     = sizeof($stats["users"]);
            $newDay->VolunStatDeptsUnique     = sizeof($stats["depts"]);
            $newDay->VolunStatOnlineResearch  = $stats["onlineResearch"];
            $newDay->VolunStatCallsDept       = $stats["callsDept"];
            $newDay->VolunStatCallsIA         = $stats["callsIA"];
            $newDay->VolunStatCallsTot        = $stats["callsTot"];
            $newDay->VolunStatTotalEdits      = $stats["totalEdits"];
            $newDay->VolunStatOnlineResearchV = $stats["onlineResearchV"];
            $newDay->VolunStatCallsDeptV      = $stats["callsDeptV"];
            $newDay->VolunStatCallsIAV        = $stats["callsIAV"];
            $newDay->VolunStatCallsTotV       = $stats["callsTotV"];
            $newDay->VolunStatTotalEditsV     = $stats["totalEditsV"];
            $newDay->save();
        }
        
        return true;
    }
    
    public function listOfficers(Request $request)
    {
        $this->admControlInit($request, '/dashboard/officers');
        $this->v["officers"] = OPOfficers::get();
        return view('vendor.openpolice.admin.lists.officers', $this->v);
    }
    
    public function listDepts(Request $request)
    {
        $this->admControlInit($request, '/dashboard/depts');
        $this->v["deptComplaints"] = $this->v["deptAllegs"] = $deptList = [];
        $comDeptLnks = OPLinksComplaintDept::all();
        if (sizeof($comDeptLnks) > 0) {
            foreach ($comDeptLnks as $lnk) {
                if (!in_array($lnk->LnkComDeptDeptID, $deptList)) {
                    $deptList[] = $lnk->LnkComDeptDeptID;
                }
                if (!isset($this->v["deptComplaints"][$lnk->LnkComDeptDeptID])) {
                    $this->v["deptComplaints"][$lnk->LnkComDeptDeptID] = [];
                    $this->v["deptAllegs"][$lnk->LnkComDeptDeptID] = [];
                }
                $this->v["deptComplaints"][$lnk->LnkComDeptDeptID][] = $lnk->LnkComDeptComplaintID;
            }
        }
        $this->v["departments"] = OPDepartments::whereIn('DeptID', $deptList)->get();
        return view('vendor.openpolice.admin.lists.depts', $this->v);
    }
    
    public function listOvers(Request $request)
    {
        $this->admControlInit($request, '/dashboard/overs');
        $this->v["oversights"] = DB::table('OP_Oversight')
            ->where('OP_Oversight.OverType', 302)
            ->leftJoin('OP_Departments', 'OP_Departments.DeptID', '=', 'OP_Oversight.OverDeptID')
            ->orderBy('OP_Oversight.OverAddressState', 'asc')
            ->orderBy('OP_Oversight.OverAddressCity', 'asc')
            ->get();
        return view('vendor.openpolice.admin.lists.overs', $this->v);
    }
    
    public function quickAssign(Request $request)
    {
        $this->admControlInit($request);
        if ($request->OverID > 0 && $request->DeptID > 0) {
            $over = OPOversight::find($request->OverID);
            $over->OverDeptID = $request->DeptID;
            $over->save();
        }
        return $this->redir('/dashboard/overs#o'.$request->OverID);
    }
    
    public function listLegal(Request $request)
    {
        $this->admControlInit($request, '/dashboard/legal');
        $this->v["attorneys"] = OPCustomers::where('CustType', 'Attorney')->get();
        return view('vendor.openpolice.admin.lists.legal', $this->v);
    }
    
    public function listAcademic(Request $request)
    {
        $this->admControlInit($request, '/dashboard/academic');
        $this->v["academic"] = OPCustomers::where('CustType', 'Academic')->get();
        return view('vendor.openpolice.admin.lists.academic', $this->v);
    }
    
    public function listMedia(Request $request)
    {
        $this->admControlInit($request, '/dashboard/media');
        $this->v["journalists"] = OPCustomers::where('CustType', 'Journalist')->get();
        return view('vendor.openpolice.admin.lists.media', $this->v);
    }
    
}
