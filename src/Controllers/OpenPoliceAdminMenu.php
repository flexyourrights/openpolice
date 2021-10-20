<?php
/**
  * OpenPoliceAdminMenu is responsible for building the menu inside the
  * dashboard area for all user types.
  *
  * OpenPolice.org
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <morgan@flexyourrights.org>
  * @since v0.0.1
  */
namespace FlexYourRights\OpenPolice\Controllers;

use Auth;
use RockHopSoft\Survloop\Controllers\Admin\AdminMenu;

class OpenPoliceAdminMenu extends AdminMenu
{
    public function loadAdmMenu($currUser = null, $currPage = '')
    {
        $this->currUser = $currUser;
        $this->currPage = $currPage;
        $treeMenu = [];
        if (isset($this->currUser)) {
            $published = $flagged = 0;
            if ($this->currUser->hasRole('administrator|databaser|brancher')) {

                return $this->loadAdmMenuAdmin($currUser);

            } elseif ($this->currUser->hasRole('staff')) {

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

    protected function loadAdmMenuAdmin($currUser = null)
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
                    $this->genDeptSubMenu()
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
                    '/dash/complaint-stats',
                    'Statistics',
                    '',
                    1,
                    [
                        $this->admMenuLnk(
                            '/dash/complaint-stats',
                            'Complaint Stats'
                        ),
                        $this->admMenuLnk(
                            '/dash/demographic-stats',
                            'Demographic Stats'
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
                            'Software Updates'
                        )
                    ]
                )
            ]
        );
        return $this->addAdmMenuBasics($treeMenu);
    }

    protected function loadAdmMenuStaff()
    {
        $treeMenu = [];
        $treeMenu[] = $this->addAdmMenuCollapse();
        $treeMenu[] = $this->admMenuLnk(
            '/dash/staff',
            'Dashboard',
            '<i class="fa fa-home"></i>'
        );
        $treeMenu[] = $this->admMenuLnk(
            '/dash/all-complete-complaints',
            'Manage Complaints',
            '<i class="fa fa-frown-o" aria-hidden="true"></i>',
            1,
            $this->subLinksComplaintTypes()
        );
        $treeMenu[] = $this->admMenuLnk(
            '/dash/volunteer',
            'Manage Departments',
            '<i class="fa fa-university" aria-hidden="true"></i>',
            1,
            $this->genDeptSubMenu()
        );
        if (in_array(Auth::user()->id, [863, 195, 897])) {
            $treeMenu[] = $this->admMenuLnk(
                '/dashboard/contact',
                'Contact Form',
                '<i class="fa fa-envelope-o" aria-hidden="true"></i>',
                1,
                [
                    $this->admMenuLnk(
                        '/dashboard/contact',
                        '',
                        '',
                        1,
                        [
                            $this->admMenuLnk(
                                '/dashboard/contact',
                                'Unread'
                            ),
                            $this->admMenuLnk(
                                '/dashboard/contact/hold',
                                'On Hold'
                            ),
                            $this->admMenuLnk(
                                '/dashboard/contact/resolved',
                                'Resolved'
                            ),
                            $this->admMenuLnk(
                                '/dashboard/contact/trash',
                                'Trash'
                            )
                        ]
                    )
                ]
            );
        }
        /*
        $treeMenu[] = $this->admMenuLnk(
            '/dash/manage-partners',
            'Manage Partners',
            '<i class="fa fa-users" aria-hidden="true"></i>',
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
        );
        $treeMenu[] = $this->admMenuLnk(
            '/dash/team-resources',
            'Team Resources',
            '<i class="fa fa-list-alt" aria-hidden="true"></i>',
            1,
            [
                $this->admMenuLnk(
                    '/dash/team-resources',
                    'Team Resources'
                ),
                $this->admMenuLnk(
                    '/dash/development-team-update',
                    'Software Updates'
                )
            ]
        );
        */
        return $treeMenu;
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
                            'Software Updates'
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
        /*
        $stars = '';
        if (isset($GLOBALS["SL"]->x["yourUserInfo"])
            && isset($GLOBALS["SL"]->x["yourUserInfo"]->user_info_stars)) {
            $stars = '<nobr>';
            for ($s = 0; $s < $GLOBALS["SL"]->x["yourUserInfo"]->user_info_stars; $s++) {
                if ($s > 0 && $s%25 == 0) {
                    $stars .= '</nobr><nobr>';
                }
                $stars .= '<img src="/openpolice/star1.png" border=0 height=15 '
                    . 'class="mLn10" alt="Gold Star"> ';
            }
            $stars .= '</nobr>';
        }
        */
        $treeMenu[] = $this->admMenuLnk(
            '/dash/volunteer',
            'Departments List',
            '<i class="fa fa-university" aria-hidden="true"></i>'
        );
        $treeMenu[] = $this->admMenuLnk(
            '/dash/verify-next-department',
            'Verify A Department',
            '<i class="fa fa-search" aria-hidden="true"></i>',
            1,
            $this->subLinksVerifDept()
        );
        /*
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
        */
        return $treeMenu;
    }

    protected function genDeptSubMenu()
    {
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
                'Accessibility Scores'
            )
        ];
        if ($GLOBALS["SL"]->REQ->has('cid')) {
            $deptSubMenu = $this->subLinksVerifDept();
        }
        return $deptSubMenu;
    }


}