<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as BaseResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResetPassword extends BaseResetPassword implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $backoff = 30;

    public function __construct(#[\SensitiveParameter] string $token)
    {
        parent::__construct($token);

        $this->afterCommit();
    }
}
