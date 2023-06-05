<?php

namespace App\Http\Livewire\Chat;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Livewire\Component;

class CreateChat extends Component
{
    public $users;
    public $message = 'Hello';

    public function checkConversation($recieverId)
    {
        
        $checkedConversation = Conversation::where('receiver_id', auth()->user()->id)->where('sender_id', $recieverId)
        ->orWhere('receiver_id', $recieverId)->where('sender_id', auth()->user()->id)->get();

        if (count($checkedConversation) == 0) {
            $createConversation = Conversation::create([
                'receiver_id' => $recieverId,
                'sender_id' => auth()->user()->id,
                'last_time_message' => 0
            ]);

            $createMessage = Message::create([
                'conversation_id' => $createConversation->id,
                'sender_id' => auth()->user()->id,
                'receiver_id' => $recieverId,
                'body' => $this->message
            ]);

            $createConversation->last_time_message = $createMessage->created_at;
            $createConversation->save();
            dd($createMessage);
            
        } else if(count($checkedConversation) >= 1) {
            dd('conversation exist');
        }
        
    }

    public function render()
    {
        $this->users = User::where('id','!=', auth()->user()->id)->get();
        return view('livewire.chat.create-chat');
    }
}
