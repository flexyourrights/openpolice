<?php
/**
  * OpenReportToolsAdmin is mid-level class with functions to print and process
  * administrative forms used by OpenPolice.org staff.
  *
  * OpenPolice.org
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <rockhoppers@runbox.com>
  * @since v0.2.4
  */
namespace FlexYourRights\OpenPolice\Controllers;

use App\Models\User;
use App\Models\SLEmails;
use App\Models\SLEmailed;
use App\Models\OPComplaints;
use App\Models\OPDepartments;
use App\Models\OPOversight;
use App\Models\OPLinksComplaintDept;
use App\Models\OPLinksComplaintOversight;
use App\Models\OPzComplaintReviews;
use FlexYourRights\OpenPolice\Controllers\OpenReportToolsOversight;

class OpenReportToolsAdmin extends OpenReportToolsOversight
{
    /**
     * Prints a jQuery load of this complaint report within the 
     * admin dashboard.
     *
     * @param  int $nID
     * @return string
     */
    protected function printComplaintReportForAdmin($nID)
    {
        if ($this->coreID > 0) {
            $src = '/complaint/readi-' . $this->coreID;
            if (isset($this->sessData->dataSets["complaints"])
                && sizeof($this->sessData->dataSets["complaints"]) > 0
                && isset($this->sessData->dataSets["complaints"][0]->com_public_id)
                && intVal($this->sessData->dataSets["complaints"][0]->com_public_id) > 0) {
                $src = '/complaint/read-' 
                    . intVal($this->sessData->dataSets["complaints"][0]->com_public_id);
            }
            $src .= '/full?ajax=1&wdg=1' . $GLOBALS["SL"]->getAnyReqParams();
            $GLOBALS["SL"]->pageAJAX .= '$("#admDashReportWrap").load("' . $src . '");';
        }
        return view(
            'vendor.openpolice.nodes.2377-admin-dash-load-complaint', 
            [ "coreID" => $this->coreID ]
        )->render();
    }

    /**
     * Print staff toolkit form for the first complaint review.
     *
     * @return string
     */
    protected function printComplaintAdminFirstReview()
    {
        if ($this->coreID > 0
            && isset($this->sessData->dataSets["complaints"])
            && sizeof($this->sessData->dataSets["complaints"]) > 0
            && $this->isStaffOrAdmin()) {
            echo view(
                'vendor.openpolice.nodes.2844-report-staff-form-first-review',
                [ "complaintRec" => $this->sessData->dataSets["complaints"][0] ]
            )->render();
        }
        exit;
    }

    /**
     * Print staff toolkit form to update complaint status, 
     * and document uploads.
     *
     * @return string
     */
    protected function printComplaintAdminFormStatus()
    {
        if ($this->coreID > 0
            && isset($this->sessData->dataSets["complaints"])
            && sizeof($this->sessData->dataSets["complaints"]) > 0
            && $this->isStaffOrAdmin()) {
            $this->initComplaintToolbox();
            $this->loadOversightDateLookups();
            $this->loadComplaintAdminHistory();
            $this->prepEmailComplaintData();
            echo view(
                'vendor.openpolice.nodes.2842-report-staff-form-status',
                $this->v
            )->render();
        }
        exit;
    }

    /**
     * Print staff admin tools to send email regarding a complaint.
     *
     * @return string
     */
    protected function printComplaintAdminEmailForm()
    {
        if ($this->coreID > 0
            && isset($this->sessData->dataSets["complaints"])
            && sizeof($this->sessData->dataSets["complaints"]) > 0
            && $this->isStaffOrAdmin()) {
            $this->initComplaintToolbox();
            $this->prepEmailComplaintData();
            $this->chkForEmailID();
            $this->loadComplaintEmailList();
            $this->loadCurrEmail();
            $this->prepAdminComplaintEmailing();
            if ($GLOBALS["SL"]->REQ->has('ajaxEmaForm')) {
                echo view(
                    'vendor.openpolice.nodes.1712-report-inc-staff-tools-email-form', 
                    $this->v
                )->render();
                exit;
            }
            echo view(
                'vendor.openpolice.nodes.2846-report-inc-staff-tools-email',
                $this->v
            )->render();
        }
        exit;
    }

    /**
     * Print staff toolkit form to edit complaint details.
     *
     * @return string
     */
    protected function printComplaintAdminFormEdits()
    {
        if ($this->coreID > 0
            && isset($this->sessData->dataSets["complaints"])
            && sizeof($this->sessData->dataSets["complaints"]) > 0
            && $this->isStaffOrAdmin()) {
            $this->initComplaintToolbox();
            echo view(
                'vendor.openpolice.nodes.2848-report-inc-staff-tools-edits',
                $this->v
            )->render();
        }
        exit;
    }

    /**
     * Print OpenPolice.org staff admin tools for managing one complaint.
     *
     * @return string
     */
    protected function printComplaintAdmin()
    {
        $this->initComplaintToolbox();
        $this->loadOversightDateLookups();
        //$this->prepEmailComplaintData();
        $this->loadComplaintAdminHistory();
        //$GLOBALS["SL"]->loadStates();
        $this->v["needsWsyiwyg"]  = true;
        return $this->printComplaintAdminCard();
    }

    /**
     * Wrap OpenPolice.org staff admin tools in an accordian card.
     *
     * @return string
     */
    protected function printComplaintAdminCard()
    {
        $hasEmailLoaded = $this->prepAdminComplaintEmailing();
        if ($GLOBALS["SL"]->REQ->has('open')) {
            $hasEmailLoaded = true;
        }
        $hasEmailSent = 0;
        if ($GLOBALS["SL"]->REQ->has('emailSent')) {
            $hasEmailSent = intVal($GLOBALS["SL"]->REQ->has('emailSent'));
        }
        $status = $this->printComplaintStatus(
            $this->v["complaintRec"]->com_status, 
            $this->v["complaintRec"]->com_type
        );
        $status = str_replace('Investigative Agency', 'IA', $status);
        $this->v["toolkitTitle"] = '<span class="slBlueDark">' 
            . $this->getCurrComplaintEngLabel() . ': Admin Toolkit</span>'
            . '<div class="mT5" style="color: #333; font-size: 16px;">'
            . '<b>Status: ' . $status . '</b></div>';
        $this->v["alertIco"] = '<span class="mL5 slRedDark"><b>'
            . '(Next Step)</b></span>';
        $this->v["updateTitle"] = ' <b>Assign Complaint Status</b>';
        if ($this->v["firstRevDone"]
            || ($GLOBALS["SL"]->REQ->has('open') 
                && $GLOBALS["SL"]->REQ->open == 'status')
            || in_array($status, ['New', 'Unverified'])) {
            $this->v["updateTitle"] .= $this->v["alertIco"];
        }
        $this->v["hasOfficers"] = (isset($this->sessData->dataSets["officers"])
            && sizeof($this->sessData->dataSets["officers"]) > 0);
        $this->v["ico"] = 'caret';
        return view(
                'vendor.openpolice.nodes.1712-report-inc-staff-tools', 
                $this->v
            )->render() 
            . $this->printComplaintAdminChkPdfs();
    }

    /**
     * Check for sensitive and public PDFs while loading admin tools.
     *
     * @return string
     */
    protected function printComplaintAdminChkPdfs()
    {
        $ret = '';
        $prevView = $GLOBALS["SL"]->pageView;
        $GLOBALS["SL"]->pageView = 'full-pdf';
        if (!$this->chkCachePdfByID(true)) {
            $ret = '/complaint/read-' . $this->corePublicID 
                . '/full-pdf?refresh=1&pdf=1';
        } else {
            $prevPerm = $GLOBALS["SL"]->dataPerms;
            $GLOBALS["SL"]->dataPerms = 'public';
            $GLOBALS["SL"]->pageView = 'pdf';
            if (!$this->chkCachePdfByID(true)) {
                $ret = '/complaint/read-' . $this->corePublicID 
                    . '/pdf?refresh=1&pdf=1&publicView=1';
            }
            $GLOBALS["SL"]->dataPerms = $prevPerm;
        }
        $GLOBALS["SL"]->pageView = $prevView;
        if ($ret != '') {
            return '<div class="hid1px"><iframe src="' . $ret
                . '" frameborder=0 width=1 height=1 ></iframe></div>';
        }
        return $ret;
    }
    
    /**
     * Check for pre-loaded email ID.
     *
     * @return int
     */
    protected function chkForEmailID()
    {
        $this->v["emailID"] = -3;
        if ($GLOBALS["SL"]->REQ->has('email')) {
            $this->v["emailID"] = intVal($GLOBALS["SL"]->REQ->email);
        }
        if (!isset($this->v["deptID"])) {
            $this->v["deptID"] = 0;
        }
        return $this->v["emailID"];
    }
    
    /**
     * Pre-loaded email details and settings.
     *
     * @return boolean
     */
    protected function loadCurrEmail()
    {
        $this->v["currEmail"] = [];
        if (isset($this->sessData->dataSets["links_complaint_dept"])) {
            $depts = $this->sessData->dataSets["links_complaint_dept"];
            if (sizeof($depts) > 0) {
                foreach ($depts as $i => $deptLnk) {
                    if (!isset($deptLnk->lnk_com_dept_dept_id) 
                        || intVal($deptLnk->lnk_com_dept_dept_id) <= 0) {
                        $deptLnk->delete();
                    }
                    if (!isset($this->v["deptID"])
                        || $deptLnk->lnk_com_dept_dept_id == $this->v["deptID"]) {
                        $this->loadDeptStuff($deptLnk->lnk_com_dept_dept_id, $this->coreID);
                        $this->v["currEmail"][] = $this->processEmail(
                            $this->v["emailID"], 
                            $deptLnk->lnk_com_dept_dept_id
                        );
                    }
                }
            }
        }
        return true;
    }
    
    /**
     * Prepare everything needed for staff to select and send emails
     * to complainants from the top of their complaint report.
     *
     * @return boolean
     */
    protected function prepAdminComplaintEmailing()
    {
        $w = '';
        $this->chkForEmailID();
        $this->autoloadAdminComplaintEmail();
        $currDept = $this->prepAdminComplaintEmailGetDept();
        $isOverCompatible = false;
        if (sizeof($currDept) > 0) {
            if (isset($currDept["deptRow"])
                && isset($currDept["deptRow"]->dept_op_compliant)
                && intVal($currDept["deptRow"]->dept_op_compliant) == 1) {
                $isOverCompatible = true;
            }
            $w = $currDept["whichOver"];
        }
        $this->prepAdminComplaintEmailOpts($w, $currDept, $isOverCompatible);
        $this->loadDeptStuff();
        $this->chkAdminComplaint();
        $this->loadCurrEmail();
        $hasEmailLoaded = ($this->v["emailID"] > 0 && sizeof($this->v["currEmail"]) > 0);
        if ($hasEmailLoaded) {
            $this->v["needsWsyiwyg"] = true;
            foreach ($this->v["currEmail"] as $j => $email) {
                $GLOBALS["SL"]->pageAJAX .= ' $("#emailBodyCust' . $j 
                    . 'ID").summernote({ height: 350 }); ';
            }
        }
        return $hasEmailLoaded;
    }
    
    /**
     * Check for currently selected department, and
     *
     * @return array
     */
    protected function prepAdminComplaintEmailGetDept()
    {
        $currDept = [];
        if ($GLOBALS["SL"]->REQ->has('d')) {
            $this->v["deptID"] = intVal($GLOBALS["SL"]->REQ->get('d'));
        }
        if (!isset($this->v["comDepts"])) {
            $this->prepEmailComplaintData();
        }
        if (sizeof($this->v["comDepts"]) > 0) {
            if ($this->v["deptID"] <= 0) {
                $this->v["deptID"] = $this->v["comDepts"][0]["id"];
            }
            if ($this->v["deptID"] > 0) {
                foreach ($this->v["comDepts"] as $dept) {
                    if ($dept["id"] == $this->v["deptID"]) {
                        $currDept = $dept;
                    }
                }
            }
        }
        return $currDept;
    }
    
    /**
     * Load sending options for this email template, primarily contact lists.
     *
     * @return boolean
     */
    protected function prepAdminComplaintEmailOpts($w, $currDept, $isOverCompatible)
    {
        $this->v["emailsTo"] = [
            "to Complainant" => [],
            "to Oversight"   => []
        ];
        $userID = $this->sessData->dataSets["complaints"][0]->com_user_id;
        $complainantUser = User::find($userID);
        if ($complainantUser && isset($complainantUser->email)) {
            $name = $complainantUser->name;
            if (isset($this->sessData->dataSets["person_contact"])) {
                $pers = $this->sessData->dataSets["person_contact"];
                if (sizeof($pers) > 0 && isset($pers[0]->prsn_name_first)) {
                    $name = $pers[0]->prsn_name_first . ' ' 
                        . $pers[0]->prsn_name_last;
                }
            }
            $this->v["emailsTo"]["to Complainant"][] = [
                $complainantUser->email,
                $name,
                true
            ];
        }
        if ($isOverCompatible) {
            $this->v["emailsTo"]["to Oversight"][] = [
                $currDept[$w]->over_email,
                $currDept[$w]->over_agnc_name,
                true
            ];
        }
        $this->v["emailsTo"]["to Complainant"][] = [
            $this->v["user"]->email,
            '',
            false
        ];
        $this->v["emailsTo"]["to Oversight"][] = [
            $this->v["user"]->email,
            '',
            false
        ];
        return true;
    }

    /**
     * If an email template has not already been selected, 
     * check for suggested emails for staff to send next.
     *
     * @return int
     */
    protected function autoloadAdminComplaintEmail()
    {
        // Figure out default instructions
        //if ($this->v["deptID"] <= 0
        //    && !$GLOBALS["SL"]->REQ->has('d')
        //    && !$GLOBALS["SL"]->REQ->has('email')) {
        if ($this->v["emailID"] <= 0) {
            $com = $this->sessData->dataSets["complaints"][0];
            $defSet = 'Complaint Status';
            switch ($com->com_status) {
                case $GLOBALS["SL"]->def->getID($defSet, 'Incomplete'):
                    $this->v["emailID"] = 36; // Incomplete Complaint Check-In
                    break;
                case $GLOBALS["SL"]->def->getID($defSet, 'Wants Attorney'):
                    $this->autoloadEmailWantsAttorney($com);
                    break;
                case $GLOBALS["SL"]->def->getID($defSet, 'Pending Attorney'):
                    $this->autoloadEmailNeedsAttorney($com);
                    break;
                case $GLOBALS["SL"]->def->getID($defSet, 'OK to Submit to Oversight'):
                    $this->autoloadEmailOkToSubmit($com);
                    break;
                case $GLOBALS["SL"]->def->getID($defSet, 'Submitted to Oversight'):
                case $GLOBALS["SL"]->def->getID($defSet, 'Received by Oversight'):
                    $this->autoloadEmailSubmitted($com);
                    break;
            }
        }

        // Check that auto-suggested email has not already been sent
        if ($this->v["emailID"] > 0) {
            $found = false;
            // Simple version
            if ($GLOBALS["SL"]->x["deptsCnt"] == 1) {
                foreach ($this->v["history"] as $hist) {
                    if (isset($hist["emaID"]) 
                        && intVal($hist["emaID"]) == $this->v["emailID"]) {
                        $found = true;
                    }
                }
            }
            if ($found) {
                if ($this->v["emailID"] == 22) {
                    $this->v["emailID"] = 42; // Lawyer, Charges
                } elseif ($this->v["emailID"] == 32) {
                    $this->v["emailID"] = 35; // Lawyer, No Charges
                } elseif ($this->v["emailID"] == 9) { 
                    $this->v["emailID"] = 33; // How To File, Full Transparent
                } elseif ($this->v["emailID"] == 40) {
                    $this->v["emailID"] = 34; // How To File, Not Transparent
                } elseif ($this->v["emailID"] == 7) { // We Filed It
                    $this->v["emailID"] = 16; // What's the status?

                } else {
                    $this->v["emailID"] = 0;
                }
            }
        }
//echo '<pre>'; print_r($this->v["history"]); echo '</pre>'; exit;

        return $this->v["emailID"];
    }
    
    /**
     * Check for suggested emails for staff to send next 
     * if the complainant wants an attorney.
     *
     * @return int
     */
    protected function autoloadEmailWantsAttorney($com)
    {
        if (isset($com->com_publish_user_name)
            && in_array(intVal($com->com_publish_user_name), [0, 1])) {
            list($submitted, $compliant) = $this->chkDeptSubmissionStatus();
            if (sizeof($submitted) == 0) {
                if (sizeof($compliant) > 0) {
//echo 'autoloadEmailWantsAttorney(submitted:<pre>'; print_r($submitted); echo '</pre>compliant:<pre>'; print_r($compliant); echo '</pre>'; exit;
                    $this->v["emailID"] = 12; // Email to OP-Friendly IA
                } else {
                    if ($this->canPrintFullReportByRecSettings($com)) {
                        $this->v["emailID"] = 46; // Wants Lawyer, Transparent
                    } else {
                        $this->v["emailID"] = 44; // Wants Lawyer, Not Transparent
                    }
                }
            } elseif (sizeof($compliant) > 0) {
                $this->v["emailID"] = 45; // Emailed to OP-Friendly IA
            }
        } else {
            $this->v["emailID"] = 32; // Wants Lawyer, No Publishing Settings
        }
        return $this->v["emailID"];
    }
    
    /**
     * Check for suggested emails for staff to send next 
     * if the complainant needs an attorney.
     *
     * @return int
     */
    protected function autoloadEmailNeedsAttorney($com)
    {
        $this->v["emailID"] = 32; // Wants Lawyer, No Charges
        if (isset($com->com_anyone_charged) 
            && in_array(trim($com->com_anyone_charged), ['Y', '?'])
            && isset($com->com_all_charges_resolved)
            && trim($com->com_all_charges_resolved) != 'Y') {
            $this->v["emailID"] = 22; // Needs Lawyer, Charges
        }
        return $this->v["emailID"];
    }
    
    /**
     * Check for suggested emails for staff to send next 
     * if the complaint is OK to submit to investigative agency.
     *
     * @return int
     */
    protected function autoloadEmailOkToSubmit($com)
    {
        list($submitted, $compliant) = $this->chkDeptSubmissionStatus();

//echo 'AAA — emailID: ' . $this->v["emailID"] . ', deptID: ' . $this->v["deptID"] . '<br />submitted: <pre>'; print_r($submitted); echo '</pre>compliant: <pre>'; print_r($compliant); echo '</pre>';

        if ($GLOBALS["SL"]->x["deptsCnt"] == 1) {

            // First check if department is OP-Friendly
            foreach ($GLOBALS["SL"]->x["depts"] as $deptID => $dept) {
                if ($deptID != $this->v["notSureDeptID"]
                    && $this->v["deptID"] <= 0 
                    && $this->v["emailID"] <= 0) {
                    if (in_array($deptID, $compliant)) {
                        if (!in_array($deptID, $submitted)) {
                            // Send to investigative agency
                            $this->v["emailID"] = 12; 
                            $this->v["deptID"]  = $deptID;
                        } elseif ($cnt == 1) {
                            // Sent to investigative agency
                            $this->v["emailID"] = 7;
                        }
                    }
                }
            }

            foreach ($GLOBALS["SL"]->x["depts"] as $deptID => $dept) {
                if ($deptID != $this->v["notSureDeptID"]
                    && $this->v["deptID"] <= 0 
                    && $this->v["emailID"] <= 0) {
                    if (!in_array($deptID, $compliant) && !in_array($deptID, $submitted)) {


                        // How to manually file your complaint with investigative agency

                        if ($this->canPrintFullReportByRecSettings($com)) {
                            // Fully transparent, with incentive to file

                            $this->v["emailID"] = 9;
                            $this->v["deptID"]  = $deptID;
                        } else {
                            // Not fully transparent
                            $this->v["emailID"] = 40;
                            $this->v["deptID"]  = $deptID;
                        }
                    }
                }
            }


        } else { // multiple departments

            // First check if any departments are OP-Friendly
            $foundFriendly = $foundFriendlySubmitted = false;
            foreach ($GLOBALS["SL"]->x["depts"] as $deptID => $dept) {
                if ($deptID != $this->v["notSureDeptID"]
                    && $this->v["deptID"] <= 0 
                    && $this->v["emailID"] <= 0) {
                    if (in_array($deptID, $compliant)) {
                        if (!in_array($deptID, $submitted)) {
                            // Send to investigative agency
                            $this->v["emailID"] = 12; 
                            $this->v["deptID"]  = $deptID;
                        } else {
                            // Sent to investigative agency
                            $this->v["emailID"] = 7;
                        }
                    }
                }
            }

            foreach ($GLOBALS["SL"]->x["depts"] as $deptID => $dept) {
                if ($deptID != $this->v["notSureDeptID"]
                    && $this->v["deptID"] <= 0 
                    && $this->v["emailID"] <= 0) {
                    if (!in_array($deptID, $compliant) && !in_array($deptID, $submitted)) {


                        // How to manually file your complaint with investigative agency

                        if ($this->canPrintFullReportByRecSettings($com)) {
                            // Fully transparent, with incentive to file

                            $this->v["emailID"] = 9;
                            $this->v["deptID"]  = $deptID;
                        } else {
                            // Not fully transparent
                            $this->v["emailID"] = 40;
                            $this->v["deptID"]  = $deptID;
                        }
                    }
                }
            }

        } // end multiple departments

//echo 'BBB — emailID: ' . $this->v["emailID"] . ', deptID: ' . $this->v["deptID"] . '<br />submitted: <pre>'; print_r($submitted); echo '</pre>compliant: <pre>'; print_r($compliant); echo '</pre>'; exit;

        if ($this->v["emailID"] <= 0 && $GLOBALS["SL"]->x["deptsCnt"] > 1) {
            if ($GLOBALS["SL"]->x["deptsCnt"] == sizeof($submitted) 
                && $GLOBALS["SL"]->x["deptsCnt"] == sizeof($compliant)) {
                $this->v["emailID"] = 7;
            }
        }

        return $this->v["emailID"];
    }
    
    /**
     * Check for suggested emails for staff to send next 
     * if the complaint has been submitted for investigation.
     *
     * @return int
     */
    protected function autoloadEmailSubmitted($com)
    {
        $chk = SLEmailed::whereIn('emailed_tree', [1, 42])
            ->where('emailed_rec_id', $this->coreID)
            ->where('emailed_email_id', 7)
            ->first();
        if (!$chk || !isset($chk->created_at)) {
            $this->v["emailID"] = 7; // Sent to investigative agency
        }
        return $this->v["emailID"];
    }
    
    /**
     * Save and process staff use of admin tools for managing one complaint.
     *
     * @return boolean
     */
    protected function saveComplaintAdmin()
    {
        $hasSave = $GLOBALS["SL"]->REQ->has('save');
        $hasFirstReview = ($GLOBALS["SL"]->REQ->has('firstReview') 
            && $GLOBALS["SL"]->REQ->has('n1712fld') 
            && intVal($GLOBALS["SL"]->REQ->n1712fld) > 0);
        $hasReportUpload = ($GLOBALS["SL"]->REQ->has('reportUp') 
            && $GLOBALS["SL"]->REQ->has('reportUpType')
            && trim($GLOBALS["SL"]->REQ->reportUpType) != '');
        $hasFixDepts = $GLOBALS["SL"]->REQ->has('fixDepts');
        $hasEmailSend = ($GLOBALS["SL"]->REQ->has('emailID') 
            && intVal($GLOBALS["SL"]->REQ->emailID) > 0
            && $GLOBALS["SL"]->REQ->has('send') );
        if ($hasFirstReview
            || $hasSave
            || $hasReportUpload
            || $hasFixDepts
            || $hasEmailSend) {
            $this->loadReportUploadTypes();
            $this->loadOversightDateLookups();
            $this->prepEmailComplaintData();
            if ($hasFirstReview) {
                $this->saveComplaintAdminFirstReview();
            } elseif ($hasSave) {
                $this->saveComplaintAdminStatus();
            } elseif ($hasReportUpload) {
                $this->saveComplaintAdminUpload();
            } elseif ($hasFixDepts) {
                $this->processComplaintFixDepts();
            } elseif ($hasEmailSend) {
                return $this->saveComplaintAdminSendEmail();
            }
            $this->clearComplaintCaches(true);
        } elseif ($GLOBALS["SL"]->REQ->has('refresh')
            && intVal($GLOBALS["SL"]->REQ->has('refresh')) == 2) {
            $this->clearComplaintCaches();
        }
        return true;
    }
    
    /**
     * Save and process the first pass-fail review performed by staff.
     *
     * @return boolean
     */
    protected function saveComplaintAdminFirstReview()
    {
        if ($GLOBALS["SL"]->REQ->has('n1712fld')) {
            $type = intVal($GLOBALS["SL"]->REQ->n1712fld);
            $newTypeVal = $GLOBALS["SL"]->def->getVal('Complaint Type', $type);
            $this->logComplaintReview('First', '', $newTypeVal);
            $this->sessData->dataSets["complaints"][0]->com_type = $type;
            $this->sessData->dataSets["complaints"][0]->save();
            $this->v["firstRevDone"] = true;
            $this->v["firstReview"]  = false;
        }
        return true;
    }
    
    /**
     * Save and process a change in complaint status assigned by staff.
     *
     * @return boolean
     */
    protected function saveComplaintAdminStatus()
    {
        $status = 0;
        $evalNotes = '';
        if ($GLOBALS["SL"]->REQ->has('revNote')) {
            $evalNotes = trim($GLOBALS["SL"]->REQ->revNote) . ' <br />';
        }
        $evalNotes .= $this->processComplaintOverDates();
        if ($GLOBALS["SL"]->REQ->has('revStatus')) {
            $defSet = 'Complaint Status';
            $status = $GLOBALS["SL"]->REQ->revStatus;
            $holds = [ 'Hold: Go Gold', 'Hold: Not Sure' ];
            if (in_array($status, $holds)) {
                $this->sessData->dataSets["complaints"][0]->com_status 
                    = $GLOBALS["SL"]->def->getID($defSet, 'Hold');
            } elseif ($status == 'Needs More Work') {
                $this->sessData->dataSets["complaints"][0]->com_status 
                    = $GLOBALS["SL"]->def->getID($defSet, 'Needs More Work');
            } elseif ($status == 'Pending Attorney: Civil Rights Needed/Wanted') {
                $this->sessData->dataSets["complaints"][0]->com_status 
                    = $GLOBALS["SL"]->def->getID($defSet, 'Wants Attorney');
            } elseif ($status == 'Pending Attorney: Defense Needed') {
                $this->sessData->dataSets["complaints"][0]->com_status 
                    = $GLOBALS["SL"]->def->getID($defSet, 'Pending Attorney');
            } elseif (in_array($status, [ 'Has Attorney' ])) {
                $this->sessData->dataSets["complaints"][0]->com_status 
                    = $GLOBALS["SL"]->def->getID($defSet, 'Has Attorney');
            } else {
                $this->sessData->dataSets["complaints"][0]->com_status 
                    = $GLOBALS["SL"]->def->getID($defSet, $status);
            }
        }
        if ($GLOBALS["SL"]->REQ->has('revComplaintType')) {
            $incDef = $GLOBALS["SL"]->def->getID('Complaint Status', 'Incomplete');
            if ($GLOBALS["SL"]->REQ->revComplaintType == $incDef) {
                $this->sessData->dataSets["complaints"][0]->com_status = $incDef;
                $this->sessData->dataSets["complaints"][0]->com_type 
                    = $GLOBALS["SL"]->def->getID('Complaint Type', 'Unverified');
                $status = 'Incomplete';
            } else { 
                $newTypeVal = $GLOBALS["SL"]->def->getVal(
                    'Complaint Type', 
                    $GLOBALS["SL"]->REQ->revComplaintType
                );
                if ($newTypeVal != 'Police Complaint') {
                    $status = $newTypeVal;
                }
                $this->sessData->dataSets["complaints"][0]->com_type 
                    = intVal($GLOBALS["SL"]->REQ->revComplaintType);
            }
        }
        $this->logComplaintReview('Update', $evalNotes, $status);
        $this->sessData->dataSets["complaints"][0]->save();
        return true;
    }
    
    /**
     * Save and process a report upload done by staff.
     *
     * @return boolean
     */
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
            if (strtolower($extension) == "pdf" && strtolower($mimetype) == "application/pdf") {
                if (!$GLOBALS["SL"]->REQ->file('reportToUpload')->isValid()) {
                    $ret .= '<div class="txtDanger">Upload Error.' 
                        . /* $_FILES["up" . $nID . "File"]["error"] . */ '</div>';
                } else {
                    $upFold = $this->v["reportUploadFolder"];
                    $this->mkNewFolder($upFold);
                    $filename = $this->sessData->dataSets["complaints"][0]->com_id 
                        . '-' . $GLOBALS["SL"]->REQ->reportUpType . '.pdf';
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
    
    /**
     * Send an email send by staff, and log with this conduct report.
     *
     * @return boolean
     */
    protected function sendComplaintAdminEmail()
    {
        $emailSent = false;
        $emaInd = 0;
        while ($GLOBALS["SL"]->REQ->has('emailID') 
            && $GLOBALS["SL"]->REQ->has('emailTo' . $emaInd . '') 
            && trim($GLOBALS["SL"]->REQ->get('emailTo' . $emaInd . '')) != '') {
            $userToID = -3;
            $email = trim($GLOBALS["SL"]->REQ->get('emailTo' . $emaInd . ''));
            $chk = User::where('email', $email)
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
                $bcc,
                $this->sendComplaintAdminEmailAttach($emaInd)
            );
            $this->sendComplaintAdminEmailExtras($emaInd);
            $emailSent = true;
            $emaInd++;
        }
        return $emailSent;
    }
    
    /**
     * Get path and filename for attachment needed on this email.
     *
     * @return string
     */
    protected function sendComplaintAdminEmailAttach($emaInd)
    {
        $attach = [];
        if ($GLOBALS["SL"]->REQ->has('attachType' . $emaInd . '')) {
            $prevPerm = $GLOBALS["SL"]->dataPerms;
            $prevView = $GLOBALS["SL"]->pageView;
            $attach = trim($GLOBALS["SL"]->REQ->get('attachType' . $emaInd . ''));
            if ($attach == 'sensitive') {
                $GLOBALS["SL"]->dataPerms = 'sensitive';
                $GLOBALS["SL"]->pageView  = 'full-pdf';
            } elseif ($attach == 'public') {
                $GLOBALS["SL"]->dataPerms = 'public';
                $GLOBALS["SL"]->pageView  = 'pdf';
            }
            $file = $this->loadPdfByID();
            $fileWithAttach = str_replace('.pdf', '-with-attach.pdf', $file);
            if (file_exists($fileWithAttach)) {
                $file = $fileWithAttach;
            }
            $GLOBALS["SL"]->x["pdfFilename"] = $this->getComplaintFilenamePDF();
            $output = $this->v["pdf-gen"]->setOutput($file, $GLOBALS["SL"]->x["pdfFilename"]);
            $attach = [ $output ];
            $GLOBALS["SL"]->dataPerms = $prevPerm;
            $GLOBALS["SL"]->pageView  = $prevView;
        }
        return $attach;
    }
    
    protected function loadPdfFilename()
    {
        if ($GLOBALS["SL"]->coreTbl == 'complaints') {
            return $this->getComplaintFilenamePDF();
        }
        return '';
    }
    
    /**
     * Send an email send by staff, and log with this conduct report.
     *
     * @return boolean
     */
    protected function sendComplaintAdminEmailExtras($emaInd)
    {
        $deptID = intVal($GLOBALS["SL"]->REQ->get('d'));
        if (intVal($GLOBALS["SL"]->REQ->get('emailID')) == 12
            && isset($GLOBALS["SL"]->x["depts"][$deptID])) {
            $this->logOverUpDate($this->coreID, $deptID, 'submitted');
        }
//echo 'emailID: ' . $GLOBALS["SL"]->REQ->get('emailID') . ', dept: ' . $deptID . '<pre>'; print_r($GLOBALS["SL"]->REQ->all()); echo '</pre>'; exit;

        return true;
    }
    
    /**
     * Initialize the loading of the widget for the complaint toolbox.
     *
     * @return boolean
     */
    protected function chkAdminComplaint()
    {
        $subDef = 'OK to Submit to Oversight';
        $subDef = $GLOBALS["SL"]->def->getID('Complaint Status', $subDef);
        list($submitted, $compliant) = $this->chkDeptSubmissionStatus();
//echo 'chkAdminComplaint?? ' . sizeof($GLOBALS["SL"]->x["depts"]) . ', submitted: ' . sizeof($submitted) . ' - com_status: ' . $this->sessData->dataSets["complaints"][0]->com_status . ' ?= ' . $subDef . '<br />'; exit;
        if (isset($GLOBALS["SL"]->x["depts"])
            && sizeof($GLOBALS["SL"]->x["depts"]) == sizeof($submitted)
            && $this->sessData->dataSets["complaints"][0]->com_status == $subDef) {
            $subDef = 'Submitted to Oversight';
            $subDef = $GLOBALS["SL"]->def->getID('Complaint Status', $subDef);
            $this->sessData->dataSets["complaints"][0]->com_status = $subDef;
            $this->sessData->dataSets["complaints"][0]->save();
            $newRev = new OPzComplaintReviews;
            $newRev->com_rev_complaint = $this->coreID;
            $newRev->com_rev_user      = $this->v["user"]->id;
            $newRev->com_rev_date      = date("Y-m-d H:i:s");
            $newRev->com_rev_type      = 'Update';
            $newRev->com_rev_status    = 'Submitted to Oversight';
            $newRev->save();
        }
        return true;
    }
    
    /**
     * Initialize the loading of the widget for the complaint toolbox.
     *
     * @return boolean
     */
    protected function initComplaintToolbox()
    {
        $GLOBALS["SL"]->loadStates();
        $this->loadReportUploadTypes();
        if (isset($this->sessData->dataSets["complaints"])
            && sizeof($this->sessData->dataSets["complaints"]) > 0) {
            $this->v["complaintRec"]
                = $this->sessData->dataSets["complaints"][0];
            $this->v["incidentState"] 
                = $this->sessData->dataSets["incidents"][0]->inc_address_state;
            $this->v["comStatus"] = $GLOBALS['SL']->def->getVal(
                'Complaint Status', 
                $this->v["complaintRec"]->com_status
            );
        }
        $this->v["firstRevDone"] = false;
        $this->v["firstReview"]  = true;
        $this->v["lastReview"]   = true;
        $this->v["history"]      = [];
        return true;
    }
    
    /**
     * Prepare full list of email templates.
     *
     * @return boolean
     */
    protected function loadComplaintEmailList()
    {
        $this->v["emailList"] = SLEmails::orderBy('email_name', 'asc')
            ->orderBy('email_type', 'asc')
            ->get();
        return true;
    }
    
    /**
     * Prepare full history of this conduct report.
     *
     * @return boolean
     */
    protected function loadComplaintAdminHistory()
    {
        $this->loadComplaintEmailList();
        $allUserNames = [];
        $reviews = OPzComplaintReviews::where('com_rev_complaint', 
                '=', $this->coreID)
            ->where('com_rev_type', 'NOT LIKE', 'Draft')
            ->orderBy('com_rev_date', 'desc')
            ->get();
        if ($reviews->isNotEmpty()) {
            foreach ($reviews as $i => $r) {
                if ($i == 0) {
                    $this->v["lastReview"] = $r;
                }
                $this->v["firstReview"] = false;
                if (!isset($allUserNames[$r->com_rev_user])) {
                    $allUserNames[$r->com_rev_user] = $this->printUserLnk(
                        $r->com_rev_user
                    );
                }
                $desc = '<span class="slBlueDark">';
                if (isset($r->com_rev_next_action) 
                    && trim($r->com_rev_next_action) == 'Complaint Received'
                    && $r->com_rev_status == 'Submitted to Oversight') {
                    $desc .= $r->com_rev_next_action;
                } else {
                    $desc .= $r->com_rev_status;
                }
                $desc = str_replace('Oversight', 'Investigative Agency', $desc);
                $desc .= '</span>';
                $note = ((isset($r->com_rev_note)) ? trim($r->com_rev_note) : '');
                $note = trim(str_replace("\n\n", "\n", $note));
                $note = str_replace("\n", "<br />", $note);
                $desc .= ((strlen(trim(strip_tags($note))) > 0) 
                    ? ' <span class="fPerc80 slGrey">(Notes: ' 
                        . number_format(strlen(strip_tags($note))) . ' Chars)</span>'
                    : '');
                $this->v["history"][] = [
                    "type" => 'Status', 
                    "date" => strtotime($r->com_rev_date), 
                    "desc" => $desc, 
                    "who"  => $allUserNames[$r->com_rev_user],
                    "note" => $note
                ];
            }
        }
        $emails = SLEmailed::whereIn('emailed_tree', [1, 42, 197])
            ->where('emailed_rec_id', $this->coreID) //corePublicID
            ->orderBy('created_at', 'asc')
            ->get();
        if ($emails->isNotEmpty()) {
            foreach ($emails as $i => $e) {
                if (!isset($allUserNames[$e->emailed_from_user])) {
                    $allUserNames[$e->emailed_from_user] 
                        = $this->printUserLnk($e->emailed_from_user);
                }
                $desc = '"' . $e->emailed_subject . '"'
                    . ((strlen(trim(strip_tags($e->emailed_body))) > 0) 
                        ? ' <span class="fPerc80 slGrey">(Email Body: ' 
                            . number_format(strlen(strip_tags($e->emailed_body))) 
                            . ' characters)</span>'
                        : '')
                    . '<br /><span class="blk">sent to ' . $e->emailed_to 
                    . ((isset($e->emailed_attach) && trim($e->emailed_attach) != '')
                        ? '<i class="fa fa-paperclip mL15 mR3" aria-hidden="true"></i>'
                            . $e->emailed_attach 
                        : '') . '</span>';
                $this->v["history"][] = [
                    "type"  => 'Email', 
                    "emaID" => $e->emailed_email_id, 
                    "date"  => strtotime($e->created_at), 
                    "desc"  => $desc, 
                    "note"  => $e->emailed_body,
                    "who"   => $allUserNames[$e->emailed_from_user]
                ];
            }
        }
        $this->v["history"] = $GLOBALS["SL"]->sortArrByKey($this->v["history"], 'date', 'desc');
        return true;
    }

    /**
     * Process form submission to correct a complaint's associated departments.
     *
     * @return booleon
     */
    protected function processComplaintFixDepts()
    {
        if ($this->v["user"]->hasRole('administrator|databaser|staff')) {
            $evalNotes = '';
            $keepDepts = $delDepts = [];
            if ($GLOBALS["SL"]->REQ->has('keepDepts') 
                && sizeof($GLOBALS["SL"]->REQ->keepDepts) > 0) {
                $keepDepts = $GLOBALS["SL"]->REQ->keepDepts;
            }
            foreach ($GLOBALS["SL"]->x["depts"] as $deptID => $d) {
                if (!in_array($deptID, $keepDepts)) {
                    $delDepts[] = $deptID;
                    $evalNotes .= $this->fixDeptsRemove($d["deptRow"]);
                }
            }
            if ($GLOBALS["SL"]->REQ->has('keepDeptNew')) {
                $newID = intVal($GLOBALS["SL"]->REQ->keepDeptNew);
                if ($newID > 0) {
                    $deptRow = OPDepartments::where('dept_id', $newID)
                        ->select('dept_id', 'dept_name')
                        ->first();
                    if ($deptRow && isset($deptRow->dept_name)) {
                        $evalNotes .= $this->fixDeptsAdd($deptRow);
                    }
                }
            }
            $status = $this->sessData->dataSets["complaints"][0]->com_status;
            $status = $GLOBALS["SL"]->def->getVal('Complaint Status', $status);
            $this->logComplaintReview('Staff', $evalNotes, $status);
            $this->fixDeptsClean();
            $redir = '/dash/complaint/read-' . $this->corePublicID . '?refresh=1';
            $this->redir($redir, true);
            exit;
        }
        return false;
    }
    
    /**
     * Admin sends an email to a complainant regarding one complaint.
     *
     * @return boolean
     */
    protected function saveComplaintAdminSendEmail()
    {
        if ($this->sendComplaintAdminEmail()) {
            $emaID = -1;
            if ($GLOBALS["SL"]->REQ->has('emailID')) {
                $emaID = intVal($GLOBALS["SL"]->REQ->emailID);
            }
            return view(
                'vendor.openpolice.nodes.1712-report-inc-staff-tools-email-redir', 
                [
                    "coreID" => $this->coreID,
                    "emaID"  => $emaID
                ]
            )->render();
        }
        return 'Sorry, something went wrong trying to send the email.';
    }

    /**
     * Remove this department's association with this conduct report.
     *
     * @param  App\Models\OPDepartments $deptRow
     * @return string
     */
    protected function fixDeptsAdd($deptRow)
    {
        if (!isset($deptRow->dept_id) || !isset($deptRow->dept_name)) {
            return '';
        }
        $newLnk = new OPLinksComplaintDept;
        $newLnk->lnk_com_dept_complaint_id = $this->coreID;
        $newLnk->lnk_com_dept_dept_id = $deptRow->dept_id;
        $newLnk->save();
        return ', <i>' . $deptRow->dept_name . '</i> Added';
    }

    /**
     * Remove this department's association with this conduct report.
     *
     * @param  App\Models\OPDepartments $deptRow
     * @return string
     */
    protected function fixDeptsRemove($deptRow)
    {
        if (!isset($deptRow->dept_id) || !isset($deptRow->dept_name)) {
            return '';
        }
        OPLinksComplaintDept::where('lnk_com_dept_complaint_id', $this->coreID)
            ->where('lnk_com_dept_dept_id', $deptRow->dept_id)
            ->delete();
        OPLinksComplaintOversight::where('lnk_com_over_complaint_id', $this->coreID)
            ->where('lnk_com_over_dept_id', $deptRow->dept_id)
            ->delete();
        return ', <i>' . $deptRow->dept_name . '</i> Removed';
    }

    /**
     * Delete empty, useless or error-prone department and oversight records.
     *
     * @return string
     */
    protected function fixDeptsClean()
    {
        $iaDef = $GLOBALS["SL"]->def->getID(
            'Investigative Agency Types', 
            'Internal Affairs'
        );
        $deptIDs = [];
        $depts = OPLinksComplaintDept::where('lnk_com_dept_complaint_id', $this->coreID)
            ->get();
        if ($depts->count() > 0) {
            foreach ($depts as $d) {
                $deptID = $d->lnk_com_dept_dept_id;
                $deptIDs[] = $deptID;
                $overs = OPOversight::where('over_dept_id', $deptID)
                    ->where('over_type', $iaDef)
                    ->get();
                if ($overs->count() > 0) {
                    foreach ($overs as $over) {
                        $chk = OPLinksComplaintOversight::where(
                                'lnk_com_over_complaint_id', $this->coreID)
                            ->where('lnk_com_over_dept_id', $deptID)
                            ->where('lnk_com_over_over_id', $over->over_id)
                            ->orderBy('created_at', 'asc')
                            ->first();
                        if (!$chk || !isset($chk->LnkComOverID)) {
                            $newLnk = new OPLinksComplaintOversight;
                            $newLnk->lnk_com_over_complaint_id = $this->coreID;
                            $newLnk->lnk_com_over_dept_id = $deptID;
                            $newLnk->lnk_com_over_over_id = $over->over_id;
                            $newLnk->save();
                        }
                    }
                }
            }
        }
        OPLinksComplaintOversight::where('lnk_com_over_complaint_id', $this->coreID)
            ->whereNotIn('lnk_com_over_dept_id', $deptIDs)
            ->delete();
        OPLinksComplaintOversight::whereNull('lnk_com_over_dept_id')
            ->orWhere('lnk_com_over_dept_id', '<=', 0)
            ->delete();
        OPLinksComplaintDept::whereNull('lnk_com_dept_dept_id')
            ->orWhere('lnk_com_dept_dept_id', '<=', 0)
            ->delete();
        return '';
    }

}
