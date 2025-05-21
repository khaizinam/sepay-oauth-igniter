<?php

namespace App\Controllers;

class Setup extends BaseController
{
    public function index()
    {
        $migrations = service('migrations');
        try {
            $migrations->latest();
            echo 'Migration setup successfully';
        } catch (\Throwable $th) {
            echo 'Error in creating database ' . $th->getMessage();
        }
    }

    public function dropTable(){
        $migrations = service('migrations');
        try {
            $migrations->regress(0);
            echo 'Migration drop successfully';
        } catch (\Throwable $th) {
            echo 'Error in drop database ' . $th->getMessage();
        }
    }
}
