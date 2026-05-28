<?php

namespace Caspian\Core;

interface ValidatorInterface
{
    public function rules(): array;
    public function mode(): string;
    public function auto(): bool;
    public function before(): void;
    public function after(): void;
    public function exception(): void;
}
