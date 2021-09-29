<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Reaction;
use App\Models\UserReaction;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserIteractionRepository
{

    public function react($data){

        $userReaction = new UserReaction();
        $find = $userReaction->where('user_react_id',auth()->user()->id)
                             ->where('user_reacted_id',$data['userReactedId'])
                             ->first();


        if (empty($find)){

            $userReaction = new UserReaction();
            $userReaction->create(array(
                'user_react_id' => auth()->user()->id,
                'user_reacted_id' => $data['userReactedId'], 
                'reaction_id' => $data['reactionId']
            ));
            $reaction = new Reaction();
            $reactionName = $reaction->select('name')->where('id', $data['reactionId'])->first();

            return response()->json('Reagido com'.' '.$reactionName->name, 200);

        }else{
            $userReaction->where('id',$find->id)
            ->update(['reaction_id' => $data['reactionId']]);
            
            $reaction = new Reaction();
            $reactionName = $reaction->select('name')->where('id', $data['reactionId'])->first();

            return response()->json('Reagido com'.' '.$reactionName->name, 200);
        }


    }
    
    
}