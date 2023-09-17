<?php

namespace App\Http\Livewire\Student;

use Livewire\Component;
use Illuminate\Support\Str;
use App\Models\StudentNotification;

class StudentNotificationsComponent extends Component
{
    public $title, $slug, $content, $in_operation;
    
    //update purpose
    public $is_edit, $note_id;

    protected $rules = [
        'title' =>  'required|string',
        'slug' =>  'required|string',
        'content'   =>  'required'
    ];

    public function mount()
    {
        $this->title = '';
        $this->slug = '';
        $this->content = '';
        $this->is_edit = false;
        $this->in_operation = false;
        $this->note_id = 0;
    }

    public function generateSlug()
    {
        $this->slug = Str::slug($this->title);
    }

    public function submit()
    {
        if(!$this->in_operation){
            $data = $this->validate();
            $this->in_operation = true;
            if($this->is_edit) $this->updateNotification($data);
            else $this->createNotification($data);
        }
    }

    public function createNotification($data)
    {
        if(StudentNotification::create($data))
        session()->flash('success_toast', 'Notification created!');

        else session()->flash('error_toast', 'Unable to create notification!');

        $this->addNew();
    }

    public function editNotification($id)
    {
        $this->is_edit = true;
        $this->note_id = $id;
        if($note = StudentNotification::find($this->note_id)){
            $this->title = $note->title;
            $this->slug = $note->slug;
            $this->content = $note->content;
            $this->emit('setContent', $this->content);
        }
    }

    public function addNew()
    {
        $this->emit('setContent', '');
        $this->mount();
    }

    public function deleteNotification(StudentNotification $notification)
    {
        $notification->delete();

        $this->addNew();
    }   

    public function updateNotification($data)
    {
        if($this->note_id){
            $notification = StudentNotification::find($this->note_id);
            if(!$notification) return session()->flash('warning_toast', 'Notification not found!');
            if($notification->update($data))
            session()->flash('success_toast', 'Notification updated!');
    
            else session()->flash('error_toast', 'Unable to update notification!');
        }
        else session()->flash('error_toast', 'Error occured!');
        $this->addNew();
    }


    public function render()
    {
        $notifications = StudentNotification::latest()->paginate(PAGINATE_SIZE);
        return view('livewire.student.student-notifications-component', compact('notifications'));
    }
}
