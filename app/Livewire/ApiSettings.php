<?php

namespace App\Livewire;

use Illuminate\Support\Str;
use Livewire\Component;

class ApiSettings extends Component
{

    public $docs_url;
    public $api_client_id;
    public $api_secret;

    public function mount()
    {
        $this->docs_url = 'docs.identifyafrica.io';

        $customer = customer();
        $api_user = $customer->apiuser();

        $this->api_client_id = $api_user->username;
        $this->api_secret = $api_user->api_secret;
    }


    public function render()
    {
        return view('livewire.api-settings');
    }
}
