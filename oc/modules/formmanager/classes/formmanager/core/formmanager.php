<?php defined('SYSPATH') or die('No direct script access.');

abstract class FormManager_Core_FormManager
{

	protected $model = null;
	protected $primary_key = null;
	protected $rules = array();
	public $fields = array();
	public $object = null;

	public $method = 'post';
	public $action = '';
	protected $expected_input;

    /**
     * Any buttons required for this form
     * @var array
     */
    public $buttons    = array();
    /**
     * Any HTML attributes we want to add to the form.
     * @var array
     */
    public $attributes = array();

    /**
     * Any relationships we wish to exclude
     * @var array
     */
    protected $exclude_relationship = array();

	public $submit_text;

	const SUBMIT_STATUS_FAIL = 'fail';
	const SUBMIT_STATUS_SUCCESS = 'success';

	protected $submit_status = null;

	protected $container;

	public $custom_view;

	/*
	 * Include these fields in the form
	 */
	protected $include_fields = array();

	/*
	 * Exclude these fields from the form (only used if $include_fields is empty)
	 */
	protected $exclude_fields = array();

	public $fieldsets = array();

	protected $form_name;
	
	protected $uploads;

	protected function setup() {}

	/**
	 * Constructor
	 *
	 * @param int $id Primary key of the model. Ignored unless $this->model is set.
     * @param string $parent_container The parent container for the form elements
	 **/
	public function __construct($id = null, $parent_container = null) {
        $this->_init($parent_container);
        $this->_load_model($id);

		$this->_preload_has_many();
		
        $this->setup();

        $this->_load_belongs_to();
		$this->_load_has_many();
        $this->_configure_fields();
        $this->_load_labels();
        $this->_load_input_data();
	}

    /**
     * Set up any relationships for the current form. This method should be overloaded if there are any
     * custom relational constraints required for the current form.
     *
     * @param string $model The name of the Model to be loaded
     * @return ORM
     */
    protected function setup_relationship($model, $field) {
        return ORM::factory($model);
    }

    /**
     * Setup the form container and load the custom view, if one exists.
     *
     * @param string $parent_container The name of the parent container for the form elements
     * @return void
     */
    private function _init($parent_container) {
        // get the name of our form, first
        $this->form_name = preg_replace('/^form_/', '', strtolower(get_class($this)));

        // set the custom view, if there isn't one
        if( empty($this->custom_view)) {
            $this->custom_view = 'formmanager/' . $this->form_name;
        }

        // set up any wrapping containers for this form
        if ($parent_container) {
            $this->container = $parent_container . '[' . $this->form_name . ']';
        } else {
            $this->container = $this->form_name;
        }
    }

    /**
     * Load the model this form will be built on top of.
     *
     * @param null $id
     * @return void
     */
    private function _load_model($id = NULL) {
	
        // set up the model, if one is either specified or passed in
        if ($this->model OR $id instanceof Model) {

            // if $id is not and instance of Model, load the model via factory, passing in $id
            if( ! ($id instanceof Model) ) {
                $this->object = ORM::factory($this->model, $id);
            }
            // otherwise, just assign the model instance to our object
            else {
                $this->object = $id;
            }


            $table_columns = $this->object->table_columns();
            foreach ($table_columns as $table_column) {
                if ($table_column['key'] == 'PRI') {
                    $this->primary_key = $table_column['column_name'];
                }
                $this->add_field($table_column['column_name'], $table_column);
            }

            // handle any inclusions or exclusions
            if ($this->include_fields) {
                foreach ($this->fields as $key => $field) {
                    if (!in_array($key, $this->include_fields)) {
                        unset($this->fields[$key]);
                    }
                }
            } else if ($this->exclude_fields) {
                foreach ($this->fields as $key => $field) {
                    if (in_array($key, $this->exclude_fields)) {
                        unset($this->fields[$key]);
                    }
                }
            }

            // assign all field values
            foreach ($this->fields as $key => $field) {
                $this->fields[$key]['value'] = $this->object->$key;
            }

            // if the object has a created column, throw it away
            if ($column = $this->object->created_column()) {
                $this->remove_field($column['column']);
            }

            // if the object has an updated column, throw it away
            if ($column = $this->object->updated_column()) {
                $this->remove_field($column['column']);
            }
        }
    }

    private function _load_labels() {
        if (isset($this->object)) {
            $labels = $this->object->labels();
            foreach ($this->fields as $key => $value) {
                if (isset($labels[$key])) {
                    $this->set_field_value($key, 'label', $labels[$key], true);
                }
            }
        }
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
                        $this->fields[$foreign_key]['options'][$row->{$model->primary_key()}] = isset($this->fields[$foreign_key]['foreign_name']) ?
                            $row->{$this->fields[$foreign_key]['foreign_name']} :
                            $row->{$model->primary_key()};
                    }
                    $this->set_field_value($foreign_key, 'display_as', 'select');
                    $this->set_field_value($foreign_key, 'dont_reindex_options', true);
                }
            }
        }
    }
	
	protected function _preload_has_many() {
		// check for any relationships we need to load with the object
		if (isset($this->object) && $has_many = $this->object->has_many()) {
			foreach ($has_many as $alias => $config) {
				if ($config['through']) {
					$this->add_field($alias, array(
						'name'       => $alias,
						'relation'   => 'has_many',
						'display_as' => 'checkboxes',
						'options'    => array(),
						'is_nullable'=> false,
						'dont_reindex_options'=> true,
					));
				}
			}
		}
	}
	
	protected function _load_has_many() {
		// check for any relationships we need to load with the object
		if (isset($this->object) && $has_many = $this->object->has_many()) {
			foreach ($has_many as $alias => $config) {
				// if the field's been removed in setup() then don't proceed.
				if (!isset($this->fields[$alias])) continue;
				
				if ($config['through']) {
					
					$model = $this->setup_relationship($config['model'], $alias);
					$options = array();

					foreach ($model->find_all() as $row) {
						$options[$row->{$model->primary_key()}] = isset($this->fields[$alias]['foreign_name']) ?
							$row->{$this->fields[$alias]['foreign_name']} :
							$row;
							
					}
					
					$this->fields[$alias]['options'] = $options;
					
					$this->fields[$alias]['value'] = array();
					$values = $this->object->{$alias};
					foreach ($values->find_all() as $value) {
						$this->fields[$alias]['value'][] = $value->{$values->primary_key()};
					}
					
				}
			}
		}
	}

    /**
     * Configure all the form fields, as per, setup()
     */
    private function _configure_fields() {
        foreach ($this->fields as $key => $field) {
            $this->configure_field($key);
        }
    }

    /**
     * Load any input data received from a form submission, or Query string
     */
    private function _load_input_data() {
        if ($this->method == 'post') {
            $this->expected_input = $_POST;
        } elseif ($this->method == 'get') {
            $this->expected_input = $_GET;
        }
    }

	public function add_fieldset($legend, $fields) {
		$this->fieldsets[] = array(
			'legend' => $legend,
			'fields' => $fields
		);
	}
	
	/**
	 * Add a validation rule. These will be in addition to any defined in the model
	 *
	 * @param string $field The field name
	 * @param string $rule The rule type
	 * @param array The rule parameters
	 * @return void
	 **/
	public function rule($field, $rule, array $params = null) {
		$this->rules[] = array(
			'field'  => $field,
			'rule'   => $rule,
			'params' => $params
		);
	}
	
	/**
	 * Set the value of a field
	 *
	 * @param string $key The field name
	 * @param mixed $value The field value
	 * @return void
	 **/
	public function set_value($key, $value) {
		if ($this->model && $key == $this->primary_key) {
			$this->object = ORM::factory($this->model, $value);
		}
		if (isset($this->fields[$key])) {
			$this->fields[$key]['value'] = $value;
			if ($this->model && in_array($key, array_keys($this->object->table_columns()))) {
				if (isset($this->fields[$key]['data_type']) && $this->fields[$key]['data_type'] == 'set') {
					$this->object->$key = implode(',', $value);
				} else {
					if ($value === '' && $this->fields[$key]['is_nullable']) $value = null;
					$this->object->$key = $value;
				}
			}
		}
	}
	
	/**
	 * Set values all at once
	 *
	 * @param array $values key/value pairs
	 * @return void
	 **/
	public function set_values($values) {
		foreach ($this->fields as $key => $field) {
			if (isset($values[$key])) {
				$this->set_value($key, $values[$key]);
			}
		}
	}
	
	/**
	 * Add a field to the form
	 *
	 * @param string $name Name of the field
	 * @param array $spec Field specification
	 * @param string $position start/end/before/after
	 * @param string $relative The field that $position is relative to (for before/after)
	 * @return void
	 **/
	public function add_field($name, $spec = array(), $position = 'end', $relative = null) {
		if (!isset($spec['name'])) $spec['name'] = $name;

		$insertion_point = count($this->fields);
		if ($position == 'start') {
			$insertion_point = 0;
		} else if ($position == 'before' && $relative) {
			$insertion_point = array_search($relative, array_keys($this->fields));
		} else if ($position == 'after' && $relative) {
			$insertion_point = array_search($relative, array_keys($this->fields))+1;
		}

		$before = array_slice($this->fields, 0, $insertion_point, true);
		$after  = array_slice($this->fields, $insertion_point, null, true);

		$this->fields = array_merge($before, array($name => $spec), $after);

	}
	
	/**
	 * Remove a field from the form
	 *
	 * @param string $name Field name
	 * @return void
	 **/
	public function remove_field($name) {
		if (isset($this->fields[$name])) {
			unset($this->fields[$name]);
		}
	}

	/**
	 * Remove a field from display in the form
	 * but don't stop it being written to the
	 * model. It'll need set elsewhere if it's
	 * not nullable.
	 *
	 * @param string $name Field name
	 */
	public function disable_field($name) {
		$this->set_field_value($name, 'disabled', true, true);
	}
	
	/**
	 * Move a field
	 *
	 * @param string $name Name of the field
	 * @param string $position start/end/before/after
	 * @param string $relative The field that $position is relative to (for before/after)
	 * @return void
	 **/
	public function move_field($name, $position = null, $relative = null) {
		if (isset($this->fields[$name])) {
			$field = $this->fields[$name];
			unset($this->fields[$name]);
			$this->add_field($name, $field, $position, $relative);
		}
	}

	/**
	 * Render the form
	 *
	 * @return string
	 **/
	public function render() {
        // ensure we have at least one button assigned, if none are assigned in the class
        if( empty($this->buttons) ) {
			if (!$this->submit_text) $this->submit_text = __('Submit');
            $this->add_button($this->submit_text, NULL, 'submit', array('class' => 'btn btn-primary'));
        }

        // set the defaults styling for the form, if it hasn't been defined
        if( empty($this->attributes) ) {
            $this->attributes = array(
                'method' => ! empty($this->method) ? $this->method : 'post',
                'enctype' => 'multipart/form-data',
                'class' => 'form form-horizontal'
            );
        }

        // set the action of the form, if it hasn't been defined already
        if( empty($this->action) ) {
            $this->action = Request::current()->url();
        }

		if (Kohana::find_file('views', $this->custom_view)) {
			$view = View::factory($this->custom_view);
		} else {
			$view = View::factory('formmanager/form');
		}

		$view->form = $this;
	
		foreach($view->form->fields as $key => $field) {
			if ($field['disabled']) {
				unset($view->form->fields[$key]);
			}
		}

		$this->process_fieldsets();

		return $view->render();
	}

    /**
     * This allows us to simply echo the form to the screen, much like you can a View
     *
     * @return string The rendered form
     */
    public function __toString() {
        return (string) $this->render();
    }

    /**
     * Add a button to the form.
     *
     * @param $label
     * @param null $name
     * @param string $type
     * @param array $attributes
     */
    public function add_button($label, $name = NULL, $type = 'submit', $attributes = array()) {
        if( is_null($name) ) {
            $name = preg_replace('/[^a-z]/', '', str_replace(' ', '_',strtolower($label)));
        }

        $attributes['type'] = $type;

        $this->buttons[] = array(
            'name' => "{$this->container}[{$name}]",
            'text' => $label,
            'attributes' => $attributes
        );
    }

	protected function process_fieldsets($remaining_field_names=null) {
		if (is_null($remaining_field_names)) $remaining_field_names = array_keys($this->fields);

		foreach ($this->fieldsets as &$fieldset) {
			foreach ($fieldset['fields'] as $i => &$field) {
				if (is_string($field) && isset($this->fields[$field])) {
					unset($remaining_field_names[array_search($field, $remaining_field_names)]);
					$field = $this->fields[$field];
				}
				if ($field['display_as'] == 'hidden') {
					unset($fieldset['fields'][$i]);
				}
			}
		}

		if ($remaining_field_names && $this->fieldsets) {
			$this->add_fieldset(__('Other'), $remaining_field_names);
			$this->process_fieldsets($remaining_field_names);
		}
		
		if (isset($this->fieldsets[count($this->fieldsets)-1]) && !$this->fieldsets[count($this->fieldsets)-1]['fields']) {
			unset($this->fieldsets[count($this->fieldsets)-1]);
		}

	}
	
	/**
	 * Validate the submitted values
	 *
	 * @param array $values Array of values
	 * @return bool
	 */
	public function submit() {
		
		$values = $this->get_input();
		if (!$values) {
			return false;
		}

		// If we leave in a blank primary key, then ORM will not set it.
		if (isset($values[$this->primary_key]) && $values[$this->primary_key] == '') {
			$this->remove_field($this->primary_key);
		}

		foreach($this->fields as $field) {
			if ($field['display_as'] == 'file') {
				$this->rule($field['column_name'], 'upload::valid');
			}
		}

		$this->set_values($values);
		// Validate
		$object_valid = true; // assume so, in case there isn't even an object
		if ($this->object) {
			$object_validation = $this->object->validation();
			$object_valid = $object_validation->check();
			if (!$object_valid) {
				$errors = $object_validation->errors('forms/' . strtolower(get_class($this)));
				foreach($errors as $key => $value) {
					$this->fields[$key]['error'] = true;
					$this->fields[$key]['error_text'] = $value;
				}
			}
		}
		
		// Local validation - to figure out.
		$local_validation = Validation::factory($values);
		foreach ($this->rules as $rule) {
			$local_validation->rule($rule['field'], $rule['rule'], $rule['params']);
		}
		$local_valid = $local_validation->check();
		if (!$local_valid) {
			$errors = $local_validation->errors('forms/' . strtolower(get_class($this)));
			foreach($errors as $key => $value) {
				$this->fields[$key]['error'] = true;
				$this->fields[$key]['error_text'] = $value;
			}
		}
		
		$this->submit_status = ($object_valid && $local_valid) ? self::SUBMIT_STATUS_SUCCESS : self::SUBMIT_STATUS_FAIL;
		
		// Return success or not. By default this is just if the form was valid.
		// Saving is left up to the child form class.
		return $object_valid && $local_valid;
		
	}

	/**
	 * Get the submit status
	 *
	 * @return bool
	 */
	public function submit_status() {
		return $this->submit_status;
	}

	/**
	 * Check to see if this form is submitted
	 *
	 * @return bool
	 */
	public function is_submitted() {
		return isset($this->expected_input[$this->container]);
	}

	/**
	 * Return the submitted data, or empty array if not submitted
	 *
	 * @return mixed
	 */
	public function get_input($field=null) {
		if ($this->is_submitted()) {
			$input = $this->expected_input[$this->container];
			if ($field) {
				return isset($input[$field]) ? $input[$field] : null;
			} else {
				foreach($this->fields as $key => $field) {
					if ($field['display_as'] == 'bool' && !isset($input[$key])) {
						$input[$key] = 0;
					}
				}
			}
			return $input;
		}
		return array();
	}

	/**
	 * Save the associated object, return the object on success
	 * 
	 * @return mixed
	 */
	public function save_object() {
		if (!$this->object) return false;

		foreach($this->fields as $field) {
			if ($field['display_as'] == 'file') {
				if (!$this->object->id) {
					$this->object->save();
				}
				$directory = $this->uploads . $this->object->id . '/' . $field['column_name'] . '/';
				if (!is_dir($directory)) mkdir($directory, 0755, true);
				
				$file = $this->unbork_file($field);
				$saved_path = Upload::save($file, preg_replace('/[^a-zA-Z0-9-.]/', '_', $file['name']), $directory);
				$saved_path = substr($saved_path, strlen(DOCROOT)-1);
				$saved_path = str_replace('\\', '/', $saved_path);
				$this->set_value($field['column_name'], $saved_path);
			}
		}

		$object = $this->object->save();
		
		foreach($this->fields as $field) {
			if ($field['relation'] == 'has_many') {
				$current_relations = $this->object->{$field['name']};
				foreach ($current_relations->find_all() as $relation) {
					$this->object->remove($field['name'], $relation->{$current_relations->primary_key()});
				}
				foreach($field['value'] as $value) {
					$this->object->add($field['name'], $value);
				}
			}
		}
		
	}

	protected function unbork_file($field) {
		return array(
			'name'     => $_FILES[$this->form_name]['name'][$field['column_name']],
			'type'     => $_FILES[$this->form_name]['type'][$field['column_name']],
			'tmp_name' => $_FILES[$this->form_name]['tmp_name'][$field['column_name']],
			'error'    => $_FILES[$this->form_name]['error'][$field['column_name']],
			'size'     => $_FILES[$this->form_name]['size'][$field['column_name']],
		);
	}


	/**
	 * Add parameters for display.
	 * If we have data in the comment, use that
	 *
	 * @param array $field
	 * @return array $field
	 */
	protected function configure_field($field) {
		$this->set_field_value($field, 'disabled', false);
		
		$this->set_field_value($field, 'relation', null);
		
		$this->set_field_value($field, 'name', $field);
		$this->set_field_value($field, 'field_name', $this->container . '[' . $this->fields[$field]['name'] . ']');
		$this->set_field_value($field, 'field_id', trim(str_replace(array('[', ']'), '_', $this->fields[$field]['field_name']), '_'));

		$this->set_field_value($field, 'value', '');

		#if (!isset($this->fields[$field]['label']) || !$this->fields[$field]['label']) $this->fields[$field]['label'] = ucwords(str_replace('_', ' ', $this->fields[$field]['name']));
		$this->set_field_value($field, 'label', ucwords(str_replace('_', ' ', $this->fields[$field]['name'])));

		$this->set_field_value($field, 'error', false);
		$this->set_field_value($field, 'error_text','');

		$attributes = array('id' => $this->fields[$field]['field_id']);

		if (isset($this->fields[$field]['comment'])) {
			$comment = explode("\n", $this->fields[$field]['comment']);
			foreach($comment as $line) {
				if (false !== strpos($line, ':')) {
					list($field, $value) = preg_split('/ *: */', $line, 2);
					$this->set_field_value($field, trim($field), trim($value));
				}
			}
		}

		$this->set_field_value($field, 'help', '');
		
		if (isset($this->fields[$field]['options'])) {
			if (isset($this->fields[$field]['dont_reindex_options'])) {
				if ($this->fields[$field]['is_nullable'] || ($this->is_new() && $this->fields[$field]['display_as'] == 'select')) $this->fields[$field]['options'] = array('' => '') + $this->fields[$field]['options'];
			} else {
				$options = array();
				if ($this->fields[$field]['is_nullable']) $options[''] = '';
				foreach ($this->fields[$field]['options'] as $option) {
						$i18id = "option_name_".$option;
						$i18name = I18n::get($i18id);
						if ($i18name == "" || $i18name == $i18id) {
							$options[$option] = $option;
						}
						else {
							$options[$option] = $i18name;
						}
				}
				$this->fields[$field]['options'] = $options;
			}
		}

		if ($this->fields[$field]['name'] == $this->primary_key) {
			$this->set_field_value($field, 'display_as', 'hidden');
		}

		elseif (isset($this->fields[$field]['data_type']) && $this->fields[$field]['data_type'] == 'enum') {
			$this->set_field_value($field, 'display_as', 'select');
		}

		elseif (isset($this->fields[$field]['data_type']) && $this->fields[$field]['data_type'] == 'set') {
			$this->set_field_value($field, 'display_as', 'checkboxes');
			$this->fields[$field]['value'] = explode(',', $this->fields[$field]['value']);
		}

		elseif (isset($this->fields[$field]['data_type']) && $this->fields[$field]['data_type'] == 'tinyint' && $this->fields[$field]['display'] == '1') {
			$this->set_field_value($field, 'display_as', 'bool');
		}

		elseif (isset($this->fields[$field]['type']) && preg_match('/^(.*int|decimal|float)$/', $this->fields[$field]['type'])) {
			$this->set_field_value($field, 'display_as', 'text');
			$this->set_field_value($field, 'input_type', 'number');
		}

		elseif (isset($this->fields[$field]['data_type']) && preg_match('/.*text$/', $this->fields[$field]['data_type'])) {
			$this->set_field_value($field, 'display_as', 'textarea');
		}
		
		else {
			$this->set_field_value($field, 'display_as', 'text');
			$this->set_field_value($field, 'input_type', 'text');
			if (isset($this->fields[$field]['character_maximum_length'])) {
				$attributes['maxlength'] = $this->fields[$field]['character_maximum_length'];
			}
		}

		$required = isset($this->fields[$field]['is_nullable']) && !$this->fields[$field]['is_nullable'];
		$this->set_field_value($field, 'required', $required);
		# $attributes['required'] = $required; // this triggers browser behaviour, which doesn't seem quite ready yet.

		$this->set_field_value($field, 'attributes', $attributes);



	}

	/**
	 * Set a value into the $field array, not overwriting a previous value by default.
	 *
	 * @param array $field
	 * @param string $key
	 * @param mixed $value
	 * @param bool $override
	 */
	protected function set_field_value($field, $key, $value, $override=false) {
		if (!isset($this->fields[$field])) return false;
		if (!isset($this->fields[$field][$key]) or $override) $this->fields[$field][$key] = $value;
	}
	
	public function is_new() {
		return $this->object && !$this->object->id;
	}



	
	
}