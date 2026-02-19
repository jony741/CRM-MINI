<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('customers.index') }}" class="mr-4 text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $customer->name }}
                </h2>
                <x-status-badge :status="$customer->status" class="ml-3" />
            </div>
            <div class="flex space-x-2">
                @can('update', $customer)
                    <a href="{{ route('customers.edit', $customer) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Edit
                    </a>
                @endcan
                @can('delete', $customer)
                    <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="inline" x-data="{ confirmDelete: false }">
                        @csrf
                        @method('DELETE')
                        <button type="button" @click="confirmDelete = true" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Delete
                        </button>
                        <div x-show="confirmDelete" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="confirmDelete = false"></div>
                                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                        <div class="sm:flex sm:items-start">
                                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                </svg>
                                            </div>
                                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                                <h3 class="text-lg leading-6 font-medium text-gray-900">Delete Customer</h3>
                                                <div class="mt-2">
                                                    <p class="text-sm text-gray-500">Are you sure you want to delete "{{ $customer->name }}"? This action can be undone.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">Delete</button>
                                        <button type="button" @click="confirmDelete = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Flash Messages -->
            @if(session('success'))
                <x-alert type="success" :message="session('success')" />
            @endif
            @if(session('error'))
                <x-alert type="error" :message="session('error')" />
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Customer Details -->
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Customer Details</h3>

                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        <a href="mailto:{{ $customer->email }}" class="text-indigo-600 hover:text-indigo-900">{{ $customer->email }}</a>
                                    </dd>
                                </div>

                                @if($customer->phone)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Phone</dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            <a href="tel:{{ $customer->phone }}" class="text-indigo-600 hover:text-indigo-900">{{ $customer->phone }}</a>
                                        </dd>
                                    </div>
                                @endif

                                @if($customer->company)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Company</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $customer->company }}</dd>
                                    </div>
                                @endif

                                @if($customer->address)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Address</dt>
                                        <dd class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $customer->address }}</dd>
                                    </div>
                                @endif

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                                    <dd class="mt-1">
                                        <x-status-badge :status="$customer->status" />
                                    </dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Assigned To</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $customer->assignee?->name ?? 'Unassigned' }}</dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Created By</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $customer->creator?->name ?? 'Unknown' }}</dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Created At</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $customer->created_at->format('M d, Y g:i A') }}</dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $customer->updated_at->format('M d, Y g:i A') }}</dd>
                                </div>
                            </dl>

                            @can('assign', $customer)
                                <div class="mt-6 pt-6 border-t border-gray-200">
                                    <h4 class="text-sm font-medium text-gray-900 mb-3">Quick Assign</h4>
                                    <form action="{{ route('customers.assign', $customer) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <select name="assigned_to" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm" onchange="this.form.submit()">
                                            <option value="">Unassigned</option>
                                            @foreach(App\Models\User::all() as $user)
                                                <option value="{{ $user->id }}" {{ $customer->assigned_to == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </form>
                                </div>
                            @endcan
                        </div>
                    </div>
                </div>

                <!-- Notes Section -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Notes</h3>

                            <!-- Add Note Form -->
                            @can('create', [App\Models\CustomerNote::class, $customer])
                                <form action="{{ route('customers.notes.store', $customer) }}" method="POST" class="mb-6" x-data="{ isPrivate: false }">
                                    @csrf
                                    <div class="mb-3">
                                        <x-input-label for="title" value="Title (optional)" />
                                        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title')" placeholder="Note title..." />
                                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                                    </div>
                                    <div class="mb-3">
                                        <x-input-label for="content" value="Note" />
                                        <textarea id="content" name="content" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Write your note here..." required>{{ old('content') }}</textarea>
                                        <x-input-error :messages="$errors->get('content')" class="mt-2" />
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <label class="flex items-center">
                                            <input type="checkbox" name="is_private" value="1" x-model="isPrivate" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                            <span class="ml-2 text-sm text-gray-600">Private note (only visible to you)</span>
                                        </label>
                                        <x-primary-button>Add Note</x-primary-button>
                                    </div>
                                </form>
                            @endcan

                            <!-- Notes List -->
                            @if($notes->count() > 0)
                                <div class="space-y-4">
                                    @foreach($notes as $note)
                                        <div class="border border-gray-200 rounded-lg p-4 {{ $note->is_private ? 'bg-yellow-50 border-yellow-200' : '' }}" x-data="{ editing: false }">
                                            <div class="flex justify-between items-start mb-2">
                                                <div>
                                                    @if($note->title)
                                                        <h4 class="font-medium text-gray-900">{{ $note->title }}</h4>
                                                    @endif
                                                    <div class="text-sm text-gray-500">
                                                        {{ $note->user?->name ?? 'Unknown' }} - {{ $note->created_at->diffForHumans() }}
                                                        @if($note->is_private)
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 ml-2">Private</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="flex space-x-2" x-show="!editing">
                                                    @can('update', $note)
                                                        <button @click="editing = true" class="text-sm text-indigo-600 hover:text-indigo-900">Edit</button>
                                                    @endcan
                                                    @can('delete', $note)
                                                        <form action="{{ route('customers.notes.destroy', [$customer, $note]) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this note?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-sm text-red-600 hover:text-red-900">Delete</button>
                                                        </form>
                                                    @endcan
                                                </div>
                                            </div>

                                            <!-- View Mode -->
                                            <div x-show="!editing" class="text-gray-700 whitespace-pre-line">{{ $note->content }}</div>

                                            <!-- Edit Mode -->
                                            @can('update', $note)
                                                <form x-show="editing" action="{{ route('customers.notes.update', [$customer, $note]) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="mb-3">
                                                        <x-text-input name="title" type="text" class="block w-full" :value="$note->title" placeholder="Title (optional)" />
                                                    </div>
                                                    <div class="mb-3">
                                                        <textarea name="content" rows="3" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ $note->content }}</textarea>
                                                    </div>
                                                    <div class="flex items-center justify-between">
                                                        <label class="flex items-center">
                                                            <input type="checkbox" name="is_private" value="1" {{ $note->is_private ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                                            <span class="ml-2 text-sm text-gray-600">Private note</span>
                                                        </label>
                                                        <div class="flex space-x-2">
                                                            <button type="button" @click="editing = false" class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Cancel</button>
                                                            <x-primary-button type="submit">Update</x-primary-button>
                                                        </div>
                                                    </div>
                                                </form>
                                            @endcan
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 text-center py-8">No notes yet. Add the first note above.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
