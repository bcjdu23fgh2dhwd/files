<?php

namespace Interpro\ImageAgrTypes;

use Illuminate\Bus\Dispatcher;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Interpro\Files\FieldProviding\FieldExtractor;
use Interpro\Files\FieldProviding\FieldSaver;

class FileTypeServiceProvider extends ServiceProvider {

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(Dispatcher $dispatcher)
    {
        $this->publishes([
            __DIR__.'/migrations' => $this->app->databasePath().'/migrations'
        ], 'migrations');

        //Создание основных папок -------------------------------------------------------------------------
        if(!File::isDirectory(public_path('files')))
        {
            File::makeDirectory(public_path('files'));
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $FExtMed = App::make('Interpro\QuickStorage\Concept\FieldProviding\FieldExtMediator');
        $FSaveMed = App::make('Interpro\QuickStorage\Concept\FieldProviding\FieldSaveMediator');

        $FE = new FieldExtractor();
        $FS = new FieldSaver();

        $FExtMed->addSuffix('file', $FE);
        $FSaveMed->addSuffix('file', $FS);

        $this->app->make('Interpro\Files\Http\FilesController');

        include __DIR__ . '/Http/routes.php';
    }

}
