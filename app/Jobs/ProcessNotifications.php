<?php

namespace App\Jobs;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;

class ProcessNotifications implements ShouldQueue
{
	use Dispatchable;
	use InteractsWithQueue;
	use Queueable;
	use SerializesModels;

	protected $user;
    protected $params;

	/**
	 * Create a new job instance.
	 *
	 * @param  Client  $client
	 * @param  array  $params
	 */
    public function __construct(User $user, array $params)
    {
        $this->user = $user;
        $this->params = $params;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {

        $this->user->notifications()->create([
            'title'         => $this->params['title'],
            'status'       => 'unread',
            'body'          => $this->params['body'],
        ]);

    }
}
