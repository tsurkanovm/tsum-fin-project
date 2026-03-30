<?php

namespace Tsum\CashFlow\Api\Data;

interface RegistrationDocumentInterface
{
    public const string REGISTRATION_TIME = 'registration_time';
    public const string IS_ACTIVE = 'is_active';

    /**
     * @return string|null
     */
    public function getRegistrationTime(): ?string;

    /**
     * @param string $registrationTime
     * @return $this
     */
    public function setRegistrationTime(string $registrationTime);

    /**
     * @return bool|null
     */
    public function isActive(): ?bool;

    /**
     * @param bool|int $isActive
     * @return $this
     */
    public function setIsActive(bool|int $isActive);
}
