<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CountRequestRequest;
use App\Models\ClientConfInfo;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class CountRequestCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CountRequestCrudController extends CrudController
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
                'type'=> 'text',
                'attributes' => ['disabled' => 'disabled'],
            ],
            [
                'name' => 'remain',
                'label' => 'Remain',
                'type' => 'number',
            ],
            [
                'name' => 'limit_level',
                'label' => 'Limit level',
                'type' => 'number',
                'attributes' => ['disabled' => 'disabled'],
            ],
            [
                'name' => 'request_type',
                'label' => 'Request type',
                'type' => 'text',
                'attributes' => ['disabled' => 'disabled'],
            ],
            [
                'name' => 'month',
                'label' => 'Month',
                'type' => 'text',
                'attributes' => ['disabled' => 'disabled'],
            ],
            [
                'name' => 'datadate',
                'label' => 'Data date',
                'type' => 'datetime_picker',
            ],
            [
                'name' => 'last_update',
                'label' => 'Last update',
                'type' => 'datetime_picker',
                'attributes' => ['disabled' => 'disabled'],
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
        $this->crud->setModel(\App\Models\CountRequest::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/count-request');
        $this->crud->setEntityNameStrings('count_request', 'Count Requests');
        $this->crud->denyAccess(['create']);
        $this->crud->addFields($this->getFieldsData());
        $this->crud->addFilter([
            'name'  => 'client_code',
            'type'  => 'select2',
            'label' => 'Client code'
        ], function() {
            return ClientConfInfo::all()->pluck('client_code', 'client_code')->toArray();
        }, function($value) {
            $this->crud->addClause('where', 'client_code', $value);
        });
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->addColumns($this->getFieldsData()); // columns
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        $this->crud->setValidation(CountRequestRequest::class);
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
