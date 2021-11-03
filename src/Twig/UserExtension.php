<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class UserExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('filter_name', [$this, 'doSomething']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('user_role', [$this, 'roles']),
        ];
    }

    public function roles($value)
    {
        switch ($value){
	        case 'ROLE_SUPER_ADMIN':
				$mention = 'SUPER ADMIN';
				break;
	        case 'ROLE_DISTRICT':
				$mention = 'DISTRICT';
				break;
	        case 'ROLE_REGION':
				$mention = 'REGION';
				break;
	        case 'ROLE_NATION':
				$mention = 'NATION';
				break;
	        case 'ROLE_ADMIN':
				$mention = 'ADMIN';
				break;
	        default:
				$mention = 'UTILISATEUR';
        }
		
		return $mention;
    }
}
