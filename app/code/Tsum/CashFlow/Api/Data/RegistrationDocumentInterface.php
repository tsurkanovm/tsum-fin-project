<?php

namespace Tsum\CashFlow\Api\Data;

interface RegistrationDocumentInterface
{
    public const string REGISTRATION_TIME = 'registration_time';
    public const string IS_ACTIVE = 'is_active';

    public function getRegistrationTime(): ?string;
    public function setRegistrationTime(string $registrationTime);

    public function isActive(): ?bool;
    public function setIsActive(bool|int $isActive);
}
