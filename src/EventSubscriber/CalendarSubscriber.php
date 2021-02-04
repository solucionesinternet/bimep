<?php

namespace App\EventSubscriber;

use App\Entity\TurbinesFiles;
use App\Repository\TurbinesFilesRepository;
use CalendarBundle\CalendarEvents;
use CalendarBundle\Entity\Event;
use CalendarBundle\Event\CalendarEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CalendarSubscriber implements EventSubscriberInterface
{
    private $turbinesFilesRepository;
    private $router;

    public function __construct(
        TurbinesFilesRepository $turbinesFilesRepository,
        UrlGeneratorInterface $router
    ) {
        $this->turbinesFilesRepository = $turbinesFilesRepository;
        $this->router = $router;
    }

    public static function getSubscribedEvents()
    {
        return [
            CalendarEvents::SET_DATA => 'onCalendarSetData',
        ];
    }

    public function onCalendarSetData(CalendarEvent $calendar)
    {
        $start = $calendar->getStart();
        $end = $calendar->getEnd();
        $filters = $calendar->getFilters();

        // Modify the query to fit to your entity and needs
        // Change booking.beginAt by your start date property
        $bookings = $this->turbinesFilesRepository
            ->createQueryBuilder('TurbinesFiles')
            ->where('TurbinesFiles.date BETWEEN :start and :end OR TurbinesFiles.date BETWEEN :start and :end')
            ->setParameter('start', $start->format('Y-m-d H:i:s'))
            ->setParameter('end', $end->format('Y-m-d H:i:s'))
            ->getQuery()
            ->getResult()
        ;

        foreach ($bookings as $booking) {
            // this create the events with your data (here booking data) to fill calendar
            $bookingEvent = new Event(
                $booking->getName(),
                $booking->getDate(),
                null
            );

            /*
             * Add custom options to events
             *
             * For more information see: https://fullcalendar.io/docs/event-object
             * and: https://github.com/fullcalendar/fullcalendar/blob/master/src/core/options.ts
             */

            $bookingEvent->setOptions([
                'backgroundColor' => '9199e6',
                'borderColor' => 'blue',
                'textColor' => 'white',
                'setAllDay' => true
            ]);
            $bookingEvent->addOption(
                'id',
                $booking->getId()

            );

            // finally, add the event to the CalendarEvent to fill the calendar
            $calendar->addEvent($bookingEvent);
        }
    }
}