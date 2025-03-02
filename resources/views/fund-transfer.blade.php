<div class="modal-header">   
        <h5 class="modal-title">Fund Transfer</h5>
  </div>
  <form method="POST" id="transferForm" url="{{route('fund.transfer')}}">
  @csrf

  <div class="modal-body">
        <div>
            <label>From</label>
            <select name="sender_id" class="form-control">
                @foreach ($fromAccounts as $account)
                    <option value="{{ $account->id }}">{{ $account->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label>Account Number</label>
            <input type="text" name="account_number" value="" class="form-control">
        </div>
        <div>
            <label>Currency</label>
            <select name="currency" class="form-control">
                <option value="USD">USD</option>
                <option value="GBP">GBP</option>
                <option value="EUR">EUR</option>
            </select>
        </div>
        <div>
            <label>Amount</label>
            <input type="text" name="amount" value="" class="form-control">
        </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-primary" onclick="commonAjax($('#transferForm').attr('url'), 'POST', $('#transferForm').serialize())">Save changes</button>
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
</div>
</form>

<script>
    $('#commonModal').modal('show');
</script>