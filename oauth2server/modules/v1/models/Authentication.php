<?php

namespace app\modules\v1\models;

use Yii;

/**
 * Methods to retrieve user related information given the accessToken
 *
 * @author ncarumba
 */
class Authentication {

    /**
     * Adds authentication and returns the programs the user has access
     * Authenticates the resource and get the programs the user has an access
     * @return string $program query to filter data only with the given program_id
     */
    public function getPrograms() {
        $teams = \app\models\User::getTeamsByUserId(Yii::$app->user->getId());
        $prog = \app\models\User::getProgramByTeamId($teams);
        $user = Yii::$app->user->getIdentity();

        $program = '';
        foreach ($prog as $prog_row) {
            if ($prog_row['id'] !== 6) {
                $program.=' study.program_id=' . $prog_row['program_id'] . ' or';
            }
        }
        $program = substr($program, 0, -2);

        return $program;
    }

    /**
     * Adds authentication and returns the programs the user has access
     * Authenticates the resource and get the programs the user has an access
     * @return array $program query to filter data only with the given program_id
     */
    public function getProgramsArr() {
        $teams = \app\models\User::getTeamsByUserId(Yii::$app->user->getId());
        $prog = \app\models\User::getProgramByTeamId($teams);

        $program = array();
        foreach ($prog as $prog_row) {
            if ($prog_row['id'] !== 6) {
                $program[] = $prog_row['program_id'];
            }
        }

        return $program;
    }
/**
 * Retrieve id of the team that the user belongs to
 * @return array array that contains the id of the teams
 */
    public function getTeams() {
        $teams = \app\models\User::getTeamsByUserId(Yii::$app->user->getId());

        $teamsArr = array();
        foreach ($teams as $team_row) {
            if ($team_row['team_id'] !== 6 && $team_row['team_id'] !== 42)
                $teamsArr[] = $team_row['team_id'];
        }

        return $teamsArr;
    }

}
