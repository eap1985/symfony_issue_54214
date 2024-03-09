<?php

// src/ValueResolver/IdentifierValueResolver.php
namespace App\ValueResolver;

use App\IdentifierInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;

use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class BookingIdValueResolver implements ValueResolverInterface
{
    public function supports(Request $request, ArgumentMetadata $argument): bool {
        
        return $argument->getType() === 'App\Reservation\BookingId';
        
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {

        // get the argument type (e.g. BookingId)
        $argumentType = $argument->getType();
      
        if (
            !$argumentType
           // || !is_subclass_of($argumentType, IdentifierInterface::class, true)
        ) {

            return [];
        }

        // get the value from the request, based on the argument name
        $value = $request->attributes->get('id');
    
        if (!is_string($value)) {
            return [];
        }
      
        // create and return the value object
        return [new $argumentType];
    }
}