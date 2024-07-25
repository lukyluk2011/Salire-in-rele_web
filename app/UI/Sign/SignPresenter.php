<?php

declare(strict_types=1);

namespace App\UI\Sign;

use Couchbase\AuthenticationException;
use Nette;
use Nette\Application\UI\Form;

final class SignPresenter extends Nette\Application\UI\Presenter
{
    protected function createComponentRegistrationForm(): Form
    {
        $form = new Form;
        $form->addPassword('password', 'Heslo:');
        $form->addSubmit('send', 'Přihlásit se');
        $form->onSuccess[] = [$this, 'formSucceeded'];
        return $form;
    }

    public function formSucceeded(Form $form, \stdClass $data): void
    {
        // zpracujeme data odeslaná formulářem
        // $data->password obsahuje heslo
        try {
            $this->getUser()->authenticator->authenticate("sal", $data->password);
            $this->flashMessage('Byl jste úspěšně registrován.');
            //setcookie("user", "yes", time() + (86400 * 30), "/");
            $this->redirect('RezimUprav:');
        } catch (AuthenticationException $e) {
            $this->flashMessage('Neplatné heslo.', 'error');
        }
    }


}
