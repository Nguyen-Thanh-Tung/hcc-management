<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UserRequest;
use App\Models\Role;
use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Support\Facades\Hash;

/**
 * Class UserCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class UserCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\BulkDeleteOperation;

    private function getFieldsData($show = FALSE) {
        return [
            [
                'name'=> 'username',
                'label' => 'Username',
                'type'=> 'text'
            ],
            [
                'name'=>'password',
                'label'=>'Password',
                'type'=>'password'
            ],
            [
                'name' => 'enabled',
                'label' => 'Enabled',
                'type' => 'boolean',
            ],
            [    // Select2Multiple = n-n relationship (with pivot table)
                'label'     => "Roles",
                'type'      => ($show ? "select": 'relationship'),
                'name'      => 'roles', // the method that defines the relationship in your Model
// optional
                'entity'    => 'roles', // the method that defines the relationship in your Model
                'model'     => "App\Models\Role", // foreign key model
                'attribute' => 'name', // foreign key attribute that is shown to user
                'pivot'     => true, // on create&update, do you need to add/delete pivot table entries?
            ],
            [
                'name' => 'rate_limit_config',
                'label' => 'Rate limit config',
                'type' => 'text',
            ],
        ];
    }

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        $this->crud->setModel(\App\Models\User::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/user');
        $this->crud->setEntityNameStrings('user', 'users');
        $this->crud->addFields($this->getFieldsData());
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->addColumns($this->getFieldsData(TRUE));
        $this->crud->removeColumn('password');
        // select2_multiple filter
        $this->crud->addFilter([
            'name'  => 'roles',
            'type'  => 'select2_multiple',
            'label' => 'Roles'
        ], function() { // the options that show up in the select2
            return Role::all()->pluck('name', 'id')->toArray();
        }, function($values) { // if the filter is active
            foreach (json_decode($values) as $key => $value) {
                $this->crud->query = $this->crud->query->whereHas('roles', function ($query) use ($value) {
                    $query->where('role_id', $value);
                });
            }
        });
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        $this->crud->setValidation(UserRequest::class);
        $this->crud->setFromDb();
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
