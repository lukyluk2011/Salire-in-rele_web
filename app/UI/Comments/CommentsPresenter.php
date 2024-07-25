<?php

declare(strict_types=1);

namespace App\UI\Home;

use Nette;


final class CommentsPresenter extends Nette\Application\UI\Presenter
{    public function startup(){
    parent::startup();
    if(empty($_SESSION["user"])){
        $this->redirect("Home:prihlas");
    }
}
}
