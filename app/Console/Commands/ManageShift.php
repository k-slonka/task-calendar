<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UsersShift;
use App\Models\Estate;

class ManageShift extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:manage-shift';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set new supervisor to estate and return old user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //get shifts which are between this dates
        $shiftsNew = UsersShift::whereDate('date_from', '<=', today())->whereDate('date_to', '>=', today())->get();

        foreach ($shiftsNew as $shift) {
            //get estates ids and set this in temp_changes
            $estatesIds = Estate::where('supervisor_user_id', $shift->user_id)->get()->pluck('id')->toArray();
            $shift->temp_changes = $estatesIds;
            $shift->save();
            //change user in estate
            Estate::whereIn('id', $estatesIds)->update(['supervisor_user_id' => $shift->substitute_user_id]);
        }

        //get shifts which are end today or ended
        $shiftsOld = UsersShift::whereDate('date_to', '<', today())->get();
        foreach ($shiftsOld as $shift) {
            //check estates to change
            Estate::whereIn('id', $shift->temp_changes)->update(['supervisor_user_id' => $shift->user_id]);

            //delete shift
            $shift->delete();
        }
    }
}
