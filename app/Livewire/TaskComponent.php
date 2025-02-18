<?php

namespace App\Livewire;

use App\Models\Task;
use App\Models\User;
use Livewire\Component;

class TaskComponent extends Component
{
    public $tasks = [];
    public $title;
    public $description;
    public $id;
    public $miTarea = null;
    public $modal = false;
    public $users = [];
    public $user_id;
    public $permiso;
    public $modalShare = false;

    public function mount()
    {
        $this->tasks = $this->getTasks()->sortByDesc('id');
        $this->users = User::where('id', '!=', auth()->user()->id)->get();
    }
    public function render()
    {
        return view('livewire.task-component');
    }

    public function renderAllTasks()
    {
        $this->tasks = $this->getTasks()->sortByDesc('id');
    }

    public function getTasks(){
        $user = auth()->user();
        $misTareas = Task::where('user_id', auth()->user()->id)->get();
        $misSharedTask = $user->sharedTasks()->get();

        return $misSharedTask->merge($misTareas);
    }

    private function clearFields()
    {
        $this->title = '';
        $this->description = '';
    }

    public function openCreateModal(Task $task = null)
    {
        $this->isUpdating = false;
        if($task){
            $this->miTarea = $task;
            $this->title = $task->title;
            $this->description = $task->description;
            $this->id = $task->id;
        }else{
            $this->clearFields();
        }
        $this->modal = true;
    }
    public function closeCreateModal()
    {
        $this->modal = false;
    }

    public function createorUpdateTask()
    {
        if ($this->miTarea->id) {
            $task = Task::find($this->miTarea->id);
            $task->update([
                'title' => $this->title,
                'description' => $this->description,
            ]);
        }else{
            $top = [
                'title' => $this->title,
                'description' => $this->description,
                'user_id' => auth()->user()->id,
            ];
            $task = Task::create($top);
        }

        $this->clearFields();
        $this->modal = false;
        $this->tasks = $this->getTasks()->sortByDesc('id');
    }

    public function deleteTask(Task $task){
        $task->delete();
        $this->tasks = $this->getTasks()->sortByDesc('id');
    }

    public function openShareModal(Task $task)
    {
        $this->miTarea = $task;
        $this->modalShare = true;
    }

    public function closeShareModal()
    {
        $this->modalShare = false;
    }

    public function shareTask(){
        $task = Task::find($this->miTarea->id);
        $user = User::find($this->user_id);
        $user->sharedTasks()->attach($task->id, ['permissions' => $this->permiso]);
        $this->closeShareModal();
        $this->tasks = $this->getTasks()->sortByDesc('id');
    }

    public function unshareTask(Task $task)
    {
        $user = User::find(auth()->user()->id);
        $user->sharedTasks()->detach($task->id);
        $this->tasks = $this->getTasks()->sortByDesc('id');
    }

}
