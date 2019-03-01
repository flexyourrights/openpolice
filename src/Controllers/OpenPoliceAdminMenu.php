<?php
/**
  * OpenPoliceAdminMenu is responsible for building the menu inside the dashboard area for all user types.
  *
  * Open Police Complaints
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <wikiworldorder@protonmail.com>
  * @since 0.0
  */
namespace OpenPolice\Controllers;

use SurvLoop\Controllers\AdminMenu;

class OpenPoliceAdminMenu extends AdminMenu
{
    public function loadAdmMenu($currUser = null, $currPage = '')
    {
        $this->currUser = $currUser;
        $this->currPage = $currPage;
        $treeMenu = [];
        if (isset($this->currUser)) {
            $published = $flagged = 0;
            if ($this->currUser->hasRole('administrator|staff|databaser|brancher')) {
                $deptSubMenu = [
                    $this->admMenuLnk('/dash/verify-next-department', 'Verify A Department'),
                    $this->admMenuLnk('/dash/volunteer-edits-history', 'Volunteer History'),
                    $this->admMenuLnk('/department-accessibility', 'Accessibility Scores Report')
                    ];
                if ($GLOBALS["SL"]->REQ->has('cid')) {
                    $deptSubMenu = $this->subLinksVerifDept();
                }
                $treeMenu[] = $this->admMenuLnk('javascript:;', 'Complaints', '<i class="fa fa-star"></i>', 1, [
                    $this->admMenuLnk('/dash/all-complete-complaints', 'Manage Complaints', '', 1, 
                        $this->subLinksComplaintTypes()), 
                    $this->admMenuLnk('/dash/volunteer', 'Manage Departments', '', 1, $deptSubMenu),
                    $this->admMenuLnk('/dash/manage-partners', 'Manage Partners', '', 1, [
                        $this->admMenuLnk('/dash/manage-attorneys', 'Attorneys'),
                        $this->admMenuLnk('/dash/manage-organizations', 'Organizations'),
                        $this->admMenuLnk('/dash/beta-test-signups', 'Beta Signups')
                        ]),
                    $this->admMenuLnk('/dash/team-resources', 'Team Resources')
                    ]);
            } elseif ($this->currUser->hasRole('partner')) {
                $treeMenu[] = $this->admMenuLnk('javascript:;', 'Complaints', '<i class="fa fa-star"></i>', 1, [
                    $this->admMenuLnk('/dash/all-complete-complaints', 'Manage Complaints', '', 1, 
                        $this->subLinksComplaintTypes()), 
                    $this->admMenuLnk('/dash/volunteer', 'Manage Departments'),
                    $this->admMenuLnk('/dash/team-resources', 'Team Resources')
                    ]);
                return $treeMenu;
            } elseif ($this->currUser->hasRole('volunteer')) {
                $stars = '';
                if (isset($GLOBALS["SL"]->x["yourUserInfo"]) 
                    && isset($GLOBALS["SL"]->x["yourUserInfo"]->UserInfoStars)) {
                    $stars = '<nobr>';
                    for ($s = 0; $s < $GLOBALS["SL"]->x["yourUserInfo"]->UserInfoStars; $s++) {
                        if ($s > 0 && $s%25 == 0) {
                            $stars .= '</nobr><nobr>';
                        }
                        $stars .= '<img src="/openpolice/star1.png" border=0 height=15 class="mLn10" alt="Gold Star"> ';
                    }
                    $stars .= '</nobr>';
                }
                $treeMenu[] = $this->admMenuLnk('javascript:;', 'Department List', 
                    '<i class="fa fa-university" aria-hidden="true"></i>', 1, [
                    $this->admMenuLnk('/dash/volunteer', 'All Departments'),
                    $this->admMenuLnk('/dash/verify-next-department', 'Verify A Department', '', 1, 
                        $this->subLinksVerifDept()),
                    $this->admMenuLnk('/department-accessibility', 'Department Scores Report')
                    ]);
                $treeMenu[] = $this->admMenuLnk('javascript:;', 'Volunteers', '<i class="fa fa-users"></i>', 1, [
                    $this->admMenuLnk('/dash/volunteer-stars', 'All-Stars List'),
                    $this->admMenuLnk('/my-profile', 'My Profile')
                    ]);
                return $treeMenu;
            }
        }
        //$treeMenu = $this->addAdmMenuHome();
        //return $treeMenu;
        return $this->addAdmMenuBasics($treeMenu);
    }
    
    private function subLinksVerifDept()
    {
        return [
            $this->admMenuLnk('#deptContact', 'Contact Info'),
            $this->admMenuLnk('#deptWeb', 'Web Presence'),
            $this->admMenuLnk('#deptIA', 'Internal Affairs'),
            $this->admMenuLnk('#deptCiv', 'Civilian Oversight'),
            $this->admMenuLnk('#deptSave', 
                '<i class="fa fa-floppy-o mR5" aria-hidden="true"></i> Save Changes'),
            $this->admMenuLnk('#deptEdits', 'Dept Edit History'),
            $this->admMenuLnk('#deptChecklist', 'Checklist & Scripts')
            ];
    }
    
    private function subLinksComplaintTypes()
    {
        return [
            $this->admMenuLnk('/dash/all-complete-complaints', 'Police Complaints'),
            $this->admMenuLnk('/dash/all-complete-complaints?type=notsure', 'Not Sure'),
            $this->admMenuLnk('/dash/all-complete-complaints?type=notpolice', 'Not About Police'),
            $this->admMenuLnk('/dash/all-complete-complaints?type=spam', 'Abuse, Spam, Tests'),
            $this->admMenuLnk('/dash/all-incomplete-complaints', 'Incomplete Complaints')
            ];
    }
    
}