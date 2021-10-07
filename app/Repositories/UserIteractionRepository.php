<?php

namespace App\Repositories;

use App\Models\Reaction;
use App\Models\User;
use App\Models\UserReaction;
use Validator;

class UserIteractionRepository
{

    public function react($data)
    {

        $validator = Validator::make($data, [
            'userReactedId' => 'required|int',
            'reactionId' => 'required|int',
        ]);

        if ($validator->fails()) {
            $erro = $validator->errors()->first();
            abort(response()->json(['message' => $erro], 409));
        }

        $userReaction = new UserReaction();
        $find = $userReaction->where('user_react_id', auth()->user()->id)
            ->where('user_reacted_id', $data['userReactedId'])
            ->first();

        if (empty($find)) {

            $userReaction = new UserReaction();
            $userReaction->create(array(
                'user_react_id' => auth()->user()->id,
                'user_reacted_id' => $data['userReactedId'],
                'reaction_id' => $data['reactionId'],
            ));
            $reaction = new Reaction();
            $reactionName = $reaction->select('name')->where('id', $data['reactionId'])->first();

            $message = [
                'message' => 'Reagido com' . ' ' . $reactionName->name,
            ];

            return $message;

        } else {
            $userReaction->where('id', $find->id)
                ->update(['reaction_id' => $data['reactionId']]);
            
            $reaction = new Reaction();
            $reactionName = $reaction->select('name')->where('id', $data['reactionId'])->first();

            $message = [
                'message' => 'Reagido com' . ' ' . $reactionName->name,
            ];

            return $message;

        }
    }

    public function search($data)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string',
        ]);

        if ($validator->fails()) {
            $erro = $validator->errors()->first();
            abort(response()->json(['message' => $erro], 409));
        }

        $users = new User();
        $query = $users->select('id', 'name', 'last_name', 'gender', 'bio')
            ->where(function ($q) use ($data) {
                $q->where('name', 'like', '%' . $data['name'] . '%')
                    ->orWhere('last_name', 'like', '%' . $data['name'] . '%');
            })
            ->where('id', '<>', auth()->user()->id)
            ->distinct()
            ->get();
        return $query;

    }
}
