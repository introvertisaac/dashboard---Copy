<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Str;
use Livewire\Component;

class ApiSettings extends Component
{

    public $docs_url;
    public $api_client_id;
    public $api_secret;
    public $showModal = false;

    public function mount()
    {
        $this->docs_url = 'docs.identifyafrica.io';

        $customer = customer();
        $api_user = $customer->apiuser();

        $this->api_client_id = $api_user->username;
        $this->api_secret = $api_user->api_secret;
    }

    public function confirmGenerateCredentials()
    {
        $this->showModal = true;
    }

    public function cancelGenerate()
    {
        $this->showModal = false;
    }


    public function generateCredentials()
    {

        $customer = customer();
        $api_user = $customer->apiuser();

        $new_username = 'api-'.strtolower(Str::random(4)).'-' . bin2hex(random_bytes(5));
        $new_password = Str::password(35, true, true, false);

        $api_user->update([
            'username' => $new_username,
            'api_secret' => $new_password,
        ]);

        $this->api_client_id = $new_username;
        $this->api_secret = $new_password;

        $this->showModal = false;
        session()->flash('message', "API credentials regenerated, New API Client ID and API Secret Key are available below");

        trail('api-regenerate', "API credentials regenerated", $customer);

        return redirect()->route('settings');

    }

    public function render()
    {
        return view('livewire.api-settings');
    }
}
