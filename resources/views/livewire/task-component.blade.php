<section wire:poll="renderAllTasks">
    <div class="max-w-7xl mx-auto">
        <div class="inline-block min-w-full py-2 align-middle">
            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                <button
                    wire:click="openCreateModal"
                    class="bg-blue-800 text-white px-6 py-1 rounded-md text-sm">Nuevo</button>
                <table class="min-w-full divide-y divide-gray-500">
                    <thead class="bg-base-50">
                    <tr>
                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-purple-600 sm:pl-6">Title</th>
                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-purple-600">Description</th>
                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-purple-600">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-500 bg-base">
                    @foreach($tasks as $task)
                    <tr>
                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-semibold text-white sm:pl-6">
                            {{ $task->title }}
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-purple-200 text-wrap">
                            {{ $task->description }}
                        </td>
                        <td>
                            <div class="flex flex-col md:flex-row space-y-2 md:space-x-2 items-center justify-between py-2">
                                @if(isset($task->pivot))
                                    <button class="bg-green-700 text-white px-6 py-1 rounded-md text-sm min-w-full md:w-auto"
                                            wire:click="unshareTask({{$task}})">
                                        Descompartir
                                    </button>
                                @endif
                                @if((isset($task->pivot) && $task->pivot->permissions === 'edit')
                                    || $task->user_id == auth()->user()->id )

                                    <button class="bg-indigo-800 text-white px-6 py-1 rounded-md text-sm min-w-full md:w-auto"
                                            wire:click="openCreateModal({{$task}})">
                                        Edit
                                    </button>

                                    <button class="bg-sky-700 text-white px-6 py-1 rounded-md text-sm min-w-full md:w-auto"
                                            wire:click="openShareModal({{$task}})">
                                        Compartir
                                    </button>
                                    <button class="bg-purple-950 text-white px-6 py-1 rounded-md text-sm min-w-full md:w-auto"
                                            wire:click="deleteTask({{$task}})"
                                            wire:confirm="Seguro?">
                                        Delete
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {{-- MODAL --}}
    @if($modal)
        <div class="fixed left-0 top-0 flex h-full w-full items-center justify-center bg-black bg-opacity-50 py-10">
            <div class="max-h-full w-full max-w-xl overflow-y-auto sm:rounded-2xl bg-white">
                <div class="w-full">
                    <div class="m-8 my-20 max-w-[400px] mx-auto text-slate-800">
                        <div class="mb-8">
                            <h1 class="mb-4 text-3xl font-extrabold">Create new task</h1>
                            <form>
                                <div class="mb-4">
                                    <label for="" class="block font-medium text-gray-900">Titulo</label>
                                    <input wire:model="title" type="text" name="title" class="block min-w-full bg-gray-50 border border-gray-300 rounded-md" autofocus />
                                </div>
                                <div class="mb-4">
                                    <label for="" class="block font-medium text-gray-900">Descripción</label>
                                    <input wire:model="description" type="text" name="description" class="block min-w-full bg-gray-50 border border-gray-300 rounded-md" />
                                </div>
                            </form>
                        </div>
                        <div class="flex flex-row space-x-4">
                            <button class="p-3 bg-black rounded-full text-white w-full font-semibold" wire:click.prevent="createorUpdateTask">{{ isset($miTarea->id) ? 'Actualizar':'Crear Tarea' }}</button>
                            <button class="p-3 bg-white border border-slate-600 rounded-full w-full font-semibold text-slate-900" wire:click.prevent="closeCreateModal">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    {{--END MODAL --}}
    {{--    modal shared--}}
    @if($modalShare)
    <div class="fixed left-0 top-0 flex h-full w-full items-center justify-center bg-black bg-opacity-50 py-10">
        <div class="max-h-full w-full max-w-xl overflow-y-auto sm:rounded-2xl bg-white">
            <div class="w-full">
                <div class="m-8 my-20 max-w-[400px] mx-auto text-slate-800">
                    <div class="mb-8">
                        <h1 class="mb-4 text-3xl font-extrabold">Shared tasks</h1>
                        <form>
                            <div class="mb-4">
                                <label for="" class="block font-medium text-gray-900">Compartir con:</label>
                                <select wire:model="user_id" class="block min-w-full bg-gray-50 border border-gray-300 rounded-md">
                                    <option value=""> - Seleccione un usuario - </option>
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="" class="block font-medium text-gray-900">Permisos</label>
                                <select wire:model="permiso" class="block min-w-full bg-gray-50 border border-gray-300 rounded-md">
                                    <option value="">-Seleccione un permiso-</option>
                                    <option value="edit">Editar</option>
                                    <option value="view">Ver</option>

                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="flex flex-row space-x-4">
                        <button class="p-3 bg-black rounded-full text-white w-full font-semibold" wire:click.prevent="shareTask"> Compartir </button>
                        <button class="p-3 bg-white border border-slate-600 rounded-full w-full font-semibold text-slate-900" wire:click.prevent="closeShareModal">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</section>
