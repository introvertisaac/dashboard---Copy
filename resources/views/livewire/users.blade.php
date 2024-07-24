<div>

    <div class="flex items-center flex-wrap justify-between gap20 mb-5">
        <div class="mb-20">
            <h4>Users</h4>
            <p>Here is a list of all the users</p>
        </div>

        <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
            <a wire:click="create()" class="button">Create User</a>
        </ul>
    </div>

    @include('partials.alerts')

    <div class="flex items-center justify-between gap10 flex-wrap mb-20">
        <div class="wg-filter flex-grow">
            <form class="form-search bg-white">
                <fieldset class="name">
                    <input spellcheck="false" type="text" wire:model.live="search" id="search"
                           placeholder="Search for a user" class="bg-white"
                           name="name" tabindex="2" value="" aria-required="true" required="">
                </fieldset>
                <div class="button-submit">
                    <button class="" type="submit"><i class="icon-search"></i></button>
                </div>
            </form>
        </div>
    </div>

    <div class="wg-box">

        @if (!count($users))

            <div class="block-warning type-custom w-full fs-4 my-3">
               No data found
            </div>

        @endif


        <div class="table-wrapper">
            <table class="table">
                <thead>
                <tr>
                    <th>Customer</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>

                @foreach($users as $user)
                    <tr>
                        <td>{{$user->customer->name}}</td>
                        <td>{{$user->name}}</td>
                        <td>{{$user->username}}</td>
                        <td><a class="" wire:click="edit('{{$user->uuid}}')">Manage</a></td>
                    </tr>
                @endforeach


                </tbody>
            </table>

            {{ $users->links() }}
        </div>

    </div>

</div>
