<?php

namespace App\Utils;


use App\Entity\Event;

class CalendarBox
{
    /**
     *  @var integer
     * number of second after begining of the day to start the box
     */
    private $start;

    /**
     * @var integer
     * number of second after begining of the event to end the box
     */
    private $height;

    /**
     * @var bool
     * is it the first box of the event
     */
    private $init;

    /**
     * @var Event
     * event asociated to the box
     */
    private $event;

    public function __construct(int $start, int $height, Event $event, bool $init=true)
    {
        $this->start = $start;
        $this->height = $height;
        $this->event = $event;
        $this->init = $init;
    }

    public function setStart(int $start):self
    {
        $this->start = $start;

        return $this;
    }

    public function getStart():int
    {
        return $this->start;
    }

    public function setHeight(int $height):self
    {
        $this->height = $height;

        return $this;
    }

    public function getHeight():int
    {
        return $this->height;
    }

    public function setInit(bool $init):self
    {
        $this->init = $init;

        return $this;
    }

    public function getInit():bool
    {
        return $this->init;
    }

    public function setEvent(Event $event):self
    {
        $this->event = $event;

        return $this;
    }

    public function getEvent():Event
    {
        return $this->event;
    }

    /*
     * helpful class that can create a list of boxes separated for each week day
     * make the display easyer in columns of the weekly calendar
     */
    public static function getWeeklyBoxes(\DateTime $monday, Array $events):array
    {
        $nbJour = 7;
        $weeklyEvents = array($nbJour);
         for ($day=0; $day <$nbJour; $day++)
             $weeklyEvents[$day]= array();

        foreach ($events as $event) 
        {
            //reevaluate the start and finish of the event to fit in the week
            $sunday = new \DateTime('@'.$monday->getTimestamp());
            $sunday->add(new \DateInterval('P7D'));
            $start = max($event->getStart(),$monday);
            $finish = min($event->getFinish(),$sunday);

            $jour = (int)(($start->getTimestamp()-$monday->getTimestamp())/86400);
            $init = true;
            do 
            {
                $day = new \DateTime('@'.$monday->getTimestamp());
                $day->add(new \DateInterval('P'.$jour.'D'));
                $endDay = new \DateTime('@'.$day->getTimestamp());
                $endDay->add(new \DateInterval('P1D'));

                $startBox = max($start,$day)->getTimestamp()-$day->getTimestamp();
                $finishBox = min($finish,$endDay)->getTimestamp() - $day->getTimestamp();
                array_push($weeklyEvents[$jour], new CalendarBox($startBox,$finishBox-$startBox,$event,$init));
                
                 $init = false; //will only be true for the first box
                 $jour++;

              } while ( $finish > $endDay);
        }

        return $weeklyEvents;
    }    
}