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

    protected function clearComplaintCaches()
    {
        return $GLOBALS["SL"]->forgetAllItemCaches(42, $this->coreID);
    }

    protected function printComplaintOversight()
    {
        $overRow = OPOversight::where('OverEmail', $this->v["user"]->email)
            ->first();
        return view('vendor.openpolice.nodes.1711-report-inc-oversight-tools', [
            "user"        => $this->v["user"],
            "complaint"   => $this->sessData->dataSets["Complaints"][0],
            "overRow"     => $overRow
        ])->render();
    }

    protected function saveComplaintOversight()
    {
        $overRow = OPOversight::where('OverEmail', $this->v["user"]->email)
            ->first();
        if ($this->chkOverUserHasCore()) {
            if ($GLOBALS["SL"]->REQ->has('overUpdate') 
                && intVal($GLOBALS["SL"]->REQ->get('overUpdate')) == 1
                && $overRow && isset($overRow->OverDeptID)) {
                $overUpdateRow = $this->getOverUpdateRow($this->coreID, $overRow->OverID);
                $status = 0;
                $evalNotes = (($GLOBALS["SL"]->REQ->has('overNote')) 
                    ? trim($GLOBALS["SL"]->REQ->overNote) : '');
                if ($GLOBALS["SL"]->REQ->has('overStatus')) { 
                    if ($GLOBALS["SL"]->REQ->overStatus == 'Received by Oversight') {
                        $this->logOverUpDate(
                            $this->coreID, 
                            $overRow->OverID, 
                            'Received', 
                            $overUpdateRow
                        );
                    } elseif ($GLOBALS["SL"]->REQ->overStatus == 'Investigated (Closed)') {
                        $this->logOverUpDate(
                            $this->coreID, 
                            $overRow->OverID, 
                            'Investigated', 
                            $overUpdateRow
                        );
                    }
                    $statusID = $GLOBALS["SL"]->def->getID('Complaint Status', 
                        trim($GLOBALS["SL"]->REQ->overStatus));
                    $this->sessData->dataSets["Complaints"][0]->update([
                        "ComStatus" => $statusID
                    ]);
                    $status = $GLOBALS["SL"]->REQ->overStatus;
                }
                $this->logComplaintReview('Oversight', $evalNotes, $status);
                $this->clearComplaintCaches();
            } elseif ($GLOBALS["SL"]->REQ->has('upResult') 
                && intVal($GLOBALS["SL"]->REQ->get('upResult')) == 1) {
                
                
                $this->clearComplaintCaches();
            }
        }
        return true;
    }
    
    protected function printComplaintSessPath()
    {
        if ($this->v["isOwner"] 
            || $this->v["user"]->hasRole('administrator|databaser|staff')) {
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
        if (($this->v["isOwner"] || $this->v["isAdmin"]) 
            && isset($this->v["comDepts"]) && sizeof($this->v["comDepts"]) > 0) {
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
                            $newDate = $GLOBALS["SL"]
                                ->dateToTime($GLOBALS["SL"]->REQ->get($dateFld));
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
                            $evalNotes .= 'Status with the ' 
                                . str_replace("Police Department", "PD", 
                                    str_replace("Sheriff's Office", "Sheriff", 
                                        $dept["deptRow"]->DeptName)) . ' date of ' 
                                . $this->v["oversightDateLookups"][$d][1] . ' changed from '
                                . str_replace('1/1', 'N/A', date('n/j', $oldDate)) . ' to ' 
                                . str_replace('1/1', 'N/A', date('n/j', $newDate)) . '. ';
                        }
                    }
                }
            }
        }
        return $evalNotes;
    }

    protected function processComplaintOwnerStatus()
    {
        $evalNotes = '';
        if (isset($this->v["comDepts"]) && sizeof($this->v["comDepts"]) > 0) {
            foreach ($this->v["comDepts"] as $c => $dept) {
                if (isset($dept["deptRow"]) && isset($dept["deptRow"]->DeptName)) {
                    $fld = 'over' . $dept["deptRow"]->DeptID . 'Status';
                    if ($GLOBALS["SL"]->REQ->has($fld) 
                        && is_array($GLOBALS["SL"]->REQ->get($fld))) {
                        foreach ($this->v["oversightDateLookups"] as $d => $date) {
                            if (in_array($d, $GLOBALS["SL"]->REQ->get($fld))) {
                                $evalNotes .= $this->processComplaintOwnerStatusChecked($date);
                            }
                        }
                    }
                }
            }
        }
        return $evalNotes;
    }

    protected function processComplaintOwnerStatusChecked($date)
    {
        $newStatus = '';
        $currStatus = $GLOBALS["SL"]->def->getVal('Complaint Status', 
            $this->sessData->dataSets["Complaints"][0]->ComStatus);
        if ($date[0] == 'LnkComOverSubmitted') {
            if ($currStatus == 'OK to Submit to Oversight') {
                $newStatus = 'Submitted to Oversight';
            }
        } elseif ($date[0] == 'LnkComOverReceived') {
            if (in_array($currStatus, [
                'OK to Submit to Oversight', 
                'Submitted to Oversight'])) {
                $newStatus = 'Received by Oversight';
            }
        } elseif ($date[0] == 'LnkComOverInvestigated') {
            if (in_array($currStatus, [
                'OK to Submit to Oversight', 
                'Submitted to Oversight', 
                'Received by Oversight'])) {
                $newStatus = 'Investigated (Closed)';
            }
        }
        if ($newStatus != '') {
            $this->sessData->dataSets["Complaints"][0]->update([
                "ComStatus" => $GLOBALS["SL"]->def->getID('Complaint Status', $newStatus)
            ]);
        }
        return '';
    }
    
    protected function printComplaintOwner()
    {
        $this->loadOversightDateLookups();
        $this->prepEmailComData();
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
            "privacyForm" => $this->printComplaintOwnerPrivacyForm(),
            "comDepts"    => $this->v["comDepts"],
            "oversightDates" => $this->v["oversightDateLookups"]
        ])->render();
        $title = $this->getCurrComplaintEngLabel() . ': Your Toolkit';
        return '<div class="pT20 pB20">'
            . $GLOBALS["SL"]->printAccard($title, $ret, true)
            . '</div>';
    }
    
    protected function printComplaintOwnerPrivacyForm()
    {
        $status = $this->sessData->dataSets["Complaints"][0]->ComStatus;
        $status = $GLOBALS["SL"]->def->getVal('Complaint Status', $status);
        $tooLateForAnon = [
            'Submitted to Oversight', 
            'Received by Oversight', 
            'Declined To Investigate (Closed)', 
            'Investigated (Closed)'
        ];
        return view('vendor.openpolice.inc-static-privacy-page', [
            "complaint"  => $this->sessData->dataSets["Complaints"][0],
            "twoOptions" => in_array($status, $tooLateForAnon)
        ])->render();
    }

    protected function processOwnerUpdate()
    {
        if ($this->v["isOwner"]) {
            $hasUpdate = ($GLOBALS["SL"]->REQ->has('ownerUpdate') 
                && intVal($GLOBALS["SL"]->REQ->get('ownerUpdate')) == 1 
                && isset($this->sessData->dataSets["Oversight"]));
            $hasPrivacy = ($GLOBALS["SL"]->REQ->has('ownerPublish') 
                && intVal($GLOBALS["SL"]->REQ->get('ownerPublish')) == 1
                && $GLOBALS["SL"]->REQ->has('n2018fld') 
                && intVal($GLOBALS["SL"]->REQ->get('n2018fld')) > 0);
            if ($hasUpdate || $hasPrivacy) {
                $this->loadOversightDateLookups();
                $this->prepEmailComData();
                if ($hasUpdate) {
                    $this->processOwnerUpdateStatus();
                } elseif ($hasPrivacy) {
                    $this->processOwnerUpdatePrivacy();
                }
                $this->clearComplaintCaches();
                return true;
            }
        }
        return false;
    }

    protected function processOwnerUpdateStatus()
    {
        $evalNotes = (($GLOBALS["SL"]->REQ->has('overNote')) 
            ? trim($GLOBALS["SL"]->REQ->overNote) : '')
            . $this->processComplaintOverDates();
        $overID = $this->sessData->dataSets["Oversight"][0]->OverID;
        if (isset($this->sessData->dataSets["Oversight"][1]) 
            && $this->sessData->dataSets["Oversight"][1]->OverType == 303) {
            $overID = $this->sessData->dataSets["Oversight"][1]->OverID;
        }
        $overUpdateRow = $this->getOverUpdateRow($this->coreID, $overID);
        $evalNotes .= $this->processComplaintOwnerStatus();

        $this->logComplaintReview('Owner', $evalNotes, $GLOBALS["SL"]->REQ->overStatus);
        if ($GLOBALS["SL"]->REQ->has('overStatus')) {
            if (trim($GLOBALS["SL"]->REQ->overStatus) == 'Received by Oversight') {
                $this->logOverUpDate($this->coreID, $overID, 'Received', $overUpdateRow);
                if ($this->sessData->dataSets["Complaints"][0]->ComStatus 
                    == $GLOBALS["SL"]->def->getID('Complaint Status', 
                        'OK to Submit to Oversight')) {
                    $this->sessData->dataSets["Complaints"][0]->update([ 
                        "ComStatus" => $GLOBALS["SL"]->def->getID('Complaint Status', 
                            'Submitted to Oversight') ]);
                }
            } else {
                if ($GLOBALS["SL"]->REQ->overStatus == 'Investigated (Closed)') {
                    $this->logOverUpDate(
                        $this->coreID, 
                        $overID, 
                        'Investigated', 
                        $overUpdateRow
                    );
                } elseif (in_array($GLOBALS["SL"]->REQ->overStatus, [
                    'Submitted to Oversight', 'OK to Submit to Oversight'])) {
                    if (isset($overUpdateRow->LnkComOverReceived) 
                        && $overUpdateRow->LnkComOverReceived != '') {
                        $overUpdateRow->LnkComOverReceived = NULL;
                        $overUpdateRow->save();
                    }
                }
                $this->sessData->dataSets["Complaints"][0]->update([ 
                    "ComStatus" => $GLOBALS["SL"]->def
                        ->getID('Complaint Status', $GLOBALS["SL"]->REQ->overStatus)
                ]);
            }
        }
        return true;
    }

    protected function processOwnerUpdatePrivacy()
    {
        $evalNotes = $GLOBALS["SL"]->def
            ->getVal('Privacy Types', $GLOBALS["SL"]->REQ->get('n2018fld'))
            . ' Privacy Option Selected';
        $this->logComplaintReview('Owner', $evalNotes, 'OK to Submit to Oversight');
        $this->sessData->dataSets["Complaints"][0]->update([ 
            "ComPrivacy" => $GLOBALS["SL"]->REQ->get('n2018fld'),    
            "ComStatus"  => $GLOBALS["SL"]->def
                ->getID('Complaint Status', 'OK to Submit to Oversight')
        ]);
        return true;
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
        $this->loadReportUploadTypes();
        $this->loadOversightDateLookups();
        $this->prepEmailComData();
        $this->loadComplaintAdminHistory();
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
                $this->v["currEmail"][] = $this->processEmail(
                    $this->v["emailID"], 
                    $deptLnk->LnkComDeptDeptID
                );
            }
        }
        $hasEmailLoaded = ($this->v["emailID"] > 0 && sizeof($this->v["currEmail"]) > 0);
        if ($hasEmailLoaded) {
            $this->v["needsWsyiwyg"] = true;
            foreach ($this->v["currEmail"] as $j => $email) {
                $GLOBALS["SL"]->pageAJAX .= ' $("#emailBodyCust' . $j 
                    . 'ID").summernote({ height: 350 }); ';
            }
        }
        
        if ($this->sendComplaintAdminEmail()) {
            return $this->redir('/complaint/read-' . $this->corePublicID . '?refresh=1', true);
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
                $hasEmailLoaded
            ) . '</div>';
    }
    
    protected function saveComplaintAdmin()
    {
        $hasFirstReview = ($GLOBALS["SL"]->REQ->has('n1712fld') 
            && intVal($GLOBALS["SL"]->REQ->n1712fld) > 0);
        $hasSave = $GLOBALS["SL"]->REQ->has('save');
        $hasReportUpload = ($GLOBALS["SL"]->REQ->has('reportUp') 
            && $GLOBALS["SL"]->REQ->has('reportUpType')
            && trim($GLOBALS["SL"]->REQ->reportUpType) != '');
        if ($hasFirstReview || $hasSave || $hasReportUpload) {
            $this->loadReportUploadTypes();
            $this->loadOversightDateLookups();
            $this->prepEmailComData();
            if ($hasFirstReview) {
                $this->saveComplaintAdminFirstReview();
            } elseif ($hasSave) {
                $this->saveComplaintAdminStatus();
            } elseif ($hasReportUpload) {
                $this->saveComplaintAdminUpload();
            }
            $this->clearComplaintCaches();
        }
        return true;
    }
    
    protected function saveComplaintAdminFirstReview()
    {
        $newTypeVal = $GLOBALS["SL"]->def
            ->getVal('Complaint Type', $GLOBALS["SL"]->REQ->n1712fld);
        $this->logComplaintReview('First', '', $newTypeVal);
        $com = OPComplaints::find($this->coreID);
        $com->comType = intVal($GLOBALS["SL"]->REQ->n1712fld);
        $com->save();
        $this->v["firstRevDone"] = true;
        $this->v["firstReview"] = false;
        return true;
    }
    
    protected function saveComplaintAdminStatus()
    {
        $status = 0;
        $evalNotes = (($GLOBALS["SL"]->REQ->has('revNote')) 
            ? trim($GLOBALS["SL"]->REQ->revNote) : '') . $this->processComplaintOverDates();
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
                    = $GLOBALS["SL"]->def->getID('Complaint Status', 
                        $GLOBALS["SL"]->REQ->revStatus);
            }
        }
        if ($GLOBALS["SL"]->REQ->has('revComplaintType')) {
            if ($GLOBALS["SL"]->REQ->revComplaintType 
                == $GLOBALS["SL"]->def->getID('Complaint Status', 'Incomplete')) {
                $this->sessData->dataSets["Complaints"][0]->ComStatus 
                    = $GLOBALS["SL"]->REQ->revComplaintType;
                $this->sessData->dataSets["Complaints"][0]->ComType 
                    = $GLOBALS["SL"]->def->getID('Complaint Type', 'Unreviewed');
            } else { 
                $newTypeVal = $GLOBALS["SL"]->def->getVal('Complaint Type', 
                    $GLOBALS["SL"]->REQ->revComplaintType);
                if ($newTypeVal != 'Police Complaint') {
                    $newRev->ComRevStatus = $newTypeVal;
                }
                $this->sessData->dataSets["Complaints"][0]->ComType 
                    = $GLOBALS["SL"]->REQ->revComplaintType;
            }
        }
        $this->logComplaintReview('Update', $evalNotes, $status);
        $this->sessData->dataSets["Complaints"][0]->save();
        return true;
    }
    
    protected function saveComplaintAdminUpload()
    {
        $ret = '';
        if ($GLOBALS["SL"]->REQ->hasFile('reportToUpload')) { // file upload
            $upRow->UpUploadFile = $GLOBALS["SL"]->REQ->file('reportToUpload')
                ->getClientOriginalName();
            $extension = $GLOBALS["SL"]->REQ->file('reportToUpload')
                ->getClientOriginalExtension();
            $mimetype = $GLOBALS["SL"]->REQ->file('reportToUpload')->getMimeType();
            $size = $GLOBALS["SL"]->REQ->file('reportToUpload')->getSize();
            if (strtolower($extension) == "pdf"
                && strtolower($mimetype) == "application/pdf") {
                if (!$GLOBALS["SL"]->REQ->file('reportToUpload')->isValid()) {
                    $ret .= '<div class="txtDanger">Upload Error.' 
                        . /* $_FILES["up" . $nID . "File"]["error"] . */ '</div>';
                } else {
                    $upFold = $this->v["reportUploadFolder"];
                    $this->mkNewFolder($upFold);
                    $filename = $this->sessData->dataSets["Complaints"][0]->ComID . '-' 
                        . $GLOBALS["SL"]->REQ->reportUpType . '.pdf';
                    //if ($GLOBALS["SL"]->debugOn) { $ret .= "saving as filename: " . $upFold . $filename . "<br>"; }
                    if (file_exists($upFold . $filename)) {
                        Storage::delete($upFold . $filename);
                    }
                    $GLOBALS["SL"]->REQ->file('reportToUpload')->move($upFold, $filename);
                }
            } else {
                $ret .= '<div class="txtDanger">'
                    . 'Invalid file. Please check the format and try again.</div>';
            }
        }
        return true;
    }
    
    protected function sendComplaintAdminEmail()
    {
        $emailSent = false;
        $emaInd = 0;
        while ($GLOBALS["SL"]->REQ->has('emailID') 
            && $GLOBALS["SL"]->REQ->has('emailTo' . $emaInd . '') 
            && trim($GLOBALS["SL"]->REQ->get('emailTo' . $emaInd . '')) != '') {
            $userToID = -3;
            $chk = User::where('email', trim($GLOBALS["SL"]->REQ->get('emailTo' . $emaInd . '')))
                ->first();
            if ($chk && isset($chk->id)) {
                $userToID = $chk->id;
            }
            $coreID = ((isset($this->coreID)) ? $this->coreID : -3);
            $emaTo = trim($GLOBALS["SL"]->REQ->get('emailTo' . $emaInd . ''));
            if ($emaTo == '--CUSTOM--') {
                $emaTo = trim($GLOBALS["SL"]->REQ->get('emailTo' . $emaInd . 'CustEmail'));
                //trim($GLOBALS["SL"]->REQ->get('emailTo' . $emaInd . 'CustName'))
            }
            $cc = $bcc = '';
            if (trim($GLOBALS["SL"]->REQ->get('emailCC' . $emaInd . '')) != '') {
                $cc = trim($GLOBALS["SL"]->REQ->get('emailCC' . $emaInd . ''));
            }
            if (trim($GLOBALS["SL"]->REQ->get('emailBCC' . $emaInd . '')) != '') {
                $bcc = trim($GLOBALS["SL"]->REQ->get('emailBCC' . $emaInd . ''));
            }
            $this->sendNewEmailFromCurrUser(
                trim($GLOBALS["SL"]->REQ->get('emailBodyCust' . $emaInd . '')), 
                trim($GLOBALS["SL"]->REQ->get('emailSubj' . $emaInd . '')), 
                $emaTo, 
                $GLOBALS["SL"]->REQ->get('emailID'), 
                $GLOBALS["SL"]->treeID, 
                $coreID, 
                $userToID,
                $cc,
                $bcc
            );
            if (intVal($GLOBALS["SL"]->REQ->get('emailID')) == 12) {
                $this->sessData->dataSets["Complaints"][0]->update([ 
                    "ComStatus" => $GLOBALS["SL"]->def->getID('Complaint Status', 'Submitted to Oversight')
                ]);
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
        return $emailSent;
    }
    
    protected function loadComplaintAdminHistory()
    {
        $this->v["firstRevDone"] = false;
        $this->v["firstReview"] = true;
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
                $desc = '<span class="slBlueDark">' . ((isset($r->ComRevNextAction) 
                    && trim($r->ComRevNextAction) == 'Complaint Received'
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
                $desc = '<a href="javascript:;" id="hidivBtnEma' . $e->EmailedID 
                    . '" class="hidivBtn">"' . $e->EmailedSubject . '"</a><br />sent to ' 
                    . $e->EmailedTo . '<div id="hidivEma' . $e->EmailedID 
                    . '" class="disNon p10">' . $e->EmailedBody 
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
        return true;
    }
    
}