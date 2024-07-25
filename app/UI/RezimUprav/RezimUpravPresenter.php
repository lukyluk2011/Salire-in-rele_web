<?php

declare(strict_types=1);

namespace App\UI\RezimUprav;

use Nette;


final class RezimUpravPresenter extends Nette\Application\UI\Presenter
{

    public function startup()
    {
        parent::startup();
        if(!$this->getUser()->isLoggedIn()){
            //$this->redirect("Sign:");
        }

    }
    public function renderDefault()
    {
        $this->template->isLoggedIn = $this->getUser()->isLoggedIn();
    }
}