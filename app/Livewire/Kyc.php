<?php

namespace App\Livewire;

use App\Models\Customer;
use App\Models\Search;
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

    public $bank;


    public $methods = [];

    protected $paginationTheme = 'bootstrap';
    public $search = '';

    public function init_check($check)
    {
        $this->reset();
        $this->check_type = $check;

        $this->check_type_label = Arr::get(config('billing.services'), $check . '.label');
        $this->dispatch('openCheckInputModal', check: $check);

    }


    public function kyc_check()
    {
        $this->openModal();

        $query_value = $this->bank ? $this->bank . '-' . $this->check_number : $this->check_number;

        $transaction = Search::newSearch(user(), $this->check_type, $query_value, null, 'portal');

        if ($transaction) {

            $call_response = check_call($this->check_type, $query_value);

            if (is_array($call_response)) {

                $transaction->update([
                    'response' => $call_response
                ]);

                $this->balance_impact = ['Balance Before' => optional($transaction)->balance_before, 'Balance After' => optional($transaction)->balance_after];

                $this->check_result = ($call_response);
            }
            #Log::info($this->check_type, ['query' => $this->check_number, 'response' => $this->check_result]);
        } else {

            $this->check_result = [
                'Unable to perform search' => ['Reason' => 'Low credit']
            ];
        }


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
        $this->methods = \customer()->allowed_services;

        if (strlen($this->search)) {
            $listing = Search::currentCustomer()->latest()->where('search_param', 'like', '%' . $this->search . '%')
                ->take(10)
                ->paginate(10);
        } else {

            $listing = Search::currentCustomer()->latest()->paginate(5);
        }


        return view('livewire.kyc', compact('listing'));
    }
}
