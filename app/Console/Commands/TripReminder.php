<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Notification;
use App\Models\User;
use App\Models\Trip;
use App\Services\NotificationService;
class TripReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reminder driver 15 before the trip start';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $trips=Trip::whereDate('pickup_date', now()->toDateString())->where('reminder_status','0')->get();
        $now = Carbon::now();
        $pickupThreshold = $now->copy()->addMinutes(15);
        foreach ($trips as $key => $trip) {
            $pickupDate = Carbon::parse($trip->pickup_date);
            $timeUntilPickup = $now->diffInMinutes($pickupDate);
            if($timeUntilPickup<=15){
                $user=User::where('id',$trip->user_id)->first();
                if($user){
                    $data=[
                        'title'=>'Trip Reminder',
                        'message'=>'Your trip is about to start in 15 minutes',
                        'sound'=>'remindertrip.mp3',
                    ];
                    if($user->fcm_token!=null){
                        // dd($user->fcm_token);
                        (new NotificationService)->sendNotification($user->fcm_token,$data,'admin');
                    }
                    Trip::where('id','=',$trip->id)->update(['reminder_status'=>1]);
                    Notification::create(['title'=>$data['title'],
                        'notification'=>$data['message'],
                        'type'=>'notification',
                        'user_id'=>$user->id,
                        'seen'=>0,
                    ]);
                }
                
            }
        }
        $this->info('Successfully sent reminder.');
    }
}
