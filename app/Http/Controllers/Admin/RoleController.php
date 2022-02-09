<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Models\Roles;
use App\Models\Permissions;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use App\Http\Requests\Admin\RoleRequest;
use App\Http\Requests\Admin\UpdateRoleRequest;


class RoleController extends Controller
{

    private $RoleRepository;

    public function __construct(RoleRepositoryInterface $RoleRepositoryInterface)
    {
        $this->middleware('auth:admin');
        parent::__construct();
        $this->RoleRepository = $RoleRepositoryInterface;
    }

    /**
     * Index action
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $Roles = Roles::NotAdminRole()->get();
        return backend_view('role.index', compact(
            'Roles'
        ));
    }

    /**
     * Create action
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        // getting dashnboard card permissions
        $dashboard_card_permissions = Permissions::GetAllDashboardCardPermissions();
        return backend_view('role.create', compact(
            'dashboard_card_permissions'
        ))->with('Role successfully added');
    }

    /**
     * store action
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function store(RoleRequest $request)
    {
        $data = $request->except(
            [
                '_token',
                '_method',
            ]
        );
        /*including dashboard cards rights*/
        $dashboard_cards_rights = '';
        if (isset($data['dashboard_card_permission'])) {
            $dashboard_cards_rights = implode(",", $data['dashboard_card_permission']);
        }
        /*createing inserting data*/
        $create = [
            'display_name' => $data['name'],
            'role_name' => SlugMaker($data['name']),
            'dashbaord_cards_rights' => $dashboard_cards_rights,
            'type' => Roles::ROLE_TYPE_NAME,
        ];
        /*inserting data*/
        $this->RoleRepository->create($create);
        /*return data */
        return redirect()
            ->route('role.index')
            ->with('success', 'Role added successfully.');
    }

    /**
     * show action
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Roles $role)
    {
        $permissions = Permissions::GetAllPermissions();
        $route_names = $role->Permissions->pluck('route_name')->toArray();
        return backend_view('role.show', compact(
            'role',
            'route_names',
            'permissions'
        ));
    }


    /**
     * edit action
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Roles $role)
    {
        // getting dashnboard card permissions
        $dashboard_card_permissions = Permissions::GetAllDashboardCardPermissions();
        return backend_view('role.edit', compact(
            'role',
            'dashboard_card_permissions'
        ));
    }

    /**
     * update action
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update(UpdateRoleRequest $request, $role)
    {
        /*getting all requests data*/
        $Postdata = $request->all();
        /*including dashboard cards rights*/
        $dashboard_cards_rights = '';
        if (isset($Postdata['dashboard_card_permission'])) {
            $dashboard_cards_rights = implode(",", $Postdata['dashboard_card_permission']);
        }
        /*creating updating data*/
        $update_data = [
            'display_name' => $Postdata['name'],
            'role_name' => SlugMaker($Postdata['name']),
            'dashbaord_cards_rights' => $dashboard_cards_rights,
            'type' => Roles::ROLE_TYPE_NAME,
        ];
        /*ipdating data*/
        $this->RoleRepository->update($role, $update_data);
        /*return data */
        return redirect()
            ->route('role.index')
            ->with('success', 'Role updated successfully.');
    }

    /**
     * Show permission list
     */
    public function setPermissions(Roles $role)
    {
        // getting permissions
        $permissions_list = Permissions::getAllPermissions();

        return backend_view('role.set-permissions', compact(
            'role',
            'permissions_list'
        ));
    }

    /**
     * update permission on role
     */

    public function setPermissionsUpdate(Request $request, $role)
    {
        // now creating insert data of permissions
        $insert_permissions = [];
        $role_permissions = $request->permissions ?? [];
        //$role_permissions = $request->permissions;
        foreach ($role_permissions as $role_permission) {
            if (strpos($role_permission, '|') !== false) {
                foreach (explode('|', $role_permission) as $child_permission) {
                    $insert_permissions[] = ['route_name' => $child_permission, 'role_id' => $role];
                }
            } else {
                $insert_permissions[] = ['route_name' => $role_permission, 'role_id' => $role];
            }
        }
        // deleting old data
        Permissions::where('role_id', $role)->delete();
        //inserting new data
        Permissions::insert($insert_permissions);
        /*return data */
        return redirect()
            ->route('role.index')
            ->with('success', 'Role permissions updated successfully');
    }
}
