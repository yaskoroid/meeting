<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 20.11.2018
 * Time: 15:14
 */

namespace Service;

class DateTime extends Basic
{
    /**
     * @return \DateTime;
     */
    public function now() {
        return new \DateTime();
    }

    /**
     * @param \DateTime $dateTime
     * @return string;
     */
    public function formatMySql($dateTime) {
        return $dateTime->format('Y-m-d H:i:s');
    }

    /**
     * @return string;
     */
    public function formatMySqlUtc() {
        return gmdate(
            'Y-m-d H:i:s',
            strtotime($this->formatMySql($this->now()))
        );
    }

    /**
     * @return string;
     */
    public function formatMySqlNextWeekUtc() {
        return gmdate(
            'Y-m-d H:i:s',
            strtotime($this->formatMySql($this->now()) . ' + 1 week')
        );
    }

    /**
     * @return string;
     */
    public function formatMySqlNextHourUtc() {
        return gmdate(
            'Y-m-d H:i:s',
            strtotime($this->formatMySql($this->now()) . ' + 1 hour')
        );
    }
}