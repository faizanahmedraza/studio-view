<?php

namespace App\Services\Menu;

use Illuminate\Support\Facades\Auth;
use Spatie\Menu\Laravel\Link;
use Spatie\Menu\Laravel\Menu;

/**
 * Class AdminMenu
 *
 * @author Muzafar Ali Jatoi <muzfr7@gmail.com>
 * @date   23/9/18
 */
class AdminMenu
{
    public function register()
    {
        Menu::macro('admin', function () {
            /*getting user permissions*/
            // $userPermissoins = Auth::user()->getPermissions();
            $userPermissoins = ['users.index'];



            return Menu::new()
                ->addClass('page-sidebar-menu')
                ->setAttribute('data-keep-expanded', 'false')
                ->setAttribute('data-auto-scroll', 'true')
                ->setAttribute('data-slide-speed', '200')
                ->html('<div class="sidebar-toggler hidden-phone"></div>')
                ->add(Link::toRoute(
                    'dashboard.index',
                    '<i class="fa fa-home"></i> <span class="title">Dashboard</span>'
                )
                    ->addParentClass('start'))
                // ->submenuIf(can_access_route(['role.index', 'role.create'], $userPermissoins), '
                //     <a href="javascript:;">
                //         <i class="fa fa-exclamation-circle"></i>
                //         <span class="title">Roles </span>
                //         <span class="arrow open"></span>
                //         <!--<span class="selected"></span>-->
                //     </a>
                //     ',
                //     Menu::new()
                //         ->addClass('sub-menu')
                //         ->addIf(can_access_route('role.index', $userPermissoins),
                //             Link::toRoute('role.index', '<span class="title">Role List</span>'))
                //         ->addIf(can_access_route('role.create', $userPermissoins),
                //             Link::toRoute('role.create', '<span class="title">Add Role</span>'))
                // )
                // ->submenuIf(can_access_route(['sub-admin.index', 'sub-admin.create'], $userPermissoins), '
                //     <a href="javascript:;">
                //         <i class="fa fa-users"></i>
                //         <span class="title">Sub Admins </span>
                //         <span class="arrow open"></span>
                //         <!--<span class="selected"></span>-->
                //     </a>
                //     ',
                //     Menu::new()
                //         ->addClass('sub-menu')
                //         ->addIf(can_access_route('sub-admin.index', $userPermissoins),
                //             Link::toRoute('sub-admin.index', '<span class="title">Sub Admins List</span>'))
                //         ->addIf(can_access_route('sub-admin.create', $userPermissoins),
                //             Link::toRoute('sub-admin.create', '<span class="title">Add Sub Admin</span>'))
                // )
                ->submenuIf(can_access_route(['users.index'], $userPermissoins), '
                    <a href="javascript:;">
                        <i class="fa fa-user"></i>
                        <span class="title">Users</span>
                        <span class="arrow open"></span>
                    </a>
                    ',
                    Menu::new()
                        ->addClass('sub-menu')
                        ->addIf(can_access_route('users.index', $userPermissoins),
                            Link::toRoute('users.index', '<span class="title">Users List</span>'))
                        // ->addIf(can_access_route('new-request.index', $userPermissoins),
                        //     Link::toRoute('new-request.index', '<span class="title">New Request List</span>'))
                )
                ->submenuIf(true, '
                    <a href="javascript:;">
                        <i class="fa fa-music"></i>
                        <span class="title">Studios</span>
                        <span class="arrow open"></span>
                    </a>
                    ',
                    Menu::new()
                        ->addClass('sub-menu')
                        ->addIf(can_access_route('studio.index', $userPermissoins),
                            Link::toRoute('studio.index', '<span class="title">Studio List</span>'))
                        ->addIf(can_access_route('studio.pending.index', $userPermissoins),
                            Link::toRoute('studio.pending.index', '<span class="title">Studio Pending List</span>'))
                        // ->addIf(can_access_route('new-request.index', $userPermissoins),
                        //     Link::toRoute('new-request.index', '<span class="title">New Request List</span>'))
                )
                // ->submenuIf(can_access_route(['attendance.index','attendance.addAttendance'], $userPermissoins), '
                //     <a href="javascript:;">
                //         <i class="fa fa-clock-o"></i>
                //         <span class="title">Attendance</span>
                //         <span class="arrow open"></span>
                //     </a>
                //     ',
                //     Menu::new()
                //         ->addClass('sub-menu')

                //         ->addIf(can_access_route('attendance.index', $userPermissoins),
                //         Link::toRoute('attendance.index', '<span class="title">Attendance List</span>'))

                //         ->addIf(can_access_route('attendance.addAttendance', $userPermissoins),
                //         Link::toRoute('attendance.addAttendance', '<span class="title">Add Attendance</span>'))


                //         // ->addIf(can_access_route('attendance.report', $userPermissoins),
                //         //     Link::toRoute('attendance.report', '<span class="title">Attendance Report</span>'))
                //         // ->addIf(can_access_route('attendance.report', $userPermissoins),
                //         //     Link::toRoute('attendance.getHours', '<span class="title">Hours Report</span>'))
                //         // ->addIf(can_access_route('attendance.report', $userPermissoins),
                //         //     Link::toRoute('attendance.getMonths', '<span class="title">Monthly Report</span>'))
                // )

                // ->submenuIf(can_access_route(['attendance.getHours','attendance.report','attendance.getMonths'], $userPermissoins), '
                //     <a href="javascript:;">
                //         <i class="fa fa-clock-o"></i>
                //         <span class="title">Attendance Reports</span>
                //         <span class="arrow open"></span>
                //     </a>
                //     ',
                //     Menu::new()
                //         ->addClass('sub-menu')

                //         // ->addIf(can_access_route('attendance.addAttendance', $userPermissoins),
                //         // Link::toRoute('attendance.addAttendance', '<span class="title">Add Attendance</span>'))

                //         // ->addIf(can_access_route('attendance.index', $userPermissoins),
                //         //     Link::toRoute('attendance.index', '<span class="title">Attendance List</span>'))
                //         ->addIf(can_access_route('attendance.report', $userPermissoins),
                //             Link::toRoute('attendance.report', '<span class="title">Attendance Report</span>'))
                //         ->addIf(can_access_route('attendance.getHours', $userPermissoins),
                //             Link::toRoute('attendance.getHours', '<span class="title">Hours Report</span>'))
                //         ->addIf(can_access_route('attendance.getMonths', $userPermissoins),
                //             Link::toRoute('attendance.getMonths', '<span class="title">Monthly Report</span>'))
                // )

                // ->submenuIf(can_access_route(['attendancePage.edit'], $userPermissoins), '
                //     <a href="javascript:;">
                //         <i class="fa fa-paperclip"></i>
                //         <span class="title">Page</span>
                //         <span class="arrow open"></span>
                //     </a>
                //     ',
                //     Menu::new()
                //         ->addClass('sub-menu')
                //         ->addIf(can_access_route('attendancePage.edit', $userPermissoins),
                //             Link::toRoute('attendancePage.edit', '<span class="title">Page Edit</span>'))
                // )
                // ->submenuIf(can_access_route(['department.index', 'department.create'], $userPermissoins), '
                //     <a href="javascript:;">
                //         <i class="fa fa-users"></i>
                //         <span class="title">Departments</span>
                //         <span class="arrow open"></span>
                //         <!--<span class="selected"></span>-->
                //     </a>
                //     ',
                //     Menu::new()
                //         ->addClass('sub-menu')
                //         ->addIf(can_access_route('department.index', $userPermissoins),
                //             Link::toRoute('department.index', '<span class="title">Department List</span>'))
                //         ->addIf(can_access_route('department.create', $userPermissoins),
                //             Link::toRoute('department.create', '<span class="title">Add Department</span>'))
                // )

                // ->submenuIf(can_access_route(['public_holidays.index','public_holidays.create'], $userPermissoins), '
                //     <a href="javascript:;">
                //         <i class="fa fa-clock-o"></i>
                //         <span class="title">Public Holidays</span>
                //         <span class="arrow open"></span>
                //     </a>
                //     ',
                //     Menu::new()
                //         ->addClass('sub-menu')

                //         ->addIf(can_access_route('public_holidays.index', $userPermissoins),
                //         Link::toRoute('public_holidays.index', '<span class="title">Public Holidays List</span>'))

                //         ->addIf(can_access_route('public_holidays.create', $userPermissoins),
                //         Link::toRoute('public_holidays.create', '<span class="title">Add Public Holiday</span>'))
                // )

                // // leaves

                // ->submenuIf(can_access_route(['leave.index','leave.create'], $userPermissoins), '
                //     <a href="javascript:;">
                //         <i class="fa fa-clock-o"></i>
                //         <span class="title">Leaves</span>
                //         <span class="arrow open"></span>
                //     </a>
                //     ',
                //     Menu::new()
                //         ->addClass('sub-menu')

                //         ->addIf(can_access_route('leave.index', $userPermissoins),
                //         Link::toRoute('leave.index', '<span class="title">Leave List</span>'))

                //         // ->addIf(can_access_route('leave.create', $userPermissoins),
                //         // Link::toRoute('leave.create', '<span class="title">Apply For Leave</span>'))
                // )

                // // leaves

                // // leave types

                // ->submenuIf(can_access_route(['leave-type.index','leave-type.create'], $userPermissoins), '
                //     <a href="javascript:;">
                //         <i class="fa fa-clock-o"></i>
                //         <span class="title">Leave Types</span>
                //         <span class="arrow open"></span>
                //     </a>
                //     ',
                //     Menu::new()
                //         ->addClass('sub-menu')

                //         ->addIf(can_access_route('leave-type.index', $userPermissoins),
                //         Link::toRoute('leave-type.index', '<span class="title">Leave Type List</span>'))

                //         ->addIf(can_access_route('leave-type.create', $userPermissoins),
                //         Link::toRoute('leave-type.create', '<span class="title">Add Leave Type</span>'))
                // )

                // // leave types

                // //Add leave

                // ->submenuIf(can_access_route(['apply-leave.index','apply-leave.create'], $userPermissoins), '
                //     <a href="javascript:;">
                //         <i class="fa fa-clock-o"></i>
                //         <span class="title">Apply For Leaves</span>
                //         <span class="arrow open"></span>
                //     </a>
                //     ',
                //     Menu::new()
                //         ->addClass('sub-menu')

                //         ->addIf(can_access_route('apply-leave.index', $userPermissoins),
                //         Link::toRoute('apply-leave.index', '<span class="title">Apply For Leaves List</span>'))

                //         ->addIf(can_access_route('apply-leave.create', $userPermissoins),
                //         Link::toRoute('apply-leave.create', '<span class="title">Add Apply For Leave</span>'))
                // )




                // ->addIf(can_access_route('users.change-password', $userPermissoins), (Link::toRoute(
                //     'users.change-password',
                //     '<i class="fa fa-lock"></i> <span class="title">Change Password</span>'
                // )))
                ->add(Link::toRoute(
                    'logout',
                    '<i class="fa fa-sign-out"></i> <span class="title">Logout</span>'
                )->setAttribute('id', 'leftnav-logout-link'))
                ->setActiveFromRequest();

        });
    }
}
