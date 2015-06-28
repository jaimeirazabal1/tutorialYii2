<?php

use yii\helpers\Url;
use yii\helpers\Html;

?>

<?= Html::beginForm(Url::toRoute("site/request"),'post',['class'=>'form-inline']) ?>
    <div class="form-group">
        <?php echo Html::label("Nombre") ?>
        <?php echo Html::textInput("nombre",null,['class'=>'form-control']) ?>
    </div>
    <?php echo Html::submitButton("Enviar",['class'=>'btn btn-primary']) ?>
<?= Html::endForm() ?>
<br />
<?= Html::a("Validar Formulario",Url::toRoute('site/validarformulario')) ?>