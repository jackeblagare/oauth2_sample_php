<?php

namespace app\modules\v1\models;

use yii\web\BadRequestHttpException;
use app\models\Facility;

/**
 * Contains methods in validating a json
 *
 * @author ncarumba
 */
class JsonValidator {

    public function get_json_last_error() {
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                return 'No errors.';
                break;
            case JSON_ERROR_DEPTH:
                return 'Maximum stack depth exceeded.';
                break;
            case JSON_ERROR_STATE_MISMATCH:
                return 'Underflow or the modes mismatch.';
                break;
            case JSON_ERROR_CTRL_CHAR:
                return 'Unexpected control character found.';
                break;
            case JSON_ERROR_SYNTAX:
                return 'Syntax error, malformed JSON.';
                break;
            case JSON_ERROR_UTF8:
                return 'Malformed UTF-8 characters, possibly incorrectly encoded.';
                break;
            default:
                return 'Unknown error.';
                break;
        }
    }

    function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    /**
     * Checks if a valid json based on the format of a value when creating a task record.
     * 
     * @param json $data data to be validated
     * @return array array that contains the status=1, if the array is valid, status=0 if invalid and corresponding error message
     */
    public function validateJson($data) {
        $mandatory = array('variable_id', 'value');
        $missing = array();
        $nullValues = array();

        $errors = '';
        $data_arr = json_decode($data, TRUE);
        $count_index = 0;
        foreach ($data_arr as $index => $row) {
            foreach ($mandatory as $key) {
                if (array_key_exists($key, $row)) { // checks of the mandatory keys are in the data
                    if ($row[$key] == null) {
                        array_push($nullValues, "'" . $key . "' of variable with id " . $row["variable_id"]);  // checks if the key has value
                    }
                } else {
                    array_push($missing, $key);
                }
            }

            if (count($missing) > 0) {
                $errors.='Error in index ' . $index . '. Missing key(s): ' . implode(',', $missing) . ' <br> ';
            }

            if (count($nullValues) > 0) {
                $errors.='Error in index ' . $index . '. ' . implode(',', $nullValues) . ' should not be null. <br> ';
            }

            if ($errors === '') {
                if (!is_array($row['value'])) {
                    if (!is_numeric($row['value'])) {
                        if (JsonValidator::isJson($row['value'])) {

                            $data = json_decode($row['value'], TRUE);
                            if ($this->is_assoc($data)) {
                                throw new BadRequestHttpException("Value of variable with id " . $row["variable_id"] . " is not a sequential array.");
                            }
                            if ($index === 0) {
                                $count_index = count($data);
                            } else {
                                if ($count_index != count($data)) {
                                    throw new BadRequestHttpException("The number of values per variables should be equal.");
                                }
                            }
                        } else {
                            throw new BadRequestHttpException("Value of variable with id " . $row["variable_id"] . " is not a valid JSON. ERRORS: " . JsonValidator::get_json_last_error());
                        }
                    } else {
                        throw new BadRequestHttpException("Value of variable with id " . $row["variable_id"] . " is not a valid JSON. ERRORS: 'value' is an integer.");
                    }
                } else {
                    throw new BadRequestHttpException("Value of variable with id " . $row["variable_id"] . " is not a valid JSON. ERRORS: 'value' is an array.");
                }
            }
        }
        if ($errors !== '') {
            throw new BadRequestHttpException($errors);
        }
    }

    /**
     * Checks if in a valid field Loc format.
     * 
     * @param type $data data to be evaluated
     * @param type $variable_id ID of the variable
     * @throws BadRequestHttpException when there is a missing or empty key
     */
    public function validateFieldLoc($data, $variable_id, $transaction_db) {

        if (!isset($data['fields'])) { // checks the mandatory keys are in the data
            $transaction_db->rollBack();
            throw new BadRequestHttpException("Value for variable with id " . $variable_id . " is invalid. Key 'fields' is missing. ");
        } else {
            if (empty($data['fields'])) {
                $transaction_db->rollBack();
                throw new BadRequestHttpException("Value for variable with id " . $variable_id . " is invalid. Key 'fields' is empty.");
            }

            if (!isset($data['display_name'])) { // checks the mandatory keys are in the data
                $transaction_db->rollBack();
                throw new BadRequestHttpException("Value for variable with id " . $variable_id . " should have key 'display_name' ");
            } else {
                if (empty($data['display_name'])) {
                    $transaction_db->rollBack();
                    throw new BadRequestHttpException("Value for variable with id " . $variable_id . " is invalid. Key 'display_name' is empty.");
                }
            }

            $fieldNames = explode(',', $data['display_name']);
            if (count($fieldNames) !== count(array_unique($fieldNames))) {
                $transaction_db->rollBack();
                throw new BadRequestHttpException("Value for variable with id " . $variable_id . " is invalid. There are duplicates in the display_name.");
            }
            $no_dupe = array();

            foreach ($data['fields'] as $field) {
                if (isset($no_dupe['facility_id']) && in_array($field['facility_id'], $no_dupe)) {
                    if (isset($no_dupe['current_name']) && in_array($field['current_name'], $no_dupe)) {
                        if (isset($no_dupe['previous_name']) && in_array($field['previous_name'], $no_dupe)) {
                            $transaction_db->rollBack();
                            throw new BadRequestHttpException("Value for variable with id " . $variable_id . " is invalid. There are duplicates in the fields.");
                        }
                    }
                } else {
                    $no_dupe = array('facility_id' => $field['facility_id'],
                        'current_name' => $field['current_name'],
                        'previous_name' => $field['previous_name']);
                }
            }
            if (count($fieldNames) != count($data['fields'])) {
                $transaction_db->rollBack();
                throw new BadRequestHttpException("Value for variable with id " . $variable_id . " is invalid. The number of 'display_name' does not match the number of fields. A display_name should correspond to a field.");
            }

            foreach ($data['fields'] as $field) {
                if (!isset($field['facility_id'])) {
                    $transaction_db->rollBack();
                    throw new BadRequestHttpException("Value for variable with id " . $variable_id . " is invalid. Key 'facility_id' in 'fields' is missing.");
                } else {
                    if (empty($field['facility_id'])) {
                        $transaction_db->rollBack();
                        throw new BadRequestHttpException("Value for variable with id " . $variable_id . " is invalid.  Key 'facility_id' in 'fields' is empty.");
                    }
                }

                if (!isset($field['current_name'])) {
                    $transaction_db->rollBack();
                    throw new BadRequestHttpException("Value for variable with id " . $variable_id . " is invalid. Key 'current_name' in 'fields' is missing.");
                } else {
                    if (empty($field['current_name'])) {
                        $transaction_db->rollBack();
                        throw new BadRequestHttpException("Value for variable with id " . $variable_id . " is invalid.  Key 'current_name' in 'fields' is empty.");
                    }
                }
                if (!array_key_exists('previous_name', $field) ) {
                    $transaction_db->rollBack();
                    throw new BadRequestHttpException("Value for variable with id " . $variable_id . " is invalid. Key 'previous_name' in 'fields' is empty.");
                }
//                else {
//                    if (empty($field['previous_name'])) {
//                        $transaction_db->rollBack();
//                        throw new BadRequestHttpException("Value for variable with id " . $variable_id . " is invalid.  Key 'previous_name' in 'fields' is empty.");
//                    }
//                }

                $facObj = Facility::findOne($field['facility_id']);

                if (empty($facObj) || is_null($facObj)) {
                    $transaction_db->rollBack();
                    throw new BadRequestHttpException("Value for variable with id " . $variable_id . " is invalid.  Object of the facility_id is not found .");
                } else {
                    if (!in_array($facObj->name, $fieldNames)) {
                        $transaction_db->rollBack();
                        throw new BadRequestHttpException("Value for variable with id " . $variable_id . " is invalid.  The object found given the facility_id " . $field['facility_id'] . " does not correspond to a name in the display_name.");
                    }
                    if ($facObj->name !== $field['current_name']) {
                        $transaction_db->rollBack();
                        throw new BadRequestHttpException("Value for variable with id " . $variable_id . " is invalid.  The object found given the facility_id " . $field['facility_id'] . " does not correspond to the 'current_name'.");
                    }
                    if ($facObj->other_name !== $field['previous_name']) {
                        $transaction_db->rollBack();
                        throw new BadRequestHttpException("Value for variable with id " . $variable_id . " is invalid.  The object found given the facility_id " . $field['facility_id'] . " does not correspond to the 'previous_name'.");
                    }
                }
            }
        }
    }

}
