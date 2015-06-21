<?php

namespace app\modules\v1\models;

use yii\web\BadRequestHttpException;
use Yii;

/**
 * Contains methods in validating a value, in creating or updating task records
 *
 * @author Nikki G. Carumba <n.carumba@irri.org>
 */
class ValueValidator {

    /**
     * Checks if the value of a variable is valid.
     * 
     * Checks if it is valid based on data types integer,float, double precision, date, or character varying
     * @param type $value value of the variable
     * @param type $data_type data type pf a variable
     * @return boolean true, if the value is valid, else, invalid.
     */
    public function validateDataType($decodedValue, $data_type, $transaction_db, $variable_id) {
        foreach ($decodedValue as $index => $value) {
            if ($data_type === 'integer') {
                if (!is_integer($value)) {
                    $transaction_db->rollBack();
                    throw new BadRequestHttpException("The value is not valid. The value has an invalid data type. Values of variable with id of " . $variable_id . " should be in integer");
                }
            } else if ($data_type === 'float' || $data_type === 'double precision') {
                if (!is_double($value)) {
                    $transaction_db->rollBack();
                    throw new BadRequestHttpException("The value is not valid. The value has an invalid data type. Values of variable with id of " . $variable_id . " should be in " . $data_type);
                }
            } else if ($data_type === 'date') {
                $d = \DateTime::createFromFormat('Y-m-d', $value);

                if (!($d && $d->format('Y-m-d') === $value)) {
                    $transaction_db->rollBack();
                    throw new BadRequestHttpException("The value is not valid. The value has an invalid data type. Values of variable with id of " . $variable_id . " should be in " . $data_type . "with format yyyy-mm-dd.");
                }
            } else if ($data_type === 'json' && $variable_id == 1245) { //field location
                JsonValidator::validateFieldLoc($value, $variable_id, $transaction_db);
            } else if($data_type !== 'character varying' && $data_type !== 'text'){
                $transaction_db->rollBack();
                throw new BadRequestHttpException("The value is not valid. The data type of the variable with id " . $variable_id . " is not yet supported by the application.");
            }
        }
    }

    /**
     * Checks if the value is among the scale values.
     * 
     * Checks if it is valid value based on the scale values
     * @param type $scale_id ID of the scale from the variable object
     * @param type $variable_id ID of the  variable 
     * @param type $transaction_db transaction initiated
     * @return boolean true, if the value is valid, else, invalid.
     */
    public function validateScaleValue($decodedValue, $scale_id, $variable_id, $transaction_db) {

        if ($scale_id == null) {
            return true;
        }
        $connection = Yii::$app->db;
        $comm = $connection->createCommand("select exists( select 1 from master.scale_value where scale_id=" . $scale_id . " and is_void=false)");
        $result = $comm->queryAll();
        if ($result[0]['exists'] === 'true' || $result[0]['exists']) {

            foreach ($decodedValue as $value) {
                $comm = $connection->createCommand("select '" . $value . "' IN (select value from master.scale_value where scale_id=" . $scale_id . " and is_void=false) as exists");
                $result = $comm->queryAll();
                if (!$result[0]['exists']) {
                    $transaction_db->rollBack();
                    throw new BadRequestHttpException("The value is not valid. Value of the variable " . $variable_id . "is not in the range of the scale values.");
                }
            }
        }
    }

}
