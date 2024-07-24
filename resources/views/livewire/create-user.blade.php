<div>

    <div class="flex items-center flex-wrap justify-between gap20 mb-5">
        <div class="mb-20">


            <div class="d-flex justify-content-between align-items-center mb-4">
                <a wire:click="list()" class="back-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor"
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="feather feather-chevron-left">
                        <polyline points="15 18 9 12 15 6"></polyline>
                    </svg>
                </a>
                <div class="ml-6">
                    <h4>Create New User</h4>
                    <p>Fill in the form below to create a user</p>
                </div>
            </div>


        </div>

    </div>

    @include('livewire.user-form', ['action'=>'new_user'])

</div>
