<?php
/**
  * OpenPoliceAdminMenu is responsible for building the menu inside the 
  * dashboard area for all user types.
  *
  * OpenPolice.org
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <wikiworldorder@protonmail.com>
  * @since v0.0.1
  */
namespace OpenPolice\Controllers;

use SurvLoop\Controllers\Admin\AdminMenu;

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

                return $this->loadAdmMenuStaff($currUser);

            } elseif ($this->currUser->hasRole('partner')) {

                return $this->loadAdmMenuPartner();

            } elseif ($this->currUser->hasRole('volunteer')) {

                return $this->loadAdmMenuVolunteer();

            }
        }
        return $treeMenu;
    }
    
    private function subLinksVerifDept()
    {
        return [
            $this->admMenuLnk('#deptContact', 'Contact Info'),
            $this->admMenuLnk('#deptWeb',     'Web Presence'),
            $this->admMenuLnk('#deptIA',      'Internal Affairs'),
            $this->admMenuLnk('#deptCiv',     'Civilian Oversight')
        ];
    }
    
    private function subLinksComplaintTypes()
    {
        return [];
        /* return [
            $this->admMenuLnk('/dash/all-complete-complaints', 'Police Complaints'),
            $this->admMenuLnk('/dash/all-complete-complaints?type=notsure', 'Not Sure'),
            $this->admMenuLnk('/dash/all-complete-complaints?type=notpolice', 'Not About Police'),
            $this->admMenuLnk('/dash/all-complete-complaints?type=spam', 'Abuse, Spam, Tests'),
            $this->admMenuLnk('/dash/all-incomplete-complaints', 'Incomplete Complaints')
        ]; */
    }

    protected function loadAdmMenuStaff($currUser = null)
    {
        $treeMenu = [];
        if ($this->currUser->hasRole('staff')) {
            $treeMenu[] = $this->admMenuLnk(
                '/dash/staff', 
                'Dashboard', 
                '<i class="fa fa-home"></i>'
            );
        } else {
            $treeMenu[] = $this->addAdmMenuHome();
        }
        $deptSubMenu = [
            $this->admMenuLnk(
                '/dash/verify-next-department', 
                'Verify A Department'
            ),
            $this->admMenuLnk(
                '/dash/volunteer-edits-history', 
                'Volunteer History'
            ),
            $this->admMenuLnk(
                '/department-accessibility', 
                'Accessibility Scores Report'
            )
        ];
        if ($GLOBALS["SL"]->REQ->has('cid')) {
            $deptSubMenu = $this->subLinksVerifDept();
        }
        $treeMenu[] = $this->admMenuLnk(
            'javascript:;', 
            'Complaints', 
            '<i class="fa fa-frown-o" aria-hidden="true"></i>', 
            1, 
            [
                $this->admMenuLnk(
                    '/dash/all-complete-complaints', 
                    'Manage Complaints', 
                    '', 
                    1, 
                    $this->subLinksComplaintTypes()
                ), 
                $this->admMenuLnk(
                    '/dash/volunteer', 
                    'Manage Departments', 
                    '', 
                    1, 
                    $deptSubMenu
                ),
                $this->admMenuLnk(
                    '/dash/manage-partners', 
                    'Manage Partners', 
                    '', 
                    1, 
                    [
                        $this->admMenuLnk(
                            '/dash/manage-organizations', 
                            'Organizations'
                        ),
                        $this->admMenuLnk(
                            '/dash/manage-attorneys', 
                            'Attorneys'
                        ),
                        $this->admMenuLnk(
                            '/dash/beta-test-signups', 
                            'Beta Signups'
                        )
                    ]
                ),
                $this->admMenuLnk(
                    '/dash/team-resources', 
                    'Team Resources', 
                    '', 
                    1, 
                    [
                        $this->admMenuLnk(
                            '/dash/team-resources', 
                            'Team Resources'
                        ),
                        $this->admMenuLnk(
                            '/dash/development-team-update', 
                            'Software Development Updates'
                        )
                    ]
                )
            ]
        );
        return $this->addAdmMenuBasics($treeMenu);
    }

    protected function loadAdmMenuPartner()
    {
        $treeMenu = [];
        $treeMenu[] = $this->addAdmMenuCollapse();
        $treeMenu[] = $this->admMenuLnk(
            '/dash/partner', 
            'Dashboard', 
            '<i class="fa fa-home"></i>'
        );
        $treeMenu[] = $this->admMenuLnk(
            'javascript:;', 
            'Complaints', 
            '<i class="fa fa-frown-o" aria-hidden="true"></i>', 
            1, 
            [
                $this->admMenuLnk(
                    '/dash/all-complete-complaints', 
                    'Manage Complaints', 
                    '', 
                    1, 
                    $this->subLinksComplaintTypes()
                ), 
                $this->admMenuLnk(
                    '/dash/volunteer', 
                    'Manage Departments'
                ),
                $this->admMenuLnk(
                    '/dash/team-resources', 
                    'Team Resources', 
                    '', 
                    1, 
                    [
                        $this->admMenuLnk(
                            '/dash/team-resources', 
                            'Team Resources'
                        ),
                        $this->admMenuLnk(
                            '/dash/development-team-update', 
                            'Software Development Updates'
                        )
                    ]
                )
            ]
        );
        return $treeMenu;
    }

    protected function loadAdmMenuVolunteer()
    {
        $treeMenu = [];
        $treeMenu[] = $this->addAdmMenuCollapse();
        $treeMenu[] = $this->admMenuLnk(
            '/dash/volunteer', 
            'Dashboard', 
            '<i class="fa fa-home"></i>'
        );
        $stars = '';
        if (isset($GLOBALS["SL"]->x["yourUserInfo"]) 
            && isset($GLOBALS["SL"]->x["yourUserInfo"]->UserInfoStars)) {
            $stars = '<nobr>';
            for ($s = 0; $s < $GLOBALS["SL"]->x["yourUserInfo"]->UserInfoStars; $s++) {
                if ($s > 0 && $s%25 == 0) {
                    $stars .= '</nobr><nobr>';
                }
                $stars .= '<img src="/openpolice/star1.png" border=0 height=15 '
                    . 'class="mLn10" alt="Gold Star"> ';
            }
            $stars .= '</nobr>';
        }
        $treeMenu[] = $this->admMenuLnk(
            'javascript:;', 
            'Department List', 
            '<i class="fa fa-university" aria-hidden="true"></i>', 
            1, 
            [
                $this->admMenuLnk(
                    '/dash/volunteer', 
                    'All Departments'
                ),
                $this->admMenuLnk(
                    '/dash/verify-next-department', 
                    'Verify A Department', 
                    '', 
                    1, 
                    $this->subLinksVerifDept()
                ),
                $this->admMenuLnk(
                    '/department-accessibility', 
                    'Department Scores Report'
                )
            ]
        );
        $treeMenu[] = $this->admMenuLnk(
            'javascript:;', 
            'Volunteers', 
            '<i class="fa fa-users"></i>', 
            1, 
            [
                $this->admMenuLnk(
                    '/dash/volunteer-stars', 
                    'All-Stars List'
                ),
                $this->admMenuLnk(
                    '/my-profile', 
                    'My Profile'
                )
            ]
        );
        return $treeMenu;
    }
    

}