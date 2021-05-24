<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ImageMetadataRequest;
use App\Models\ClientConfInfo;
use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ImageMetadataCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ImageMetadataCrudController extends CrudController
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
                'name'=> 'host',
                'label' => 'Host',
                'type'=> 'text'
            ],
            [
                'name'=>'image_id',
                'label'=>'Image Id',
                'type'=>'text'
            ],
            [
                'name' => 'label',
                'label' => 'Label',
                'type' => 'text',
            ],
            [
                'name' => 'owner',
                'label' => 'Owner',
                'type' => 'text',
            ],
            [
                'name' => 'insert_date',
                'label' => 'Insert date',
                'type' => 'datetime_picker',
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
        $this->crud->setModel(\App\Models\ImageMetadata::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/image-metadata');
        $this->crud->setEntityNameStrings('image_metadata', 'Image Metadatas');
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
        $this->crud->denyAccess(['create', 'update']);
        $this->crud->addFilter([
            'name'  => 'owner',
            'type'  => 'select2',
            'label' => 'Owner'
        ], function() {
            return ClientConfInfo::all()->pluck('client_code', 'client_code')->toArray();
        }, function($value) {
            $this->crud->addClause('where', 'owner', $value);
        });
        $this->crud->addFilter([
            'name'  => 'label',
            'type'  => 'text',
            'label' => 'Label'
        ], false);
//        $this->crud->addColumn([
//            'name'=>'save_path',
//            'label'=>'Preview',
//            'type'=>'image',
//            'prefix'=>''
//        ]);
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        $this->crud->setValidation(ImageMetadataRequest::class);
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
