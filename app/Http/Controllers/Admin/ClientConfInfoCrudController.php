<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ClientConfInfoRequest;
use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ClientConfInfoCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ClientConfInfoCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\BulkDeleteOperation;
    private function getFieldsData() {
        return [
            [
                'name'=> 'client_code',
                'label' => 'Client code',
                'type'=> 'text'
            ],
            [
                'name' => 'client_name',
                'label' => 'Client name',
                'type' => 'text',
            ],
            [
                'name' => 'ocr_limit',
                'label' => 'OCR limit',
                'type' => 'text',
            ],
            [
                'label'     => "User",
                'type'      => 'select',
                'name'      => 'userid',
                'entity'    => 'user',
                'model'     => "App\Models\User",
                'attribute' => 'username',
                'allows_null'=> false,
            ],
            [
                'name'=>'status',
                'type'=>'select_from_array',
                'label'=>'Status',
                'options'=> [0 => 'Test', 1 => 'Product', 2=>'Block'],
                'allows_null'=>false,
                'default'=>0
            ],
            [
                'name'=>'cac_ref_limit',
                'type'=>'number',
                'label'=>'Cac ref limit',
            ],
            [
                'name'=>'datadate',
                'type'=>'datetime_picker',
                'label'=>'Data date'
            ]
        ];
    }
    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        $this->crud->setModel(\App\Models\ClientConfInfo::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/client-conf-info');
        $this->crud->setEntityNameStrings('client_conf_info', 'Client Conf Info');
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
        $this->crud->addColumns($this->getFieldsData());
        $this->crud->addFilter([
            'name'  => 'userid',
            'type'  => 'select2',
            'label' => 'User'
        ], function() {
            return User::all()->pluck('username', 'id')->toArray();
        }, function($value) {
            $this->crud->addClause('where', 'userid', $value);
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
        $this->crud->setValidation(ClientConfInfoRequest::class);
        $this->crud->setFromDb(); // fields
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

