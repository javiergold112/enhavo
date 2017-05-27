<?php
/**
 * Created by PhpStorm.
 * User: m
 * Date: 28.04.17
 * Time: 18:02
 */

namespace Enhavo\Bundle\CalendarBundle\Provider;


use Doctrine\ORM\EntityManager;
use Enhavo\Bundle\CalendarBundle\Entity\Appointment;
use Recurr\Rule;
use Recurr\Transformer\ArrayTransformer;

class AppointmentProvider
{
    /**
     * @var EntityManager
     */
    private $em;

    public function __construct($em)
    {
        $this->em = $em;
    }

    public function getNormalizedAppointments(\DateTime $startDate, \DateTime $endDate)
    {
        $appointmentsWithRRULE = [];
        $appointments = $this->em->getRepository(Appointment::class)->getAppointments($startDate, $endDate);

        /** @var Appointment $appointment */
        foreach ($appointments as $index => $appointment){
            if($appointment->getRepeatRule()){
                $appointmentsWithRRULE[] = $appointment;
                unset($appointments[$index]);
            }
        }
        $appointmentsWithoutRRULE = array_values($appointments);

        $transformer = new ArrayTransformer();
        /** @var Appointment $appointmentWithRRULE */
        foreach ($appointmentsWithRRULE as $appointmentWithRRULE){
            $rule = new Rule(   $appointmentWithRRULE->getRepeatRule(),
                                $appointmentWithRRULE->getDateFrom(),
                                $appointmentWithRRULE->getDateTo());
            $timeRanges = $transformer->transform($rule);
            foreach ($timeRanges as $timeRange){
                if($timeRange->getStart() < $endDate && $timeRange->getEnd() > $startDate){
                    /** @var Appointment $newAppointmentWithoutRRULE */
                    $newAppointmentWithoutRRULE = clone $appointmentWithRRULE;
                    $newAppointmentWithoutRRULE->setRepeatRule(null);
                    $newAppointmentWithoutRRULE->setDateFrom($timeRange->getStart());
                    $newAppointmentWithoutRRULE->setDateTo($timeRange->getEnd());
                    $appointmentsWithoutRRULE[] = $newAppointmentWithoutRRULE;
                }
            }
        }
        return $appointmentsWithoutRRULE;
    }
}