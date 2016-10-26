<?php
/* @var $gen llstarscreamll\CrudGenerator\Providers\TestsGenerator */
/* @var $fields [] */
/* @var $test [] */
/* @var $request Request */
?>
<?='<?php'?>


<?= $gen->getClassCopyRightDocBlock() ?>


namespace {{$gen->studlyCasePlural()}};

use FunctionalTester;
use Page\Functional\{{$gen->studlyCasePlural()}}\{{$test}} as Page;

class {{$test}}Cest
{
    /**
     * Las acciones a realizar antes de cada test.
     *
     * @param  FunctionalTester $I
     */
    public function _before(FunctionalTester $I)
    {
        new Page($I);
        $I->amLoggedAs(Page::$adminUser);
    }

    /**
     * Prueba la funcionalidad de editar la información de un modelo ya creado.
     *
     * @param  FunctionalTester $I
     */
    public function edit(FunctionalTester $I)
    {
        $I->wantTo('editar un registro en modulo '.Page::$moduleName);

        // creo el registro de prueba
        Page::have{{$gen->modelClassName()}}($I);

        // voy a la página de detalles del registro
        $I->amOnPage(Page::route('/'.Page::${{$gen->modelVariableName()}}Data['id']));
        // doy clic al enlace para ir al formulario de edición
        $I->click(Page::$linkToEdit, Page::$linkToEditElem);

        // estoy en la página de edición
        $I->seeCurrentUrlEquals(Page::route('/'.Page::${{$gen->modelVariableName()}}Data['id'].'/edit'));
        $I->see(Page::$moduleName, Page::$titleElem);
        $I->see(Page::$title, Page::$titleSmallElem);

        // veo los datos en el formulario
        $I->seeInFormFields(Page::$form, Page::getUpdateFormData());

        // envío el formulario con los nuevos datos
        $updateData = Page::getDataToUpdateForm();
        $I->submitForm(Page::$form, $updateData);

        // soy redirigido al Index del módulo
        $I->seeCurrentUrlEquals(Page::route(''));
        // veo mensaje de confirmación
        $I->see(Page::$msgSuccess, Page::$msgSuccessElem);
        
        // voy a la página de detalles del registro
        $I->amOnPage(Page::route('/'.Page::${{$gen->modelVariableName()}}Data['id']));
        
        // veo los datos actualizados en el formulario de sólo lectura
        $updateData = Page::unsetHiddenFields($updateData);
        $updateData = Page::unsetConfirmationFields($updateData);
        $I->seeInFormFields('form', $updateData);
    }
}