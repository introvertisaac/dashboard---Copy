<?php

namespace App\Http\Controllers;

use App\Http\Requests\BankAccountRequest;
use App\Http\Requests\BusinessRequest;
use App\Http\Requests\CollateralRequest;
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
    /**
     * National ID
     *
     * Returns the details associated with the submitted national ID number, ensuring the accuracy and validity of the information provided.
     *
     * @response array{success:true, response_code:200, message: "ID Details Fetched Successfully", data:array{"first_name":"JANE","last_name":"DOE","other_name":"SMITH","name":"DOE JANE SMITH","gender":"Female","dob":"1990-01-20","citizenship":"Kenyan","id_number":"12345678","serial_no":"987654321","valid":true}, request_id:"00000000-0000-0000-0000-000000000000"}
     *
     */
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

            }else{

                $transaction->update([
                    'response' => $call_response
                ]);

                return response()->json([
                    'success' => true,
                    'response_code' => 204,
                    'message' => 'ID Details Fetched Successfully',
                    'data' => $call_response,
                    'request_id' => $transaction->search_uuid
                ]);

            }



        }

        return $this->low_balance_response();

    }


    /**
     * Alien ID
     *
     * Returns the details associated with the submitted alien ID number, ensuring the accuracy and validity of the information provided.
     *
     * @response array{success:true, response_code:200, message: "Alien ID Details Fetched Successfully",data:array{"first_name":"FIRSTNAME","last_name":"LASTNAME","other_name":"OTHERNAME","name":"LASTNAME FIRSTNAME OTHERNAME","gender":"Female","dob":"yyyy-mm-dd","citizenship":"Alien","id_number":"52425261","serial_no":"3444124","valid":true}, request_id:"39d81b8f-fcb7-49ff-9405-0ec1c312764e"}
     *
     */
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

            }else{

                $transaction->update([
                    'response' => $call_response
                ]);

                return response()->json([
                    'success' => true,
                    'response_code' => 204,
                    'message' => 'Alien ID Details Fetched Successfully',
                    'data' => $call_response,
                    'request_id' => $transaction->search_uuid
                ]);

            }

        }

        return $this->low_balance_response();

    }

    /**
     * KRA PIN
     *
     * Returns the details associated with the submitted KRA PIN, ensuring the accuracy and validity of the taxpayer's information provided.
     *
     * @response array{success:true,response_code:200,message:"KRA Pin Details Fetched Successfully",data:array{Business_Certificate_Id:"XXXXXXXXX",Email_Addresses:"XXXXXXX@XXXXXXX.XXX",Locality:"XXXXX XX XXXXXXX",PINNo:"XXXXXXXXXXX",Partnership:"X",Paye:"X",Station:"XXXXX XX XXXXXXX",TaxpayerName:"XXXXXXXX XXXXXXX",Tot:"X",Trading_Business_Name:"XXXXXXXX XXXXXXX",Vat:"X"},request_id:"XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX"}
     *
     */
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

            }else{

                $transaction->update([
                    'response' => $call_response
                ]);

                return response()->json([
                    'success' => true,
                    'response_code' => 204,
                    'message' => 'KRA Pin Details Fetched Successfully',
                    'data' => $call_response,
                    'request_id' => $transaction->search_uuid
                ]);

            }

        }

        return $this->low_balance_response();

    }


    /**
     * Driving License
     *
     * Returns the details associated with the submitted driving license number, ensuring the accuracy and validity of the license holder's information provided.
     *
     * @response array{success:true,response_code:200,message:"DL Checked Successfully",data:array{data:array{points:"XX",national_id:"XXXXXXXX",full_name:"XXXXXXXX XXXXXXX XXXXX",address:"XXXXXXX",status:"XXXXX",date_of_issue:"XXXX-XX-XX",foreignConversionDLNumber:null,date_of_expiry:"XXXX-XX-XX",smartDLBookingTestCenter:"XXX",foreignConversionIssueDate:null,phoneNumber:"XXXXXXXXXXXX",city:"XXXXXXX",kra:"XXXXXXXXXX",pdl_number:null,postalAddress:null,date_of_birth:"XX-XXX-XX",id_type:"XXXXXXX",smartDLBookingStatusCd:"XX:XX:XX--XX:XX:XX",hasSmartDl:"XXXXXXXXXXXXXXXXXXXX",foreignConversionOrganization:null,license_number:"XXXXXXXXXX",blood_group:"XXXXXXX",sex:"X",foreignConversionExpiryDate:null,smartDlChipId:null,smartDLBookingDate:"XX-XXX-XX",email:"X@XXXXX.XXX",dlclass:"X",smartDLBookingStatus:"XXXXXX",foreignConversionDtlConversionType:null,smartDLBookingStartDate:"XX-XXX-XX",nationality:"XX",interim_number:"XXXXXXXXX"},status:"ok"},request_id:"XXXXXXXXXXXXXXXXXXXXXXXXXXXXX"}
     *
     *
     */
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

            }else{

                $transaction->update([
                    'response' => $call_response
                ]);

                return response()->json([
                    'success' => true,
                    'response_code' => 204,
                    'message' => 'DL Checked Successfully',
                    'data' => $call_response,
                    'request_id' => $transaction->search_uuid
                ]);

            }

        }

        return $this->low_balance_response();

    }

    /**
     * Bank Account
     *
     * Returns the name of the bank account associated with the submitted account details
     *
     *
     * @response array{success:true,response_code:200,message:"Bank Account Checked Successfully",data:array{name:"XXXXXX XXXXX XXXXXXX",account_number:"XXXXXXXXXX",bank_name:"XXX"},request_id:"XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX"}
     *
     */
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

    /**
     * Vehicle
     *
     * Returns the vehicle details associated with the submitted number plate,
     *
     *
     * @response array{success:true,response_code:200,message:"Vehicle Plate Checked Successfully",data:array{Details:"XXXXXXX",Status:"XX",caveat:array[],chassisNumber:"XXXXXXXXXXXXXXXXXX",inspection:array[],owner:array[],regNo:"XXXXXXX",vehicle:array{ChassisNo:"XXXXXXXXXXXXXXXXXX",bodyColor:"XXXXX",bodyType:"XXXXXX",carMake:"XXXX",carModel:"XX",dutyAmount:XXXXXXX,dutyDate:"",dutyStatus:"XXXX",engineCapacity:XXXX,engineNumber:"XXX - XXXXXX",entry:array{CPC:"XX",importer_pin:"XXXXXXXXXX",number:"XXXXXXXXXXXXXXXXX"},fuel_type:"XX",grossweight:XXXX,logbookNumber:array{LOGBOOK_NUMBER:"XXXXXXXXXXXXXXX",SERIAL_NO:"XXXXXXXXX"},passengerCapacity:X,purpose:"XXXXXXX",regNo:"XXXXXXX",regStatus:"XXXXXXXXXX",registrationDate:"XXXX-XX-XX",tareweight:XXXX,vehiceType:"XXXXXXXXXXXXX",yearOfManufacture:"XXXX"}},request_id:"XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX"}
     *
     */
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

            }else{

                $transaction->update([
                    'response' => $call_response
                ]);

                return response()->json([
                    'success' => true,
                    'response_code' => 204,
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


    /**
     * Business
     *
     * Returns the business details associated with the submitted registration number
     *
     *
     * @response array{success:true,response_code:200,message:"Business Details Fetched Successfully",data:array{status:"XXXXXXXXXX",registration_date:"XX XXXX XXXX",postal_address:"XXXXX - XXXXX",physical_address:"XXXXX-XXXXXXXXXX/XXX & XXX, XXXXXXX, Fl: Xst floor, Room/Door: X, XXXXX-XXXXXXXXXX/XXX & XXX, XXXXXXX",phone_number:"+XXXXXXXXXXXX",kra_pin:"XXXXXXXXXXX",email:"XXXXXXXX@XXXXX.XXX",business_name:"XXXXXXXX XXXXXXXX XXXXX XXXXXXX",partners:array{}  },request_id:"XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX"}
     *
     */
    public function business(BusinessRequest $request)
    {
        $businessnumber = $request->get('regnumber');

        $transaction = Search::newSearch($request->user(), 'brs', $businessnumber);

        if ($transaction) {

            $call_response = check_call('brs', $businessnumber);

            if (isJson($call_response)) {

                $records = Arr::get($call_response, 'records.0');

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

            }else{

                $transaction->update([
                    'response' => $call_response
                ]);

                return response()->json([
                    'success' => true,
                    'response_code' => 204,
                    'message' => 'Business Details Fetched Successfully',
                    'data' => $call_response,
                    'request_id' => $transaction->search_uuid
                ]);

            }

        }

        return $this->low_balance_response();

    }


    /**
     * Balance
     *
     * Returns account balance of your customer account
     *
     */
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
                'balance' => intval($wallet_balance),
                'currency' => 'KES'
            ],
            'request_id' => Str::uuid()
        ]);

    }


    /**
     * Bank List.
     *
     * List banks with respective BankIDs for use on the bank account name verification
     *
     */
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


    /**
     * Collateral
     *
     * Returns the details associated with an MPSR item verifying if it's in use as collateral. Example of serial no include vehicle chassis number
     *
     * @response array{success:true,response_code:200,message:"Collateral Check Fetched Successfully",data:array{},request_id:"XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX"}
     *
     */
    public function collateral(CollateralRequest $request)
    {
        $serialno = $request->get('serialno');

        $transaction = Search::newSearch($request->user(), 'collateral', $serialno);


        if ($transaction) {

            $call_response = check_call('collateral', $serialno);

            if (is_array($call_response)) {

                $transaction->update([
                    'response' => $call_response
                ]);

                return response()->json([
                    'success' => true,
                    'response_code' => 200,
                    'message' => 'Collateral Check Fetched Successfully',
                    'data' => $call_response,
                    'request_id' => $transaction->search_uuid
                ]);

            }else{

                $transaction->update([
                    'response' => $call_response
                ]);

                return response()->json([
                    'success' => true,
                    'response_code' => 204,
                    'message' => 'Collateral Check Fetched Successfully',
                    'data' => $call_response,
                    'request_id' => $transaction->search_uuid
                ]);

            }

        }

        return $this->low_balance_response();

    }


}
