<?php

declare(strict_types=1);

namespace App\UI\Chat;

use Nette;

final class ChatPresenter extends Nette\Application\UI\Presenter
{
    private Nette\Database\Connection $database;

    public function __construct(Nette\Database\Connection $database)
    {
        parent::__construct();
        $this->database = $database;
    }
    //public function startup()
    //{
      //  parent::startup();
        //if(!$this->getUser()->isLoggedIn()){
          //  $this->redirect("Sign:");
 //       }

   // }

    private function escapeWithAllowedTags(string $input): string
    {
        // Povolené tagy
        $allowed_tags = '<a><h1><h2><h3><h4><h5><h6><br>';

        // Zachování povolených tagů
        $escaped = strip_tags($input, $allowed_tags);

        // Escapování všeho ostatního kromě povolených tagů
        // Použijeme htmlspecialchars() na zbylý text uvnitř tagů
        $escaped = preg_replace_callback(
            '/(<[^>]+>)|([^<]+)/',
            function ($matches) {
                if (!empty($matches[1])) {
                    // Pokud je to tag, vrátíme ho bez změny
                    return $matches[1];
                } else {
                    // Jinak escapujeme
                    return htmlspecialchars($matches[2], ENT_QUOTES, 'UTF-8');
                }
            },
            $escaped
        );

        return $escaped;
    }

    public function renderDefault(): void
    {
        $result = $this->database->query('SELECT * FROM ChatMes');
        $this->template->chat = [];
        foreach ($result as $res) {
            $this->template->chat[] = [
                "user" => $res->user,
                "message" => (string)$res->message,
            ];
        }
    }

    protected function createComponentForm(): Nette\Application\UI\Form
    {
        $form = new Nette\Application\UI\Form;

        $form->addText('message', '')
            ->setRequired();

        $form->addSubmit('send', 'Odeslat');
        $form->onSuccess[] = [$this, 'sendFormSucceeded'];

        return $form;
    }

    public function sendFormSucceeded(Nette\Application\UI\Form $form, \stdClass $values): void
    {
        $datum = date('j.n H:i:s', time());
        $zprava1 = $values->message;

        $url = 'https://hooks.slack.com/services/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';

        // URL-ify the data for the POST
        $fields_string = '{"text": "' . $zprava1 . '"}';

        // Open connection
        $ch = curl_init();

        // Set the URL, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

        // So that curl_exec returns the contents of the cURL; rather than echoing it
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute post
        $result = curl_exec($ch);
        curl_close($ch);

        $this->database->query('INSERT INTO ChatMes', [
            "user" => "unknown",
            "message" => $this->escapeWithAllowedTags($zprava1),
        ]);

        $this->redirect('this');
    }
}
