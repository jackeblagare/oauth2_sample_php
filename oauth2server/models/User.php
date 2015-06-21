<?php

namespace app\models;

use app\modules\v1\models\QueryBuilder;
use yii\helpers\Url;

/**
 * This is the model class for table "master.user".
 *
 * @property integer $id
 * @property string $email
 * @property string $username
 * @property integer $user_type
 * @property integer $status
 * @property string $last_name
 * @property string $first_name
 * @property string $middle_name
 * @property string $display_name
 * @property string $salutation
 * @property string $valid_start_date
 * @property string $valid_end_date
 * @property string $remarks
 * @property string $creation_timestamp
 * @property integer $creator_id
 * @property string $modification_timestamp
 * @property integer $modifier_id
 * @property string $notes
 * @property boolean $is_void
 *
 * @property ProductGid[] $productGs
 * @property Program[] $programs
 * @property Product[] $products
 * @property CrossMethod[] $crossMethods
 * @property Crosscutting[] $crosscuttings
 * @property Changelog[] $changelogs
 * @property Entity[] $entities
 * @property FacilityMetadata[] $facilityMetadatas
 * @property GeolocationData[] $geolocationDatas
 * @property Formula[] $formulas
 * @property FormulaParameter[] $formulaParameters
 * @property Instruction[] $instructions
 * @property Geolocation[] $geolocations
 * @property GeolocationMetadata[] $geolocationMetadatas
 * @property Institute[] $institutes
 * @property ItemAction[] $itemActions
 * @property Method[] $methods
 * @property ItemRecord[] $itemRecords
 * @property ItemRelation[] $itemRelations
 * @property Place[] $places
 * @property Phase[] $phases
 * @property Pipeline[] $pipelines
 * @property PlaceMetadata[] $placeMetadatas
 * @property ProductName[] $productNames
 * @property ProductList[] $productLists
 * @property ProductMetadata[] $productMetadatas
 * @property PlaceSeason[] $placeSeasons
 * @property Scale[] $scales
 * @property Property[] $properties
 * @property RecordVariable[] $recordVariables
 * @property Scheme[] $schemes
 * @property Role[] $roles
 * @property ProgramTeam[] $programTeams
 * @property UserRole[] $userRoles
 * @property TeamMember[] $teamMembers
 * @property UserItem[] $userItems
 * @property UserMetadata[] $userMetadatas
 * @property UserSession[] $userSessions
 * @property Tooltip[] $tooltips
 * @property User $modifier
 * @property User[] $users
 * @property User $creator
 * @property UserVariableSetMember[] $userVariableSetMembers
 * @property VariableSetMember[] $variableSetMembers
 * @property VariableResult[] $variableResults
 * @property VariableSetRelation[] $variableSetRelations
 * @property AnalysisTransaction[] $analysisTransactions
 * @property CrosscuttingTeam[] $crosscuttingTeams
 * @property Item[] $items
 * @property Variable[] $variables
 * @property Facility[] $facilities
 * @property ScaleValue[] $scaleValues
 * @property VariableSet[] $variableSets
 * @property PipelineTeam[] $pipelineTeams
 * @property FacilityData[] $facilityDatas
 * @property PlaceData[] $placeDatas
 * @property ProductListMember[] $productListMembers
 * @property Record[] $records
 * @property Team[] $teams
 * @property Season[] $seasons
 * @property PropertyMethodScale[] $propertyMethodScales
 * @property Family[] $families
 */
class User extends BaseModel implements \yii\web\IdentityInterface {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'master.user';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['email', 'username', 'user_type', 'last_name', 'first_name', 'display_name'], 'required'],
            [['user_type', 'status', 'creator_id', 'modifier_id'], 'integer'],
            [['valid_start_date', 'valid_end_date', 'creation_timestamp', 'modification_timestamp'], 'safe'],
            [['remarks', 'notes'], 'string'],
            [['is_void'], 'boolean'],
            [['email', 'username', 'display_name'], 'string', 'max' => 64],
            [['last_name', 'first_name', 'middle_name'], 'string', 'max' => 32],
            [['salutation'], 'string', 'max' => 16],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['username'], 'unique'],
            [['email'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'username' => 'Username',
            'user_type' => 'User Type',
            'status' => 'Status',
            'last_name' => 'Last Name',
            'first_name' => 'First Name',
            'middle_name' => 'Middle Name',
            'display_name' => 'Display Name',
            'salutation' => 'Salutation',
            'valid_start_date' => 'Valid Start Date',
            'valid_end_date' => 'Valid End Date',
            'remarks' => 'Remarks',
            'creation_timestamp' => 'Creation Timestamp',
            'creator_id' => 'Creator ID',
            'modification_timestamp' => 'Modification Timestamp',
            'modifier_id' => 'Modifier ID',
            'notes' => 'Notes',
            'is_void' => 'Is Void',
        ];
    }

    /**
     * Set values for the fields or attributes to be returned
     * @return array array of the attributes to be returned
     */
    public function fields() {
        return [
            'id',
            'email',
            'username',
            //  'user_type',
//            'status',
            'last_name',
            'first_name',
            'middle_name',
            'display_name',
            'salutation',
//            'valid_start_date',
//            'valid_end_date',
            'remarks',
//            'creation_timestamp',
//            'creator_id' => function ($model) {
//        return array('value' => $model->creator_id, 'href' => Url::to(['users/' . $model->creator_id], true));
//    },
//            'modification_timestamp',
//            'modifier_id' => function ($model) {
//
//        return array('value' => $model->modifier_id, 'href' => empty($model->modifier_id) ? null : Url::to(['users/' . $model->modifier_id], true));
//    },
//            'notes',
//            'is_void',
        ];
    }

    /**
     * Return filtered data
     * 
     * @param array $params contains parameters for filtering
     * @param object $model object of the model class
     * @return object filtered data
     */
    public static function getQuery($params, $model) {

        extract($params);
        $model = isset($id) ? QueryBuilder::validateInteger("id", $id, $model) : $model;
        $model = isset($email) ? QueryBuilder::validateString("abbrev", $abbrev, $model) : $model;
        $model = isset($username) ? QueryBuilder::validateString("username", $username, $model) : $model;
        $model = isset($user_type) ? QueryBuilder::validateInteger("user_type", $user_type, $model) : $model;
        $model = isset($status) ? QueryBuilder::validateInteger("status", $status, $model) : $model;
        $model = isset($display_name) ? QueryBuilder::validateString("display_name", $display_name, $model) : $model;
        $model = isset($salutation) ? QueryBuilder::validateString("salutation", $salutation, $model) : $model;
        $model = isset($remarks) ? QueryBuilder::validateString("remarks", $remarks, $model) : $model;
        $model = isset($valid_start_date) ? QueryBuilder::validateString("valid_start_date", $valid_start_date, $model) : $model;
        $model = isset($valid_end_date) ? QueryBuilder::validateString("valid_end_date", $valid_end_date, $model) : $model;
        $model = isset($creation_timestamp) ? QueryBuilder::validateString("creation_timestamp", $creation_timestamp, $model) : $model;
        $model = isset($creator_id) ? QueryBuilder::validateInteger("creator_id", $creator_id, $model) : $model;
        $model = isset($modification_timestamp) ? QueryBuilder::validateString("modification_timestamp", $modification_timestamp, $model) : $model;
        $model = isset($modifier_id) ? QueryBuilder::validateInteger("modifier_id", $modifier_id, $model) : $model;
        $model = isset($notes) ? QueryBuilder::validateString("notes", $notes, $model) : $model;
        $model = isset($is_void) ? QueryBuilder::validateBoolean("is_void", $is_void, $model) : $model;

        return $model;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductGs() {
        return $this->hasMany(ProductGid::className(), ['creator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrograms() {
        return $this->hasMany(Program::className(), ['creator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts() {
        return $this->hasMany(Product::className(), ['creator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCrossMethods() {
        return $this->hasMany(CrossMethod::className(), ['creator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCrosscuttings() {
        return $this->hasMany(Crosscutting::className(), ['creator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChangelogs() {
        return $this->hasMany(Changelog::className(), ['creator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntities() {
        return $this->hasMany(Entity::className(), ['creator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFacilityMetadatas() {
        return $this->hasMany(FacilityMetadata::className(), ['creator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGeolocationDatas() {
        return $this->hasMany(GeolocationData::className(), ['creator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFormulas() {
        return $this->hasMany(Formula::className(), ['creator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFormulaParameters() {
        return $this->hasMany(FormulaParameter::className(), ['creator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInstructions() {
        return $this->hasMany(Instruction::className(), ['creator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGeolocations() {
        return $this->hasMany(Geolocation::className(), ['creator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGeolocationMetadatas() {
        return $this->hasMany(GeolocationMetadata::className(), ['creator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInstitutes() {
        return $this->hasMany(Institute::className(), ['creator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemActions() {
        return $this->hasMany(ItemAction::className(), ['creator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMethods() {
        return $this->hasMany(Method::className(), ['creator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemRecords() {
        return $this->hasMany(ItemRecord::className(), ['creator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemRelations() {
        return $this->hasMany(ItemRelation::className(), ['creator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlaces() {
        return $this->hasMany(Place::className(), ['creator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhases() {
        return $this->hasMany(Phase::className(), ['creator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPipelines() {
        return $this->hasMany(Pipeline::className(), ['creator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlaceMetadatas() {
        return $this->hasMany(PlaceMetadata::className(), ['creator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductNames() {
        return $this->hasMany(ProductName::className(), ['creator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductLists() {
        return $this->hasMany(ProductList::className(), ['creator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductMetadatas() {
        return $this->hasMany(ProductMetadata::className(), ['creator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlaceSeasons() {
        return $this->hasMany(PlaceSeason::className(), ['creator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScales() {
        return $this->hasMany(Scale::className(), ['creator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProperties() {
        return $this->hasMany(Property::className(), ['creator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecordVariables() {
        return $this->hasMany(RecordVariable::className(), ['creator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchemes() {
        return $this->hasMany(Scheme::className(), ['creator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoles() {
        return $this->hasMany(Role::className(), ['creator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProgramTeams() {
        return $this->hasMany(ProgramTeam::className(), ['creator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserRoles() {
        return $this->hasMany(UserRole::className(), ['creator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeamMembers() {
        return $this->hasMany(TeamMember::className(), ['creator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserItems() {
        return $this->hasMany(UserItem::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserMetadatas() {
        return $this->hasMany(UserMetadata::className(), ['creator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserSessions() {
        return $this->hasMany(UserSession::className(), ['creator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTooltips() {
        return $this->hasMany(Tooltip::className(), ['creator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModifier() {
        return $this->hasOne(User::className(), ['id' => 'modifier_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers() {
        return $this->hasMany(User::className(), ['creator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreator() {
        return $this->hasOne(User::className(), ['id' => 'creator_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserVariableSetMembers() {
        return $this->hasMany(UserVariableSetMember::className(), ['creator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVariableSetMembers() {
        return $this->hasMany(VariableSetMember::className(), ['creator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVariableResults() {
        return $this->hasMany(VariableResult::className(), ['creator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVariableSetRelations() {
        return $this->hasMany(VariableSetRelation::className(), ['creator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnalysisTransactions() {
        return $this->hasMany(AnalysisTransaction::className(), ['actor_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCrosscuttingTeams() {
        return $this->hasMany(CrosscuttingTeam::className(), ['creator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItems() {
        return $this->hasMany(Item::className(), ['creator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVariables() {
        return $this->hasMany(Variable::className(), ['creator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFacilities() {
        return $this->hasMany(Facility::className(), ['creator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScaleValues() {
        return $this->hasMany(ScaleValue::className(), ['creator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVariableSets() {
        return $this->hasMany(VariableSet::className(), ['creator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPipelineTeams() {
        return $this->hasMany(PipelineTeam::className(), ['creator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFacilityDatas() {
        return $this->hasMany(FacilityData::className(), ['creator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlaceDatas() {
        return $this->hasMany(PlaceData::className(), ['creator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductListMembers() {
        return $this->hasMany(ProductListMember::className(), ['creator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecords() {
        return $this->hasMany(Record::className(), ['creator_id' => 'id']);
    }

//    /**
//     * @return \yii\db\ActiveQuery
//     */
//    public function getTeams() {
//        return $this->hasMany(Team::className(), ['creator_id' => 'id']);
//    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSeasons() {
        return $this->hasMany(Season::className(), ['creator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPropertyMethodScales() {
        return $this->hasMany(PropertyMethodScale::className(), ['creator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFamilies() {
        return $this->hasMany(Family::className(), ['creator_id' => 'id']);
    }

    public function getTeams() {
        $teams = \app\modules\v1\models\Authentication::getTeams();
        return $this->hasMany(TeamMember::className(), ['member_id' => 'id', 'is_void' => 'is_void'])->where(['master.team_member.team_id' => $teams, 'is_void' => false]);
    }

    /**
     * Retrieve all the team the user belongs to
     * Get all the teams the user belongs to given the the user_id
     * @param integer $user_id id of the user
     * @return object $data objects of the user and team_member
     */
    public function getTeamsByUserId($user_id) {
        //  return User::findOne($user_id)->hasOne(TeamMember::className(), ['member_id' => 'id'])->where('master.team_member.team_id!= 6');
        $query = new \yii\db\Query;
        $query->select(['u.*', 'team_member.*'])
                ->from('master.user u')
                ->leftJoin('master.team_member', 'team_member.member_id=u.id')
                ->where('u.id=' . $user_id . '  and '
                        . 'team_member.is_void=false and u.is_void=false');
        $command = $query->createCommand();
        $data = $command->queryAll();
        return $data;
    }

    /**
     * Return the program object given the team object
     * Get all the programs the user belongs to given the team_id(s)
     * @param array $team_id array of team objects
     * @return array $data array of program objects
     */
    public function getProgramByTeamId($team_id) {
        $team = '';
        foreach ($team_id as $team_row) {
            $team.=' program_team.team_id=' . $team_row['team_id'] . ' or';
        }

        $team = substr($team, 0, -2);
        $query = new \yii\db\Query;
        $query->select(['program.id as program_id', 'program.*', 'program_team.*'])
                ->from('master.program ')
                ->leftJoin('master.program_team', 'program.id=program_team.program_id')->where('(' . $team . ') and program_team.is_void=false and program.is_void=false');
        $command = $query->createCommand();
        $data = $command->queryAll();
        return $data;
    }

    /**
     * Add additional attributes to the fields
     * @return array array of the extra fields, if you want to show the extra field, add parameter expand and the comma separated extra fields to return, e.g v1/studies?expand=audit
     */
    public function extraFields() {
        return [
            'teams' => function ($model) {
        $teams = array();
        foreach ($model->teams as $team) {
            $teams[] = $team->team;
        }
        return $model->teams;
    }
            ,
            'creation_timestamp',
            'creator_id' => function ($model) {
        return array('value' => $model->creator_id, 'href' => Url::to(['users/' . $model->creator_id], true));
    },
            'modification_timestamp',
            'modifier_id' => function ($model) {

        return array('value' => $model->modifier_id, 'href' => empty($model->modifier_id) ? null : Url::to(['users/' . $model->modifier_id], true));
    },
        ];
    }

    public function getAuthKey() {
        
    }

    public function getId() {
        return $this->getPrimaryKey();
    }

    public function validateAuthKey($authKey) {
        
    }

    public static function findIdentity($id) {
        return User::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null) {
        $identity = AccessTokens::findOne($token);
        if ($identity === null) {
            return null;
        } else {
            return User::findOne($identity->user_id);
        }
        //  throw new \yii\base\NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

}
