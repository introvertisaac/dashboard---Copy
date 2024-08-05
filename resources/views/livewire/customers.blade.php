<div>

    @include('partials.alerts')
    <div class="flex items-center flex-wrap justify-between gap20 mb-5">
        <div class="mb-20">
            <h4>Customers</h4>
            <p>Here is a list of all the customers</p>
        </div>

        <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10"><a wire:click="create()"
                                                                                   {{--href="{{route('customers.create')}}"--}}
                                                                                   class="button">Create Customer</a>
        </ul>
    </div>


    <div class="flex items-center justify-between gap10 flex-wrap mb-20">
        <div class="wg-filter flex-grow">
            <form class="form-search bg-white">
                <fieldset class="name">
                    <input spellcheck="false" type="text" wire:model.live="search" id="search"
                           placeholder="Search for a customer" class="bg-white"
                           name="name" tabindex="2" value="" aria-required="true" required="">
                </fieldset>
                <div class="button-submit">
                    <button class="" type="submit"><i class="icon-search"></i></button>
                </div>
            </form>
        </div>
    </div>

    <div class="wg-box">

        <div class="table-wrapper">
            <table class="table table-responsive">
                <thead>
                <tr>
                    <th class="w-25">Customer Name</th>
                    <th class="w-25">Email</th>
                    <th>Balance</th>
                    <th>Number of APIs</th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>

                @foreach($customers as $customer)
                    <tr>
                        <td>{{$customer->name}}</td>
                        <td>{{$customer->primary_email}}</td>
                        <td>{{$customer->balance_label}}</td>
                        <td>{{$customer->api_count}}</td>
                        <td><a class="" wire:click="edit('{{$customer->uuid}}')">Manage</a></td>
                        <td><a class="" wire:click="allocate('{{$customer->uuid}}')">Allocate Credit</a></td>
                        <td><a target="_blank"
                               href="{{route('dashboard.customer',['customer_uuid'=>$customer->uuid])}}">View
                                Dashboard</a></td>
                    </tr>
                @endforeach


                </tbody>
            </table>

            {{ $customers->links() }}
        </div>


        {{--      <div class="wg-table table-all-user">
                  <ul class="table-title flex gap20 mb-14">
                      <li>
                          <div class="body-title">Customer Name</div>
                      </li>
                      <li>
                          <div class="body-title">Phone Number</div>
                      </li>
                      <li>
                          <div class="body-title">Primary Email</div>
                      </li>

                      <li>
                          <div class="body-title">Number of APIs</div>
                      </li>
                      <li>
                          <div class="body-title">Actions</div>
                      </li>

                  </ul>
                  <ul class="flex flex-column">

                      @foreach($customers as $customer)
                          <li class="user-item gap14">
                              <div class="flex items-center justify-between gap20 flex-grow">
                                  <div class="body-text">{{$customer->name}}</div>
                                  <div class="body-text">{{$customer->phone}}</div>
                                  <div class="body-text">{{$customer->primary_email}}</div>
                                  <div class="body-text">{{$customer->api_count}}</div>
                                  <div class="list-icon-functionx">

                                      <a class=""
                                         wire:click="edit('{{$customer->uuid}}')" --}}{{-- #href="{{route('customers.edit',$customer)}}"--}}{{-->
                                          Manage</a>

                                  </div>
                              </div>
                          </li>
                      @endforeach

                  </ul>
              </div>


              {{ $customers->links() }}--}}

    </div>

</div>
