<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $saltPassword
 * @property string $email
 * @property string $joinDate
 * @property integer $id_level
 * @property string $avatar
 *
 * The followings are the available model relations:
 * @property Comment[] $comments
 * @property Raputation[] $raputations
 * @property Raputation[] $raputations1
 * @property Thread[] $threads
 * @property Threadstar[] $threadstars
 * @property Level $level
 */
class User extends CActiveRecord
{
	public $password2;
	public $verifyCode;
	/**
	 * Returns the static model of the specified AR class.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('username, password,password2,verifyCode', 'required','message'=>'{attribute} Tidak Boleh Kosong'),
			array('verifyCode', 'captcha', 'allowEmpty'=>!extension_loaded('gd')),
			array('id_level', 'numerical', 'integerOnly'=>true),
			array('username', 'length', 'max'=>20),
			array('password, saltPassword, email', 'length', 'max'=>50),
			array('avatar','file', 'types'=>'gif,png,jpg'),
			array('id, username, password, saltPassword, email, joinDate, id_level, avatar, isActive', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			//'level' => array(self::BELONGS_TO, 'tbl_level', 'id_level'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'username' => 'Username',
			'password' => 'Password',
			'password2' => 'Password 2',
			'verifyCode'=>'Kode Verifikasi',
			'saltPassword' => 'Salt Password',
			'email' => 'Email',
			'joinDate' => 'Tanggal Bergabung',
			'id_level' => 'Level',
			'avatar' => 'Avatar',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('saltPassword',$this->saltPassword,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('joinDate',$this->joinDate,true);
		$criteria->compare('id_level',$this->id_level);	
		$criteria->compare('avatar',$this->avatar,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
	
	public function validatePassword($password)
	{
		return $this->hashPassword($password,$this->saltPassword)===$this->password;
	}
	
	public function hashPassword($password,$salt)
	{
		return md5($salt.$password);
	}


	public function generateSalt()
	{
		return uniqid('',true);
	}
	
	public function status($ii)
	{
		if($ii==0)
			return 'Belum Aktif / Banned';
		else 
			return 'Aktif';
	}
		
}