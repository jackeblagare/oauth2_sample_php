<?php

namespace app\modules\v1\models;

use yii\db\Expression;
use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;
use yii\web\ServerErrorHttpException;
use Yii;

/**
 * functions to select or retrieve objects using model.
 *
 * @author ncarumba
 */
class StudyMetadata {

    public function updateValue($data, $transaction) {

        extract($data);
        $model_meta = \app\models\StudyMetadata::findOne($study_id);

        $model_meta->setAttribute('value', $value);
        $model_meta->setAttribute('modifier_id', $modifier_id);
        $model_meta->setAttribute('modification_timestamp', $modification_timestamp);
        $model_meta->setAttribute('notes', $notes);
        $model_meta->setAttribute('record_id', $record_id);
        if ($model_meta->save()) {
            return true;
        } else {

            $transaction->rollBack();
            $errors = '';
            foreach ($model_meta->getErrors() as $index => $error) {
                $errors.= "<br>" . $error[0];
            }
            throw new ServerErrorHttpException('Failed to create task.' . $errors);
        }
    }

    public function insert($data, $transaction_db) {

        extract($data);
        $model_meta = new \app\models\StudyMetadata();
        $model_meta->setAttribute('study_id', $id);
        $model_meta->setAttribute('variable_id', $variable_id);
        $model_meta->setAttribute('value', $value);
        $model_meta->setAttribute('creator_id', $creator_id);
        $model_meta->setAttribute('creation_timestamp', $creation_timestamp);
        $model_meta->setAttribute('notes', $notes);
        $model_meta->setAttribute('record_id', $record_id);
        if ($model_meta->save()) {
            return $model_meta;
        } else {
            $transaction_db->rollBack();
            $errors = '';
            foreach ($model_meta->getErrors() as $index => $error) {
                $errors.= "<br>" . $error[0];
            }
            throw new ServerErrorHttpException('Failed to create task.' . $errors);
        }
    }

    public function updateAudit($data, $transaction_db) {
        $user = Yii::$app->user->getIdentity();
        extract($data);

        $model_creator = \app\models\StudyMetadata::findOne($study_meta_creator_id);
        $model_creator->setAttribute('study_id', $study_id);
        $model_creator->setAttribute('variable_id', 1243);
        $model_creator->setAttribute('value', $creator);
        $model_creator->setAttribute('creator_id', $user->id);
        $model_creator->setAttribute('creation_timestamp', new Expression('NOW()'));
        $model_creator->setAttribute('notes', 'added by client');
        $model_creator->setAttribute('record_id', $record_id);

        if ($model_creator->save()) {
            $model_timestamp = \app\models\StudyMetadata::findOne($study_meta_timestamp_id);
            $model_timestamp->setAttribute('study_id', $study_id);
            $model_timestamp->setAttribute('variable_id', 1242);
            $model_timestamp->setAttribute('value', $timestamp);
            $model_timestamp->setAttribute('creator_id', $user->id);
            $model_timestamp->setAttribute('creation_timestamp', new Expression('NOW()'));
            $model_timestamp->setAttribute('notes', 'added by client');
            $model_timestamp->setAttribute('record_id', $record_id);
            if ($model_timestamp->save()) {
                
            }
        } else {
            $transaction_db->rollBack();
            $errors = '';
            foreach ($model_meta->getErrors() as $index => $error) {
                $errors.= "<br>" . $error[0];
            }
            throw new ServerErrorHttpException('Failed to create task.' . $errors);
        }
    }

    public function insertAudit($data, $transaction_db) {
        $user = Yii::$app->user->getIdentity();
        extract($data);

        $model_creator = new \app\models\StudyMetadata();
        $model_creator->setAttribute('study_id', $study_id);
        $model_creator->setAttribute('variable_id', 1243);
        $model_creator->setAttribute('value', $creator);
        $model_creator->setAttribute('creator_id', $user->id);
        $model_creator->setAttribute('creation_timestamp', new Expression('NOW()'));
        $model_creator->setAttribute('notes', 'added by client');
        $model_creator->setAttribute('record_id', $record_id);

        if ($model_creator->save()) {
            $model_timestamp = new \app\models\StudyMetadata();
            $model_timestamp->setAttribute('study_id', $study_id);
            $model_timestamp->setAttribute('variable_id', 1242);
            $model_timestamp->setAttribute('value', $timestamp);
            $model_timestamp->setAttribute('creator_id', $user->id);
            $model_timestamp->setAttribute('creation_timestamp', new Expression('NOW()'));
            $model_timestamp->setAttribute('notes', 'added by client');
            $model_timestamp->setAttribute('record_id', $record_id);
            if ($model_timestamp->save()) {
                
            }
        } else {
            $transaction_db->rollBack();
            $errors = '';
            foreach ($model_meta->getErrors() as $index => $error) {
                $errors.= "<br>" . $error[0];
            }
            throw new ServerErrorHttpException('Failed to create task.' . $errors);
        }
    }

    public function insertTaskRecord($transaction_db, $var, $id, $row, $models_record) {
        $user = Yii::$app->user->getIdentity();

        $insertAudit = false;
        $meta = \app\models\StudyMetadata::find()->where('study_id=' . $id . " and variable_id=" . $row['variable_id'] . " and record_id=" . $models_record[0]['record_id'] . ' and is_void =false')->all();
        //$rec_var = \app\models\RecordVariable::find()->where('record_id=' . $models_record[0]['record_id'] . ' and variable_id=' . $row['variable_id'])->all();
//        $row['value'] = json_decode($row['value'], TRUE);
//        $row['value'] = json_encode($row['value'], JSON_FORCE_OBJECT);
        if (($var['is_mandatory'] === true || $var['is_mandatory'] === "true" || $var['is_mandatory'] === 1) && !empty($row["value"])) {

            if (!empty($meta)) {    // if there are existing values, append the new values
                $oldValue = $meta[0]['value'];
                $oldValue_arr = json_decode($oldValue, TRUE);
                $merge = array();

                if ($row['variable_id'] === 1245 || $row['variable_id'] === "1245") {
                    $new = json_decode($row['value'], TRUE);
                    foreach ($new as $val) {
                        $oldValue_arr[] = json_encode($val, JSON_FORCE_OBJECT);
                    }
                    $merge = $oldValue_arr;
                } else {
                    $merge = array_merge($oldValue_arr, json_decode($row['value'], TRUE));
                }

                \app\modules\v1\models\StudyMetadata::updateValue(array(
                    "study_id" => $meta[0]['id'],
                    'value' => json_encode($merge, JSON_FORCE_OBJECT),
                    'modifier_id' => $user->id,
                    'modification_timestamp' => new Expression('NOW()'),
                    'notes' => $meta[0]['notes'] . ", modified by client ",
                    'record_id' => $models_record[0]['record_id']
                        ), $transaction_db);
                $insertAudit = true;
            } else {    // no existing values
                if ($row['variable_id'] === 1245 || $row['variable_id'] === "1245") {
                    $row['value'] = json_decode($row['value']);

                    foreach ($row['value'] as $val) {
                        $oldValue_arr[] = json_encode(array("display_name" => $val->display_name, "fields" => array("current_name" =>
                                $val->fields[0]->current_name, "previous_name" => $val->fields[0]->previous_name, "facility_id" => $val->fields[0]->facility_id)), JSON_FORCE_OBJECT);
                    }
                    $row['value'] = $oldValue_arr;
                } else {
                    $row['value'] = json_decode($row['value'], TRUE);
                    // print_r();
                }
                \app\modules\v1\models\StudyMetadata::insert(array(
                    'id' => $id,
                    'variable_id' => $row['variable_id'],
                    'value' => json_encode($row['value'], JSON_FORCE_OBJECT),
                    'creator_id' => $user->id,
                    'creation_timestamp' => new Expression('NOW()'),
                    'notes' => "Added by client",
                    'record_id' => $models_record[0]['record_id']
                        ), $transaction_db);
                $insertAudit = true;
            }
        } else if (($var['is_mandatory'] === true || $var['is_mandatory'] === "true" || $var['is_mandatory'] === 1) && empty($row["value"])) {
            $transaction_db->rollBack();
            throw new BadRequestHttpException('Value of a mandatory variable should not be empty.');
        } else {
            if (!empty($meta)) {    // if there are existing values, append the new values
                $oldValue = $meta[0]['value'];
                $oldValue_arr = json_decode($oldValue, TRUE);
                $merge = array();
                if ($row['variable_id'] === 1245 || $row['variable_id'] === "1245") {
                    $new = json_decode($row['value']);
                    foreach ($new as $val) {
                        $oldValue_arr[] = json_encode($val, JSON_FORCE_OBJECT);
                    }
                    $merge = $oldValue_arr;
                } else {
                    $merge = array_merge($oldValue_arr, json_decode($row['value'], TRUE));
                }


                \app\modules\v1\models\StudyMetadata::updateValue(array(
                    "study_id" => $meta[0]['id'],
                    'value' => json_encode($merge, JSON_FORCE_OBJECT),
                    'modifier_id' => $user->id,
                    'modification_timestamp' => new Expression('NOW()'),
                    'notes' => $meta[0]['notes'] . ", modified by client ",
                    'record_id' => $models_record[0]['record_id']
                        ), $transaction_db);
            } else {    // no existing values
                if ($row['variable_id'] === 1245 || $row['variable_id'] === "1245") {
                    $row['value'] = json_encode($row['value'], JSON_FORCE_OBJECT);
                }
                \app\modules\v1\models\StudyMetadata::insert(array(
                    'id' => $id,
                    'variable_id' => $row['variable_id'],
                    'value' => $row['value'],
                    'creator_id' => $user->id,
                    'creation_timestamp' => new Expression('NOW()'),
                    'notes' => "Added by client",
                    'record_id' => $models_record[0]['record_id']
                        ), $transaction_db);
                $insertAudit = true;
            }
        }
        return $insertAudit;
    }

}
