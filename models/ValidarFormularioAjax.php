<?php

namespace app\models;
use Yii;
use yii\base\Model;

class ValidarFormularioAjax extends model{
    
    public $nombre;
    public $email;
    
    public function rules(){
        return [
          ['nombre','required','message'=>'Campo requerido'],
          ['nombre','match','pattern'=>'/^.{3,50}$/','message'=>'Minimo 3 y maximo 50 caracteres'],
          ['nombre','match','pattern'=>'/^[0-9a-z]+$/i','message'=>'Solo se aceptan letras y numeros'],
          ['email','required','message'=>'Campo requerido'],
          ['email','match','pattern'=>'/^.{5,80}$/','message'=>'Minimo 5 y maximo 80 caracteres'],
          ['email','email','message'=>'Formato no valido'],
          ['email','email_existe']
        ];
    }
    public function attributeLabels(){
        return [
          'nombre' => 'Nombre: ',
          'email' => 'Email: '
        ];
    }
    public function email_existe($attribute, $params)
    {
        $email = ['manual@mail.com','antonio@mail.com'];
        if (in_array($this->email, $email)) {
            $this->addError($attribute, 'El email seleccionado existe');
            return true;
        }else{
            return false;
        }

    }
}