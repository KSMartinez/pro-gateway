<?php


namespace App\Service;

use App\Entity\Event;
use App\Entity\User;
use App\Repository\EventParticipantRepository;
use App\Repository\EventRepository;
use App\Repository\UserRepository;
use Exception;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\RequestStack;


class EventService
{
    /**
     * Number of random events to display
     */
    const random_Events_Value = 6;

    /**
     * @param EventRepository $eventRepository
     * @param EventParticipantRepository $eventParticipantRepository
     * @param UserRepository $userRepository
     * @param RequestStack $requestStack
     */
    public function __construct(
        private EventRepository            $eventRepository,
        private EventParticipantRepository $eventParticipantRepository,
        private UserRepository             $userRepository,
        private RequestStack               $requestStack
    )
    {
    }

    /**
     * @return Event[]
     */
    public function randomEventsList()
    {


        $events = array();
        $result = array();

        $allEvents = $this->eventRepository->allEvents();

        $rangeMax = count($allEvents) - 1;

        if (count($allEvents) > self::random_Events_Value) {

            # Let's generate 6 Random events
            while (count($events) != self::random_Events_Value) {

                for ($i = 0; $i < count($allEvents); $i++) {

                    $forRandomEvent = mt_rand(0, $rangeMax);

                    if ($i == $forRandomEvent) {

                        if (!in_array($allEvents[$i], $events)) {

                            array_push($events, $allEvents[$i]);

                        }
                    }
                }

            }


            return $this->reOrderTable($events, $result);

        }

        return $this->reOrderTable($allEvents, $result);


    }

    /**
     * @param Event[] $events
     * @param Event[] $result
     * @return Event[]
     */
    public function reOrderTable(array $events, array $result)
    {

        $array_dateTimes = array();

        for ($i = 0; $i < count($events); $i++) {

            $dateTime = $events[$i]->getStartingAt();

            $array_dateTimes[$i] = $dateTime->format('Y-m-d H:i:s');


        }

        usort($array_dateTimes, function ($time1, $time2) {

            if (strtotime($time1) < strtotime($time2))
                return 1;

            else if (strtotime($time1) > strtotime($time2))
                return -1;
            else
                return 0;
        });


        usort($array_dateTimes, function ($time1, $time2) {

            $hours1 = substr($time1, 11, -6);
            $minutes1 = substr($time1[0], 14, -3);

            $hours2 = substr($time2, 11, -6);
            $minutes2 = substr($time2[0], 14, -3);

            if (intval($hours1) < intval($hours2)) {
                return 1;
            } else if (intval($hours1) > intval($hours2)) {
                return -1;
            } else {
                if (intval($minutes1) < intval($minutes2)) {
                    return 1;
                } else if (intval($minutes1) > intval($minutes2)) {
                    return -1;
                } else {
                    return 0;
                }

            }

        });


        foreach ($array_dateTimes as $date) {

            $i = 0;

            foreach ($events as $event) {


                $dateTime = $events[$i]->getStartingAt()->format('Y-m-d H:i:s');

                if (strcmp($dateTime, $date) == 0) {

                    if (!in_array($event, $result)) {

                        array_push($result, $event);
                    }

                }

                $i++;
            }

        }


        return $result;
    }

    /**
     * @return Event
     * @throws Exception
     */
    public function updatePicture(): Event
    {
        if ($this->requestStack->getCurrentRequest() === null) {
            throw new Exception('Request is null');
        }
        $request = $this->requestStack->getCurrentRequest();
        $object = $request->attributes->get('data');

        if (!($object instanceof Event)) {
            throw new \RuntimeException('The object does not match');
        }
        if (!$object->getId()) {
            throw new Exception('The user should have an id for updating');
        }
        if (!$this->eventRepository->find($object->getId())) {
            throw new Exception('The user should have an id for updating');
        }
        $file = $request->files->get('imageFile');

        if ($file instanceof File) {
            $object->setFile($file);
            $object->setUpdatedAt(new \DateTimeImmutable());
        }

        return $object;
    }


    /**
     * @param Event $data
     * @return User[]
     */
    public function participantList(Event $data)
    {


        if (!$data->getId()) {
            throw new Exception('The event should have an id for getting the Participant List');
        }
        if (!$this->eventRepository->find($data->getId())) {
            throw new Exception('The event should have an id for Participant List');

        }

        $eventParticipants = $this->eventParticipantRepository->getParticipants($data->getId());
        $participants = array();


        foreach ($eventParticipants as $ep) {
            array_push($participants, $ep->getUser()->getId());
        }

        $users = $this->userRepository->userEvents($participants);

        return $users;


    }


}