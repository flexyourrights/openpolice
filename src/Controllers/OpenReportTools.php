<?php
namespace OpenPolice\Controllers;

use App\Models\User;
use App\Models\SLEmails;
use App\Models\SLEmailed;
use App\Models\OPOversight;
use App\Models\OPComplaints;
use App\Models\OPzComplaintReviews;
use SurvLoop\Controllers\SessAnalysis;
use OpenPolice\Controllers\OpenReport;

class OpenReportTools extends OpenReport
{
    protected function printComplaintOversight()
    {
        $overRow = OPOversight::where('OverEmail', $this->v["user"]->email)
            ->first();
        if ($this->chkOverUserHasCore()) {
            if ($GLOBALS["SL"]->REQ->has('overUpdate') && intVal($GLOBALS["SL"]->REQ->get('overUpdate')) == 1
                && $overRow && isset($overRow->OverDeptID)) {
                $overUpdateRow = $this->getOverUpdateRow($this->coreID, $overRow->OverID);
                $newReview = new OPzComplaintReviews;
                $newReview->ComRevComplaint = $this->coreID;
                $newReview->ComRevUser      = $this->v["user"]->id;
                $newReview->ComRevDate      = date("Y-m-d H:i:s");
                $newReview->ComRevType      = 'Oversight';
                $newReview->ComRevNote      = (($GLOBALS["SL"]->REQ->has('overNote')) 
                    ? trim($GLOBALS["SL"]->REQ->overNote) : '');
                if ($GLOBALS["SL"]->REQ->has('overStatus')) { 
                    if ($GLOBALS["SL"]->REQ->overStatus == 'Received by Oversight') {
                        $this->logOverUpDate($this->coreID, $overRow->OverID, 'Received', $overUpdateRow);
                    } elseif ($GLOBALS["SL"]->REQ->overStatus == 'Investigated (Closed)') {
                        $this->logOverUpDate($this->coreID, $overRow->OverID, 'Investigated', $overUpdateRow);
                    }
                    $statusID = $GLOBALS["SL"]->def->getID('Complaint Status', trim($GLOBALS["SL"]->REQ->overStatus));
                    $this->sessData->dataSets["Complaints"][0]->update([ "ComStatus" => $statusID ]);
                    $newReview->ComRevStatus = $GLOBALS["SL"]->REQ->overStatus;
                }
                $newReview->save();
            } elseif ($GLOBALS["SL"]->REQ->has('upResult') && intVal($GLOBALS["SL"]->REQ->get('upResult')) == 1) {
                
            }
        }
        return view('vendor.openpolice.nodes.1711-report-inc-oversight-tools', [
            "user"        => $this->v["user"],
            "complaint"   => $this->sessData->dataSets["Complaints"][0],
            "overRow"     => $overRow
            ])->render();
    }
    
    protected function printComplaintSessPath()
    {
        if ($this->v["isOwner"] || $this->v["user"]->hasRole('administrator|databaser|staff')) {
            $this->loadCustLoop($GLOBALS["SL"]->REQ, 1, 1);
            $this->custReport->loadTree(1);
            $analyze = new SessAnalysis(1);
            $nodeTots = $analyze->loadNodeTots($this->custReport);
            $coreTots = $analyze->analyzeCoreSessions($this->coreID);
            return '<div class="pT20 pB20"><div class="slCard mT20 mB20"><h2>Incomplete: Session Attempt History</h2>'
                . view('vendor.survloop.admin.tree.tree-session-attempt-history', [
                "core"     => $coreTots,
                "nodeTots" => $nodeTots
                ])->render() . '<br /></div></div>';
        }
        return '';
    }
    
    protected function printComplaintOwner()
    {
        if ($this->v["isOwner"] && $GLOBALS["SL"]->REQ->has('ownerUpdate') 
            && intVal($GLOBALS["SL"]->REQ->get('ownerUpdate')) == 1 && isset($this->sessData->dataSets["Oversight"])) {
            $overID = $this->sessData->dataSets["Oversight"][0]->OverID;
            if (isset($this->sessData->dataSets["Oversight"][1]) 
                && $this->sessData->dataSets["Oversight"][1]->OverType == 303) {
                $overID = $this->sessData->dataSets["Oversight"][1]->OverID;
            }
            $overUpdateRow = $this->getOverUpdateRow($this->coreID, $overID);
        
            $newReview = new OPzComplaintReviews;
            $newReview->ComRevComplaint = $this->coreID;
            $newReview->ComRevUser      = $this->v["user"]->id;
            $newReview->ComRevDate      = date("Y-m-d H:i:s");
            $newReview->ComRevType      = 'Owner';
            $newReview->ComRevNote      = (($GLOBALS["SL"]->REQ->has('overNote')) 
                ? trim($GLOBALS["SL"]->REQ->overNote) : '');
            $newReview->ComRevStatus    = $GLOBALS["SL"]->REQ->overStatus;
            $newReview->save();
            if ($GLOBALS["SL"]->REQ->has('overStatus')) {
                if (trim($GLOBALS["SL"]->REQ->overStatus) == 'Received by Oversight') {
                    $this->logOverUpDate($this->coreID, $overID, 'Received', $overUpdateRow);
                    if ($this->sessData->dataSets["Complaints"][0]->ComStatus 
                        == $GLOBALS["SL"]->def->getID('Complaint Status', 'OK to Submit to Oversight')) {
                        $this->sessData->dataSets["Complaints"][0]->update([ 
                            "ComStatus" => $GLOBALS["SL"]->def->getID('Complaint Status', 
                                'Submitted to Oversight') ]);
                    }
                } else {
                    if ($GLOBALS["SL"]->REQ->overStatus == 'Investigated (Closed)') {
                        $this->logOverUpDate($this->coreID, $overID, 'Investigated', $overUpdateRow);
                    } elseif (in_array($GLOBALS["SL"]->REQ->overStatus, [
                        'Submitted to Oversight', 'OK to Submit to Oversight'])) {
                        if (isset($overUpdateRow->LnkComOverReceived) && $overUpdateRow->LnkComOverReceived != '') {
                            $overUpdateRow->LnkComOverReceived = NULL;
                            $overUpdateRow->save();
                        }
                    }
                    $this->sessData->dataSets["Complaints"][0]->update([ 
                        "ComStatus" => $GLOBALS["SL"]->def->getID('Complaint Status', $GLOBALS["SL"]->REQ->overStatus)
                        ]);
                }
            }
        }
        return view('vendor.openpolice.nodes.1714-report-inc-owner-tools', [
            "user"        => $this->v["user"],
            "complaint"   => $this->sessData->dataSets["Complaints"][0],
            "depts"       => ((isset($this->sessData->dataSets["Departments"])) 
                ? $this->sessData->dataSets["Departments"] : []),
            "oversigts"   => ((isset($this->sessData->dataSets["Oversight"]))
                ? $this->sessData->dataSets["Oversight"] : []),
            "overUpdates" => ((isset($this->sessData->dataSets["LinksComplaintOversight"]))
                ? $this->sessData->dataSets["LinksComplaintOversight"] : []),
            "overList"    => $this->oversightList(),
            "warning"     => $this->multiRecordCheckDelWarn()
            ])->render();
    }
    
    protected function oversightList()
    {
        $ret = '';
        if (isset($this->sessData->dataSets["Oversight"]) && sizeof($this->sessData->dataSets["Oversight"]) > 0) {
            $cnt = 0;
            foreach ($this->sessData->dataSets["Oversight"] as $i => $o) {
                if (isset($o->OverAgncName) && trim($o->OverAgncName) != '') {
                    $ret .= (($cnt > 0) ? ' and ' : '') . $o->OverAgncName;
                    $cnt++;
                }
            }
        }
        return $ret;
    }
    
    protected function printMfaInstruct()
    {
        if (isset($this->v["tokenUser"]) && $this->v["tokenUser"]) {
            return view('vendor.openpolice.nodes.1780-mfa-instructions', [
                "user" => $this->v["tokenUser"],
                "mfa"  => $this->processTokenAccess(false)
                ])->render();
        }
        return '';
    }
    
    protected function printComplaintAdmin()
    {
        $this->v["firstRevDone"] = false;
        if ($this->v["user"]->hasRole('administrator|databaser|staff')) {
            $GLOBALS["SL"]->addTopNavItem('All Complaints', '/dash/all-complete-complaints');
        }
        if ($GLOBALS["SL"]->REQ->has('firstReview') && intVal($GLOBALS["SL"]->REQ->firstReview) > 0) {
            $newTypeVal = $GLOBALS["SL"]->def->getVal('OPC Staff/Internal Complaint Type', 
                $GLOBALS["SL"]->REQ->firstReview);
            $newRev = new OPzComplaintReviews;
            $newRev->ComRevComplaint = $this->coreID;
            $newRev->ComRevUser      = $this->v["user"]->id;
            $newRev->ComRevDate      = date("Y-m-d H:i:s");
            $newRev->ComRevType      = 'First';
            $newRev->ComRevStatus    = $newTypeVal;
            $newRev->save();
            $com = OPComplaints::find($this->coreID);
            $com->comType = $GLOBALS["SL"]->REQ->firstReview;
            $com->save();
            $this->v["firstRevDone"] = true;
        } elseif ($GLOBALS["SL"]->REQ->has('save')) {
            $newRev = new OPzComplaintReviews;
            $newRev->ComRevComplaint = $this->coreID;
            $newRev->ComRevUser      = $this->v["user"]->id;
            $newRev->ComRevDate      = date("Y-m-d H:i:s");
            $newRev->ComRevType      = 'Update';
            $newRev->ComRevNote      = (($GLOBALS["SL"]->REQ->has('revNote')) ? $GLOBALS["SL"]->REQ->revNote : '');
            if ($GLOBALS["SL"]->REQ->has('revStatus')) {
                $newRev->ComRevStatus = $GLOBALS["SL"]->REQ->revStatus;
                if (in_array($GLOBALS["SL"]->REQ->revStatus, ['Hold: Go Gold', 'Hold: Not Sure'])) {
                    $this->sessData->dataSets["Complaints"][0]->ComStatus 
                        = $GLOBALS["SL"]->def->getID('Complaint Status', 'Hold');
                } elseif (in_array($GLOBALS["SL"]->REQ->revStatus, [
                    'Pending Attorney: Needed', 'Pending Attorney: Hook-Up'])) {
                    $this->sessData->dataSets["Complaints"][0]->ComStatus 
                        = $GLOBALS["SL"]->def->getID('Complaint Status', 'Pending Attorney');
                } elseif (in_array($GLOBALS["SL"]->REQ->revStatus, ['Attorney\'d'])) {
                    $this->sessData->dataSets["Complaints"][0]->ComStatus 
                        = $GLOBALS["SL"]->def->getID('Complaint Status', 'Attorney\'d');
                } else {
                    $this->sessData->dataSets["Complaints"][0]->ComStatus 
                        = $GLOBALS["SL"]->def->getID('Complaint Status', $GLOBALS["SL"]->REQ->revStatus);
                }
            }
            if ($GLOBALS["SL"]->REQ->has('revComplaintType')) {
                $newTypeVal = $GLOBALS["SL"]->def->getVal('OPC Staff/Internal Complaint Type', 
                    $GLOBALS["SL"]->REQ->revComplaintType);
                if ($newTypeVal != 'Police Complaint') $newRev->ComRevStatus = $newTypeVal;
                $this->sessData->dataSets["Complaints"][0]->ComType = $GLOBALS["SL"]->REQ->revComplaintType;
            }
            $newRev->save();
            $this->sessData->dataSets["Complaints"][0]->save();
        }
        $this->v["firstReview"] = true;
        $this->v["lastReview"]  = true;
        $this->v["history"]     = [];
        $allUserNames = [];
        $reviews = OPzComplaintReviews::where('ComRevComplaint', '=', $this->coreID)
            ->where('ComRevType', 'NOT LIKE', 'Draft')
            ->orderBy('ComRevDate', 'desc')
            ->get();
        if ($reviews->isNotEmpty()) {
            foreach ($reviews as $i => $r) {
                if ($i == 0) {
                    $this->v["lastReview"] = $r;
                }
                $this->v["firstReview"] = false;
                if (!isset($allUserNames[$r->ComRevUser])) {
                    $allUserNames[$r->ComRevUser] = $this->printUserLnk($r->ComRevUser);
                }
                $desc = '<span class="slBlueDark">' 
                    . ((isset($r->ComRevNextAction) && trim($r->ComRevNextAction) == 'Complaint Received'
                        && $r->ComRevStatus == 'Submitted to Oversight') ? $r->ComRevNextAction : $r->ComRevStatus)
                    . '</span>';
                $this->v["history"][] = [
                    "type" => 'Status', 
                    "date" => strtotime($r->ComRevDate), 
                    "desc" => $desc, 
                    "who"  => $allUserNames[$r->ComRevUser],
                    "note" => ((isset($r->ComRevNote)) ? trim($r->ComRevNote) : '')
                    ];
            }
        }
        $this->v["emailList"] = SLEmails::orderBy('EmailName', 'asc')
            ->orderBy('EmailType', 'asc')
            ->get();
        $emails = SLEmailed::where('EmailedTree', 1)
            ->where('EmailedRecID', $this->coreID)
            ->orderBy('created_at', 'asc')
            ->get();
        if ($emails->isNotEmpty()) {
            foreach ($emails as $i => $e) {
                if (!isset($allUserNames[$e->EmailedFromUser])) {
                    $allUserNames[$e->EmailedFromUser] = $this->printUserLnk($e->EmailedFromUser);
                }
                $desc = '<a href="javascript:;" id="hidFldBtnEma' . $e->EmailedID . '" class="hidFldBtn">' 
                    . $e->EmailedSubject . '</a> <i>to ' . substr($e->EmailedTo, 0, strpos($e->EmailedTo, '<'))  
                    . '<span class="fPerc66">&lt; ' 
                    . str_replace('>', '', substr($e->EmailedTo, 1+strpos($e->EmailedTo, '<'))) . ' &gt;</span></i>'
                    . '<div id="hidFldEma' . $e->EmailedID . '" class="disNon p10">' . $e->EmailedBody . '</div>';
                $this->v["history"][] = [
                    "type" => 'Email', 
                    "date" => strtotime($e->created_at), 
                    "desc" => $desc, 
                    "who"  => $allUserNames[$e->EmailedFromUser]
                    ];
            }
        }
        $this->v["history"] = $GLOBALS["SL"]->sortArrByKey($this->v["history"], 'date', 'desc');
        $this->prepEmailComData();
        $isOverCompatible = false;
        if (isset($this->v["comDepts"][0])) {
            $w = $this->v["comDepts"][0]["whichOver"];
            if (isset($this->v["comDepts"][0][$w])) {
                $isOverCompatible = $this->isOverCompatible($this->v["comDepts"][0][$w]);
            }
        }
        $this->v["emailsTo"] = [ "To Complainant" => [], "To Oversight" => [] ];
        $complainantUser = User::find($this->sessData->dataSets["Complaints"][0]->ComUserID);
        if ($complainantUser && isset($complainantUser->email)) {
            $name = $complainantUser->name;
            if (isset($this->sessData->dataSets["PersonContact"])
                && sizeof($this->sessData->dataSets["PersonContact"]) > 0
                && isset($this->sessData->dataSets["PersonContact"][0]->PrsnNameFirst)) {
                $name = $this->sessData->dataSets["PersonContact"][0]->PrsnNameFirst . ' '
                    . $this->sessData->dataSets["PersonContact"][0]->PrsnNameLast;
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
        $this->v["emailID"] = ($GLOBALS["SL"]->REQ->has('email') ? $GLOBALS["SL"]->REQ->email : -3);
        if ($this->v["emailID"] <= 0) {
            switch ($this->sessData->dataSets["Complaints"][0]->ComStatus) {
                case $GLOBALS["SL"]->def->getID('Complaint Status', 'OK to Submit to Oversight'):
                    if ($isOverCompatible) {
                        $this->v["emailID"] = 12; // Send to oversight agency
                    } else {
                        $this->v["emailID"] = 9; // How to manually submit
                    }
                    break;
                case $GLOBALS["SL"]->def->getID('Complaint Status', 'Submitted to Oversight'):
                case $GLOBALS["SL"]->def->getID('Complaint Status', 'Received by Oversight'):
                    $chk = SLEmailed::where('EmailedTree', 1)
                        ->where('EmailedRecID', $this->coreID)
                        ->where('EmailedEmailID', 7)
                        ->first();
                    if (!$chk || !isset($chk->created_at)) {
                        $this->v["emailID"] = 7; // Sent to oversight agency
                    }
                    break;
            }
        }
        
        $this->v["currEmail"] = [];
        if (isset($this->sessData->dataSets["LinksComplaintDept"]) 
            && sizeof($this->sessData->dataSets["LinksComplaintDept"]) > 0) {
            foreach ($this->sessData->dataSets["LinksComplaintDept"] as $deptLnk) {
                $this->loadDeptStuff($deptLnk->LnkComDeptDeptID);
                $this->v["currEmail"][] = $this->processEmail($this->v["emailID"], $deptLnk->LnkComDeptDeptID);
            }
        }
        if (sizeof($this->v["currEmail"]) > 0) { 
            foreach ($this->v["currEmail"] as $j => $email) {
                $this->v["needsWsyiwyg"] = true;
                $GLOBALS["SL"]->pageAJAX .= ' $("#emailBodyCust' . $j . 'ID").summernote({ height: 350 }); ';
            }
        }
        
        $emailSent = false;
        $emaInd = 0;
        while ($GLOBALS["SL"]->REQ->has('emailID') && $GLOBALS["SL"]->REQ->has('emailTo' . $emaInd . '') 
            && trim($GLOBALS["SL"]->REQ->get('emailTo' . $emaInd . '')) != '') {
            $userToID = -3;
            $chk = User::where('email', trim($GLOBALS["SL"]->REQ->get('emailTo' . $emaInd . '')))->first();
            if ($chk && isset($chk->id)) $userToID = $chk->id;
            $coreID = ((isset($this->coreID)) ? $this->coreID : -3);
            $emaTo = trim($GLOBALS["SL"]->REQ->get('emailTo' . $emaInd . ''));
            if ($emaTo == '--CUSTOM--') {
                $emaTo = trim($GLOBALS["SL"]->REQ->get('emailTo' . $emaInd . 'CustEmail'));
                //trim($GLOBALS["SL"]->REQ->get('emailTo' . $emaInd . 'CustName'))
            }
            $this->sendNewEmailSimple(trim($GLOBALS["SL"]->REQ->get('emailBodyCust' . $emaInd . '')), 
                trim($GLOBALS["SL"]->REQ->get('emailSubj' . $emaInd . '')), $emaTo, $GLOBALS["SL"]->REQ->get('emailID'), 
                $GLOBALS["SL"]->treeID, $coreID, $userToID);
            if (intVal($GLOBALS["SL"]->REQ->get('emailID')) == 12) {
                $this->sessData->dataSets["Complaints"][0]->update([ 
                    "ComStatus" => $GLOBALS["SL"]->def->getID('Complaint Status', 'Submitted to Oversight') ]);
                $deptID = $this->v["currEmail"][$emaInd]["deptID"];
                if (isset($GLOBALS["SL"]->x["depts"][$deptID]) 
                    && isset($GLOBALS["SL"]->x["depts"][$deptID]["whichOver"])) {
                    $whichRow = $GLOBALS["SL"]->x["depts"][$deptID][$GLOBALS["SL"]->x["depts"][$deptID]["whichOver"]];
                    if ($whichRow && isset($whichRow->OverID)) {
                        $this->logOverUpDate($coreID, $deptID, 'Submitted');
                    }
                }
                $newRev = new OPzComplaintReviews;
                $newRev->ComRevComplaint = $this->coreID;
                $newRev->ComRevUser      = $this->v["user"]->id;
                $newRev->ComRevDate      = date("Y-m-d H:i:s");
                $newRev->ComRevType      = 'Update';
                $newRev->ComRevStatus    = 'Submitted to Oversight';
                $newRev->save();
            }
            $emailSent = true;
            $emaInd++;
        }
        if ($emailSent) {
            return $this->redir('/complaint/read-' . $this->corePublicID, true);
        }
        $GLOBALS["SL"]->pageAJAX .= '$("#legitTypeBtn").click(function(){ $("#legitTypeDrop").slideToggle("fast"); });
        $("#newStatusUpdate").click(function(){ $("#newStatusUpdateBlock").slideToggle("fast"); });
        $("#newEmails").click(function(){ $("#analystEmailer").slideToggle("fast"); });
        $(document).on("change", ".changeEmailTo", function() { 
            var emaInd = $(this).attr("name").replace("emailTo", "");
            if (document.getElementById("emailTo"+emaInd+"ID") && document.getElementById("emailTo"+emaInd+"ID").value == "--CUSTOM--") {
                $("#emailTo"+emaInd+"CustID").slideDown("fast");
            } else {
                $("#emailTo"+emaInd+"CustID").slideUp("fast"); 
            }
        }); ' . (($this->v["view"] == 'update') ? 'window.location = "#new"; ' : '');
        $this->v["needsWsyiwyg"] = true;
        $this->v["complaintRec"] = $this->sessData->dataSets["Complaints"][0];
        return view('vendor.openpolice.nodes.1712-report-inc-staff-tools', $this->v)->render();
    }
    
}