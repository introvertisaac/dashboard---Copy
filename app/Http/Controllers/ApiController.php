<?php

namespace App\Http\Controllers;

use App\Http\Requests\BankAccountRequest;
use App\Http\Requests\BusinessRequest;
use App\Http\Requests\IdRequest;
use App\Http\Requests\KrapinRequest;
use App\Http\Requests\PhoneRequest;
use App\Http\Requests\VehiclePlateRequest;
use App\Models\Search;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use function PHPUnit\Framework\isJson;

class ApiController extends Controller
{
    //

    public function id(IdRequest $request)
    {
        $idnumber = $request->get('idnumber');

        $transaction = Search::newSearch($request->user(), 'national_id', $idnumber);

        if ($transaction) {

            $call_response = check_call('national_id', $idnumber);

            if (is_array($call_response)) {

                $transaction->update([
                    'response' => $call_response
                ]);

                return response()->json([
                    'success' => true,
                    'response_code' => 200,
                    'message' => 'ID Details Fetched Successfully',
                    'data' => $call_response,
                    'request_id' => $transaction->search_uuid
                ]);

            }

        }

        return $this->low_balance_response();

    }

    public function alienid(IdRequest $request)
    {
        $idnumber = $request->get('idnumber');

        $transaction = Search::newSearch($request->user(), 'alien_id', $idnumber);


        if ($transaction) {

            $call_response = check_call('alien_id', $idnumber);

            if (is_array($call_response)) {

                $transaction->update([
                    'response' => $call_response
                ]);

                return response()->json([
                    'success' => true,
                    'response_code' => 200,
                    'message' => 'Alien ID Details Fetched Successfully',
                    'data' => $call_response,
                    'request_id' => $transaction->search_uuid
                ]);

            }

        }

        return $this->low_balance_response();

    }


    public function krapin(KrapinRequest $request)
    {
        $idnumber = $request->get('pinnumber');

        $transaction = Search::newSearch($request->user(), 'kra', $idnumber);


        if ($transaction) {

            $call_response = check_call('kra', $idnumber);

            if (is_array($call_response)) {

                $transaction->update([
                    'response' => $call_response
                ]);

                return response()->json([
                    'success' => true,
                    'response_code' => 200,
                    'message' => 'KRA Pin Details Fetched Successfully',
                    'data' => $call_response,
                    'request_id' => $transaction->search_uuid
                ]);

            }

        }

        return $this->low_balance_response();

    }

    public function dl(IdRequest $request)
    {
        $idnumber = $request->get('idnumber');

        $transaction = Search::newSearch($request->user(), 'dl', $idnumber);


        if ($transaction) {

            $call_response = check_call('dl', $idnumber);
            if (is_array($call_response)) {

                $transaction->update([
                    'response' => $call_response
                ]);

                return response()->json([
                    'success' => true,
                    'response_code' => 200,
                    'message' => 'DL Checked Successfully',
                    'data' => $call_response,
                    'request_id' => $transaction->search_uuid
                ]);

            }

        }

        return $this->low_balance_response();

    }

    public function bank(BankAccountRequest $request)
    {
        $bank_id = $request->get('bankid');
        $account = $request->get('account');

        $bank_account = $bank_id . '-' . $account;

        $transaction = Search::newSearch($request->user(), 'bank', $bank_account);

        if ($transaction) {


            $call_response = check_call('bank', $bank_account);

            if (is_array($call_response)) {

                $transaction->update([
                    'response' => $call_response
                ]);


                $fetched_name = Arr::get($call_response, 'name');

                if (strlen($fetched_name) > 4) {

                    return response()->json([
                        'success' => true,
                        'response_code' => 200,
                        'message' => 'Bank Account Checked Successfully',
                        'data' => $call_response,
                        'request_id' => $transaction->search_uuid
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'response_code' => 503,
                        'message' => 'Unable to retrieve',
                        'data' => $call_response,
                        'request_id' => $transaction->search_uuid
                    ]);

                }


            }

        }

        return $this->low_balance_response();

    }


    public function vehicleplate(VehiclePlateRequest $request)
    {
        $plate = $request->get('plate');

        $transaction = Search::newSearch($request->user(), 'plate', $plate);


        if ($transaction) {

            $call_response = check_call('plate', $plate);

            if (is_array($call_response)) {

                $transaction->update([
                    'response' => $call_response
                ]);

                return response()->json([
                    'success' => true,
                    'response_code' => 200,
                    'message' => 'Vehicle Plate Checked Successfully',
                    'data' => $call_response,
                    'request_id' => $transaction->search_uuid
                ]);

            }

        }

        return $this->low_balance_response();

    }


    public function low_balance_response(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => false,
            'response_code' => 402,
            'message' => 'Low credit balance, kindly topup account',
            'data' => [],
            'request_id' => Str::uuid()
        ]);
    }

    public function business(BusinessRequest $request)
    {
        $businessnumber = $request->get('regnumber');

        $transaction = Search::newSearch($request->user(), 'brs', $businessnumber);

        if ($transaction) {

            $call_response = FetchController::business($businessnumber);

            if (isJson($call_response)) {

                $call_response_decode = json_decode($call_response, true);
                $records = Arr::get($call_response_decode, 'records.0');

                $transaction->update([
                    'response' => $records
                ]);

                return response()->json([
                    'success' => true,
                    'response_code' => 200,
                    'message' => 'Business Details Fetched Successfully',
                    'data' => $records,
                    'request_id' => $transaction->search_uuid
                ]);

            }

        }

        return $this->low_balance_response();

    }

    public function balance(Request $request)
    {
        $user = $request->user();

        $customer = $user->customer;
        $wallet = $customer->wallet;
        $wallet_balance = $wallet->balance;

        return response()->json([
            'success' => true,
            'response_code' => 200,
            'message' => 'Account Balance Fetched Successfully',
            'data' => [
                'balance' => $wallet_balance,
                'currency' => 'KES'
            ],
            'request_id' => Str::uuid()
        ]);

    }

    public function banks(Request $request)
    {

        $banks = '[
            {"id":"1","name":"KCB"},
{"id":"2","name":"Standard Chartered Bank"},
{"id":"3","name":"Absa Bank"},
{"id":"4","name":"Bank of India"},
{"id":"5","name":"Bank of Baroda"},
{"id":"6","name":"NCBA"},
{"id":"7","name":"Prime Bank"},
{"id":"8","name":"Co-operative Bank"},
{"id":"9","name":"National Bank"},
{"id":"10","name":"M-Oriental"},
{"id":"11","name":"Citi Bank"},
{"id":"12","name":"Habib Bank AG Zurich"},
{"id":"13","name":"Middle East Bank"},
{"id":"14","name":"Bank of Africa"},
{"id":"15","name":"Consolidated Bank"},
{"id":"16","name":"Credit Bank"},
{"id":"17","name":"Access Bank"},
{"id":"18","name":"Stanbic Bank"},
{"id":"19","name":"ABC Bank"},
{"id":"20","name":"Eco Bank"},
{"id":"21","name":"SPIRE Bank"},
{"id":"22","name":"Paramount"},
{"id":"23","name":"Kingdom Bank"},
{"id":"24","name":"Guaranty Trust Bank (GT Bank)"},
{"id":"25","name":"Victoria Bank"},
{"id":"26","name":"Guardian Bank"},
{"id":"27","name":"I&M Bank"},
{"id":"28","name":"Development Bank"},
{"id":"29","name":"SBM"},
{"id":"30","name":"Housing finance"},
{"id":"31","name":"Diamond Trust Bank (DTB)"},
{"id":"32","name":"Mayfair Bank"},
{"id":"33","name":"Sidian Bank"},
{"id":"34","name":"Equity Bank"},
{"id":"35","name":"Family Bank"},
{"id":"36","name":"Gulf African Bank"},
{"id":"37","name":"First Community Bank"},
{"id":"38","name":"DIB Bank"},
{"id":"39","name":"UBA Bank"},
{"id":"40","name":"KWFT"},
{"id":"41","name":"Faulu Bank"},
{"id":"42","name":"Post Bank"}
]';
        $data = json_decode($banks, true);


        return response()->json([
            'success' => true,
            'response_code' => 200,
            'message' => 'BankIDs List Fetched Successfully',
            'data' => $data,
            'request_id' => Str::uuid()
        ]);

    }

}
