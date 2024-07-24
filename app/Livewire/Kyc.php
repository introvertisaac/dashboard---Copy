<?php

namespace App\Livewire;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Kyc extends Component
{

    public $check_number;
    public $check_type_label;
    public $check_type;
    public $check_result;
    public $balance_impact;


    public $methods = [
        'national_id' => ['input_label' => 'National ID Number', 'label' => 'National ID'],
        'alien_id' => ['input_label' => 'Alien ID Number', 'label' => 'Alien ID'],
        'plate' => ['input_label' => 'Vehicle Number Plate', 'label' => 'Vehicle Number Plate'],
        'dl' => ['input_label' => 'Driving License', 'label' => 'Driving License'],
        'kra' => ['input_label' => 'KRA Pin', 'label' => 'KRA Pin'],
        'brs' => ['input_label' => 'Company Registration', 'label' => 'BRS Verification'],
        //'bank' => ['input_label' => 'Bank Account Number', 'label' => 'Bank Account Number']
    ];

    public function init_check($check)
    {
        $this->reset();
        $this->check_type = $check;

        $this->check_type_label = Arr::get($this->methods, $check . '.label');
        $this->dispatch('openCheckInputModal',check: $check);

    }


    public function kyc_check()
    {
        $this->openModal();

        $response = check_call($this->check_type, $this->check_number);

        $this->balance_impact = ['Balance Before' => 200, 'Balance After' => 150];

        $this->check_result = ($response);


        Log::info($this->check_type, ['query' => $this->check_number, 'response' => $this->check_result]);


    }


    public function openModal()
    {
        $this->dispatch('openResultModal');
    }

    public function closeModal()
    {
        $this->dispatch('closeResultModal');
    }


    public function render()
    {
        return view('livewire.kyc');
    }
}
