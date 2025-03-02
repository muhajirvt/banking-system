<div class="modal-header">
    @if($type == 1)
        <h5 class="modal-title">Add Account</h5>
        <button type="button" class="btn btn-primary" style="float:right" onclick="appendFields()">Add</button>
    @else
        <h5 class="modal-title">Edit Account</h5>
    @endif
  </div>
  <form method="POST" id="accountForm" url="{{route('add.update.account')}}">
  @csrf
  <input type="hidden" name="type" value="{{ $type }}">
  <input type="hidden" name="id" @if(!empty($account->id)) value="{{ $account->id }}" @else value="0" @endif>

  <div class="modal-body" id="accountFieldSection">
        <div>
            <label>User</label>
            <select name="user_id[]" class="form-control">
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" @if(!empty($account->user_id) && $account->user_id == $user->id) selected @endif>{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label>FullName</label>
            <input type="text" name="name[]" class="form-control" @if(!empty($account->name)) value="{{ $account->name }}"@endif>
        </div>
        <div>
            <label>DOB</label>
            <input type="date" name="dob[]" class="form-control" @if(!empty($account->dob)) value="{{ $account->dob }}"@endif>
        </div>
        <div>
            <label>Address</label>
            <textarea name="address[]" class="form-control">@if(!empty($account->address)){{ $account->address }}@endif</textarea>
        </div>
        <div>
            <label>Currency</label>
            <select name="currency[]" class="form-control">
                <option value="USD" @if(!empty($account->currency) && $account->currency == "USD") selected @endif>USD</option>
                <option value="GBP" @if(!empty($account->currency) && $account->currency == "GBP") selected @endif>GBP</option>
                <option value="EUR" @if(!empty($account->currency) && $account->currency == "EUR") selected @endif>EUR</option>
            </select>
        </div>
        <br>
  </div>
  <div id="appendFields" style="padding-left:20px">

  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-primary" onclick="commonAjax($('#accountForm').attr('url'), 'POST', $('#accountForm').serialize())">Save changes</button>
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
</div>
</form>

<script>
    $('#commonModal').modal('show');
    function appendFields(){
        var accountFieldSection = $('#accountFieldSection').html();
        $('#appendFields').append(accountFieldSection);
    }
</script>