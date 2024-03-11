<?php

namespace App\Modules\Search\Actions;

class FindTextBySite
{
    private $text;

    public function __construct($text)
    {
        $this->text = $text;
    }


    public function run()
    {
        return (new FindTextInArticle($this->text))->run();
    }
}
