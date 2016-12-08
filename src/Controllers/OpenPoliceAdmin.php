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

use App\Models\OPzComplaintEmails;
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
            return redirect('/');
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
            list($treeMenu, $dbMenu) = $this->loadAdmMenuBasics();
            if ($this->v["user"]->hasRole('administrator|staff|databaser')) {
                return [
                    [
                        '/dashboard',
                        'Dashboard',
                        1,
                        []
                    ], [
                        'javascript:;',
                        'Complaints <span class="pull-right"><i class="fa fa-star"></i></span>',
                        1,
                        [
                            [
                                '/dashboard/complaints',
                                'Requiring Action',
                                1,
                                []
                            ], [
                                '/dashboard/complaints/me',
                                'Assigned To Me',
                                1,
                                []
                            ], [
                                '/dashboard/complaints/waiting',
                                'Waiting',
                                1,
                                []
                            ], [
                                '/dashboard/complaints/all',
                                'All Complete',
                                1,
                                []
                            ], [
                                '/dashboard/complaints/incomplete',
                                'Incomplete Sessions',
                                1,
                                []
                            ], [
                                '/dashboard/complaints/emails',
                                'Settings & Emails',
                                1,
                                []
                            ]
                        ]
                    ], [
                        'javascript:;',
                        'Volunteering <span class="pull-right"><i class="fa fa-users"></i></span>',
                        1,
                        [
                            [
                                '/dashboard/volun',
                                'Recent Department Edits',
                                1,
                                []
                            ], [
                                '/dashboard/volun/stars',
                                'List of Volunteers',
                                1,
                                []
                            ], [
                                '/volunteer',
                                'Departments Dashboard',
                                1,
                                []
                            ], [
                                '/volunteer/nextDept',
                                'Verify A Department',
                                1,
                                []
                            ], [
                                '/dashboard/instruct',
                                'Edit Instructions',
                                1,
                                []
                            ]
                        ]
                    ], [
                        'javascript:;',
                        'Directories <span class="pull-right"><i class="fa fa-list-ul"></i></span>',
                        1,
                        [
                            [
                                '/dashboard/officers',
                                'Police Officers',
                                1,
                                []
                            ], [
                                '/dashboard/depts',
                                'Police Departments',
                                1,
                                []
                            ], [
                                '/dashboard/overs',
                                'Oversight Agencies',
                                1,
                                []
                            ], [
                                'javascript:;',
                                'Other Contacts',
                                1,
                                [
                                    [
                                        '/dashboard/legal',
                                        'Attorneys',
                                        1,
                                        []
                                    ], [
                                        '/dashboard/academic',
                                        'Academic',
                                        1,
                                        []
                                    ], [
                                        '/dashboard/media',
                                        'Journalists',
                                        1,
                                        []
                                    ]
                                ]
                            ]
                        ]
                    ], 
                    $treeMenu,
                    $dbMenu
                ];
            } elseif ($this->v["user"]->hasRole('volunteer')) {
                return [
                    [
                        '/volunteer',
                        'Police Departments <span class="pull-right"><i class="fa fa-list-ul"></i></span>',
                        1,
                        []
                    ], [
                        '/volunteer/nextDept',
                        'Verify A Department <span class="pull-right"><i class="fa fa-check"></i></span>',
                        1,
                        []
                    ], [
                        '/volunteer/stars',
                        'You Have [[score]] Stars <span class="pull-right"><img src="/openpolice/star1.png" height=20 '
                             . 'border=0 style="margin-top: -5px;" ></span>',
                        1,
                        []
                    ]
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
        } elseif (isset($this->v["deptSlug"])) {
            if ($this->v["user"]->hasRole('administrator|staff|databaser')) {
                $this->admMenuData["currNavPos"] = [2, 3, -1, -1];
            } else {
                $this->admMenuData["currNavPos"] = [1, -1, -1, -1];
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
                $this->admMenuData["adminNav"][2][3][3] = $volunteeringSubMenu;
            } else { // is Volunteer
                $volunteeringSubMenu[1] .= ' <span class="pull-right"><i class="fa fa-check"></i></span>';
                $this->admMenuData["adminNav"][1] = $volunteeringSubMenu;
            }
        }
        if (!$this->v["user"]->hasRole('administrator|staff|databaser')) {
            if (isset($this->admMenuData["adminNav"][2]) && isset($this->admMenuData["adminNav"][2][1])) {
                if (!isset($this->v["yourUserInfo"]->UserInfoStars)) {
                    $this->admMenuData["adminNav"][2][1] = 0;
                } else {
                    $this->admMenuData["adminNav"][2][1] = str_replace('[[score]]', 
                        intVal($this->v["yourUserInfo"]->UserInfoStars), $this->admMenuData["adminNav"][2][1]);
                }
            }
        }
        
        $this->getAdmMenuLoc($currPage);
        return true;
    }
    
    protected function loadSysSettings() 
    {
        $settings = SLDefinitions::where('DefSet', 'Custom Settings')
            ->orderBy('DefOrder', 'asc')
            ->get();
        $this->v["settings"] = [];
        if ($settings && sizeof($settings) > 0) {
            foreach ($settings as $s) {
                $this->v["settings"][$s->DefSubset] = $s->DefValue;
            }
        }
        return true;
    }
    
    protected function initExtra(Request $request)
    {
        $this->CustReport = new OpenPoliceReport($request);
        
        if (!isset($this->v["currPage"])) $this->v["currPage"] = '/dashboard';
        if (trim($this->v["currPage"]) == '') $this->v["currPage"] = '/dashboard';
        $this->v["allowEdits"] = ($this->v["user"]->hasRole('administrator|staff'));
        
        $this->loadSysSettings();
        
        $this->v["management"] = ($this->v["user"]->hasRole('administrator|staff'));
        $this->v["volunOpts"] = 1;
        if ($this->REQ->session()->has('volunOpts')) {
            $this->v["volunOpts"] = $this->REQ->session()->get('volunOpts');
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
                return redirect('/volunteer');
            }
            return redirect('/');
        }
        return redirect( '/dashboard/complaints' );
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
    
    protected function printComplaintListing(Request $request)
    {
        $sort = "date";
        $qman = "SELECT c.*, p.`PrsnNameFirst`, p.`PrsnNameLast`, i.* 
            FROM `OP_Complaints` c 
            JOIN `OP_Incidents` i ON c.`ComID` LIKE i.`IncComplaintID` 
            LEFT OUTER JOIN `OP_Civilians` civ ON c.`ComID` LIKE civ.`CivComplaintID` 
            LEFT OUTER JOIN `OP_PersonContact` p ON p.`PrsnID` LIKE civ.`CivPersonID` 
            WHERE civ.`CivIsCreator` LIKE 'Y' ";
        switch ($this->v["currPage"]) {
            case '/dashboard/complaints':         
                $qman .= " AND (c.`ComStatus` LIKE '" . $GLOBALS["DB"]->getDefID('Complaint Status', 'New') . "' 
                    OR (c.`ComType` IN (
                    '" . $GLOBALS["DB"]->getDefID('OPC Staff/Internal Complaint Type', 'Unreviewed') . "', 
                    '" . $GLOBALS["DB"]->getDefID('OPC Staff/Internal Complaint Type', 'Not Sure') . "'
                    ) AND c.`ComStatus` NOT LIKE '" 
                    . $GLOBALS["DB"]->getDefID('Complaint Status', 'Incomplete') . "') )"; 
                break;
            case '/dashboard/complaints/me':     
                $qman .= " AND c.`ComAdminID` LIKE '" . $this->v["user"]->id . "' 
                    AND c.`ComStatus` NOT LIKE '" . $GLOBALS["DB"]->getDefID('Complaint Status', 'Incomplete') . "'";
                break;
            case '/dashboard/complaints/waiting':     
                $qman .= " AND (c.`ComStatus` IN (
                    '" . $GLOBALS["DB"]->getDefID('Complaint Status', 'Attorney\'d') . "', 
                    '" . $GLOBALS["DB"]->getDefID('Complaint Status', 'Submitted to Oversight') . "', 
                    '" . $GLOBALS["DB"]->getDefID('Complaint Status', 'Received by Oversight') . "', 
                    '" . $GLOBALS["DB"]->getDefID('Complaint Status', 'Pending Oversight Investigation') . "'
                    ) )"; 
                break;
            case '/dashboard/complaints/all':     
                $qman .= " AND c.`ComStatus` NOT LIKE '" 
                    . $GLOBALS["DB"]->getDefID('Complaint Status', 'Incomplete') . "'";
                break;
            case '/dashboard/complaints/incomplete':     
                $qman .= " AND c.`ComStatus` LIKE '" 
                    . $GLOBALS["DB"]->getDefID('Complaint Status', 'Incomplete') . "'";
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
                    $this->v["comInfo"][$com->ComID]["alleg"] = $this->CustReport->commaAllegationList($allegsTmp);
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
            $chk = mysqli_query($GLOBALS["DB"], $qman); // echo $qman . '<br />';
            if ($chk && mysqli_num_rows($chk) > 0) { 
                while ($row = mysqli_fetch_array($chk)) { $delComs[] = $row["ComID"]; }
                $qmen[] = "DELETE FROM `Civilians` WHERE `CivComplaintID` IN ('" . implode("', '", $delComs) . "')";
                $qmen[] = "DELETE FROM `Scenes` WHERE `ScnComplaintID` IN ('" . implode("', '", $delComs) . "')";
                $qmen[] = "DELETE FROM `Incidents` WHERE `IncComplaintID` IN ('" . implode("', '", $delComs) . "')";
                $qmen[] = "DELETE FROM `Complaints` WHERE `ComID` IN ('" . implode("', '", $delComs) . "')";
            }
            $qmen[] = "DELETE FROM `AuthUser` WHERE `UserCreated` < '" . date("Y-m-d 00:00:00", mktime(0, 0, 0, date("n"), date("j")-2, date("Y"))) . "' AND `UserName` IS NULL AND `UserPassword` IS NULL AND `UserID` NOT IN (SELECT `CivUserID` FROM `Civilians`)";
            foreach ($qmen as $qman) {
                $chk = mysqli_query($GLOBALS["DB"], $qman); // echo $qman . '<br />';
            }
            $_SESSION["clearIncompletes"] = true;
        }
        */
    }
    
    
    

    
    public function complaintView(Request $request, $cid, $viewType = 'view') 
    {
        $this->v["cID"] = $this->coreID = $cid;
        $this->loadSysSettings();
        $currPage = '/dashboard/complaint/' . $cid . (($viewType == 'view') ? '' : '/'.$viewType);
        if ($this->v["settings"]["Complaint Evaluations"] == 'N') $currPage = '/dashboard/complaints/all';
        $this->admControlInit($request, $currPage);
        $this->v["viewType"] = $viewType;
        if ($viewType == 'review' && $this->v["settings"]["Complaint Evaluations"] == 'Y') {
            $this->v["admMenuHideable"] = true;
        }
        $this->CustReport->loadSessionData($cid);
        $this->v["fullReport"] = $this->CustReport->printAdminReport($cid, $viewType);
        $this->v["complaintRec"] = $this->CustReport->sessData->dataSets["Complaints"][0];
        $this->v["firstReview"] = true;
        $this->v["yourReview"] = new OPzComplaintReviews;
        $this->v["allStaffName"] = [];
        $this->v["latestReview"] = OPzComplaintReviews::where('ComRevComplaint', '=', $cid)
            ->where('ComRevType', 'Full')
            ->orderBy('ComRevDate', 'desc')
            ->first();
        $this->v["reviews"] = OPzComplaintReviews::where('ComRevComplaint', '=', $cid)
            ->where('ComRevType', 'NOT LIKE', 'Draft')
            ->orderBy('ComRevDate', 'asc')
            ->get();
        if ($this->v["reviews"] && sizeof($this->v["reviews"]) > 0) {
            foreach ($this->v["reviews"] as $i => $r) {
                if ($r->ComRevUser == Auth::user()->id) {
                    $this->v["firstReview"] = false;
                    $this->v["yourReview"] = $r;
                }
                if (!isset($this->v["allStaffName"][$r->ComRevUser])) {
                    $this->v["allStaffName"][$r->ComRevUser] = User::find($r->ComRevUser)
                        ->printUsername(true, '/dashboard/volun/user/');
                }
            }
        }
        $this->v["emailList"] = OPzComplaintEmails::orderBy('ComEmailName', 'asc')
            ->orderBy('ComEmailType', 'asc')
            ->get();
        $this->v["emailMap"] = [ // 'Review Status' => Email ID#
                'Submitted to Oversight'             => [7, 12], 
                'Hold: Go Gold'                     => [6],
                'Pending Attorney: Needed'             => [17],
                'Pending Attorney: Hook-Up'         => [18]
            ];
        $this->v["email1"] = ($request->has('email1') ? $request->email1 : -3);
        $this->v["email2"] = ($request->has('email2') ? $request->email2 : -3);
        $this->prepEmailComData();
        $this->v["e1"] = $this->processEmail($this->v["email1"]);
        $this->v["e2"] = $this->processEmail($this->v["email2"]);
        $this->v["allReviews"] = view('vendor.openpolice.complaint-staff-review', $this->v)->render();
        $this->v["sendingEmails"] = view('vendor.openpolice.complaint-staff-emails', $this->v)->render();
        return view('vendor.openpolice.admin.complaints.complaint-review', $this->v);
    }
    
    public function prepEmailComData()
    {
        $cnt = 0;
        $this->v["comDepts"] = [];
        if (sizeof($this->CustReport->sessData->dataSets["LinksComplaintDept"]) > 0) {
            foreach ($this->CustReport->sessData->dataSets["LinksComplaintDept"] as $i => $lnk) {
                $this->v["comDepts"][$cnt] = array("id" => $lnk->LnkComDeptDeptID);
                $this->v["comDepts"][$cnt]["deptRow"] = OPDepartments::find($lnk->LnkComDeptDeptID)->first();
                $this->v["comDepts"][$cnt]["iaRow"] = OPOversight::where('OverDeptID', $lnk->LnkComDeptDeptID)
                    ->where('OverType', $GLOBALS["DB"]->getDefID('Oversight Agency Types', 'Internal Affairs'))
                    ->first();
                $this->v["comDepts"][$cnt]["civRow"] = OPOversight::where('OverDeptID', $lnk->LnkComDeptDeptID)
                    ->where('OverType', $GLOBALS["DB"]->getDefID('Oversight Agency Types', 'Civilian Oversight'))
                    ->first();
                if (!isset($this->v["comDepts"][$cnt]["iaRow"]) || sizeof($this->v["comDepts"][$cnt]["iaRow"]) == 0) {
                    $this->v["comDepts"][$cnt]["iaRow"] = new OPOversight;
                    $this->v["comDepts"][$cnt]["iaRow"]->OverDeptID       = $lnk->LnkComDeptDeptID;
                    $this->v["comDepts"][$cnt]["iaRow"]->OverType
                        = $GLOBALS["DB"]->getDefID('Oversight Agency Types', 'Internal Affairs');
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
                $cnt++;
            }
        }
        return true;
    }
    
    public function processEmail($emailID)
    {
        $email = array("rec" => false, "splits" => []);
        if ($emailID > 0) {
            if (sizeof($this->v["emailList"]) > 0) {
                foreach ($this->v["emailList"] as $e) {
                    if ($e->ComEmailID == $ComEmailID) $email["rec"] = $e;
                }
                if ($email["rec"] !== false && isset($email["rec"]->ComEmailBody) 
                    && trim($email["rec"]->ComEmailBody) != '') {
                    $emailBody = $this->swapBlurb($email["rec"]->ComEmailBody);
                    if (strpos($emailBody, '[{ Evaluator Message }]') !== false) {
                        $email["splits"] = explode('[{ Evaluator Message }]', $emailBody);
                    }
                }
            }
        }
        return $email;
    }
    
    public function swapBlurb($emailBody)
    {
        if (trim($emailBody) != '' && sizeof($this->v["emailList"]) > 0) {
            foreach ($this->v["emailList"] as $i => $e) {
                if ($e->ComEmailType == 'Blurb') {
                    $emailTag = '[{ ' . $e->ComEmailName . ' }]';
                    if (strpos($emailBody, $emailTag) !== false) {
                        $emailBody = str_replace($emailTag, $this->swapBlurb($e->ComEmailBody), $emailBody);
                    }
                }
            }
        }
        
        $dynamos = [
            '[{ Complaint ID }]', 
            '[{ Complaint URL }]', 
            '[{ Complaint URL Link }]', 
            '[{ Complainant Name }]', 
            '[{ Confirmation URL }]', 
            '[{ Go Gold Secure URL }]', 
            '[{ Submit Silver Secure URL }]', 
            '[{ Update Complaint Secure URL }]', 
            '[{ Login URL }]', 
            '[{ Days From Now: 7, mm/dd/yyyy }]', 
            '[{ Complaint Number of Weeks Old }]', 
            '[{ Analyst Name }]', 
            '[{ Complaint Department Submission Ways }]', 
            '[{ Complaint Oversight Agency }]', 
            '[{ Complaint Police Department }]', 
            '[{ Dear Primary Oversight Agency }]', 
            '[{ Complaint Investigability Score & Description }]', 
            '[{ Complaint Allegation List }]', 
            '[{ Oversight Complaint Secure URL }]', 
            '[{ Complaint Department Complaint PDF }]', 
            '[{ Complaint Department Complaint Web }]', 
            '[{ Flex Article Suggestions Based On Responses }]'
        ];
        foreach ($dynamos as $dy) {
            if (strpos($emailBody, $dy) !== false) {
                $swap = $dy;
                $dyCore = str_replace('[{ ', '', str_replace(' }]', '', $dy));
                switch ($dy) {
                    case '[{ Complaint ID }]': 
                        $swap = $this->coreID;
                        break;
                    case '[{ Complaint URL }]':
                        $swap = $this->swapURLwrap('https://app.openpolicecomplaints.org/report' 
                            . $this->CustReport->sessData->dataSets["Complaints"][0]->ComSlug);
                        break;
                    case '[{ Complaint URL Link }]':
                        $swap = $this->CustReport->sessData->dataSets["Complaints"][0]->ComSlug;
                        break;
                    case '[{ Complainant Name }]':
                        $swap = $this->getCivilianNameFromID(
                            $this->CustReport->sessData->dataSets["Civilians"][0]->CivID);
                        break;
                    case '[{ Confirmation URL }]':
                        $swap = $this->swapURLwrap('https://app.openpolicecomplaints.org/report/' 
                            . $this->coreID . '/confirm-email/goooobblygooook8923528350');
                        break;
                    case '[{ Go Gold Secure URL }]':
                        $swap = $this->swapURLwrap('https://app.openpolicecomplaints.org/report/' 
                            . $this->coreID . '/go-gold/goooobblygooook8923528350');
                        break;
                    case '[{ Submit Silver Secure URL }]':
                        $swap = $this->swapURLwrap('https://app.openpolicecomplaints.org/report/' 
                            . $this->coreID . '/submit-silver/goooobblygooook8923528350');
                        break;
                    case '[{ Update Complaint Secure URL }]':
                        $swap = $this->swapURLwrap('https://app.openpolicecomplaints.org/report/' 
                            . $this->coreID . '/update/goooobblygooook8923528350');
                        break;
                    case '[{ Login URL }]':
                        $swap = $this->swapURLwrap('https://app.openpolicecomplaints.org/login');
                        break;
                    case '[{ Days From Now: 7, mm/dd/yyyy }]':
                        $swap = date('n/j/y', mktime(0, 0, 0, date("n"), (7+date("j")), date("Y")));
                        break;
                    case '[{ Complaint Number of Weeks Old }]':
                        $dayCount = date_diff(mktime(), strtotime(
                            $this->CustReport->sessData->dataSets["Complaints"][0]->ComRecordSubmitted
                            ))->format('%a');
                        $swap = floor($dayCount/7);
                        break;
                    case '[{ Analyst Name }]':
                        $swap = str_replace('<a', '<a target="_blank"', 
                            $this->v["user"]->printCasualUsername(true, '', '/dashboard/volun/user/'));
                        break;
                    case '[{ Complaint Department Submission Ways }]':
                        $swap = '';
                        foreach ($this->v["comDepts"] as $i => $d)
                        {
                            $swap = '<br /><b>' . $d["deptRow"]->DeptName . '</b>';
                            if (isset($d[$d["whichOver"]]->OverAddress) 
                                && trim($d[$d["whichOver"]]->OverAddress) != '') {
                                $swap .= '<br />' . $d[$d["whichOver"]]->OverAddress;
                                if (trim($d[$d["whichOver"]]->OverAddress2) != '') {
                                    $swap .= '<br />' . $d[$d["whichOver"]]->OverAddress2;
                                }
                                $swap .= '<br />' . $d[$d["whichOver"]]->OverAddressCity . ', ' 
                                    . $d[$d["whichOver"]]->OverAddressState . ' ' 
                                    . $d[$d["whichOver"]]->OverAddressZip . '<br />';
                            }
                            if (isset($d[$d["whichOver"]]->OverPhoneWork) 
                                && trim($d[$d["whichOver"]]->OverPhoneWork) != '') {
                                $swap .= '<br />' . $d[$d["whichOver"]]->OverPhoneWork;
                            }
                            if (isset($d[$d["whichOver"]]->OverEmail) && trim($d[$d["whichOver"]]->OverEmail) != '') {
                                $swap .= '<br />' . $this->swapURLwrap('mailto:' . $d[$d["whichOver"]]->OverEmail);
                            }
                            $swap .= '<br />About their complaint process:<ul>';
                            if (intVal($d["iaRow"]->OverSubmitDeadline) > 0) {
                                $incident = strtotime(
                                    $this->CustReport->sessData->dataSets["Incidents"][0]->IncTimeStart);
                                $swap .= '<li>You need to officially submit your complaint within ' 
                                    . $d["iaRow"]->OverSubmitDeadline . ' of the incident (by ' 
                                    . date("n/j/y", mktime(0, 0, 0, date("n", $incident), 
                                        ($d["iaRow"]->OverSubmitDeadline+date("j", $incident)), date("Y", $incident)))
                                    . ')</li>';
                            }
                            if (intVal($d["iaRow"]->OverWaySubOnline) == 1 
                                && trim($d["iaRow"]->OverComplaintWebForm) != '') {
                                $swap .= '<li>You can use their online form: ' 
                                    . $this->swapURLwrap($d["iaRow"]->OverComplaintWebForm) . '</li>';
                            }
                            if (intVal($d["iaRow"]->OverWaySubEmail) == 1 && trim($d["iaRow"]->OverEmail) != '') {
                                $swap .= '<li>You can email them the pdf of your OPC complaint.</li>';
                            }
                            if (intVal($d["iaRow"]->OverWaySubVerbalPhone) == 1) {
                                $swap .= '<li>You can call their phone number and describe your complaint</li>';
                            }
                            $pdfForm = '';
                            if (isset($d[$d["whichOver"]]->OverComplaintPDF) 
                                && trim($d[$d["whichOver"]]->OverComplaintPDF) != '') {
                                $pdfForm = ': ' . $this->swapURLwrap($d[$d["whichOver"]]->OverComplaintPDF);
                            }
                            if (intVal($d["iaRow"]->OverOfficialFormNotReq) == 1) {
                                $swap .= '<li>You can print out your OPC complaint because you do not have to use '
                                    . 'their official complaint form' . $pdfForm . '</li>';
                            } else {
                                $swap .= '<li>You have to use their official complaint form' . $pdfForm . '</li>';
                            }
                            if (intVal($d["iaRow"]->OverWaySubPaperMail) == 1 
                                && intVal($d["iaRow"]->OverWaySubPaperInPerson) == 1) {
                                $swap .= '<li>You can either submit your complaint in-person or send it to their '
                                    . 'mailing address</li>';
                            } elseif (intVal($d["iaRow"]->OverWaySubPaperMail) == 1) {
                                $swap .= '<li>You can either send your complaint to their mailing address</li>';
                            }
                            if (intVal($d["iaRow"]->OverWaySubPaperInPerson) == 1) {
                                $swap .= '<li>You can either submit your complaint in-person</li>';
                            }
                            $swap .= '</ul>';
                        }
                        break;
                    case '[{ Complaint Police Department }]':
                        $swap = $this->v["comDepts"][0]["deptRow"]->DeptName;
                        if (sizeof($this->v["comDepts"]) > 1) {
                            for ($i = 1; $i < sizeof($this->v["comDepts"]); $i++) {
                                if (sizeof($this->v["comDepts"]) == 2) {
                                    $swap .= ' and ';
                                } elseif ($i == sizeof($this->v["comDepts"])-1) {
                                    $swap .= ', and ';
                                } else {
                                    $swap .= ', ';
                                }
                                $swap .= $this->v["comDepts"][$i]["deptRow"]->DeptName;
                            }
                        }
                        break;
                    case '[{ Complaint Oversight Agency }]':
                        $swap = $this->v["comDepts"][0][$this->v["comDepts"][0]["whichOver"]]->OverAgncName;
                        if ($swap != $this->v["comDepts"][0]["deptRow"]->DeptName) {
                            $swap .= ' (for the ' . $this->v["comDepts"][0]["deptRow"]->DeptName . ')';
                        }
                        if (sizeof($this->v["comDepts"]) > 1) {
                            for ($i = 1; $i < sizeof($this->v["comDepts"]); $i++) {
                                if (sizeof($this->v["comDepts"]) == 2) $swap .= ' and ';
                                elseif ($i == sizeof($this->v["comDepts"])-1) $swap .= ', and ';
                                else $swap .= ', ';
                                $swap .= $this->v["comDepts"][$i][$this->v["comDepts"][$i]["whichOver"]]->OverAgncName;
                                if ($this->v["comDepts"][$i][$this->v["comDepts"][$i]["whichOver"]]->OverAgncName 
                                    != $this->v["comDepts"][$i]["deptRow"]->DeptName)
                                {
                                    $swap .= ' (for the ' . $this->v["comDepts"][$i]["deptRow"]->DeptName . ')';
                                }
                            }
                        }
                        break;
                    case '[{ Dear Primary Oversight Agency }]':
                        $swap = 'To whom it may concern:';
                        break;
                    case '[{ Complaint Investigability Score & Description }]':
                        $swap = '';
                        if (isset($this->v["latestReview"]) && isset($this->v["latestReview"]->ComRevNotAnon)) {
                            $score = $this->v["latestReview"]->ComRevNotAnon 
                                + $this->v["latestReview"]->ComRevOneIncident 
                                + $this->v["latestReview"]->ComRevCivilianContact 
                                + $this->v["latestReview"]->ComRevOneOfficer 
                                + $this->v["latestReview"]->ComRevOneAllegation 
                                + $this->v["latestReview"]->ComRevEvidenceUpload;
                            $swap = '<h3>Investigability Score: ' . $score . '</h3>';
                            $swapDesc = '';
                            if (intVal($this->v["latestReview"]->ComRevNotAnon) == 1) {
                                $swapDesc .= ', Not anonymous';
                            }
                            if (intVal($this->v["latestReview"]->ComRevOneIncident) == 1) {
                                $swapDesc .= ', Focused on one incident';
                            }
                            if (intVal($this->v["latestReview"]->ComRevCivilianContact) == 1) {
                                $swapDesc .= ', Civilian contact info';
                            }
                            if (intVal($this->v["latestReview"]->ComRevOneOfficer) == 1) {
                                $swapDesc .= ', Officer info';
                            }
                            if (intVal($this->v["latestReview"]->ComRevOneAllegation) == 1) {
                                $swapDesc .= ', Specific Allegations';
                            }
                            if (intVal($this->v["latestReview"]->ComRevEvidenceUpload) == 1) {
                                $swapDesc .= ', Evidence Uploaded';
                            }
                            if (trim($swapDesc) != '') {
                                $swap .= substr($swapDesc, 1);
                            }
                        }
                        break;
                    case '[{ Complaint Allegation List }]':
                        $swap = $this->CustReport->simpleAllegationList();
                        break;
                    case '[{ Oversight Complaint Secure URL }]':
                        $swap = $this->swapURLwrap('https://app.openpolicecomplaints.org/report/' 
                            . $this->coreID . '/oversight/goooobblygooook8923528350');
                        break;
                    case '[{ Complaint Department Complaint PDF }]':
                        $which = $this->v["comDepts"][0]["whichOver"];
                        if (isset($this->v["comDepts"][0][$which]->OverComplaintPDF) 
                            && trim($this->v["comDepts"][0][$which]->OverComplaintPDF) != '') {
                            $swap = '';
                            if (sizeof($this->v["comDepts"]) > 1) {
                                $swap .= $this->v["comDepts"][0][$which]->OverAgncName;
                            }
                            $swap .= ': ' . $this->swapURLwrap($this->v["comDepts"][0][$which]->OverComplaintPDF);
                        }
                        if (sizeof($this->v["comDepts"]) > 1) {
                            for ($i = 1; $i < sizeof($this->v["comDepts"]); $i++) {
                                if (trim($swap) != '') $swap .= '<br />';
                                $which = $this->v["comDepts"][$i]["whichOver"];
                                $swap .= $this->v["comDepts"][0][$which]->OverAgncName . ': ' 
                                    . $this->swapURLwrap($this->v["comDepts"][$i][$which]->OverComplaintPDF);
                            }
                        }
                        break;
                    case '[{ Complaint Department Complaint Web }]':
                        $which = $this->v["comDepts"][0]["whichOver"];
                        if (isset($this->v["comDepts"][0][$which]->OverComplaintWebForm) 
                            && trim($this->v["comDepts"][0][$which]->OverComplaintWebForm) != '') {
                            $swap = '';
                            if (sizeof($this->v["comDepts"]) > 1) {
                                $swap = $this->v["comDepts"][0][$which]->OverAgncName;
                            }
                            $swap .= ': ' . $this->swapURLwrap($this->v["comDepts"][0][$which]->OverComplaintWebForm);
                        }
                        if (sizeof($this->v["comDepts"]) > 1) {
                            for ($i = 1; $i < sizeof($this->v["comDepts"]); $i++) {
                                if (trim($swap) != '') $swap .= '<br />';
                                $swap .= $this->v["comDepts"][0][$which]->OverAgncName
                                    . ': ' . $this->swapURLwrap($this->v["comDepts"][$i][$this->v["comDepts"][$i]["whichOver"]]->OverComplaintWebForm);
                            }
                        }
                        break;
                    case '[{ Link To All Police Department\'s Complaints }]':
                        $swap = '';
                        break;
                    case '[{ Flex Article Suggestions Based On Responses }]':
                        $swap = '';
                        break;
                }
                $ifPos = strpos($emailBody, '@if[{ ' . $dyCore . ' }]');
                if ($ifPos === false) {
                    if (trim($swap) == '') $swap = $dy;
                } else {
                    $ifEndPos = strpos($emailBody, $dy, (5+$ifPos));
                    if (trim($swap) == '') {
                        $emailBody = substr($emailBody, $ifPos, $ifEndPos-$ifPos+strlen($dy)+1);
                    } else {
                        $emailBody = substr($emailBody, 0, $ifPos) . substr($emailBody, $ifPos+strlen($dy)+3);
                    }
                }
                $emailBody = str_replace($dy, $swap, $emailBody);
            }
        }
        
        $emailBody = str_replace('[{ Flex Article Suggestions Based On Responses }]', 
            '[{ Flex Article Suggestions Based On Responses }]', $emailBody);
        
        $emailBody = str_replace('Hello Complainant,', 'Hello,', $emailBody);

        return $emailBody;
    }
    
    public function swapURLwrap($url)
    {
        return '<a href="' . $url . '" target="_blank">' . str_replace('mailto:', '', $url) . '</a>'; 
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
        $this->CustReport->loadSessionData($cid);
        $this->admControlInit($request, '/dashboard/complaint/' . $cid . '/review');
        if ($request->has('cID') && intVal($request->cID) > 0 && intVal($request->cID) == $cid) {
            $newReview = new OPzComplaintReviews;
            $newReview->ComRevComplaint             = $cid;
            $newReview->ComRevUser                  = $this->v["user"]->id;
            $newReview->ComRevDate                  = date("Y-m-d H:i:s");
            $newReview->ComRevType                  = $request->revType;
            $newReview->ComRevNote                  = $request->revNote;
            $newReview->ComRevStatus                = $request->revStatus;
            $newReview->ComRevMakeFeatured          = intVal($request->revMakeFeatured);
            if ($request->revType == 'Full') {
                OPzComplaintReviews::where('ComRevComplaint', '=', $cid)
                    ->where('ComRevUser', Auth::user()->id)
                    ->where('ComRevType', 'Full')
                    ->update([ "ComRevType" => 'Draft' ]);
                $newReview->ComRevComplaintType     = 196;
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
            
            // MORGAN, this needs update!!!
            switch ($newReview->ComRevStatus) {
                case 'Submitted to Oversight':           $com->comStatus = 300; break;
                case 'Hold: Not Sure':                   $com->comStatus = 295; break;
                case 'Hold: Go Gold':                    $com->comStatus = 295; break;
                case 'Pending Attorney: Needed':         $com->comStatus = 298; break;
                case 'Pending Attorney: Hook-Up':        $com->comStatus = 298; break;
                case "Attorney'd":                       $com->comStatus = 299; break;
                case 'Incomplete':                       $com->comStatus = 294; break;
                case 'Received by Oversight':            $com->comStatus = 301; break;
                case 'Pending Oversight Investigation':  $com->comStatus = 302; break;
                case 'Declined To Investigate (Closed)': $com->comStatus = 303; break;
                case 'Investigated (Closed)':            $com->comStatus = 304; break;
                case 'Closed':                           $com->comStatus = 305; break;
            }
            $com->save();
        }
        //$this->CustReport->loadSessionData();
        return redirect('/dashboard/complaint/' . $cid . '/history');
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
    
    public function volunManage(REQUEST $request)
    {
        $this->admControlInit($request, '/dashboard/volun/stars');
        $this->loadPrintVoluns();
        return view('vendor.openpolice.admin.volun.volunManage', $this->v);
    }
    
    public function volunManagePost(REQUEST $request)
    {
        $volunteers = User::where('name', 'NOT LIKE', 'Session#%')->get();
        foreach ($volunteers as $i => $volun) {
            foreach ($volun->rolesRanked as $role) {
                if ($request->has('user'.$volun->id) && in_array($role, $request->get('user'.$volun->id))) {
                    if (!$volun->hasRole($role)) {
                        $volun->assignRole($role);
                    }
                } elseif ($volun->hasRole($role)) {
                    $volun->revokeRole($role);
                }
            }
        }
        return $this->volunManage($request);
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
                "user"         => $deptEdit[0], 
                "deptRow"     => OPDepartments::find($deptEdit[1]->EditDeptDeptID), 
                "deptEdit"     => $deptEdit[1], 
                "deptType"     => $GLOBALS["DB"]->getDefValue('Types of Departments', $deptEdit[1]->EditDeptType),
                "iaEdit"     => $deptEdit[2], 
                "civEdit"     => $deptEdit[3]
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
    
    
        
    public function volunEmail(Request $request)
    {
        $this->admControlInit($request, '/dashboard/volun/stars');
        $this->loadPrintVoluns();
        return view('vendor.openpolice.admin.volun.volunEmail', $this->v);
    }
    
    
    
    protected function loadPrintVoluns()
    {
        $this->v["printVoluns"] = [ [], [], [], [] ]; // voluns, staff, admin
        $volunteers = User::where('name', 'NOT LIKE', 'Session#%')
            ->orderBy('name', 'asc')
            ->get();
        foreach ($volunteers as $i => $volun) {
            $list = 3;
            if ($volun->hasRole('administrator')) $list = 0;
            elseif ($volun->hasRole('databaser')) $list = 1;
            elseif ($volun->hasRole('brancher'))  $list = 2;
            elseif ($volun->hasRole('staff'))     $list = 3;
            elseif ($volun->hasRole('volunteer')) $list = 4;
            $this->v["printVoluns"][$list][] = $volun;
        }
        $this->v["disableAdmin"] = ((!$this->v["user"]->hasRole('administrator')) ? ' DISABLED ' : '');
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
        return redirect('/dashboard/overs#o'.$request->OverID);
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
