<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Form specific to use with orm, used in the Auth Crud
 *
 *
 * @package    OC
 * @category   Helpers
 * @author     Chema <chema@open-classifieds.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 * @see https://github.com/colinbm/kohana-formmanager
 */

class FormOrm extends FormManager {


    /**
    * Constructor
    * @param string $model model name
    * @param int $id Primary key of the model. Ignored unless $this->model is set.
    * @param string $parent_container The parent container for the form elements
    */
    public function __construct($model,$id = null, $parent_container = null)
    {
        $this->model = $model;
        $element = ORM::factory($this->model, $id);
        $this->exclude_fields = $element->exclude_fields();
        parent::__construct($element, $parent_container);

    }

    protected function setup()
    {
        $element = $this->object;
        $element->form_setup($this);
    }

    public function is_new() {
        return $this->object;
    }

    /**
     * Load any models that "belong to" the model this form is driven by
     */
    protected function _load_belongs_to() {
        // check for any relationships we need to load with the object
        if (isset($this->object) && $belongs_to = $this->object->belongs_to()) {

            // iterate over all related objects that "belong" to our model
            foreach ($belongs_to as $alias => $config) {
                // if we've explicitly excluded this relationship from the form, move on...
                if( isset($this->exclude_relationship[$alias]) ) continue;

                // assign the model name and foreign key
                $model = isset($config['model']) ? $config['model'] : $alias;
                $foreign_key = isset($config['foreign_key']) ? $config['foreign_key'] : $model . '_id';

                // if our foreign key is found in the fields list
                if (isset($this->fields[$foreign_key])) {
                    // set up the relationship
                    $model = $this->setup_relationship($model, $this->fields[$foreign_key]['name']);

                    // fetch all available options for this relationship
                    // and make sure the relationship is displayed appropriately
                    $this->fields[$foreign_key]['options'] = array();
                    foreach ($model->find_all() as $row) {
                        $this->fields[$foreign_key]['options'][$row->{$model->primary_key()}] = isset($this->fields[$foreign_key]['caption']) ?
                            $row->{$this->fields[$foreign_key]['caption']} :
                            $row->{$model->primary_key()};
                    }
                    $this->set_field_value($foreign_key, 'display_as', 'select');
                    $this->set_field_value($foreign_key, 'dont_reindex_options', true);
                }
            }
        }
    }
}