<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\IdTypeLogRequest;
use App\Models\ClientConfInfo;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class IdTypeLogCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class IdTypeLogCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\BulkDeleteOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        $this->crud->setModel(\App\Models\IdTypeLog::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/id-type-log');
        $this->crud->setEntityNameStrings('id_type_log', 'Id Type Logs');
        $this->crud->denyAccess(['create', 'update']);
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->setFromDb(); // columns
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
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        $this->crud->setValidation(IdTypeLogRequest::class);

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
