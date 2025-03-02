<div>
    <button type="button" class="btn btn-primary" style="float:right" data-caste="commonModalContent" data-url="{{ route('add.update.popup.account') }}?type=1" onclick="fetchPage(this)">
        Add
    </button>
</div>
<h2>Accounts</h2>
<input type="text" class="form-control" id="search-account"style="width: fit-content" onkeyup="if (event.key === 'Enter' || event.keyCode === 13) performSearch(this.value)"> <br>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Sl No</th>
            <th>Name</th>
            <th>DOB</th>
            <th>Address</th>
            <th>Account Number</th>
            <th>Currency</th>
            <th>Balance</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($accounts as $key => $account)
            <tr>
                <td>{{ $key+1 }}</td>
                <td>{{ $account->name }}</td>
                <td>{{ $account->dob }}</td>
                <td>{{ $account->address }}</td>
                <td>{{ $account->account_number }}</td>
                <td>{{ $account->currency }}</td>
                <td>{{ $account->balance }}</td>
                <td>@if($account->status == 1) Active @else Inactive @endif</td>
                <td><button class="btn btn-primary" data-caste="commonModalContent" data-url="{{ route('add.update.popup.account') }}?type=2&id={{ $account->id }}" onclick="fetchPage(this)">Edit</button></td>
            </tr>
        @empty
            <tr>
                <td colspan="8" align="center">No accounts found.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<script>
    function performSearch(searchContent){
        var element = $("#pageContentSection");
        element.empty();
        $.ajax({
            url:  '{{ route('accounts') }}?search='+searchContent,
            type: "GET",
            success: function(response) {
                element.html(response);
            }
        });
    };
</script>