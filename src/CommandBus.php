<?php
// src/CommandBus.php
namespace App;

use App\CommandHandler\BarHandler;
use App\CommandHandler\FooHandler;
use App\Handler\HandlerCollection;
use App\Handler\HandlerLocator;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Contracts\Service\ServiceSubscriberInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\DependencyInjection\Attribute\TaggedLocator;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Symfony\Component\DependencyInjection\Attribute\Target;

use Symfony\Contracts\Service\Attribute\SubscribedService;

class CommandBus implements ServiceSubscriberInterface
{
    public function __construct(
        //#[Autowire(service: 'app.command_handler_locator')]
        //#[TaggedLocator(tag: 'app.handler')] 
       private ContainerInterface $locator
      ) {
    }
    
    public static function getSubscribedServices(): array
    {
        return [
          
            // TaggedLocator
            new SubscribedService('handlers', ContainerInterface::class, false, attributes: new TaggedLocator('app.handler')),
        ];
    }

    public function handle($typecommand): mixed
    {

       
        dd($this->locator->get('handlers'));
        if ($this->locator->has($typecommand)) {
            $handler = $this->locator->get($typecommand);
            
           
            return $handler;
        }
    }
}