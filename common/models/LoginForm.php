<?php
namespace common\models;

use Yii;
use yii\base\Model;
use yii\helpers\VarDumper;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            //['rememberMe', 'boolean'],
            // password is validated by validatePassword()
           ['password', 'validatePassword'],
           
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
       
      // do Active directory authentication here
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            
            if(Yii::$app->params['SystemConfigs']['UsingNTLM'] == 'Yes'){ 
                if (!$user || !$user->validatePassword($this->password) || !$this->logintoAD($this->username, $this->password) ) {//Add AD login condition here also--> when ad details are given
                    $this->addError($attribute, 'Incorrect username or password.');
                }
            }else{
                if (!$user || !$user->validatePassword($this->password) || !$this->actionAuth($this->username, $this->password) ) {//Add AD login condition here also--> when ad details are given

                    $this->addError($attribute, 'Incorrect username or password.');
                }
            }
            
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {

        if ($this->validate()) {

            //Lets log the password
            Yii::$app->session->set('IdentityPassword', $this->password);
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);

        }
        
        return false;
    }

    //Ad Login

    function logintoAD($username,$password){
        
        $me=['ye'=>'ds'];//replace this hack for go live, this hack is for dev env only
        return $me;//replace this hack for go live

        $adServer = Yii::$app->params['adServer'];//
        $ldap = ldap_connect($adServer, 389);//connect
        $ldaprdn = Yii::$app->params['ldPrefix'] . "\\" . strtoupper($username);//put the username in a way specific to the domain
        ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);
        $bind = @ldap_bind($ldap, $ldaprdn, $password);
            // echo '<pre>';
            // VarDumper::dump( $bind, $depth = 10, $highlight = true);
            // exit;
        if ($bind) {
           
            // return $bind; // True for a bind else false
            $filter = "(sAMAccountName=$username)";
            $result = ldap_search($ldap, "CN=Users,DC=tenwekhosp, DC=org", $filter);
           
            // ldap_sort($ldap,$result,"sn");
            $info = ldap_get_entries($ldap, $result);
        

            return $info;
            @ldap_close($ldap);
        } else {
            //notify incorrect login
            return false;
        }

    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {

        if ($this->_user === null) {
            //TENWEKHOSP\NAVADMIN
            // exit();
            $this->_user = User::findByUsername(strtoupper($this->username));
            // echo '<pre>';
            // VarDumper::dump( $this->_user, $depth = 10, $highlight = true);
            // exit;
        }


        return $this->_user;
    }

    public function actionAuth($UserName, $Password)
    {
        $service = Yii::$app->params['ServiceName']['UserSetup'];
        $credentials = new \stdClass();
        // $json = file_get_contents('php://input');
        // Convert it into a PHP object
        //$data = json_decode($json);
        $NavisionUsername = $UserName;
        $NavisionPassword = $Password;

        $credentials->UserName = $NavisionUsername;
        $credentials->PassWord = $NavisionPassword;
        // echo '<pre>';
        // print_r($credentials);
        // exit;
       // return $credentials;

        $result = \Yii::$app->navhelper->findOne($service,$credentials,'User_ID', $NavisionUsername);
                // \yii\helpers\VarDumper::dump( $result, $depth = 10, $highlight = true); exit;

        return $result;
    }
}
