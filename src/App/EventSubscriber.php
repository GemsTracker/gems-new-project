<?php

declare(strict_types=1);

/**
 * @package    App
 * @subpackage
 * @author     Matijs de Jong <mjong@magnafacta.nl>
 */

namespace App;

use Gems\Event\Application\CreateMenuEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @package    App
 * @subpackage
 * @since      Class available since version 1.0
 */
class EventSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            CreateMenuEvent::class => [
                'updateMenu',
            ],
        ];
    }

    public function updateMenu(CreateMenuEvent $event)
    {
        $menu = $event->getMenu();

//        $menuConfig = [
//            $this->createMenuItem(
//                name: 'respondent.results',
//                label: $this->_('Results'),
//                parent: 'respondent.show',
//                position: 7,
//            ),
//            $this->createMenuItem(
//                name:  'information.information',
//                label: $this->_('Information'),
//                position: 3,
//            ),
//        ];
//
//
//        $menu->addFromConfig($menu, $menuConfig);
//
//        $add = $menu->find('respondent.tracks.create');
//        $add->setLabel($this->_('Add'));
//
//        if ($menu->getUser()) {
//            foreach (['ask.index', 'contact.index'] as $route) {
//                $menu->find($route)->setLabel('');
//            }
//        }
//
//        $menu->setHorizontal(true);
    }

}