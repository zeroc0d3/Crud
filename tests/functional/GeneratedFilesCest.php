<?php

namespace Crud;

use Crud\FunctionalTester;
use Crud\Page\Functional\Generate as Page;

class GeneratedFilesCest
{
    public function _before(FunctionalTester $I)
    {
        // delete old generated dirs
        // $I->deleteDir(app_path("/Containers"));
        // $I->deleteDir(app_path("/Angular2"));

        // page setup
        new Page($I);
        $I->amLoggedAs(Page::$adminUser);

        $I->amOnPage(Page::route('?table_name='.Page::$tableName));
    }

    /**
     * Cleans the generated Container directory.
     */
    public function _after(FunctionalTester $I)
    {
        // if (file_exists(app_path("/Containers/").$this->package)) {
        //     // this step should be donde by user, but for testing purposes we do here
        //     // copy generated container on Hello-API project for test the final app there
        //     $I->copyDir(app_path('Containers/Book'), base_path('../hello/app/Containers/Book'));
        //     // copy migration file
        //     $migrationFile = base_path("llstarscreamll/Crud/src/Database/Migrations");
        //     $I->copyDir($migrationFile, base_path('../hello/app/Containers/Book/Data/Migrations'));
        //     // delete unnecessary copied migration file
        //     $I->deleteFile(base_path('../hello/app/Containers/Book/Data/Migrations/2016_03_01_222942_create_reasons_table.php'));
        // }

        // if (file_exists(app_path("/Angular2/Book"))) {
        //     // copy generated Angular 2 Moduel on saas-CLI project for test the final app there
        //     $I->copyDir(app_path('Angular2/Book'), base_path('../saas-CLI/src/app/modules/book'));
        // }
    }

    public function checkLaravelPackageFilesGeneration(FunctionalTester $I)
    {
        $I->wantTo('generate a Laravel Package');

        $data = Page::$formData;
        $data['app_type'] = 'laravel_package';
        $data['create_angular_2_module'] = true;
        // modify relations namespaces for Porto container convenience
        $data['field[1][namespace]'] = 'App\Containers\Reason\Models\Reason';
        $data['field[12][namespace]'] = 'App\Containers\User\Models\User';

        $this->package = studly_case(str_singular($data['is_part_of_package']));
        $this->entity = studly_case(str_singular($data['table_name']));
        
        $I->submitForm('form[name=CRUD-form]', $data);

        $this->checkAngular2ModuleGeneration($I);
        $this->checkPortoFilesGeneration($I);
    }

    private function checkAngular2ModuleGeneration($I)
    {
        $moduleDir = app_path('Angular2/Book/');
        $I->assertTrue(file_exists($moduleDir), 'NG Module dir');

        $I->seeFileFound('book.module.ts', $moduleDir);
        $I->seeFileFound('book-routing.module.ts', $moduleDir);

        // models
        $modelsDir = $moduleDir.'models/';
        $I->assertTrue(file_exists($modelsDir), 'NG models dir');
        $I->seeFileFound('book.ts', $modelsDir);
        $I->seeFileFound('bookPagination.ts', $modelsDir);

        // translations
        $transDir = $moduleDir.'translations/';
        $I->assertTrue(file_exists($transDir), 'NG translations dir');
        $I->seeFileFound('es.ts', $transDir);

        // actions
        $actionsDir = $moduleDir.'actions/';
        $I->assertTrue(file_exists($actionsDir), 'NG actions dir');
        $I->seeFileFound('book.actions.ts', $actionsDir);

        // reducers
        $reducersDir = $moduleDir.'reducers/';
        $I->assertTrue(file_exists($reducersDir), 'NG reducers dir');
        $I->seeFileFound('book.reducer.ts', $reducersDir);

        // effects
        $effectsDir = $moduleDir.'effects/';
        $I->assertTrue(file_exists($effectsDir), 'NG effects dir');
        $I->seeFileFound('book.effects.ts', $effectsDir);

        // services
        $servicesDir = $moduleDir.'services/';
        $I->assertTrue(file_exists($servicesDir), 'NG services dir');
        $I->seeFileFound('book.service.ts', $servicesDir);

        // components
        $componentsDir = $moduleDir.'components/';
        $I->assertTrue(file_exists($componentsDir), 'NG components dir');
        $I->seeFileFound('book-form.component.ts', $componentsDir);
        $I->seeFileFound('books-table.component.ts', $componentsDir);

        // containers
        $containersDir = $moduleDir.'containers/';
        $I->assertTrue(file_exists($containersDir), 'NG containers dir');
        $I->seeFileFound('list-and-search-books.page.ts', $containersDir);
        $I->seeFileFound('list-and-search-books.page.css', $containersDir);
        $I->seeFileFound('list-and-search-books.page.html', $containersDir);
        $I->seeFileFound('create-book.page.ts', $containersDir);
        $I->seeFileFound('create-book.page.css', $containersDir);
        $I->seeFileFound('create-book.page.html', $containersDir);
        $I->seeFileFound('book-details.page.ts', $containersDir);
        $I->seeFileFound('book-details.page.css', $containersDir);
        $I->seeFileFound('book-details.page.html', $containersDir);
        $I->seeFileFound('edit-book.page.ts', $containersDir);
        $I->seeFileFound('edit-book.page.css', $containersDir);
        $I->seeFileFound('edit-book.page.html', $containersDir);
    }

    private function checkPortoFilesGeneration(FunctionalTester $I)
    {
        // los directorios deben estar creados correctamente
        $I->assertTrue(file_exists(app_path('Containers')), 'Containers dir');
        $I->assertTrue(file_exists(app_path('Containers/'.$this->package)), 'package container dir');
        
        // generated entity Actions
        $actionsDir = app_path('Containers/'.$this->package.'/Actions/'.$this->entity);
        $I->assertTrue(file_exists($actionsDir), 'Actions dir');
        $I->seeFileFound('ListAndSearchBooksAction.php', $actionsDir);
        $I->seeFileFound('BookFormDataAction.php', $actionsDir);
        $I->seeFileFound('CreateBookAction.php', $actionsDir);
        $I->seeFileFound('GetBookAction.php', $actionsDir);
        $I->seeFileFound('UpdateBookAction.php', $actionsDir);
        $I->seeFileFound('DeleteBookAction.php', $actionsDir);
        $I->seeFileFound('RestoreBookAction.php', $actionsDir);

        // Configs folder
        $configsDir = app_path('Containers/'.$this->package.'/Configs');
        $I->assertTrue(file_exists($configsDir), 'Configs dir');
        $I->seeFileFound('book-form-model.php', $configsDir);

        // Data folders
        $dataDir = app_path('Containers/'.$this->package.'/Data');
        $I->assertTrue(file_exists($dataDir), 'Data dir');
        $I->assertTrue(file_exists($dataDir.'/Criterias'), 'Data/Criterias dir');
        $I->assertTrue(file_exists($dataDir.'/Factories'), 'Data/Factories dir');
        $I->assertTrue(file_exists($dataDir.'/Migrations'), 'Data/Migrations dir');
        $I->assertTrue(file_exists($dataDir.'/Repositories'), 'Data/Repositories dir');
        $I->seeFileFound('BookRepository.php', $dataDir.'/Repositories');
        $I->assertTrue(file_exists($dataDir.'/Seeders'), 'Data/Seeders dir');

        // exceptions
        $exceptionsDir = app_path('Containers/'.$this->package.'/Exceptions/');
        $I->assertTrue(file_exists($exceptionsDir), 'Exceptions dir');
        $I->seeFileFound('BookCreationFailedException.php', $exceptionsDir);
        $I->seeFileFound('BookNotFoundException.php', $exceptionsDir);

        // generated Models
        $modelsDir = app_path('Containers/'.$this->package.'/Models');
        $I->assertTrue(file_exists($modelsDir), 'Models dir');
        $I->seeFileFound('Book.php', $modelsDir);

        // generated entity Tasks
        $tasksDir = app_path('Containers/'.$this->package."/Tasks/{$this->entity}");
        $I->assertTrue(file_exists($tasksDir), 'Tasks dir');
        $I->seeFileFound('ListAndSearchBooksTask.php', $tasksDir);
        $I->seeFileFound('CreateBookTask.php', $tasksDir);
        $I->seeFileFound('GetBookTask.php', $tasksDir);
        $I->seeFileFound('UpdateBookTask.php', $tasksDir);
        $I->seeFileFound('DeleteBookTask.php', $tasksDir);
        $I->seeFileFound('RestoreBookTask.php', $tasksDir);

        // tests
        $testDir = app_path('Containers/'.$this->package.'/tests/');
        $I->assertTrue(file_exists($testDir), 'tests dir');
        $I->assertTrue(file_exists($testDir.'acceptance'), 'acceptance test');
        $I->seeFileFound('acceptance.suite.yml', $testDir);
        $I->seeFileFound('UserHelper.php', $testDir.'_support/Helper');
        $I->assertTrue(file_exists($testDir.'functional'), 'functional test');
        $I->seeFileFound('functional.suite.yml', $testDir);
        $I->assertTrue(file_exists($testDir.'unit'), 'unit test');
        $I->seeFileFound('unit.suite.yml', $testDir);
        $I->assertTrue(file_exists($testDir.'api'), 'api test');
        $I->seeFileFound('api.suite.yml', $testDir);
        // API entity tests
        $apiTestsFolder = $testDir.'api/'.$this->entity;
        $I->assertTrue(file_exists($apiTestsFolder), 'entity api tests dir');
        $I->seeFileFound('BookFormModelCest.php', $apiTestsFolder);
        $I->seeFileFound('BookFormDataCest.php', $apiTestsFolder);
        $I->seeFileFound('ListAndSearch'.str_plural($this->entity).'Cest.php', $apiTestsFolder);
        $I->seeFileFound('Create'.$this->entity.'Cest.php', $apiTestsFolder);
        $I->seeFileFound('Get'.$this->entity.'Cest.php', $apiTestsFolder);
        $I->seeFileFound('Update'.$this->entity.'Cest.php', $apiTestsFolder);
        $I->seeFileFound('Delete'.$this->entity.'Cest.php', $apiTestsFolder);
        $I->seeFileFound('Restore'.$this->entity.'Cest.php', $apiTestsFolder);

        // UI
        $I->assertTrue(file_exists(app_path('Containers/'.$this->package.'/UI')), 'UI dir');

        // UI/API
        $apiDir = app_path('Containers/'.$this->package.'/UI/API');
        $I->assertTrue(file_exists($apiDir), 'UI/API dir');
        $I->assertTrue(file_exists($apiDir.'/Controllers'), 'API/Controllers dir');
        $I->seeFileFound('Controller.php', $apiDir.'/Controllers');

        // generated entity API requests
        $apiRequestsDir = $apiDir.'/Requests';
        $I->assertTrue(file_exists($apiRequestsDir), 'API/Requests dir');
        $I->seeFileFound('ListAndSearchBooksRequest.php', $apiRequestsDir."/{$this->entity}");
        $I->seeFileFound('CreateBookRequest.php', $apiRequestsDir."/{$this->entity}");
        $I->seeFileFound('GetBookRequest.php', $apiRequestsDir."/{$this->entity}");
        $I->seeFileFound('UpdateBookRequest.php', $apiRequestsDir."/{$this->entity}");
        $I->seeFileFound('DeleteBookRequest.php', $apiRequestsDir."/{$this->entity}");
        $I->seeFileFound('RestoreBookRequest.php', $apiRequestsDir."/{$this->entity}");
        
        // generated API routes
        $apiRoutesDir = $apiDir.'/Routes';
        $I->assertTrue(file_exists($apiRoutesDir), 'API/Routes dir');
        $I->seeFileFound('BookFormModel.v1.private.php', $apiRoutesDir);
        $I->seeFileFound('BookFormData.v1.private.php', $apiRoutesDir);
        $I->seeFileFound('ListAndSearchBooks.v1.private.php', $apiRoutesDir);
        $I->seeFileFound('CreateBook.v1.private.php', $apiRoutesDir);
        $I->seeFileFound('UpdateBook.v1.private.php', $apiRoutesDir);
        $I->seeFileFound('DeleteBook.v1.private.php', $apiRoutesDir);
        $I->seeFileFound('RestoreBook.v1.private.php', $apiRoutesDir);

        $I->assertTrue(file_exists($apiDir.'/Transformers'), 'API/Transformers dir');
        $I->seeFileFound('BookTransformer.php', $apiDir.'/Transformers');

        // WEB folders
        $uiWebDir = app_path('Containers/'.$this->package.'/UI/WEB');
        $I->assertTrue(file_exists($uiWebDir), 'UI/WEB dir');
        $I->assertTrue(file_exists($uiWebDir.'/Controllers'), 'WEB/Controllers dir');
        $I->assertTrue(file_exists($uiWebDir.'/Requests'), 'WEB/Requests dir');
        $I->assertTrue(file_exists($uiWebDir.'/Routes'), 'WEB/Routes dir');
        $I->assertTrue(file_exists($uiWebDir.'/Views'), 'WEB/Views dir');

        // CLI folders
        $I->assertTrue(file_exists(app_path('Containers/'.$this->package.'/UI/CLI')), 'UI/CLI dir');

        // Other files
        $I->seeFileFound('composer.json', app_path('Containers/'.$this->package));
    }

    /**
     * Comprueba la funcionalidad de crear los ficheros requeridos para la CRUD app.
     *
     * @param FunctionalTester $I
     */
    /*public function checkLaravelAppFilesGeneration(FunctionalTester $I)
    {
        $I->wantTo('crear aplicacion Laravel App CRUD');

        $I->submitForm('form[name=CRUD-form]', Page::$formData);

        // veo los mensajes de operación exitosa
        $I->see('Los tests se han generado correctamente.', '.alert-success');
        $I->see('Modelo generado correctamente', '.alert-success');
        $I->see('Controlador generado correctamente', '.alert-success');
        // hay muchos otros mensajes

        // compruebo que los archivos de la app hayan sido generados
        $I->seeFileFound('Book.php', base_path('app/Models'));
        $I->seeFileFound('BookController.php', base_path('app/Http/Controllers'));
        $I->seeFileFound('BookService.php', base_path('app/Services'));
        $I->seeFileFound('BookRepository.php', base_path('app/Repositories/Contracts'));
        $I->seeFileFound('SearchBookCriteria.php', base_path('app/Repositories/Criterias'));
        $I->seeFileFound('BookEloquentRepository.php', base_path('app/Repositories'));
        $I->seeFileFound('book.php', base_path('/resources/lang/es'));
        // reviso que se hallan añadido las rutas en web.php
        $I->openFile(base_path('routes/web.php'));
        $I->seeInThisFile("Route::resource('books', 'BookController');");

        // los tests
        foreach (config('modules.crud.config.tests') as $test) {
            if ($test != 'Permissions') {
                $I->seeFileFound($test.'.php', base_path('tests/_support/Page/Functional/Books'));
            }
            $I->seeFileFound($test.'Cest.php', base_path('tests/functional/Books'));
        }

        // las vistas
        foreach (config('modules.crud.config.views') as $view) {
            if (strpos($view, 'partials/') === false) {
                $I->seeFileFound($view.'.blade.php', base_path('resources/views/books'));
            } else {
                $I->seeFileFound(
                    str_replace('partials/', '', $view).'.blade.php',
                    base_path('resources/views/books/partials')
                );
            }
        }
    }*/
}
