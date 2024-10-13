<tr>
    <td style="padding-left: {{ $level * 20 }}px;">
        @if ($customer->is_reseller)
            {!! status_label('r') !!}
        @else
            {!! status_label('c') !!}
        @endif
        {{ $customer->name }}
    </td>
    <td>{{ $customer->primary_email }}</td>
    <td>{!! status_label($customer->status) !!}</td>
    <td>{{ $customer->balance_label }}</td>
    <td>{{ $customer->api_count }}</td>
    <td>{{ $customer->childAccountsCount }}</td>
    <td>
            <a class="btn btn-primary" target="_blank" href="{{ route('dashboard.customer', ['customer_uuid' => $customer->uuid]) }}">View Dashboard</a>

    </td>
</tr>

@if($customer->childAccounts->isNotEmpty())
    @foreach($customer->childAccounts as $child)
        @include('partials.customer-row', ['customer' => $child, 'level' => $level + 1])
    @endforeach
@endif
