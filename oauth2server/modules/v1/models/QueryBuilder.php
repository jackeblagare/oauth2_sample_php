<?php

namespace app\modules\v1\models;

/**
 * functions to select or retrieve objects using model.
 *
 * @author ncarumba
 */
class QueryBuilder {

    /**
     * validates if the data type of the attribute is integer
     * 
     * @param string $column name of the attribute or colum
     * @param string $value value of the attribute
     * @param object $model object of the model class
     * @return object if the value is numeric, return the filtered object, else, return the model
     */
    public function validateInteger($column, $value, $model) {

        $value_arr = explode(',', $value);

        $all_integer = true;
        foreach ($value_arr as $i => $val) {

            if (!is_numeric($val)) {
                $all_integer = false;
                break;
            }
        }
        if ($all_integer) {
            $model->andWhere(['IN', $column, $value_arr]);
        }

        return $model;
    }
    
    /**
     * validates if the data type of the attribute is double precision
     * 
     * @param string $column name of the attribute or colum
     * @param string $value value of the attribute
     * @param object $model object of the model class
     * @return object if the value is double precision, return the filtered object, else, return the model
     */
    public function validateDouble($column, $value, $model) {

        $value_arr = explode(',', $value);

        $all_double = true;
        foreach ($value_arr as $i => $val) {

            if (!is_double($val)) {
                $all_double = false;
                break;
            }
        }
        if ($all_double) {
            $model->andWhere(['IN', $column, $value_arr]);
        }

        return $model;
    }
    

    /**
     * validates if the data type of the attribute is boolean
     * 
     * @param string $column name of the attribute or colum
     * @param string $value value of the attribute
     * @param object $model object of the model class
     * @return object if the value is numeric, return the filtered object, else, return the model
     */
    public function validateBoolean($column, $value, $model) {

        if (is_bool($value) || $value === "true" || $value === "false") {
            $model->andWhere([$column, $value]);
        }

        return $model;
    }

    /**
     * validates if the data type of the attribute is a string
     * 
     * @param string $column name of the attribute or colum
     * @param string $value value of the attribute
     * @param object $model object of the model class
     * @param boolean $caseSensitive whether the search should be case sensitive or not
     * @return object if the value is numeric, return the filtered object, else, return the model
     */
    public function validateString($column, $value, $model,$caseSensitive = TRUE) {

        $value_arr = explode(',', $value);
        $strCond = '';
        foreach ($value_arr as $i => $val) {

            $strCond = ($i == 0) ? $strCond . ' ' : (($strCond != ' ') ? $strCond . ' OR ' : $strCond . ' ');
            
            if($caseSensitive){
                $strCond = $strCond . $column . " = '" . trim($val) . "'";
            }
            else{
                $strCond = $strCond . $column . " ILIKE '" . trim($val) . "'";
            }
        }
        $model->andWhere($strCond);

        return $model;
    }

}
