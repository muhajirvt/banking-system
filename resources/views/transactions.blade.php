<div>
    <button type="button" class="btn btn-primary" style="float:right" data-caste="commonModalContent" data-url="{{ route('fund.transfer.form') }}" onclick="fetchPage(this)">
        Add
    </button>
</div>
<h2>Transactions</h2>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Sl No</th>
            <th>Sender</th>
            <th>Receiver</th>
            <th>Amount</th>
            <th>Currency</th>
            <th>Exchange Rate</th>
            <th>Convert Amount</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($transactions as $key => $transaction)
            <tr>
                <td>{{ $key+1 }}</td>
                <td>{{ $transaction->sender_name }}</td>
                <td>{{ $transaction->receiver_name }}</td>
                <td>{{ $transaction->amount }}</td>
                <td>{{ $transaction->currency }}</td>
                <td>{{ $transaction->exchange_rate }}</td>
                <td>{{ $transaction->convert_amount }}</td>
                <td>{{ $transaction->created_at }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="8" align="center">No transcation found.</td>
            </tr>
        @endforelse
    </tbody>
</table>