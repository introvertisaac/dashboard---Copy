<?php

namespace App\Http\Controllers;

use App\Http\Requests\BankAccountRequest;
use App\Http\Requests\BusinessRequest;
use App\Http\Requests\CollateralRequest;
use App\Http\Requests\IdRequest;
use App\Http\Requests\KrapinRequest;
use App\Http\Requests\PhoneRequest;
use App\Http\Requests\VehiclePlateRequest;
use App\Services\AccountValidationService;
use App\Http\Requests\AccountValidationRequest;
use App\Models\Search;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
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
        Log::info('Controller input', [
            'all' => $request->all(),
            'input' => $request->input(),
            'raw' => $request->get('idnumber')
        ]);
    
        // Get and clean the ID number before making the API call
        $original_id = $request->get('idnumber');
        // Ensure we strip leading zeros for the API call
        $idnumber = ltrim($original_id, '0');
        
        Log::info('ID API Call', [
            'original_id' => $original_id,
            'processed_id' => $idnumber
        ]);
    
        $transaction = Search::newSearch($request->user(), 'national_id', $idnumber);
    
        if ($transaction instanceof Search) {
            // Log before making API call
            Log::info('ID API Call - Making external request', [
                'id' => $idnumber,
                'transaction_id' => $transaction->search_uuid,
                'request_ip' => $request->ip()
            ]);
    
            $call_response = check_call('national_id', $idnumber);
    
            // Log the API response
            Log::info('ID API Call - Received response', [
                'id' => $idnumber,
                'transaction_id' => $transaction->search_uuid,
                'response_type' => gettype($call_response),
                'is_array' => is_array($call_response),
                'request_ip' => $request->ip()
            ]);
    
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
            } else {
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
    
        // Log error cases
        Log::error('ID API Call - Transaction creation failed', [
            'id' => $idnumber,
            'transaction' => $transaction,
            'request_ip' => $request->ip()
        ]);
    
        return $this->error_handle($transaction);
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


        if ($transaction instanceof Search) {

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

            } else {

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

        return $this->error_handle($transaction);
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
    
        if ($transaction instanceof Search) {
            $call_response = check_call('kra', $idnumber);
    
            if (is_array($call_response)) {
                // Check if the response contains an error message
                if (isset($call_response['message']) && $call_response['message'] === 'Invalid PIN Number') {
                    $transaction->update([
                        'response' => $call_response
                    ]);
    
                    return response()->json([
                        'success' => true,
                        'response_code' => 204,
                        'message' => 'KRA Pin Details Fetched Successfully',
                        'data' => [],
                        'request_id' => $transaction->search_uuid
                    ]);
                }
    
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
            } else {
                $transaction->update([
                    'response' => $call_response
                ]);
    
                return response()->json([
                    'success' => true,
                    'response_code' => 204,
                    'message' => 'KRA Pin Details Fetched Successfully',
                    'data' => [],
                    'request_id' => $transaction->search_uuid
                ]);
            }
        }
    
        return $this->error_handle($transaction);
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


        if ($transaction instanceof Search) {

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

            } else {

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

        return $this->error_handle($transaction);

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

        if ($transaction instanceof Search) {


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

        return $this->error_handle($transaction);

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


        if ($transaction instanceof Search) {

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

            } else {

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

        return $this->error_handle($transaction);

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

        if ($transaction instanceof Search) {

            $call_response = check_call('brs', $businessnumber);

            if (isJson($call_response)) {

                $records = Arr::get($call_response, 'records.0');

                if(is_null($records)){

                    $records =  $call_response;
                }


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

            } else {

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

        return $this->error_handle($transaction);

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
 * Phone Number
 *
 * Returns the details associated with the submitted phone number and institution, ensuring the accuracy and validity of the information provided. For the Institution Code, use 63902 (Safaricom).
 *
 * @response array{success:true, response_code:200, message:"Phone Number Details Fetched Successfully", data:array{"name":"JANE DOE SMITH","phone":"+254700000000","institution":"Safaricom","reference":"79ed217f-9c3b-470b-9a5e-4859e873d005"}, request_id:"550e8400-e29b-41d4-a716-446655440000"}
 * @response array{success:false, response_code:424, message:"Phone number validation failed", data:array{}, request_id:"550e8400-e29b-41d4-a716-446655440000"} 
 */
public function validateAccount(AccountValidationRequest $request)
{
    $accountNumber = $request->get('account_number');
    $institutionCode = $request->get('institution_code');
    
    // Match billing.php config key
    $transaction = Search::newSearch($request->user(), 'phone', $accountNumber, [
        'institution_code' => $institutionCode
    ]);

    if (!($transaction instanceof Search)) {
        return $this->error_handle($transaction);
    }

    try {
        $call_response = check_call('phone', $accountNumber, [
            'institution_code' => $institutionCode
        ]);

        if ($call_response) {
            $transaction->update([
                'response' => $call_response
            ]);

            return response()->json([
                'success' => true,
                'response_code' => 200,
                'message' => 'Account Details Fetched Successfully',
                'data' => $call_response,
                'request_id' => $transaction->search_uuid
            ]);
        }

        return response()->json([
            'success' => false,
            'response_code' => 424,
            'message' => 'Account validation failed',
            'data' => [],
            'request_id' => $transaction->search_uuid
        ]);

    } catch (\Exception $e) {
        Log::error('Account Validation Error', [
            'error' => $e->getMessage(),
            'account' => $accountNumber,
            'institution' => $institutionCode,
            'search_id' => $transaction->id
        ]);

        $transaction->update([
            'response' => ['error' => $e->getMessage()]
        ]);

        return response()->json([
            'success' => false,
            'response_code' => 424,
            'message' => 'Account validation failed',
            'data' => [],
            'request_id' => $transaction->search_uuid
        ]);
    }
}


public function getAccountTestData($accountNumber, $institutionCode)
{
    // Test data for specific numbers we want to always return the same data for
    $fixedTestAccounts = [
        '0700000000' => [
            'name' => 'JOHN KARIUKI KAMAU',
            'account' => '+254703121492', 
            'institution' => 'Safaricom',
            'reference' => (string) Str::uuid()
        ],
        '0700000001' => [
            'name' => 'SARAH WANJIKU MAINA',
            'account' => '+254000000001',
            'institution' => 'Safaricom', 
            'reference' => (string) Str::uuid()
        ],
        '0700000002' => [
            'name' => 'PETER OCHIENG OTIENO',
            'account' => '+254000000002',
            'institution' => 'Safaricom',
            'reference' => (string) Str::uuid()
        ]
    ];

    // Format phone number to handle different formats
    $searchNumber = $accountNumber;
    if (str_starts_with($accountNumber, '0')) {
        $searchNumber = $accountNumber;
    } elseif (str_starts_with($accountNumber, '+254')) {
        $searchNumber = '0' . substr($accountNumber, 4);
    }

    // If it's one of our fixed test accounts, return that specific data
    if (isset($fixedTestAccounts[$searchNumber])) {
        return $fixedTestAccounts[$searchNumber];
    }

    // Validate phone number format
    if (!preg_match('/^(?:254|\+254|0)?(7\d{8})$/', $searchNumber, $matches)) {
        return [
            'name' => 'INVALID REQUEST',
            'account' => 'Please provide a valid phone number',
            'institution' => $this->getInstitutionName($institutionCode),
            'reference' => (string) Str::uuid()
        ];
    }

    // For any other valid phone number, return random test data
    $names = [
        'JANE DOE SMITH',
        'JOHN DOE SMITH',
        'MARY DOE SMITH',
        'JAMES DOE SMITH',
        'ELIZABETH DOE SMITH'
    ];

    // Return random name but consistent per phone number by using it as a seed
    $numericSeed = crc32($searchNumber);
    mt_srand($numericSeed);
    $randomName = $names[array_rand($names)];

    return [
        'name' => $randomName,
        'account' => '+254' . $matches[1],
        'institution' => $this->getInstitutionName($institutionCode),
        'reference' => (string) Str::uuid()
    ];
}

private function getInstitutionName($code)
{
    $institutions = [
        '63902' => 'Safaricom',
        '63903' => 'Airtel Money',
        '63904' => 'Telkom T-Kash'
    ];

    return $institutions[$code] ?? 'Unknown Institution';
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


        if ($transaction instanceof Search) {

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

            } else {

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

        return $this->error_handle($transaction);

    }

    public function ping(Request $request)
    {
        return response()->json([
            'success' => true,
            'response_code' => 200,
            'message' => 'API service running okay',
            // 'request_ip' => $request->ip(),
        ]);

    }


}
