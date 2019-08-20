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
    protected function logComplaintReview($type, $note, $status = 0)
    {
        $newReview = new OPzComplaintReviews;
        $newReview->ComRevComplaint = $this->coreID;
        $newReview->ComRevUser      = $this->v["user"]->id;
        $newReview->ComRevDate      = date("Y-m-d H:i:s");
        $newReview->ComRevType      = $type;
        $newReview->ComRevNote      = $note;
        $newReview->ComRevStatus    = $status;
        $newReview->save();
    }

    protected function printComplaintOversight()
    {
        $overRow = OPOversight::where('OverEmail', $this->v["user"]->email)
            ->first();
        if ($this->chkOverUserHasCore()) {
            if ($GLOBALS["SL"]->REQ->has('overUpdate') && intVal($GLOBALS["SL"]->REQ->get('overUpdate')) == 1
                && $overRow && isset($overRow->OverDeptID)) {
                $overUpdateRow = $this->getOverUpdateRow($this->coreID, $overRow->OverID);
                $status = 0;
                $evalNotes = (($GLOBALS["SL"]->REQ->has('overNote')) 
                    ? trim($GLOBALS["SL"]->REQ->overNote) : '');
                if ($GLOBALS["SL"]->REQ->has('overStatus')) { 
                    if ($GLOBALS["SL"]->REQ->overStatus == 'Received by Oversight') {
                        $this->logOverUpDate($this->coreID, $overRow->OverID, 'Received', $overUpdateRow);
                    } elseif ($GLOBALS["SL"]->REQ->overStatus == 'Investigated (Closed)') {
                        $this->logOverUpDate($this->coreID, $overRow->OverID, 'Investigated', $overUpdateRow);
                    }
                    $statusID = $GLOBALS["SL"]->def->getID('Complaint Status', 
                        trim($GLOBALS["SL"]->REQ->overStatus));
                    $this->sessData->dataSets["Complaints"][0]->update([ "ComStatus" => $statusID ]);
                    $status = $GLOBALS["SL"]->REQ->overStatus;
                }
                $this->logComplaintReview('Oversight', $evalNotes, $status);
            } elseif ($GLOBALS["SL"]->REQ->has('upResult') 
                && intVal($GLOBALS["SL"]->REQ->get('upResult')) == 1) {
                
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
            return '<div class="pT20 pB20"><div class="slCard mT20 mB20">'
                . '<h2>Incomplete: Session Attempt History</h2>'
                . view('vendor.survloop.admin.tree.tree-session-attempt-history', [
                    "core"     => $coreTots,
                    "nodeTots" => $nodeTots
                ])->render() . '<br /></div></div>';
        }
        return '';
    }
    
    protected function processComplaintOverDates()
    {
        $evalNotes = '';
        if (($this->v["isOwner"] || $this->v["isAdmin"]) && isset($this->v["comDepts"]) 
            && sizeof($this->v["comDepts"]) > 0) {
            foreach ($this->v["comDepts"] as $c => $dept) {
                if (isset($dept["deptRow"]) && isset($dept["deptRow"]->DeptName)) {
                    $fldID = 'over' . $dept["id"] . 'Status';
                    foreach ($this->v["oversightDateLookups"] as $d => $date) {
                        $dateFld = $fldID . $d . 'date';
                        $dbFld = $this->v["oversightDateLookups"][$d][0];
                        $oldDate = ((isset($this->v["comDepts"][$c]["overDates"]->{ $dbFld }))
                            ? $GLOBALS["SL"]->dateToTime(
                                $this->v["comDepts"][$c]["overDates"]->{ $dbFld })
                            : 0);
                        $newDate = 0;
                        if ($GLOBALS["SL"]->REQ->has($fldID) 
                            && is_array($GLOBALS["SL"]->REQ->get($fldID))
                            && in_array($d, $GLOBALS["SL"]->REQ->get($fldID)) 
                            && $GLOBALS["SL"]->REQ->has($dateFld)) {
                            $newDate = $GLOBALS["SL"]->dateToTime($GLOBALS["SL"]->REQ->get($dateFld));
                        }
                        if ($oldDate != $newDate) {
                            if (intVal($newDate) > 0) {
                                $this->v["comDepts"][$c]["overDates"]->update([
                                    $dbFld => date("Y-m-d", $newDate) . ' 00:00:00'
                                ]);
                            } else {
                                $this->v["comDepts"][$c]["overDates"]->update([
                                    $dbFld => NULL
                                ]);
                            }
                            $evalNotes .= 'Status with the ' . str_replace("Police Department", "PD", 
                                str_replace("Sheriff's Office", "Sheriff", $dept["deptRow"]->DeptName)) 
                                . ' date of ' . $this->v["oversightDateLookups"][$d][1] . ' changed from '
                                . str_replace('1/1', 'N/A', date('n/j', $oldDate)) . ' to ' 
                                . str_replace('1/1', 'N/A', date('n/j', $newDate)) . '. ';
                        }
                    }
                }
            }
        }
        return $evalNotes;
    }
    
    protected function printComplaintOwner()
    {
        $this->loadOversightDateLookups();
        $this->prepEmailComData();
        if ($this->v["isOwner"]) {
            $this->processOwnerUpdate();
            $this->processOwnerPrivacy();
        }
        $depts = ((isset($this->sessData->dataSets["Departments"])) 
            ? $this->sessData->dataSets["Departments"] : []);
        $oversights = ((isset($this->sessData->dataSets["Oversight"]))
            ? $this->sessData->dataSets["Oversight"] : []);
        $overUpdates = ((isset($this->sessData->dataSets["LinksComplaintOversight"]))
            ? $this->sessData->dataSets["LinksComplaintOversight"] : []);
        $ret = view('vendor.openpolice.nodes.1714-report-inc-owner-tools', [
            "user"        => $this->v["user"],
            "complaint"   => $this->sessData->dataSets["Complaints"][0],
            "depts"       => $depts,
            "oversights"  => $oversights,
            "overUpdates" => $overUpdates,
            "overList"    => $this->oversightList(),
            "warning"     => $this->multiRecordCheckDelWarn(),
            "comDepts"    => $this->v["comDepts"],
            "oversightDates" => $this->v["oversightDateLookups"]
        ])->render();
        $title = $this->getCurrComplaintEngLabel() . ': Your Toolkit';
        return '<div class="pT20 pB20">'
            . $GLOBALS["SL"]->printAccard($title, $ret, true)
            . '</div>';
    }

    protected function processOwnerPrivacy()
    {
        if ($GLOBALS["SL"]->REQ->has('ownerPublish') 
            && intVal($GLOBALS["SL"]->REQ->get('ownerPublish')) == 1
            && $GLOBALS["SL"]->REQ->has('n2018fld') 
            && intVal($GLOBALS["SL"]->REQ->get('n2018fld')) > 0) {
            $evalNotes = $GLOBALS["SL"]->def->getVal('Privacy Types', $GLOBALS["SL"]->REQ->get('n2018fld'))
                . ' Privacy Option Selected';
            $this->logComplaintReview('Owner', $evalNotes, 'OK to Submit to Oversight');
            $this->sessData->dataSets["Complaints"][0]->update([ 
                "ComPrivacy" => $GLOBALS["SL"]->REQ->get('n2018fld'),    
                "ComStatus"  => $GLOBALS["SL"]->def->getID('Complaint Status', 
                    'OK to Submit to Oversight')
            ]);
            return true;
        }
        return false;
    }

    protected function processOwnerUpdate()
    {
        if ($GLOBALS["SL"]->REQ->has('ownerUpdate') 
            && intVal($GLOBALS["SL"]->REQ->get('ownerUpdate')) == 1 
            && isset($this->sessData->dataSets["Oversight"])) {
            $evalNotes = (($GLOBALS["SL"]->REQ->has('overNote')) 
                ? trim($GLOBALS["SL"]->REQ->overNote) : '')
                . $this->processComplaintOverDates();
            $overID = $this->sessData->dataSets["Oversight"][0]->OverID;
            if (isset($this->sessData->dataSets["Oversight"][1]) 
                && $this->sessData->dataSets["Oversight"][1]->OverType == 303) {
                $overID = $this->sessData->dataSets["Oversight"][1]->OverID;
            }
            $overUpdateRow = $this->getOverUpdateRow($this->coreID, $overID);
            $this->logComplaintReview('Owner', $evalNotes, $GLOBALS["SL"]->REQ->overStatus);
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
                        "ComStatus" => $GLOBALS["SL"]->def->getID('Complaint Status', 
                            $GLOBALS["SL"]->REQ->overStatus)
                    ]);
                }
            }
            return true;
        }
        return false;
    }

    protected function getCurrComplaintEngLabel()
    {
        $ret = '';
        if (!isset($this->sessData->dataSets["Complaints"][0]->ComPublicID) 
            || intVal($this->sessData->dataSets["Complaints"][0]->ComPublicID) <= 0) {
            $ret = 'Incomplete Complaint #' 
                . $this->sessData->dataSets["Complaints"][0]->ComID;
        } else {
            $ret = 'Complaint #' . $this->sessData->dataSets["Complaints"][0]->ComPublicID;
        }
        return $ret;
    }
    
    protected function oversightList()
    {
        $ret = '';
        if (isset($this->sessData->dataSets["Oversight"]) 
            && sizeof($this->sessData->dataSets["Oversight"]) > 0) {
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
        $this->loadOversightDateLookups();
        $this->prepEmailComData();
        $this->v["firstRevDone"] = false;
        $this->v["firstReview"] = true;
        if ($GLOBALS["SL"]->REQ->has('n1712fld') && intVal($GLOBALS["SL"]->REQ->n1712fld) > 0) {

            $newTypeVal = $GLOBALS["SL"]->def->getVal('OPC Staff/Internal Complaint Type', 
                $GLOBALS["SL"]->REQ->n1712fld);
            $this->logComplaintReview('First', '', $newTypeVal);
            $com = OPComplaints::find($this->coreID);
            $com->comType = intVal($GLOBALS["SL"]->REQ->n1712fld);
            $com->save();
            $this->v["firstRevDone"] = true;
            $this->v["firstReview"] = false;

        } elseif ($GLOBALS["SL"]->REQ->has('save')) {
            $status = 0;
            $evalNotes = (($GLOBALS["SL"]->REQ->has('revNote')) ? trim($GLOBALS["SL"]->REQ->revNote) : '')
                . $this->processComplaintOverDates();
            if ($GLOBALS["SL"]->REQ->has('revStatus')) {
                $status = $GLOBALS["SL"]->REQ->revStatus;
                if (in_array($GLOBALS["SL"]->REQ->revStatus, ['Hold: Go Gold', 'Hold: Not Sure'])) {
                    $this->sessData->dataSets["Complaints"][0]->ComStatus 
                        = $GLOBALS["SL"]->def->getID('Complaint Status', 'Hold');
                } elseif ($GLOBALS["SL"]->REQ->revStatus == 'Needs More Work') {
                    $this->sessData->dataSets["Complaints"][0]->ComStatus 
                        = $GLOBALS["SL"]->def->getID('Complaint Status', 'Needs More Work');
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
                if ($GLOBALS["SL"]->REQ->revComplaintType 
                    == $GLOBALS["SL"]->def->getID('Complaint Status', 'Incomplete')) {
                    $this->sessData->dataSets["Complaints"][0]->ComStatus = $GLOBALS["SL"]->REQ->revComplaintType;
                    $this->sessData->dataSets["Complaints"][0]->ComType 
                        = $GLOBALS["SL"]->def->getID('OPC Staff/Internal Complaint Type', 'Unreviewed');
                } else { 
                    $newTypeVal = $GLOBALS["SL"]->def->getVal('OPC Staff/Internal Complaint Type', 
                        $GLOBALS["SL"]->REQ->revComplaintType);
                    if ($newTypeVal != 'Police Complaint') {
                        $newRev->ComRevStatus = $newTypeVal;
                    }
                    $this->sessData->dataSets["Complaints"][0]->ComType = $GLOBALS["SL"]->REQ->revComplaintType;
                }
            }
            $this->logComplaintReview('Update', $evalNotes, $status);
            $this->sessData->dataSets["Complaints"][0]->save();
        }
        $this->v["lastReview"] = true;
        $this->v["history"]    = [];
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
                        && $r->ComRevStatus == 'Submitted to Oversight') 
                        ? $r->ComRevNextAction : $r->ComRevStatus) . '</span>';
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
        $emails = SLEmailed::whereIn('EmailedTree', [1, 42])
            ->where('EmailedRecID', $this->coreID) //corePublicID
            ->orderBy('created_at', 'asc')
            ->get();
        if ($emails->isNotEmpty()) {
            foreach ($emails as $i => $e) {
                if (!isset($allUserNames[$e->EmailedFromUser])) {
                    $allUserNames[$e->EmailedFromUser] = $this->printUserLnk($e->EmailedFromUser);
                }
                $desc = '<a href="javascript:;" id="hidivBtnEma' . $e->EmailedID . '" class="hidivBtn">"' 
                    . $e->EmailedSubject . '"</a><br />sent to ' . $e->EmailedTo . '<div id="hidivEma' 
                    . $e->EmailedID . '" class="disNon p10">' . $e->EmailedBody 
                    . '</div><div style="margin-bottom: -36px;"></div>';
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
        $this->v["emailsTo"] = [
            "To Complainant" => [],
            "To Oversight"   => []
        ];
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
        $this->v["emailID"] = ($GLOBALS["SL"]->REQ->has('email') 
            ? intVal($GLOBALS["SL"]->REQ->email) : -3);
        if ($this->v["emailID"] <= 0) {
            switch ($this->sessData->dataSets["Complaints"][0]->ComStatus) {
                case $GLOBALS["SL"]->def->getID('Complaint Status', 'OK to Submit to Oversight'):
                    if ($isOverCompatible) {
                        $this->v["emailID"] = 12; // Send to investigative agency
                    } else {
                        $this->v["emailID"] = 9; // How to manually submit
                    }
                    break;
                case $GLOBALS["SL"]->def->getID('Complaint Status', 'Submitted to Oversight'):
                case $GLOBALS["SL"]->def->getID('Complaint Status', 'Received by Oversight'):
                    $chk = SLEmailed::whereIn('EmailedTree', [1, 42])
                        ->where('EmailedRecID', $this->coreID)
                        ->where('EmailedEmailID', 7)
                        ->first();
                    if (!$chk || !isset($chk->created_at)) {
                        $this->v["emailID"] = 7; // Sent to investigative agency
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
        if ($this->v["emailID"] > 0 && sizeof($this->v["currEmail"]) > 0) { 
            $this->v["needsWsyiwyg"] = true;
            foreach ($this->v["currEmail"] as $j => $email) {
                $GLOBALS["SL"]->pageAJAX .= ' $("#emailBodyCust' . $j . 'ID").summernote({ height: 350 }); ';
            }
        }
        
        $emailSent = false;
        $emaInd = 0;
        while ($GLOBALS["SL"]->REQ->has('emailID') && $GLOBALS["SL"]->REQ->has('emailTo' . $emaInd . '') 
            && trim($GLOBALS["SL"]->REQ->get('emailTo' . $emaInd . '')) != '') {
            $userToID = -3;
            $chk = User::where('email', trim($GLOBALS["SL"]->REQ->get('emailTo' . $emaInd . '')))->first();
            if ($chk && isset($chk->id)) {
                $userToID = $chk->id;
            }
            $coreID = ((isset($this->coreID)) ? $this->coreID : -3);
            $emaTo = trim($GLOBALS["SL"]->REQ->get('emailTo' . $emaInd . ''));
            if ($emaTo == '--CUSTOM--') {
                $emaTo = trim($GLOBALS["SL"]->REQ->get('emailTo' . $emaInd . 'CustEmail'));
                //trim($GLOBALS["SL"]->REQ->get('emailTo' . $emaInd . 'CustName'))
            }
            $this->sendNewEmailFromCurrUser(trim($GLOBALS["SL"]->REQ->get('emailBodyCust' . $emaInd . '')), 
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
        return '<div class="pT20 pB20">' . $GLOBALS["SL"]->printAccard(
                $this->getCurrComplaintEngLabel() . ': Admin Toolkit',
                view('vendor.openpolice.nodes.1712-report-inc-staff-tools', $this->v)->render(),
                false
            ) . '</div>';
    }
    
}