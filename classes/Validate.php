<?php

/**
 * Created to validate user input
 */

class Validate
{
    private $_passed = false;
    private $_errors = array();
    private $_db = null;

    public function __construct()
    {
        $this->_db = DB::getInstance();
    }

    public function check($source, $items = array())
    {
        foreach ($items as $item => $rules) {
            foreach ($rules as $rule => $rule_value) { //iterate through each rule and its value
                $value = $source[$item]; // get value of item from source e.g value of $_POST['name']
                $item = escape($item); //ensure that item is a string

                if ($rule === 'required' && empty($value)) {
                    $this->addError("{$item} is required"); // item is required yet it is empty add 'is required error to error array
                } else if ($rule === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {

                    $this->addError("{$item} is not valid");
                } else if ($rule == 'file') {
                    $img_name = $value['name'];
                    $img_type = $value['type'];

                    $img_explode = explode('.', $img_name);
                    $img_ext = end($img_explode);

                    $extensions = ["jpeg", "png", "jpg"];
                    if (in_array($img_ext, $extensions) === true) {
                        $types = ["image/jpeg", "image/jpg", "image/png"];
                        if (in_array($img_type, $types) === false) {
                            $this->addError("file must be image of type - jpeg, png, jpg");
                        }
                    } else {
                        $this->addError("file must be an with extentions - jpeg, png, jpg");
                    }
                } else if (!empty($value)) { // if item is not empty check for other rules
                    switch ($rule) {
                        case 'min':
                            if (strlen($value) < $rule_value) { // if item is less than its minimum number of characters add an error
                                $this->addError("{$item} must be a minimum of {$rule_value} characters.");
                            }
                            break;

                        case 'max':
                            if (strlen($value) > $rule_value) { // if item is greater than its maximum number of characters add an error
                                $this->addError("{$item} must be a maximum of {$rule_value} characters.");
                            }
                            break;
                        case 'matches':
                            if ($value != $source[$rule_value]) { // if item doesnot it required vakue add an error
                                $this->addError("{$rule_value} must match {$item}.");
                            }
                            break;
                        case 'unique': // chack in the database if a similar value exists
                            $check = $this->_db->get($rule_value, array($item, '=', $value));

                            if ($check->count()) { // count results of db check, if > 1 add an error
                                $this->addError("{$item} already exists.");
                            }
                            break;
                    }
                }
            }
        }

        if (empty($this->_errors)) { //if errors array is empty then thed return passed
            $this->_passed = true;
        }
    }

    private function addError($error)
    { // add an error to array
        $this->_errors[] = $error;
    }

    public function errors()
    { //return errors
        return $this->_errors;
    }

    public function passed()
    { //return passed
        return $this->_passed;
    }


}
