<?php

namespace App\CommandHandler;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag(name: 'app.handler')]
class FooHandler
{
    public function __construct()
    {

    }
}